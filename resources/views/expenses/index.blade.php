<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses - SiteLedger</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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
            border-left: 4px solid #667eea;
        }
        .summary-card.materials { border-left-color: #e17055; }
        .summary-card.labor { border-left-color: #00b894; }
        .summary-card.design { border-left-color: #6c5ce7; }
        .summary-card.execution { border-left-color: #fdcb6e; }
        .summary-card.office { border-left-color: #74b9ff; }
        .summary-card.total { border-left-color: #d63031; }
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
        .summary-card .value.negative { color: #d63031; }

        /* Project Section */
        .project-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .project-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .project-header:hover {
            background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
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

        /* Expense Type Sections */
        .expense-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .expense-type-card {
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
        }
        .expense-type-header {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }
        .expense-type-header.materials {
            background: linear-gradient(135deg, #e17055 0%, #d63031 100%);
            color: white;
        }
        .expense-type-header.labor {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
        }
        .expense-type-header.other {
            background: linear-gradient(135deg, #636e72 0%, #2d3436 100%);
            color: white;
        }
        .expense-type-body {
            padding: 1rem;
            max-height: 300px;
            overflow-y: auto;
        }

        /* Phase Section (for labor) */
        .phase-section {
            margin-bottom: 1rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .phase-header {
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .phase-header.design {
            background: #6c5ce7;
            color: white;
        }
        .phase-header.execution {
            background: #fdcb6e;
            color: #333;
        }

        /* Expense Items */
        .expense-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s;
            cursor: pointer;
        }
        .expense-item:last-child {
            border-bottom: none;
        }
        .expense-item:hover {
            background: #e3f2fd;
        }
        .expense-info {
            flex: 1;
        }
        .expense-description {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.25rem;
        }
        .expense-meta {
            font-size: 0.8rem;
            color: #666;
        }
        .expense-amount {
            font-weight: 700;
            color: #d63031;
            white-space: nowrap;
        }

        /* Materials specific */
        .material-item {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 1rem;
            align-items: center;
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background 0.2s;
        }
        .material-item:hover {
            background: #ffeaa7;
        }
        .material-name {
            font-weight: 500;
        }
        .material-qty {
            color: #666;
            font-size: 0.9rem;
        }

        /* Summary Row */
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        /* Office Expenses Section */
        .office-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .office-header {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .office-header h3 {
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
            background: #f0f8f5;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-materials { background: #ffeaa7; color: #d63031; }
        .badge-labor { background: #55efc4; color: #00695c; }
        .badge-design { background: #dfe6e9; color: #6c5ce7; }
        .badge-execution { background: #ffeaa7; color: #856404; }
        .badge-completed { background: #d4edda; color: #155724; }
        .badge-pending { background: #fff3cd; color: #856404; }

        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        /* Collapsible */
        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .collapsible-content.expanded {
            max-height: 2000px;
        }

        @media (max-width: 768px) {
            .expense-type-grid {
                grid-template-columns: 1fr;
            }
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <div>
                <h1>💰 Expenses</h1>
                <p>Track project expenses: Materials, Labor by Phase, and more</p>
            </div>
            <a href="/expenses/create" class="btn-primary">+ Add Expense</a>
        </div>

        <!-- Combined Payments + Expenses Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card total">
                <h4>💳 Total All Expenses</h4>
                <div class="value negative">RWF {{ number_format($allExpensesTotal ?? 0, 0) }}</div>
                <small style="color: #666; margin-top: 0.5rem; display: block;">Payments + Office + Project</small>
            </div>
            <div class="summary-card office">
                <h4>📅 This Month</h4>
                <div class="value">RWF {{ number_format($allExpensesThisMonth ?? 0, 0) }}</div>
                <small style="color: #666; margin-top: 0.5rem; display: block;">All expenses this month</small>
            </div>
            <div class="summary-card materials">
                <h4>📆 Today</h4>
                <div class="value">RWF {{ number_format($allExpensesToday ?? 0, 0) }}</div>
                <small style="color: #666; margin-top: 0.5rem; display: block;">All expenses today</small>
            </div>
            <div class="summary-card labor">
                <h4>👷 Worker Payments</h4>
                <div class="value">RWF {{ number_format($paymentsTotal ?? 0, 0) }}</div>
                <small style="color: #666; margin-top: 0.5rem; display: block;">Total worker payments</small>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card total">
                <h4>Total Expenses</h4>
                <div class="value negative">RWF {{ number_format(($officeTotal ?? 0) + ($projectTotal ?? 0), 0) }}</div>
            </div>
            <div class="summary-card materials">
                <h4>🧱 Materials</h4>
                <div class="value">RWF {{ number_format($totalMaterials ?? 0, 0) }}</div>
            </div>
            <div class="summary-card labor">
                <h4>👷 Labor</h4>
                <div class="value">RWF {{ number_format($totalLabor ?? 0, 0) }}</div>
            </div>
            <div class="summary-card design">
                <h4>📝 Design Labor</h4>
                <div class="value">RWF {{ number_format($totalDesignLabor ?? 0, 0) }}</div>
            </div>
            <div class="summary-card execution">
                <h4>🔨 Execution Labor</h4>
                <div class="value">RWF {{ number_format($totalExecutionLabor ?? 0, 0) }}</div>
            </div>
            <div class="summary-card office">
                <h4>🏢 Office</h4>
                <div class="value">RWF {{ number_format($officeTotal ?? 0, 0) }}</div>
            </div>
        </div>

        @if(($expensesByProject ?? collect())->count() > 0)
            <!-- Project Expenses - Each Project Independent -->
            @foreach($expensesByProject as $projectId => $projectData)
                <div class="project-section">
                    <div class="project-header" onclick="toggleProject({{ $projectId }})">
                        <h3>
                            <span>🏗️ {{ $projectData['project_name'] }}</span>
                            <span style="font-size: 0.85rem; font-weight: normal; opacity: 0.9;">({{ $projectData['count'] }} expenses)</span>
                        </h3>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span class="project-total">RWF {{ number_format($projectData['total'], 0) }}</span>
                            <span class="toggle-icon" id="icon-{{ $projectId }}">▼</span>
                        </div>
                    </div>
                    <div class="project-body" id="project-{{ $projectId }}">
                        <!-- Project Summary -->
                        <div class="summary-grid" style="margin-bottom: 1.5rem;">
                            <div class="summary-card materials" style="padding: 1rem;">
                                <h4 style="font-size: 0.75rem;">Materials</h4>
                                <div class="value" style="font-size: 1.2rem;">RWF {{ number_format($projectData['materials']['total'], 0) }}</div>
                            </div>
                            <div class="summary-card labor" style="padding: 1rem;">
                                <h4 style="font-size: 0.75rem;">Labor Total</h4>
                                <div class="value" style="font-size: 1.2rem;">RWF {{ number_format($projectData['labor']['total'], 0) }}</div>
                            </div>
                            <div class="summary-card design" style="padding: 1rem;">
                                <h4 style="font-size: 0.75rem;">Design Phase</h4>
                                <div class="value" style="font-size: 1.2rem;">RWF {{ number_format($projectData['labor']['design']['total'], 0) }}</div>
                            </div>
                            <div class="summary-card execution" style="padding: 1rem;">
                                <h4 style="font-size: 0.75rem;">Execution Phase</h4>
                                <div class="value" style="font-size: 1.2rem;">RWF {{ number_format($projectData['labor']['execution']['total'], 0) }}</div>
                            </div>
                        </div>

                        <div class="expense-type-grid">
                            <!-- Materials Section -->
                            <div class="expense-type-card">
                                <div class="expense-type-header materials">
                                    <span>🧱 Materials ({{ $projectData['materials']['count'] }})</span>
                                    <span>RWF {{ number_format($projectData['materials']['total'], 0) }}</span>
                                </div>
                                <div class="expense-type-body">
                                    @forelse($projectData['materials']['expenses'] as $expense)
                                        <div class="material-item" onclick="window.location.href='/expenses/{{ $expense->id }}'">
                                            <div>
                                                <div class="material-name">{{ $expense->item_name ?? $expense->description ?? 'Material' }}</div>
                                                <div class="expense-meta">{{ $expense->date ? $expense->date->format('M d, Y') : 'N/A' }} • {{ $expense->user->name ?? 'Unknown' }}</div>
                                            </div>
                                            <div class="material-qty">
                                                @if($expense->quantity && $expense->unit)
                                                    {{ number_format($expense->quantity, 0) }} {{ $expense->unit }}
                                                @endif
                                            </div>
                                            <div class="expense-amount">RWF {{ number_format($expense->amount, 0) }}</div>
                                        </div>
                                    @empty
                                        <div class="empty-message">No materials recorded</div>
                                    @endforelse
                                    @if($projectData['materials']['count'] > 0)
                                        <div class="summary-row">
                                            <span>Total Materials</span>
                                            <span style="color: #d63031;">RWF {{ number_format($projectData['materials']['total'], 0) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Labor Section -->
                            <div class="expense-type-card">
                                <div class="expense-type-header labor">
                                    <span>👷 Labor ({{ $projectData['labor']['count'] }})</span>
                                    <span>RWF {{ number_format($projectData['labor']['total'], 0) }}</span>
                                </div>
                                <div class="expense-type-body">
                                    <!-- Design Phase Labor -->
                                    @if($projectData['labor']['design']['count'] > 0)
                                        <div class="phase-section">
                                            <div class="phase-header design">
                                                <span>📝 Design Phase</span>
                                                <span>RWF {{ number_format($projectData['labor']['design']['total'], 0) }}</span>
                                            </div>
                                            @foreach($projectData['labor']['design']['expenses'] as $expense)
                                                <div class="expense-item" onclick="window.location.href='/expenses/{{ $expense->id }}'">
                                                    <div class="expense-info">
                                                        <div class="expense-description">{{ $expense->description ?? 'Labor' }}</div>
                                                        <div class="expense-meta">{{ $expense->date ? $expense->date->format('M d, Y') : 'N/A' }} • {{ $expense->user->name ?? 'Unknown' }}</div>
                                                    </div>
                                                    <div class="expense-amount">RWF {{ number_format($expense->amount, 0) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Execution Phase Labor -->
                                    @if($projectData['labor']['execution']['count'] > 0)
                                        <div class="phase-section">
                                            <div class="phase-header execution">
                                                <span>🔨 Execution Phase</span>
                                                <span>RWF {{ number_format($projectData['labor']['execution']['total'], 0) }}</span>
                                            </div>
                                            @foreach($projectData['labor']['execution']['expenses'] as $expense)
                                                <div class="expense-item" onclick="window.location.href='/expenses/{{ $expense->id }}'">
                                                    <div class="expense-info">
                                                        <div class="expense-description">{{ $expense->description ?? 'Labor' }}</div>
                                                        <div class="expense-meta">{{ $expense->date ? $expense->date->format('M d, Y') : 'N/A' }} • {{ $expense->user->name ?? 'Unknown' }}</div>
                                                    </div>
                                                    <div class="expense-amount">RWF {{ number_format($expense->amount, 0) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Unassigned Phase Labor -->
                                    @php
                                        $unassignedLabor = $projectData['labor']['expenses']->whereNull('phase')->merge(
                                            $projectData['labor']['expenses']->whereNotIn('phase', ['design', 'execution'])
                                        );
                                    @endphp
                                    @if($unassignedLabor->count() > 0)
                                        <div class="phase-section" style="background: #f5f5f5;">
                                            <div class="phase-header" style="background: #95a5a6; color: white;">
                                                <span>📋 General Labor</span>
                                                <span>RWF {{ number_format($unassignedLabor->sum('amount'), 0) }}</span>
                                            </div>
                                            @foreach($unassignedLabor as $expense)
                                                <div class="expense-item" onclick="window.location.href='/expenses/{{ $expense->id }}'">
                                                    <div class="expense-info">
                                                        <div class="expense-description">{{ $expense->description ?? 'Labor' }}</div>
                                                        <div class="expense-meta">{{ $expense->date ? $expense->date->format('M d, Y') : 'N/A' }} • {{ $expense->user->name ?? 'Unknown' }}</div>
                                                    </div>
                                                    <div class="expense-amount">RWF {{ number_format($expense->amount, 0) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($projectData['labor']['count'] == 0)
                                        <div class="empty-message">No labor recorded</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Other Expenses Section -->
                            @if($projectData['other']['count'] > 0)
                                <div class="expense-type-card">
                                    <div class="expense-type-header other">
                                        <span>📦 Other ({{ $projectData['other']['count'] }})</span>
                                        <span>RWF {{ number_format($projectData['other']['total'], 0) }}</span>
                                    </div>
                                    <div class="expense-type-body">
                                        @foreach($projectData['other']['expenses'] as $expense)
                                            <div class="expense-item" onclick="window.location.href='/expenses/{{ $expense->id }}'">
                                                <div class="expense-info">
                                                    <div class="expense-description">{{ $expense->description ?? $expense->category ?? 'Expense' }}</div>
                                                    <div class="expense-meta">
                                                        {{ $expense->date ? $expense->date->format('M d, Y') : 'N/A' }}
                                                        @if($expense->expense_type)
                                                            • {{ ucfirst($expense->expense_type) }}
                                                        @endif
                                                        • {{ $expense->user->name ?? 'Unknown' }}
                                                    </div>
                                                </div>
                                                <div class="expense-amount">RWF {{ number_format($expense->amount, 0) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Office Expenses Section -->
        @if(($officeExpenses ?? collect())->count() > 0)
            <div class="office-section">
                <div class="office-header">
                    <h3>🏢 Office Expenses ({{ $officeExpenses->count() }})</h3>
                    <span style="font-size: 1.3rem; font-weight: 700;">RWF {{ number_format($officeTotal, 0) }}</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Recorded By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($officeExpenses as $expense)
                            <tr onclick="window.location.href='/expenses/{{ $expense->id }}'">
                                <td>{{ $expense->date ? $expense->date->format('M d, Y') : $expense->created_at->format('M d, Y') }}</td>
                                <td>{{ $expense->description ?? 'N/A' }}</td>
                                <td>{{ $expense->category ?? 'General' }}</td>
                                <td><strong style="color: #d63031;">RWF {{ number_format($expense->amount ?? 0, 0) }}</strong></td>
                                <td>{{ $expense->user->name ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-{{ $expense->status ?? 'pending' }}">
                                        {{ ucfirst($expense->status ?? 'Pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(($expensesByProject ?? collect())->count() == 0 && ($officeExpenses ?? collect())->count() == 0)
            <div class="empty-message" style="background: white; border-radius: 12px; padding: 3rem;">
                <p style="font-size: 1.2rem;">No expenses found.</p>
                <p style="margin-top: 0.5rem;">
                    <a href="/expenses/create" style="color: #667eea;">Add your first expense</a> or
                    <a href="/admin/dashboard" style="color: #667eea;">Go to Dashboard</a>
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
