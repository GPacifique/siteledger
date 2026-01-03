<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Worker;
use App\Traits\Downloadable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RbacFilterService;
use Inertia\Inertia;

class TaskController extends Controller
{
    use Downloadable;

    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        $this->rbacFilterService = $rbacFilterService;
    }

    /**
     * Display a listing of tasks for a specific project
     */
    public function index(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)
            ->with(['project', 'worker', 'assignedTo', 'createdBy'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        return view('tasks.index', compact('project', 'tasks'));
    }

    /**
     * Display all tasks globally
     */
    public function globalIndex(Request $request)
    {
        $tenantId = auth()->user()->current_tenant_id;

        $tasks = Task::where('tenant_id', $tenantId)
            ->with(['project', 'worker', 'assignedTo', 'createdBy'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        return view('tasks.global-index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Project $project)
    {
        // Workers scoped to current tenant and active status
        $workers = Worker::where('tenant_id', auth()->user()->current_tenant_id)
                         ->where('status', 'active')
                         ->orderBy('first_name')
                         ->get();

        return view('tasks.create', compact('project', 'workers'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request, Project $project = null)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'worker_id' => 'nullable|exists:workers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Set project_id from route parameter if not provided in request
        if ($project && !isset($validated['project_id'])) {
            $validated['project_id'] = $project->id;
        }

        $validated['created_by'] = Auth::id();
        $validated = $this->ensureTenantId($validated);

        Task::create($validated);

        // Return appropriate response based on request type
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Task created successfully.']);
        }

        $redirectRoute = ($project) ? route('projects.tasks.index', $project) : route('tasks.index');
        return redirect($redirectRoute)->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignedTo', 'createdBy']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Project $project, Task $task)
    {
        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();
        $workers = Worker::where('tenant_id', auth()->user()->current_tenant_id)->where('status', 'active')->get();

        return view('tasks.edit', compact('task', 'project', 'projects', 'users', 'workers'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $validated = $request->validate([
            'worker_id' => 'nullable|exists:workers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Set completed date if status changed to completed
        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_date'] = now()->toDateString();
        }

        $task->update($validated);

        return redirect()->route('projects.tasks.index', $project)
                        ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return redirect()->route('projects.tasks.index', $project)
                        ->with('success', 'Task deleted successfully.');
    }

    /**
     * Store task from global tasks page
     */
    public function storeFromGlobal(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'worker_id' => 'required|exists:workers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        $validated['created_by'] = Auth::id();
        $validated = $this->ensureTenantId($validated);

        Task::create($validated);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task created successfully.');
    }

    /**
     * Export tasks as CSV
     */
    public function exportCsv(Request $request)
    {
        // Check permission for task export (using tasks.view)
        if (!Auth::user()->can('tasks.view')) {
            abort(403, 'You do not have permission to export tasks.');
        }

        $filename = $request->get('filename', 'tasks');

        $tasks = Task::with(['project', 'assignedTo', 'createdBy'])->get();

        $headers = [
            'id' => 'ID',
            'title' => 'Title',
            'project_name' => 'Project',
            'assigned_to_name' => 'Assigned To',
            'priority' => 'Priority',
            'status' => 'Status',
            'due_date' => 'Due Date',
            'estimated_hours' => 'Est. Hours',
            'actual_hours' => 'Actual Hours',
            'estimated_cost' => 'Est. Cost',
            'actual_cost' => 'Actual Cost',
            'created_at' => 'Created At'
        ];

        $csvData = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project_name' => $task->project ? $task->project->name : 'N/A',
                'assigned_to_name' => $task->assignedTo ? $task->assignedTo->name : 'Unassigned',
                'priority' => ucfirst($task->priority),
                'status' => ucfirst(str_replace('_', ' ', $task->status)),
                'due_date' => $task->due_date ? (string) $task->due_date : 'N/A',
                'estimated_hours' => $task->estimated_hours ?? 0,
                'actual_hours' => $task->actual_hours ?? 0,
                'estimated_cost' => $task->estimated_cost ?? 0,
                'actual_cost' => $task->actual_cost ?? 0,
                'created_at' => $task->created_at->format('Y-m-d H:i:s')
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export tasks as PDF
     */
    public function exportPdf(Request $request)
    {
        // Check permission for task export (using tasks.view)
        if (!Auth::user()->can('tasks.view')) {
            abort(403, 'You do not have permission to export tasks.');
        }

        $filename = $request->get('filename', 'tasks');

        $tasks = Task::with(['project', 'assignedTo', 'createdBy'])->get();

        $html = $this->generatePdfHtml('exports.tasks-pdf', [
            'data' => $tasks,
            'title' => 'Tasks Report',
            'subtitle' => 'Complete list of all tasks',
            'totalRecords' => $tasks->count()
        ]);

        return $this->downloadPdf($html, $filename);
    }
}
