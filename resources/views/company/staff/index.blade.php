<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Staff - {{ $company->name }} - SiteLedger</title>
    <!-- Colorful Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .page-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #666;
            margin: 0;
        }
        .company-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-left: 0.5rem;
        }
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .stat-card .count {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }
        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        tbody tr {
            cursor: pointer;
            transition: background 0.3s ease;
        }
        tbody tr:hover {
            background: #f0f4ff;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
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
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 0.5rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .status-active { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.4rem 0.75rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .btn-view { background: #17a2b8; color: white; }
        .btn-view:hover { background: #138496; }
        .btn-edit { background: #3498db; color: white; }
        .btn-edit:hover { background: #2980b9; }
        .btn-admin { background: #f39c12; color: white; }
        .btn-admin:hover { background: #d68910; }
        .btn-remove-admin { background: #95a5a6; color: white; }
        .btn-remove-admin:hover { background: #7f8c8d; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-delete:hover { background: #c0392b; }
        .no-data {
            text-align: center;
            padding: 3rem;
            color: #999;
            background: white;
            border-radius: 8px;
        }
        .add-button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .table-wrap { width: 100%; overflow-x: auto; }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .page-header { padding: 1rem; }
            table { font-size: 0.85rem; min-width: 800px; }
            th, td { padding: 0.75rem; }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1>👥 Company Staff <span class="company-badge">{{ $company->name }}</span></h1>
            <p>Manage staff members for your company</p>
        </div>

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

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">👥</div>
                <div class="count">{{ $staff->count() }}</div>
                <div class="label">Total Staff</div>
            </div>
            <div class="stat-card">
                <div class="icon">👑</div>
                <div class="count">{{ $staff->where('is_admin', true)->count() }}</div>
                <div class="label">Admins</div>
            </div>
            <div class="stat-card">
                <div class="icon">✅</div>
                <div class="count">{{ $staff->whereNotNull('email_verified_at')->count() }}</div>
                <div class="label">Verified</div>
            </div>
        </div>

        <div class="add-button-container">
            <a href="{{ route('company.staff.create') }}" class="btn-primary">+ Add Staff Member</a>
        </div>

        @if($staff->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $member)
                            <tr onclick="window.location='{{ route('company.staff.show', $member->id) }}'">
                                <td>
                                    <strong>{{ $member->name }}</strong>
                                    @if($member->is_admin)
                                        <span class="admin-badge">ADMIN</span>
                                    @endif
                                    @if($member->id === Auth::id())
                                        <span style="color: #667eea; font-size: 0.8rem;">(You)</span>
                                    @endif
                                </td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->phone ?? '-' }}</td>
                                <td>
                                    <span class="role-badge role-{{ $member->role }}">
                                        {{ $roles[$member->role] ?? ucfirst($member->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($member->email_verified_at)
                                        <span class="status-badge status-active">✓ Verified</span>
                                    @else
                                        <span class="status-badge status-pending">⏳ Pending</span>
                                    @endif
                                </td>
                                <td>{{ $member->joined_at ? $member->joined_at->format('M d, Y') : '-' }}</td>
                                <td onclick="event.stopPropagation()">
                                    <div class="action-buttons">
                                        <a href="{{ route('company.staff.edit', $member->id) }}" class="btn btn-edit">✏️ Edit</a>

                                        @if($member->id !== Auth::id())
                                            <form action="{{ route('company.staff.toggle-admin', $member->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @if($member->is_admin)
                                                    <button type="submit" class="btn btn-remove-admin" onclick="return confirm('Remove admin privileges?')">👤 Remove Admin</button>
                                                @else
                                                    <button type="submit" class="btn btn-admin" onclick="return confirm('Grant admin privileges?')">👑 Make Admin</button>
                                                @endif
                                            </form>

                                            <form action="{{ route('company.staff.destroy', $member->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete" onclick="return confirm('Remove this staff member from the company?')">🗑️ Remove</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <p style="font-size: 3rem; margin-bottom: 1rem;">👥</p>
                <p>No staff members found.</p>
                <p style="margin-top: 1rem;">
                    <a href="{{ route('company.staff.create') }}" class="btn-primary">Add your first staff member</a>
                </p>
            </div>
        @endif
    </div>
</body>
</html>
