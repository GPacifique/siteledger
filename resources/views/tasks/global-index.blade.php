<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tasks - CSMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-size: 1.8rem;
            color: #333;
        }
        .header-info {
            font-size: 0.95rem;
            color: #666;
        }
        .filter-bar {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .filter-bar select {
            padding: 0.5rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        .filter-bar select:focus {
            outline: none;
            border-color: #667eea;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .task-card {
            background: white;
            border-left: 4px solid #667eea;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .task-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        .task-card.urgent {
            border-left-color: #e74c3c;
        }
        .task-card.high {
            border-left-color: #e67e22;
        }
        .task-card.medium {
            border-left-color: #f39c12;
        }
        .task-card.low {
            border-left-color: #27ae60;
        }
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .task-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
        }
        .task-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        .badge-priority {
            background: #ffe6e6;
            color: #c0392b;
        }
        .badge-priority.low {
            background: #d5f4e6;
            color: #27ae60;
        }
        .badge-priority.medium {
            background: #fff3cd;
            color: #f39c12;
        }
        .badge-priority.high {
            background: #ffe0cd;
            color: #e67e22;
        }
        .badge-status {
            background: #e8f4fd;
            color: #2980b9;
        }
        .badge-status.completed {
            background: #d4edda;
            color: #155724;
        }
        .badge-status.in_progress {
            background: #e2e3e5;
            color: #383d41;
        }
        .task-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-item {
            font-size: 0.9rem;
        }
        .detail-label {
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            color: #333;
            font-weight: 500;
        }
        .task-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        .task-actions {
            display: flex;
            gap: 0.75rem;
        }
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .action-btn.view {
            background: #3498db;
            color: white;
        }
        .action-btn.view:hover {
            background: #2980b9;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 8px;
            color: #999;
        }
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .pagination .active span {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="header">
            <div>
                <h1>📋 All Tasks</h1>
                <p class="header-info">Manage all project tasks across your organization</p>
            </div>
            <div class="header-actions">
                <button class="btn-add" id="createTaskBtn">➕ Create Task</button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $tasks->total() }}</div>
                <div class="stat-label">Total Tasks</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $tasks->where('status', 'in_progress')->count() }}</div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $tasks->where('status', 'completed')->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $tasks->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('tasks.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; width: 100%;">
                <select name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>⏱️ In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                </select>

                <select name="priority" onchange="this.form.submit()">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                </select>
            </form>
        </div>

        <!-- Tasks List -->
        @if($tasks->count() > 0)
            @foreach($tasks as $task)
                <div class="task-card {{ $task->priority }}">
                    <div class="task-header">
                        <div class="task-title">{{ $task->title }}</div>
                        <div class="task-badges">
                            <span class="badge badge-priority {{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            <span class="badge badge-status {{ str_replace('_', '-', $task->status) }}">
                                {{ \App\Models\Task::STATUSES[$task->status] ?? ucfirst($task->status) }}
                            </span>
                        </div>
                    </div>

                    @if($task->description)
                        <p class="task-description">{{ Str::limit($task->description, 150) }}</p>
                    @endif

                    <div class="task-details">
                        @if($task->project)
                            <div class="detail-item">
                                <div class="detail-label">📁 Project</div>
                                <div class="detail-value">{{ $task->project->name }}</div>
                            </div>
                        @endif

                        @if($task->worker)
                            <div class="detail-item">
                                <div class="detail-label">👷 Assigned Worker</div>
                                <div class="detail-value">{{ $task->worker->first_name }} {{ $task->worker->last_name }}</div>
                            </div>
                        @endif

                        @if($task->due_date)
                            <div class="detail-item">
                                <div class="detail-label">📅 Due Date</div>
                                <div class="detail-value">{{ $task->due_date->format('M d, Y') }}</div>
                            </div>
                        @endif

                        @if($task->estimated_hours)
                            <div class="detail-item">
                                <div class="detail-label">⏰ Est. Hours</div>
                                <div class="detail-value">{{ $task->estimated_hours }} hours</div>
                            </div>
                        @endif

                        @if($task->estimated_cost)
                            <div class="detail-item">
                                <div class="detail-label">💰 Est. Cost</div>
                                <div class="detail-value">RWF {{ number_format($task->estimated_cost, 2) }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="task-actions">
                        @if($task->project)
                            <a href="{{ route('projects.tasks.edit', [$task->project, $task]) }}" class="action-btn view">View/Edit</a>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pagination">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No tasks yet</h3>
                <p>Create your first task by going to a project.</p>
            </div>
        @endif
    </div>

    <!-- Create Task Modal -->
    <!-- Create Task Modal -->
    <div id="createTaskModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);">
            <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: #333; border-bottom: 2px solid #667eea; padding-bottom: 0.75rem;">📋 Create New Task</h2>

            <form id="createTaskForm" method="POST" action="/tasks/store-from-global">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label for="projectSelect" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Select Project <span style="color: #d63031;">*</span></label>
                    <select id="projectSelect" name="project_id" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;">
                        <option value="">-- Select a Project --</option>
                        @if(isset($projects) && $projects->count() > 0)
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No projects available</option>
                        @endif
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="taskTitle" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Task Title <span style="color: #d63031;">*</span></label>
                    <input type="text" id="taskTitle" name="title" required placeholder="e.g., Foundation Excavation" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="taskDescription" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Description</label>
                    <textarea id="taskDescription" name="description" placeholder="Detailed description of the task..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 1rem; resize: vertical; min-height: 100px;"></textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="workerSelect" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Assign to Worker <span style="color: #d63031;">*</span></label>
                    <select id="workerSelect" name="worker_id" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                        <option value="">-- Select a Worker --</option>
                        @if(isset($workers) && $workers->count() > 0)
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->first_name }} {{ $worker->last_name }} ({{ $worker->position ?? 'N/A' }})</option>
                            @endforeach
                        @else
                            <option value="" disabled>No workers available</option>
                        @endif
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="taskPriority" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Priority</label>
                        <select id="taskPriority" name="priority" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                            <option value="low">🟢 Low</option>
                            <option value="medium" selected>🟡 Medium</option>
                            <option value="high">🟠 High</option>
                            <option value="urgent">🔴 Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label for="taskStatus" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Status</label>
                        <select id="taskStatus" name="status" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                            <option value="pending" selected>⏳ Pending</option>
                            <option value="in_progress">⏱️ In Progress</option>
                            <option value="completed">✅ Completed</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="startDate" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Start Date</label>
                        <input type="date" id="startDate" name="start_date" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div>
                        <label for="dueDate" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Due Date</label>
                        <input type="date" id="dueDate" name="due_date" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="estimatedHours" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Estimated Hours <span style="color: #667eea; font-size: 0.85rem;">(Auto: 8hrs/day)</span></label>
                        <input type="number" id="estimatedHours" name="estimated_hours" step="0.5" placeholder="Auto-calculated" readonly style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; background-color: #f8f9fa; cursor: not-allowed;">
                    </div>
                    <div>
                        <label for="estimatedCost" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Estimated Cost (RWF)</label>
                        <input type="number" id="estimatedCost" name="estimated_cost" step="0.01" placeholder="0.00" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #f0f0f0;">
                    <button type="submit" style="flex: 1; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.875rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        ✅ Create Task
                    </button>
                    <button type="button" id="closeModalBtn" style="flex: 1; background: #95a5a6; color: white; padding: 0.875rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('createTaskModal');
        const createTaskBtn = document.getElementById('createTaskBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const createTaskForm = document.getElementById('createTaskForm');
        const startDateInput = document.getElementById('startDate');
        const dueDateInput = document.getElementById('dueDate');
        const estimatedHoursInput = document.getElementById('estimatedHours');

        // Function to calculate hours based on date range
        function calculateHours() {
            const startDate = startDateInput.value;
            const dueDate = dueDateInput.value;

            if (!startDate || !dueDate) {
                estimatedHoursInput.value = '';
                return;
            }

            const start = new Date(startDate);
            const due = new Date(dueDate);

            // Calculate difference in milliseconds
            const diffMs = due - start;

            // Convert to days (including both start and end dates)
            const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays > 0) {
                // 8 hours per day
                const hours = diffDays * 8;
                estimatedHoursInput.value = hours.toFixed(1);
            } else {
                estimatedHoursInput.value = '';
            }
        }

        // Add event listeners to date inputs
        startDateInput.addEventListener('change', calculateHours);
        dueDateInput.addEventListener('change', calculateHours);

        // Open modal
        createTaskBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });

        // Close modal
        function closeModal() {
            modal.style.display = 'none';
            createTaskForm.reset();
            estimatedHoursInput.value = '';
        }

        closeModalBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Handle form submission - simple server-side form
        // No need for custom JS, form will post normally
    </script>
</body>
</html>
