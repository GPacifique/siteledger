<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - SiteLedger</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; color: #333; }

        .navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
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
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome-banner h2 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .welcome-banner p { opacity: 0.9; }
        .welcome-banner .date-info { text-align: right; }
        .welcome-banner .date-info .day { font-size: 2.5rem; font-weight: 700; }
        .welcome-banner .date-info .month { font-size: 1.1rem; opacity: 0.9; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #2c3e50;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(44, 62, 80, 0.2);
        }
        .stat-card h3 { color: #666; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #2c3e50; }
        .stat-card .change { font-size: 0.85rem; color: #27ae60; margin-top: 0.25rem; }
        .stat-card.projects { border-left-color: #3498db; }
        .stat-card.projects .value { color: #3498db; }
        .stat-card.workers { border-left-color: #e67e22; }
        .stat-card.workers .value { color: #e67e22; }
        .stat-card.revenue { border-left-color: #27ae60; }
        .stat-card.revenue .value { color: #27ae60; }
        .stat-card.tasks { border-left-color: #9b59b6; }
        .stat-card.tasks .value { color: #9b59b6; }

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
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .action-link:hover {
            border-color: #2c3e50;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            transform: translateY(-2px);
        }
        .action-link .icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .action-link .label { font-weight: 600; font-size: 0.95rem; text-align: center; }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .panel-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e8ecf4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .panel-header h3 { font-size: 1.1rem; color: #333; }
        .panel-header a { color: #3498db; text-decoration: none; font-size: 0.9rem; }
        .panel-header a:hover { text-decoration: underline; }
        .panel-body { padding: 1.5rem; }

        .project-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }
        .project-card:hover { background: #fafafa; border-color: #ddd; }
        .project-card:last-child { margin-bottom: 0; }
        .project-info .name { font-weight: 600; color: #333; }
        .project-info .client { font-size: 0.85rem; color: #666; }
        .project-info .meta { font-size: 0.8rem; color: #999; margin-top: 0.25rem; }
        .project-progress { text-align: right; }
        .project-progress .amount { font-weight: 700; color: #2c3e50; }
        .project-progress .bar {
            width: 100px;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .project-progress .bar-fill {
            height: 100%;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            border-radius: 3px;
        }

        .worker-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .worker-item:last-child { border-bottom: none; }
        .worker-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            margin-right: 1rem;
            font-size: 0.9rem;
        }
        .worker-info { flex: 1; }
        .worker-info .name { font-weight: 600; color: #333; font-size: 0.95rem; }
        .worker-info .position { font-size: 0.8rem; color: #666; }
        .worker-status {
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .worker-status.active { background: #d4edda; color: #155724; }

        .chart-container {
            height: 250px;
            padding: 1rem;
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .task-item:last-child { border-bottom: none; }
        .task-info .title { font-weight: 600; color: #333; font-size: 0.95rem; }
        .task-info .meta { font-size: 0.8rem; color: #666; }
        .task-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .task-status.pending { background: #fff3cd; color: #856404; }
        .task-status.in-progress { background: #cce5ff; color: #004085; }
        .task-status.completed { background: #d4edda; color: #155724; }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .empty-state .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

        @media (max-width: 1024px) {
            .content-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .welcome-banner { flex-direction: column; text-align: center; }
            .welcome-banner .date-info { margin-top: 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>📊 SiteLedger</h1>
        <div class="user-info">
            <span class="role-badge">Manager</span>
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
            <div>
                @if(isset($company))
                    <h2>🏢 {{ $company->name }}</h2>
                    <p style="font-size: 1.2rem; margin-bottom: 0.25rem;">Manager Dashboard</p>
                    <p>Operations overview and project management</p>
                @else
                    <h2>Manager Dashboard</h2>
                    <p>Operations overview and project management</p>
                    <div style="background: rgba(255,255,255,0.2); padding: 0.75rem 1rem; border-radius: 8px; margin-top: 0.75rem; font-size: 0.9rem;">
                        ⚠️ <strong>No Company Assigned</strong> - Contact your administrator to be assigned to a company.
                    </div>
                @endif
            </div>
            <div class="date-info">
                <div class="day">{{ now()->format('d') }}</div>
                <div class="month">{{ now()->format('F Y') }}</div>
            </div>
        </div>

        @if(isset($company))
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card projects">
                <h3>Company Projects</h3>
                <div class="value">{{ number_format($projectsCount ?? 0) }}</div>
                <div class="change">+{{ $projectsThisMonth ?? 0 }} this month</div>
            </div>
            <div class="stat-card workers">
                <h3>Company Workers</h3>
                <div class="value">{{ number_format($totalWorkers ?? 0) }}</div>
                <div class="change">{{ $activeWorkers ?? 0 }} active</div>
            </div>
            <div class="stat-card revenue">
                <h3>Project Value</h3>
                <div class="value">RWF {{ number_format($projectsTotal ?? 0, 0) }}</div>
                <div class="change">Total contract value</div>
            </div>
            <div class="stat-card tasks">
                <h3>Company Tasks</h3>
                <div class="value">{{ number_format($activeTasks ?? 0) }}</div>
                <div class="change">{{ $completedTasks ?? 0 }} completed</div>
            </div>
            <div class="stat-card">
                <h3>Company Clients</h3>
                <div class="value">{{ number_format($totalClients ?? 0) }}</div>
                <div class="change">{{ $activeClients ?? 0 }} active</div>
            </div>
        </div>
        @else
        <div class="stats-grid">
            <div class="stat-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                <h3 style="font-size: 1.2rem; color: #666;">No Company Data Available</h3>
                <p style="color: #999; margin-top: 0.5rem;">You need to be assigned to a company to view operations data.</p>
            </div>
        </div>
        @endif

        @if(isset($company))

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('projects.create') }}" class="action-link">
                    <span class="icon">📁</span>
                    <span class="label">New Project</span>
                </a>
                <a href="{{ route('clients.create') }}" class="action-link">
                    <span class="icon">👤</span>
                    <span class="label">Add Client</span>
                </a>
                <a href="{{ route('workers.create') }}" class="action-link">
                    <span class="icon">👷</span>
                    <span class="label">Add Worker</span>
                </a>
                <a href="{{ route('projects.index') }}" class="action-link">
                    <span class="icon">📊</span>
                    <span class="label">View Projects</span>
                </a>
                <a href="{{ route('workers.index') }}" class="action-link">
                    <span class="icon">👥</span>
                    <span class="label">Manage Team</span>
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <div>
                <!-- Projects Panel -->
                <div class="panel" style="margin-bottom: 2rem;">
                    <div class="panel-header">
                        <h3>📁 Active Projects</h3>
                        <a href="{{ route('projects.index') }}">View all →</a>
                    </div>
                    <div class="panel-body">
                        @forelse($recentProjects ?? [] as $project)
                            <div class="project-card">
                                <div class="project-info">
                                    <div class="name">{{ $project->name }}</div>
                                    <div class="client">{{ $project->client->name ?? 'No client' }}</div>
                                    <div class="meta">Started {{ $project->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="project-progress">
                                    <div class="amount">RWF {{ number_format($project->contract_value ?? 0, 0) }}</div>
                                    @php
                                        $paid = $project->incomes->sum('amount_received') ?? 0;
                                        $total = $project->contract_value ?? 1;
                                        $progress = min(100, ($paid / max($total, 1)) * 100);
                                    @endphp
                                    <div class="bar">
                                        <div class="bar-fill" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="icon">📁</div>
                                <p>No active projects. Create your first project!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Project Revenue Chart -->
                <div class="panel">
                    <div class="panel-header">
                        <h3>📈 Project Revenue Trend</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div>
                <!-- Workers Panel -->
                <div class="panel" style="margin-bottom: 2rem;">
                    <div class="panel-header">
                        <h3>👷 Team Members</h3>
                        <a href="{{ route('workers.index') }}">View all →</a>
                    </div>
                    <div class="panel-body">
                        @forelse($recentWorkers ?? [] as $worker)
                            <div class="worker-item">
                                <div class="worker-avatar">
                                    {{ strtoupper(substr($worker->name ?? $worker->first_name ?? 'W', 0, 1)) }}
                                </div>
                                <div class="worker-info">
                                    <div class="name">{{ $worker->name ?? ($worker->first_name . ' ' . $worker->last_name) }}</div>
                                    <div class="position">{{ $worker->position ?? 'Worker' }}</div>
                                </div>
                                <span class="worker-status active">Active</span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="icon">👷</div>
                                <p>No workers yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tasks Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3>📋 Recent Tasks</h3>
                    </div>
                    <div class="panel-body">
                        @forelse($recentTasks ?? [] as $task)
                            <div class="task-item">
                                <div class="task-info">
                                    <div class="title">{{ $task->title ?? $task->name }}</div>
                                    <div class="meta">{{ $task->project->name ?? 'General' }}</div>
                                </div>
                                <span class="task-status {{ str_replace(' ', '-', strtolower($task->status ?? 'pending')) }}">
                                    {{ ucfirst($task->status ?? 'Pending') }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="icon">📋</div>
                                <p>No tasks yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if(isset($company))
    <script>
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
                datasets: [{
                    label: 'Project Revenue',
                    data: {!! json_encode($projectsMonthly ?? [0, 0, 0, 0, 0, 0]) !!},
                    borderColor: '#2c3e50',
                    backgroundColor: 'rgba(44, 62, 80, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RWF ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endif
</body>
</html>
