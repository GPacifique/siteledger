<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Worker;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Project;
use App\Services\DashboardStatsService;

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(DashboardStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function index()
    {
        $user = Auth::user();

        // Check if user has any meaningful permissions
        if (!$user->hasRole(['super-admin', 'admin', 'manager', 'accountant', 'secretary', 'foreman', 'site manager', 'user']) &&
            !$user->hasAnyPermission(['projects.create', 'expenses.create', 'users.view', 'payments.create', 'reports.generate'])) {
            // Redirect users with no permissions to welcome page
            return redirect('/')->with('error', 'You need proper permissions to access the dashboard.');
        }

        // Redirect to appropriate dashboard URL based on role (prioritize highest privilege)
        if ($user->is_super_admin) {
            return redirect('/super-admin/dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('accountant')) {
            return redirect('/accountant/dashboard');
        } elseif ($user->hasRole('manager')) {
            return redirect('/manager/dashboard');
        } elseif ($user->hasRole('secretary')) {
            return redirect('/secretary/dashboard');
        } elseif ($user->hasRole('foreman') || $user->hasRole('site manager')) {
            return redirect('/foreman/dashboard');
        }

        return redirect('/user/dashboard');
    }

    /**
     * Admin sees all data and statistics with enhanced analytics
     */
    public function adminDashboard()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Get enhanced stats from service
        $financialSummary = $this->statsService->getFinancialSummary();
        $quickStats = $this->statsService->getQuickStats();
        $dailyStats = $this->statsService->getDailyStats(30);
        $weeklyStats = $this->statsService->getWeeklyStats(12);
        $cashFlowAnalysis = $this->statsService->getCashFlowAnalysis(6);
        $topProjects = $this->statsService->getTopProjects(5);
        $incomeByCategory = $this->statsService->getIncomeByCategory();
        $expenseByCategory = $this->statsService->getExpenseByCategory();
        $expenseByMethod = $this->statsService->getExpenseByMethod();
        $paymentStatusBreakdown = $this->statsService->getPaymentStatusBreakdown();
        $outstandingReceivables = $this->statsService->getOutstandingReceivables();

        // Get card stats for different periods
        $dailyCardStats = $this->statsService->getDailyCardStats();
        $monthlyCardStats = $this->statsService->getMonthlyCardStats();
        $yearlyCardStats = $this->statsService->getYearlyCardStats();

        // Additional stats for admin dashboard
        $dailyTotals = [];
        $categories = [];

        // Workers and Employees
        $totalWorkers = $has('workers') ? Worker::count() : 0;
        $totalEmployees = class_exists('App\Models\Employee') ? \App\Models\Employee::count() : 0;
        $totalWorkforce = $totalWorkers + $totalEmployees;
        $activeWorkers = $has('workers', 'status')
            ? Worker::where('status', 'active')->count()
            : $totalWorkers;
        $recentWorkers = $has('workers') ? Worker::latest()->limit(6)->get() : collect();

        // Payroll calculations
        $totalPayroll = $has('workers', 'salary') ? Worker::sum('salary') : 0;
        $workersAvgSalary = $totalWorkers > 0 ? $totalPayroll / $totalWorkers : 0;
        $employeesAvgSalary = class_exists('App\Models\Employee') && $totalEmployees > 0
            ? \App\Models\Employee::avg('salary') ?? 0
            : 0;

        // Payments (Company Payments - independent of employees)
        $paymentsTotal = $has('payments', 'amount') ? Payment::sum('amount') : 0;
        $paymentsThisMonth = $has('payments', 'amount')
            ? Payment::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
            : 0;
        $paymentsToday = $has('payments', 'amount')
            ? Payment::whereDate('created_at', $today)->sum('amount')
            : 0;
        $recentPayments = $has('payments') ? Payment::latest()->limit(7)->get() : collect();

        // Transactions
        $recentTransactions = $has('transactions') ? Transaction::latest()->limit(7)->get() : collect();
        $transactionsThisMonth = $has('transactions', 'amount')
            ? Transaction::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
            : 0;

        // Incomes
        $incomesTotal = $has('incomes', 'amount_received') ? Income::sum('amount_received') : 0;
        $incomesThisMonth = $has('incomes', 'amount_received')
            ? Income::whereBetween('received_at', [$startOfMonth, $endOfToday])->sum('amount_received')
            : 0;
        $incomesToday = $has('incomes', 'amount_received')
            ? Income::whereDate('received_at', $today)->sum('amount_received')
            : 0;
        $recentIncomes = $has('incomes') ? Income::latest()->limit(7)->get() : collect();

        // Expenses
        $expensesTotal = $has('expenses', 'total') ? Expense::sum('total') : 0;
        $expensesThisMonth = $has('expenses', 'total')
            ? Expense::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('total')
            : 0;
        $expensesToday = $has('expenses', 'total')
            ? Expense::whereDate('created_at', $today)->sum('total')
            : 0;
        $recentExpenses = $has('expenses') ? Expense::latest()->limit(7)->get() : collect();

        // Combined Payments and Expenses (All Expenses)
        $allExpensesTotal = $paymentsTotal + $expensesTotal;
        $allExpensesThisMonth = $paymentsThisMonth + $expensesThisMonth;
        $allExpensesToday = $paymentsToday + $expensesToday;

        // Expenses by category (Office vs Project)
        $officeExpenses = $has('expenses') ? Expense::whereNull('project_id')->sum('total') : 0;
        $projectExpenses = $has('expenses') ? Expense::whereNotNull('project_id')->sum('total') : 0;
        $officeExpensesThisMonth = $has('expenses')
            ? Expense::whereNull('project_id')->whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('total')
            : 0;
        $projectExpensesThisMonth = $has('expenses')
            ? Expense::whereNotNull('project_id')->whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('total')
            : 0;

        // Projects
        $projectsCount = $has('projects') ? Project::withoutGlobalScope('tenant')->count() : 0;
        $projectsThisMonth = $has('projects')
            ? Project::withoutGlobalScope('tenant')->whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $projectsTotal = $has('projects', 'contract_value') ? Project::withoutGlobalScope('tenant')->sum('contract_value') : 0;
        $recentProjects = $has('projects') ? Project::withoutGlobalScope('tenant')->latest()->limit(7)->get() : collect();

        // Design Phase Summary Variables
        $totalDesignValue = 0;
        $totalDesignPaid = 0;
        $totalExecutionValue = 0;
        $totalExecutionPaid = 0;
        $totalDesignExpenses = 0;
        $totalExecutionExpenses = 0;
        $totalDesignPhases = 0;
        $completedDesignPhases = 0;
        $inProgressDesignPhases = 0;
        $pendingDesignPhases = 0;
        if (class_exists('App\\Models\\DesignPhase') && $has('design_phases')) {
            $designPhases = \App\Models\DesignPhase::all();
            $totalDesignPhases = $designPhases->count();
            $completedDesignPhases = $designPhases->where('status', 'completed')->count();
            $inProgressDesignPhases = $designPhases->where('status', 'in_progress')->count();
            $pendingDesignPhases = $designPhases->where('status', 'pending')->count();
        }
        if ($has('projects')) {
            $projects = Project::withoutGlobalScope('tenant')->get();
            $totalDesignValue = $projects->sum('design_phase_value');
            $totalDesignPaid = $projects->sum('design_phase_paid');
            $totalExecutionValue = $projects->sum('execution_phase_value');
            $totalExecutionPaid = $projects->sum('execution_phase_paid');
        }

        // Calculate expenses by phases
        if ($has('expenses', 'total')) {
            $totalDesignExpenses = Expense::where('phase', 'design')->sum('total');
            $totalExecutionExpenses = Expense::where('phase', 'execution')->sum('total');
        }

        // Expense breakdown by category
        $expensesByCategory = [];
        if ($has('expenses', 'expense_category_id')) {
            $expensesByCategory = \App\Models\Expense::with('category')
                ->selectRaw('expense_category_id, SUM(total) as total_amount')
                ->groupBy('expense_category_id')
                ->get()
                ->map(function($expense) {
                    return [
                        'category_name' => $expense->category->name ?? 'Unknown',
                        'total' => $expense->total_amount
                    ];
                })
                ->sortByDesc('total')
                ->values()
                ->toArray();
        }

        // Calculate revenue by phases (from project relationships)
        $totalDesignRevenue = 0;
        $totalExecutionRevenue = 0;
        $designPhaseRevenue = 0;
        $executionPhaseRevenue = 0;

        if ($has('projects')) {
            // Revenue from design-only projects (use Income if available, otherwise use amount_paid)
            $designOnlyProjects = Project::withoutGlobalScope('tenant')->where('project_type', Project::PROJECT_TYPE_DESIGN);
            if ($has('incomes')) {
                $designOnlyProjectIds = $designOnlyProjects->pluck('id');
                $totalDesignRevenue = Income::whereIn('project_id', $designOnlyProjectIds)->sum('amount_received');

                // Fallback to project amount_paid if no income records exist
                if ($totalDesignRevenue == 0) {
                    $totalDesignRevenue = $designOnlyProjects->sum('amount_paid');
                }
            } else {
                $totalDesignRevenue = $designOnlyProjects->sum('amount_paid');
            }

            // Revenue from execution-only projects (use Income if available, otherwise use amount_paid)
            $executionOnlyProjects = Project::withoutGlobalScope('tenant')->where('project_type', Project::PROJECT_TYPE_EXECUTION);
            if ($has('incomes')) {
                $executionOnlyProjectIds = $executionOnlyProjects->pluck('id');
                $totalExecutionRevenue = Income::whereIn('project_id', $executionOnlyProjectIds)->sum('amount_received');

                // Fallback to project amount_paid if no income records exist
                if ($totalExecutionRevenue == 0) {
                    $totalExecutionRevenue = $executionOnlyProjects->sum('amount_paid');
                }
            } else {
                $totalExecutionRevenue = $executionOnlyProjects->sum('amount_paid');
            }

            // For combined projects, use phase payments (with fallback to 50/50 split of amount_paid)
            $designExecutionProjects = Project::withoutGlobalScope('tenant')->where('project_type', Project::PROJECT_TYPE_DESIGN_EXECUTION);
            $designPhaseRevenue = $designExecutionProjects->sum('design_phase_paid');
            $executionPhaseRevenue = $designExecutionProjects->sum('execution_phase_paid');

            // If phase-specific payments are zero, split the amount_paid 50/50
            if ($designPhaseRevenue == 0 && $executionPhaseRevenue == 0) {
                $combinedProjectsPaid = $designExecutionProjects->sum('amount_paid');
                $designPhaseRevenue = $combinedProjectsPaid * 0.5; // 50% for design
                $executionPhaseRevenue = $combinedProjectsPaid * 0.5; // 50% for execution
            }
        }

        // Project Type Statistics
        $designOnlyProjects = 0;
        $executionOnlyProjects = 0;
        $designExecutionProjects = 0;
        $totalProjectsByType = 0;

        if ($has('projects') && $has('projects', 'project_type')) {
            $designOnlyProjects = Project::withoutGlobalScope('tenant')->where('project_type', Project::PROJECT_TYPE_DESIGN)->count();
            $executionOnlyProjects = Project::withoutGlobalScope('tenant')->where('project_type', Project::PROJECT_TYPE_EXECUTION)->count();
            $designExecutionProjects = Project::withoutGlobalScope('tenant')->where('project_type', Project::PROJECT_TYPE_DESIGN_EXECUTION)->count();
            $totalProjectsByType = $designOnlyProjects + $executionOnlyProjects + $designExecutionProjects;
        }

        // Clients (using clients table)
        $totalClients = $has('clients') ? \App\Models\Client::count() : 0;
        $activeClients = $has('clients', 'status')
            ? \App\Models\Client::where('status', 'active')->count()
            : $totalClients;
        $clientsThisMonth = $has('clients')
            ? \App\Models\Client::whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;

        // Orders (placeholder - no orders table exists)
        $totalOrders = 0;

        // Project Stats
        $projectStats = collect();
        if ($has('projects') && $has('incomes', 'amount_received')) {
            $projectStats = Project::with('incomes')
                ->get()
                ->map(function($project) {
                    $amountPaid = (float) $project->incomes->sum('amount_received');
                    $totalAmount = (float) ($project->contract_value ?? 0);
                    return [
                        'id' => $project->id,
                        'project_name' => $project->name,
                        'amount_paid' => $amountPaid,
                        'total_amount' => $totalAmount,
                        'amount_remaining' => $totalAmount - $amountPaid,
                    ];
                })
                ->toArray();
        }

        // Daily stats (last 30 days)
        $dailyDates = [];
        $dailyRevenue = [];
        $dailyExpenses = [];
        $dailyTasks = [];

        for ($i = 29; $i >= 0; $i--) {
            $dt = Carbon::now()->subDays($i);
            $dailyDates[] = $dt->format('M d');

            $dailyRevenue[] = $has('incomes', 'amount_received')
                ? Income::whereDate('received_at', $dt)->sum('amount_received')
                : 0;

            $dailyExpenses[] = $has('expenses', 'total')
                ? Expense::whereDate('created_at', $dt)->sum('total')
                : 0;

            $dailyTasks[] = $has('tasks')
                ? \App\Models\Task::whereDate('created_at', $dt)->count()
                : 0;
        }

        // Monthly series
        $months = [];
        $paymentsMonthly = [];
        $expensesMonthly = [];
        $incomeMonthly = [];

        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $months[] = $dt->format('M Y');

            $mStart = $dt->copy()->startOfMonth();
            $mEnd = $dt->copy()->endOfMonth();

            $paymentsMonthly[] = $has('payments', 'amount')
                ? Payment::whereBetween('created_at', [$mStart, $mEnd])->sum('amount')
                : 0;

            $expensesMonthly[] = $has('expenses', 'total')
                ? Expense::whereBetween('created_at', [$mStart, $mEnd])->sum('total')
                : 0;

            $incomeMonthly[] = $has('incomes', 'amount_received')
                ? Income::whereBetween('received_at', [$mStart, $mEnd])->sum('amount_received')
                : 0;
        }

        return view('dashboard', compact(
            'financialSummary', 'quickStats', 'dailyStats', 'weeklyStats', 'cashFlowAnalysis',
            'incomeByCategory', 'expenseByCategory', 'expenseByMethod', 'topProjects',
            'paymentStatusBreakdown', 'outstandingReceivables', 'dailyTotals', 'categories',
            'dailyCardStats', 'monthlyCardStats', 'yearlyCardStats',
            'totalWorkers', 'totalEmployees', 'totalWorkforce', 'activeWorkers', 'recentWorkers',
            'totalPayroll', 'workersAvgSalary', 'employeesAvgSalary',
            'paymentsTotal', 'paymentsThisMonth', 'paymentsToday', 'recentPayments',
            'recentTransactions', 'transactionsThisMonth',
            'incomesTotal', 'incomesThisMonth', 'incomesToday', 'recentIncomes',
            'expensesTotal', 'expensesThisMonth', 'expensesToday', 'recentExpenses',
            'allExpensesTotal', 'allExpensesThisMonth', 'allExpensesToday',
            'officeExpenses', 'projectExpenses', 'officeExpensesThisMonth', 'projectExpensesThisMonth',
            'projectsCount', 'projectsThisMonth', 'projectsTotal', 'recentProjects',
            'totalClients', 'activeClients', 'clientsThisMonth', 'totalOrders',
            'projectStats', 'months', 'paymentsMonthly', 'expensesMonthly', 'incomeMonthly',
            'dailyDates', 'dailyRevenue', 'dailyExpenses', 'dailyTasks',
            'totalDesignValue', 'totalDesignPaid', 'totalExecutionValue', 'totalExecutionPaid',
            'totalDesignExpenses', 'totalExecutionExpenses', 'expensesByCategory',
            'totalDesignPhases', 'completedDesignPhases', 'inProgressDesignPhases', 'pendingDesignPhases',
            'designOnlyProjects', 'executionOnlyProjects', 'designExecutionProjects', 'totalProjectsByType',
            'totalDesignRevenue', 'totalExecutionRevenue', 'designPhaseRevenue', 'executionPhaseRevenue'
        ));
    }

    /**
     * Secretary sees clients, tasks, and administrative data
     */
    public function secretaryDashboard()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Filter by current user's tenant
        $currentTenantId = auth()->user()->current_tenant_id ?? auth()->user()->tenants()->first()->id ?? null;

        // Clients
        $totalClients = $has('clients') && $currentTenantId
            ? \App\Models\Client::where('tenant_id', $currentTenantId)->count() : 0;
        $clientsThisMonth = $has('clients') && $currentTenantId
            ? \App\Models\Client::where('tenant_id', $currentTenantId)->whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $recentClients = $has('clients') && $currentTenantId
            ? \App\Models\Client::where('tenant_id', $currentTenantId)->latest()->limit(7)->get() : collect();

        // Projects
        $projectsCount = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->count() : 0;
        $projectsThisMonth = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $recentProjects = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->with('client')->latest()->limit(7)->get() : collect();

        // Tasks
        $pendingTasks = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->where('status', '!=', 'completed')->count() : 0;
        $tasksToday = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->whereDate('due_date', $today)->count() : 0;
        $todaysTasks = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->whereDate('due_date', $today)->with('project')->get() : collect();

        // Staff count
        $totalStaff = $has('workers') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->count() : 0;
        $activeStaff = $has('workers', 'status') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->where('status', 'active')->count() : $totalStaff;

        // Notifications
        $recentNotifications = Auth::user()->notifications()->latest()->limit(5)->get();

        // Get current tenant/company
        $company = Auth::user()->currentTenant();

        return view('dashboard.secretary', compact(
            'totalClients', 'clientsThisMonth', 'recentClients',
            'projectsCount', 'projectsThisMonth', 'recentProjects',
            'pendingTasks', 'tasksToday', 'todaysTasks',
            'totalStaff', 'activeStaff',
            'recentNotifications', 'company'
        ));
    }

    /**
     * Foreman sees workers, tasks, and site-related data
     */
    public function foremanDashboard()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Filter by current user's tenant
        $currentTenantId = auth()->user()->current_tenant_id ?? auth()->user()->tenants()->first()->id ?? null;

        // Workers
        $totalWorkers = $has('workers') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->count() : 0;
        $activeWorkers = $has('workers', 'status') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->where('status', 'active')->count() : $totalWorkers;
        $recentWorkers = $has('workers') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->latest()->limit(8)->get() : collect();

        // Tasks
        $activeTasks = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->where('status', '!=', 'completed')->count() : 0;
        $tasksCompleted = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->where('status', 'completed')
                ->whereBetween('updated_at', [$startOfMonth, $endOfToday])
                ->count()
            : 0;
        $tasksList = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->where('status', '!=', 'completed')
                ->with(['project', 'assignedWorker'])
                ->latest()
                ->limit(8)
                ->get()
            : collect();

        // Current project (latest active one)
        $currentProject = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->where('status', '!=', 'completed')->latest()->first()
            : null;
        $projectProgress = 0;
        if ($currentProject && $currentProject->contract_value > 0) {
            $paid = $has('incomes') ? Income::where('project_id', $currentProject->id)->sum('amount_received') : 0;
            $projectProgress = min(100, round(($paid / $currentProject->contract_value) * 100));
        }

        // Site expenses
        $siteExpenses = $has('expenses', 'total') && $currentTenantId
            ? Expense::where('tenant_id', $currentTenantId)->whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('total')
            : 0;
        $recentExpenses = $has('expenses') && $currentTenantId
            ? Expense::where('tenant_id', $currentTenantId)->latest()->limit(7)->get() : collect();

        // Company payments
        $recentPayments = $has('payments') && $currentTenantId
            ? Payment::where('tenant_id', $currentTenantId)->latest()->limit(7)->get() : collect();

        // Get current tenant/company
        $company = Auth::user()->currentTenant();

        return view('dashboard.foreman', compact(
            'totalWorkers', 'activeWorkers', 'recentWorkers',
            'activeTasks', 'tasksCompleted', 'tasksList',
            'currentProject', 'projectProgress',
            'siteExpenses', 'recentExpenses',
            'recentPayments', 'company'
        ));
    }

    /**
     * Manager sees project and employee data with analytics
     */
    public function managerDashboard()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Get financial summary and top projects from service
        $financialSummary = $this->statsService->getFinancialSummary();
        $topProjects = $this->statsService->getTopProjects(8);
        $weeklyStats = $this->statsService->getWeeklyStats(12);
        $incomeByCategory = $this->statsService->getIncomeByCategory();

        // Filter by current user's tenant
        $currentTenantId = auth()->user()->current_tenant_id ?? auth()->user()->tenants()->first()->id ?? null;

        // Workers/Employees
        $totalWorkers = $has('workers') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->count() : 0;
        $activeWorkers = $has('workers', 'status') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->where('status', 'active')->count()
            : $totalWorkers;
        $recentWorkers = $has('workers') && $currentTenantId
            ? Worker::where('tenant_id', $currentTenantId)->latest()->limit(10)->get() : collect();

        // Projects
        $projectsCount = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->count() : 0;
        $projectsThisMonth = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $projectsTotal = $has('projects', 'contract_value') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->sum('contract_value') : 0;
        $recentProjects = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->with(['client', 'incomes'])->latest()->limit(10)->get() : collect();

        // Tasks
        $activeTasks = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->where('status', '!=', 'completed')->count() : 0;
        $completedTasks = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->where('status', 'completed')
                ->whereBetween('updated_at', [$startOfMonth, $endOfToday])
                ->count()
            : 0;
        $recentTasks = $has('tasks') && $currentTenantId
            ? \App\Models\Task::where('tenant_id', $currentTenantId)->with('project')->latest()->limit(8)->get() : collect();

        // Clients
        $totalClients = $has('clients') && $currentTenantId
            ? \App\Models\Client::where('tenant_id', $currentTenantId)->count() : 0;
        $activeClients = $has('clients', 'status') && $currentTenantId
            ? \App\Models\Client::where('tenant_id', $currentTenantId)->where('status', 'active')->count()
            : $totalClients;

        // Project Stats with payments
        $projectStats = collect();
        if ($has('projects') && $has('incomes', 'amount_received') && $currentTenantId) {
            $projectStats = Project::where('tenant_id', $currentTenantId)->with('incomes')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function($project) {
                    $amountPaid = (float) $project->incomes->sum('amount_received');
                    $totalAmount = (float) ($project->contract_value ?? 0);
                    return [
                        'id' => $project->id,
                        'project_name' => $project->name,
                        'amount_paid' => $amountPaid,
                        'total_amount' => $totalAmount,
                        'amount_remaining' => $totalAmount - $amountPaid,
                    ];
                })
                ->toArray();
        }

        // Monthly project data
        $months = [];
        $projectsMonthly = [];

        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $months[] = $dt->format('M Y');

            $mStart = $dt->copy()->startOfMonth();
            $mEnd = $dt->copy()->endOfMonth();

            $projectsMonthly[] = $has('projects', 'contract_value') && $currentTenantId
                ? Project::where('tenant_id', $currentTenantId)->whereBetween('created_at', [$mStart, $mEnd])->sum('contract_value')
                : 0;
        }

        // Get current tenant/company
        $company = Auth::user()->currentTenant();

        return view('dashboard.manager', compact(
            'financialSummary', 'topProjects', 'weeklyStats', 'incomeByCategory',
            'totalWorkers', 'activeWorkers', 'recentWorkers',
            'projectsCount', 'projectsThisMonth', 'projectsTotal', 'recentProjects',
            'activeTasks', 'completedTasks', 'recentTasks',
            'totalClients', 'activeClients',
            'projectStats', 'months', 'projectsMonthly', 'company'
        ));
    }

    /**
     * Accountant sees only financial data with enhanced analytics
     */
    public function accountantDashboard()
    {
        // Get comprehensive financial summary
        $financialSummary = $this->statsService->getFinancialSummary();
        $quickStats = $this->statsService->getQuickStats();

        // Get daily, weekly, and monthly trends
        $dailyStats = $this->statsService->getDailyStats(30);
        $weeklyStats = $this->statsService->getWeeklyStats(12);
        $cashFlowAnalysis = $this->statsService->getCashFlowAnalysis(6);

        // Category breakdowns
        $incomeByCategory = $this->statsService->getIncomeByCategory();
        $expenseByCategory = $this->statsService->getExpenseByCategory();
        $expenseByMethod = $this->statsService->getExpenseByMethod();
    $transactionsByCategory = $this->statsService->getTransactionsByCategory();

        // Payment analysis
        $paymentStatusBreakdown = $this->statsService->getPaymentStatusBreakdown();
        $outstandingReceivables = $this->statsService->getOutstandingReceivables();

        // Recent transactions
        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Recent transactions - filter by current user's tenant
        $currentTenantId = auth()->user()->current_tenant_id ?? auth()->user()->tenants()->first()->id ?? null;

        $companyPayments = $has('payments') && $currentTenantId
            ? Payment::where('tenant_id', $currentTenantId)->with('employee')->latest()->limit(10)->get() : collect();
        $recentIncomes = $has('incomes') && $currentTenantId
            ? Income::where('tenant_id', $currentTenantId)->latest()->limit(10)->get() : collect();
        $recentExpenses = $has('expenses') && $currentTenantId
            ? Expense::where('tenant_id', $currentTenantId)->latest()->limit(10)->get() : collect();

        // Get current tenant/company
        $company = Auth::user()->currentTenant();

        return view('dashboard.accountant', compact(
            'financialSummary', 'quickStats',
            'dailyStats', 'weeklyStats', 'cashFlowAnalysis',
            'incomeByCategory', 'expenseByCategory', 'expenseByMethod', 'transactionsByCategory',
            'paymentStatusBreakdown', 'outstandingReceivables',
            'companyPayments', 'recentIncomes', 'recentExpenses', 'company'
        ));
    }

    /**
     * Regular user sees limited overview
     */
    public function userDashboard()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Limited project view - filter by current user's tenant
        $currentTenantId = auth()->user()->current_tenant_id ?? auth()->user()->tenants()->first()->id ?? null;

        $projectsCount = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->count() : 0;
        $projectsThisMonth = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $recentProjects = $has('projects') && $currentTenantId
            ? Project::where('tenant_id', $currentTenantId)->latest()->limit(5)->get() : collect();

        // Available tenants (for join action when user has none)
        $availableTenants = class_exists('App\\Models\\Tenant')
            ? \App\Models\Tenant::select('id','name')->orderBy('name')->get()
            : collect();

        return view('dashboard.user', compact(
            'projectsCount', 'projectsThisMonth', 'recentProjects', 'availableTenants'
        ));
    }

    /**
     * Allow a user to join a tenant (simple self-join)
     */
    public function joinTenant(): \Illuminate\Http\RedirectResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        request()->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $tenantId = (int) request('tenant_id');
        try {
            $user->addToTenant($tenantId, 'user', false);
            $user->current_tenant_id = $tenantId;
            $user->save();
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to join tenant: ' . $e->getMessage());
        }

        return back()->with('success', 'You have joined the tenant successfully.');
    }

    /**
     * Display advanced analytics dashboard
     */
    public function analytics()
    {
        $user = Auth::user();

        // Check if user has permissions to view analytics
        if (!$user->hasRole(['super-admin', 'admin', 'manager']) &&
            !$user->hasPermission('analytics.view')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view analytics.');
        }

        // Get enhanced analytics data
        $analyticsData = [
            'financialSummary' => $this->statsService->getFinancialSummary(),
            'quickStats' => $this->statsService->getQuickStats(),
            'dailyStats' => $this->statsService->getDailyStats(30),
            'weeklyStats' => $this->statsService->getWeeklyStats(12),
            'cashFlowAnalysis' => $this->statsService->getCashFlowAnalysis(6),
            'topProjects' => $this->statsService->getTopProjects(10),
            'incomeByCategory' => $this->statsService->getIncomeByCategory(),
            'expenseByCategory' => $this->statsService->getExpenseByCategory(),
            'expenseByMethod' => $this->statsService->getExpenseByMethod(),
            'paymentStatusBreakdown' => $this->statsService->getPaymentStatusBreakdown(),
            'outstandingReceivables' => $this->statsService->getOutstandingReceivables(),
        ];

        return view('dashboard.analytics', $analyticsData);
    }

    /**
     * Get daily summary for calendar
     */
    public function calendarDailySummary()
    {
        $date = request('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        // Get incomes for the date
        $incomes = collect();
        $totalIncome = 0;
        $projectIncome = 0;

        if ($has('incomes', 'amount_received')) {
            $incomes = Income::with('project')
                ->whereDate('received_at', $date)
                ->get();
            $totalIncome = $incomes->sum('amount_received');
            $projectIncome = $incomes->whereNotNull('project_id')->sum('amount_received');
        }

        // Get expenses for the date
        $expenses = collect();
        $totalExpenses = 0;
        $projectExpenses = 0;
        $officeExpenses = 0;

        if ($has('expenses', 'total')) {
            $expenses = Expense::with(['project', 'category'])
                ->whereDate('date', $date)
                ->get();
            $totalExpenses = $expenses->sum('total');
            $projectExpenses = $expenses->whereNotNull('project_id')->sum('total');
            $officeExpenses = $expenses->whereNull('project_id')->sum('total');
        }

        // Group by projects with detailed breakdown
        $projectData = [];

        // Get projects with income on this date
        foreach ($incomes->whereNotNull('project_id')->groupBy('project_id') as $projectId => $projectIncomes) {
            $project = $projectIncomes->first()->project;
            if (!isset($projectData[$projectId])) {
                $projectData[$projectId] = [
                    'name' => $project ? $project->name : 'Unknown Project',
                    'income' => 0,
                    'expenses' => 0,
                    'materials' => 0,
                    'labor' => 0,
                    'designLabor' => 0,
                    'executionLabor' => 0,
                    'otherExpenses' => 0,
                    'incomeDetails' => [],
                    'expenseDetails' => []
                ];
            }
            $projectData[$projectId]['income'] = $projectIncomes->sum('amount_received');
            $projectData[$projectId]['incomeDetails'] = $projectIncomes->map(function ($income) {
                return [
                    'description' => $income->description ?? 'Income',
                    'amount' => $income->amount_received
                ];
            })->values()->toArray();
        }

        // Get projects with expenses on this date
        foreach ($expenses->whereNotNull('project_id')->groupBy('project_id') as $projectId => $projectExps) {
            $project = $projectExps->first()->project;
            if (!isset($projectData[$projectId])) {
                $projectData[$projectId] = [
                    'name' => $project ? $project->name : 'Unknown Project',
                    'income' => 0,
                    'expenses' => 0,
                    'materials' => 0,
                    'labor' => 0,
                    'designLabor' => 0,
                    'executionLabor' => 0,
                    'otherExpenses' => 0,
                    'incomeDetails' => [],
                    'expenseDetails' => []
                ];
            }
            $projectData[$projectId]['expenses'] = $projectExps->sum('total');

            // Calculate materials, labor, and other expenses
            $projectData[$projectId]['materials'] = $projectExps->where('expense_type', 'materials')->sum('total');
            $projectData[$projectId]['labor'] = $projectExps->where('expense_type', 'labor')->sum('total');
            $projectData[$projectId]['designLabor'] = $projectExps->where('expense_type', 'labor')->where('phase', 'design')->sum('total');
            $projectData[$projectId]['executionLabor'] = $projectExps->where('expense_type', 'labor')->where('phase', 'execution')->sum('total');
            $projectData[$projectId]['otherExpenses'] = $projectExps->whereNotIn('expense_type', ['materials', 'labor'])->sum('total');

            $projectData[$projectId]['expenseDetails'] = $projectExps->map(function ($expense) {
                return [
                    'description' => $expense->notes ?? $expense->item_name ?? 'Expense',
                    'category' => $expense->category->name ?? null,
                    'expense_type' => $expense->expense_type ?? null,
                    'phase' => $expense->phase ?? null,
                    'item_name' => $expense->item_name ?? null,
                    'quantity' => $expense->quantity ?? null,
                    'unit' => $expense->unit ?? null,
                    'amount' => $expense->total
                ];
            })->values()->toArray();
        }

        // Calculate balance for each project
        foreach ($projectData as $projectId => $pData) {
            $projectData[$projectId]['balance'] = $pData['income'] - $pData['expenses'];
        }

        // Format income details (all incomes)
        $incomeDetails = $incomes->map(function ($income) {
            return [
                'description' => $income->description ?? 'Income',
                'project' => $income->project ? $income->project->name : null,
                'amount' => $income->amount_received
            ];
        })->values()->toArray();

        // Format expense details (all expenses)
        $expenseDetails = $expenses->map(function ($expense) {
            return [
                'description' => $expense->notes ?? $expense->item_name ?? 'Expense',
                'category' => $expense->category->name ?? null,
                'expense_type' => $expense->expense_type ?? null,
                'project' => $expense->project ? $expense->project->name : 'Office',
                'amount' => $expense->total
            ];
        })->values()->toArray();

        // Office expenses details
        $officeExpenseDetails = $expenses->whereNull('project_id')->map(function ($expense) {
            return [
                'description' => $expense->notes ?? $expense->item_name ?? 'Expense',
                'category' => $expense->category->name ?? null,
                'amount' => $expense->total
            ];
        })->values()->toArray();

        return response()->json([
            'date' => $date,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'balance' => $totalIncome - $totalExpenses,
            'projectIncome' => $projectIncome,
            'projectExpenses' => $projectExpenses,
            'officeExpenses' => $officeExpenses,
            'projects' => array_values($projectData),
            'incomeDetails' => $incomeDetails,
            'expenseDetails' => $expenseDetails,
            'officeExpenseDetails' => $officeExpenseDetails
        ]);
    }

    /**
     * Get month data for calendar indicators
     */
    public function calendarMonthData()
    {
        $year = request('year', date('Y'));
        $month = request('month', date('m'));

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $has = function (string $table, ?string $column = null): bool {
            if (! Schema::hasTable($table)) {
                return false;
            }
            return $column ? Schema::hasColumn($table, $column) : true;
        };

        $dates = [];

        // Get income data for the month
        if ($has('incomes', 'amount_received')) {
            $incomesByDate = Income::selectRaw('DATE(received_at) as date, SUM(amount_received) as total')
                ->whereBetween('received_at', [$startDate, $endDate])
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray();

            foreach ($incomesByDate as $date => $amount) {
                if (!isset($dates[$date])) {
                    $dates[$date] = ['income' => 0, 'expenses' => 0];
                }
                $dates[$date]['income'] = (float) $amount;
            }
        }

        // Get expense data for the month
        if ($has('expenses', 'total')) {
            $expensesByDate = Expense::selectRaw('DATE(date) as date, SUM(total) as total')
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray();

            foreach ($expensesByDate as $date => $amount) {
                if (!isset($dates[$date])) {
                    $dates[$date] = ['income' => 0, 'expenses' => 0];
                }
                $dates[$date]['expenses'] = (float) $amount;
            }
        }

        return response()->json([
            'year' => $year,
            'month' => $month,
            'dates' => $dates
        ]);
    }
}
