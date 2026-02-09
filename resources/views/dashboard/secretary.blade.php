<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretary Dashboard - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; color: #333; }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .welcome-banner h2 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .welcome-banner p { opacity: 0.9; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
        }
        .stat-card h3 { color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #667eea; }
        .stat-card .change { font-size: 0.85rem; color: #27ae60; margin-top: 0.25rem; }

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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }
        .action-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .action-link:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
        }
        .action-link .icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .action-link .label { font-weight: 600; font-size: 0.95rem; }

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
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e8ecf4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .panel-header h3 { font-size: 1.1rem; color: #333; }
        .panel-header a { color: #667eea; text-decoration: none; font-size: 0.9rem; }
        .panel-header a:hover { text-decoration: underline; }
        .panel-body { padding: 1.5rem; }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .list-item:last-child { border-bottom: none; }
        .list-item .title { font-weight: 600; color: #333; }
        .list-item .meta { font-size: 0.85rem; color: #666; margin-top: 0.25rem; }
        .list-item .status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status.active { background: #d4edda; color: #155724; }
        .status.pending { background: #fff3cd; color: #856404; }
        .status.completed { background: #cce5ff; color: #004085; }

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
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>📋 SiteLedger</h1>
        <div class="user-info">
            <span class="role-badge">Secretary</span>
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
        <!-- Company Context Banner -->
        @if(isset($company))
        <div class="welcome-banner" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>🏢 {{ $company->name }}</h2>
                <p>Secretary Dashboard • {{ now()->format('l, F j, Y') }}</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.9rem; opacity: 0.9;">Welcome back,</div>
                <div style="font-size: 1.3rem; font-weight: 600;">{{ auth()->user()->first_name ?? auth()->user()->name }}</div>
            </div>
        </div>
        @else
        <div class="welcome-banner">
            <h2>Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h2>
            <p>Secretary Dashboard • {{ now()->format('l, F j, Y') }}</p>
            <p style="margin-top: 0.5rem; padding: 0.5rem; background: rgba(255,255,255,0.2); border-radius: 8px; font-size: 0.9rem;">
                ⚠️ You are not assigned to any company yet. Please contact your administrator.
            </p>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Company Clients</h3>
                <div class="value">{{ number_format($totalClients ?? 0) }}</div>
                <div class="change">+{{ $clientsThisMonth ?? 0 }} this month</div>
            </div>
            <div class="stat-card">
                <h3>Active Projects</h3>
                <div class="value">{{ number_format($projectsCount ?? 0) }}</div>
                <div class="change">{{ $projectsThisMonth ?? 0 }} new this month</div>
            </div>
            <div class="stat-card">
                <h3>Pending Tasks</h3>
                <div class="value">{{ number_format($pendingTasks ?? 0) }}</div>
                <div class="change">{{ $tasksToday ?? 0 }} due today</div>
            </div>
            <div class="stat-card">
                <h3>Company Staff</h3>
                <div class="value">{{ number_format($totalStaff ?? 0) }}</div>
                <div class="change">{{ $activeStaff ?? 0 }} active</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions for {{ $company->name ?? 'Your Company' }}</h2>
            <div class="actions-grid">
                <a href="{{ route('clients.index') }}" class="action-link">
                    <span class="icon">👥</span>
                    <span class="label">Manage Clients</span>
                </a>
                <a href="{{ route('clients.create') }}" class="action-link">
                    <span class="icon">➕</span>
                    <span class="label">Add Client</span>
                </a>
                <a href="{{ route('projects.index') }}" class="action-link">
                    <span class="icon">📁</span>
                    <span class="label">View Projects</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="action-link">
                    <span class="icon">🔔</span>
                    <span class="label">Notifications</span>
                </a>
            </div>
        </div>

        <!-- Content Panels -->
        <div class="content-grid">
            <!-- Recent Clients -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📋 {{ $company->name ?? 'Company' }} Clients</h3>
                    <a href="{{ route('clients.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentClients ?? [] as $client)
                        <div class="list-item">
                            <div>
                                <div class="title">{{ $client->name }}</div>
                                <div class="meta">{{ $client->email ?? 'No email' }} • Added {{ $client->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="status {{ $client->status ?? 'active' }}">{{ ucfirst($client->status ?? 'Active') }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">👥</div>
                            <p>No clients yet. Start by adding your first client!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📁 Active Projects</h3>
                    <a href="{{ route('projects.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentProjects ?? [] as $project)
                        <div class="list-item">
                            <div>
                                <div class="title">{{ $project->name }}</div>
                                <div class="meta">{{ $project->client->name ?? 'No client' }} • {{ $project->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="status {{ strtolower($project->status ?? 'active') }}">{{ ucfirst($project->status ?? 'Active') }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">📁</div>
                            <p>No projects available.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Today's Tasks -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📝 Today's Tasks</h3>
                </div>
                <div class="panel-body">
                    @forelse($todaysTasks ?? [] as $task)
                        <div class="list-item">
                            <div>
                                <div class="title">{{ $task->title ?? $task->name }}</div>
                                <div class="meta">{{ $task->project->name ?? 'General' }} • Due {{ $task->due_date ? $task->due_date->format('h:i A') : 'Today' }}</div>
                            </div>
                            <span class="status {{ strtolower($task->status ?? 'pending') }}">{{ ucfirst($task->status ?? 'Pending') }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">✅</div>
                            <p>No tasks scheduled for today.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="panel">
                <div class="panel-header">
                    <h3>🔔 Recent Notifications</h3>
                    <a href="{{ route('notifications.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentNotifications ?? [] as $notification)
                        <div class="list-item">
                            <div>
                                <div class="title">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                <div class="meta">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">🔔</div>
                            <p>No new notifications.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
