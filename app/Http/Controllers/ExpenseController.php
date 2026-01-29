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
     * Display a listing of the expenses.
     *
     * Also prepares a minimal daily-by-category stats structure:
     *  - $categories: array of distinct categories
     *  - $dailyTotals: [ 'YYYY-MM-DD' => [ 'Category' => total, ... ], ... ]
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = ExpenseCategory::where('active', true)->orderBy('name')->get();
        $query = Expense::with(['category', 'project'])->orderByDesc('date');

        // Filtering
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $expenses = $query->get();
        $grandTotal = $expenses->sum('total');

        // Reporting
        $today = now()->toDateString();
        $month = now()->format('Y-m');
        $year = now()->format('Y');
        $dailyTotal = $expenses->where('date', $today)->sum('total');
        $monthlyTotal = $expenses->where('date', '>=', $month.'-01')->where('date', '<=', $month.'-31')->sum('total');
        $yearlyTotal = $expenses->where('date', '>=', $year.'-01-01')->where('date', '<=', $year.'-12-31')->sum('total');

        return view('expenses.index', compact('expenses', 'categories', 'grandTotal', 'dailyTotal', 'monthlyTotal', 'yearlyTotal'));
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
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_type' => 'required|string',
            'item_name' => 'nullable|string',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string',
            'unit_price' => 'nullable|numeric|min:0',
            'price_per_one' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        // Validate phase as required if expense_type is labor
        $data['phase'] = $request->input('phase') ?? null;
        if ($data['expense_type'] === 'labor' && empty($data['phase'])) {
            $data['phase'] = 'design';
        }
        $data['phase'] = $request->validate([
            'phase' => 'required_if:expense_type,labor|string|in:design,execution',
        ])['phase'];
        // Calculate total if not provided and possible
        if (empty($data['total']) && isset($data['quantity'], $data['unit_price']) && $data['quantity'] > 0 && $data['unit_price'] > 0) {
            $data['total'] = $data['quantity'] * $data['unit_price'];
        }
        // Save unit_price to price_per_one for labor, and to unit_price for materials
        if (isset($data['expense_type']) && $data['expense_type'] === 'labor') {
            $data['price_per_one'] = $data['unit_price'] ?? null;
        } else {
            $data['unit_price'] = $data['unit_price'] ?? null;
            $data['price_per_one'] = null;
        }
        $data['user_id'] = Auth::id();
        $data['tenant_id'] = Auth::user()->tenant_id ?? null;
        Expense::create($data);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
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
