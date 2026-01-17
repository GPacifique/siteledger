<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - SiteLedger</title>
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
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="form-card">
            <h2>📁 Add New Project</h2>

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

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="client_id">Company Client *</label>
                        <select name="client_id" id="client_id" required>
                            <option value="">Select a company client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    Company: {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Project Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="project_code">Project Code</label>
                    <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                        <input type="text" name="project_code" id="project_code" placeholder="e.g., PRJ-001" value="{{ old('project_code') }}" style="flex: 1;">
                        <button type="button" id="generateCodeBtn" style="padding: 0.75rem 1rem; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Generate</button>
                    </div>
                    @error('project_code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Project description and details...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $defaultStartDate = now()->addMonth()->format('Y-m-d');
                    $defaultEndDate = now()->addMonth()->format('Y-m-d');
                @endphp
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $defaultStartDate) }}">
                        @error('start_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $defaultEndDate) }}">
                        @error('end_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phase_type">Project Type *</label>
                        <select name="phase_type" id="phase_type" required>
                            <option value="">-- Select Project Type --</option>
                            <option value="design_only" {{ old('phase_type') == 'design_only' ? 'selected' : '' }}>Design Only</option>
                            <option value="execution_only" {{ old('phase_type') == 'execution_only' ? 'selected' : '' }}>Execution Only</option>
                            <option value="both" {{ old('phase_type') == 'both' ? 'selected' : '' }}>Both (Design & Execution)</option>
                        </select>
                        <small style="color: #666; font-size: 0.85rem;">Select the type of project. Amounts for each phase are entered manually and are not auto-calculated.</small>
                    </div>

                    <div class="form-group">
                        <label for="contract_value">Total Contract Value (RWF)</label>
                        <input type="number" name="contract_value" id="contract_value" step="0.01" value="{{ old('contract_value') }}">
                        @error('contract_value')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Project Phases Section -->
                <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 1.5rem; color: #333; font-size: 1.2rem; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">📐 Project Phases</h3>

                    <div class="form-group">
                        <label for="current_phase">Current Phase</label>
                        <select name="current_phase" id="current_phase">
                            <option value="">-- Select --</option>
                            <option value="design" {{ old('current_phase') == 'design' ? 'selected' : '' }}>Design</option>
                            <option value="execution" {{ old('current_phase') == 'execution' ? 'selected' : '' }}>Execution</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="design_phase_value">Design Phase Value (RWF)</label>
                            <input type="number" name="design_phase_value" id="design_phase_value" step="0.01" value="{{ old('design_phase_value') }}">
                            @error('design_phase_value')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="design_phase_status">Design Phase Status</label>
                            <select name="design_phase_status" id="design_phase_status">
                                <option value="pending" {{ old('design_phase_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('design_phase_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('design_phase_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="design_start_date">Design Start Date</label>
                            <input type="date" name="design_start_date" id="design_start_date" value="{{ old('design_start_date', $defaultStartDate) }}">
                        </div>
                        <div class="form-group">
                            <label for="design_end_date">Design End Date</label>
                            <input type="date" name="design_end_date" id="design_end_date" value="{{ old('design_end_date') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="execution_phase_value">Execution Phase Value (RWF)</label>
                            <input type="number" name="execution_phase_value" id="execution_phase_value" step="0.01" value="{{ old('execution_phase_value') }}">
                            @error('execution_phase_value')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="execution_phase_status">Execution Phase Status</label>
                            <select name="execution_phase_status" id="execution_phase_status">
                                <option value="pending" {{ old('execution_phase_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('execution_phase_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('execution_phase_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="execution_start_date">Execution Start Date</label>
                            <input type="date" name="execution_start_date" id="execution_start_date" value="{{ old('execution_start_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="execution_end_date">Execution End Date</label>
                            <input type="date" name="execution_end_date" id="execution_end_date" value="{{ old('execution_end_date', $defaultEndDate) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="manager_id">Project Manager (Worker)</label>
                    <select name="manager_id" id="manager_id">
                        <option value="">Select a manager</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ old('manager_id') == $worker->id ? 'selected' : '' }}>
                                {{ $worker->first_name }} {{ $worker->last_name }} ({{ $worker->position ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">Save Project</button>
                    <a href="{{ route('projects.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generateProjectCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = 'PRJ-';
            for (let i = 0; i < 6; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('project_code').value = code;
        }

        document.getElementById('generateCodeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            generateProjectCode();
        });

        // Auto-generate code on page load if not already set
        window.addEventListener('load', function() {
            const codeField = document.getElementById('project_code');
            if (!codeField.value) {
                generateProjectCode();
            }
        });
    </script>
</body>
</html>
