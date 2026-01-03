<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task - CSMS</title>
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
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .form-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .form-card h2 {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 0.75rem;
        }
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .form-section h3 {
            font-size: 1.1rem;
            color: #667eea;
            margin-bottom: 1rem;
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
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
            opacity: 0.85;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f0f0f0;
        }
        button[type="submit"],
        .btn {
            padding: 0.875rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn:hover {
            background: #7f8c8d;
        }
        .error {
            color: #d63031;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-danger {
            background: #ffe6e6;
            color: #c0392b;
            border: 1px solid #fab1a0;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .project-info {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #3498db;
        }
        .worker-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
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
        }
        .worker-card input[type="radio"] {
            display: none;
        }
        .worker-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
        }
        .worker-info {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="form-card">
            <h2>📋 Assign Task to Worker</h2>

            <div class="project-info">
                <strong>📁 Project:</strong> {{ $project->name }} |
                <strong>👤 Client:</strong> {{ $project->client->name ?? 'N/A' }}
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

            <form action="{{ route('projects.tasks.store', $project) }}" method="POST" id="taskForm">
                @csrf

                <!-- Task Information -->
                <div class="form-section">
                    <h3>📝 Task Information</h3>

                    <div class="form-group">
                        <label for="title">Task Title <span style="color: #d63031;">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g., Foundation Excavation, Wall Painting" required>
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="Detailed description of the task...">{{ old('description') }}</textarea>
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
                    <h3>👷 Assign to Worker</h3>

                    <div class="form-group">
                        <label>Select Worker <span style="color: #d63031;">*</span></label>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse($workers as $worker)
                                <label class="worker-card" data-worker-id="{{ $worker->id }}">
                                    <input type="radio" name="worker_id" value="{{ $worker->id }}" {{ old('worker_id') == $worker->id ? 'checked' : '' }}>
                                    <div class="worker-name">{{ $worker->first_name }} {{ $worker->last_name }}</div>
                                    <div class="worker-info">
                                        📍 {{ $worker->position ?? 'N/A' }} |
                                        📞 {{ $worker->phone ?? 'N/A' }}
                                    </div>
                                </label>
                            @empty
                                <div class="alert alert-danger">
                                    No active workers available. Please add workers first.
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
                    <h3>⏰ Estimates & Costs</h3>

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
                        <textarea name="notes" id="notes" placeholder="Any special instructions or notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit">✅ Assign Task</button>
                    <a href="{{ route('projects.show', $project) }}" class="btn">Cancel</a>
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
