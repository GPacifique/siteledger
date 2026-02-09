<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 100%);
            min-height: 100vh;
            color: #e0e0e0;
        }

        /* Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.4);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-brand h1 {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .navbar-links a {
            color: #9ca3af;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
            padding: 8px 0;
            border-bottom: 2px solid transparent;
        }

        .navbar-links a:hover, .navbar-links a.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Main Container */
        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Grid Layouts */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stats-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .two-column {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .three-column {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Stat Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .stat-card.primary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .stat-card.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
            border-color: rgba(245, 158, 11, 0.3);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-card.primary .stat-icon { background: rgba(102, 126, 234, 0.2); }
        .stat-card.success .stat-icon { background: rgba(16, 185, 129, 0.2); }
        .stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.2); }
        .stat-card.danger .stat-icon { background: rgba(239, 68, 68, 0.2); }

        .stat-label {
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
        }

        .stat-change {
            font-size: 12px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive { color: #10b981; }
        .stat-change.negative { color: #ef4444; }

        /* Section Cards */
        .section-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-body {
            padding: 20px 24px;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-weight: 600;
        }

        .data-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 14px;
        }

        .data-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-primary { background: rgba(102, 126, 234, 0.2); color: #818cf8; }
        .badge-success { background: rgba(16, 185, 129, 0.2); color: #34d399; }
        .badge-warning { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .badge-danger { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .badge-info { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Quick Actions Grid */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            background: rgba(102, 126, 234, 0.1);
            border-color: rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .action-card .icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .action-card .label {
            font-size: 14px;
            font-weight: 500;
            color: #e0e0e0;
        }

        /* Tenant Cards */
        .tenant-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .tenant-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(102, 126, 234, 0.2);
        }

        .tenant-card .tenant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .tenant-card .tenant-name {
            font-weight: 600;
            color: #fff;
            font-size: 15px;
        }

        .tenant-card .tenant-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            font-size: 12px;
            color: #9ca3af;
        }

        .tenant-card .tenant-stats .stat-item {
            text-align: center;
        }

        .tenant-card .tenant-stats .stat-item .value {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
        }

        /* Forms */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Select dropdown styling for visible colors */
        select.form-control {
            background-color: #1e1e2e;
            color: #ffffff;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        select.form-control option {
            background-color: #1e1e2e;
            color: #ffffff;
            padding: 12px;
        }

        select.form-control option:hover,
        select.form-control option:focus,
        select.form-control option:checked {
            background-color: #667eea;
            color: #ffffff;
        }

        select.form-control:hover {
            border-color: #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* Distribution Chart */
        .distribution-chart {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .distribution-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .distribution-item .label {
            width: 100px;
            font-size: 13px;
            color: #9ca3af;
            text-transform: capitalize;
        }

        .distribution-item .bar-container {
            flex: 1;
            height: 24px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            overflow: hidden;
        }

        .distribution-item .bar {
            height: 100%;
            display: flex;
            align-items: center;
            padding-left: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .two-column {
                grid-template-columns: 1fr;
            }
            .three-column {
                grid-template-columns: 1fr 1fr;
            }
            .stats-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 16px;
                padding: 16px;
            }
            .navbar-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
            }
            .stats-grid, .stats-grid-4 {
                grid-template-columns: 1fr;
            }
            .three-column {
                grid-template-columns: 1fr;
            }
            .tenant-card .tenant-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            .container {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <h1>🏗️ SiteLedger <span style="font-size: 0.7em; opacity: 0.8;">Super Admin</span></h1>
        </div>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}" class="active">Dashboard</a>
            <a href="{{ route('super-admin.tenants.index') }}">Tenants</a>
            <a href="{{ route('super-admin.users.index') }}">Users</a>
            <a href="{{ route('super-admin.roles.index') }}">Roles</a>
            <a href="{{ route('super-admin.permissions.index') }}">Permissions</a>
            <a href="{{ route('super-admin.audit') }}">Audit</a>
            <a href="{{ route('super-admin.settings') }}">Settings</a>
            <a href="{{ route('dashboard') }}">← Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2>Platform Overview</h2>
            <p>Welcome back, {{ Auth::user()->name }}. Here's what's happening across your platform.</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Platform Stats -->
        <div class="stats-grid-4">
            <div class="stat-card primary">
                <div class="stat-icon">🏢</div>
                <div class="stat-label">Total Tenants</div>
                <div class="stat-value">{{ $totalTenants }}</div>
                <div class="stat-change positive">
                    +{{ $newTenantsThisMonth }} this month
                </div>
            </div>
            <div class="stat-card success">
                <div class="stat-icon">👥</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-change positive">
                    +{{ $newUsersThisMonth }} this month
                </div>
            </div>
            <div class="stat-card warning">
                <div class="stat-icon">📊</div>
                <div class="stat-label">Total Projects</div>
                <div class="stat-value">{{ $platformTotalProjects }}</div>
                <div class="stat-change">
                    {{ $activeProjects }} active
                </div>
            </div>
            <div class="stat-card danger">
                <div class="stat-icon">🛡️</div>
                <div class="stat-label">Super Admins</div>
                <div class="stat-value">{{ $superAdmins }}</div>
                <div class="stat-change">
                    {{ $adminUsers }} admins
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card" style="margin-bottom: 32px;">
            <div class="section-header">
                <h3>⚡ Quick Actions</h3>
            </div>
            <div class="section-body">
                <div class="quick-actions">
                    <a href="{{ route('super-admin.tenants.create') }}" class="action-card">
                        <div class="icon">🏢</div>
                        <div class="label">New Tenant</div>
                    </a>
                    <a href="{{ route('super-admin.users.index') }}" class="action-card">
                        <div class="icon">👤</div>
                        <div class="label">Manage Users</div>
                    </a>
                    <a href="{{ route('super-admin.roles.index') }}" class="action-card">
                        <div class="icon">🔐</div>
                        <div class="label">Manage Roles</div>
                    </a>
                    <a href="{{ route('super-admin.audit') }}" class="action-card">
                        <div class="icon">📋</div>
                        <div class="label">View Audit Log</div>
                    </a>
                    <a href="{{ route('super-admin.settings') }}" class="action-card">
                        <div class="icon">⚙️</div>
                        <div class="label">System Settings</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Two Column Layout: Tenants & Stats -->
        <div class="two-column">
            <!-- Tenant Overview -->
            <div class="section-card">
                <div class="section-header">
                    <h3>🏢 Tenant Overview</h3>
                    <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-secondary btn-sm">View All</a>
                </div>
                <div class="section-body">
                    @forelse($tenants->take(5) as $tenant)
                        @php $stats = $tenantStats[$tenant->id] ?? []; @endphp
                        <div class="tenant-card">
                            <div class="tenant-header">
                                <span class="tenant-name">{{ $tenant->name }}</span>
                                <span class="badge {{ $tenant->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $tenant->status ?? 'active' }}
                                </span>
                            </div>
                            <div class="tenant-stats">
                                <div class="stat-item">
                                    <div class="value">{{ $tenant->users_count ?? 0 }}</div>
                                    <div>Users</div>
                                </div>
                                <div class="stat-item">
                                    <div class="value">{{ $stats['projects'] ?? 0 }}</div>
                                    <div>Projects</div>
                                </div>
                                <div class="stat-item">
                                    <div class="value">{{ $stats['clients'] ?? 0 }}</div>
                                    <div>Clients</div>
                                </div>
                                <div class="stat-item">
                                    <div class="value">{{ $stats['employees'] ?? 0 }}</div>
                                    <div>Employees</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color: #9ca3af; text-align: center; padding: 40px;">No tenants found. Create your first tenant to get started.</p>
                    @endforelse
                </div>
            </div>

            <!-- Distributions -->
            <div class="section-card">
                <div class="section-header">
                    <h3>📊 Platform Distribution</h3>
                </div>
                <div class="section-body">
                    <!-- Status Distribution -->
                    <h4 style="font-size: 13px; color: #9ca3af; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Tenant Status</h4>
                    <div class="distribution-chart" style="margin-bottom: 24px;">
                        @php $totalStatus = array_sum($statusDistribution); @endphp
                        @forelse($statusDistribution as $status => $count)
                            @php $percentage = $totalStatus > 0 ? ($count / $totalStatus) * 100 : 0; @endphp
                            <div class="distribution-item">
                                <span class="label">{{ ucfirst($status) }}</span>
                                <div class="bar-container">
                                    <div class="bar" style="width: {{ max($percentage, 5) }}%; background: {{ $status === 'active' ? 'linear-gradient(90deg, #10b981, #059669)' : 'linear-gradient(90deg, #f59e0b, #d97706)' }};">
                                        {{ $count }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="color: #9ca3af;">No data available</p>
                        @endforelse
                    </div>

                    <!-- Plan Distribution -->
                    <h4 style="font-size: 13px; color: #9ca3af; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Subscription Plans</h4>
                    <div class="distribution-chart" style="margin-bottom: 24px;">
                        @php $totalPlans = array_sum($planDistribution); @endphp
                        @forelse($planDistribution as $plan => $count)
                            @php $percentage = $totalPlans > 0 ? ($count / $totalPlans) * 100 : 0; @endphp
                            <div class="distribution-item">
                                <span class="label">{{ ucfirst($plan ?? 'basic') }}</span>
                                <div class="bar-container">
                                    <div class="bar" style="width: {{ max($percentage, 5) }}%; background: linear-gradient(90deg, #667eea, #764ba2);">
                                        {{ $count }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="color: #9ca3af;">No data available</p>
                        @endforelse
                    </div>

                    <!-- Role Distribution -->
                    <h4 style="font-size: 13px; color: #9ca3af; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">User Roles</h4>
                    <div class="distribution-chart">
                        @php $totalRolesUsers = array_sum($roleDistribution); @endphp
                        @forelse($roleDistribution as $role => $count)
                            @php $percentage = $totalRolesUsers > 0 ? ($count / $totalRolesUsers) * 100 : 0; @endphp
                            <div class="distribution-item">
                                <span class="label">{{ ucfirst($role) }}</span>
                                <div class="bar-container">
                                    <div class="bar" style="width: {{ max($percentage, 5) }}%; background: linear-gradient(90deg, #3b82f6, #1d4ed8);">
                                        {{ $count }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="color: #9ca3af;">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Business Metrics -->
        <div class="section-card" style="margin-bottom: 32px;">
            <div class="section-header">
                <h3>📈 Platform-Wide Business Metrics</h3>
            </div>
            <div class="section-body">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Clients</div>
                        <div class="stat-value" style="font-size: 24px;">{{ number_format($platformTotalClients) }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Employees</div>
                        <div class="stat-value" style="font-size: 24px;">{{ number_format($platformTotalEmployees) }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Workers</div>
                        <div class="stat-value" style="font-size: 24px;">{{ number_format($platformTotalWorkers) }}</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-label">Platform Income</div>
                        <div class="stat-value" style="font-size: 24px;">{{ number_format($platformTotalIncome, 0) }} <small style="font-size: 14px; color: #9ca3af;">RWF</small></div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-label">Platform Expenses</div>
                        <div class="stat-value" style="font-size: 24px;">{{ number_format($platformTotalExpense, 0) }} <small style="font-size: 14px; color: #9ca3af;">RWF</small></div>
                    </div>
                    <div class="stat-card {{ ($platformTotalIncome - $platformTotalExpense) >= 0 ? 'success' : 'danger' }}">
                        <div class="stat-label">Net Revenue</div>
                        <div class="stat-value" style="font-size: 24px;">{{ number_format($platformTotalIncome - $platformTotalExpense, 0) }} <small style="font-size: 14px; color: #9ca3af;">RWF</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="three-column">
            <!-- Recent Users -->
            <div class="section-card">
                <div class="section-header">
                    <h3>👥 Recent Users</h3>
                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary btn-sm">View All</a>
                </div>
                <div class="section-body" style="padding: 0;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;">{{ $user->name }}</div>
                                        <div style="font-size: 12px; color: #9ca3af;">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        @if($user->is_super_admin)
                                            <span class="badge badge-danger">Super Admin</span>
                                        @elseif($user->hasRole('admin'))
                                            <span class="badge badge-primary">Admin</span>
                                        @else
                                            <span class="badge badge-info">User</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #9ca3af;">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Tenants -->
            <div class="section-card">
                <div class="section-header">
                    <h3>🏢 Recent Tenants</h3>
                    <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-secondary btn-sm">View All</a>
                </div>
                <div class="section-body" style="padding: 0;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTenants as $tenant)
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;">{{ $tenant->name }}</div>
                                        <div style="font-size: 12px; color: #9ca3af;">
                                            @if($tenant->created_at)
                                                {{ $tenant->created_at->diffForHumans() }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $tenant->users_count ?? 0 }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #9ca3af;">No tenants found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- System Info -->
            <div class="section-card">
                <div class="section-header">
                    <h3>⚙️ System Info</h3>
                </div>
                <div class="section-body">
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <span style="color: #9ca3af;">Total Roles</span>
                            <span style="font-weight: 600;">{{ $totalRoles }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <span style="color: #9ca3af;">Total Permissions</span>
                            <span style="font-weight: 600;">{{ $totalPermissions }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <span style="color: #9ca3af;">Active Tenants</span>
                            <span style="font-weight: 600; color: #10b981;">{{ $activeTenants }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;">
                            <span style="color: #9ca3af;">Platform Health</span>
                            <span class="badge badge-success">● Healthy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Tenant Form -->
        <div class="section-card" style="margin-bottom: 32px;">
            <div class="section-header">
                <h3>🔗 Assign User to Tenant</h3>
                <span class="badge badge-info">{{ $unassignedUsers->count() }} unassigned users</span>
            </div>
            <div class="section-body">
                @if($unassignedUsers->count() > 0)
                <form method="POST" action="{{ route('super-admin.assign-tenant') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="user_id">Select User (Unassigned Only)</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="">-- Choose a User --</option>
                                @foreach($unassignedUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tenant_id">Select Tenant</label>
                            <select id="tenant_id" name="tenant_id" class="form-control" required>
                                <option value="">-- Choose a Tenant --</option>
                                @foreach($allTenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Role in Tenant</label>
                            <select id="role" name="role" class="form-control">
                                <option value="member">Member</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button type="submit" class="btn btn-primary">Assign User</button>
                        </div>
                    </div>
                </form>
                @else
                <div style="text-align: center; padding: 24px; color: #9ca3af;">
                    <div style="font-size: 48px; margin-bottom: 12px;">✅</div>
                    <p style="margin-bottom: 8px;">All users are already assigned to tenants!</p>
                    <p style="font-size: 12px;">Create new users or remove existing tenant assignments to see unassigned users here.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Tenant Details Table -->
        <div class="section-card">
            <div class="section-header">
                <h3>📋 Tenant Details & Metrics</h3>
            </div>
            <div class="section-body" style="padding: 0; overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Status</th>
                            <th>Users</th>
                            <th>Projects</th>
                            <th>Clients</th>
                            <th>Income</th>
                            <th>Expenses</th>
                            <th>Net</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                            @php $stats = $tenantStats[$tenant->id] ?? []; @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 500;">{{ $tenant->name }}</div>
                                    <div style="font-size: 12px; color: #9ca3af;">{{ $tenant->domain ?? 'No domain' }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $tenant->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($tenant->status ?? 'active') }}
                                    </span>
                                </td>
                                <td>{{ $tenant->users_count ?? 0 }}</td>
                                <td>{{ $stats['projects'] ?? 0 }}</td>
                                <td>{{ $stats['clients'] ?? 0 }}</td>
                                <td style="color: #10b981;">{{ number_format($stats['total_income'] ?? 0, 0) }}</td>
                                <td style="color: #ef4444;">{{ number_format($stats['total_expense'] ?? 0, 0) }}</td>
                                <td style="color: {{ ($stats['net_revenue'] ?? 0) >= 0 ? '#10b981' : '#ef4444' }};">
                                    {{ number_format($stats['net_revenue'] ?? 0, 0) }}
                                </td>
                                <td>
                                    <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-secondary btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; color: #9ca3af; padding: 40px;">
                                    No tenants found. <a href="{{ route('super-admin.tenants.create') }}" style="color: #667eea;">Create your first tenant</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
