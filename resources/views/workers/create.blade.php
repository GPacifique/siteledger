<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Worker - CSMS</title>
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
        input[type="email"],
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
            <h2>👷 Add New Worker</h2>

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

            <form action="{{ route('workers.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="position">Position</label>
                        <select name="position" id="position">
                            <option value="">Select Position</option>
                            <option value="Engineer" {{ old('position') == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                            <option value="Architect" {{ old('position') == 'Architect' ? 'selected' : '' }}>Architect</option>
                            <option value="MEP" {{ old('position') == 'MEP' ? 'selected' : '' }}>MEP (Mechanical, Electrical, Plumbing)</option>
                            <option value="Dealer" {{ old('position') == 'Dealer' ? 'selected' : '' }}>Dealer</option>
                            <option value="Technician" {{ old('position') == 'Technician' ? 'selected' : '' }}>Technician</option>
                            <option value="Casual Labor" {{ old('position') == 'Casual Labor' ? 'selected' : '' }}>Casual Labor</option>
                            <option value="Mason" {{ old('position') == 'Mason' ? 'selected' : '' }}>Mason</option>
                            <option value="Carpenter" {{ old('position') == 'Carpenter' ? 'selected' : '' }}>Carpenter</option>
                            <option value="Electrician" {{ old('position') == 'Electrician' ? 'selected' : '' }}>Electrician</option>
                            <option value="Plumber" {{ old('position') == 'Plumber' ? 'selected' : '' }}>Plumber</option>
                            <option value="Foreman" {{ old('position') == 'Foreman' ? 'selected' : '' }}>Foreman</option>
                            <option value="Other" {{ old('position') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('position')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" name="status" id="status" value="{{ old('status') }}" placeholder="e.g., active">
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="salary">Monthly Salary (optional)</label>
                        <input type="number" name="salary" id="salary" step="0.01" value="{{ old('salary') }}" placeholder="e.g., 50000">
                        @error('salary')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="currency">Currency (3-letter code)</label>
                        <input type="text" name="currency" id="currency" value="{{ old('currency', 'RWF') }}" maxlength="3">
                        @error('currency')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hired_at">Hire Date</label>
                        <input type="date" name="hired_at" id="hired_at" value="{{ old('hired_at') }}">
                        @error('hired_at')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone_alt">Alternate Contact (optional)</label>
                        <input type="text" name="phone_alt" id="phone_alt" value="{{ old('phone_alt') }}" placeholder="Alternate phone or contact">
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" placeholder="Additional information about the worker...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">Save Worker</button>
                    <a href="{{ route('workers.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
