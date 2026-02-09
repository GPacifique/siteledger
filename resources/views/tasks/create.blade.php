<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task - SiteLedger</title>
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
            position: relative;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            border-radius: 16px 16px 0 0;
        }
        .form-section {
            margin-bottom: 2.5rem;
            padding: 1.75rem;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0efff 100%);
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .form-section h3 {
            font-size: 1.2rem;
            color: #667eea;
            margin-bottom: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .form-group {
            margin-bottom: 1.75rem;
        }
        label {
            display: block;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #1f2937;
            font-size: 0.95rem;
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
            background: white;
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
        input[readonly] {
            background-color: #f3f4f6;
            cursor: not-allowed;
            opacity: 0.85;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.35);
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
        .worker-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .worker-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            background: #f8f9ff;
        }
        .worker-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.25);
        }
        .worker-card input[type="radio"] {
            display: none;
        }
        .worker-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1f2937;
        }
        .worker-info {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 0.5rem;
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
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tasks"></i> Assign Task to Worker</h1>
            <p>Create and manage project tasks</p>
        </div>

        <div class="form-card">
            <div class="project-info">
                <strong>📁 Project:</strong> {{ $project->name }} | <strong>👤 Client:</strong> {{ $project->client->name ?? 'N/A' }}
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

            <form action="{{ route('projects.tasks.store', $project) }}" method="POST" id="taskForm">
                @csrf

                <!-- Task Information -->
                <div class="form-section">
                    <h3><i class="fas fa-pen-fancy"></i> Task Information</h3>

                    <div class="form-group">
                        <label for="title">Task Title <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g., Foundation Excavation, Wall Painting" required>
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="📝 Detailed description of the task...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority">
                                <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="high" {{ old('priority', 'medium') == 'high' ? 'selected' : '' }}>🟠 High</option>
                                <option value="urgent" {{ old('priority', 'medium') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                            </select>
                            @error('priority')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status">
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="in_progress" {{ old('status', 'pending') == 'in_progress' ? 'selected' : '' }}>⏱️ In Progress</option>
                                <option value="completed" {{ old('status', 'pending') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            </select>
                            @error('status')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}">
                            @error('due_date')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assignment & Estimates -->
                <div class="form-section">
                    <h3><i class="fas fa-person-hiking"></i> Assign to Worker</h3>

                    <div class="form-group">
                        <label>Select Worker <span style="color: #ef4444;">*</span></label>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse($workers as $worker)
                                <label class="worker-card" data-worker-id="{{ $worker->id }}">
                                    <input type="radio" name="worker_id" value="{{ $worker->id }}" {{ old('worker_id') == $worker->id ? 'checked' : '' }}>
                                    <div class="worker-name">👤 {{ $worker->first_name }} {{ $worker->last_name }}</div>
                                    <div class="worker-info">
                                        📍 {{ $worker->position ?? 'N/A' }} | 📞 {{ $worker->phone ?? 'N/A' }}
                                    </div>
                                </label>
                            @empty
                                <div class="alert alert-danger">
                                    ⚠️ No active workers available. Please add workers first.
                                </div>
                            @endforelse
                        </div>
                        @error('worker_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Estimates -->
                <div class="form-section">
                    <h3><i class="fas fa-hourglass-end"></i> Estimates & Costs</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="estimated_hours">Estimated Hours <span style="color: #667eea; font-size: 0.85rem;">(Auto: 8hrs/day)</span></label>
                            <input type="number" name="estimated_hours" id="estimated_hours" step="0.5" value="{{ old('estimated_hours') }}" placeholder="Auto-calculated" readonly>
                            @error('estimated_hours')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="estimated_cost">Estimated Cost (RWF)</label>
                            <input type="number" name="estimated_cost" id="estimated_cost" step="0.01" value="{{ old('estimated_cost') }}" placeholder="0.00">
                            @error('estimated_cost')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea name="notes" id="notes" placeholder="✍️ Any special instructions or notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit"><i class="fas fa-check"></i> Assign Task</button>
                    <a href="{{ route('projects.show', $project) }}" class="btn"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const workerCards = document.querySelectorAll('.worker-card');

            workerCards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');

                // Mark card as selected if radio is checked
                if (radio.checked) {
                    card.classList.add('selected');
                }

                // Add click handler to select/deselect
                card.addEventListener('click', function(e) {
                    if (e.target !== radio) {
                        radio.checked = !radio.checked;
                    }

                    // Update visual state
                    workerCards.forEach(c => c.classList.remove('selected'));
                    if (radio.checked) {
                        card.classList.add('selected');
                    }
                });
            });

            // Auto-calculate estimated hours based on date range (8 hours per day)
            const startDateInput = document.getElementById('start_date');
            const dueDateInput = document.getElementById('due_date');
            const estimatedHoursInput = document.getElementById('estimated_hours');

            function calculateHours() {
                const startDate = startDateInput.value;
                const dueDate = dueDateInput.value;

                if (!startDate || !dueDate) {
                    estimatedHoursInput.value = '';
                    return;
                }

                const start = new Date(startDate);
                const due = new Date(dueDate);

                // Calculate difference in milliseconds
                const diffMs = due - start;

                // Convert to days (including both start and end dates)
                const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24)) + 1;

                if (diffDays > 0) {
                    // 8 hours per day
                    const hours = diffDays * 8;
                    estimatedHoursInput.value = hours.toFixed(1);
                } else {
                    estimatedHoursInput.value = '';
                }
            }

            // Add event listeners to date inputs
            startDateInput.addEventListener('change', calculateHours);
            dueDateInput.addEventListener('change', calculateHours);

            // Calculate on page load if dates are pre-filled
            if (startDateInput.value && dueDateInput.value) {
                calculateHours();
            }
        });
    </script>
</body>
</html>
