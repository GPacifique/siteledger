<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Tenant - Super Admin - CSMS</title>
    <style>
        body { background: #0f172a; color: #e2e8f0; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji"; }
        .nav { display: flex; gap: 12px; align-items: center; padding: 12px 20px; background: rgba(255,255,255,0.04); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav a { color: #e2e8f0; text-decoration: none; padding: 8px 10px; border-radius: 6px; }
        .nav a:hover { background: rgba(255,255,255,0.06); }
        .logout-btn { background: linear-gradient(90deg, #ff6b6b, #ff8e8e); border: none; color: white; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .container { max-width: 1000px; margin: 0 auto; padding: 24px; }
        .breadcrumb { color: #a3a3a3; margin-bottom: 10px; }
        h2 { color: #c4b5fd; }
        form { background: rgba(255,255,255,0.03); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        label { display: block; color: #cbd5e1; margin-bottom: 6px; }
        input, select { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #0b1324; color: #e2e8f0; }
        .actions { margin-top: 18px; display: flex; gap: 10px; }
        .btn-primary { background: linear-gradient(90deg, #667eea, #764ba2); color: white; text-decoration: none; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; }
        .btn-secondary { background: rgba(255,255,255,0.06); color: #e2e8f0; text-decoration: none; border: 1px solid rgba(255,255,255,0.1); padding: 10px 16px; border-radius: 8px; }
        .error { color: #fb7185; font-size: 13px; margin-top: 6px; }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="nav">
        <div style="flex:1;display:flex;gap:12px;align-items:center;">
            <a href="{{ route('super-admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.users.index') }}">Users</a>
            <a href="{{ route('super-admin.tenants.index') }}">Tenants</a>
            <a href="{{ route('super-admin.roles.index') }}">Roles</a>
            <a href="{{ route('super-admin.permissions.index') }}">Permissions</a>
            <a href="{{ route('dashboard') }}">Back to App</a>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / <a href="{{ route('super-admin.tenants.index') }}">Tenants</a> / Create
        </div>

        <h2>➕ Create Tenant</h2>

        <form method="POST" action="{{ route('super-admin.tenants.store') }}">
            @csrf
            <div class="grid">
                <div>
                    <label for="name">Tenant Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required />
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="domain">Domain</label>
                    <input id="domain" name="domain" type="text" placeholder="example.yourapp.com" value="{{ old('domain') }}" required />
                    @error('domain')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="email">Tenant Email (optional)</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" />
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="contact_email">Contact Email (optional)</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email') }}" />
                    @error('contact_email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="contact_phone">Contact Phone (optional)</label>
                    <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone') }}" />
                    @error('contact_phone')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="business_type">Business Type</label>
                    <select id="business_type" name="business_type">
                        <option value="other" selected>Other</option>
                        <option value="construction">Construction Company</option>
                        <option value="consulting">Consulting</option>
                        <option value="manufacturing">Manufacturing</option>
                        <option value="retail">Retail</option>
                        <option value="service">Service Provider</option>
                    </select>
                    @error('business_type')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn-primary">Create Tenant</button>
                <a href="{{ route('super-admin.tenants.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
