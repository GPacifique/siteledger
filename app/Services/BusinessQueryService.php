<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use App\Models\User;
use App\Models\Tenant;

class BusinessQueryService
{
    protected $user;
    protected $tenantId;
    protected $userRole;

    public function __construct()
    {
        $this->user = Auth::user();

        // Safely get current tenant - may not exist in console commands or before middleware
        try {
            $this->tenantId = app()->bound('currentTenant') ? app('currentTenant')?->id : null;
        } catch (\Exception $e) {
            $this->tenantId = null;
        }

        $this->userRole = $this->user?->getRoleNames()->first();
    }

    /**
     * Get SQL query with role and tenant conditions
     */
    public function buildRoleBasedQuery(string $table, array $conditions = [], array $select = ['*']): QueryBuilder
    {
        $query = DB::table($table);

        // Apply tenant isolation for non-super-admin users
        if (!$this->isSuperAdmin()) {
            if ($this->tenantId) {
                $query->where('tenant_id', $this->tenantId);
            } else {
                // No tenant context for non-super-admin: return no rows
                $query->whereRaw('1=0');
            }
        }

        // Apply role-based conditions
        $this->applyRoleBasedConditions($query, $table);

        // Apply additional conditions
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query->select($select);
    }

    /**
     * Apply role-based data access conditions
     */
    protected function applyRoleBasedConditions(QueryBuilder $query, string $table): void
    {
        switch ($this->userRole) {
            case 'super-admin':
                // Super admin sees everything - no additional conditions
                break;

            case 'admin':
                // Admin sees all data within their tenant
                // Already handled by tenant_id condition
                break;

            case 'manager':
                // Manager sees data they manage or are assigned to
                $this->applyManagerConditions($query, $table);
                break;

            case 'accountant':
                // Accountant sees financial data and related records
                $this->applyAccountantConditions($query, $table);
                break;

            case 'employee':
                // Employee sees limited data - own records and public info
                $this->applyEmployeeConditions($query, $table);
                break;

            case 'client':
                // Client sees only their own data
                $this->applyClientConditions($query, $table);
                break;

            case 'viewer':
                // Viewer has read-only access to approved data
                $this->applyViewerConditions($query, $table);
                break;

            default:
                // Unknown role - very restricted access
                $query->where('id', '=', 0); // No results
                break;
        }
    }

    /**
     * Apply manager-specific conditions
     */
    protected function applyManagerConditions(QueryBuilder $query, string $table): void
    {
        $managedProjects = $this->getManagedProjectIds();
        $managedTeams = $this->getManagedTeamIds();

        switch ($table) {
            case 'projects':
                $query->where(function ($q) use ($managedProjects) {
                    $q->whereIn('id', $managedProjects)
                      ->orWhere('manager_id', $this->user->id)
                      ->orWhere('created_by', $this->user->id);
                });
                break;

            case 'tasks':
                $query->where(function ($q) use ($managedProjects) {
                    $q->whereIn('project_id', $managedProjects)
                      ->orWhere('assigned_to', $this->user->id)
                      ->orWhere('created_by', $this->user->id);
                });
                break;

            case 'employees':
            case 'users':
                $query->where(function ($q) use ($managedTeams) {
                    $q->whereIn('team_id', $managedTeams)
                      ->orWhere('manager_id', $this->user->id)
                      ->orWhere('id', $this->user->id);
                });
                break;

            case 'time_entries':
                $query->where(function ($q) use ($managedProjects) {
                    $q->whereIn('project_id', $managedProjects)
                      ->orWhere('user_id', $this->user->id);
                });
                break;

            case 'expenses':
                $query->where(function ($q) use ($managedProjects) {
                    $q->whereIn('project_id', $managedProjects)
                      ->orWhere('submitted_by', $this->user->id)
                      ->orWhere('approved_by', $this->user->id);
                });
                break;

            default:
                // For other tables, show records created by or assigned to manager
                $this->applyGenericManagerConditions($query);
                break;
        }
    }

    /**
     * Apply accountant-specific conditions
     */
    protected function applyAccountantConditions(QueryBuilder $query, string $table): void
    {
        switch ($table) {
            case 'accounts':
            case 'transactions':
            case 'invoices':
            case 'payments':
            case 'expenses':
            case 'budgets':
                // Accountants can see all financial data
                break;

            case 'customers':
            case 'suppliers':
                // Can see all customers and suppliers for financial purposes
                break;

            case 'projects':
                // Can see projects for financial reporting
                $query->where('status', '!=', 'draft');
                break;

            case 'employees':
            case 'users':
                // Can see employee data for payroll purposes
                $query->where('status', 'active');
                break;

            default:
                // Limited access to other data
                $this->applyGenericFinancialConditions($query);
                break;
        }
    }

