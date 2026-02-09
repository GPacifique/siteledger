<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiteLedger</title>

    <!-- Colorful Theme CSS - For consistent vibrant colors -->
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">

    <!-- Modern Design System -->
    <link rel="stylesheet" href="{{ asset('css/modern.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="page-wrapper">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Main Dashboard Content -->
    <main class="page-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-top">
                    <div>
                        <h1 class="page-title">Dashboard</h1>
                        <p class="page-subtitle">Welcome back, {{ auth()->user()->name }}! Here's your overview.</p>
                    </div>
                    <div class="page-actions">
                        <a href="{{ route('projects.create') }}" class="btn btn-primary">
                            <span>➕</span>
                            <span>New Project</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-lg mb-2xl animate-fade-in-up">
                <!-- Total Projects Card -->
                <div class="stat-card primary">
                    <div class="stat-card-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                        🏗️
                    </div>
                    <div class="stat-card-label">Total Projects</div>
                    <div class="stat-card-value">{{ $totalProjects ?? 0 }}</div>
                    <div class="stat-card-trend positive">
                        <span>↗</span>
                        <span>12% from last month</span>
                    </div>
                </div>

                <!-- Active Projects Card -->
                <div class="stat-card success">
                    <div class="stat-card-icon" style="background: var(--color-secondary-50); color: var(--color-secondary-600);">
                        ✅
                    </div>
                    <div class="stat-card-label">Active Projects</div>
                    <div class="stat-card-value">{{ $activeProjects ?? 0 }}</div>
                    <div class="stat-card-trend positive">
                        <span>↗</span>
                        <span>5 new this week</span>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="stat-card info">
                    <div class="stat-card-icon" style="background: var(--color-info-light); color: var(--color-info-dark);">
                        💰
                    </div>
                    <div class="stat-card-label">Total Revenue</div>
                    <div class="stat-card-value">RWF {{ number_format($totalRevenue ?? 0, 0) }}</div>
                    <div class="stat-card-trend positive">
                        <span>↗</span>
                        <span>18% increase</span>
                    </div>
                </div>

                <!-- Total Expenses Card -->
                <div class="stat-card warning">
                    <div class="stat-card-icon" style="background: var(--color-warning-light); color: var(--color-warning-dark);">
                        💸
                    </div>
                    <div class="stat-card-label">Total Expenses</div>
                    <div class="stat-card-value">RWF {{ number_format($totalExpenses ?? 0, 0) }}</div>
                    <div class="stat-card-trend negative">
                        <span>↗</span>
                        <span>8% from last month</span>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout for Charts and Recent Activity -->
            <div class="grid grid-cols-2 gap-xl mb-2xl">
                <!-- Revenue Chart Card -->
                <div class="card animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card-header">
                        <h3 class="text-xl font-semibold">Revenue Overview</h3>
                        <p class="text-sm text-secondary">Monthly revenue trends</p>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Expense Breakdown Card -->
                <div class="card animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="card-header">
                        <h3 class="text-xl font-semibold">Expense Breakdown</h3>
                        <p class="text-sm text-secondary">By category</p>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Projects Table -->
            <div class="card animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-semibold">Recent Projects</h3>
                            <p class="text-sm text-secondary">Your latest project activities</p>
                        </div>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">View All</a>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th class="text-right">Contract Value</th>
                                <th class="text-right">Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProjects ?? [] as $project)
                                <tr class="clickable" onclick="location.href='{{ route('projects.show', $project->id) }}'">
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                        <div class="text-xs text-secondary">{{ $project->project_code ?? '' }}</div>
                                    </td>
                                    <td>{{ $project->client->name ?? '—' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($project->status ?? 'planning') {
                                                'active' => 'badge-success',
                                                'completed' => 'badge-info',
                                                'on-hold' => 'badge-warning',
                                                'planning' => 'badge-gray',
                                                default => 'badge-gray'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($project->status ?? 'planning') }}
                                        </span>
                                    </td>
                                    <td class="text-right font-semibold">
                                        RWF {{ number_format($project->contract_value ?? 0, 0) }}
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-sm">
                                            <div style="width: 80px; height: 6px; background: var(--color-gray-200); border-radius: var(--radius-full); overflow: hidden;">
                                                <div style="width: {{ $project->completion_percentage ?? 0 }}%; height: 100%; background: var(--gradient-primary);"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ $project->completion_percentage ?? 0 }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📊</div>
                                            <h3 class="empty-state-title">No Projects Yet</h3>
                                            <p class="empty-state-message">Create your first project to get started!</p>
                                            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="grid grid-cols-3 gap-lg mt-2xl">
                <a href="{{ route('projects.create') }}" class="card card-interactive" style="text-decoration: none; color: inherit;">
                    <div class="card-body text-center">
                        <div style="font-size: 3rem; margin-bottom: var(--space-md);">🏗️</div>
                        <h4 class="text-lg font-semibold mb-xs">New Project</h4>
                        <p class="text-sm text-secondary">Start a new construction project</p>
                    </div>
                </a>

                <a href="{{ route('expenses.create') }}" class="card card-interactive" style="text-decoration: none; color: inherit;">
                    <div class="card-body text-center">
                        <div style="font-size: 3rem; margin-bottom: var(--space-md);">💸</div>
                        <h4 class="text-lg font-semibold mb-xs">Add Expense</h4>
                        <p class="text-sm text-secondary">Record project expenses</p>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="card card-interactive" style="text-decoration: none; color: inherit;">
                    <div class="card-body text-center">
                        <div style="font-size: 3rem; margin-bottom: var(--space-md);">📊</div>
                        <h4 class="text-lg font-semibold mb-xs">View Reports</h4>
                        <p class="text-sm text-secondary">Access financial reports</p>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <!-- Chart.js Scripts -->
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000000, 15000000, 13000000, 18000000, 21000000, 24000000],
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4F46E5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
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
                                    return 'RWF ' + (value / 1000000) + 'M';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Expense Chart
        const expenseCtx = document.getElementById('expenseChart');
        if (expenseCtx) {
            new Chart(expenseCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Materials', 'Labor', 'Equipment', 'Transport', 'Other'],
                    datasets: [{
                        data: [45, 25, 15, 10, 5],
                        backgroundColor: [
                            '#4F46E5',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#6B7280'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
