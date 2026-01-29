<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $expense->description ?? 'Expense Details' }} - SiteLedger</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .expense-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .expense-item {
            display: flex;
            flex-direction: column;
        }

        .expense-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .expense-value {
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }

        .expense-amount {
            font-size: 32px;
            color: #667eea;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            width: fit-content;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background: #d4edda;
            color: #155724;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-completed {
            background: #d4edda;
            color: #155724;
        }

        .description-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .description-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .description-text {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        .relation-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }

        .relation-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .relation-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .relation-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .relation-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #333;
        }

        .btn-secondary:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.4);
        }

        .meta-info {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .meta-item {
            display: inline-block;
            margin-right: 20px;
        }

        @media (max-width: 768px) {
            .detail-card {
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            .expense-header {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url()->previous() }}" class="back-link">← Back</a>

        <div class="detail-card">
            <h1>💰 Expense Details</h1>

            <div class="expense-header">
                <div class="expense-item">
                    <span class="expense-label">Amount</span>
                    <span class="expense-amount">RWF {{ number_format($expense->amount ?? 0, 2) }}</span>
                </div>

                <div class="expense-item">
                    <span class="expense-label">Category</span>
                    <span class="expense-value">
                        @if(is_object($expense->category) && isset($expense->category->name))
                            {{ $expense->category->name }}
                        @else
                            {{ $expense->category ?? 'General' }}
                        @endif
                    </span>
                </div>

                <div class="expense-item">
                    <span class="expense-label">Status</span>
                    @php
                        $statusClass = match($expense->status ?? 'pending') {
                            'approved' => 'badge-approved',
                            'completed' => 'badge-completed',
                            'rejected' => 'badge-rejected',
                            default => 'badge-pending'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($expense->status ?? 'Pending') }}</span>
                </div>

                <div class="expense-item">
                    <span class="expense-label">Date</span>
                    <span class="expense-value">{{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('M d, Y') : $expense->created_at->format('M d, Y') }}</span>
                </div>

                <div class="expense-item">
                    <span class="expense-label">Payment Method</span>
                    <span class="expense-value">{{ $expense->method ?? 'Not Specified' }}</span>
                </div>

                <div class="expense-item">
                    <span class="expense-label">Recorded</span>
                    <span class="expense-value">
                        @if($expense->created_at)
                            {{ $expense->created_at->format('M d, Y - g:i A') }}
                        @else
                            Not recorded
                        @endif
                    </span>
                </div>
            </div>

            <div class="description-section">
                <div class="description-label">Description</div>
                <div class="description-text">{{ $expense->description ?? 'No description provided' }}</div>
            </div>

            @if($expense->project)
                <div class="relation-card">
                    <div class="relation-label">📁 Associated Project</div>
                    <div class="relation-value">
                        <a href="{{ route('projects.show', $expense->project->id) }}" class="relation-link">{{ $expense->project->name }}</a>
                    </div>
                </div>
            @endif

            @if($expense->client)
                <div class="relation-card">
                    <div class="relation-label">🏢 Associated Client</div>
                    <div class="relation-value">
                        <a href="{{ route('clients.show', $expense->client->id) }}" class="relation-link">{{ $expense->client->name }}</a>
                    </div>
                </div>
            @endif

            @if($expense->user)
                <div class="relation-card">
                    <div class="relation-label">👤 Recorded By</div>
                    <div class="relation-value">{{ $expense->user->name }}</div>
                </div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-primary">Edit Expense</a>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to Expenses</a>
                <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this expense?')">Delete Expense</button>
                </form>
            </div>

            <div class="meta-info">
                <div class="meta-item">ID: <strong>{{ $expense->id }}</strong></div>
                <div class="meta-item">Last Updated: <strong>
                    @if($expense->updated_at)
                        {{ $expense->updated_at->format('M d, Y - g:i A') }}
                    @else
                        Not updated
                    @endif
                </strong></div>
            </div>
        </div>
    </div>
</body>
</html>
