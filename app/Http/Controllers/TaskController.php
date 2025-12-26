<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
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
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignedTo']);
        $filteredTasks = $this->rbacFilterService->filterTasks($query)->get();

        return Inertia::render('Admin/Tasks', [
            'tasks' => $filteredTasks,
            'filterContext' => $this->rbacFilterService->getFilterContext(),
        ]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();

        return view('tasks.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
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

        $validated['created_by'] = Auth::id();
        $validated = $this->ensureTenantId($validated);

        Task::create($validated);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task created successfully.');
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
    public function edit(Task $task)
    {
        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
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

        return redirect()->route('tasks.index')
                        ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
                        ->with('success', 'Task deleted successfully.');
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
