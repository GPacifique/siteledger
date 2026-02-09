<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Company Payment - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            min-height: 100vh;
            color: #333;
            padding: 2rem 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .page-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .form-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            position: relative;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #f97316 50%, #ea580c 100%);
            border-radius: 16px 16px 0 0;
        }
        .form-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-header-icon {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .form-header h2 {
            font-size: 1.8rem;
            color: #1f2937;
            margin: 0;
        }
        .form-header p {
            font-size: 0.95rem;
            color: #6b7280;
            margin: 0;
        }
        .form-group {
            margin-bottom: 1.75rem;
        }
        .form-label-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        label {
            display: block;
            font-weight: 700;
            color: #1f2937;
            font-size: 0.95rem;
        }
        .required {
            color: #ef4444;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.95rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        input[type="text"]::placeholder,
        input[type="number"]::placeholder,
        textarea::placeholder {
            color: #9ca3af;
        }
        input:hover,
        select:hover,
        textarea:hover {
            border-color: #d1d5db;
            background: #ffffff;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #f59e0b;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
        }
        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .page-header h1 {
                font-size: 1.8rem;
            }
            .form-card {
                padding: 1.5rem;
            }
        }
        .button-group {
            display: flex;
            gap: 1.25rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
        }
        button[type="submit"] {
            flex: 1;
            padding: 1.125rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.35);
        }
        button[type="submit"]:active {
            transform: translateY(0);
        }
        .btn {
            flex: 1;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #374151;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1.125rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: 2px solid #d1d5db;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn i {
            margin-right: 0.75rem;
        }
        .btn:hover {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        .error {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .error::before {
            content: '⚠';
            font-size: 1rem;
        }
        .alert {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            animation: slideIn 0.3s ease;
        }
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 2px solid #fca5a5;
            border-left: 4px solid #dc2626;
        }
        .alert strong {
            display: block;
            margin-bottom: 0.75rem;
            font-size: 1.05rem;
        }
        .alert ul {
            margin-left: 1.75rem;
            margin-top: 0.5rem;
        }
        .alert li {
            margin-bottom: 0.35rem;
        }
        .reference-input-group {
            display: flex;
            gap: 0.75rem;
            align-items: flex-end;
        }
        .reference-input-group input {
            flex: 1;
        }
        .reference-input-group button {
            padding: 0.95rem 1.25rem;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            height: auto;
            white-space: nowrap;
        }
        .reference-input-group button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .field-icon {
            color: #f59e0b;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-money-bill-wave"></i> Record Payment</h1>
            <p>Add and track company payments</p>
        </div>

        <div class="form-card">
            <div class="form-header">
                <div class="form-header-icon"><i class="fas fa-credit-card"></i></div>
                <div>
                    <h2>Payment Information</h2>
                    <p>Enter payment details below</p>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <!-- Amount and Date -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="amount">
                                <i class="fas fa-calculator field-icon"></i>
                                Amount (RWF) <span class="required">*</span>
                            </label>
                        </div>
                        <input type="number" name="amount" id="amount" step="0.01" placeholder="Enter amount" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="payment_date">
                                <i class="fas fa-calendar-alt field-icon"></i>
                                Payment Date <span class="required">*</span>
                            </label>
                        </div>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Category and Method -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="category">
                                <i class="fas fa-tags field-icon"></i>
                                Category
                            </label>
                        </div>
                        <select name="category" id="category">
                            <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>🔌 Utilities (Electric, Water, Internet)</option>
                            <option value="rent" {{ old('category') == 'rent' ? 'selected' : '' }}>🏢 Rent</option>
                            <option value="insurance" {{ old('category') == 'insurance' ? 'selected' : '' }}>🛡️ Insurance</option>
                            <option value="office_supplies" {{ old('category') == 'office_supplies' ? 'selected' : '' }}>📎 Office Supplies</option>
                            <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>💻 Software & Subscriptions</option>
                            <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance & Repairs</option>
                            <option value="taxes" {{ old('category') == 'taxes' ? 'selected' : '' }}>📋 Taxes & Licenses</option>
                            <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>✈️ Travel & Transport</option>
                            <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>📢 Marketing & Advertising</option>
                            <option value="professional_services" {{ old('category') == 'professional_services' ? 'selected' : '' }}>👔 Professional Services</option>
                            <option value="other" {{ old('category', 'other') == 'other' ? 'selected' : '' }}>📦 Other</option>
                        </select>
                        @error('category')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="method">
                                <i class="fas fa-wallet field-icon"></i>
                                Payment Method
                            </label>
                        </div>
                        <select name="method" id="method">
                            <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                            <option value="check" {{ old('method') == 'check' ? 'selected' : '' }}>📄 Check</option>
                            <option value="mobile_money" {{ old('method') == 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money</option>
                            <option value="credit_card" {{ old('method') == 'credit_card' ? 'selected' : '' }}>💳 Credit Card</option>
                            <option value="debit_card" {{ old('method') == 'debit_card' ? 'selected' : '' }}>💳 Debit Card</option>
                            <option value="wire_transfer" {{ old('method') == 'wire_transfer' ? 'selected' : '' }}>💸 Wire Transfer</option>
                            <option value="paypal" {{ old('method') == 'paypal' ? 'selected' : '' }}>💻 PayPal</option>
                            <option value="crypto" {{ old('method') == 'crypto' ? 'selected' : '' }}>₿ Cryptocurrency</option>
                            <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>🔹 Other</option>
                        </select>
                        @error('method')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Reference Number -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="reference">
                            <i class="fas fa-barcode field-icon"></i>
                            Reference Number
                        </label>
                    </div>
                    <div class="reference-input-group">
                        <input type="text" name="reference" id="reference" placeholder="e.g., PAY-2026-ABC123" value="{{ old('reference') }}">
                        <button type="button" onclick="generateReference()"><i class="fas fa-magic"></i> Generate</button>
                    </div>
                    @error('reference')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="notes">
                            <i class="fas fa-pen-fancy field-icon"></i>
                            Notes
                        </label>
                    </div>
                    <textarea name="notes" id="notes" placeholder="✍️ Add payment notes and details (optional)...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit"><i class="fas fa-save"></i> Save Payment</button>
                    <a href="{{ route('payments.index') }}" class="btn"><i class="fas fa-arrow-left"></i> Cancel</a>
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
