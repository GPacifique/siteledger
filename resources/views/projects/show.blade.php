<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details - CSMS</title>
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
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
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
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                <h2 style="margin-bottom: 0;">📁 Project Information</h2>
                <div class="action-buttons" style="margin-top: 0;">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">← Back</a>
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary">✏️ Edit</a>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this project? This will also delete all associated tasks and data.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                    </form>
                </div>
            </div>

            <div class="detail-row" style="margin-top: 1.5rem;">
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
                    <span class="detail-label">Project Manager</span>
                    <span class="detail-value">{{ $project->manager ? $project->manager->first_name . ' ' . $project->manager->last_name : 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="badge badge-active">{{ ucfirst($project->status ?? 'Active') }}</span>
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
                <div class="financial-item">
                    <h4>💼 Agreed Budget</h4>
                    <div class="amount">RWF {{ number_format($agreedBudget ?? $project->contract_value ?? 0, 0) }}</div>
                </div>
                <div class="financial-item revenue">
                    <h4>💵 Amount Received</h4>
                    <div class="amount" style="color: #27ae60;">RWF {{ number_format($amountReceived ?? $receivedAmount ?? 0, 0) }}</div>
                </div>
                <div class="financial-item" style="border-left-color: #3498db;">
                    <h4>📊 Budget Remaining</h4>
                    <div class="amount" style="color: #3498db;">RWF {{ number_format($budgetRemaining ?? 0, 0) }}</div>
                    <small style="color: #666; display: block; margin-top: 0.3rem;">
                        (Budget - Received)
                    </small>
                </div>
            </div>
            <div class="financial-summary" style="margin-top: 1rem;">
                <div class="financial-item expense">
                    <h4>💸 Total Spent</h4>
                    <div class="amount" style="color: #dc3545;">RWF {{ number_format($amountSpent ?? 0, 0) }}</div>
                    <small style="color: #666; display: block; margin-top: 0.3rem;">
                        (Expenses: RWF {{ number_format($totalExpenses ?? 0, 0) }} + Payments: RWF {{ number_format($totalPayments ?? 0, 0) }})
                    </small>
                </div>
                <div class="financial-item profit">
                    <h4>📈 Profit</h4>
                    <div class="amount" style="color: {{ ($profit ?? 0) >= 0 ? '#27ae60' : '#dc3545' }};">
                        RWF {{ number_format($profit ?? 0, 0) }}
                    </div>
                    <small style="color: #666; display: block; margin-top: 0.3rem;">
                        (Budget - Spent)
                    </small>
                </div>
            </div>
        </div>

        <!-- Project Phases -->
        <div class="detail-card">
            <h2>📐 Project Phases</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Current Phase</span>
                    <span class="badge {{ $project->current_phase === 'execution' ? 'badge-active' : 'badge-pending' }}">
                        {{ $project->current_phase_label ?? 'Design Phase' }}
                    </span>
                </div>
            </div>

            <!-- Design Phase -->
            <div style="margin-bottom: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea;">
                <h3 style="color: #667eea; margin-bottom: 1rem; font-size: 1.1rem;">📝 Design Phase</h3>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Phase Value</span>
                        <span class="detail-value">RWF {{ number_format($project->design_phase_value ?? 0, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Amount Paid</span>
                        <span class="detail-value" style="color: #27ae60;">RWF {{ number_format($project->design_phase_paid ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Remaining</span>
                        <span class="detail-value" style="color: #dc3545;">RWF {{ number_format($project->design_phase_remaining ?? 0, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="badge {{ $project->design_phase_status === 'completed' ? 'badge-completed' : ($project->design_phase_status === 'in_progress' ? 'badge-active' : 'badge-pending') }}">
                            {{ ucfirst(str_replace('_', ' ', $project->design_phase_status ?? 'pending')) }}
                        </span>
                    </div>
                </div>
                @if($project->design_start_date || $project->design_end_date)
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Start Date</span>
                        <span class="detail-value">{{ $project->design_start_date?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">End Date</span>
                        <span class="detail-value">{{ $project->design_end_date?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
                @endif
                <!-- Progress Bar -->
                <div style="margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.85rem; color: #666;">Payment Progress</span>
                        <span style="font-size: 0.85rem; font-weight: 600;">{{ $project->design_phase_progress ?? 0 }}%</span>
                    </div>
                    <div style="background: #e0e0e0; border-radius: 4px; height: 8px; overflow: hidden;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; width: {{ $project->design_phase_progress ?? 0 }}%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
                <div style="margin-top: 1rem;">
                    <a href="{{ route('projects.phase-payments.create', [$project, 'design']) }}"
                       style="display: inline-block; padding: 0.5rem 1rem; background: #667eea; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                        + Add Design Payment
                    </a>
                </div>
            </div>

            <!-- Execution Phase -->
            <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #27ae60;">
                <h3 style="color: #27ae60; margin-bottom: 1rem; font-size: 1.1rem;">🔨 Execution Phase</h3>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Phase Value</span>
                        <span class="detail-value">RWF {{ number_format($project->execution_phase_value ?? 0, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Amount Paid</span>
                        <span class="detail-value" style="color: #27ae60;">RWF {{ number_format($project->execution_phase_paid ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Remaining</span>
                        <span class="detail-value" style="color: #dc3545;">RWF {{ number_format($project->execution_phase_remaining ?? 0, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="badge {{ $project->execution_phase_status === 'completed' ? 'badge-completed' : ($project->execution_phase_status === 'in_progress' ? 'badge-active' : 'badge-pending') }}">
                            {{ ucfirst(str_replace('_', ' ', $project->execution_phase_status ?? 'pending')) }}
                        </span>
                    </div>
                </div>
                @if($project->execution_start_date || $project->execution_end_date)
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Start Date</span>
                        <span class="detail-value">{{ $project->execution_start_date?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">End Date</span>
                        <span class="detail-value">{{ $project->execution_end_date?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
                @endif
                <!-- Progress Bar -->
                <div style="margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.85rem; color: #666;">Payment Progress</span>
                        <span style="font-size: 0.85rem; font-weight: 600;">{{ $project->execution_phase_progress ?? 0 }}%</span>
                    </div>
                    <div style="background: #e0e0e0; border-radius: 4px; height: 8px; overflow: hidden;">
                        <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); height: 100%; width: {{ $project->execution_phase_progress ?? 0 }}%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
                <div style="margin-top: 1rem;">
                    <a href="{{ route('projects.phase-payments.create', [$project, 'execution']) }}"
                       style="display: inline-block; padding: 0.5rem 1rem; background: #27ae60; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                        + Add Execution Payment
                    </a>
                </div>
            </div>
        </div>

        <!-- Phase Payments History -->
        @php
            $phasePayments = $project->phasePayments()->with('receiver')->orderBy('payment_date', 'desc')->get();
        @endphp
        @if($phasePayments->count() > 0)
        <div class="detail-card">
            <h2>💳 Phase Payments History ({{ $phasePayments->count() }})</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Phase</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($phasePayments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date?->format('M d, Y') }}</td>
                            <td>
                                <span class="badge {{ $payment->phase === 'design' ? 'badge-pending' : 'badge-active' }}">
                                    {{ ucfirst($payment->phase) }}
                                </span>
                            </td>
                            <td><strong>RWF {{ number_format($payment->amount, 2) }}</strong></td>
                            <td>{{ $payment->payment_method_label }}</td>
                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $payment->status === 'completed' ? 'badge-completed' : 'badge-pending' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('projects.phase-payments.edit', [$project, $payment]) }}"
                                   style="color: #667eea; text-decoration: none; margin-right: 0.5rem;">Edit</a>
                                <form action="{{ route('projects.phase-payments.destroy', [$project, $payment]) }}"
                                      method="POST" style="display: inline;"
                                      onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: #dc3545; background: none; border: none; cursor: pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Worker Payments History -->
        @if(isset($projectPayments) && $projectPayments->count() > 0)
        <div class="detail-card">
            <h2>💳 Worker Payments History ({{ $projectPayments->count() }})</h2>

            <!-- Payment Summary by Phase -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #667eea;">
                    <h4 style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">📝 Design Phase</h4>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #667eea;">RWF {{ number_format($designPayments ?? 0, 0) }}</div>
                </div>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #27ae60;">
                    <h4 style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">🔨 Execution Phase</h4>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #27ae60;">RWF {{ number_format($executionPayments ?? 0, 0) }}</div>
                </div>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #dc3545;">
                    <h4 style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">💰 Total Payments</h4>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #dc3545;">RWF {{ number_format($totalPayments ?? 0, 0) }}</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Worker</th>
                        <th>Phase</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectPayments as $payment)
                        <tr onclick="window.location.href='{{ route('payments.show', $payment->id) }}'" style="cursor: pointer;">
                            <td>{{ $payment->created_at?->format('M d, Y') }}</td>
                            <td>
                                @if($payment->employee)
                                    <strong>{{ $payment->employee->first_name }} {{ $payment->employee->last_name }}</strong>
                                @else
                                    <span style="color: #999;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->phase)
                                    @if($payment->phase == 'design')
                                        <span class="badge" style="background: #e8daef; color: #6c5ce7;">📝 Design</span>
                                    @else
                                        <span class="badge" style="background: #d4edda; color: #155724;">🔨 Execution</span>
                                    @endif
                                @else
                                    <span style="color: #999;">—</span>
                                @endif
                            </td>
                            <td><strong style="color: #dc3545;">RWF {{ number_format($payment->amount, 0) }}</strong></td>
                            <td>{{ ucfirst($payment->method ?? 'N/A') }}</td>
                            <td>{{ $payment->reference ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot style="background: #f5f7fa; font-weight: bold;">
                    <tr>
                        <td colspan="3"><strong>Total Worker Payments</strong></td>
                        <td colspan="3"><strong style="color: #dc3545;">RWF {{ number_format($totalPayments ?? 0, 0) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

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
                                <td>RWF {{ number_format($worker->projectPayments->sum('amount') ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">No workers assigned to this project yet</div>
            @endif
        </div>

        <!-- Payments by Position -->
        <div class="detail-card">
            <h2>📊 Worker Costs by Position</h2>
            @if(($paymentsByPosition ?? collect())->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Worker Count</th>
                            <th>Total Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentsByPosition as $position => $data)
                            <tr>
                                <td><strong>{{ $position ?: 'Unspecified' }}</strong></td>
                                <td>{{ $data['count'] }}</td>
                                <td>RWF {{ number_format($data['total_paid'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background: #f5f7fa; font-weight: bold;">
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalWorkers ?? 0 }}</strong></td>
                            <td><strong>RWF {{ number_format($totalWorkerCost ?? 0, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="empty-message">No worker payments recorded for this project yet</div>
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
