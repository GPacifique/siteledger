<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - CSMS</title>
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
        .navbar a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .navbar a:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-button:hover {
            text-decoration: underline;
        }
        .detail-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .detail-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #999;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .detail-value {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .amount-large {
            font-size: 2rem;
            color: #27ae60;
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            width: fit-content;
        }
        .badge.completed {
            background: #d4edda;
            color: #155724;
        }
        .badge.pending {
            background: #fff3cd;
            color: #856404;
        }
        .badge.failed {
            background: #f8d7da;
            color: #721c24;
        }
        .divider {
            border: 1px solid #f0f0f0;
            margin: 2rem 0;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
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
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="detail-card">
            <h2>Payment Information</h2>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Payment Amount</span>
                    <span class="amount-large">RWF {{ number_format($payment->amount ?? 0, 2) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    @php
                        $statusClass = match($payment->status ?? 'completed') {
                            'completed' => 'completed',
                            'pending' => 'pending',
                            'failed' => 'failed',
                            default => 'completed'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($payment->status ?? 'Completed') }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Payment Type</span>
                    <span class="detail-value">{{ $payment->type ?? 'Transfer' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Date</span>
                    <span class="detail-value">{{ $payment->created_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">🏗️ Project</span>
                    <span class="detail-value">
                        @if($payment->project)
                            <a href="{{ route('projects.show', $payment->project) }}" style="color: #27ae60; text-decoration: none; font-weight: 600;">
                                {{ $payment->project->name }}
                            </a>
                        @else
                            <span style="color: #999;">No project assigned</span>
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">📋 Phase</span>
                    <span class="detail-value">
                        @if($payment->phase)
                            @if($payment->phase === 'design')
                                <span style="background: #9b59b6; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                    📝 Design Phase
                                </span>
                            @else
                                <span style="background: #f39c12; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                    🔨 Execution Phase
                                </span>
                            @endif
                        @else
                            <span style="color: #999;">No phase specified</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Reference Number</span>
                    <span class="detail-value">{{ $payment->reference ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">{{ $payment->method ?? 'Unknown' }}</span>
                </div>
            </div>

            @if($payment->employee)
                <div class="divider"></div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Recipient (Worker)</span>
                        <span class="detail-value">{{ $payment->employee->first_name ?? '' }} {{ $payment->employee->last_name ?? '' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Recipient Email</span>
                        <span class="detail-value">{{ $payment->employee->email ?? 'N/A' }}</span>
                    </div>
                </div>
            @endif

            @if($payment->description)
                <div class="divider"></div>
                <div class="detail-row">
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <span class="detail-label">Description</span>
                        <span class="detail-value">{{ $payment->description }}</span>
                    </div>
                </div>
            @endif

            <div class="divider"></div>
            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ $payment->created_at?->format('M d, Y H:i:s') ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">{{ $payment->updated_at?->format('M d, Y H:i:s') ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">← Back to Payments</a>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-primary">✏️ Edit</a>
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this payment?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
