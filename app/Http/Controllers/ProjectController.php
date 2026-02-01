<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Phase;
use App\Models\Worker;
use App\Services\BusinessQueryService;
use App\Services\RbacFilterService;
use App\Traits\Downloadable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    use Downloadable;

    protected BusinessQueryService $queryService;
    protected RbacFilterService $rbacFilterService;

    public function __construct(BusinessQueryService $queryService, RbacFilterService $rbacFilterService)
    {
        $this->middleware('auth');
        $this->middleware('tenant.data');
        $this->queryService = $queryService;
        $this->rbacFilterService = $rbacFilterService;
    }

    // List projects with role-based filtering
    public function index(Request $request)
    {
        $query = Project::with(['client', 'manager']);
        $filteredProjects = $this->rbacFilterService->filterProjects($query)->get();

        // Calculate financial data for each project
        $filteredProjects->each(function($project) {
            // Get revenues (amount received from client) - payment_status = 'Paid'
            $project->total_received = \App\Models\Income::where('project_id', $project->id)
                ->where('payment_status', 'Paid')
                ->sum('amount_received');

            // Get expenses
            $project->total_expenses = \App\Models\Expense::where('project_id', $project->id)
                ->sum('total');

            // Get worker payments for this project
            $project->total_payments = \App\Models\Payment::where('project_id', $project->id)
                ->sum('amount');

            // Calculate profit: Budget - (Expenses + Payments)
            $budget = $project->contract_value ?? 0;
            $totalSpent = $project->total_expenses + $project->total_payments;
            $project->profit = $budget - $totalSpent;
        });

        return view('projects.index', [
            'projects' => $filteredProjects,
        ]);
    }

    // Show create form
    public function create()
    {
        $tenantId = auth()->user()->current_tenant_id;

        // Get all clients from database
        $clients = Client::where('tenant_id', $tenantId)->orderBy('name')->get();

        // Get workers to select as project manager
        $workers = Worker::where('tenant_id', $tenantId)
                         ->where('status', 'active')
                         ->orderBy('first_name')
                         ->get();

        return view('projects.create', compact('clients', 'workers'));
    }

    // Store new project with tenant awareness
    public function store(Request $request)
    {
        $tenantId = auth()->user()->current_tenant_id;
        $validated = $request->validate([
            'client_id'      => 'nullable|exists:clients,id',
            'name'           => ['required','string','max:255', Rule::unique('projects')->where(function($q) use ($tenantId) { $q->where('tenant_id', $tenantId); })],
            'project_code'   => 'nullable|string|max:50|unique:projects,project_code',
            'description'    => 'nullable|string',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'budget'         => 'nullable|numeric|min:0',
            'contract_value' => 'required|numeric|min:0',
            'manager_id'     => 'nullable|exists:workers,id',
            'status'         => 'nullable|string|in:planning,active,completed,on_hold',
            'priority'       => 'nullable|string|in:low,medium,high,urgent',
            'client_visible' => 'boolean',
            'notes'          => 'nullable|string',
            // Project type field (required)
            'project_type'            => 'required|in:DESIGN,EXECUTION,DESIGN_EXECUTION',
            // Phase fields
            'current_phase'           => 'nullable|in:design,execution',
            // Design phase fields
            'design_phase_value'      => 'nullable|numeric|min:0',
            'design_phase_paid'       => 'nullable|numeric|min:0',
            'design_phase_status'     => 'nullable|in:pending,in_progress,completed',
            'design_start_date'       => 'nullable|date',
            'design_end_date'         => 'nullable|date|after_or_equal:design_start_date',
            // Execution phase fields
            'execution_phase_value'   => 'nullable|numeric|min:0',
            'execution_phase_paid'    => 'nullable|numeric|min:0',
            'execution_phase_status'  => 'nullable|in:pending,in_progress,completed',
            'execution_start_date'    => 'nullable|date',
            'execution_end_date'      => 'nullable|date|after_or_equal:execution_start_date',
        ]);
        // Default current_phase if missing
        if (empty($validated['current_phase'])) {
            $validated['current_phase'] = 'design';
        }

        // Add tenant and creator information
        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'planning';
        $validated['current_phase'] = $validated['current_phase'] ?? 'design';

        // Ensure phase monetary fields are never null to satisfy DB constraints
        $validated['design_phase_value'] = isset($validated['design_phase_value']) ? $validated['design_phase_value'] : 0;
        $validated['execution_phase_value'] = isset($validated['execution_phase_value']) ? $validated['execution_phase_value'] : 0;

        // Only check client if provided
        if (!empty($validated['client_id']) && !Client::where('id', $validated['client_id'])->exists()) {
            return back()->withErrors(['client_id' => 'Invalid client selected.'])->withInput();
        }

        $validated = $this->ensureTenantId($validated);

        // Create project and then create initial phases depending on project type
        $project = Project::create($validated);

        // Attach phases based on project_type
        $type = $validated['project_type'] ?? 'EXECUTION';
        // If design or design_execution include design phase
        if (in_array($type, ['DESIGN', 'DESIGN_EXECUTION'])) {
            Phase::create([
                'project_id' => $project->id,
                'position' => 1,
                'name' => 'Design Phase',
                'status' => 'pending',
                'planned_start' => $validated['design_start_date'] ?? null,
                'planned_end' => $validated['design_end_date'] ?? null,
                'budget' => $validated['design_phase_value'] ?? null,
            ]);
        }
        // If execution or design_execution include execution phase
        if (in_array($type, ['EXECUTION', 'DESIGN_EXECUTION'])) {
            Phase::create([
                'project_id' => $project->id,
                'position' => 2,
                'name' => 'Execution Phase',
                'status' => 'pending',
                'planned_start' => $validated['execution_start_date'] ?? null,
                'planned_end' => $validated['execution_end_date'] ?? null,
                'budget' => $validated['execution_phase_value'] ?? null,
            ]);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully. Phases linked.');
    }

    // Display the specified project with role-based access
    public function show($project)
    {
        try {
            // Check if user has current tenant
            $user = auth()->user();
            $currentTenantId = $user->current_tenant_id ?? $user->tenants()->first()?->id;

            if (!$currentTenantId) {
                return redirect()->route('user.dashboard')
                    ->with('error', 'You need to be assigned to a company to access projects.');
            }

            // Handle both model binding and ID parameter
            if (is_numeric($project)) {
                $project = Project::where('id', $project)
                                 ->where('tenant_id', $currentTenantId)
                                 ->first();
            } else {
                // If it's already a model, validate tenant
                $project = Project::where('id', $project->id)
                                 ->where('tenant_id', $currentTenantId)
                                 ->first();
            }

            if (!$project) {
                return redirect()->route('projects.index')
                    ->with('error', 'Project not found or you do not have access to it.');
            }

            $project->load('client');

        // Get project statistics
        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
            'total_time' => 0,
            'total_expenses' => 0,
        ];

        // Get project workers through tasks
        $workerIds = $project->tasks()->whereNotNull('assigned_to')->pluck('assigned_to')->unique();
        $workers = \App\Models\Worker::whereIn('id', $workerIds)->get();

        // Load project-specific payments for each worker
        $workers->each(function($worker) use ($project) {
            $worker->projectPayments = \App\Models\WorkerPayment::where('worker_id', $worker->id)
                ->where('project_id', $project->id)
                ->get();
        });

        $totalWorkers = $workers->count();
        $totalWorkerCost = $workers->sum(function($worker) {
            return $worker->projectPayments->sum('amount');
        });

        // Calculate payments and worker counts by position (project-specific)
        $paymentsByPosition = $workers->groupBy('position')->map(function($group) {
            return [
                'count' => $group->count(),
                'total_paid' => $group->sum(function($worker) {
                    return $worker->projectPayments->sum('amount');
                }),
            ];
        })->sortByDesc('total_paid');

        // Get project revenues (incomes)
        $revenues = \App\Models\Income::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRevenue = $revenues->sum('amount_received');
        $receivedAmount = $revenues->where('payment_status', 'Paid')->sum('amount_received');
        $remainingAmount = max(0, $project->contract_value - $receivedAmount);

        // Get project expenses with relationships
        $expenses = \App\Models\Expense::where('project_id', $project->id)
            ->with(['category', 'user'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        $totalExpenses = $expenses->sum('total');

        // Get project worker payments (from payments table)
        $projectPayments = \App\Models\Payment::where('project_id', $project->id)
            ->with('employee')
            ->orderBy('created_at', 'desc')
            ->get();
        $totalPayments = $projectPayments->sum('amount');

        // Calculate totals by phase
        $designPayments = $projectPayments->where('phase', 'design')->sum('amount');
        $executionPayments = $projectPayments->where('phase', 'execution')->sum('amount');

        // Budget calculations
        $agreedBudget = $project->contract_value ?? 0;
        $amountReceived = $receivedAmount; // From incomes (client payments)
        $amountSpent = $totalExpenses + $totalPayments; // Expenses + Worker Payments
        $budgetRemaining = max(0, $agreedBudget - $amountReceived); // Budget minus what client has paid

        // Calculate profit: Budget - (Expenses + Payments)
        $profit = $agreedBudget - $amountSpent;

        return view('projects.show', compact(
            'project', 'stats', 'workers', 'totalWorkers', 'totalWorkerCost',
            'paymentsByPosition',
            'revenues', 'totalRevenue', 'receivedAmount', 'remainingAmount',
            'expenses', 'totalExpenses', 'profit',
            'projectPayments', 'totalPayments', 'designPayments', 'executionPayments',
            'agreedBudget', 'amountReceived', 'amountSpent', 'budgetRemaining'
        ));

        } catch (\Exception $e) {
            \Log::error('Project show error: ' . $e->getMessage());
            return redirect()->route('projects.index')
                ->with('error', 'Unable to load project details. Please try again.');
        }
    }

    // Show the form for editing the specified project
    public function edit(Project $project)
    {
        try {
            // Check if user has current tenant
            $user = auth()->user();
            $currentTenantId = $user->current_tenant_id ?? $user->tenants()->first()?->id;

            if (!$currentTenantId) {
                return redirect()->route('user.dashboard')
                    ->with('error', 'You need to be assigned to a company to access projects.');
            }

            // Check if project exists and belongs to current tenant
            $project = Project::where('id', $project->id)
                              ->where('tenant_id', $currentTenantId)
                              ->first();

            if (!$project) {
                return redirect()->route('projects.index')
                    ->with('error', 'Project not found or you do not have access to it.');
            }

            // Get clients for the current tenant
            $clients = Client::where('tenant_id', $currentTenantId)
                            ->orderBy('name')
                            ->get();

            // Get workers to select as project manager
            $workers = Worker::where('tenant_id', $currentTenantId)
                            ->where('status', 'active')
                            ->orderBy('first_name')
                            ->get();

            return view('projects.edit', compact('project', 'clients', 'workers'));

        } catch (\Exception $e) {
            \Log::error('Project edit error: ' . $e->getMessage());
            return redirect()->route('projects.index')
                ->with('error', 'Unable to load project for editing. Please try again.');
        }
    }

    // Update the specified project with role-based validation
    public function update(Request $request, Project $project)
    {
        // Ensure project belongs to current tenant and user has access
        $projectData = $this->queryService->buildRoleBasedQuery('projects')
                            ->where('id', $project->id)
                            ->first();

        if (!$projectData) {
            abort(404, 'Project not found or access denied.');
        }

        $validated = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'name'           => 'required|string|max:255',
            'project_code'   => 'nullable|string|max:50|unique:projects,project_code,' . $project->id,
            'description'    => 'nullable|string',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'budget'         => 'nullable|numeric|min:0',
            'contract_value' => 'nullable|numeric|min:0',
            'manager_id'     => 'nullable|exists:workers,id',
            'status'         => 'nullable|string|in:planning,active,completed,on_hold',
            'priority'       => 'nullable|string|in:low,medium,high,urgent',
            'client_visible' => 'boolean',
            'notes'          => 'nullable|string',
            // Phase fields
            'current_phase'           => 'nullable|in:design,execution',
            'design_phase_value'      => 'nullable|numeric|min:0',
            'design_phase_status'     => 'nullable|in:pending,in_progress,completed',
            'design_start_date'       => 'nullable|date',
            'design_end_date'         => 'nullable|date',
            'execution_phase_value'   => 'nullable|numeric|min:0',
            'execution_phase_status'  => 'nullable|in:pending,in_progress,completed',
            'execution_start_date'    => 'nullable|date',
            'execution_end_date'      => 'nullable|date',
        ]);

        $validated['updated_by'] = Auth::id();
        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    // Remove the specified project
    public function destroy(Project $project)
    {
        // Ensure project belongs to current tenant and user has access
        $projectData = $this->queryService->buildRoleBasedQuery('projects')
                            ->where('id', $project->id)
                            ->first();

        if (!$projectData) {
            abort(404, 'Project not found or access denied.');
        }

        // Check for dependencies
        $hasTasks = $this->queryService->buildRoleBasedQuery('tasks')
                         ->where('project_id', $project->id)
                         ->exists();

        if ($hasTasks) {
            return back()->with('error', 'Cannot delete project with existing tasks.');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Export projects as CSV
     */
    public function exportCsv(Request $request)
    {
        // Check permission for project export
        if (!Auth::user()->can('projects.export')) {
            abort(403, 'You do not have permission to export projects.');
        }

        $filename = $request->get('filename', 'projects');

        $projects = Project::with('client')->get();

        $headers = [
            'id' => 'ID',
            'name' => 'Project Name',
            'client_name' => 'Client Name',
            'contract_value' => 'Contract Value (RWF)',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'description' => 'Description',
            'created_at' => 'Created Date'
        ];

        // Transform data for CSV
        $csvData = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'client_name' => $project->client_name ?? ($project->client ? $project->client->name : 'N/A'),
                'contract_value' => $project->contract_value ?? 0,
                'start_date' => $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : 'N/A',
                'end_date' => $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : 'N/A',
                'status' => ucfirst($project->status ?? 'N/A'),
                'description' => $project->description ?? 'N/A',
                'created_at' => $project->created_at->format('Y-m-d H:i:s')
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export projects as PDF
     */
    public function exportPdf(Request $request)
    {
        // Check permission for project export
        if (!Auth::user()->can('projects.export')) {
            abort(403, 'You do not have permission to export projects.');
        }

        $filename = $request->get('filename', 'projects');

        $projects = Project::with('client')->get();

        $html = $this->generatePdfHtml('exports.projects-pdf', [
            'data' => $projects,
            'title' => 'Projects Report',
            'subtitle' => 'Complete list of all projects',
            'totalRecords' => $projects->count()
        ]);

        return $this->downloadPdf($html, $filename);
    }
}
