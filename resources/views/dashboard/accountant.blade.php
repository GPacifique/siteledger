<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard - SiteLedger</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; color: #333; }

        .navbar {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 { font-size: 1.5rem; }
        .navbar .user-info { display: flex; align-items: center; gap: 1rem; }
        .navbar .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .navbar button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .navbar button:hover { background: rgba(255,255,255,0.3); }

        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }

        .welcome-banner {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome-banner h2 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .welcome-banner p { opacity: 0.9; }
        .welcome-banner .financial-summary { text-align: right; }
        .welcome-banner .balance-label { font-size: 0.9rem; opacity: 0.9; }
        .welcome-banner .balance-value { font-size: 2.5rem; font-weight: 700; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #27ae60;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(39, 174, 96, 0.2);
        }
        .stat-card h3 { color: #666; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
        .stat-card .value { font-size: 1.75rem; font-weight: 700; color: #27ae60; }
        .stat-card .change { font-size: 0.85rem; color: #27ae60; margin-top: 0.25rem; }
        .stat-card.income { border-left-color: #27ae60; }
        .stat-card.income .value { color: #27ae60; }
        .stat-card.expense { border-left-color: #e74c3c; }
        .stat-card.expense .value { color: #e74c3c; }
        .stat-card.payment { border-left-color: #3498db; }
        .stat-card.payment .value { color: #3498db; }
        .stat-card.transaction { border-left-color: #9b59b6; }
        .stat-card.transaction .value { color: #9b59b6; }

        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        .quick-actions h2 { font-size: 1.3rem; color: #333; margin-bottom: 1.5rem; }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .action-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem 1rem;
            background: linear-gradient(135deg, #f0fff4 0%, #fff 100%);
            border: 2px solid #d4edda;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .action-link:hover {
            border-color: #27ae60;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            transform: translateY(-2px);
        }
        .action-link .icon { font-size: 1.75rem; margin-bottom: 0.5rem; }
        .action-link .label { font-weight: 600; font-size: 0.9rem; text-align: center; }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .panel.full-width {
            grid-column: 1 / -1;
        }
        .panel-header {
            background: linear-gradient(135deg, #f0fff4 0%, #fff 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #d4edda;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .panel-header h3 { font-size: 1.1rem; color: #333; }
        .panel-header a { color: #27ae60; text-decoration: none; font-size: 0.9rem; }
        .panel-header a:hover { text-decoration: underline; }
        .panel-body { padding: 1.5rem; }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .transaction-item:last-child { border-bottom: none; }
        .transaction-info .title { font-weight: 600; color: #333; }
        .transaction-info .meta { font-size: 0.85rem; color: #666; margin-top: 0.25rem; }
        .transaction-amount { font-weight: 700; font-size: 1.1rem; }
        .transaction-amount.income { color: #27ae60; }
        .transaction-amount.expense { color: #e74c3c; }
        .transaction-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        .status-completed { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #cce5ff; color: #004085; }

        .chart-container {
            height: 280px;
            padding: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-row .label { color: #666; }
        .summary-row .value { font-weight: 700; }
        .summary-row .value.positive { color: #27ae60; }
        .summary-row .value.negative { color: #e74c3c; }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .empty-state .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

        @media (max-width: 1024px) {
            .content-grid { grid-template-columns: 1fr; }
            .panel.full-width { grid-column: 1; }
        }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .welcome-banner { flex-direction: column; text-align: center; }
            .welcome-banner .financial-summary { margin-top: 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>💰 SiteLedger</h1>
        <div class="user-info">
            <span class="role-badge">Accountant</span>
            <span>{{ auth()->user()->name }}</span>
            @if(isset($company))
                <span style="opacity: 0.8;">| {{ $company->name }}</span>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-banner">
            <div>
                @if(isset($company))
                    <h2>🏢 {{ $company->name }}</h2>
                    <p style="font-size: 1.2rem; margin-bottom: 0.25rem;">Financial Dashboard</p>
                    <p>Complete financial overview for {{ now()->format('F Y') }}</p>
                @else
                    <h2>Financial Dashboard</h2>
                    <p>Complete financial overview for {{ now()->format('F Y') }}</p>
                    <div style="background: rgba(255,255,255,0.2); padding: 0.75rem 1rem; border-radius: 8px; margin-top: 0.75rem; font-size: 0.9rem;">
                        ⚠️ <strong>No Company Assigned</strong> - Contact your administrator to be assigned to a company.
                    </div>
                @endif
            </div>
            @if(isset($company))
            <div class="financial-summary">
                <div class="balance-label">Net Balance (This Month)</div>
                @php
                    $netBalance = ($financialSummary['totalIncome'] ?? 0) - ($financialSummary['totalExpenses'] ?? 0);
                @endphp
                <div class="balance-value">RWF {{ number_format($netBalance, 0) }}</div>
            </div>
            @endif
        </div>

        @if(isset($company))
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card income">
                <h3>Company Income</h3>
                <div class="value">RWF {{ number_format($financialSummary['totalIncome'] ?? 0, 0) }}</div>
                <div class="change">+RWF {{ number_format($financialSummary['incomeThisMonth'] ?? 0, 0) }} this month</div>
            </div>
            <div class="stat-card expense">
                <h3>Company Expenses</h3>
                <div class="value">RWF {{ number_format($financialSummary['totalExpenses'] ?? 0, 0) }}</div>
                <div class="change" style="color: #e74c3c;">-RWF {{ number_format($financialSummary['expensesThisMonth'] ?? 0, 0) }} this month</div>
            </div>
            <div class="stat-card payment">
                <h3>Company Payments</h3>
                <div class="value">RWF {{ number_format($financialSummary['totalPayments'] ?? 0, 0) }}</div>
                <div class="change">{{ $quickStats['paymentsCount'] ?? 0 }} transactions</div>
            </div>
            <div class="stat-card transaction">
                <h3>Pending Approval</h3>
                <div class="value">{{ $quickStats['pendingApprovals'] ?? 0 }}</div>
                <div class="change">Requires review</div>
            </div>
            <div class="stat-card">
                <h3>Outstanding</h3>
                <div class="value">RWF {{ number_format(is_array($outstandingReceivables) ? ($outstandingReceivables['total_outstanding'] ?? 0) : ($outstandingReceivables ?? 0), 0) }}</div>
                <div class="change">Receivables</div>
            </div>
        </div>
        @else
        <div class="stats-grid">
            <div class="stat-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💰</div>
                <h3 style="font-size: 1.2rem; color: #666;">No Company Financial Data Available</h3>
                <p style="color: #999; margin-top: 0.5rem;">You need to be assigned to a company to view financial data.</p>
            </div>
        </div>
        @endif

        @if(isset($company))

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('revenues.create') }}" class="action-link">
                    <span class="icon">📥</span>
                    <span class="label">Record Income</span>
                </a>
                <a href="{{ route('expenses.create') }}" class="action-link">
                    <span class="icon">📤</span>
                    <span class="label">Record Expense</span>
                </a>
                <a href="{{ route('payments.create') }}" class="action-link">
                    <span class="icon">💳</span>
                    <span class="label">Make Payment</span>
                </a>
                <a href="{{ route('revenues.index') }}" class="action-link">
                    <span class="icon">📊</span>
                    <span class="label">View Income</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="action-link">
                    <span class="icon">📋</span>
                    <span class="label">View Expenses</span>
                </a>
                <a href="{{ route('payments.index') }}" class="action-link">
                    <span class="icon">💰</span>
                    <span class="label">All Payments</span>
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Cash Flow Chart -->
            <div class="panel full-width">
                <div class="panel-header">
                    <h3>📈 Cash Flow Analysis</h3>
                </div>
                <div class="chart-container">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>

            <!-- Recent Income -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📥 Recent Income</h3>
                    <a href="{{ route('revenues.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentIncomes ?? [] as $income)
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="title">{{ $income->description ?? $income->source ?? 'Income' }}</div>
                                <div class="meta">{{ $income->received_at ? $income->received_at->format('M j, Y') : $income->created_at->format('M j, Y') }}</div>
                            </div>
                            <span class="transaction-amount income">+RWF {{ number_format($income->amount_received ?? $income->amount, 0) }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">📥</div>
                            <p>No income recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📤 Recent Expenses</h3>
                    <a href="{{ route('expenses.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($recentExpenses ?? [] as $expense)
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="title">{{ $expense->description ?? $expense->category }}</div>
                                <div class="meta">{{ $expense->created_at->format('M j, Y') }} • {{ ucfirst($expense->category ?? 'General') }}</div>
                            </div>
                            <span class="transaction-amount expense">-RWF {{ number_format($expense->amount, 0) }}</span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">📤</div>
                            <p>No expenses recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="panel">
                <div class="panel-header">
                    <h3>💳 Recent Payments</h3>
                    <a href="{{ route('payments.index') }}">View all →</a>
                </div>
                <div class="panel-body">
                    @forelse($companyPayments ?? [] as $payment)
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="title">{{ $payment->employee->name ?? 'Payment' }}</div>
                                <div class="meta">{{ $payment->created_at->format('M j, Y') }}</div>
                            </div>
                            <div>
                                <span class="transaction-amount expense">-RWF {{ number_format($payment->amount, 0) }}</span>
                                <span class="transaction-status status-{{ strtolower($payment->status ?? 'completed') }}">
                                    {{ ucfirst($payment->status ?? 'Completed') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="icon">💳</div>
                            <p>No payments recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="panel">
                <div class="panel-header">
                    <h3>📊 Financial Summary</h3>
                </div>
                <div class="panel-body">
                    <div class="summary-row">
                        <span class="label">Total Revenue (All Time)</span>
                        <span class="value positive">RWF {{ number_format($financialSummary['totalIncome'] ?? 0, 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Total Expenses (All Time)</span>
                        <span class="value negative">RWF {{ number_format($financialSummary['totalExpenses'] ?? 0, 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Worker Payments</span>
                        <span class="value negative">RWF {{ number_format($financialSummary['totalPayments'] ?? 0, 0) }}</span>
                    </div>
                    <div class="summary-row" style="border-top: 2px solid #27ae60; padding-top: 1rem; margin-top: 0.5rem;">
                        <span class="label" style="font-weight: 600;">Net Profit/Loss</span>
                        @php
                            $netProfit = ($financialSummary['totalIncome'] ?? 0) - ($financialSummary['totalExpenses'] ?? 0) - ($financialSummary['totalPayments'] ?? 0);
                        @endphp
                        <span class="value {{ $netProfit >= 0 ? 'positive' : 'negative' }}">
                            RWF {{ number_format(abs($netProfit), 0) }}
                            {{ $netProfit < 0 ? '(Loss)' : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if(isset($company))
    <script>
        // Cash Flow Chart
        const ctx = document.getElementById('cashFlowChart').getContext('2d');
        const cashFlowData = {!! json_encode($cashFlowAnalysis ?? []) !!};

        const labels = cashFlowData.map(item => item.month || 'N/A');
        const incomeData = cashFlowData.map(item => item.income || 0);
        const expenseData = cashFlowData.map(item => item.expenses || 0);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Income',
                        data: incomeData.length ? incomeData : [0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(39, 174, 96, 0.8)',
                        borderColor: '#27ae60',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: expenseData.length ? expenseData : [0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(231, 76, 60, 0.8)',
                        borderColor: '#e74c3c',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RWF ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endif
</body>
</html>
