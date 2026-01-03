<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - CSMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%);
            min-height: 100vh;
            color: #fff;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .navbar h1 {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .navbar-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .navbar-links a {
            color: #aaa;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-links a:hover {
            color: #667eea;
        }

        .logout-btn {
            background: #dc3545;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .breadcrumb {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #fff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(102, 126, 234, 0.4);
        }

        .stat-label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: white;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .section h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #667eea;
        }

        .user-list, .tenant-list {
            display: grid;
            gap: 10px;
        }

        .user-item, .tenant-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 3px solid #667eea;
            transition: all 0.3s;
        }

        .user-item:hover, .tenant-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .user-info h4 {
            color: white;
            margin-bottom: 5px;
        }

        .user-info p {
            font-size: 12px;
            color: #aaa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-super-admin {
            background: #ff6b6b;
            color: white;
        }

        .badge-admin {
            background: #4c6ef5;
            color: white;
        }

        .badge-user {
            background: #69db7c;
            color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            color: #aaa;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: white;
            font-size: 14px;
        }

        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .btn-assign {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-assign:hover {
            background: #764ba2;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }


            .navbar-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <h1>🛡️ Super Admin Control</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.users.index') }}">Users</a>
            <a href="{{ route('super-admin.tenants.index') }}">Tenants</a>
            <a href="{{ route('super-admin.roles.index') }}">Roles</a>
            <a href="{{ route('super-admin.permissions.index') }}">Permissions</a>
            <a href="{{ route('super-admin.audit') }}">Audit</a>
            <a href="{{ route('super-admin.settings') }}">Settings</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="breadcrumb">
            Super Admin / Dashboard
        </div>

        <h2>Welcome, {{ Auth::user()->name }}!</h2>

        <!-- System Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Tenants</div>
                <div class="stat-value">{{ $totalTenants }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Roles</div>
                <div class="stat-value">{{ $totalRoles }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Permissions</div>
                <div class="stat-value">{{ $totalPermissions }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Super Admins</div>
                <div class="stat-value">{{ $superAdmins }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Admin Users</div>
                <div class="stat-value">{{ $adminUsers }}</div>
            </div>
        </div>

        <!-- Daily Card Stats -->
        <h3 style="margin-top: 40px; margin-bottom: 20px; color: #667eea;">Daily Statistics</h3>
        <div class="stats-grid">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-label">Today's Income</div>
                <div class="stat-value">${{ number_format($dailyCardStats['income'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <div class="stat-label" style="color: white;">Today's Expense</div>
                <div class="stat-value" style="color: white;">${{ number_format($dailyCardStats['expense'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-label">Today's Payment</div>
                <div class="stat-value">${{ number_format($dailyCardStats['payment'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-label">Today's Transaction</div>
                <div class="stat-value">${{ number_format($dailyCardStats['transaction'] ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- Monthly Card Stats -->
        <h3 style="margin-top: 40px; margin-bottom: 20px; color: #667eea;">Monthly Statistics</h3>
        <div class="stats-grid">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-label">This Month's Income</div>
                <div class="stat-value">${{ number_format($monthlyCardStats['income'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <div class="stat-label" style="color: white;">This Month's Expense</div>
                <div class="stat-value" style="color: white;">${{ number_format($monthlyCardStats['expense'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-label">This Month's Payment</div>
                <div class="stat-value">${{ number_format($monthlyCardStats['payment'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-label">This Month's Transaction</div>
                <div class="stat-value">${{ number_format($monthlyCardStats['transaction'] ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- Yearly Card Stats -->
        <h3 style="margin-top: 40px; margin-bottom: 20px; color: #667eea;">Yearly Statistics</h3>
        <div class="stats-grid">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-label">This Year's Income</div>
                <div class="stat-value">${{ number_format($yearlyCardStats['income'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <div class="stat-label" style="color: white;">This Year's Expense</div>
                <div class="stat-value" style="color: white;">${{ number_format($yearlyCardStats['expense'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-label">This Year's Payment</div>
                <div class="stat-value">${{ number_format($yearlyCardStats['payment'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-label">This Year's Transaction</div>
                <div class="stat-value">${{ number_format($yearlyCardStats['transaction'] ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="action-buttons">
            <a href="{{ route('super-admin.users.index') }}" class="btn btn-primary">Manage Users</a>
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-primary">Manage Tenants</a>
            <a href="{{ route('super-admin.roles.index') }}" class="btn btn-primary">Manage Roles</a>
            <a href="{{ route('super-admin.permissions.index') }}" class="btn btn-primary">Manage Permissions</a>
            <a href="{{ route('super-admin.audit') }}" class="btn btn-secondary">View Audit Log</a>
            <a href="{{ route('super-admin.settings') }}" class="btn btn-secondary">System Settings</a>
        </div>

        <!-- Recent Users Section -->
        <div class="section">
            <h3>👥 Recent Users</h3>
            <div class="user-list">
                @forelse($recentUsers as $user)
                    <div class="user-item">
                        <div class="user-info">
                            <h4>{{ $user->name }}</h4>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if($user->is_super_admin)
                                <span class="badge badge-super-admin">Super Admin</span>
                            @elseif($user->hasRole('admin'))
                                <span class="badge badge-admin">Admin</span>
                            @else
                                <span class="badge badge-user">User</span>
                            @endif
                            <a href="{{ route('super-admin.users.show', $user) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">View</a>
                        </div>
                    </div>
                @empty
                    <p style="color: #aaa;">No users found</p>
                @endforelse
            </div>
            <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary" style="margin-top: 15px;">View All Users</a>
        </div>

        <!-- Tenants Overview -->
        <div class="section">
            <h3>🏢 Tenants Overview</h3>
            <div class="tenant-list">
                @forelse($tenants as $tenant)
                    <div class="tenant-item">
                        <div class="user-info">
                            <h4>{{ $tenant->name }}</h4>
                            <p>{{ $tenant->users_count }} user(s) · Created {{ $tenant->created_at->format('M d, Y') }}</p>
                        </div>
                        <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Manage</a>
                    </div>
                @empty
                    <p style="color: #aaa;">No tenants found</p>
                @endforelse
            </div>
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-secondary" style="margin-top: 15px;">View All Tenants</a>
        </div>

        <!-- Assign Tenant to User -->
        <div class="section">
            <h3>🔗 Assign Tenant to User</h3>
            <form method="POST" action="{{ route('super-admin.assign-tenant') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="user_id">Select User</label>
                        <select id="user_id" name="user_id" required>
                            <option value="">-- Choose a User --</option>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tenant_id">Select Tenant</label>
                        <select id="tenant_id" name="tenant_id" required>
                            <option value="">-- Choose a Tenant --</option>
                            @foreach($allTenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="role">Role in Tenant</label>
                        <select id="role" name="role">
                            <option value="member">Member</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                        <label for="is_admin" style="margin:0;">Grant Admin</label>
                        <input type="checkbox" id="is_admin" name="is_admin" value="1" />
                    </div>
                </div>
                <button type="submit" class="btn-assign">Assign Tenant to User</button>
            </form>

            @if($errors->any())
                <div style="background: #fee; color: #c33; padding: 12px; border-radius: 6px; margin-top: 15px;">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div style="background: #efe; color: #3c3; padding: 12px; border-radius: 6px; margin-top: 15px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fee; color: #c33; padding: 12px; border-radius: 6px; margin-top: 15px;">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Current Assignments -->
        <div class="section">
            <h3>📋 Current Tenant Assignments</h3>
            <div class="user-list">
                @forelse($allUsers as $user)
                    <div class="user-item">
                        <div class="user-info">
                            <h4>{{ $user->name }}</h4>
                            <p>{{ $user->email }}</p>
                            <p style="margin-top:6px;color:#ccc;font-size:12px;">Tenants: {{ $user->tenants->count() }}</p>
                        </div>
                        <div>
                            @if($user->tenants->isEmpty())
                                <span class="badge badge-user">No tenants</span>
                            @else
                                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                    @foreach($user->tenants as $tenant)
                                        <div style="background:rgba(255,255,255,0.08);padding:8px 12px;border-radius:8px;display:flex;align-items:center;gap:10px;">
                                            <span>{{ $tenant->name }}</span>
                                            <span class="badge" style="background:#4c6ef5;color:#fff;">
                                                {{ $tenant->pivot->role ?? 'member' }}
                                                @if($tenant->pivot->is_admin)
                                                    · admin
                                                @endif
                                            </span>
                                            <form method="POST" action="{{ route('super-admin.remove-tenant') }}" onsubmit="return confirm('Remove {{ $user->name }} from {{ $tenant->name }}?');">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}" />
                                                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}" />
                                                <button type="submit" class="btn btn-secondary" style="padding:6px 10px;">Remove</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="color:#aaa;">No users found</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
