<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - CSMS</title>
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
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #666;
            font-size: 1rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        .stat-card.completed {
            border-left-color: #27ae60;
        }
        .stat-card.pending {
            border-left-color: #f39c12;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-card.completed .stat-value {
            color: #27ae60;
        }
        .stat-card.pending .stat-value {
            color: #f39c12;
        }
        .stat-label {
            color: #999;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .search-box {
            flex: 1;
            min-width: 250px;
        }
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        .btn {
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
        .table-wrapper {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        tbody tr {
            cursor: pointer;
            transition: background 0.2s;
        }
        tbody tr:hover {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-completed {
            background: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        .badge-method {
            background: #e7f3ff;
            color: #0066cc;
            display: inline-block;
            padding: 0.2rem 0.6rem;
        }
        .empty-message {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        .payment-link {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }
        .payment-link:hover {
            text-decoration: underline;
        }
        .category-section {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .category-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .category-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #667eea;
        }
        .category-card h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        .category-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .category-stat:last-child {
            border-bottom: none;
        }
        .category-stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .category-stat-value {
            font-weight: 600;
            color: #667eea;
        }
        .category-total {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
            text-align: center;
        }
        .category-total-amount {
            font-size: 1.4rem;
            font-weight: bold;
            color: #667eea;
        }
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                min-width: auto;
            }
            .category-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1>💳 Payments</h1>
            <p>Manage and track all payments</p>
        </div>

        <!-- Payments History Navigation -->
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1rem;">
            <a href="{{ route('payments.index', ['period' => 'today']) }}" class="btn" style="background:#eef2ff; color:#4f46e5;">📆 Today</a>
            <a href="{{ route('payments.index', ['period' => 'month']) }}" class="btn" style="background:#e9f7ef; color:#1e7e34;">📅 This Month</a>
            <a href="{{ route('payments.index', ['period' => 'year']) }}" class="btn" style="background:#fff3cd; color:#856404;">📈 This Year</a>
            <a href="{{ route('payments.index') }}" class="btn" style="background:#f3f4f6; color:#333;">🔁 All Time</a>
        </div>

        <!-- Statistics -->
        @php
            $totalPayments = $payments->sum('amount');
        @endphp

        <div class="stats-grid">
            <!-- History Totals with Links -->
            <a href="{{ route('payments.index') }}" class="stat-card" style="text-decoration:none; color:inherit;">
                <div class="stat-value">RWF {{ number_format($paymentsTotalAll ?? $totalPayments, 0) }}</div>
                <div class="stat-label">All Time Total</div>
            </a>
            <a href="{{ route('payments.index', ['period' => 'month']) }}" class="stat-card" style="text-decoration:none; color:inherit;">
                <div class="stat-value">RWF {{ number_format($paymentsTotalMonth ?? 0, 0) }}</div>
                <div class="stat-label">This Month</div>
            </a>
            <a href="{{ route('payments.index', ['period' => 'today']) }}" class="stat-card" style="text-decoration:none; color:inherit;">
                <div class="stat-value">RWF {{ number_format($paymentsTotalToday ?? 0, 0) }}</div>
                <div class="stat-label">Today</div>
            </a>
            <a href="{{ route('payments.index', ['period' => 'year']) }}" class="stat-card" style="text-decoration:none; color:inherit;">
                <div class="stat-value">RWF {{ number_format($paymentsTotalYear ?? 0, 0) }}</div>
                <div class="stat-label">This Year</div>
            </a>
            <div class="stat-card completed">
                <div class="stat-value">{{ $payments->count() }}</div>
                <div class="stat-label">Payments {{ $period ? '(' . ucfirst($period) . ')' : '' }}</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-bar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by worker name, reference, or amount..." onkeyup="filterTable()">
            </div>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">+ Add Payment</a>
        </div>

        <!-- Worker Categories Section -->
        @php
            // Group payments by worker position/role
            $paymentsByCategory = $payments->groupBy(function($payment) {
                return $payment->employee?->position ?? 'Unassigned';
            })->sortByDesc(function($group) {
                return $group->sum('amount');
            });
        @endphp

        @if($payments->count() > 0)
            <div class="category-section">
                <h2 class="category-title">📊 Payments by Worker Category</h2>
                <div class="category-grid">
                    @foreach($paymentsByCategory as $position => $categoryPayments)
                        @php
                            $categoryTotal = $categoryPayments->sum('amount');
                            $categoryCount = $categoryPayments->count();
                            $categoryWorkers = $categoryPayments->pluck('employee')->unique('id')->count();
                        @endphp
                        <div class="category-card">
                            <h3>👷 {{ ucfirst($position) }}</h3>
                            <div class="category-stat">
                                <span class="category-stat-label">Workers:</span>
                                <span class="category-stat-value">{{ $categoryWorkers }}</span>
                            </div>
                            <div class="category-stat">
                                <span class="category-stat-label">Payments:</span>
                                <span class="category-stat-value">{{ $categoryCount }}</span>
                            </div>
                            <div class="category-total">
                                <div style="color: #999; font-size: 0.85rem; margin-bottom: 0.25rem;">Total Paid</div>
                                <div class="category-total-amount">RWF {{ number_format($categoryTotal, 0) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Payments Table -->
        @if($payments->count() > 0)
            <div class="table-wrapper">
                <table id="paymentsTable">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Project</th>
                            <th>Phase</th>
                            <th>Worker</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Recorded By</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr class="payment-row" data-id="{{ $payment->id }}">
                                <td><strong>{{ $payment->reference ?? '#' . $payment->id }}</strong></td>
                                <td>
                                    @if($payment->project)
                                        <span style="color: #27ae60; font-weight: 600;">🏗️ {{ $payment->project->name }}</span>
                                    @else
                                        <span style="color: #999;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->phase)
                                        @if($payment->phase == 'design')
                                            <span class="badge" style="background: #e8daef; color: #6c5ce7;">📝 Design</span>
                                        @else
                                            <span class="badge" style="background: #fef9e7; color: #d68910;">🔨 Execution</span>
                                        @endif
                                    @else
                                        <span style="color: #999;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->employee)
                                        {{ $payment->employee->first_name }} {{ $payment->employee->last_name }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td><strong>RWF {{ number_format($payment->amount ?? 0, 0) }}</strong></td>
                                <td>
                                    @if($payment->method)
                                        <span class="badge-method">{{ ucfirst($payment->method) }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $payment->user->name ?? '—' }}</td>
                                <td>{{ $payment->created_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('payments.show', $payment->id) }}" class="payment-link">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-message">
                <p>📋 No payments found yet.</p>
                <p><a href="{{ route('payments.create') }}" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">Create First Payment</a></p>
            </div>
        @endif
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const tableRows = document.querySelectorAll('#paymentsTable tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchInput) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