    /**
     * Apply employee-specific conditions
     */
    protected function applyEmployeeConditions(QueryBuilder $query, string $table): void
    {
        switch ($table) {
            case 'projects':
                // Can see projects they're assigned to
                $query->where(function ($q) {
                    $q->whereExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                                ->from('project_members')
                                ->whereColumn('project_members.project_id', 'projects.id')
                                ->where('project_members.user_id', $this->user->id);
                    })->orWhere('created_by', $this->user->id);
                });
                break;

            case 'tasks':
                // Can see tasks assigned to them
                $query->where(function ($q) {
                    $q->where('assigned_to', $this->user->id)
                      ->orWhere('created_by', $this->user->id);
                });
                break;

            case 'time_entries':
                // Can only see their own time entries
                $query->where('user_id', $this->user->id);
                break;

            case 'expenses':
                // Can see their own expense submissions
                $query->where('submitted_by', $this->user->id);
                break;

            case 'invoices':
                // Can see invoices for projects they're involved in
                $projectIds = $this->getUserProjectIds();
                $query->whereIn('project_id', $projectIds);
                break;

            case 'customers':
            case 'suppliers':
                // Read-only access to basic contact info
                $query->select(['id', 'name', 'email', 'phone']);
                break;

            default:
                // Very limited access - only own records
                $query->where('user_id', $this->user->id)
                      ->orWhere('created_by', $this->user->id);
                break;
        }
    }

    /**
     * Apply client-specific conditions
     */
    protected function applyClientConditions(QueryBuilder $query, string $table): void
    {
        $clientProjectIds = $this->getClientProjectIds();

        switch ($table) {
            case 'projects':
                $query->whereIn('id', $clientProjectIds)
                      ->where('client_visible', true);
                break;

            case 'tasks':
                $query->whereIn('project_id', $clientProjectIds)
                      ->where('client_visible', true);
                break;

            case 'invoices':
                $query->where('client_id', $this->user->id)
                      ->orWhereIn('project_id', $clientProjectIds);
                break;

            case 'payments':
                $query->where('client_id', $this->user->id);
                break;

            case 'time_entries':
                $query->whereIn('project_id', $clientProjectIds)
                      ->where('billable', true);
                break;

            default:
                // Very restricted access
                $query->where('client_id', $this->user->id);
                break;
        }
    }

    /**
     * Apply viewer-specific conditions
     */
    protected function applyViewerConditions(QueryBuilder $query, string $table): void
    {
        switch ($table) {
            case 'projects':
            case 'tasks':
            case 'customers':
            case 'suppliers':
                // Read-only access to approved/published data
                $query->where('status', '!=', 'draft');
                break;

            case 'invoices':
            case 'payments':
                // Can view financial summaries but not detailed transactions
                $query->where('status', 'approved');
                break;

            case 'employees':
            case 'users':
                // Limited employee directory access
                $query->select(['id', 'name', 'email', 'department', 'position'])
                      ->where('public_profile', true);
                break;

            default:
                // Read-only access to public data only
                $query->where('is_public', true);
                break;
        }
    }

    /**
     * Get financial data with role-based access
     */
    public function getFinancialData(array $metrics = []): Collection
    {
        $data = collect();

        if ($this->canAccessFinancialData()) {
            foreach ($metrics as $metric) {
                $data[$metric] = $this->calculateFinancialMetric($metric);
            }
        }

        return $data;
    }

    /**
     * Get dashboard statistics with role-based filtering
     */
    public function getDashboardStats(): array
    {
        $stats = [];

        // Projects stats
        if ($this->canAccessProjects()) {
            $projectsQuery = $this->buildRoleBasedQuery('projects');
            $stats['projects'] = [
                'total' => $projectsQuery->count(),
                'active' => $projectsQuery->where('status', 'active')->count(),
                'completed' => $projectsQuery->where('status', 'completed')->count(),
            ];
        }

        // Financial stats
        if ($this->canAccessFinancialData()) {
            $stats['financial'] = $this->getFinancialSummary();
        }

        // Task stats
        if ($this->canAccessTasks()) {
            $tasksQuery = $this->buildRoleBasedQuery('tasks');
            $stats['tasks'] = [
                'total' => $tasksQuery->count(),
                'pending' => $tasksQuery->where('status', 'pending')->count(),
                'completed' => $tasksQuery->where('status', 'completed')->count(),
            ];
        }

        // User stats (for managers and admins)
        if ($this->canAccessUserData()) {
            $usersQuery = $this->buildRoleBasedQuery('users');
            $stats['users'] = [
                'total' => $usersQuery->count(),
                'active' => $usersQuery->where('status', 'active')->count(),
            ];
        }

        return $stats;
    }

    /**
     * Execute complex business query with role and tenant filtering
     */
    public function executeBusinessQuery(string $sql, array $bindings = []): Collection
    {
        // Validate SQL for security
        if (!$this->isSafeQuery($sql)) {
            throw new \InvalidArgumentException('Unsafe SQL query detected');
        }

        // Add role and tenant conditions to the query
        $modifiedSql = $this->addRoleAndTenantConditions($sql);

        return collect(DB::select($modifiedSql, $bindings));
    }

    /**
     * Get tenant-specific data with role filtering
     */
    public function getTenantData(string $table, array $columns = ['*']): Collection
    {
        return $this->buildRoleBasedQuery($table, [], $columns)->get();
    }

    // Helper methods
    protected function isSuperAdmin(): bool
    {
        return $this->userRole === 'super-admin';
    }

    public function canAccessFinancialData(): bool
    {
        return in_array($this->userRole, ['super-admin', 'admin', 'accountant', 'manager']);
    }

    public function canAccessProjects(): bool
    {
        return in_array($this->userRole, ['super-admin', 'admin', 'manager', 'employee']);
    }

    public function canAccessTasks(): bool
    {
        return in_array($this->userRole, ['super-admin', 'admin', 'manager', 'employee']);
    }

    public function canAccessUserData(): bool
    {
        return in_array($this->userRole, ['super-admin', 'admin', 'manager']);
    }

    protected function getManagedProjectIds(): array
    {
        return DB::table('projects')
                 ->where('manager_id', $this->user->id)
                 ->pluck('id')
                 ->toArray();
    }

    protected function getManagedTeamIds(): array
    {
        return DB::table('teams')
                 ->where('manager_id', $this->user->id)
                 ->pluck('id')
                 ->toArray();
    }

    protected function getUserProjectIds(): array
    {
        return DB::table('project_members')
                 ->where('user_id', $this->user->id)
                 ->pluck('project_id')
                 ->toArray();
    }

    protected function getClientProjectIds(): array
    {
        return DB::table('projects')
                 ->where('client_id', $this->user->id)
                 ->pluck('id')
                 ->toArray();
    }

    protected function applyGenericManagerConditions(QueryBuilder $query): void
    {
        $query->where(function ($q) {
            $q->where('created_by', $this->user->id)
              ->orWhere('assigned_to', $this->user->id)
              ->orWhere('manager_id', $this->user->id);
        });
    }

    protected function applyGenericFinancialConditions(QueryBuilder $query): void
    {
        // Accountants can see most data for financial purposes
        $query->where('status', '!=', 'draft');
    }

    protected function calculateFinancialMetric(string $metric): float
    {
        switch ($metric) {
            case 'revenue':
                return $this->buildRoleBasedQuery('invoices')
                           ->where('status', 'paid')
                           ->sum('total_amount');

            case 'expenses':
                return $this->buildRoleBasedQuery('expenses')
                           ->where('status', 'approved')
                           ->sum('amount');

            case 'profit':
                return $this->calculateFinancialMetric('revenue') -
                       $this->calculateFinancialMetric('expenses');

            default:
                return 0;
        }
    }

    protected function getFinancialSummary(): array
    {
        if (!$this->canAccessFinancialData()) {
            return [];
        }

        return [
            'revenue' => $this->calculateFinancialMetric('revenue'),
            'expenses' => $this->calculateFinancialMetric('expenses'),
            'profit' => $this->calculateFinancialMetric('profit'),
        ];
    }

    protected function isSafeQuery(string $sql): bool
    {
        // Basic SQL injection protection
        $dangerousKeywords = ['DROP', 'DELETE', 'INSERT', 'UPDATE', 'ALTER', 'CREATE', 'TRUNCATE'];

        foreach ($dangerousKeywords as $keyword) {
            if (stripos($sql, $keyword) !== false) {
                return false;
            }
        }

        return true;
    }

    protected function addRoleAndTenantConditions(string $sql): string
    {
        // This would add appropriate WHERE conditions based on role and tenant
        // For now, returning the original SQL - implement based on specific needs
        return $sql;
    }
}
