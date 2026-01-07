<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $staffMember->name }} - Staff Details - SiteLedger</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #667eea;
            text-decoration: none;
            margin-bottom: 1rem;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .profile-header {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            flex-shrink: 0;
        }
        .profile-info h1 {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .profile-info .email {
            color: #666;
            margin-bottom: 0.5rem;
        }
        .badge-row {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .role-admin { background: #ffeaa7; color: #d68910; }
        .role-manager { background: #81ecec; color: #00838f; }
        .role-accountant { background: #a29bfe; color: #512da8; }
        .role-employee { background: #dfe6e9; color: #636e72; }
        .role-user { background: #b2bec3; color: #2d3436; }
        .role-viewer { background: #e8f5e9; color: #388e3c; }
        .admin-badge {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status-active { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .details-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .details-card h2 {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #eee;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .actions-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .actions-card h2 {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #eee;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-edit { background: #3498db; color: white; }
        .btn-edit:hover { background: #2980b9; }
        .btn-admin { background: #f39c12; color: white; }
        .btn-admin:hover { background: #d68910; }
        .btn-remove-admin { background: #95a5a6; color: white; }
        .btn-remove-admin:hover { background: #7f8c8d; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-delete:hover { background: #c0392b; }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .profile-header { flex-direction: column; text-align: center; }
            .detail-row { grid-template-columns: 1fr; gap: 0.25rem; }
            .action-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <a href="{{ route('company.staff.index') }}" class="back-link">← Back to Staff List</a>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>❌ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar">{{ strtoupper(substr($staffMember->name, 0, 1)) }}</div>
            <div class="profile-info">
                <h1>{{ $staffMember->name }}</h1>
                <p class="email">{{ $staffMember->email }}</p>
                <div class="badge-row">
                    <span class="role-badge role-{{ $pivot->role ?? 'user' }}">
                        {{ $roles[$pivot->role ?? 'user'] ?? ucfirst($pivot->role ?? 'user') }}
                    </span>
                    @if($pivot && $pivot->is_admin)
                        <span class="admin-badge">👑 Company Admin</span>
                    @endif
                    @if($staffMember->email_verified_at)
                        <span class="status-badge status-active">✓ Verified</span>
                    @else
                        <span class="status-badge status-pending">⏳ Pending Verification</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="details-card">
            <h2>📋 Staff Information</h2>

            <div class="detail-row">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ $staffMember->name }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $staffMember->email }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Phone</div>
                <div class="detail-value">{{ $staffMember->phone ?? 'Not provided' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Company Role</div>
                <div class="detail-value">{{ $roles[$pivot->role ?? 'user'] ?? ucfirst($pivot->role ?? 'user') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Admin Status</div>
                <div class="detail-value">{{ $pivot && $pivot->is_admin ? 'Yes - Can manage staff' : 'No' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Company</div>
                <div class="detail-value">{{ $company->name }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Joined</div>
                <div class="detail-value">{{ $pivot && $pivot->created_at ? $pivot->created_at->format('F d, Y') : 'Unknown' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Account Created</div>
                <div class="detail-value">{{ $staffMember->created_at->format('F d, Y') }}</div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="actions-card">
            <h2>⚡ Quick Actions</h2>

            <div class="action-buttons">
                <a href="{{ route('company.staff.edit', $staffMember->id) }}" class="btn btn-edit">✏️ Edit Details</a>

                @if($staffMember->id !== Auth::id())
                    <form action="{{ route('company.staff.toggle-admin', $staffMember->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @if($pivot && $pivot->is_admin)
                            <button type="submit" class="btn btn-remove-admin" onclick="return confirm('Remove admin privileges from this user?')">
                                👤 Remove Admin
                            </button>
                        @else
                            <button type="submit" class="btn btn-admin" onclick="return confirm('Grant admin privileges to this user?')">
                                👑 Make Admin
                            </button>
                        @endif
                    </form>

                    <form action="{{ route('company.staff.destroy', $staffMember->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to remove this staff member from the company?')">
                            🗑️ Remove from Company
                        </button>
                    </form>
                @else
                    <span style="color: #666; padding: 0.75rem;">You cannot modify your own admin status or remove yourself.</span>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
