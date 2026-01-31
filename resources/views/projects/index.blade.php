<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - SiteLedger</title>
    <style>
        :root{--bg:#f6f7fb;--card:#fff;--accent:#667eea;--accent-2:#27ae60;--muted:#666}
        *{box-sizing:border-box}
        body{font-family:Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:#222}
        .wrap{max-width:1200px;margin:24px auto;padding:20px}
        header.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
        header h1{font-size:1.4rem;color:#111}
        .actions{display:flex;gap:8px}
        .btn{display:inline-block;padding:10px 14px;border-radius:8px;text-decoration:none;color:#fff;font-weight:600}
        .btn-primary{background:linear-gradient(135deg,var(--accent),#764ba2)}
        .btn-ghost{background:transparent;color:var(--accent);border:1px solid #e6e9f2;font-weight:600;padding:8px 12px}
        .card{background:var(--card);padding:14px;border-radius:10px;box-shadow:0 6px 18px rgba(47,57,72,0.06)}
        .table-wrap{overflow:auto;margin-top:14px}
        table{width:100%;border-collapse:collapse;min-width:700px}
        thead th{background:#f0f3ff;color:#123;padding:12px 14px;text-align:left;font-weight:700}
        tbody td{padding:12px 14px;border-top:1px solid #f1f3f6}
        tr.row-link{cursor:pointer}
        .muted{color:var(--muted)}
        .status{display:inline-block;padding:6px 10px;border-radius:999px;font-weight:700;font-size:0.85rem}
        .status.active{background:#e6f7ee;color:var(--accent-2)}
        .status.completed{background:#eaf2ff;color:#2d5bd7}
        .status.planning{background:#fff8e6;color:#b97706}
        @media (max-width:760px){.wrap{padding:12px}.table-wrap{min-width:unset}table{font-size:13px}}
    </style>
</head>
<body>
@include('components.navbar')

<div class="wrap">
    <header class="page-header">
        <div>
            <h1>Projects</h1>
            <div class="muted">All projects and high-level financials</div>
        </div>
        <div class="actions">
            <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New Project</a>
        </div>
    </header>

    <div class="card">
        @if($projects->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Client</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th style="text-align:right">Contract</th>
                            <th style="text-align:right">Received</th>
                            <th style="text-align:right">Spent</th>
                            <th style="text-align:right">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            @php
                                $totalSpent = ($project->total_expenses ?? 0) + ($project->total_payments ?? 0);
                                $profit = $project->profit ?? (($project->contract_value ?? 0) - $totalSpent);
                            @endphp
                            <tr class="row-link" onclick="location.href='{{ route('projects.show', $project->id) }}'">
                                <td><strong>{{ $project->name }}</strong><div class="muted" style="font-size:0.85rem">{{ $project->project_code ?? '' }}</div></td>
                                <td>{{ $project->client->name ?? '—' }}</td>
                                <td>{{ $project->manager ? $project->manager->first_name . ' ' . $project->manager->last_name : '—' }}</td>
                                <td>
                                    @php $st = $project->status ?? 'planning'; @endphp
                                    <span class="status {{ $st }}">{{ ucfirst($st) }}</span>
                                </td>
                                <td style="text-align:right">RWF {{ number_format($project->contract_value ?? 0, 0) }}</td>
                                <td style="text-align:right;color:var(--accent-2);font-weight:700">RWF {{ number_format($project->total_received ?? 0, 0) }}</td>
                                <td style="text-align:right;color:#d04545;font-weight:700">RWF {{ number_format($totalSpent, 0) }}</td>
                                <td style="text-align:right;color:{{ $profit >=0 ? 'var(--accent-2)' : '#d04545' }};font-weight:800">RWF {{ number_format($profit, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding:40px;text-align:center;color:var(--muted)">
                <h3>No projects yet</h3>
                <p>Create your first project to start tracking.</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
            </div>
        @endif
    </div>
</div>
</body>
</html>
