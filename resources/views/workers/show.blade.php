@extends('layouts.admin')

@section('title', '$worker->first_name $worker->last_name - Worker Profile')

@section('styles')
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
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .header-card h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .header-card p {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 0.5rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }
        .status-badge.active {
            background: rgba(76, 175, 80, 0.3);
        }
        .status-badge.inactive {
            background: rgba(244, 67, 54, 0.3);
        }
        .status-badge.on-leave {
            background: rgba(255, 193, 7, 0.3);
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        .card h3 {
            font-size: 1.1rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 1.5rem;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            color: #999;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            text-align: center;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #999;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 1.5rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        .table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .table a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .table a:hover {
            text-decoration: underline;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.75rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge.completed {
            background: #d4edda;
            color: #155724;
        }
        .badge.in_progress {
            background: #fff3cd;
            color: #856404;
        }
        .badge.pending {
            background: #e2e3e5;
            color: #383d41;
        }
        .badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #999;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .project-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .project-card h4 {
            color: #667eea;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        .project-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        .highlight {
            background: #fff3cd;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <a href="{{ route('workers.index') }}" class="back-link">← Back to Workers</a>

        <!-- Header Card -->
        <div class="header-card">
            <h1>👷 {{ $worker->first_name }} {{ $worker->last_name }}</h1>
            <p>{{ $worker->position ?? 'Worker' }}</p>
            <span class="status-badge {{ strtolower(str_replace('_', '-', $worker->status ?? 'active')) }}">
                {{ ucfirst(str_replace('_', ' ', $worker->status ?? 'Active')) }}
            </span>
            <div class="action-buttons" style="margin-top: 1rem;">
                <a href="{{ route('workers.edit', $worker->id) }}" class="btn btn-primary">✏️ Edit</a>
                <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this worker?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                </form>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card">
            <h2>📋 Personal Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $worker->email ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $worker->phone ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Hired Date</span>
                    <span class="info-value">{{ $worker->hired_at?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value">{{ $worker->created_at?->format('M d, Y') ?? '—' }}</span>
                </div>
            </div>
            @if($worker->notes)
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f0f0f0;">
                    <h3>Notes</h3>
                    <p style="color: #666; line-height: 1.6;">{{ $worker->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Statistics -->
        <div class="card">
            <h2>📊 Work Statistics</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value">{{ $stats['total_tasks'] }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-box" style="border-left-color: #27ae60;">
                    <div class="stat-value" style="color: #27ae60;">{{ $stats['completed_tasks'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-box" style="border-left-color: #f39c12;">
                    <div class="stat-value" style="color: #f39c12;">{{ $stats['in_progress_tasks'] }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-box" style="border-left-color: #95a5a6;">
                    <div class="stat-value" style="color: #95a5a6;">{{ $stats['pending_tasks'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-box" style="border-left-color: #3498db;">
                    <div class="stat-value" style="color: #3498db;">{{ $stats['total_projects'] }}</div>
                    <div class="stat-label">Projects</div>
                </div>
                <div class="stat-box" style="border-left-color: #e74c3c;">
                    <div class="stat-value" style="color: #e74c3c;">RWF {{ number_format($stats['total_wages'], 0) }}</div>
                    <div class="stat-label">Total Wages</div>
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #f0f0f0;">
                <h3>Hours & Costs</h3>
                <div class="stats-grid">
                    <div class="stat-box" style="border-left-color: #16a085;">
                        <div class="stat-value" style="color: #16a085;">{{ $stats['estimated_hours'] }}</div>
                        <div class="stat-label">Estimated Hours</div>
                    </div>
                    <div class="stat-box" style="border-left-color: #2980b9;">
                        <div class="stat-value" style="color: #2980b9;">{{ $stats['actual_hours'] }}</div>
                        <div class="stat-label">Actual Hours</div>
                    </div>
                    <div class="stat-box" style="border-left-color: #8e44ad;">
                        <div class="stat-value" style="color: #8e44ad;">RWF {{ number_format($stats['total_actual_cost'], 0) }}</div>
                        <div class="stat-label">Total Cost</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Worked On -->
        @if($projects->count() > 0)
            <div class="card">
                <h2>🏗️ Projects Worked On ({{ $projects->count() }})</h2>
                @foreach($projects as $project)
                    <div class="project-card">
                        <h4>
                            <a href="{{ route('projects.show', $project) }}" style="color: #667eea;">{{ $project->name }}</a>
                        </h4>
                        <p><strong>Client:</strong> {{ $project->client->name ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> <span class="badge {{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span></p>
                        <p><strong>Contract Value:</strong> RWF {{ number_format($project->contract_value, 0) }}</p>
                        <p><strong>Tasks:</strong> {{ $project->tasks->count() }} total ({{ $project->tasks->where('assigned_to', $worker->id)->count() }} assigned to this worker)</p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- All Tasks -->
        @if($tasks->count() > 0)
            <div class="card">
                <h2>✅ All Tasks ({{ $tasks->count() }})</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Hours</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>
                                        <a href="{{ route('projects.tasks.edit', [$task->project_id, $task->id]) }}">
                                            {{ $task->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', $task->project_id) }}">
                                            {{ $task->project->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td><span class="badge {{ strtolower($task->status) }}">{{ ucfirst($task->status) }}</span></td>
                                    <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</td>
                                    <td>{{ $task->actual_hours ?? $task->estimated_hours ?? '—' }}</td>
                                    <td>RWF {{ number_format($task->actual_cost ?? 0, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card">
                <div class="empty-state">
                    <p>📋 No tasks assigned yet</p>
                </div>
            </div>
        @endif

        <!-- Payment History -->
        @if($allPayments->count() > 0)
            <div class="card">
                <h2>💳 Payment History ({{ $allPayments->count() }} payments)</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date Paid</th>
                                <th>Amount</th>
                                <th>Project</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allPayments as $payment)
                                <tr>
                                    <td>{{ $payment->paid_on?->format('M d, Y') ?? '—' }}</td>
                                    <td><strong>RWF {{ number_format($payment->amount, 0) }}</strong></td>
                                    <td>
                                        @if($payment->project)
                                            <a href="{{ route('projects.show', $payment->project->id) }}">
                                                {{ $payment->project->name }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $payment->notes ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #f0f0f0;">
                    <div class="info-item">
                        <span class="info-label">Total Paid</span>
                        <span class="info-value" style="color: #27ae60; font-size: 1.8rem;">RWF {{ number_format($stats['total_wages'], 0) }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <h2>💳 Payment History</h2>
                <div class="empty-state">
                    <p>No payments recorded yet</p>
                </div>
            </div>
        @endif

        <!-- Recent Payments (Summary) -->
        @if($recentPayments->count() > 0)
            <div class="card">
                <h2>📌 Recent Payments (Last 10)</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Project</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->paid_on?->format('M d, Y') ?? '—' }}</td>
                                    <td><strong>RWF {{ number_format($payment->amount, 0) }}</strong></td>
                                    <td>
                                        @if($payment->project)
                                            <a href="{{ route('projects.show', $payment->project->id) }}">
                                                {{ $payment->project->name }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
