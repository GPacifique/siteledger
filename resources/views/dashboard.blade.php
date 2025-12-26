<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SiteLedger</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
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
        .navbar button:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .stats-grid {
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
            border-left: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .stat-card:hover::before {
            left: 100%;
        }
        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
            transform: translateY(-4px);
            background: linear-gradient(135deg, #f8f9ff 0%, white 100%);
        }
        .stat-card:active {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-card .change {
            font-size: 0.85rem;
            color: #999;
        }
        .stat-card .change.positive {
            color: #27ae60;
        }
        .stat-card .change.negative {
            color: #e74c3c;
        }
        .chart-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .chart-section h2 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 2rem;
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
            border-bottom: 2px solid #229954;
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
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge.success {
            background: #d4edda;
            color: #155724;
        }
        .badge.danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge.warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge.info {
            background: #d1ecf1;
            color: #0c5460;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .two-column {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #999;
        }
        .empty-state p {
            font-size: 0.95rem;
        }
    </style>
    <script>
        // Enhanced card interaction functionality
        document.addEventListener('DOMContentLoaded', function() {
            const cardLinks = {
                'Total Income': '/revenues',
                'Total Expenses': '/expenses',
                'Total Projects': '/projects',
                'Active Clients': '/clients',
                'Total Workforce': '/workers',
                'Total Payments': '/payments',
                'Office Expenses': '/expenses',
                'Project Expenses': '/expenses'
            };

            document.querySelectorAll('.stat-card').forEach(card => {
                const title = card.querySelector('h3');
                const href = title && cardLinks[title.textContent.trim()];

                if (href) {
                    // Add pointer cursor
                    card.style.cursor = 'pointer';

                    // Click handler with ripple effect
                    card.addEventListener('click', function(e) {
                        // Create ripple effect
                        const ripple = document.createElement('span');
                        const rect = card.getBoundingClientRect();
                        const size = Math.max(rect.width, rect.height);
                        const x = e.clientX - rect.left - size / 2;
                        const y = e.clientY - rect.top - size / 2;

                        ripple.style.cssText = `
                            position: absolute;
                            left: ${x}px;
                            top: ${y}px;
                            width: ${size}px;
                            height: ${size}px;
                            background: rgba(102, 126, 234, 0.5);
                            border-radius: 50%;
                            transform: scale(0);
                            animation: ripple 0.6s ease-out;
                            pointer-events: none;
                        `;

                        card.style.position = 'relative';
                        card.style.overflow = 'hidden';
                        card.appendChild(ripple);

                        // Navigate after animation
                        setTimeout(() => {
                            window.location.href = href;
                        }, 300);
                    });

                    // Keyboard support (Enter key)
                    card.setAttribute('tabindex', '0');
                    card.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            card.click();
                        }
                    });

                    // Visual feedback on focus
                    card.addEventListener('focus', function() {
                        this.style.outline = '2px solid #667eea';
                        this.style.outlineOffset = '2px';
                    });

                    card.addEventListener('blur', function() {
                        this.style.outline = 'none';
                    });
                }
            });
        });
    </script>

    <style>
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Income</h3>
                <div class="value">RWF {{ number_format($incomesTotal ?? 0, 2) }}</div>
                <div class="change positive">+{{ $incomesThisMonth ?? 0 }} this month</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <h3 style="color: white;">Total Expenses</h3>
                <div class="value" style="color: white;">RWF {{ number_format($expensesTotal ?? 0, 2) }}</div>
                <div class="change" style="color: rgba(255, 255, 255, 0.8);">{{ $expensesThisMonth ?? 0 }} this month</div>
            </div>
            <div class="stat-card">
                <h3>Total Projects</h3>
                <div class="value">{{ $projectsCount ?? 0 }}</div>
                <div class="change">+{{ $projectsThisMonth ?? 0 }} this month</div>
            </div>
            <div class="stat-card">
                <h3>Active Clients</h3>
                <div class="value">{{ $activeClients ?? 0 }}</div>
                <div class="change">{{ $totalClients ?? 0 }} total</div>
            </div>
            <div class="stat-card">
                <h3>Total Workforce</h3>
                <div class="value">{{ $totalWorkforce ?? 0 }}</div>
                <div class="change">{{ $activeWorkers ?? 0 }} active</div>
            </div>
            <div class="stat-card">
                <h3>Total Payments</h3>
                <div class="value">RWF {{ number_format($paymentsTotal ?? 0, 2) }}</div>
                <div class="change">{{ $paymentsThisMonth ?? 0 }} this month</div>
            </div>
        </div>

        <!-- Expense Breakdown -->
        <div class="stats-grid">
            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <h3 style="color: white;">Office Expenses</h3>
                <div class="value" style="color: white;">RWF {{ number_format($officeExpenses ?? 0, 2) }}</div>
                <div class="change" style="color: rgba(255, 255, 255, 0.8);">{{ $officeExpensesThisMonth ?? 0 }} this month</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <h3 style="color: white;">Project Expenses</h3>
                <div class="value" style="color: white;">RWF {{ number_format($projectExpenses ?? 0, 2) }}</div>
                <div class="change" style="color: rgba(255, 255, 255, 0.8);">{{ $projectExpensesThisMonth ?? 0 }} this month</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="two-column">
            <div class="chart-section">
                <h2>Monthly Income vs Expenses</h2>
                <div class="chart-container">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
            </div>
            <div class="chart-section">
                <h2>Monthly Payments</h2>
                <div class="chart-container">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daily Stats Chart -->
        <div class="chart-section">
            <h2>Last 30 Days - Daily Revenue vs Expenses</h2>
            <div class="chart-container">
                <canvas id="dailyStatsChart"></canvas>
            </div>
        </div>

        <!-- Expense Breakdown Chart -->
        <div class="chart-section">
            <h2>Expense Breakdown by Category</h2>
            <div class="chart-container" style="max-width: 500px;">
                <canvas id="expenseBreakdownChart"></canvas>
            </div>
        </div>

        <!-- Recent Data Tables -->
        <div class="two-column">
            <div class="table-section">
                <h2>Recent Projects</h2>
                @if(($recentProjects ?? collect())->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProjects as $project)
                                <tr onclick="window.location.href='/projects/{{ $project->id }}';" style="cursor: pointer;">
                                    <td>{{ $project->name ?? 'N/A' }}</td>
                                    <td>RWF {{ number_format($project->contract_value ?? 0, 2) }}</td>
                                    <td><span class="badge info">Active</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <p>No projects yet</p>
                    </div>
                @endif
            </div>

            <div class="table-section">
                <h2>Recent Workers</h2>
                @if(($recentWorkers ?? collect())->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Daily Wages</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentWorkers as $worker)
                                <tr onclick="window.location.href='/workers/{{ $worker->id }}';" style="cursor: pointer;">
                                    <td>{{ ($worker->first_name ?? '') . ' ' . ($worker->last_name ?? '') }}</td>
                                    <td>{{ $worker->position ?? 'N/A' }}</td>
                                    <td>RWF {{ number_format($worker->salary ?? 0, 2) }}</td>
                                    <td><span class="badge success">{{ $worker->status ?? 'Active' }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <p>No workers yet</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="table-section">
            <h2>Recent Payments</h2>
            @if(($recentPayments ?? collect())->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $payment)
                            <tr onclick="window.location.href='/payments/{{ $payment->id }}';" style="cursor: pointer;">
                                <td>{{ $payment->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td>RWF {{ number_format($payment->amount ?? 0, 2) }}</td>
                                <td>{{ $payment->type ?? 'Transfer' }}</td>
                                <td><span class="badge success">Completed</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <p>No payments yet</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Income vs Expenses Chart
        const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
        new Chart(incomeExpenseCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months ?? []) !!},
                datasets: [
                    {
                        label: 'Income',
                        data: {!! json_encode($incomeMonthly ?? []) !!},
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($expensesMonthly ?? []) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
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

        // Payments Chart
        const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
        new Chart(paymentsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months ?? []) !!},
                datasets: [
                    {
                        label: 'Payments',
                        data: {!! json_encode($paymentsMonthly ?? []) !!},
                        backgroundColor: '#667eea',
                        borderRadius: 4,
                        borderWidth: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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

        // Daily Stats Chart
        const dailyStatsCtx = document.getElementById('dailyStatsChart').getContext('2d');
        new Chart(dailyStatsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyDates ?? []) !!},
                datasets: [
                    {
                        label: 'Daily Revenue',
                        data: {!! json_encode($dailyRevenue ?? []) !!},
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#27ae60',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                    {
                        label: 'Daily Expenses',
                        data: {!! json_encode($dailyExpenses ?? []) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
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

        // Expense Breakdown Chart
        const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
        const officeExpenses = {{ $officeExpenses ?? 0 }};
        const projectExpenses = {{ $projectExpenses ?? 0 }};

        new Chart(expenseBreakdownCtx, {
            type: 'doughnut',
            data: {
                labels: ['Office Expenses', 'Project Expenses'],
                datasets: [{
                    data: [officeExpenses, projectExpenses],
                    backgroundColor: ['#dc3545', '#f39c12'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'RWF ' + context.parsed.toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Make table rows clickable
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                // Get the first cell content (ID or name)
                const firstCell = this.querySelector('td');
                if (firstCell) {
                    console.log('Clicked row:', firstCell.textContent);
                    // Add active state styling
                    this.style.backgroundColor = '#c8e6c9';
                    setTimeout(() => {
                        this.style.backgroundColor = '#e8f5e9';
                    }, 200);
                }
            });
        });
    </script>
</body>
</html>
