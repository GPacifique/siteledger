<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Super Admin - CSMS</title>
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
        .form-group { margin-bottom: 20px; }
        label { display: block; color: #aaa; font-size: 12px; text-transform: uppercase; margin-bottom: 8px; font-weight: 600; }
        input { width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 6px; color: white; }
        input:focus { outline: none; border-color: #667eea; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }
        .btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🛡️ Super Admin Control</h1>
        <div class="navbar-links">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.settings') }}">Settings</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / Settings
        </div>

        <h2>⚙️ System Settings</h2>

        <div class="card">
            <h3 style="margin-bottom: 20px; color: #667eea;">Application Settings</h3>

            <form method="POST" action="{{ route('super-admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="app_name">Application Name</label>
                    <input type="text" id="app_name" name="app_name" value="CSMS" placeholder="Enter application name">
                </div>

                <div class="form-group">
                    <label for="app_description">Application Description</label>
                    <textarea style="width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 6px; color: white; min-height: 100px;" id="app_description" name="app_description" placeholder="Enter application description">Construction and Site Management Financial System</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px; color: #667eea;">System Information</h3>
            <div style="display: grid; gap: 15px;">
                <div>
                    <label style="color: #667eea;">Laravel Version</label>
                    <div style="color: #aaa;">12.39.0</div>
                </div>
                <div>
                    <label style="color: #667eea;">PHP Version</label>
                    <div style="color: #aaa;">{{ phpversion() }}</div>
                </div>
                <div>
                    <label style="color: #667eea;">Database</label>
                    <div style="color: #aaa;">{{ config('database.default') }}</div>
                </div>
                <div>
                    <label style="color: #667eea;">Current Time</label>
                    <div style="color: #aaa;">{{ now()->format('M d, Y g:i A') }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
