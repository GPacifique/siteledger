<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenues - CSMS</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2rem;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #27ae60;
        }
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }
        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #27ae60;
        }
        .table-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        .table-section h2 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #27ae60;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: white;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        tbody tr {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        tbody tr:hover {
            background: #e8f5e9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-received {
            background: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-view {
            background: #667eea;
        }
        .btn-edit {
            background: #f39c12;
        }
        .btn-delete {
            background: #e74c3c;
        }
        .btn-sm:hover {
            opacity: 0.8;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
        }
        .pagination span.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Mobile tweaks */
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .page-header { flex-direction: column; gap: 0.75rem; align-items: flex-start; }
            .btn { width: 100%; text-align: center; }
            .stat-cards { grid-template-columns: 1fr; }
            .table-section { padding: 1rem; }
            table { font-size: 0.9rem; min-width: 700px; }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1>💵 Revenues</h1>
            <a href="{{ route('revenues.create') }}" class="btn btn-primary">+ Add Revenue</a>
        </div>

        <!-- Stats -->
        <div class="stat-cards">
            <div class="stat-card" style="border-left-color: #27ae60;">
                <h3>Today's Income</h3>
                <div class="value" style="color: #27ae60;">RWF {{ number_format($revenueToday ?? 0, 2) }}</div>
                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">{{ \Carbon\Carbon::today()->format('M d, Y') }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #dc3545;">
                <h3>Today's Expenses</h3>
                <div class="value" style="color: #dc3545;">RWF {{ number_format($allExpensesToday ?? 0, 2) }}</div>
                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">{{ \Carbon\Carbon::today()->format('M d, Y') }}</div>
            </div>
            <div class="stat-card" style="border-left-color: {{ ($revenueToday ?? 0) - ($expensesToday ?? 0) >= 0 ? '#27ae60' : '#dc3545' }};">
                <h3>Today's Net</h3>
                <div class="value" style="color: {{ ($revenueToday ?? 0) - ($expensesToday ?? 0) >= 0 ? '#27ae60' : '#dc3545' }};">RWF {{ number_format(($revenueToday ?? 0) - ($expensesToday ?? 0), 2) }}</div>
                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">{{ ($revenueToday ?? 0) - ($expensesToday ?? 0) >= 0 ? 'Profit' : 'Loss' }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="value">RWF {{ number_format($totalRevenue ?? 0, 2) }}</div>
                <div style="font-size: 0.85rem; color: #27ae60; margin-top: 0.25rem;">+{{ number_format($revenueThisMonth ?? 0, 2) }} this month</div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-section">
            <h2>Revenue Records</h2>
            @if($revenues->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenues as $revenue)
                            <tr onclick="window.location.href='{{ route('revenues.show', $revenue) }}';" style="cursor: pointer;">
                                <td>{{ $revenue->created_at->format('M d, Y') }}</td>
                                <td>{{ $revenue->project?->name ?? 'N/A' }}</td>
                                <td>{{ $revenue->description ?? 'N/A' }}</td>
                                <td><strong>RWF {{ number_format($revenue->amount_received ?? 0, 2) }}</strong></td>
                                <td>
                                    <span class="badge {{ $revenue->status === 'received' ? 'badge-received' : 'badge-pending' }}">
                                        {{ ucfirst($revenue->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons" onclick="event.stopPropagation();">
                                        <a href="{{ route('revenues.show', $revenue) }}" class="btn-sm btn-view">View</a>
                                        <a href="{{ route('revenues.edit', $revenue) }}" class="btn-sm btn-edit">Edit</a>
                                        <form action="{{ route('revenues.destroy', $revenue) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $revenues->links() }}
                </div>
            @else
                <div class="empty-state">
                    <p>No revenues recorded yet</p>
                    <a href="{{ route('revenues.create') }}" class="btn btn-primary">Add Your First Revenue</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
