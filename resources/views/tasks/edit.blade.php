<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - SiteLedger</title>
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
        .btn-delete {
            background: #e74c3c;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .error {
            color: #d63031;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .worker-card {
            display: block;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .worker-card input {
            position: absolute;
            opacity: 0;
        }
        .worker-card:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        .worker-card.selected {
            border-color: #667eea;
            background: #eef2ff;
        }
        .worker-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        .worker-info {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h2>✏️ Edit Task</h2>

            <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Task Details -->
                <div class="form-section">
                    <h3>📝 Task Details</h3>

                    <div class="form-group">
                        <label for="title">Task Title <span style="color: #d63031;">*</span></label>
                        <input type="text" name="title" id="title" required value="{{ old('title', $task->title) }}" placeholder="Enter task title">
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="Task description...">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority">
                                <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>🟠 High</option>
                                <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                            </select>
                            @error('priority')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status">
                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>⏱️ In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            </select>
                            @error('status')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}">
                            @error('start_date')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
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
                                    <input type="radio" name="worker_id" value="{{ $worker->id }}" {{ old('worker_id', $task->worker_id) == $worker->id ? 'checked' : '' }}>
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
                            <input type="number" name="estimated_hours" id="estimated_hours" step="0.5" value="{{ old('estimated_hours', $task->estimated_hours) }}" readonly>
                            @error('estimated_hours')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="estimated_cost">Estimated Cost (RWF)</label>
                            <input type="number" name="estimated_cost" id="estimated_cost" step="0.01" value="{{ old('estimated_cost', $task->estimated_cost) }}" placeholder="0.00">
                            @error('estimated_cost')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="actual_hours">Actual Hours</label>
                            <input type="number" name="actual_hours" id="actual_hours" step="0.5" value="{{ old('actual_hours', $task->actual_hours) }}" placeholder="0">
                            @error('actual_hours')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="actual_cost">Actual Cost (RWF)</label>
                            <input type="number" name="actual_cost" id="actual_cost" step="0.01" value="{{ old('actual_cost', $task->actual_cost) }}" placeholder="0.00">
                            @error('actual_cost')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea name="notes" id="notes" placeholder="Any special instructions or notes...">{{ old('notes', $task->notes) }}</textarea>
                        @error('notes')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="submit">💾 Update Task</button>
                    <a href="{{ route('projects.tasks.index', $project) }}" class="btn">Cancel</a>
                    <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this task?');">🗑️ Delete Task</button>
                    </form>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const workerCards = document.querySelectorAll('.worker-card');

            // Set initial selected state
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
