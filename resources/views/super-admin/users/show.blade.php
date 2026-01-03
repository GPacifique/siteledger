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
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .badge-super-admin { background: #ff6b6b; color: white; }
        .badge-admin { background: #4c6ef5; color: white; }
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

        <div class="card">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($user->is_super_admin)
                            <span class="badge badge-super-admin">Super Admin</span>
                        @elseif($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span class="badge badge-admin">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        @else
                            Active
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

        <div style="margin-top: 30px;">
            <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </div>
</body>
</html>
