<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment - SiteLedger</title>
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
        .reference-input-group {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }
        .reference-input-group input {
            flex: 1;
        }
        .reference-input-group button {
            padding: 0.75rem 1rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .reference-input-group button:hover {
            background: #5568d3;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="form-card">
            <h2>💰 Add New Payment</h2>

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

            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="project_id">Project *</label>
                        <select name="project_id" id="project_id" required>
                            <option value="">-- Select Project --</option>
                            @if(isset($projects) && $projects->count() > 0)
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                            <option value="design" {{ old('phase') == 'design' ? 'selected' : '' }}>📝 Design Phase</option>
                            <option value="execution" {{ old('phase') == 'execution' ? 'selected' : '' }}>🔨 Execution Phase</option>
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
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
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
                        <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_date">Payment Date *</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="method">Payment Method</label>
                        <select name="method" id="method">
                            <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ old('method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="mobile_money" {{ old('method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="credit_card" {{ old('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit_card" {{ old('method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="wire_transfer" {{ old('method') == 'wire_transfer' ? 'selected' : '' }}>Wire Transfer</option>
                            <option value="paypal" {{ old('method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="crypto" {{ old('method') == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                            <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('method')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="reference">Reference Number</label>
                    <div class="reference-input-group">
                        <input type="text" name="reference" id="reference" placeholder="e.g., PAY-2024-001" value="{{ old('reference') }}">
                        <button type="button" onclick="generateReference()">Generate</button>
                    </div>
                    @error('reference')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" placeholder="Payment notes and details...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">Save Payment</button>
                    <a href="{{ route('payments.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Generate payment reference number
        function generateReference() {
            const year = new Date().getFullYear();
            const randomStr = Math.random().toString(36).substring(2, 8).toUpperCase();
            const reference = `PAY-${year}-${randomStr}`;
            document.getElementById('reference').value = reference;
        }

        // Auto-generate reference on page load
        document.addEventListener('DOMContentLoaded', function() {
            const referenceField = document.getElementById('reference');
            if (!referenceField.value) {
                generateReference();
            }
        });
    </script>
</body>
</html>
