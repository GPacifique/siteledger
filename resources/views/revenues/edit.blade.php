<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Revenue - CSMS</title>
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
            border-bottom: 2px solid #f39c12;
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
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
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
            <h2>💵 Edit Revenue</h2>

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

            <form action="{{ route('revenues.update', $income) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="project_id">Project *</label>
                        <select name="project_id" id="project_id" required>
                            <option value="">Select a project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $income->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount_received">Amount Received (RWF) *</label>
                        <input type="number" name="amount_received" id="amount_received" step="0.01" value="{{ old('amount_received', $income->amount_received) }}" required>
                        @error('amount_received')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select name="status" id="status" required>
                            <option value="received" {{ old('status', $income->status) == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="pending" {{ old('status', $income->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="received_at">Received Date *</label>
                        <input type="date" name="received_at" id="received_at" value="{{ old('received_at', $income->received_at ? \Carbon\Carbon::parse($income->received_at)->format('Y-m-d') : '') }}" required>
                        @error('received_at')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method">
                        <option value="">Select Payment Method</option>
                        <option value="Cash" {{ old('payment_method', $income->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank Transfer" {{ old('payment_method', $income->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Mobile Money" {{ old('payment_method', $income->payment_method) == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Check" {{ old('payment_method', $income->payment_method) == 'Check' ? 'selected' : '' }}>Check</option>
                        <option value="Credit Card" {{ old('payment_method', $income->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="Other" {{ old('payment_method', $income->payment_method) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('payment_method')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Add any notes or details about this revenue...">{{ old('description', $income->description) }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">Update Revenue</button>
                    <a href="{{ route('revenues.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
