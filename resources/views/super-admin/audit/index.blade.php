<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Super Admin - SiteLedger</title>
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
        .card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; margin-bottom: 15px; }
        .activity { padding: 15px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .activity:last-child { border-bottom: none; }
        .activity-time { color: #aaa; font-size: 12px; }
        .activity-user { color: #667eea; font-weight: 600; }
        .activity-action { color: #ccc; margin: 5px 0; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ Super Admin Control</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.audit') }}">Audit Log</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / Audit Log
        </div>

        <h2>📋 System Audit Log</h2>

        <h3 style="color: #667eea; margin-bottom: 20px;">Recent User Activity</h3>
        @forelse($recentUsers as $user)
            <div class="card">
                <div class="activity">
                    <div class="activity-time">{{ $user->updated_at->diffForHumans() }}</div>
                    <div class="activity-user">{{ $user->name }}</div>
                    <div class="activity-action">{{ $user->email }}</div>
                </div>
            </div>
        @empty
            <div class="card">
                <p style="color: #aaa;">No recent activity</p>
            </div>
        @endforelse

        <h3 style="color: #667eea; margin-bottom: 20px; margin-top: 30px;">Recent Tenant Changes</h3>
        @forelse($recentTenants as $tenant)
            <div class="card">
                <div class="activity">
                    <div class="activity-time">{{ $tenant->updated_at->diffForHumans() }}</div>
                    <div class="activity-user">{{ $tenant->name }}</div>
                    <div class="activity-action">Tenant updated</div>
                </div>
            </div>
        @empty
            <div class="card">
                <p style="color: #aaa;">No recent activity</p>
            </div>
        @endforelse

        <div style="margin-top: 30px;">
            <a href="{{ route('super-admin.dashboard') }}" style="padding: 10px 20px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 6px; color: white; text-decoration: none; display: inline-block;">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
