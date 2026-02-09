<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Tenant - Super Admin - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f1729 0%, #1a1f3a 100%);
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .nav {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 16px 24px;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        .nav a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav a:hover {
            background: rgba(102, 126, 234, 0.15);
            color: #667eea;
        }
        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 32px 24px;
        }
        .breadcrumb {
            color: #94a3b8;
            margin-bottom: 24px;
            font-size: 0.95rem;
        }
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .breadcrumb a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        h2 {
            color: #ffffff;
            font-size: 2rem;
            margin-bottom: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
        }
        form {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.6) 100%);
            padding: 32px;
            border-radius: 16px;
            border: 2px solid rgba(102, 126, 234, 0.15);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }
        label {
            display: block;
            color: #cbd5e1;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 0.95rem;
        }
        input, select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            background: rgba(11, 19, 36, 0.7);
            color: #e2e8f0;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        input::placeholder, select::placeholder {
            color: #64748b;
        }
        input:hover, select:hover {
            border-color: rgba(102, 126, 234, 0.4);
            background: rgba(11, 19, 36, 0.9);
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(11, 19, 36, 1);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        input:focus { color: #ffffff; }
        .section-divider {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid rgba(102, 126, 234, 0.2);
        }
        .section-title {
            color: #b4aeff;
            margin-bottom: 18px;
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .actions {
            margin-top: 32px;
            display: flex;
            gap: 12px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            flex: 1;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            text-decoration: none;
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            flex: 1;
        }
        .btn-secondary:hover {
            background: rgba(102, 126, 234, 0.1);
            border-color: rgba(102, 126, 234, 0.4);
            color: #667eea;
            transform: translateY(-2px);
        }
        .error {
            color: #fb7185;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .error::before {
            content: '⚠';
            font-size: 1rem;
        }
        .tip-message {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-top: 12px;
            padding: 12px 16px;
            background: rgba(102, 126, 234, 0.08);
            border-left: 4px solid #667eea;
            border-radius: 6px;
        }
        .optgroup {
            color: #cbd5e1;
        }
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            h2 {
                font-size: 1.5rem;
            }
            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="{{ route('super-admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <a href="{{ route('super-admin.tenants.index') }}"><i class="fas fa-building"></i> Tenants</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-left: auto;">
            @csrf
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
            <span> / </span>
            <a href="{{ route('super-admin.tenants.index') }}"><i class="fas fa-building"></i> Tenants</a>
            <span> / </span>
            <strong><i class="fas fa-plus-circle"></i> Create New Tenant</strong>
        </div>

        <h2><i class="fas fa-building page-icon"></i> Create New Tenant</h2>

        <form method="POST" action="{{ route('super-admin.tenants.store') }}">
            @csrf

            <div class="section-title">
                <i class="fas fa-info-circle"></i> Tenant Information
            </div>
            <div class="grid">
                <div>
                    <label for="name"><i class="fas fa-tag"></i> Tenant Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required />
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="domain"><i class="fas fa-globe"></i> Domain *</label>
                    <input id="domain" name="domain" type="text" placeholder="example.yourapp.com" value="{{ old('domain') }}" required />
                    @error('domain')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="email"><i class="fas fa-envelope"></i> Tenant Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" />
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="contact_email"><i class="fas fa-envelope-open"></i> Contact Email</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email') }}" />
                    @error('contact_email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="contact_phone"><i class="fas fa-phone"></i> Contact Phone</label>
                    <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone') }}" />
                    @error('contact_phone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="business_type"><i class="fas fa-briefcase"></i> Business Type</label>
                    <select id="business_type" name="business_type">
                        <option value="other" selected>Other</option>
                        <option value="construction" {{ old('business_type') === 'construction' ? 'selected' : '' }}>Construction Company</option>
                        <option value="consulting" {{ old('business_type') === 'consulting' ? 'selected' : '' }}>Consulting</option>
                        <option value="manufacturing" {{ old('business_type') === 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                        <option value="retail" {{ old('business_type') === 'retail' ? 'selected' : '' }}>Retail</option>
                        <option value="service" {{ old('business_type') === 'service' ? 'selected' : '' }}>Service Provider</option>
                    </select>
                    @error('business_type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="section-divider">
                <div class="section-title">
                    <i class="fas fa-user-shield"></i> Company Admin Assignment
                </div>
            </div>
            <div class="grid">
                <div>
                    <label for="admin_user_id"><i class="fas fa-user-tie"></i> Select Company Admin</label>
                    <select id="admin_user_id" name="admin_user_id">
                        <option value="">-- No Admin (Assign Later) --</option>
                        @if($unassignedUsers->count() > 0)
                            <optgroup label="Unassigned Users (Recommended)" style="color: #10b981;">
                                @foreach($unassignedUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('admin_user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if($recentUsers->count() > 0)
                            <optgroup label="Recent Users (May Have Tenants)" style="color: #f59e0b;">
                                @foreach($recentUsers as $user)
                                    @if(!$unassignedUsers->contains('id', $user->id))
                                        <option value="{{ $user->id }}" {{ old('admin_user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }}) {{ $user->tenants->count() > 0 ? '[' . $user->tenants->count() . ' tenant(s)]' : '' }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    @error('admin_user_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="admin_role"><i class="fas fa-lock"></i> Admin Role in Tenant</label>
                    <select id="admin_role" name="admin_role">
                        <option value="admin" {{ old('admin_role', 'admin') == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                        <option value="manager" {{ old('admin_role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="member" {{ old('admin_role') == 'member' ? 'selected' : '' }}>Member</option>
                    </select>
                    @error('admin_role')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="tip-message">
                <i class="fas fa-lightbulb"></i> Tip: Selecting an unassigned user is recommended. Users already in other tenants can also be added.
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check-circle"></i> Create Tenant
                </button>
                <a href="{{ route('super-admin.tenants.index') }}" class="btn-secondary">
                    <i class="fas fa-times-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>
