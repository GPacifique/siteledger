<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Details - Super Admin - SiteLedger</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%); min-height: 100vh; color: #fff; }
        .navbar { background: rgba(0, 0, 0, 0.3); padding: 20px 40px; display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar h1 { color: #667eea; font-size: 24px; font-weight: 700; }
        .navbar-links { display: flex; gap: 30px; }
        .navbar-links a { color: #aaa; text-decoration: none; font-weight: 500; }
        .navbar-links a:hover { color: #667eea; }
        .logout-btn { background: #dc3545; padding: 8px 16px; border-radius: 6px; color: white; border: none; cursor: pointer; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .breadcrumb { color: #aaa; font-size: 14px; margin-bottom: 30px; }
        .breadcrumb a { color: #667eea; text-decoration: none; }
        h2 { font-size: 32px; margin-bottom: 30px; }
        .card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 30px; margin-bottom: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 10px; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }
        .btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.2); }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 4px; background: rgba(102, 126, 234, 0.3); color: #667eea; margin: 3px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ Super Admin Control</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.roles.index') }}">Roles</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / <a href="{{ route('super-admin.roles.index') }}">Roles</a> / {{ $role->name }}
        </div>

        <h2>{{ ucfirst($role->name) }}</h2>

        <div class="card">
            <h3 style="margin-bottom: 20px;">Permissions ({{ $role->permissions->count() }})</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @forelse($role->permissions as $permission)
                    <span class="badge">{{ $permission->name }}</span>
                @empty
                    <p style="color: #aaa;">No permissions assigned</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px;">Users with this Role</h3>
            @if($role->users->isNotEmpty())
                <ul style="list-style: none;">
                    @foreach($role->users as $user)
                        <li style="padding: 10px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                            {{ $user->name }} ({{ $user->email }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="color: #aaa;">No users have this role</p>
            @endif
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('super-admin.roles.index') }}" class="btn btn-secondary">Back to Roles</a>
        </div>
    </div>
</body>
</html>
