<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Role-Based Access Control with Filters (RBAC + Filters)
 * Provides row-level security and data filtering based on user roles
 */
class RbacFilterService
{
    /**
     * Get current authenticated user
     */
    public function getUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return Auth::check() && Auth::user()->hasRole($role);
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole($roles);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return Auth::check() && Auth::user()->hasPermissionTo($permission);
    }

    /**
     * Filter Users based on role
     * - Admin: Can see all users
     * - Others: Can only see themselves
     */
    public function filterUsers(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0'); // Return empty
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator')) {
            return $query; // Return all users
        }

        // Non-admin users can only see themselves
        return $query->where('id', $user->id);
    }

    /**
     * Filter Projects based on role
     * - Admin: All projects
     * - Site Manager: Only assigned projects
     * - Accountant: All projects (read-only)
     * - Others: Only projects they're assigned to
     */
    public function filterProjects(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        // Admins, system administrators, and accountants can see all projects within their tenant
        if ($user->hasRole('admin') || $user->hasRole('system administrator') || $user->hasRole('accountant')) {
            return $query;
        }

        // Site managers and other roles: show projects tied to tasks they created or are assigned to
        return $query->whereHas('tasks', function ($q) use ($user) {
            $q->where(function ($q2) use ($user) {
                $q2->where('assigned_to', $user->id)
                   ->orWhere('created_by', $user->id);
            });
        });
    }

    /**
     * Filter Incomes/Payments based on role
     * - Admin/Accountant: All records
     * - Site Manager: Only their projects' income
     * - Others: Only their assigned work income
     */
    public function filterIncomes(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator') || $user->hasRole('accountant')) {
            return $query; // Full access
        }

        if ($user->hasRole('site manager')) {
            // Site managers see income from their projects
            return $query->whereHas('project', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        // Others see only their own income
        return $query->where('user_id', $user->id);
    }

    /**
     * Filter Expenses based on role
     * - Admin/Accountant: All expenses
     * - Site Manager: Only their project expenses
     * - Others: Only their own expenses
     */
    public function filterExpenses(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator') || $user->hasRole('accountant')) {
            return $query; // Full access
        }

        if ($user->hasRole('site manager')) {
            // Site managers see expenses from their projects
            return $query->whereHas('project', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        // Others see only their own expenses
        return $query->where('user_id', $user->id);
    }

    /**
     * Filter Products/Inventory based on role
     * - Admin/Store Keeper: All products
     * - Site Manager: Only warehouse products
     * - Others: Limited view
     */
    public function filterProducts(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator') || $user->hasRole('store keeper')) {
            return $query; // Full access
        }

        // Other roles see only active products
        return $query->where('status', 'active');
    }

    /**
     * Filter Tasks based on role
     * - Admin: All tasks
     * - Site Manager: Tasks from their projects
     * - Workers: Only assigned tasks
     */
    public function filterTasks(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator')) {
            return $query; // Full access
        }

        if ($user->hasRole('site manager')) {
            // Site managers see tasks from their projects
            return $query->whereHas('project', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        // Workers see only assigned tasks
        return $query->where('assigned_to', $user->id);
    }

    /**
     * Filter Workers based on role
     * - Admin: All workers
     * - Site Manager: Workers on their projects
     * - Others: Can only see themselves
     */
    public function filterWorkers(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator')) {
            return $query; // Return all workers
        }

        if ($user->hasRole('accountant')) {
            return $query; // Accountants can see all workers
        }

        if ($user->hasRole('site manager')) {
            // Site managers see workers on their projects
            return $query->whereHas('projects', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        // Non-admin/accountant users see only themselves
        return $query->where('user_id', $user->id);
    }

    /**
     * Filter Transactions based on role
     * - Admin/Accountant: All transactions
     * - Site Manager: Transactions from their projects
     * - Others: Limited view
     */
    public function filterTransactions(Builder $query): Builder
    {
        $user = $this->getUser();

        if (!$user) {
            return $query->whereRaw('1=0');
        }

        if ($user->hasRole('admin') || $user->hasRole('system administrator') || $user->hasRole('accountant')) {
            return $query; // Full access
        }

        if ($user->hasRole('site manager')) {
            // Site managers see transactions from their projects
            return $query->whereHas('project', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        // Limited view for others
        return $query->where('user_id', $user->id);
    }

    /**
     * Get user's accessible roles for display
     */
    public function getUserRoles(): array
    {
        return Auth::check() ? Auth::user()->roles->pluck('name')->toArray() : [];
    }

    /**
     * Get user's accessible permissions for display
     */
    public function getUserPermissions(): array
    {
        return Auth::check() ? Auth::user()->getAllPermissions()->pluck('name')->toArray() : [];
    }

    /**
     * Check if user can access a specific model
     */
    public function canAccess($model, string $action = 'view'): bool
    {
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        // Admins can access everything
        if ($user->hasRole(['admin', 'system administrator'])) {
            return true;
        }

        // Check specific permissions
        return $user->hasPermissionTo($action . ' ' . class_basename($model));
    }

    /**
     * Get filter context for frontend
     */
    public function getFilterContext(): array
    {
        $user = $this->getUser();

        return [
            'user_id' => $user?->id,
            'roles' => $this->getUserRoles(),
            'permissions' => $this->getUserPermissions(),
            'is_admin' => $this->hasRole('admin'),
            'is_accountant' => $this->hasRole('accountant'),
            'is_site_manager' => $this->hasRole('site manager'),
            'is_store_keeper' => $this->hasRole('store keeper'),
            'is_system_admin' => $this->hasRole('system administrator'),
        ];
    }
}
