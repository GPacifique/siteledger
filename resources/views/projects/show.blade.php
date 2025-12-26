<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details - SiteLedger</title>
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
            border-bottom: 2px solid #667eea;
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box.revenue {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        .stat-box.expense {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        .stat-box.profit {
            background: linear-gradient(135deg, #f39c12 0%, #d68910 100%);
        }
        .stat-box h3 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }
        .stat-box .value {
            font-size: 1.6rem;
            font-weight: 700;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 2rem;
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
        tbody tr:hover {
            background: #f5f7fa;
            cursor: pointer;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        .badge-completed {
            background: #d1ecf1;
            color: #0c5460;
        }
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #999;
            font-style: italic;
        }
        .financial-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .financial-item {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        .financial-item.revenue {
            border-left-color: #27ae60;
        }
        .financial-item.expense {
            border-left-color: #dc3545;
        }
        .financial-item.profit {
            border-left-color: #f39c12;
        }
        .financial-item h4 {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }
        .financial-item .amount {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
        }
        @media (max-width: 768px) {
            .detail-row {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .financial-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <!-- Project Information -->
        <div class="detail-card">
            <h2>📁 Project Information</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Project Name</span>
                    <span class="detail-value">{{ $project->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="badge badge-active">{{ ucfirst($project->status ?? 'Active') }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Contract Value</span>
                    <span class="detail-value">RWF {{ number_format($project->contract_value ?? 0, 2) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Client</span>
                    <span class="detail-value">{{ $project->client->name ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Start Date</span>
                    <span class="detail-value">{{ $project->start_date?->format('M d, Y') ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">End Date</span>
                    <span class="detail-value">{{ $project->end_date?->format('M d, Y') ?? 'N/A' }}</span>
                </div>
            </div>

            @if($project->description)
                <div class="detail-row" style="grid-column: 1 / -1;">
                    <div class="detail-item">
                        <span class="detail-label">Description</span>
                        <span class="detail-value">{{ $project->description }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Financial Summary -->
        <div class="detail-card">
            <h2>💰 Financial Summary</h2>
            <div class="financial-summary">
                <div class="financial-item revenue">
                    <h4>Received Amount</h4>
                    <div class="amount">RWF {{ number_format($receivedAmount ?? 0, 2) }}</div>
                </div>
                <div class="financial-item expense">
                    <h4>Remaining Amount</h4>
                    <div class="amount">RWF {{ number_format($remainingAmount ?? 0, 2) }}</div>
                </div>
                <div class="financial-item profit">
                    <h4>Net Profit</h4>
                    <div class="amount">RWF {{ number_format($profit ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Project Statistics -->
        <div class="detail-card">
            <h2>📊 Project Statistics</h2>

            <div class="stats-grid">
                <div class="stat-box">
                    <h3>Total Tasks</h3>
                    <div class="value">{{ $stats['total_tasks'] ?? 0 }}</div>
                </div>
                <div class="stat-box">
                    <h3>Completed Tasks</h3>
                    <div class="value">{{ $stats['completed_tasks'] ?? 0 }}</div>
                </div>
                <div class="stat-box">
                    <h3>Completion Rate</h3>
                    <div class="value">{{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 0) : 0 }}%</div>
                </div>
                <div class="stat-box revenue">
                    <h3>Team Members</h3>
                    <div class="value">{{ $totalWorkers ?? 0 }}</div>
                </div>
                <div class="stat-box expense">
                    <h3>Worker Costs</h3>
                    <div class="value">RWF {{ number_format($totalWorkerCost ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Project Workers -->
        <div class="detail-card">
            <h2>👥 Project Workers ({{ $totalWorkers ?? 0 }})</h2>
            @if(($workers ?? collect())->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Worker Name</th>
                            <th>Position</th>
                            <th>Email</th>
                            <th>Total Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workers as $worker)
                            <tr data-worker-id="{{ $worker->id }}">
                                <td><strong>{{ $worker->first_name ?? '' }} {{ $worker->last_name ?? '' }}</strong></td>
                                <td>{{ $worker->position ?? 'N/A' }}</td>
                                <td>{{ $worker->email ?? 'N/A' }}</td>
                                <td>RWF {{ number_format($worker->payments->sum('amount') ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">No workers assigned to this project yet</div>
            @endif
        </div>

        <!-- Project Revenues -->
        <div class="detail-card">
            <h2>💵 Project Revenues ({{ ($revenues ?? collect())->count() }})</h2>
            @if(($revenues ?? collect())->count() > 0)
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
                        @foreach($revenues as $revenue)
                            <tr data-revenue-id="{{ $revenue->id }}">
                                <td>{{ $revenue->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ $revenue->description ?? 'Revenue' }}</td>
                                <td><strong>RWF {{ number_format($revenue->amount_received ?? 0, 2) }}</strong></td>
                                <td>
                                    <span class="badge badge-completed">{{ ucfirst($revenue->status ?? 'Received') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">No revenues recorded for this project yet</div>
            @endif
        </div>

        <!-- Project Expenses -->
        <div class="detail-card">
            <h2>💸 Project Expenses ({{ ($expenses ?? collect())->count() }})</h2>
            @if(($expenses ?? collect())->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr data-expense-id="{{ $expense->id }}">
                                <td>{{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('M d, Y') : $expense->created_at->format('M d, Y') }}</td>
                                <td>{{ $expense->description ?? 'Expense' }}</td>
                                <td>{{ $expense->category ?? 'General' }}</td>
                                <td><strong>RWF {{ number_format($expense->amount ?? 0, 2) }}</strong></td>
                                <td>
                                    @php
                                        $statusClass = match($expense->status ?? 'pending') {
                                            'approved' => 'badge-completed',
                                            'completed' => 'badge-completed',
                                            default => 'badge-pending'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($expense->status ?? 'Pending') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">No expenses recorded for this project yet</div>
            @endif
        </div>
    </div>

    <script>
        // Make workers table rows clickable
        document.querySelectorAll('tbody tr[data-worker-id]').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') return;
                const workerId = this.getAttribute('data-worker-id');
                if (workerId) window.location.href = `/workers/${workerId}`;
            });
            row.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const workerId = this.getAttribute('data-worker-id');
                    if (workerId) window.location.href = `/workers/${workerId}`;
                }
            });
        });

        // Make revenues table rows clickable
        document.querySelectorAll('tbody tr[data-revenue-id]').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') return;
                const revenueId = this.getAttribute('data-revenue-id');
                if (revenueId) window.location.href = `/revenues/${revenueId}`;
            });
            row.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const revenueId = this.getAttribute('data-revenue-id');
                    if (revenueId) window.location.href = `/revenues/${revenueId}`;
                }
            });
        });

        // Make expenses table rows clickable
        document.querySelectorAll('tbody tr[data-expense-id]').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') return;
                const expenseId = this.getAttribute('data-expense-id');
                if (expenseId) window.location.href = `/expenses/${expenseId}`;
            });
            row.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const expenseId = this.getAttribute('data-expense-id');
                    if (expenseId) window.location.href = `/expenses/${expenseId}`;
                }
            });
        });
    </script>
</body>
</html>
