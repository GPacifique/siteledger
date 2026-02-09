<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles - Super Admin - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%); min-height: 100vh; color: #fff; }
        .navbar { background: rgba(0, 0, 0, 0.3); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar h1 { font-size: 24px; font-weight: 700; color: #667eea; }
        .navbar-links { display: flex; gap: 30px; }
        .navbar-links a { color: #aaa; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar-links a:hover { color: #667eea; }
        .logout-btn { background: #dc3545; padding: 8px 16px; border-radius: 6px; color: white; border: none; cursor: pointer; font-weight: 500; }
        .container { max-width: 1400px; margin: 0 auto; padding: 40px 20px; }
        .breadcrumb { color: #aaa; font-size: 14px; margin-bottom: 30px; }
        .breadcrumb a { color: #667eea; text-decoration: none; }
        h2 { font-size: 32px; margin-bottom: 30px; }
        .table-section { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 30px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: rgba(255, 255, 255, 0.05); border-bottom: 2px solid rgba(102, 126, 234, 0.3); }
        th { padding: 15px; text-align: left; font-weight: 600; color: #667eea; text-transform: uppercase; font-size: 12px; }
        td { padding: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        tbody tr:hover { background: rgba(102, 126, 234, 0.1); }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 10px; background: rgba(102, 126, 234, 0.3); color: #667eea; margin-right: 4px; margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ SiteLedger Admin</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.roles.index') }}">Roles</a>
            <a href="{{ route('super-admin.permissions.index') }}">Permissions</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / Roles
        </div>

        <h2>🔐 Manage Roles</h2>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td><strong>{{ ucfirst($role->name) }}</strong></td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($role->permissions->take(3) as $perm)
                                        <span class="badge">{{ $perm->name }}</span>
                                    @endforeach
                                    @if($role->permissions->count() > 3)
                                        <span class="badge">+{{ $role->permissions->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $role->users_count ?? 0 }}</td>
                            <td>
                                <a href="{{ route('super-admin.roles.show', $role) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #aaa;">No roles found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
