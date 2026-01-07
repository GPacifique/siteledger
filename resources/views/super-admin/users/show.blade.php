<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - Super Admin - CSMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%); min-height: 100vh; color: #fff; }
        .navbar { background: rgba(0, 0, 0, 0.3); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar h1 { font-size: 24px; font-weight: 700; color: #667eea; }
        .navbar-links { display: flex; gap: 30px; }
        .navbar-links a { color: #aaa; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar-links a:hover { color: #667eea; }
        .logout-btn { background: #dc3545; padding: 8px 16px; border-radius: 6px; color: white; border: none; cursor: pointer; font-weight: 500; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .breadcrumb { color: #aaa; font-size: 14px; margin-bottom: 30px; }
        .breadcrumb a { color: #667eea; text-decoration: none; }
        h2 { font-size: 32px; margin-bottom: 30px; }
        .card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 30px; margin-bottom: 20px; }
        .info-item { margin-bottom: 15px; }
        .info-label { color: #aaa; font-size: 12px; text-transform: uppercase; }
        .info-value { color: #fff; font-size: 16px; font-weight: 500; margin-top: 5px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; margin-right: 10px; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }
        .btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.2); }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .badge-super-admin { background: #ff6b6b; color: white; }
        .badge-admin { background: #4c6ef5; color: white; }
        .badge-success { background: #10b981; color: white; }
        .badge-warning { background: #f59e0b; color: white; }
        .section-title { font-size: 18px; color: #667eea; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #aaa; font-size: 12px; text-transform: uppercase; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-size: 14px; }
        select.form-control { background-color: #1e293b; color: #ffffff; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
        select.form-control option { background-color: #1e293b; color: #ffffff; padding: 12px; }
        .tenant-list { margin-top: 15px; }
        .tenant-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; margin-bottom: 10px; }
        .tenant-info { display: flex; align-items: center; gap: 12px; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ Super Admin Control</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.users.index') }}">Users</a>
            <a href="{{ route('super-admin.tenants.index') }}">Tenants</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / <a href="{{ route('super-admin.users.index') }}">Users</a> / {{ $user->name }}
        </div>

        <h2>{{ $user->name }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Status</div>
                    <div class="info-value">
                        @if($user->is_super_admin)
                            <span class="badge badge-super-admin">Super Admin</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">System Roles</div>
                    <div class="info-value">
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span class="badge badge-admin">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        @else
                            <span style="color: #9ca3af;">No roles assigned</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tenants</div>
                    <div class="info-value">{{ $user->tenants->count() }} assigned</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $user->created_at->format('M d, Y g:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Current Tenant Assignments -->
        <div class="card">
            <h3 class="section-title">🏢 Current Tenant Assignments</h3>
            @if($user->tenants->count() > 0)
                <div class="tenant-list">
                    @foreach($user->tenants as $tenant)
                        <div class="tenant-item">
                            <div class="tenant-info">
                                <div>
                                    <strong>{{ $tenant->name }}</strong>
                                    <div style="font-size: 12px; color: #9ca3af;">{{ $tenant->domain }}</div>
                                </div>
                                <span class="badge badge-{{ $tenant->pivot->role === 'admin' ? 'warning' : 'success' }}">
                                    {{ ucfirst($tenant->pivot->role) }}
                                </span>
                            </div>
                            <form method="POST" action="{{ route('super-admin.remove-tenant') }}" style="display: inline;" onsubmit="return confirm('Remove this user from {{ $tenant->name }}?');">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #9ca3af;">This user is not assigned to any tenant.</p>
            @endif
        </div>

        <!-- Assign to Tenant -->
        <div class="card">
            <h3 class="section-title">➕ Assign to Tenant</h3>
            @php
                $assignedTenantIds = $user->tenants->pluck('id')->toArray();
                $availableTenants = $allTenants->whereNotIn('id', $assignedTenantIds);
            @endphp

            @if($availableTenants->count() > 0)
                <form method="POST" action="{{ route('super-admin.assign-tenant') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="tenant_id">Select Tenant</label>
                            <select id="tenant_id" name="tenant_id" class="form-control" required>
                                <option value="">-- Choose a Tenant --</option>
                                @foreach($availableTenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }} ({{ $tenant->domain }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="role">Role in Tenant</label>
                            <select id="role" name="role" class="form-control">
                                <option value="member">Member</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            @else
                <p style="color: #9ca3af;">This user is already assigned to all available tenants.</p>
            @endif
        </div>

        <!-- Update System Role -->
        <div class="card">
            <h3 class="section-title">🔐 Update System Role</h3>
            <form method="POST" action="{{ route('super-admin.users.update-roles', $user) }}">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="system_role">System Role</label>
                        <select id="system_role" name="roles[]" class="form-control">
                            @foreach($allRoles as $role)
                                <option value="{{ $role->id }}" {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
                <p style="color: #9ca3af; font-size: 12px; margin-top: 10px;">
                    💡 This updates the user's system-wide permissions. Tenant roles are managed separately above.
                </p>
            </form>
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </div>
</body>
</html>
