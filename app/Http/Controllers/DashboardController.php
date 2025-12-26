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
        if (!$user->hasRole(['super-admin', 'admin', 'manager', 'accountant']) &&
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

        // Worker payments (using employee_id to identify worker-related payments)
        $workerPaymentsToday = $has('payments', 'employee_id')
            ? Payment::whereDate('created_at', $today)->whereNotNull('employee_id')->sum('amount')
            : 0;
        $workerPaymentsThisMonth = $has('payments', 'employee_id')
            ? Payment::whereBetween('created_at', [$startOfMonth, $endOfToday])->whereNotNull('employee_id')->sum('amount')
            : 0;
        $recentWorkerPayments = $has('payments', 'employee_id')
            ? Payment::whereNotNull('employee_id')->latest()->limit(7)->get()
            : collect();

        // Payments
        $paymentsTotal = $has('payments', 'amount') ? Payment::sum('amount') : 0;
        $paymentsThisMonth = $has('payments', 'amount')
            ? Payment::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
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
        $recentIncomes = $has('incomes') ? Income::latest()->limit(7)->get() : collect();

        // Expenses
        $expensesTotal = $has('expenses', 'amount') ? Expense::sum('amount') : 0;
        $expensesThisMonth = $has('expenses', 'amount')
            ? Expense::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
            : 0;
        $recentExpenses = $has('expenses') ? Expense::latest()->limit(7)->get() : collect();

        // Expenses by category (Office vs Project)
        $officeExpenses = $has('expenses') ? Expense::whereNull('project_id')->sum('amount') : 0;
        $projectExpenses = $has('expenses') ? Expense::whereNotNull('project_id')->sum('amount') : 0;
        $officeExpensesThisMonth = $has('expenses')
            ? Expense::whereNull('project_id')->whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
            : 0;
        $projectExpensesThisMonth = $has('expenses')
            ? Expense::whereNotNull('project_id')->whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
            : 0;

        // Projects
        $projectsCount = $has('projects') ? Project::count() : 0;
        $projectsThisMonth = $has('projects')
            ? Project::whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $projectsTotal = $has('projects', 'contract_value') ? Project::sum('contract_value') : 0;
        $recentProjects = $has('projects') ? Project::latest()->limit(7)->get() : collect();

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

            $dailyExpenses[] = $has('expenses', 'amount')
                ? Expense::whereDate('created_at', $dt)->sum('amount')
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

            $expensesMonthly[] = $has('expenses', 'amount')
                ? Expense::whereBetween('created_at', [$mStart, $mEnd])->sum('amount')
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
            'workerPaymentsToday', 'workerPaymentsThisMonth', 'recentWorkerPayments',
            'paymentsTotal', 'paymentsThisMonth', 'recentPayments',
            'recentTransactions', 'transactionsThisMonth',
            'incomesTotal', 'incomesThisMonth', 'recentIncomes',
            'expensesTotal', 'expensesThisMonth', 'recentExpenses',
            'officeExpenses', 'projectExpenses', 'officeExpensesThisMonth', 'projectExpensesThisMonth',
            'projectsCount', 'projectsThisMonth', 'projectsTotal', 'recentProjects',
            'totalClients', 'activeClients', 'clientsThisMonth', 'totalOrders',
            'projectStats', 'months', 'paymentsMonthly', 'expensesMonthly', 'incomeMonthly',
            'dailyDates', 'dailyRevenue', 'dailyExpenses', 'dailyTasks'
        ));
    }

    /**
     * Accountant sees only financial data with enhanced analytics
     */
    private function accountantDashboard()
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

        $recentPayments = $has('payments') ? Payment::latest()->limit(10)->get() : collect();
        $recentIncomes = $has('incomes') ? Income::latest()->limit(10)->get() : collect();
        $recentExpenses = $has('expenses') ? Expense::latest()->limit(10)->get() : collect();

        return view('dashboard.accountant', compact(
            'financialSummary', 'quickStats',
            'dailyStats', 'weeklyStats', 'cashFlowAnalysis',
            'incomeByCategory', 'expenseByCategory', 'expenseByMethod', 'transactionsByCategory',
            'paymentStatusBreakdown', 'outstandingReceivables',
            'recentPayments', 'recentIncomes', 'recentExpenses'
        ));
    }

    /**
     * Manager sees project and employee data with analytics
     */
    private function managerDashboard()
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

        // Workers/Employees
        $totalWorkers = $has('workers') ? Worker::count() : 0;
        $activeWorkers = $has('workers', 'status')
            ? Worker::where('status', 'active')->count()
            : $totalWorkers;
        $recentWorkers = $has('workers') ? Worker::latest()->limit(10)->get() : collect();

        // Projects
        $projectsCount = $has('projects') ? Project::count() : 0;
        $projectsThisMonth = $has('projects')
            ? Project::whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $projectsTotal = $has('projects', 'contract_value') ? Project::sum('contract_value') : 0;
        $recentProjects = $has('projects') ? Project::latest()->limit(10)->get() : collect();

        // Project Stats with payments
        $projectStats = collect();
        if ($has('projects') && $has('incomes', 'amount_received')) {
            $projectStats = Project::with('incomes')
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

            $projectsMonthly[] = $has('projects', 'contract_value')
                ? Project::whereBetween('created_at', [$mStart, $mEnd])->sum('contract_value')
                : 0;
        }

        return view('dashboard.manager', compact(
            'financialSummary', 'topProjects', 'weeklyStats', 'incomeByCategory',
            'totalWorkers', 'activeWorkers', 'recentWorkers',
            'projectsCount', 'projectsThisMonth', 'projectsTotal', 'recentProjects',
            'projectStats', 'months', 'projectsMonthly'
        ));
    }

    /**
     * Regular user sees limited overview
     */
    private function userDashboard()
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

        // Limited project view
        $projectsCount = $has('projects') ? Project::count() : 0;
        $projectsThisMonth = $has('projects')
            ? Project::whereBetween('created_at', [$startOfMonth, $endOfToday])->count()
            : 0;
        $recentProjects = $has('projects') ? Project::latest()->limit(5)->get() : collect();

        return view('dashboard.user', compact(
            'projectsCount', 'projectsThisMonth', 'recentProjects'
        ));
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
}
