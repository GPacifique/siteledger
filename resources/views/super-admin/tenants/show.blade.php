<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Details - Super Admin - SiteLedger</title>
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
        .info-item { margin-bottom: 15px; }
        .info-label { color: #aaa; font-size: 12px; text-transform: uppercase; }
        .info-value { color: #fff; font-size: 16px; font-weight: 500; margin-top: 5px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 10px; }
        .btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ SiteLedger Admin</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
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
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / <a href="{{ route('super-admin.tenants.index') }}">Tenants</a> / {{ $tenant->name }}
        </div>

        <h2>{{ $tenant->name }}</h2>

        <div class="card">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div class="info-item">
                    <div class="info-label">Tenant Name</div>
                    <div class="info-value">{{ $tenant->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Users</div>
                    <div class="info-value">{{ $usersCount }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Admins</div>
                    <div class="info-value">{{ $admins->count() }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $tenant->created_at->format('M d, Y g:i A') }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px;">Tenant Admins</h3>
            @if($admins->isNotEmpty())
                <ul style="list-style: none;">
                    @foreach($admins as $admin)
                        <li style="padding: 10px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                            {{ $admin->name }} ({{ $admin->email }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="color: #aaa;">No admins assigned</p>
            @endif
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-secondary">Back to Tenants</a>
        </div>
    </div>
</body>
</html>
