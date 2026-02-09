<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Super Admin - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%); min-height: 100vh; color: #fff; }
        .navbar { background: rgba(0, 0, 0, 0.3); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar h1 { font-size: 24px; font-weight: 700; color: #667eea; }
        .navbar-links { display: flex; gap: 30px; }
        .navbar-links a { color: #aaa; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar-links a:hover { color: #667eea; }
        .logout-btn { background: #dc3545; padding: 8px 16px; border-radius: 6px; color: white; border: none; cursor: pointer; font-weight: 500; transition: all 0.3s; }
        .logout-btn:hover { background: #c82333; }
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
        .btn-primary:hover { background: #764ba2; transform: translateY(-2px); }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .badge-super-admin { background: #ff6b6b; color: white; }
        .badge-admin { background: #4c6ef5; color: white; }
        .badge-user { background: #69db7c; color: #1a1a1a; }
        .pagination { margin-top: 30px; display: flex; gap: 10px; justify-content: center; }
        .pagination a, .pagination span { padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.2); color: #aaa; text-decoration: none; transition: all 0.3s; }
        .pagination a:hover { background: rgba(102, 126, 234, 0.3); color: #667eea; }
        .pagination .active { background: #667eea; color: white; border-color: #667eea; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ SiteLedger Admin</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.users.index') }}">Users</a>
            <a href="{{ route('super-admin.tenants.index') }}">Tenants</a>
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
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / Users
        </div>

        <h2>👥 Manage Users</h2>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tenants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_super_admin)
                                    <span class="badge badge-super-admin">Super Admin</span>
                                @elseif($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-admin">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-user">User</span>
                                @endif
                            </td>
                            <td>{{ $user->tenants->count() }}</td>
                            <td>
                                <a href="{{ route('super-admin.users.show', $user) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #aaa;">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="pagination">
                    @if ($users->onFirstPage())
                        <span>← Previous</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">← Previous</a>
                    @endif
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">Next →</a>
                    @else
                        <span>Next →</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
