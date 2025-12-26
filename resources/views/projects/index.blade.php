<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - SiteLedger</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #666;
            font-size: 1rem;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        thead {
            background: #27ae60;
            color: white;
        }
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        tbody tr {
            cursor: pointer;
            transition: background 0.2s;
        }
        tbody tr:hover {
            background: #e8f5e9;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        .empty-message {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1>📁 Projects</h1>
            <p>Manage all projects and their status</p>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
            <a href="{{ route('projects.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">+ Add Project</a>
        </div>

        @if($projects->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Contract Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                        <tr onclick="window.location.href='/projects/{{ $project->id }}';">
                            <td><strong>{{ $project->name }}</strong></td>
                            <td>{{ $project->client->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = match($project->status) {
                                        'active', 'in_progress' => 'badge-active',
                                        'completed' => 'badge-inactive',
                                        default => 'badge-pending'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($project->status) }}</span>
                            </td>
                            <td>RWF {{ number_format($project->contract_value ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">
                <p>No projects found. <a href="/admin/dashboard">Go to Dashboard</a></p>
            </div>
        @endif
    </div>
</body>
</html>
