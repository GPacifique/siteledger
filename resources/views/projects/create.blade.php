<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - SiteLedger</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(120deg, #f5f7fa, #e9ecef);
            color: #222;
        }

        .container {
            max-width: 760px;
            margin: 3rem auto;
            padding: 1.5rem;
        }

        .card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(102,126,234,0.12);
        }

        h2 {
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #667eea22;
            padding-bottom: 0.6rem;
        }

        h3 {
            margin: 2rem 0 1.2rem;
            color: #333;
            font-size: 1.2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.4rem;
        }

        .form-group {
            margin-bottom: 1.4rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.45rem;
            color: #444;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1.5px solid #e0e4ea;
            border-radius: 6px;
            background: #f8fafd;
            font-size: 1rem;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px #667eea33;
            background: #fff;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.2rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        button {
            padding: 0.8rem 2rem;
            border-radius: 7px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-size: 1.05rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(102,126,234,0.25);
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .alert {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>

@include('components.navbar')

<div class="container">
    <div class="card">
        <h2>📁 Add New Project</h2>

        @if($errors->any())
            <div class="alert">
                <strong>Please fix the following errors:</strong>
                <ul style="margin-top:0.5rem; margin-left:1.3rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <!-- Project Information -->
            <h3>📌 Project Information</h3>

            <div class="row">
                <div class="form-group">
                    <label>Company Client (optional)</label>
                    <select name="client_id">
                        <option value="">Select a company</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Project Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Project Code (optional)</label>
                <input type="text" name="project_code" placeholder="PRJ-001" value="{{ old('project_code') }}">
            </div>

            <div class="form-group">
                <label>Description (optional)</label>
                <textarea name="description">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Total Contract Value (RWF) *</label>
                    <input type="number" name="contract_value" step="0.01" required value="{{ old('contract_value') }}">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Select status</option>
                        <option value="planning">Planning</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                </div>
            </div>

            <!-- Project Phases -->
            <h3>📐 Project Phases</h3>

            <div class="row">
                <div class="form-group">
                    <label>Project Type *</label>
                    <select name="project_type" required>
                        <option value="">Select</option>
                        <option value="DESIGN">Design Only</option>
                        <option value="EXECUTION">Execution Only</option>
                        <option value="DESIGN_EXECUTION">Design & Execution</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Current Phase</label>
                    <select name="current_phase">
                        <option value="">Select</option>
                        <option value="design">Design</option>
                        <option value="execution">Execution</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Design Phase Status</label>
                <select name="design_phase_status">
                    <option value="">Select</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="btn-group">
                <button type="submit" class="btn-primary">Save Project</button>
                <a href="{{ route('projects.index') }}" class="btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
