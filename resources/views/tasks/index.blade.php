<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Tasks - SiteLedger</title>
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
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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
        .action-btn.edit {
            background: #3498db;
            color: white;
        }
        .action-btn.edit:hover {
            background: #2980b9;
        }
        .action-btn.delete {
            background: #e74c3c;
            color: white;
        }
        .action-btn.delete:hover {
            background: #c0392b;
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
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="header">
            <div>
                <h1>📋 Project Tasks</h1>
                <p style="color: #666; margin-top: 0.5rem;">{{ $project->name }}</p>
            </div>
            <a href="{{ route('projects.tasks.create', $project) }}" class="btn-add">➕ Add Task</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="filter-bar">
            <form method="GET" action="{{ route('projects.tasks.index', $project) }}" style="display: flex; gap: 1rem; flex-wrap: wrap; width: 100%;">
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
                        <p style="margin-bottom: 1rem; color: #666;">{{ Str::limit($task->description, 150) }}</p>
                    @endif

                    <div class="task-details">
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
                        <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="action-btn edit">Edit</a>
                        <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 2rem;">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No tasks yet</h3>
                <p>Create your first task to start assigning work to laborers.</p>
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn-add" style="margin-top: 1.5rem;">➕ Add First Task</a>
            </div>
        @endif
    </div>
</body>
</html>
