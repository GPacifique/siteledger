<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Project;
use App\Models\User;
use App\Models\Worker;
use App\Models\Task;
use App\Models\Product;
use App\Utils\CurrencyFormatter;

class DashboardStatsService
{
    /**
     * Check if table and column exist
     */
    private function has(string $table, ?string $column = null): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }
        return $column ? Schema::hasColumn($table, $column) : true;
    }

    /**
     * Get daily income/expense statistics for the last 30 days
     * Returns data for charts showing trends
     */
    public function getDailyStats($days = 30)
    {
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays($days - 1);

        $dailyData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $endDate->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');

            $incomeAmount = 0;
            $expenseAmount = 0;

            if ($this->has('incomes', 'amount_received')) {
                $incomeAmount = Income::whereDate('received_at', $dateStr)
                    ->sum('amount_received');
            }

            if ($this->has('expenses', 'amount')) {
                $expenseAmount = Expense::whereDate('date', $dateStr)
                    ->sum('amount');
            }

            $dailyData[] = [
                'date' => $dateStr,
                'date_formatted' => $date->format('M d'),
                'income' => (float) $incomeAmount,
                'expense' => (float) $expenseAmount,
                'balance' => (float) ($incomeAmount - $expenseAmount),
            ];
        }

        return $dailyData;
    }

    /**
     * Get income totals by category for the current period
     */
    public function getIncomeByCategory($startDate = null, $endDate = null)
    {
        if (!$this->has('incomes')) {
            return [];
        }

        $startDate = $startDate ?? Carbon::today()->startOfMonth();
        $endDate = $endDate ?? Carbon::today()->endOfDay();

        $incomes = Income::with('project')
            ->whereBetween('received_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($income) => $income->project->name ?? 'Uncategorized')
            ->map(function($group) {
                return [
                    'category' => $group->first()->project->name ?? 'Uncategorized',
                    'total' => (float) $group->sum('amount_received'),
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->toArray();

        return $incomes;
    }

    /**
     * Get expense totals by category
     */
    public function getExpenseByCategory($startDate = null, $endDate = null)
    {
        if (!$this->has('expenses')) {
            return [];
        }

        $startDate = $startDate ?? Carbon::today()->startOfMonth();
        $endDate = $endDate ?? Carbon::today()->endOfDay();

        $expenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($expense) => $expense->category ?? 'Uncategorized')
            ->map(function($group) {
                return [
                    'category' => $group->first()->category ?? 'Uncategorized',
                    'total' => (float) $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->toArray();

        return $expenses;
    }

    /**
     * Get weekly statistics (last 12 weeks)
     */
    public function getWeeklyStats($weeks = 12)
    {
        $endDate = Carbon::today();
        $weeklyData = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekEnd = $endDate->copy()->subWeeks($i)->endOfWeek();
            $weekStart = $weekEnd->copy()->startOfWeek();

            $incomeAmount = 0;
            $expenseAmount = 0;

            if ($this->has('incomes', 'amount_received')) {
                $incomeAmount = Income::whereBetween('received_at', [$weekStart, $weekEnd])
                    ->sum('amount_received');
            }

            if ($this->has('expenses', 'amount')) {
                $expenseAmount = Expense::whereBetween('date', [$weekStart, $weekEnd])
                    ->sum('amount');
            }

            $weeklyData[] = [
                'week_start' => $weekStart->format('Y-m-d'),
                'week_end' => $weekEnd->format('Y-m-d'),
                'week_label' => $weekStart->format('M d') . ' - ' . $weekEnd->format('M d'),
                'income' => (float) $incomeAmount,
                'expense' => (float) $expenseAmount,
                'balance' => (float) ($incomeAmount - $expenseAmount),
            ];
        }

        return $weeklyData;
    }

    /**
     * Get comprehensive financial summary
     */
    public function getFinancialSummary()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();
        $startOfYear = $today->copy()->startOfYear();

        $summary = [
            'today' => [
                'income' => 0,
                'expense' => 0,
                'balance' => 0,
            ],
            'this_month' => [
                'income' => 0,
                'expense' => 0,
                'balance' => 0,
            ],
            'this_year' => [
                'income' => 0,
                'expense' => 0,
                'balance' => 0,
            ],
            'all_time' => [
                'income' => 0,
                'expense' => 0,
                'balance' => 0,
            ],
        ];

        // Today
        if ($this->has('incomes', 'amount_received')) {
            $summary['today']['income'] = (float) Income::whereDate('received_at', $today)
                ->sum('amount_received');
        }
        if ($this->has('expenses', 'amount')) {
            $summary['today']['expense'] = (float) Expense::whereDate('date', $today)
                ->sum('amount');
        }
        $summary['today']['balance'] = $summary['today']['income'] - $summary['today']['expense'];

        // This Month
        if ($this->has('incomes', 'amount_received')) {
            $summary['this_month']['income'] = (float) Income::whereBetween('received_at', [$startOfMonth, $endOfToday])
                ->sum('amount_received');
        }
        if ($this->has('expenses', 'amount')) {
            $summary['this_month']['expense'] = (float) Expense::whereBetween('date', [$startOfMonth, $endOfToday])
                ->sum('amount');
        }
        $summary['this_month']['balance'] = $summary['this_month']['income'] - $summary['this_month']['expense'];

        // This Year
        if ($this->has('incomes', 'amount_received')) {
            $summary['this_year']['income'] = (float) Income::whereBetween('received_at', [$startOfYear, $endOfToday])
                ->sum('amount_received');
        }
        if ($this->has('expenses', 'amount')) {
            $summary['this_year']['expense'] = (float) Expense::whereBetween('date', [$startOfYear, $endOfToday])
                ->sum('amount');
        }
        $summary['this_year']['balance'] = $summary['this_year']['income'] - $summary['this_year']['expense'];

        // All Time
        if ($this->has('incomes', 'amount_received')) {
            $summary['all_time']['income'] = (float) Income::sum('amount_received');
        }
        if ($this->has('expenses', 'amount')) {
            $summary['all_time']['expense'] = (float) Expense::sum('amount');
        }
        $summary['all_time']['balance'] = $summary['all_time']['income'] - $summary['all_time']['expense'];

        return $summary;
    }

    /**
     * Get top performing projects by income
     */
    public function getTopProjects($limit = 5)
    {
        if (!$this->has('projects') || !$this->has('incomes')) {
            return [];
        }

        try {
            // Use Eloquent models to ensure tenant scoping
            return \App\Models\Project::withSum('incomes', 'amount_received')
                ->select('id', 'name', 'contract_value')
                ->orderByDesc('incomes_sum_amount_received')
                ->limit($limit)
                ->get()
                ->map(function($project) {
                    $income = (float) ($project->incomes_sum_amount_received ?? 0);
                    $target = (float) ($project->contract_value ?? 0);
                    $completion_percent = $target > 0 ? round(($income / $target) * 100, 2) : 0;

                    return [
                        'id' => $project->id,
                        'name' => $project->name,
                        'income' => $income,
                        'target' => $target,
                        'completion_percent' => $completion_percent,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            // Fallback to empty array if there's an error
            \Log::warning('Error getting top projects: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cash flow analysis (income vs expenses trend)
     */
    public function getCashFlowAnalysis($months = 6)
    {
        $endDate = Carbon::today();
        $cashFlow = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthEnd = $endDate->copy()->subMonths($i)->endOfMonth();
            $monthStart = $monthEnd->copy()->startOfMonth();

            $incomeAmount = 0;
            $expenseAmount = 0;

            if ($this->has('incomes', 'amount_received')) {
                $incomeAmount = Income::whereBetween('received_at', [$monthStart, $monthEnd])
                    ->sum('amount_received');
            }

            if ($this->has('expenses', 'amount')) {
                $expenseAmount = Expense::whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('amount');
            }

            $cashFlow[] = [
                'month' => $monthStart->format('M Y'),
                'month_short' => $monthStart->format('M'),
                'income' => (float) $incomeAmount,
                'expense' => (float) $expenseAmount,
                'net_cash_flow' => (float) ($incomeAmount - $expenseAmount),
                'margin' => $incomeAmount > 0 ? round((($incomeAmount - $expenseAmount) / $incomeAmount) * 100, 2) : 0,
            ];
        }

        return $cashFlow;
    }

    /**
     * Get payment status breakdown
     */
    public function getPaymentStatusBreakdown()
    {
        if (!$this->has('incomes', 'payment_status')) {
            return [];
        }

        $statuses = Income::get()
            ->groupBy('payment_status')
            ->map(function($group) {
                return [
                    'status' => $group->first()->payment_status,
                    'count' => $group->count(),
                    'total' => (float) $group->sum('amount_received'),
                ];
            })
            ->values()
            ->toArray();

        return $statuses;
    }

    /**
     * Get outstanding receivables (unpaid invoices)
     */
    public function getOutstandingReceivables()
    {
        if (!$this->has('incomes')) {
            return [
                'total_outstanding' => 0,
                'count' => 0,
                'pending_count' => 0,
                'overdue_count' => 0,
            ];
        }

        $outstanding = Income::whereIn('payment_status', ['Pending', 'Overdue', 'partially paid'])
            ->get();

        return [
            'total_outstanding' => (float) $outstanding->sum('amount_remaining'),
            'count' => $outstanding->count(),
            'pending_count' => $outstanding->where('payment_status', 'Pending')->count(),
            'overdue_count' => $outstanding->where('payment_status', 'Overdue')->count(),
            'partially_paid_count' => $outstanding->where('payment_status', 'partially paid')->count(),
        ];
    }

    /**
     * Get expense breakdown by payment method
     */
    public function getExpenseByMethod($startDate = null, $endDate = null)
    {
        if (!$this->has('expenses')) {
            return [];
        }

        $startDate = $startDate ?? Carbon::today()->startOfMonth();
        $endDate = $endDate ?? Carbon::today()->endOfDay();

        $methods = Expense::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($expense) => $expense->method ?? 'Unknown')
            ->map(function($group) {
                return [
                    'method' => $group->first()->method ?? 'Unknown',
                    'total' => (float) $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->toArray();

        return $methods;
    }

    /**
     * Get transaction totals by category (optionally filtered by type)
     * Structure: [ { category: string, total: float, count: int } ]
     */
    public function getTransactionsByCategory($startDate = null, $endDate = null, $type = null)
    {
        if (!$this->has('transactions')) {
            return [];
        }

        $startDate = $startDate ?? Carbon::today()->startOfMonth();
        $endDate = $endDate ?? Carbon::today()->endOfDay();

        $query = Transaction::whereBetween('date', [$startDate, $endDate]);

        if ($type) {
            $query->where('type', $type);
        }

        $categories = $query->get()
            ->groupBy(fn($transaction) => $transaction->category ?? 'Uncategorized')
            ->map(function($group) {
                return [
                    'category' => $group->first()->category ?? 'Uncategorized',
                    'total' => (float) $group->sum('amount'),
                    'count' => (int) $group->count(),
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->toArray();

        return $categories;
    }

    /**
     * Get quick stats for dashboard cards
     */
    public function getQuickStats()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        return [
            'today_income' => $this->has('incomes', 'amount_received')
                ? (float) Income::whereDate('received_at', $today)->sum('amount_received')
                : 0,
            'today_expense' => $this->has('expenses', 'amount')
                ? (float) Expense::whereDate('date', $today)->sum('amount')
                : 0,
            'month_income' => $this->has('incomes', 'amount_received')
                ? (float) Income::whereBetween('received_at', [$startOfMonth, $endOfToday])->sum('amount_received')
                : 0,
            'month_expense' => $this->has('expenses', 'amount')
                ? (float) Expense::whereBetween('date', [$startOfMonth, $endOfToday])->sum('amount')
                : 0,
            'outstanding' => $this->getOutstandingReceivables()['total_outstanding'],
            'total_transactions' => $this->has('transactions')
                ? (int) Transaction::count()
                : 0,
        ];
    }

    /**
     * Get Admin Dashboard Stats
     */
    public function getAdminStats()
    {
        return [
            'totalUsers' => User::count(),
            'activeProjects' => $this->has('projects')
                ? Project::whereIn('status', ['active', 'in_progress'])->count()
                : 0,
            'totalRevenue' => $this->has('projects')
                ? (float) Project::sum('contract_value')
                : 0,
            'systemHealth' => '98%',
        ];
    }

    /**
     * Get Accountant Dashboard Stats
     */
    public function getAccountantStats()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $totalIncome = $this->has('incomes', 'amount_received')
            ? (float) Income::whereBetween('received_at', [$startOfMonth, $endOfToday])->sum('amount_received')
            : 0;

        $totalExpense = $this->has('expenses', 'amount')
            ? (float) Expense::whereBetween('date', [$startOfMonth, $endOfToday])->sum('amount')
            : 0;

        return [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpense,
            'netProfit' => $totalIncome - $totalExpense,
            'unpaidInvoices' => $this->has('incomes')
                ? Income::whereIn('payment_status', ['Pending', 'Overdue'])->count()
                : 0,
        ];
    }

    /**
     * Get Site Manager Dashboard Stats
     */
    public function getSiteManagerStats()
    {
        return [
            'activeProjects' => $this->has('projects')
                ? Project::whereIn('status', ['active', 'in_progress'])->count()
                : 0,
            'teamMembers' => $this->has('workers')
                ? Worker::count()
                : 0,
            'tasksCompleted' => $this->has('tasks')
                ? Task::where('status', 'completed')->count()
                : 0,
            'onTimeRate' => '92%',
        ];
    }

    /**
     * Get Store Keeper Dashboard Stats
     */
    public function getStoreKeeperStats()
    {
        return [
            'totalProducts' => $this->has('products')
                ? Product::count()
                : 0,
            'lowStockItems' => $this->has('products')
                ? Product::where('quantity_on_hand', '<', 10)->count()
                : 0,
            'recentOrders' => $this->has('payments')
                ? Payment::whereDate('created_at', Carbon::today())->count()
                : 0,
            'pendingDeliveries' => $this->has('payments')
                ? Payment::whereIn('status', ['pending', 'pending_delivery'])->count()
                : 0,
        ];
    }

    /**
     * Get System Admin Dashboard Stats
     */
    public function getSystemAdminStats()
    {
        return [
            'systemStatus' => 'Healthy',
            'activeUsers' => User::count(),
            'apiUptime' => 99.9,
            'diskUsage' => 65,
        ];
    }

    /**
     * Get Daily Card Statistics
     */
    public function getDailyCardStats()
    {
        $today = Carbon::today();

        return [
            'income' => $this->has('incomes', 'amount_received')
                ? (float) Income::whereDate('received_at', $today)->sum('amount_received')
                : 0,
            'expense' => $this->has('expenses', 'amount')
                ? (float) Expense::whereDate('date', $today)->sum('amount')
                : 0,
            'payment' => $this->has('payments', 'amount')
                ? (float) Payment::whereDate('created_at', $today)->sum('amount')
                : 0,
            'transaction' => $this->has('transactions', 'amount')
                ? (float) Transaction::whereDate('created_at', $today)->sum('amount')
                : 0,
        ];
    }

    /**
     * Get Monthly Card Statistics
     */
    public function getMonthlyCardStats()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        return [
            'income' => $this->has('incomes', 'amount_received')
                ? (float) Income::whereBetween('received_at', [$startOfMonth, $endOfToday])->sum('amount_received')
                : 0,
            'expense' => $this->has('expenses', 'amount')
                ? (float) Expense::whereBetween('date', [$startOfMonth, $endOfToday])->sum('amount')
                : 0,
            'payment' => $this->has('payments', 'amount')
                ? (float) Payment::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
                : 0,
            'transaction' => $this->has('transactions', 'amount')
                ? (float) Transaction::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('amount')
                : 0,
        ];
    }

    /**
     * Get Yearly Card Statistics
     */
    public function getYearlyCardStats()
    {
        $today = Carbon::today();
        $startOfYear = $today->copy()->startOfYear();
        $endOfToday = $today->endOfDay();

        return [
            'income' => $this->has('incomes', 'amount_received')
                ? (float) Income::whereBetween('received_at', [$startOfYear, $endOfToday])->sum('amount_received')
                : 0,
            'expense' => $this->has('expenses', 'amount')
                ? (float) Expense::whereBetween('date', [$startOfYear, $endOfToday])->sum('amount')
                : 0,
            'payment' => $this->has('payments', 'amount')
                ? (float) Payment::whereBetween('created_at', [$startOfYear, $endOfToday])->sum('amount')
                : 0,
            'transaction' => $this->has('transactions', 'amount')
                ? (float) Transaction::whereBetween('created_at', [$startOfYear, $endOfToday])->sum('amount')
                : 0,
        ];
    }
}
