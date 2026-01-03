<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Permissions - Super Admin - CSMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%); min-height: 100vh; color: #fff; }
        .navbar { background: rgba(0, 0, 0, 0.3); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar h1 { font-size: 24px; font-weight: 700; color: #667eea; }
        .navbar-links { display: flex; gap: 30px; }
        .navbar-links a { color: #aaa; text-decoration: none; font-weight: 500; }
        .navbar-links a:hover { color: #667eea; }
        .logout-btn { background: #dc3545; padding: 8px 16px; border-radius: 6px; color: white; border: none; cursor: pointer; }
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
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ Super Admin Control</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
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
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / Permissions
        </div>

        <h2>🔐 Manage Permissions</h2>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        <th>Roles</th>
                        <th>Users</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td><strong>{{ $permission->name }}</strong></td>
                            <td>{{ $permission->roles_count ?? 0 }}</td>
                            <td>{{ $permission->users_count ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #aaa;">No permissions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
