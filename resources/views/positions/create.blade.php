@extends('layouts.admin')

@section('title', 'Create Worker Position - SiteLedger')

@section('styles')<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}"><style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            color: #764ba2;
            transform: translateX(-4px);
        }
        .form-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(245, 247, 250, 0.95) 100%);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        .form-card h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #1a202c;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.75rem;
            color: #2d3748;
            font-weight: 700;
            font-size: 0.95rem;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            background: #ffffff;
            color: #1a202c;
            transition: all 0.3s ease;
        }
        input::placeholder,
        select::placeholder,
        textarea::placeholder {
            color: #cbd5e1;
        }
        input[type="text"]:hover,
        input[type="number"]:hover,
        select:hover,
        textarea:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: #ffffff;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(102, 126, 234, 0.05);
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s ease;
        }
        .checkbox-group input {
            width: auto;
            cursor: pointer;
        }
        .checkbox-group input:focus {
            accent-color: #667eea;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }
        .btn {
            flex: 1;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #cbd5e1;
            color: #1a202c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .btn-secondary:hover {
            background: #a0aec0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        .error {
            background: rgba(245, 25, 25, 0.08);
            color: #741c26;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #e53e3e;
        }
        .error strong {
            color: #e53e3e;
        }
        .error-list {
            list-style: none;
            padding: 0;
        }
        .error-list li {
            padding: 0.5rem 0;
        }
        .error-list li::before {
            content: '⚠ ';
            color: #e53e3e;
            margin-right: 0.5rem;
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <a href="{{ route('positions.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Positions</a>

        <div class="form-card">
            <h1>👔 Create Worker Position</h1>

            @if($errors->any())
                <div class="error">
                    <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('positions.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name"><i class="fas fa-briefcase"></i> Position Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Site Supervisor">
                </div>

                <div class="form-group">
                    <label for="description"><i class="fas fa-file-alt"></i> Description</label>
                    <textarea id="description" name="description" placeholder="Describe the role and responsibilities">{{ old('description') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category"><i class="fas fa-tag"></i> Category</label>
                        <select id="category" name="category">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="seniority_level"><i class="fas fa-medal"></i> Seniority Level</label>
                        <select id="seniority_level" name="seniority_level">
                            <option value="">-- Select Level --</option>
                            @foreach($seniorityLevels as $level => $label)
                                <option value="{{ $level }}" {{ old('seniority_level') == $level ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hourly_rate"><i class="fas fa-clock"></i> Hourly Rate (RWF)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" step="0.01" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="daily_rate"><i class="fas fa-sun"></i> Daily Rate (RWF)</label>
                        <input type="number" id="daily_rate" name="daily_rate" value="{{ old('daily_rate') }}" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span><i class="fas fa-check-circle"></i> Active Position</span>
                        </div>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create Position</button>
                    <a href="{{ route('positions.index') }}" class="btn btn-secondary"><i class="fas fa-times-circle"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
