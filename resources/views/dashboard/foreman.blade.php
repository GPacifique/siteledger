<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foreman Dashboard - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; color: #333; }

        .navbar {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 { font-size: 1.5rem; }
        .navbar .user-info { display: flex; align-items: center; gap: 1rem; }
        .navbar .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .navbar button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .navbar button:hover { background: rgba(255,255,255,0.3); }

        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }

        .welcome-banner {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .welcome-banner h2 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .welcome-banner p { opacity: 0.9; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #f39c12;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(243, 156, 18, 0.2);
        }
        .stat-card h3 { color: #666; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #e67e22; }
        .stat-card .change { font-size: 0.85rem; color: #27ae60; margin-top: 0.25rem; }
        .stat-card.workers { border-left-color: #3498db; }
        .stat-card.workers .value { color: #3498db; }
        .stat-card.tasks { border-left-color: #27ae60; }
        .stat-card.tasks .value { color: #27ae60; }
        .stat-card.expenses { border-left-color: #e74c3c; }
        .stat-card.expenses .value { color: #e74c3c; }

        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        .quick-actions h2 { font-size: 1.3rem; color: #333; margin-bottom: 1.5rem; }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
        }
        .action-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            background: linear-gradient(135deg, #fffaf0 0%, #fff 100%);
            border: 2px solid #fdebd0;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .action-link:hover {
            border-color: #f39c12;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            transform: translateY(-2px);
        }
        .action-link .icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .action-link .label { font-weight: 600; font-size: 0.95rem; text-align: center; }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }

        .panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .panel-header {
            background: linear-gradient(135deg, #fffaf0 0%, #fff 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #fdebd0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .panel-header h3 { font-size: 1.1rem; color: #333; }
        .panel-header a { color: #e67e22; text-decoration: none; font-size: 0.9rem; }
        .panel-header a:hover { text-decoration: underline; }
        .panel-body { padding: 1.5rem; }

        .worker-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: background 0.2s;
        }
        .worker-card:hover { background: #fafafa; }
        .worker-card:last-child { margin-bottom: 0; }
        .worker-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            margin-right: 1rem;
        }
        .worker-info { flex: 1; }
        .worker-info .name { font-weight: 600; color: #333; }
        .worker-info .position { font-size: 0.85rem; color: #666; }
        .worker-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .worker-status.active { background: #d4edda; color: #155724; }
        .worker-status.inactive { background: #f8d7da; color: #721c24; }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .task-item:last-child { border-bottom: none; }
        .task-info .title { font-weight: 600; color: #333; }
        .task-info .meta { font-size: 0.85rem; color: #666; margin-top: 0.25rem; }
        .task-priority {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .priority-high { background: #f8d7da; color: #721c24; }
        .priority-medium { background: #fff3cd; color: #856404; }
        .priority-low { background: #d4edda; color: #155724; }

        .expense-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .expense-item:last-child { border-bottom: none; }
        .expense-info .title { font-weight: 600; color: #333; }
        .expense-info .meta { font-size: 0.85rem; color: #666; margin-top: 0.25rem; }
        .expense-amount { font-weight: 700; color: #e74c3c; }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .empty-state .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .content-grid { grid-template-columns: 1fr; }
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>🏗️ SiteLedger</h1>
        <div class="user-info">
            <span class="role-badge">Foreman</span>
            <span>{{ auth()->user()->name }}</span>
            @if(isset($company))
                <span style="opacity: 0.8;">| {{ $company->name }}</span>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-banner">
            @if(isset($company))
                <h2>🏢 {{ $company->name }}</h2>
                <p style="font-size: 1.2rem; margin-bottom: 0.5rem;">Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</p>
                <p>Site supervision dashboard for {{ now()->format('l, F j, Y') }}</p>
            @else
                <h2>Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h2>
                <p>Site supervision dashboard for {{ now()->format('l, F j, Y') }}</p>
                <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                    ⚠️ <strong>No Company Assigned</strong> - Please contact your administrator to be assigned to a company.
                </div>
            @endif
        </div>

        @if(isset($company))
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card workers">
                <h3>Company Workers</h3>
                <div class="value">{{ number_format($totalWorkers ?? 0) }}</div>
                <div class="change">{{ $activeWorkers ?? 0 }} on site</div>
            </div>
            <div class="stat-card tasks">
                <h3>Company Tasks</h3>
                <div class="value">{{ number_format($activeTasks ?? 0) }}</div>
                <div class="change">{{ $tasksCompleted ?? 0 }} completed</div>
            </div>
            <div class="stat-card">
                <h3>Current Project</h3>
                <div class="value">{{ $currentProject->name ?? 'N/A' }}</div>
                <div class="change">{{ $projectProgress ?? 0 }}% complete</div>
            </div>
            <div class="stat-card expenses">
                <h3>Site Expenses</h3>
                <div class="value">RWF {{ number_format($siteExpenses ?? 0, 0) }}</div>
                <div class="change">This month</div>
            </div>
        </div>
        @else
        <div class="stats-grid">
            <div class="stat-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏗️</div>
                <h3 style="font-size: 1.2rem; color: #666;">No Company Data Available</h3>
                <p style="color: #999; margin-top: 0.5rem;">You need to be assigned to a company to view site data.</p>
            </div>
        </div>
        @endif

        @if(isset($company))
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('workers.index') }}" class="action-link">
                    <span class="icon">👷</span>
                    <span class="label">Manage Workers</span>
                </a>
                <a href="{{ route('workers.create') }}" class="action-link">
                    <span class="icon">➕</span>
                    <span class="label">Add Worker</span>
                </a>
                <a href="{{ route('projects.index') }}" class="action-link">
                    <span class="icon">📁</span>
                    <span class="label">View Projects</span>
                </a>
                <a href="{{ route('expenses.create') }}" class="action-link">
                    <span class="icon">💰</span>
                    <span class="label">Log Expense</span>
                </a>
            </div>
        </div>

        <!-- Content Panels -->
        <div class="content-grid">
            <!-- Workers on Site -->
            <div class="panel">
                <div class="panel-header">
                    <h3>👷 Workers on Site</h3>
                    <a href="{{ route('workers.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentWorkers ?? [] as $worker)
                        <div class="worker-card">
                            <div class="worker-avatar">
                                {{ strtoupper(substr($worker->name ?? $worker->first_name ?? 'W', 0, 1)) }}
                            </div>
                            <div class="worker-info">
                                <div class="name">{{ $worker->name ?? ($worker->first_name . ' ' . $worker->last_name) }}</div>
                                <div class="position">{{ $worker->position ?? 'Worker' }}</div>
                            </div>
                            <span class="worker-status {{ strtolower($worker->status ?? 'active') }}">
                                {{ ucfirst($worker->status ?? 'Active') }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">👷</div>
                            <p>No workers assigned yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Tasks -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📋 Active Tasks</h3>
                </div>
                <div class="panel-body">
                    @forelse($activeTasks ?? [] as $task)
                        <div class="task-item">
                            <div class="task-info">
                                <div class="title">{{ $task->title ?? $task->name }}</div>
                                <div class="meta">
                                    @if($task->assignedWorker)
                                        Assigned to {{ $task->assignedWorker->name }}
                                    @endif
                                    • Due {{ $task->due_date ? $task->due_date->format('M j') : 'No date' }}
                                </div>
                            </div>
                            <span class="task-priority priority-{{ strtolower($task->priority ?? 'medium') }}">
                                {{ ucfirst($task->priority ?? 'Medium') }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">✅</div>
                            <p>No active tasks at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Site Expenses -->
            <div class="panel">
                <div class="panel-header">
                    <h3>💰 Recent Site Expenses</h3>
                    <a href="{{ route('expenses.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentExpenses ?? [] as $expense)
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="title">{{ $expense->description ?? $expense->category }}</div>
                                <div class="meta">{{ $expense->created_at->format('M j, Y') }}</div>
                            </div>
                            <span class="expense-amount">RWF {{ number_format($expense->amount, 0) }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">💰</div>
                            <p>No expenses recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Worker Payments -->
            <div class="panel">
                <div class="panel-header">
                    <h3>💳 Recent Worker Payments</h3>
                </div>
                <div class="panel-body">
                    @forelse($recentWorkerPayments ?? [] as $payment)
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="title">{{ $payment->employee->name ?? 'Worker' }}</div>
                                <div class="meta">{{ $payment->created_at->format('M j, Y') }} • {{ ucfirst($payment->status ?? 'Completed') }}</div>
                            </div>
                            <span class="expense-amount">RWF {{ number_format($payment->amount, 0) }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">💳</div>
                            <p>No recent payments.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
