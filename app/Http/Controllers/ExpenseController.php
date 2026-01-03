<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Client;
use App\Traits\Downloadable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\RbacFilterService;
use Carbon\Carbon;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    use Downloadable;

    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        $this->rbacFilterService = $rbacFilterService;
    }

    /**
     * Items per page for pagination.
     */
    protected int $perPage = 15;

    /**
     * Display a listing of the expenses.
     *
     * Also prepares a minimal daily-by-category stats structure:
     *  - $categories: array of distinct categories
     *  - $dailyTotals: [ 'YYYY-MM-DD' => [ 'Category' => total, ... ], ... ]
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all expenses with relationships
        $allExpenses = Expense::with('project')->latest('date')->get();

        // Separate expenses into office and project categories
        $officeExpenses = $allExpenses->whereNull('project_id');
        $projectExpenses = $allExpenses->whereNotNull('project_id');

        // Calculate totals
        $officeTotal = $officeExpenses->sum('amount');
        $projectTotal = $projectExpenses->sum('amount');

        // Group project expenses by project
        $expensesByProject = $projectExpenses->groupBy('project_id')->map(function($expenses, $projectId) {
            $project = $expenses->first()->project;

            // Group by expense type
            $materials = $expenses->where('expense_type', 'materials');
            $labor = $expenses->where('expense_type', 'labor');
            $other = $expenses->whereNotIn('expense_type', ['materials', 'labor']);

            // Group labor by phase
            $designLabor = $labor->where('phase', 'design');
            $executionLabor = $labor->where('phase', 'execution');

            return [
                'project' => $project,
                'project_name' => $project ? $project->name : 'Unknown Project',
                'total' => $expenses->sum('amount'),
                'count' => $expenses->count(),
                'materials' => [
                    'expenses' => $materials,
                    'total' => $materials->sum('amount'),
                    'count' => $materials->count(),
                ],
                'labor' => [
                    'expenses' => $labor,
                    'total' => $labor->sum('amount'),
                    'count' => $labor->count(),
                    'design' => [
                        'expenses' => $designLabor,
                        'total' => $designLabor->sum('amount'),
                        'count' => $designLabor->count(),
                    ],
                    'execution' => [
                        'expenses' => $executionLabor,
                        'total' => $executionLabor->sum('amount'),
                        'count' => $executionLabor->count(),
                    ],
                ],
                'other' => [
                    'expenses' => $other,
                    'total' => $other->sum('amount'),
                    'count' => $other->count(),
                ],
                'all_expenses' => $expenses,
            ];
        })->sortByDesc('total');

        // Calculate overall totals by type
        $totalMaterials = $projectExpenses->where('expense_type', 'materials')->sum('amount');
        $totalLabor = $projectExpenses->where('expense_type', 'labor')->sum('amount');
        $totalDesignLabor = $projectExpenses->where('expense_type', 'labor')->where('phase', 'design')->sum('amount');
        $totalExecutionLabor = $projectExpenses->where('expense_type', 'labor')->where('phase', 'execution')->sum('amount');

        // Combined Payments and Expenses totals
        $monthStart = Carbon::now()->startOfMonth();
        $endOfToday = Carbon::now()->endOfDay();
        $today = Carbon::today();

        $paymentsTotal = Payment::sum('amount') ?? 0;
        $paymentsThisMonth = Payment::whereBetween('created_at', [$monthStart, $endOfToday])->sum('amount') ?? 0;
        $paymentsToday = Payment::whereDate('created_at', $today)->sum('amount') ?? 0;

        // Use query-based sums for expenses to avoid collection date filters
        $expensesThisMonthTotal = Expense::whereBetween('date', [$monthStart, $endOfToday])->sum('amount');
        $expensesTodayTotal = Expense::whereDate('date', $today)->sum('amount');

        $allExpensesTotal = $paymentsTotal + ($officeTotal + $projectTotal);
        $allExpensesThisMonth = $paymentsThisMonth + $expensesThisMonthTotal;
        $allExpensesToday = $paymentsToday + $expensesTodayTotal;

        // Group expenses by category with totals and counts (for summary)
        $expensesByCategory = $allExpenses->groupBy('category')->map(function($group, $category) {
            return [
                'category' => $category ?: 'Uncategorized',
                'count' => $group->count(),
                'total' => $group->sum('amount'),
                'expenses' => $group,
            ];
        })->sortByDesc('total');

        return view('expenses.index', [
            'officeExpenses' => $officeExpenses,
            'projectExpenses' => $projectExpenses,
            'officeTotal' => $officeTotal,
            'projectTotal' => $projectTotal,
            'expensesByProject' => $expensesByProject,
            'expensesByCategory' => $expensesByCategory,
            'totalMaterials' => $totalMaterials,
            'totalLabor' => $totalLabor,
            'totalDesignLabor' => $totalDesignLabor,
            'totalExecutionLabor' => $totalExecutionLabor,
            'paymentsTotal' => $paymentsTotal,
            'paymentsThisMonth' => $paymentsThisMonth,
            'paymentsToday' => $paymentsToday,
            'allExpensesTotal' => $allExpensesTotal,
            'allExpensesThisMonth' => $allExpensesThisMonth,
            'allExpensesToday' => $allExpensesToday,
        ]);
    }

    /**
     * Show the form for creating a new expense.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $projects = Project::orderBy('name')->pluck('name', 'id');
        $clients  = Client::orderBy('name')->pluck('name', 'id');

        return view('expenses.create', compact('projects', 'clients'));
    }

    /**
     * Store a newly created expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $this->validateExpense($request);

        $data = $this->ensureTenantId($data);
        Expense::create($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified expense.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\View\View
     */
    public function show(Expense $expense)
    {
        // If you want to ensure project/client/user are loaded:
        $expense->load(['project', 'client', 'user']);

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\View\View
     */
    public function edit(Expense $expense)
    {
        $projects = Project::orderBy('name')->pluck('name', 'id');
        $clients  = Client::orderBy('name')->pluck('name', 'id');

        return view('expenses.edit', compact('expense', 'projects', 'clients'));
    }

    /**
     * Update the specified expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Expense $expense)
    {
        $data = $this->validateExpense($request);

        $expense->update($data);

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Validate expense request data (shared between store & update).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function validateExpense(Request $request): array
    {
        return $request->validate([
            'date'         => 'required|date',
            'category'     => 'required|string|max:255',
            'expense_type' => 'nullable|string|max:255',
            'phase'        => 'nullable|string|in:design,execution',
            'item_name'    => 'nullable|string|max:255',
            'quantity'     => 'nullable|numeric|min:0',
            'unit'         => 'nullable|string|max:50',
            'unit_price'   => 'nullable|numeric|min:0',
            'description'  => 'nullable|string',
            'project_id'   => 'nullable|exists:projects,id',
            'client_id'    => 'nullable|exists:clients,id',
            'amount'       => 'required|numeric',
            'method'       => 'nullable|string|max:255',
            'status'       => 'nullable|string|max:255',
            'user_id'      => 'nullable|exists:users,id',
        ]);
    }

    /**
     * Export expenses as CSV
     */
    public function exportCsv(Request $request)
    {
        // Check permission for expense export
        if (!Auth::user()->can('expenses.export')) {
            abort(403, 'You do not have permission to export expenses.');
        }

        $filename = $request->get('filename', 'expenses');

        $expenses = Expense::with(['project', 'client'])->latest()->get();

        $headers = [
            'id' => 'ID',
            'category' => 'Category',
            'description' => 'Description',
            'amount' => 'Amount (RWF)',
            'project_name' => 'Project',
            'client_name' => 'Client',
            'method' => 'Payment Method',
            'status' => 'Status',
            'created_at' => 'Created Date'
        ];

        // Transform data for CSV
        $csvData = $expenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'category' => $expense->category ?? 'N/A',
                'description' => $expense->description ?? 'N/A',
                'amount' => $expense->amount ?? 0,
                'project_name' => $expense->project ? $expense->project->name : 'N/A',
                'client_name' => $expense->client ? $expense->client->name : 'N/A',
                'method' => $expense->method ?? 'N/A',
                'status' => ucfirst($expense->status ?? 'completed'),
                'created_at' => $expense->created_at->format('Y-m-d H:i:s')
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export expenses as PDF
     */
    public function exportPdf(Request $request)
    {
        // Check permission for expense export
        if (!Auth::user()->can('expenses.export')) {
            abort(403, 'You do not have permission to export expenses.');
        }

        $filename = $request->get('filename', 'expenses');

        $expenses = Expense::with(['project', 'client'])->latest()->get();

        $html = $this->generatePdfHtml('exports.financial-pdf', [
            'data' => $expenses,
            'title' => 'Expenses Report',
            'subtitle' => 'Complete list of all expenses',
            'totalRecords' => $expenses->count(),
            'showProject' => true
        ]);

        return $this->downloadPdf($html, $filename);
    }
}
