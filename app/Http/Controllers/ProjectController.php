<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Services\BusinessQueryService;
use App\Services\RbacFilterService;
use App\Traits\Downloadable;
use Illuminate\Http\Request;
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
        $query = Project::query();
        $filteredProjects = $this->rbacFilterService->filterProjects($query)->get();

        return view('projects.index', [
            'projects' => $filteredProjects,
        ]);
    }

    // Show create form
    public function create()
    {
        // Get all clients from database
        $clients = Client::orderBy('name')->get();

        // Get potential managers (if user has access)
        $managers = [];
        if ($this->queryService->canAccessUserData()) {
            $managers = $this->queryService->buildRoleBasedQuery('users')
                             ->whereHas('roles', function($q) {
                                 $q->whereIn('name', ['manager', 'admin']);
                             })
                             ->orderBy('name')
                             ->get();
        }

        return view('projects.create', compact('clients', 'managers'));
    }

    // Store new project with tenant awareness
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'name'           => 'required|string|max:255',
            'project_code'   => 'nullable|string|max:50|unique:projects,project_code',
            'description'    => 'nullable|string',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'budget'         => 'nullable|numeric|min:0',
            'contract_value' => 'nullable|numeric|min:0',
            'manager_id'     => 'nullable|exists:users,id',
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

        // Add tenant and creator information
        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'planning';
        $validated['current_phase'] = $validated['current_phase'] ?? 'design';

        // Validate client exists
        if (!Client::where('id', $validated['client_id'])->exists()) {
            return back()->withErrors(['client_id' => 'Invalid client selected.']);
        }

        $validated = $this->ensureTenantId($validated);
        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    // Display the specified project with role-based access
    public function show($project)
    {
        // Handle both model binding and ID parameter
        if (is_numeric($project)) {
            $project = Project::findOrFail($project);
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
        $receivedAmount = $revenues->where('status', 'received')->sum('amount_received');
        $remainingAmount = max(0, $project->contract_value - $receivedAmount);

        // Get project expenses
        $expenses = \App\Models\Expense::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalExpenses = $expenses->sum('amount');

        // Calculate profit/loss
        $profit = $totalRevenue - $totalExpenses - $totalWorkerCost;

        return view('projects.show', compact(
            'project', 'stats', 'workers', 'totalWorkers', 'totalWorkerCost',
            'paymentsByPosition',
            'revenues', 'totalRevenue', 'receivedAmount', 'remainingAmount',
            'expenses', 'totalExpenses', 'profit'
        ));
    }

    // Show the form for editing the specified project
    public function edit(Project $project)
    {
        // Ensure project belongs to current tenant and user has access
        $projectData = $this->queryService->buildRoleBasedQuery('projects')
                            ->where('id', $project->id)
                            ->first();

        if (!$projectData) {
            abort(404, 'Project not found or access denied.');
        }

        $clients = Client::orderBy('name')->get();

        // Get managers if user has access
        $managers = [];
        if ($this->queryService->canAccessUserData()) {
            $managers = $this->queryService->buildRoleBasedQuery('users')
                             ->whereHas('roles', function($q) {
                                 $q->whereIn('name', ['manager', 'admin']);
                             })
                             ->orderBy('name')
                             ->get();
        }

        return view('projects.edit', compact('project', 'clients', 'managers'));
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
            'manager_id'     => 'nullable|exists:users,id',
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
