@extends('layouts.admin')

@section('title', 'Add Worker - SiteLedger')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
<style>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .form-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(245, 247, 250, 0.95) 100%);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        .form-card h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #1a202c;
            border-bottom: 3px solid #667eea;
            padding-bottom: 0.75rem;
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
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #2d3748;
            font-size: 0.95rem;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            background: #ffffff;
            color: #1a202c;
            transition: all 0.3s ease;
        }
        input::placeholder,
        select::placeholder,
        textarea::placeholder {
            color: #cbd5e1;
        }
        input:hover,
        select:hover,
        textarea:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: #ffffff;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }
        button[type="submit"],
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            flex: 1;
        }
        button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .btn {
            background: #cbd5e1;
            color: #1a202c;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .btn:hover {
            background: #a0aec0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        .error {
            color: #e53e3e;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .error::before {
            content: '⚠';
        }
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #e53e3e;
        }
        .alert-danger {
            background: rgba(245, 25, 25, 0.08);
            color: #741c26;
        }
        .alert-danger strong {
            color: #e53e3e;
        }
    </style>
@endsection

@section('content')
    <div class="form-card">
            <h2>👷 Add New Worker</h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                    <ul style="margin-top: 0.75rem; margin-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('workers.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="project_id"><i class="fas fa-project-diagram"></i> Assign to Project</label>
                    <select name="project_id" id="project_id">
                        <option value="">-- None --</option>
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name"><i class="fas fa-user"></i> First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name"><i class="fas fa-user"></i> Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="position"><i class="fas fa-briefcase"></i> Position</label>
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="daily_wage"><i class="fas fa-dollar-sign"></i> Daily Wage (RWF) *</label>
                        <input type="number" name="daily_wage" id="daily_wage" step="0.01" value="{{ old('daily_wage') }}" placeholder="e.g., 5000" required>
                        @error('daily_wage')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hired_at"><i class="fas fa-calendar-alt"></i> Hire Date</label>
                        <input type="date" name="hired_at" id="hired_at" value="{{ old('hired_at') }}">
                        @error('hired_at')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_alt"><i class="fas fa-phone"></i> Alternate Contact (optional)</label>
                        <input type="text" name="phone_alt" id="phone_alt" value="{{ old('phone_alt') }}" placeholder="Alternate phone or contact">
                    </div>

                    <div class="form-group">
                        <label for="status"><i class="fas fa-toggle-on"></i> Status</label>
                        <select name="status" id="status">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes"><i class="fas fa-sticky-note"></i> Notes</label>
                    <textarea name="notes" id="notes" placeholder="Additional information about the worker...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit"><i class="fas fa-check-circle"></i> Save Worker</button>
                    <a href="{{ route('workers.index') }}" class="btn"><i class="fas fa-times-circle"></i> Cancel</a>
                </div>
            </form>
        </div>
@endsection
