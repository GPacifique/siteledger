<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenues - SiteLedger</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-header h1 {
            font-size: 2rem;
            color: #333;
        }
        .page-header p {
            color: #666;
            font-size: 1rem;
            width: 100%;
        }
        .btn-primary {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .summary-card {
            background: white;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #27ae60;
        }
        .summary-card.total { border-left-color: #27ae60; }
        .summary-card.monthly { border-left-color: #3498db; }
        .summary-card.today { border-left-color: #9b59b6; }
        .summary-card.pending { border-left-color: #f39c12; }
        .summary-card.paid { border-left-color: #2ecc71; }
        .summary-card.net { border-left-color: #1abc9c; }
        .summary-card.expense { border-left-color: #e74c3c; }
        .summary-card h4 {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .summary-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }
        .summary-card .value.positive { color: #27ae60; }
        .summary-card .value.negative { color: #e74c3c; }
        .summary-card small {
            color: #666;
            margin-top: 0.5rem;
            display: block;
            font-size: 0.8rem;
        }

        /* Project Section */
        .project-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .project-header {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .project-header:hover {
            background: linear-gradient(135deg, #219a52 0%, #27ae60 100%);
        }
        .project-header h3 {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .project-header .project-total {
            font-size: 1.3rem;
            font-weight: 700;
        }
        .project-header .toggle-icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }
        .project-header.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }
        .project-body {
            padding: 1.5rem;
            display: block;
        }
        .project-body.hidden {
            display: none;
        }

        /* Revenue Items */
        .revenue-list {
            display: grid;
            gap: 1rem;
        }
        .revenue-item {
            display: grid;
            grid-template-columns: 1fr auto auto auto;
            gap: 1.5rem;
            align-items: center;
            padding: 1rem 1.25rem;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .revenue-item:hover {
            background: #e8f5e9;
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.15);
        }
        .revenue-item.pending {
            border-left-color: #f39c12;
        }
        .revenue-info {
            flex: 1;
        }
        .revenue-description {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        .revenue-meta {
            font-size: 0.85rem;
            color: #666;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .revenue-meta span {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .revenue-amount {
            font-weight: 700;
            color: #27ae60;
            font-size: 1.1rem;
            white-space: nowrap;
        }
        .revenue-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-partially-paid {
            background: #cce5ff;
            color: #004085;
        }
        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }

        /* Project Stats */
        .project-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .project-stat {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .project-stat-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }
        .project-stat-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
        }
        .project-stat-value.positive { color: #27ae60; }
        .project-stat-value.negative { color: #e74c3c; }
        .project-stat-value.neutral { color: #3498db; }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-view { background: #27ae60; }
        .btn-edit { background: #f39c12; }
        .btn-delete { background: #e74c3c; }
        .btn-sm:hover { opacity: 0.85; }

        /* Non-Project Section */
        .general-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .general-header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .general-header h3 {
            font-size: 1.2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            transition: background 0.2s;
            cursor: pointer;
        }
        tbody tr:hover {
            background: #e8f5e9;
        }

        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        /* Progress Bar */
        .progress-container {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            transition: width 0.3s ease;
        }
        .progress-text {
            font-size: 0.75rem;
            color: #666;
            margin-top: 0.25rem;
            text-align: right;
        }

        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .revenue-item {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            .action-buttons {
                justify-content: flex-start;
            }
        }

        @media (max-width: 480px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
            .container { padding: 1rem; }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    @php
        // Group revenues by project
        $allRevenues = \App\Models\Income::with('project')->orderBy('received_at', 'desc')->get();

        $revenuesByProject = $allRevenues->whereNotNull('project_id')->groupBy('project_id')->map(function($revenues, $projectId) {
            $project = $revenues->first()->project;
            $totalReceived = $revenues->sum('amount_received');
            $paidCount = $revenues->whereIn('payment_status', ['Paid', 'received'])->count();
            $pendingCount = $revenues->whereIn('payment_status', ['Pending', 'pending'])->count();
            $contractValue = $project?->contract_value ?? 0;
            $remaining = max(0, $contractValue - $totalReceived);
            $progress = $contractValue > 0 ? round(($totalReceived / $contractValue) * 100, 1) : 0;

            return [
                'project_name' => $project?->name ?? 'Unknown Project',
                'project' => $project,
                'revenues' => $revenues,
                'total' => $totalReceived,
                'count' => $revenues->count(),
                'paid_count' => $paidCount,
                'pending_count' => $pendingCount,
                'contract_value' => $contractValue,
                'remaining' => $remaining,
                'progress' => min(100, $progress),
            ];
        });

        $generalRevenues = $allRevenues->whereNull('project_id');
        $generalTotal = $generalRevenues->sum('amount_received');

        // Stats calculations
        $today = \Carbon\Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        $totalPaid = $allRevenues->whereIn('payment_status', ['Paid', 'received'])->sum('amount_received');
        $totalPending = $allRevenues->whereIn('payment_status', ['Pending', 'pending'])->sum('amount_received');

        // Project totals
        $projectTotal = $revenuesByProject->sum('total');
    @endphp

    <div class="container">
        <div class="page-header">
            <div>
                <h1>💵 Revenues</h1>
                <p>Track income by project: Payments received, pending amounts, and contract progress</p>
            </div>
            <a href="{{ route('revenues.create') }}" class="btn-primary">+ Add Revenue</a>
        </div>

        <!-- Combined Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card total">
                <h4>💰 Total Revenue</h4>
                <div class="value positive">RWF {{ number_format($totalRevenue ?? 0, 0) }}</div>
                <small>All time income</small>
            </div>
            <div class="summary-card monthly">
                <h4>📅 This Month</h4>
                <div class="value">RWF {{ number_format($revenueThisMonth ?? 0, 0) }}</div>
                <small>{{ $startOfMonth->format('M Y') }} income</small>
            </div>
            <div class="summary-card today">
                <h4>📆 Today</h4>
                <div class="value">RWF {{ number_format($revenueToday ?? 0, 0) }}</div>
                <small>{{ $today->format('M d, Y') }}</small>
            </div>
            <div class="summary-card expense">
                <h4>💸 Today's Expenses</h4>
                <div class="value negative">RWF {{ number_format($allExpensesToday ?? 0, 0) }}</div>
                <small>All expenses today</small>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="summary-grid">
            <div class="summary-card paid">
                <h4>✅ Paid</h4>
                <div class="value positive">RWF {{ number_format($totalPaid ?? 0, 0) }}</div>
                <small>Received payments</small>
            </div>
            <div class="summary-card pending">
                <h4>⏳ Pending</h4>
                <div class="value" style="color: #f39c12;">RWF {{ number_format($totalPending ?? 0, 0) }}</div>
                <small>Awaiting payment</small>
            </div>
            <div class="summary-card net">
                <h4>📊 Today's Net</h4>
                @php $todayNet = ($revenueToday ?? 0) - ($allExpensesToday ?? 0); @endphp
                <div class="value {{ $todayNet >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format($todayNet, 0) }}</div>
                <small>{{ $todayNet >= 0 ? 'Profit' : 'Loss' }} today</small>
            </div>
            <div class="summary-card" style="border-left-color: #9b59b6;">
                <h4>🏗️ From Projects</h4>
                <div class="value">RWF {{ number_format($projectTotal ?? 0, 0) }}</div>
                <small>{{ $revenuesByProject->count() }} projects</small>
            </div>
        </div>

        @if($revenuesByProject->count() > 0)
            <!-- Project Revenues - Each Project Independent -->
            @foreach($revenuesByProject as $projectId => $projectData)
                <div class="project-section">
                    <div class="project-header" onclick="toggleProject({{ $projectId }})">
                        <h3>
                            <span>🏗️ {{ $projectData['project_name'] }}</span>
                            <span style="font-size: 0.85rem; font-weight: normal; opacity: 0.9;">({{ $projectData['count'] }} payments)</span>
                        </h3>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span class="project-total">RWF {{ number_format($projectData['total'], 0) }}</span>
                            <span class="toggle-icon" id="icon-{{ $projectId }}">▼</span>
                        </div>
                    </div>
                    <div class="project-body" id="project-{{ $projectId }}">
                        <!-- Project Stats -->
                        <div class="project-stats">
                            <div class="project-stat">
                                <div class="project-stat-label">Contract Value</div>
                                <div class="project-stat-value neutral">RWF {{ number_format($projectData['contract_value'], 0) }}</div>
                            </div>
                            <div class="project-stat">
                                <div class="project-stat-label">Total Received</div>
                                <div class="project-stat-value positive">RWF {{ number_format($projectData['total'], 0) }}</div>
                            </div>
                            <div class="project-stat">
                                <div class="project-stat-label">Remaining</div>
                                <div class="project-stat-value negative">RWF {{ number_format($projectData['remaining'], 0) }}</div>
                            </div>
                            <div class="project-stat">
                                <div class="project-stat-label">Progress</div>
                                <div class="project-stat-value">{{ $projectData['progress'] }}%</div>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ $projectData['progress'] }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue List -->
                        <div class="revenue-list">
                            @foreach($projectData['revenues'] as $revenue)
                                <div class="revenue-item {{ in_array($revenue->payment_status, ['Pending', 'pending']) ? 'pending' : '' }}"
                                     onclick="window.location.href='{{ route('revenues.show', $revenue) }}'">
                                    <div class="revenue-info">
                                        <div class="revenue-description">
                                            {{ $revenue->invoice_number ?? $revenue->notes ?? 'Payment #' . $revenue->id }}
                                        </div>
                                        <div class="revenue-meta">
                                            <span>📅 {{ $revenue->received_at ? $revenue->received_at->format('M d, Y') : 'N/A' }}</span>
                                            @if($revenue->notes)
                                                <span>📝 {{ Str::limit($revenue->notes, 30) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="revenue-amount">RWF {{ number_format($revenue->amount_received, 0) }}</div>
                                    <span class="revenue-status status-{{ strtolower(str_replace(' ', '-', $revenue->payment_status ?? 'pending')) }}">
                                        {{ $revenue->payment_status ?? 'Pending' }}
                                    </span>
                                    <div class="action-buttons" onclick="event.stopPropagation();">
                                        <a href="{{ route('revenues.show', $revenue) }}" class="btn-sm btn-view">View</a>
                                        <a href="{{ route('revenues.edit', $revenue) }}" class="btn-sm btn-edit">Edit</a>
                                        <form action="{{ route('revenues.destroy', $revenue) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- General Revenues (No Project) -->
        @if($generalRevenues->count() > 0)
            <div class="general-section">
                <div class="general-header">
                    <h3>📋 General Revenue ({{ $generalRevenues->count() }})</h3>
                    <span style="font-size: 1.3rem; font-weight: 700;">RWF {{ number_format($generalTotal, 0) }}</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice/Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($generalRevenues as $revenue)
                            <tr onclick="window.location.href='{{ route('revenues.show', $revenue) }}'">
                                <td>{{ $revenue->received_at ? $revenue->received_at->format('M d, Y') : $revenue->created_at->format('M d, Y') }}</td>
                                <td>{{ $revenue->invoice_number ?? $revenue->notes ?? 'N/A' }}</td>
                                <td><strong style="color: #27ae60;">RWF {{ number_format($revenue->amount_received ?? 0, 0) }}</strong></td>
                                <td>
                                    <span class="revenue-status status-{{ strtolower(str_replace(' ', '-', $revenue->payment_status ?? 'pending')) }}">
                                        {{ $revenue->payment_status ?? 'Pending' }}
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
            </div>
        @endif

        @if($revenuesByProject->count() == 0 && $generalRevenues->count() == 0)
            <div class="empty-message" style="background: white; border-radius: 12px; padding: 3rem;">
                <p style="font-size: 1.2rem;">No revenues found.</p>
                <p style="margin-top: 0.5rem;">
                    <a href="{{ route('revenues.create') }}" style="color: #27ae60;">Add your first revenue</a> or
                    <a href="/admin/dashboard" style="color: #27ae60;">Go to Dashboard</a>
                </p>
            </div>
        @endif
    </div>

    <script>
        function toggleProject(projectId) {
            const body = document.getElementById('project-' + projectId);
            const icon = document.getElementById('icon-' + projectId);

            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                icon.style.transform = 'rotate(0deg)';
            } else {
                body.classList.add('hidden');
                icon.style.transform = 'rotate(-90deg)';
            }
        }
    </script>
</body>
</html>
