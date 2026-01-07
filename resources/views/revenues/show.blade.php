<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Details - SiteLedger</title>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .detail-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .detail-card h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 0.5rem;
        }
        .detail-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 1.5rem;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .detail-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            width: fit-content;
        }
        .badge-received {
            background: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background: #fff3cd;
            color: #856404;
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
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }
        .stat-card .value {
            font-size: 1.4rem;
            font-weight: 700;
        }
        .stat-card.revenue {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        .stat-card.expense {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th {
            background: #27ae60;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        tbody tr:hover {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="detail-card">
            <h2>💵 Revenue Details</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ $income->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="badge {{ $income->status === 'received' ? 'badge-received' : 'badge-pending' }}">
                        {{ ucfirst($income->status ?? 'pending') }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Project</span>
                    <span class="detail-value">
                        @if($income->project)
                            <a href="{{ route('projects.show', $income->project) }}" style="color: #667eea; text-decoration: none;">
                                {{ $income->project->name }}
                            </a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Amount Received</span>
                    <span class="detail-value">RWF {{ number_format($income->amount_received ?? 0, 2) }}</span>
                </div>
            </div>

            @if($income->description)
                <div class="detail-row" style="grid-column: 1 / -1;">
                    <div class="detail-item">
                        <span class="detail-label">Description</span>
                        <span class="detail-value">{{ $income->description }}</span>
                    </div>
                </div>
            @endif

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Received Date</span>
                    <span class="detail-value">{{ $income->received_at ? \Carbon\Carbon::parse($income->received_at)->format('M d, Y') : 'N/A' }}</span>
                </div>
                @if($income->payment_method)
                    <div class="detail-item">
                        <span class="detail-label">Payment Method</span>
                        <span class="detail-value">{{ $income->payment_method }}</span>
                    </div>
                @endif
            </div>

            <div class="action-buttons">
                <a href="{{ route('revenues.edit', $income) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('revenues.destroy', $income) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
                <a href="{{ route('revenues.index') }}" class="btn" style="background: #95a5a6; color: white;">Back to Revenues</a>
            </div>
        </div>

        <!-- Project Details Section -->
        @if($project)
            <div class="detail-card">
                <h2>📁 Project Overview</h2>

                <!-- Project Stats -->
                @if(!empty($projectStats))
                    <div class="stat-cards">
                        <div class="stat-card revenue">
                            <h4>Total Revenue</h4>
                            <div class="value">RWF {{ number_format($projectStats['total_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="stat-card revenue">
                            <h4>Received Amount</h4>
                            <div class="value">RWF {{ number_format($projectStats['received_amount'] ?? 0, 2) }}</div>
                        </div>
                        <div class="stat-card expense">
                            <h4>Remaining Amount</h4>
                            <div class="value">RWF {{ number_format($projectStats['remaining_amount'] ?? 0, 2) }}</div>
                        </div>
                        <div class="stat-card expense">
                            <h4>Total Expenses</h4>
                            <div class="value">RWF {{ number_format($projectStats['total_expenses'] ?? 0, 2) }}</div>
                        </div>
                    </div>
                @endif

                <!-- Project Info -->
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Project Name</span>
                        <span class="detail-value">
                            <a href="{{ route('projects.show', $project) }}" style="color: #667eea; text-decoration: none;">
                                {{ $project->name }}
                            </a>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Contract Value</span>
                        <span class="detail-value">RWF {{ number_format($project->contract_value ?? 0, 2) }}</span>
                    </div>
                </div>

                @if($project->client)
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">Client</span>
                            <span class="detail-value">{{ $project->client->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value badge" style="background: #d4edda; color: #155724; width: fit-content;">
                                {{ ucfirst($project->status ?? 'Active') }}
                            </span>
                        </div>
                    </div>
                @endif

                @if($project->description)
                    <div class="detail-row" style="grid-column: 1 / -1;">
                        <div class="detail-item">
                            <span class="detail-label">Description</span>
                            <span class="detail-value">{{ $project->description }}</span>
                        </div>
                    </div>
                @endif

                <!-- Project Revenues Table -->
                @if(!empty($projectRevenues) && $projectRevenues->count() > 0)
                    <div style="margin-top: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: #333;">All Project Revenues ({{ $projectStats['revenue_count'] ?? 0 }})</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projectRevenues as $rev)
                                    <tr style="{{ $rev->id === $income->id ? 'background: #fff3cd;' : '' }}">
                                        <td>{{ $rev->created_at->format('M d, Y') }}</td>
                                        <td>{{ $rev->description ?? 'N/A' }}</td>
                                        <td><strong>RWF {{ number_format($rev->amount_received ?? 0, 2) }}</strong></td>
                                        <td>
                                            <span class="badge {{ $rev->status === 'received' ? 'badge-received' : 'badge-pending' }}">
                                                {{ ucfirst($rev->status ?? 'pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
