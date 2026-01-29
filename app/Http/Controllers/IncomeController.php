<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Project;
use App\Traits\Downloadable;
use Illuminate\Http\Request;
use App\Services\RbacFilterService;
use Inertia\Inertia;

class IncomeController extends Controller
{
    use Downloadable;

    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        $this->rbacFilterService = $rbacFilterService;
    }

    public function index()
    {
        $revenues = Income::with('project')->orderBy('created_at', 'desc')->paginate(15);

        // Calculate stats
        $today = \Carbon\Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfToday = $today->endOfDay();

        $totalRevenue = Income::sum('amount_received');
        $revenueThisMonth = Income::whereBetween('received_at', [$startOfMonth, $endOfToday])->sum('amount_received');
        $revenueToday = Income::whereDate('received_at', $today)->sum('amount_received');

        // Get expenses for comparison
        $expensesToday = \App\Models\Expense::whereDate('created_at', $today)->sum('total');
        $expensesThisMonth = \App\Models\Expense::whereBetween('created_at', [$startOfMonth, $endOfToday])->sum('total');

        // Get worker payments for comparison
        $workerPaymentsToday = \App\Models\Payment::whereDate('created_at', $today)->whereNotNull('employee_id')->sum('amount');

        // Combined formula: Worker Payments + Office Expenses
        $allExpensesToday = $workerPaymentsToday + $expensesToday;

        return view('revenues.index', compact(
            'revenues', 'totalRevenue', 'revenueThisMonth', 'revenueToday',
            'expensesToday', 'expensesThisMonth', 'workerPaymentsToday', 'allExpensesToday'
        ));
    }

    public function create()
    {
        $projects = Project::all(); // For project dropdown
        return view('revenues.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'nullable|string|max:255|unique:incomes,invoice_number',
            'amount_received' => 'required|numeric|min:0',
            'payment_status' => 'nullable|in:Paid,Pending,partially paid,Overdue',
            'amount_remaining' => 'nullable|numeric|min:0',
            'received_at' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            // Accept alternative form fields
            'status' => 'nullable|in:received,pending',
            'description' => 'nullable|string|max:1000',
        ]);

        // Map alternative fields to model columns
        $statusInput = $request->input('status') ?? $validated['payment_status'] ?? null;
        if ($statusInput) {
            $validated['payment_status'] = match($statusInput) {
                'received' => 'Paid',
                'pending' => 'Pending',
                'Paid', 'Pending', 'partially paid', 'Overdue' => $statusInput,
                default => $validated['payment_status'] ?? null,
            };
        }

        if (empty($validated['notes']) && $request->filled('description')) {
            $validated['notes'] = $request->input('description');
        }

        // Compute amount_remaining if not provided
        if (!isset($validated['amount_remaining'])) {
            $project = Project::find($validated['project_id']);
            if ($project && $project->contract_value) {
                $existingReceived = Income::where('project_id', $project->id)->sum('amount_received');
                $validated['amount_remaining'] = max(0, ($project->contract_value - ($existingReceived + $validated['amount_received'])));
            } else {
                $validated['amount_remaining'] = 0;
            }
        }

        $validated = $this->ensureTenantId($validated);
        Income::create($validated);

        return redirect()->route('revenues.index')
                         ->with('success', 'Revenue record created successfully.');
    }

    public function show(Income $income)
    {
        $income->load('project');

        // Get more project details
        $project = $income->project;
        $projectStats = [];
        $projectRevenues = [];
        $projectExpenses = 0;

        if ($project) {
            // Get all revenues for this project
            $projectRevenues = Income::where('project_id', $project->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get total expenses for this project
            $projectExpenses = \App\Models\Expense::where('project_id', $project->id)->sum('total');

            // Calculate stats
            $receivedSum = $projectRevenues->where('payment_status', 'Paid')->sum('amount_received');
            $projectStats = [
                'total_revenue' => $projectRevenues->sum('amount_received'),
                'received_amount' => $receivedSum,
                'remaining_amount' => max(0, ($project->contract_value ?? 0) - $receivedSum),
                'total_expenses' => $projectExpenses,
                'revenue_count' => $projectRevenues->count(),
            ];
        }

        return view('revenues.show', compact('income', 'project', 'projectStats', 'projectRevenues'));
    }

    public function edit(Income $income)
    {
        $projects = Project::all();
        return view('revenues.edit', compact('income', 'projects'));
    }

    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'nullable|string|max:255|unique:incomes,invoice_number,' . $income->id,
            'amount_received' => 'required|numeric|min:0',
            'payment_status' => 'nullable|in:Paid,Pending,partially paid,Overdue',
            'amount_remaining' => 'nullable|numeric|min:0',
            'received_at' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'nullable|in:received,pending',
            'description' => 'nullable|string|max:1000',
        ]);

        // Map alternative fields
        $statusInput = $request->input('status') ?? $validated['payment_status'] ?? null;
        if ($statusInput) {
            $validated['payment_status'] = match($statusInput) {
                'received' => 'Paid',
                'pending' => 'Pending',
                'Paid', 'Pending', 'partially paid', 'Overdue' => $statusInput,
                default => $validated['payment_status'] ?? null,
            };
        }
        if (empty($validated['notes']) && $request->filled('description')) {
            $validated['notes'] = $request->input('description');
        }

        // Compute amount_remaining if not provided
        if (!isset($validated['amount_remaining'])) {
            $project = Project::find($validated['project_id']);
            if ($project && $project->contract_value) {
                $existingReceived = Income::where('project_id', $project->id)->where('id', '!=', $income->id)->sum('amount_received');
                $validated['amount_remaining'] = max(0, ($project->contract_value - ($existingReceived + $validated['amount_received'])));
            } else {
                $validated['amount_remaining'] = 0;
            }
        }

        $income->update($validated);

        return redirect()->route('revenues.index')
                         ->with('success', 'Revenue record updated successfully.');
    }

    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()->route('revenues.index')
                         ->with('success', 'Revenue record deleted successfully.');
    }

    /**
     * Export incomes as CSV
     */
    public function exportCsv(Request $request)
    {
        // Check permission for income export
        if (!Auth::user()->can('incomes.export')) {
            abort(403, 'You do not have permission to export incomes.');
        }

        $filename = $request->get('filename', 'incomes');

        $incomes = Income::with('project')->latest()->get();

        $headers = [
            'id' => 'ID',
            'project_name' => 'Project',
            'amount_received' => 'Amount Received (RWF)',
            'received_at' => 'Received Date',
            'payment_method' => 'Payment Method',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created Date'
        ];

        // Transform data for CSV
        $csvData = $incomes->map(function ($income) {
            return [
                'id' => $income->id,
                'project_name' => $income->project ? $income->project->name : 'N/A',
                'amount_received' => $income->amount_received ?? 0,
                'received_at' => $income->received_at ? \Carbon\Carbon::parse($income->received_at)->format('Y-m-d') : 'N/A',
                'payment_method' => $income->payment_method ?? 'N/A',
                'description' => $income->description ?? 'N/A',
                'status' => ucfirst($income->status ?? 'completed'),
                'created_at' => $income->created_at->format('Y-m-d H:i:s')
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export incomes as PDF
     */
    public function exportPdf(Request $request)
    {
        // Check permission for income export
        if (!Auth::user()->can('incomes.export')) {
            abort(403, 'You do not have permission to export incomes.');
        }

        $filename = $request->get('filename', 'incomes');

        $incomes = Income::with('project')->latest()->get();

        $html = $this->generatePdfHtml('exports.financial-pdf', [
            'data' => $incomes,
            'title' => 'Income Report',
            'subtitle' => 'Complete list of all income records',
            'totalRecords' => $incomes->count(),
            'showProject' => true
        ]);

        return $this->downloadPdf($html, $filename);
    }
}
