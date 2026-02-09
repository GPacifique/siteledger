<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add {{ ucfirst($phase) }} Phase Payment - SiteLedger</title>
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
            background: linear-gradient(135deg, {{ $phase === 'design' ? '#667eea 0%, #764ba2' : '#10b981 0%, #059669' }} 100%);
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
            background: linear-gradient(90deg, {{ $phase === 'design' ? '#667eea 0%, #764ba2 50%, #667eea' : '#10b981 0%, #059669 50%, #10b981' }} 100%);
            border-radius: 16px 16px 0 0;
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
            border-color: {{ $phase === 'design' ? '#667eea' : '#10b981' }};
            background: #ffffff;
            box-shadow: 0 0 0 4px {{ $phase === 'design' ? 'rgba(102, 126, 234, 0.15)' : 'rgba(16, 185, 129, 0.15)' }};
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
            .phase-stats {
                grid-template-columns: 1fr !important;
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
            background: linear-gradient(135deg, {{ $phase === 'design' ? '#667eea 0%, #764ba2' : '#10b981 0%, #059669' }} 100%);
            color: white;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px {{ $phase === 'design' ? 'rgba(102, 126, 234, 0.2)' : 'rgba(16, 185, 129, 0.2)' }};
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px {{ $phase === 'design' ? 'rgba(102, 126, 234, 0.35)' : 'rgba(16, 185, 129, 0.35)' }};
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
        .project-info {
            background: linear-gradient(135deg, #e8f4fd 0%, #e0f2fe 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #06b6d4;
            font-weight: 500;
            color: #0c4a6e;
        }
        .phase-summary {
            background: linear-gradient(135deg, {{ $phase === 'design' ? '#f0efff 0%, #e8e5ff' : '#f0fdf4 0%, #ecfdf5' }} 100%);
            padding: 1.75rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid {{ $phase === 'design' ? '#667eea' : '#10b981' }};
        }
        .phase-summary h3 {
            color: {{ $phase === 'design' ? '#667eea' : '#10b981' }};
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 700;
        }
        .phase-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .phase-stat {
            text-align: center;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
        }
        .phase-stat .label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .phase-stat .value {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        .phase-stat .value.total { color: #333; }
        .phase-stat .value.paid { color: #10b981; }
        .phase-stat .value.remaining { color: #dc2626; }
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
        .gen-btn {
            margin-top: 0.5rem;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, {{ $phase === 'design' ? '#667eea' : '#10b981' }} 0%, {{ $phase === 'design' ? '#764ba2' : '#059669' }} 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        .gen-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px {{ $phase === 'design' ? 'rgba(102, 126, 234, 0.3)' : 'rgba(16, 185, 129, 0.3)' }};
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-credit-card"></i> {{ ucfirst($phase) }} Phase Payment</h1>
            <p>Record payment for project phase</p>
        </div>

        <div class="form-card">
            <div class="project-info">
                <strong>📁 Project:</strong> {{ $project->name }} | <strong>👤 Client:</strong> {{ $project->client->name ?? 'N/A' }}
            </div>

            <div class="phase-summary">
                <h3>{{ ucfirst($phase) }} Phase Summary</h3>
                <div class="phase-stats">
                    <div class="phase-stat">
                        <div class="label">Phase Value</div>
                        <div class="value total">RWF {{ number_format($phaseValue, 2) }}</div>
                    </div>
                    <div class="phase-stat">
                        <div class="label">Already Paid</div>
                        <div class="value paid">✅ RWF {{ number_format($phasePaid, 2) }}</div>
                    </div>
                    <div class="phase-stat">
                        <div class="label">Remaining</div>
                        <div class="value remaining">⏳ RWF {{ number_format($phaseRemaining, 2) }}</div>
                    </div>
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

            <form action="{{ route('projects.phase-payments.store', $project) }}" method="POST">
                @csrf
                <input type="hidden" name="phase" value="{{ $phase }}">

                <!-- Amount and Date -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="amount">
                            <i class="fas fa-money-bill"></i> Payment Amount (RWF) <span class="required">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                               value="{{ old('amount', $phaseRemaining > 0 ? $phaseRemaining : '') }}" required>
                        @error('amount')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payment_date">
                            <i class="fas fa-calendar-alt"></i> Payment Date <span class="required">*</span>
                        </label>
                        <input type="date" name="payment_date" id="payment_date"
                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method and Reference -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_method">
                            <i class="fas fa-wallet"></i> Payment Method
                        </label>
                        <select name="payment_method" id="payment_method">
                            <option value="">💳 Select method</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                            <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>📄 Check</option>
                            <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>🔹 Other</option>
                        </select>
                        @error('payment_method')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference_number">
                            <i class="fas fa-barcode"></i> Reference Number
                        </label>
                        <input type="text" name="reference_number" id="reference_number"
                               placeholder="Invoice/Receipt number" value="{{ old('reference_number') }}">
                        @error('reference_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">
                        <i class="fas fa-check-circle"></i> Payment Status
                    </label>
                    <select name="status" id="status">
                        <option value="completed" {{ old('status', 'completed') === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-pen-fancy"></i> Description / Notes
                    </label>
                    <textarea name="description" id="description" placeholder="✍️ Additional notes about this payment...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit"><i class="fas fa-save"></i> Save Payment</button>
                    <a href="{{ route('projects.show', $project) }}" class="btn"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-generate reference number
        function generateReferenceNumber() {
            const projectId = {{ $project->id }};
            const phase = '{{ $phase }}';
            const date = new Date();

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const phaseCode = phase.substring(0, 3).toUpperCase();
            const randomNum = Math.floor(Math.random() * 10000).toString().padStart(4, '0');

            const refNumber = `PRJ-${projectId}-${phaseCode}-${year}${month}${day}-${randomNum}`;
            return refNumber;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const referenceInput = document.getElementById('reference_number');

            // Generate reference number on page load if field is empty
            if (!referenceInput.value) {
                referenceInput.value = generateReferenceNumber();
            }

            // Add regenerate button
            const generateBtn = document.createElement('button');
            generateBtn.type = 'button';
            generateBtn.className = 'gen-btn';
            generateBtn.innerHTML = '<i class="fas fa-magic"></i> Generate Reference';

            generateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                referenceInput.value = generateReferenceNumber();
            });

            referenceInput.parentElement.appendChild(generateBtn);
        });
    </script>
</body>
</html>
