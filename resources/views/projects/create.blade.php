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
                        <label for="client_id">Client *</label>
                        <select name="client_id" id="client_id" required>
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
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
                        <label for="contract_value">Contract Value (RWF)</label>
                        <input type="number" name="contract_value" id="contract_value" step="0.01" value="{{ old('contract_value') }}">
                        @error('contract_value')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phase_type">Project Phase Type</label>
                        <select name="phase_type" id="phase_type">
                            <option value="">-- Select --</option>
                            <option value="design_only" {{ old('phase_type') == 'design_only' ? 'selected' : '' }}>Design Only</option>
                            <option value="both" {{ old('phase_type') == 'both' ? 'selected' : '' }}>Both Phases (Design & Execution)</option>
                        </select>
                        <small style="color: #666; font-size: 0.85rem;">Optional: Choose if this project is only for design or for both phases.</small>
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
                            <option value="design" {{ old('current_phase') == 'design' ? 'selected' : '' }}>Design Phase</option>
                            <option value="execution" {{ old('current_phase') == 'execution' ? 'selected' : '' }}>Execution Phase</option>
                        </select>
                    </div>

                    <!-- Design Phase -->
                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: white; border-radius: 6px; border-left: 3px solid #667eea;">
                        <h4 style="color: #667eea; margin-bottom: 1rem;">📝 Design Phase</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="design_phase_value">Design Phase Value (RWF)</label>
                                <input type="number" name="design_phase_value" id="design_phase_value" step="0.01" value="{{ old('design_phase_value', 0) }}">
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
                    </div>

                    <!-- Execution Phase -->
                    <div style="padding: 1rem; background: white; border-radius: 6px; border-left: 3px solid #27ae60;">
                        <h4 style="color: #27ae60; margin-bottom: 1rem;">🔨 Execution Phase</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="execution_phase_value">Execution Phase Value (RWF)</label>
                                <input type="number" name="execution_phase_value" id="execution_phase_value" step="0.01" value="{{ old('execution_phase_value', 0) }}">
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
                                <small style="color: #666; font-size: 0.8rem;">Auto-fills when Design End Date is set</small>
                            </div>
                            <div class="form-group">
                                <label for="execution_end_date">Execution End Date</label>
                                <input type="date" name="execution_end_date" id="execution_end_date" value="{{ old('execution_end_date', $defaultEndDate) }}">
                                <small style="color: #666; font-size: 0.8rem;">Defaults to Project End Date</small>
                            </div>
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

        // Auto-calculate design and execution phase values from contract value
        const contractValueInput = document.getElementById('contract_value');
        const designPhaseInput = document.getElementById('design_phase_value');
        const executionPhaseInput = document.getElementById('execution_phase_value');

        // Default split: 30% Design, 70% Execution
        const DESIGN_PERCENTAGE = 0.30;
        const EXECUTION_PERCENTAGE = 0.70;

        function calculatePhaseValues() {
            const contractValue = parseFloat(contractValueInput.value) || 0;

            if (contractValue > 0) {
                const designValue = contractValue * DESIGN_PERCENTAGE;
                const executionValue = contractValue * EXECUTION_PERCENTAGE;

                designPhaseInput.value = designValue.toFixed(2);
                executionPhaseInput.value = executionValue.toFixed(2);

                // Show calculation info
                showCalculationInfo(contractValue, designValue, executionValue);
            }
        }

        function showCalculationInfo(contract, design, execution) {
            let infoDiv = document.getElementById('calculationInfo');

            if (!infoDiv) {
                infoDiv = document.createElement('div');
                infoDiv.id = 'calculationInfo';
                infoDiv.style.cssText = 'margin-top: 0.5rem; padding: 0.75rem; background: #e8f4f8; border-left: 3px solid #3498db; border-radius: 4px; font-size: 0.9rem; color: #2c3e50;';
                contractValueInput.parentElement.appendChild(infoDiv);
            }

            infoDiv.innerHTML = `
                <strong>📊 Calculated Split:</strong><br>
                Design Phase (30%): RWF ${design.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}<br>
                Execution Phase (70%): RWF ${execution.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
            `;
        }

        // Listen for changes in contract value
        contractValueInput.addEventListener('input', calculatePhaseValues);
        contractValueInput.addEventListener('change', calculatePhaseValues);

        // Allow manual editing of phase values by removing auto-calc when user manually changes them
        let userEditedDesign = false;
        let userEditedExecution = false;

        designPhaseInput.addEventListener('focus', function() {
            this.dataset.originalValue = this.value;
        });

        designPhaseInput.addEventListener('change', function() {
            if (this.value !== this.dataset.originalValue) {
                userEditedDesign = true;
            }
        });

        executionPhaseInput.addEventListener('focus', function() {
            this.dataset.originalValue = this.value;
        });

        executionPhaseInput.addEventListener('change', function() {
            if (this.value !== this.dataset.originalValue) {
                userEditedExecution = true;
            }
        });

        // Reset user edit flags when contract value changes significantly
        contractValueInput.addEventListener('input', function() {
            userEditedDesign = false;
            userEditedExecution = false;
        });

        // Auto-sync dates between phases
        const designEndDateInput = document.getElementById('design_end_date');
        const executionStartDateInput = document.getElementById('execution_start_date');
        const executionEndDateInput = document.getElementById('execution_end_date');
        const projectEndDateInput = document.getElementById('end_date');

        // When Design End Date changes, set Execution Start Date to match
        designEndDateInput.addEventListener('change', function() {
            if (this.value && !executionStartDateInput.value) {
                executionStartDateInput.value = this.value;
            }
        });

        // When Project End Date changes, update Execution End Date
        projectEndDateInput.addEventListener('change', function() {
            if (this.value) {
                executionEndDateInput.value = this.value;
            }
        });
    </script>
</body>
</html>
