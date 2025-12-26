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
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #666;
            font-size: 1rem;
        }
        .category-section {
            margin-bottom: 2rem;
        }
        .category-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
            background: #667eea;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .category-total {
            font-size: 1.1rem;
            font-weight: 700;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
            font-size: 0.85rem;
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
        .badge-approved {
            background: #d1ecf1;
            color: #0c5460;
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .summary-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .summary-card:hover::before {
            left: 100%;
        }
        .summary-card:hover {
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
            transform: translateY(-4px);
            background: linear-gradient(135deg, #f8f9ff 0%, white 100%);
        }
        .summary-card:active {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }
        .summary-card h3 {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .summary-card .amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
        }
        .empty-message {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: white;
            border-radius: 8px;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>

    <script>
        // Enhanced card interaction functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.summary-card').forEach(card => {
                const h3 = card.querySelector('h3');

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

                    // Scroll to expenses table
                    setTimeout(() => {
                        const table = document.querySelector('table');
                        if (table) {
                            table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
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
            });
        });
    </script>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1>💰 Expenses</h1>
            <p>Track expenses by category: Offices and Project Expenses</p>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
            <a href="/expenses/create" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">+ Add Expense</a>
        </div>

        @if($officeTotal > 0 || $projectTotal > 0)
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <h3>Office Expenses</h3>
                    <div class="amount">RWF {{ number_format($officeTotal, 2) }}</div>
                </div>
                <div class="summary-card">
                    <h3>Project Expenses</h3>
                    <div class="amount">RWF {{ number_format($projectTotal, 2) }}</div>
                </div>
                <div class="summary-card">
                    <h3>Total Expenses</h3>
                    <div class="amount">RWF {{ number_format($officeTotal + $projectTotal, 2) }}</div>
                </div>
            </div>

            <!-- Office Expenses -->
            @if($officeExpenses->count() > 0)
                <div class="category-section">
                    <div class="category-title">
                        <span>🏢 Office Expenses</span>
                        <span class="category-total">RWF {{ number_format($officeTotal, 2) }}</span>
                    </div>
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
                            @foreach($officeExpenses as $expense)
                                <tr data-expense-id="{{ $expense->id }}">
                                    <td>{{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('M d, Y') : $expense->created_at->format('M d, Y') }}</td>
                                    <td>{{ $expense->description ?? 'N/A' }}</td>
                                    <td>{{ $expense->category ?? 'General' }}</td>
                                    <td><strong>RWF {{ number_format($expense->amount ?? 0, 2) }}</strong></td>
                                    <td>
                                        @php
                                            $statusClass = match($expense->status ?? 'pending') {
                                                'approved' => 'badge-approved',
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
                </div>
            @endif

            <!-- Project Expenses -->
            @if($projectExpenses->count() > 0)
                <div class="category-section">
                    <div class="category-title">
                        <span>📊 Project Expenses</span>
                        <span class="category-total">RWF {{ number_format($projectTotal, 2) }}</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projectExpenses as $expense)
                                <tr data-expense-id="{{ $expense->id }}">
                                    <td>{{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('M d, Y') : $expense->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($expense->project)
                                            <a href="/projects/{{ $expense->project->id }}" style="color: #667eea; text-decoration: none;">{{ $expense->project->name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $expense->description ?? 'N/A' }}</td>
                                    <td>{{ $expense->category ?? 'General' }}</td>
                                    <td><strong>RWF {{ number_format($expense->amount ?? 0, 2) }}</strong></td>
                                    <td>
                                        @php
                                            $statusClass = match($expense->status ?? 'pending') {
                                                'approved' => 'badge-approved',
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
                </div>
            @endif
        @else
            <div class="empty-message">
                <p>No expenses found. <a href="/admin/dashboard">Go to Dashboard</a></p>
            </div>
        @endif
    </div>
    <script>
        // Make table rows clickable
        document.addEventListener('DOMContentLoaded', function() {
            // Get all table rows
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.cursor = 'pointer';

                row.addEventListener('click', function(e) {
                    // Don't navigate if clicking on a link
                    if (e.target.tagName === 'A') {
                        return;
                    }

                    // Get the expense ID from the first cell or data attribute
                    const expenseId = this.getAttribute('data-expense-id');
                    if (expenseId) {
                        window.location.href = `/expenses/${expenseId}`;
                    }
                });

                // Add keyboard support
                row.setAttribute('tabindex', '0');
                row.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const expenseId = this.getAttribute('data-expense-id');
                        if (expenseId) {
                            window.location.href = `/expenses/${expenseId}`;
                        }
                    }
                });
            });
        });
    </script></body>
</html>
