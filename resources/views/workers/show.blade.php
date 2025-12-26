<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Details - SiteLedger</title>
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
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            font-size: 1.5rem;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .navbar a:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-button:hover {
            text-decoration: underline;
        }
        .detail-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .detail-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #999;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .detail-value {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            width: fit-content;
        }
        .badge.active {
            background: #d4edda;
            color: #155724;
        }
        .badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .badge.on-leave {
            background: #fff3cd;
            color: #856404;
        }
        .payment-history {
            margin-top: 2rem;
        }
        .payment-history table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-history th {
            background: #27ae60;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        .payment-history td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .payment-history tbody tr:hover {
            background: #e8f5e9;
        }
        @media (max-width: 768px) {
            .detail-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="detail-card">
            <h2>Personal Information</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">First Name</span>
                    <span class="detail-value">{{ $worker->first_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Last Name</span>
                    <span class="detail-value">{{ $worker->last_name ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $worker->email ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">{{ $worker->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h2>Employment Information</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Position</span>
                    <span class="detail-value">{{ $worker->position ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    @php
                        $statusClass = match($worker->status ?? 'active') {
                            'active' => 'active',
                            'inactive' => 'inactive',
                            'on_leave' => 'on-leave',
                            default => 'active'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($worker->status ?? 'Active') }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Daily Wages</span>
                    <span class="detail-value">RWF {{ number_format($worker->salary ?? 0, 2) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Hired Date</span>
                    <span class="detail-value">{{ $worker->hired_at ? \Carbon\Carbon::parse($worker->hired_at)->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>

            @if($worker->notes)
                <div class="detail-row">
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <span class="detail-label">Notes</span>
                        <span class="detail-value">{{ $worker->notes }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="detail-card">
            <h2>Work Statistics</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Total Tasks</span>
                    <span class="detail-value">{{ $stats['total_tasks'] ?? 0 }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Completed Tasks</span>
                    <span class="detail-value">{{ $stats['completed_tasks'] ?? 0 }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Total Wages Paid</span>
                    <span class="detail-value">RWF {{ number_format($stats['total_wages'] ?? 0, 2) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ $worker->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        @if(($recentPayments ?? collect())->count() > 0)
            <div class="detail-card">
                <h2>Recent Payments</h2>
                <div class="payment-history">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td>RWF {{ number_format($payment->amount ?? 0, 2) }}</td>
                                    <td>{{ $payment->type ?? 'Payment' }}</td>
                                    <td>{{ $payment->description ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
