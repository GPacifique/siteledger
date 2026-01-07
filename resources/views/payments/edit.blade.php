<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment - SiteLedger</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .form-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-card h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        button[type="submit"],
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn {
            background: #95a5a6;
            color: white;
            display: inline-block;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .error {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="form-card">
            <h2>💰 Edit Payment</h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="project_id">Project *</label>
                        <select name="project_id" id="project_id" required>
                            <option value="">-- Select Project --</option>
                            @if(isset($projects) && $projects->count() > 0)
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $payment->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No projects available</option>
                            @endif
                        </select>
                        @error('project_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phase">Phase *</label>
                        <select name="phase" id="phase" required>
                            <option value="">-- Select Phase --</option>
                            <option value="design" {{ old('phase', $payment->phase) == 'design' ? 'selected' : '' }}>📝 Design Phase</option>
                            <option value="execution" {{ old('phase', $payment->phase) == 'execution' ? 'selected' : '' }}>🔨 Execution Phase</option>
                        </select>
                        @error('phase')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="employee_id">Worker</label>
                        <select name="employee_id" id="employee_id">
                            <option value="">-- Select Worker --</option>
                            @if(isset($employees) && $employees->count() > 0)
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $payment->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No workers available</option>
                            @endif
                        </select>
                        @error('employee_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (RWF) *</label>
                        <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount', $payment->amount) }}" required>
                        @error('amount')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_date">Payment Date *</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', $payment->payment_date) }}" required>
                        @error('payment_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="method">Payment Method</label>
                        <select name="method" id="method">
                            <option value="cash" {{ old('method', $payment->method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('method', $payment->method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ old('method', $payment->method) == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="mobile_money" {{ old('method', $payment->method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="credit_card" {{ old('method', $payment->method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit_card" {{ old('method', $payment->method) == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="wire_transfer" {{ old('method', $payment->method) == 'wire_transfer' ? 'selected' : '' }}>Wire Transfer</option>
                            <option value="paypal" {{ old('method', $payment->method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="crypto" {{ old('method', $payment->method) == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                            <option value="other" {{ old('method', $payment->method) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('method')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference">Reference Number</label>
                        <input type="text" name="reference" id="reference" placeholder="e.g., PAY-2024-001" value="{{ old('reference', $payment->reference) }}">
                        @error('reference')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" placeholder="Payment notes and details...">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">Update Payment</button>
                    <a href="{{ route('payments.show', $payment->id) }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
