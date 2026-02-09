@extends('layouts.admin')

@section('title', 'Project Tasks - SiteLedger')

@section('content')
        <div class="card-colorful purple" style="margin-bottom: 2rem;">
            <div class="card-body">
                <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h1 style="font-size: 2.5rem; color: var(--primary); margin: 0;">
                        <span class="icon-bounce">📋</span> Project Tasks
                    </h1>
                    <a href="#" class="btn-rainbow">
                        <span class="icon-pulse">✨</span> Add New Task
                    </a>
                </div>
            </div>
        </div>
        <!-- Enhanced Colorful Filter Bar -->
        <div class="card-colorful sunset" style="margin-bottom: 2rem;">
            <div class="card-body" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem; color: var(--orange-dark);">
                    <span class="icon-pulse">🎯</span> Filter Tasks
                </h3>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <select class="form-control-colorful" style="min-width: 150px;">
                        <option>All Projects</option>
                    </select>
                    <select class="form-control-colorful" style="min-width: 150px;">
                        <option>All Priorities</option>
                    </select>
                    <select class="form-control-colorful" style="min-width: 150px;">
                        <option>All Statuses</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 style="font-size: 2rem; color: var(--gray-900); margin: 0;">📋 Project Tasks</h1>
                    <p style="color: var(--gray-600); margin-top: 0.5rem;">{{ $project->name }}</p>
                </div>
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn-rainbow">
                    <span class="icon-pulse">➕</span> Add Task
                </a>
            </div>

        @if(session('success'))
            <div class="alert-success-colorful">
                <span class="icon-bounce">✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="card-colorful sunset" style="margin-bottom: 2rem;">
            <div class="card-body" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem; color: var(--orange-dark);">
                    <span class="icon-pulse">🎯</span> Filter Tasks
                </h3>
                <form method="GET" action="{{ route('projects.tasks.index', $project) }}" style="display: flex; gap: 1rem; flex-wrap: wrap; width: 100%;">
                    <select name="status" onchange="this.form.submit()" class="form-control-colorful" style="min-width: 150px;">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>⏱️ In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                    </select>

                    <select name="priority" onchange="this.form.submit()" class="form-control-colorful" style="min-width: 150px;">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                    </select>
                </form>
            </div>
        </div>

        @if($tasks->count() > 0)
            @foreach($tasks as $task)
                <div class="card-colorful @if($task->priority == 'urgent') sunset @elseif($task->priority == 'high') purple @elseif($task->priority == 'medium') ocean @else green @endif" style="margin-bottom: 1rem;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div style="font-size: 1.2rem; font-weight: 700; color: var(--gray-900);">{{ $task->title }}</div>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <span class="badge-colorful @if($task->priority == 'urgent') badge-sunset @elseif($task->priority == 'high') badge-purple @elseif($task->priority == 'medium') badge-ocean @else badge-success @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <span class="badge-colorful badge-primary">
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
                        @if($task->description)
                            <p style="margin-bottom: 1rem; color: var(--gray-600);">{{ Str::limit($task->description, 150) }}</p>
                        @endif

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-200);">
                            @if($task->due_date)
                                <div style="font-size: 0.9rem;">
                                    <div style="color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">📅 Due Date</div>
                                    <div style="color: var(--gray-900); font-weight: 500;">{{ $task->due_date->format('M d, Y') }}</div>
                                </div>
                            @endif

                            @if($task->estimated_hours)
                                <div style="font-size: 0.9rem;">
                                    <div style="color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">⏰ Est. Hours</div>
                                    <div style="color: var(--gray-900); font-weight: 500;">{{ $task->estimated_hours }} hours</div>
                                </div>
                            @endif

                            @if($task->estimated_cost)
                                <div style="font-size: 0.9rem;">
                                    <div style="color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">💰 Est. Cost</div>
                                    <div style="color: var(--gray-900); font-weight: 500;">RWF {{ number_format($task->estimated_cost, 2) }}</div>
                                </div>
                            @endif
                        </div>

                        <div style="display: flex; gap: 0.75rem;">
                            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="btn-ocean" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">Edit</a>
                            <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sunset" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; border: none;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 2rem;">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="card-colorful purple" style="text-align: center; padding: 3rem;">
                <div class="card-body">
                    <span class="icon-bounce" style="font-size: 3rem;">📭</span>
                    <h3 style="margin: 1rem 0; color: var(--gray-900);">No tasks yet</h3>
                    <p style="margin-bottom: 1.5rem; color: var(--gray-600);">Create your first task to start assigning work to laborers.</p>
                    <a href="{{ route('projects.tasks.create', $project) }}" class="btn-rainbow">
                        <span class="icon-pulse">✨</span> Add First Task
                    </a>
                </div>
            </div>
        @endif
@endsection
