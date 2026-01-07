<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tenants - Super Admin - SiteLedger</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%);
            min-height: 100vh;
            color: #fff;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .navbar h1 {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .navbar-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .navbar-links a {
            color: #aaa;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-links a:hover {
            color: #667eea;
        }

        .logout-btn {
            background: #dc3545;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .breadcrumb {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #fff;
        }

        .table-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 2px solid rgba(102, 126, 234, 0.3);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tbody tr:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #aaa;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-active {
            background: #69db7c;
            color: #1a1a1a;
        }

        .badge-inactive {
            background: #ffa94d;
            color: #1a1a1a;
        }

        .pagination {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #aaa;
            text-decoration: none;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: rgba(102, 126, 234, 0.3);
            color: #667eea;
        }

        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .table-section {
                padding: 15px;
            }

            th, td {
                padding: 10px;
                font-size: 12px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <!-- Main Content -->
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a> / Tenants
        </div>

        <h2 style="display:flex;align-items:center;justify-content:space-between;gap:12px;">🏢 Manage Tenants
            <a href="{{ route('super-admin.tenants.create') }}" class="btn btn-primary" style="padding:8px 12px;border-radius:8px;">➕ Create Tenant</a>
        </h2>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Tenant Name</th>
                        <th>Users</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr>
                            <td>
                                <strong>{{ $tenant->name }}</strong>
                            </td>
                            <td>{{ $tenant->users_count ?? 0 }} user(s)</td>
                            <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-active">Active</span>
                            </td>
                            <td>
                                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #aaa;">No tenants found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($tenants->hasPages())
                <div class="pagination">
                    @if ($tenants->onFirstPage())
                        <span>← Previous</span>
                    @else
                        <a href="{{ $tenants->previousPageUrl() }}">← Previous</a>
                    @endif

                    @foreach ($tenants->getUrlRange(1, $tenants->lastPage()) as $page => $url)
                        @if ($page == $tenants->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($tenants->hasMorePages())
                        <a href="{{ $tenants->nextPageUrl() }}">Next →</a>
                    @else
                        <span>Next →</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
