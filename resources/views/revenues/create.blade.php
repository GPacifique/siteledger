<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Revenue - SiteLedger</title>
    <!-- Colorful Theme CSS -->
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            backdrop-filter: blur(10px);
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #06b6d4 50%, #f59e0b 100%);
            border-radius: 16px 16px 0 0;
        }
        .form-card {
            position: relative;
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
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
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
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        select:focus {
            border-color: #667eea;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
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
            color: #667eea;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-coins"></i> Add New Revenue</h1>
            <p>Record and track revenue for your projects</p>
        </div>

        <div class="form-card">
            <div class="form-header">
                <div class="form-header-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div>
                    <h2>Revenue Details</h2>
                    <p>Fill in the revenue information below</p>
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

            <form action="{{ route('revenues.store') }}" method="POST">
                @csrf

                <!-- Project Selection -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="project_id">
                                <i class="fas fa-briefcase field-icon"></i>
                                Project <span class="required">*</span>
                            </label>
                        </div>
                        <select name="project_id" id="project_id" required>
                            <option value="">📋 Select a project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="amount_received">
                                <i class="fas fa-money-bill-wave field-icon"></i>
                                Amount Received (RWF) <span class="required">*</span>
                            </label>
                        </div>
                        <input type="number" name="amount_received" id="amount_received" step="0.01" placeholder="Enter amount" value="{{ old('amount_received') }}" required>
                        @error('amount_received')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status and Date -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="status">
                                <i class="fas fa-check-circle field-icon"></i>
                                Status <span class="required">*</span>
                            </label>
                        </div>
                        <select name="status" id="status" required>
                            <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>✅ Received</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="received_at">
                                <i class="fas fa-calendar-alt field-icon"></i>
                                Received Date <span class="required">*</span>
                            </label>
                        </div>
                        <input type="date" name="received_at" id="received_at" value="{{ old('received_at', date('Y-m-d')) }}" required>
                        @error('received_at')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="payment_method">
                            <i class="fas fa-credit-card field-icon"></i>
                            Payment Method
                        </label>
                    </div>
                    <select name="payment_method" id="payment_method">
                        <option value="">💳 Select Payment Method</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>📱 Mobile Money</option>
                        <option value="Check" {{ old('payment_method') == 'Check' ? 'selected' : '' }}>📄 Check</option>
                        <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>💳 Credit Card</option>
                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>🔹 Other</option>
                    </select>
                    @error('payment_method')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="description">
                            <i class="fas fa-pen-fancy field-icon"></i>
                            Description
                        </label>
                    </div>
                    <textarea name="description" id="description" placeholder="✍️ Add any notes or details about this revenue (optional)...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit"><i class="fas fa-save"></i> Save Revenue</button>
                    <a href="{{ route('revenues.index') }}" class="btn"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
