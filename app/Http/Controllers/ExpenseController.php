<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
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
     * Display a listing of the expenses with improved filtering and pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Base query with optimized eager loading
        $query = Expense::with(['category', 'project:id,name', 'user:id,name'])
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        // Apply RBAC filtering
        $query = $this->rbacFilterService->filterExpenses($query);

        // Advanced filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'LIKE', "%{$search}%")
                  ->orWhere('notes', 'LIKE', "%{$search}%")
                  ->orWhere('expense_type', 'LIKE', "%{$search}%")
                  ->orWhereHas('project', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('category', function($q3) use ($search) {
                      $q3->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('expense_type')) {
            $query->where('expense_type', $request->expense_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('phase')) {
            $query->where('phase', $request->phase);
        }

        // Get paginated results
        $expenses = $query->paginate($this->perPage)->withQueryString();

        // Calculate statistics efficiently
        $statsQuery = clone $query;
        $allExpenses = $statsQuery->get();

        $statistics = $this->calculateExpenseStatistics($allExpenses);

        // Get filter options
        $categories = ExpenseCategory::where('active', true)->orderBy('name')->get();
        $projects = \App\Models\Project::orderBy('name')->get();
        $expenseTypes = ['materials', 'labor', 'office', 'equipment', 'transport'];
        $phases = ['design', 'execution'];

        return view('expenses.index', compact(
            'expenses',
            'categories',
            'projects',
            'expenseTypes',
            'phases',
            'statistics'
        ));
    }

    /**
     * Calculate comprehensive expense statistics
     */
    private function calculateExpenseStatistics($expenses)
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');
        $thisYear = now()->format('Y');

        return [
            'grand_total' => $expenses->sum('total'),
            'today_total' => $expenses->where('date', $today)->sum('total'),
            'month_total' => $expenses->filter(function($expense) use ($thisMonth) {
                return $expense->date->format('Y-m') === $thisMonth;
            })->sum('total'),
            'year_total' => $expenses->filter(function($expense) use ($thisYear) {
                return $expense->date->format('Y') === $thisYear;
            })->sum('total'),
            'by_type' => $expenses->groupBy('expense_type')->map->sum('total'),
            'by_phase' => $expenses->groupBy('phase')->map->sum('total'),
            'by_project' => $expenses->groupBy('project_id')->map->sum('total'),
            'count' => $expenses->count(),
            'avg_amount' => $expenses->count() > 0 ? $expenses->avg('total') : 0,
        ];
    }

    /**
     * Show the form for creating a new expense.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = ExpenseCategory::where('active', true)->orderBy('name')->get();
        $projects = \App\Models\Project::orderBy('name')->get();
        return view('expenses.create', compact('categories', 'projects'));
    }

    /**
     * Store a newly created expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'project_id' => 'nullable|exists:projects,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_type' => 'required|string|in:materials,labor,office,equipment,transport',
            'item_name' => 'required|string|max:255',
            'quantity' => 'nullable|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0.01',
            'total' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ];

        // Phase validation for labor expenses
        if ($request->expense_type === 'labor') {
            $rules['phase'] = 'required|string|in:design,execution';
        }

        $data = $request->validate($rules);

        // Auto-calculate total if quantity and unit_price are provided
        if (!empty($data['quantity']) && !empty($data['unit_price'])) {
            $calculatedTotal = $data['quantity'] * $data['unit_price'];
            // Allow some tolerance for rounding differences
            if (abs($calculatedTotal - $data['total']) > 0.02) {
                return back()->withInput()->withErrors([
                    'total' => 'Total amount does not match quantity × unit price. Expected: ' . number_format($calculatedTotal, 2)
                ]);
            }
        }

        // Set additional fields
        $data['user_id'] = Auth::id();
        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['phase'] = $data['phase'] ?? null;

        // Handle price storage based on expense type
        if ($data['expense_type'] === 'labor') {
            $data['price_per_one'] = $data['unit_price'] ?? null;
            unset($data['unit_price']);
        } else {
            $data['price_per_one'] = null;
        }

        try {
            DB::transaction(function() use ($data) {
                Expense::create($data);
            });

            return redirect()->route('expenses.index')
                ->with('success', 'Expense "' . $data['item_name'] . '" created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating expense: ' . $e->getMessage());
            return back()->withInput()->withErrors([
                'error' => 'An error occurred while saving the expense. Please try again.'
            ]);
        }
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
        $categories = ExpenseCategory::where('active', true)->orderBy('name')->get();
        $projects = \App\Models\Project::orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'categories', 'projects'));
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
        $rules = [
            'project_id' => 'nullable|exists:projects,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_type' => 'required|string|in:materials,labor,office,equipment,transport',
            'item_name' => 'required|string|max:255',
            'quantity' => 'nullable|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0.01',
            'total' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ];

        // Phase validation for labor expenses
        if ($request->expense_type === 'labor') {
            $rules['phase'] = 'required|string|in:design,execution';
        }

        $data = $request->validate($rules);

        // Auto-calculate total if quantity and unit_price are provided
        if (!empty($data['quantity']) && !empty($data['unit_price'])) {
            $calculatedTotal = $data['quantity'] * $data['unit_price'];
            // Allow some tolerance for rounding differences
            if (abs($calculatedTotal - $data['total']) > 0.02) {
                return back()->withInput()->withErrors([
                    'total' => 'Total amount does not match quantity × unit price. Expected: ' . number_format($calculatedTotal, 2)
                ]);
            }
        }

        // Set additional fields
        $data['user_id'] = $expense->user_id; // Keep original user
        $data['tenant_id'] = $expense->tenant_id; // Keep original tenant
        $data['phase'] = $data['phase'] ?? null;

        // Handle price storage based on expense type
        if ($data['expense_type'] === 'labor') {
            $data['price_per_one'] = $data['unit_price'] ?? null;
            unset($data['unit_price']);
        } else {
            $data['price_per_one'] = null;
        }

        try {
            DB::transaction(function() use ($expense, $data) {
                $expense->update($data);
            });

            return redirect()->route('expenses.show', $expense)
                ->with('success', 'Expense "' . $data['item_name'] . '" updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating expense: ' . $e->getMessage());
            return back()->withInput()->withErrors([
                'error' => 'An error occurred while updating the expense. Please try again.'
            ]);
        }
    }

    /**
     * Remove the specified expense from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Expense $expense)
    {
        try {
            DB::transaction(function() use ($expense) {
                $expenseName = $expense->item_name;
                $expense->delete();
                return $expenseName;
            });

            return redirect()->route('expenses.index')
                ->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting expense: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'An error occurred while deleting the expense. Please try again.'
            ]);
        }
    }

    /**
     * Validate expense request data (shared between store & update).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // Validation now handled inline in store()

    /**
     * Export expenses as CSV
     */
    public function exportCsv(Request $request)
    {
        // Check permission for expense export
        if (!Auth::user() || !Auth::user()->hasPermissionTo('expenses.export')) {
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
        if (!Auth::user() || !Auth::user()->hasPermissionTo('expenses.export')) {
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
