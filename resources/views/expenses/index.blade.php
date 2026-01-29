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
                <p>Track all expenses by category, quantity, and price per unit.</p>
            </div>
            <a href="/expenses/create" class="btn-primary">+ Add Expense</a>
        </div>

        <div class="summary-grid">
            <div class="summary-card total">
                <h4>💳 Grand Total</h4>
                <div class="value" style="color: #d32f2f;">RWF {{ number_format($grandTotal ?? 0, 0) }}</div>
            </div>
            <div class="summary-card">
                <h4>📅 Today</h4>
                <div class="value">RWF {{ number_format($dailyTotal ?? 0, 0) }}</div>
            </div>
            <div class="summary-card">
                <h4>🗓️ This Month</h4>
                <div class="value">RWF {{ number_format($monthlyTotal ?? 0, 0) }}</div>
            </div>
            <div class="summary-card">
                <h4>📆 This Year</h4>
                <div class="value">RWF {{ number_format($yearlyTotal ?? 0, 0) }}</div>
            </div>
        </div>

        <form method="GET" class="mb-3" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem; align-items: flex-end;">
                <div>
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
                </div>
                <div>
                    <button type="submit" class="btn-primary" style="padding: 0.5rem 1.5rem;">Filter</button>
                </div>
            </div>
        </form>

        <div class="office-section">
            <div class="office-header" style="background: linear-gradient(135deg, #d63031 0%, #e74c3c 100%);">
                <h3>📋 All Expenses ({{ $expenses->count() }})</h3>
                <span style="font-size: 1.3rem; font-weight: 700;">RWF {{ number_format($grandTotal, 0) }}</span>
            </div>
            <table>
                <thead style="background: #d63031;">
                    <tr>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('M d, Y') : '' }}</td>
                            <td>{{ $expense->project->name ?? 'N/A' }}</td>
                            <td>{{ $expense->category->name ?? 'N/A' }}</td>
                            <td>{{ $expense->quantity }}</td>
                            <td>RWF {{ number_format($expense->price_per_one, 2) }}</td>
                            <td><strong style="color: #d63031;">RWF {{ number_format($expense->total, 2) }}</strong></td>
                            <td>{{ $expense->notes }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="/expenses/{{ $expense->id }}/edit" style="background: #f39c12; color: white; padding: 0.3rem 0.6rem; border-radius: 4px; text-decoration: none; font-size: 0.8rem;">Edit</a>
                                    <form action="/expenses/{{ $expense->id }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #e74c3c; color: white; padding: 0.3rem 0.6rem; border-radius: 4px; border: none; cursor: pointer; font-size: 0.8rem;" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-message">No expenses found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #fce4e4; font-weight: bold;">
                        <td colspan="4" style="text-align: right; font-size: 1.1rem;">Total:</td>
                        <td style="color: #d63031; font-size: 1.1rem;">RWF {{ number_format($grandTotal, 0) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
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
