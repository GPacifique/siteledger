<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add {{ ucfirst($phase) }} Phase Payment - CSMS</title>
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
            border-bottom: 2px solid {{ $phase === 'design' ? '#667eea' : '#27ae60' }};
            padding-bottom: 0.5rem;
        }
        .phase-summary {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid {{ $phase === 'design' ? '#667eea' : '#27ae60' }};
        }
        .phase-summary h3 {
            color: {{ $phase === 'design' ? '#667eea' : '#27ae60' }};
            margin-bottom: 1rem;
        }
        .phase-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .phase-stat {
            text-align: center;
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
            margin-top: 0.25rem;
        }
        .phase-stat .value.total { color: #333; }
        .phase-stat .value.paid { color: #27ae60; }
        .phase-stat .value.remaining { color: #dc3545; }
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
            border-color: {{ $phase === 'design' ? '#667eea' : '#27ae60' }};
            box-shadow: 0 0 0 3px {{ $phase === 'design' ? 'rgba(102, 126, 234, 0.1)' : 'rgba(39, 174, 96, 0.1)' }};
        }
        textarea {
            resize: vertical;
            min-height: 80px;
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
            .phase-stats {
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
            background: {{ $phase === 'design' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #27ae60 0%, #229954 100%)' }};
            color: white;
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px {{ $phase === 'design' ? 'rgba(102, 126, 234, 0.4)' : 'rgba(39, 174, 96, 0.4)' }};
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
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .project-info {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .project-info strong {
            color: #333;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="form-card">
            <h2>💳 Add {{ ucfirst($phase) }} Phase Payment</h2>

            <div class="project-info">
                <strong>Project:</strong> {{ $project->name }} |
                <strong>Client:</strong> {{ $project->client->name ?? 'N/A' }}
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
                        <div class="value paid">RWF {{ number_format($phasePaid, 2) }}</div>
                    </div>
                    <div class="phase-stat">
                        <div class="label">Remaining</div>
                        <div class="value remaining">RWF {{ number_format($phaseRemaining, 2) }}</div>
                    </div>
                </div>
            </div>

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

            <form action="{{ route('projects.phase-payments.store', $project) }}" method="POST">
                @csrf
                <input type="hidden" name="phase" value="{{ $phase }}">

                <div class="form-row">
                    <div class="form-group">
                        <label for="amount">Payment Amount (RWF) *</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                               value="{{ old('amount', $phaseRemaining > 0 ? $phaseRemaining : '') }}" required>
                        @error('amount')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payment_date">Payment Date *</label>
                        <input type="date" name="payment_date" id="payment_date"
                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method">
                            <option value="">Select method</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>Check</option>
                            <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference_number">Reference Number</label>
                        <input type="text" name="reference_number" id="reference_number"
                               placeholder="Invoice/Receipt number" value="{{ old('reference_number') }}">
                        @error('reference_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Payment Status</label>
                    <select name="status" id="status">
                        <option value="completed" {{ old('status', 'completed') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description / Notes</label>
                    <textarea name="description" id="description" placeholder="Additional notes about this payment...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">Save Payment</button>
                    <a href="{{ route('projects.show', $project) }}" class="btn">Cancel</a>
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

            // Format: PRJ-{projectId}-{PHASE}-{YYYYMMDD}-{RANDOM}
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const phaseCode = phase.substring(0, 3).toUpperCase(); // 'DES' or 'EXE'
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

            // Optional: Add a button to regenerate the reference number
            const generateBtn = document.createElement('button');
            generateBtn.type = 'button';
            generateBtn.textContent = '🔄 Generate';
            generateBtn.style.cssText = `
                margin-top: 0.5rem;
                padding: 0.5rem 1rem;
                background: #3498db;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.85rem;
                transition: all 0.3s ease;
            `;

            generateBtn.addEventListener('mouseover', function() {
                this.style.background = '#2980b9';
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 8px rgba(52, 152, 219, 0.3)';
            });

            generateBtn.addEventListener('mouseout', function() {
                this.style.background = '#3498db';
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });

            generateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                referenceInput.value = generateReferenceNumber();
            });

            // Insert button after the reference number input
            referenceInput.parentElement.appendChild(generateBtn);
        });
    </script>
</body>
</html>
