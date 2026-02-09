@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<div class="page-container">
    <!-- Page Header -->
    @include('components.page-header', [
        'title' => 'Edit Expense',
        'subtitle' => 'Update expense information',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Expenses', 'url' => route('expenses.index')],
            ['label' => 'Edit', 'url' => '#']
        ]
    ])

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <span class="icon">✏️</span>
                Edit Expense: {{ $expense->item_name }}
            </h3>
            <div class="card-actions">
                <span class="badge badge-info">{{ $expense->type_label }}</span>
                <span class="text-muted">{{ $expense->formatted_amount }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-error mb-lg">
                    <div class="alert-content">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mt-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('expenses.update', $expense) }}" method="POST" id="expenseForm">
                @csrf
                @method('PUT')

                <!-- Expense Type Selection -->
                <div class="form-section mb-xl">
                    <h4 class="section-title">
                        <span class="icon">📦</span>
                        Expense Type
                    </h4>
                    <div class="expense-type-grid">
                        <label class="expense-type-card {{ old('expense_type', $expense->expense_type) == 'materials' ? 'selected' : '' }}" data-type="materials">
                            <input type="radio" name="expense_type" value="materials" {{ old('expense_type', $expense->expense_type) == 'materials' ? 'checked' : '' }}>
                            <div class="card-icon">🧱</div>
                            <div class="card-title">Materials</div>
                            <div class="card-description">Construction materials, supplies</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type', $expense->expense_type) == 'labor' ? 'selected' : '' }}" data-type="labor">
                            <input type="radio" name="expense_type" value="labor" {{ old('expense_type', $expense->expense_type) == 'labor' ? 'checked' : '' }}>
                            <div class="card-icon">👷</div>
                            <div class="card-title">Labor</div>
                            <div class="card-description">Workforce, contractors</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type', $expense->expense_type) == 'equipment' ? 'selected' : '' }}" data-type="equipment">
                            <input type="radio" name="expense_type" value="equipment" {{ old('expense_type', $expense->expense_type) == 'equipment' ? 'checked' : '' }}>
                            <div class="card-icon">🔧</div>
                            <div class="card-title">Equipment</div>
                            <div class="card-description">Tools, machinery rental</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type', $expense->expense_type) == 'transport' ? 'selected' : '' }}" data-type="transport">
                            <input type="radio" name="expense_type" value="transport" {{ old('expense_type', $expense->expense_type) == 'transport' ? 'checked' : '' }}>
                            <div class="card-icon">🚚</div>
                            <div class="card-title">Transport</div>
                            <div class="card-description">Delivery, fuel, logistics</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type', $expense->expense_type) == 'office' ? 'selected' : '' }}" data-type="office">
                            <input type="radio" name="expense_type" value="office" {{ old('expense_type', $expense->expense_type) == 'office' ? 'checked' : '' }}>
                            <div class="card-icon">🏢</div>
                            <div class="card-title">Office</div>
                            <div class="card-description">Administrative, utilities</div>
                        </label>
                    </div>
                    @error('expense_type')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-xl">
                    <!-- Left Column -->
                    <div class="space-y-lg">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <span class="icon">📋</span>
                                Basic Information
                            </h4>

                            <div class="space-y-md">
                                <div class="form-group">
                                    <label for="date" class="form-label required">Date</label>
                                    <input type="date"
                                           name="date"
                                           id="date"
                                           value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                                           class="form-input"
                                           required>
                                    @error('date')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="item_name" class="form-label required">Item/Service Name</label>
                                    <input type="text"
                                           name="item_name"
                                           id="item_name"
                                           value="{{ old('item_name', $expense->item_name) }}"
                                           placeholder="Enter item or service name"
                                           class="form-input"
                                           required>
                                    @error('item_name')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="expense_category_id" class="form-label required">Category</label>
                                    <select name="expense_category_id" id="expense_category_id" class="form-select" required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('expense_category_id')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes"
                                              id="notes"
                                              rows="3"
                                              placeholder="Additional notes or description..."
                                              class="form-input">{{ old('notes', $expense->notes) }}</textarea>
                                    @error('notes')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-lg">
                        <!-- Project & Phase -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <span class="icon">🏗️</span>
                                Project Assignment
                            </h4>

                            <div class="space-y-md">
                                <div class="form-group">
                                    <label for="project_id" class="form-label">Project</label>
                                    <select name="project_id" id="project_id" class="form-select">
                                        <option value="">General Company Expense</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}"
                                                    {{ old('project_id', $expense->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-help">Leave unselected for general company expenses</div>
                                    @error('project_id')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="phase-group" style="display: none;">
                                    <label for="phase" class="form-label">Project Phase</label>
                                    <select name="phase" id="phase" class="form-select">
                                        <option value="">Select Phase</option>
                                        <option value="design" {{ old('phase', $expense->phase) == 'design' ? 'selected' : '' }}>
                                            📝 Design Phase
                                        </option>
                                        <option value="execution" {{ old('phase', $expense->phase) == 'execution' ? 'selected' : '' }}>
                                            🔨 Execution Phase
                                        </option>
                                    </select>
                                    @error('phase')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Quantity & Pricing -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <span class="icon">💰</span>
                                Quantity & Pricing
                            </h4>

                            <div class="space-y-md">
                                <div class="grid grid-cols-2 gap-md">
                                    <div class="form-group">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number"
                                               name="quantity"
                                               id="quantity"
                                               value="{{ old('quantity', $expense->quantity) }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="0.00"
                                               class="form-input">
                                        @error('quantity')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text"
                                               name="unit"
                                               id="unit"
                                               value="{{ old('unit', $expense->unit) }}"
                                               placeholder="pcs, kg, m², etc."
                                               class="form-input">
                                        @error('unit')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="unit_price" class="form-label">Unit Price (RWF)</label>
                                    <input type="number"
                                           name="unit_price"
                                           id="unit_price"
                                           value="{{ old('unit_price', $expense->getEffectiveUnitPrice()) }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           class="form-input">
                                    @error('unit_price')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="total" class="form-label required">Total Amount (RWF)</label>
                                    <input type="number"
                                           name="total"
                                           id="total"
                                           value="{{ old('total', $expense->total) }}"
                                           step="0.01"
                                           min="0.01"
                                           placeholder="0.00"
                                           class="form-input total-input"
                                           required>
                                    <div class="form-help">Will auto-calculate if quantity × unit price is provided</div>
                                    @error('total')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span>
                        Update Expense
                    </button>
                    <a href="{{ route('expenses.show', $expense) }}" class="btn btn-secondary">
                        <span>👁️</span>
                        View Expense
                    </a>
                    <a href="{{ route('expenses.index') }}" class="btn btn-ghost">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reuse styles from create form -->
<style>
/* Expense Type Selection */
.expense-type-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: var(--space-md);
}

.expense-type-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: var(--space-lg);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    background: var(--white);
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.expense-type-card:hover {
    border-color: var(--primary);
    background: var(--primary-50);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.expense-type-card.selected {
    border-color: var(--primary);
    background: var(--primary-50);
    box-shadow: var(--shadow-lg);
}

.expense-type-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.expense-type-card .card-icon {
    font-size: 2rem;
    margin-bottom: var(--space-sm);
}

.expense-type-card .card-title {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-xs);
}

.expense-type-card .card-description {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
}

/* Form sections */
.form-section {
    padding: var(--space-lg);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
}

.section-title {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-lg);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.section-title .icon {
    font-size: 1.2em;
}

/* Enhanced form styles */
.total-input {
    font-weight: 600;
    font-size: var(--font-size-lg);
    background: var(--warning-light);
    border-color: var(--warning);
}

.total-input:focus {
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

/* Phase group animation */
#phase-group {
    opacity: 0;
    transition: all 0.3s ease;
}

#phase-group.show {
    display: block !important;
    opacity: 1;
}

/* Form actions */
.form-actions {
    display: flex;
    gap: var(--space-md);
    padding-top: var(--space-xl);
    margin-top: var(--space-xl);
    border-top: 1px solid var(--gray-200);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }

    .expense-type-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .expense-type-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Expense type selection
    const expenseTypeCards = document.querySelectorAll('.expense-type-card');
    expenseTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            expenseTypeCards.forEach(c => c.classList.remove('selected'));

            // Add selected class to clicked card
            this.classList.add('selected');

            // Check the radio input
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Handle phase visibility for labor
            handlePhaseVisibility();
        });
    });

    // Project selection change handler
    const projectSelect = document.getElementById('project_id');
    const phaseGroup = document.getElementById('phase-group');

    projectSelect.addEventListener('change', handlePhaseVisibility);

    function handlePhaseVisibility() {
        const selectedType = document.querySelector('input[name="expense_type"]:checked')?.value;
        const hasProject = projectSelect.value !== '';

        if (selectedType === 'labor' && hasProject) {
            phaseGroup.style.display = 'block';
            phaseGroup.classList.add('show');
            document.getElementById('phase').required = true;
        } else {
            phaseGroup.style.display = 'none';
            phaseGroup.classList.remove('show');
            document.getElementById('phase').required = false;
        }
    }

    // Auto-calculate total
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const totalInput = document.getElementById('total');

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;

        if (quantity > 0 && unitPrice > 0) {
            const total = quantity * unitPrice;
            totalInput.value = total.toFixed(2);
        }
    }

    quantityInput.addEventListener('input', calculateTotal);
    unitPriceInput.addEventListener('input', calculateTotal);

    // Form validation enhancement
    const form = document.getElementById('expenseForm');
    form.addEventListener('submit', function(e) {
        const expenseType = document.querySelector('input[name="expense_type"]:checked');
        if (!expenseType) {
            e.preventDefault();
            alert('Please select an expense type');
            return false;
        }

        // Validate phase for labor with project
        const selectedType = expenseType.value;
        const hasProject = projectSelect.value !== '';
        const phase = document.getElementById('phase').value;

        if (selectedType === 'labor' && hasProject && !phase) {
            e.preventDefault();
            alert('Please select a project phase for labor expenses');
            return false;
        }
    });

    // Initialize phase visibility
    handlePhaseVisibility();
});
</script>
@endsection
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
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .form-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        label .required {
            color: #d63031;
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
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .form-row-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        @media (max-width: 768px) {
            .form-row, .form-row-3 {
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
        .btn-danger {
            background: #d63031;
        }
        .btn-danger:hover {
            background: #c0392b;
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
        .help-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.25rem;
        }

        /* Expense Type Cards */
        .expense-type-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .expense-type-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        .expense-type-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .expense-type-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .expense-type-option input {
            display: none;
        }
        .expense-type-option .icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .expense-type-option .label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Conditional Sections */
        .materials-fields,
        .labor-fields {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .materials-fields.visible,
        .labor-fields.visible {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Phase Selector */
        .phase-selector {
            display: flex;
            gap: 1rem;
        }
        .phase-option {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .phase-option:hover {
            border-color: #667eea;
        }
        .phase-option.design.selected {
            border-color: #6c5ce7;
            background: #f3f0ff;
        }
        .phase-option.execution.selected {
            border-color: #fdcb6e;
            background: #fffbf0;
        }
        .phase-option input {
            display: none;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="form-card">
            <h2>✏️ Edit Expense</h2>

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

            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" id="expenseForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <h3>📋 Basic Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date">Date <span class="required">*</span></label>
                            <input type="date" name="date" id="date" value="{{ old('date', $expense->date ? $expense->date->format('Y-m-d') : '') }}" required>
                            @error('date')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="project_id">Project</label>
                            <select name="project_id" id="project_id">
                                <option value="">-- Office Expense (No Project) --</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}" {{ old('project_id', $expense->project_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="help-text">Leave empty for office expenses</div>
                            @error('project_id')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Project Phase Section - Shown when project is selected -->
                    <div class="form-group" id="projectPhaseSection" style="{{ old('project_id', $expense->project_id) ? '' : 'display: none;' }} margin-top: 1rem;">
                        <label for="phase">Project Phase</label>
                        <select name="phase" id="phase">
                            <option value="">-- Select Phase --</option>
                            <option value="design" {{ old('phase', $expense->phase) == 'design' ? 'selected' : '' }}>📝 Design Phase - Planning, drawings, permits</option>
                            <option value="execution" {{ old('phase', $expense->phase) == 'execution' ? 'selected' : '' }}>🔨 Execution Phase - Construction, installation</option>
                        </select>
                        <div class="help-text">Select which project phase this expense belongs to</div>
                        @error('phase')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Expense Type Selection -->
                <div class="form-section" id="expenseTypeSection">
                    <h3>📦 Expense Type</h3>

                    <div class="expense-type-selector">
                        <label class="expense-type-option {{ old('expense_type', $expense->expense_type) == 'materials' ? 'selected' : '' }}" data-type="materials">
                            <input type="radio" name="expense_type" value="materials" {{ old('expense_type', $expense->expense_type) == 'materials' ? 'checked' : '' }}>
                            <span class="icon">🧱</span>
                            <span class="label">Materials</span>
                        </label>
                        <label class="expense-type-option {{ old('expense_type', $expense->expense_type) == 'labor' ? 'selected' : '' }}" data-type="labor">
                            <input type="radio" name="expense_type" value="labor" {{ old('expense_type', $expense->expense_type) == 'labor' ? 'checked' : '' }}>
                            <span class="icon">👷</span>
                            <span class="label">Labor</span>
                        </label>
                        <label class="expense-type-option {{ old('expense_type', $expense->expense_type) == 'equipment' ? 'selected' : '' }}" data-type="equipment">
                            <input type="radio" name="expense_type" value="equipment" {{ old('expense_type', $expense->expense_type) == 'equipment' ? 'checked' : '' }}>
                            <span class="icon">🔧</span>
                            <span class="label">Equipment</span>
                        </label>
                        <label class="expense-type-option {{ old('expense_type', $expense->expense_type) == 'transport' ? 'selected' : '' }}" data-type="transport">
                            <input type="radio" name="expense_type" value="transport" {{ old('expense_type', $expense->expense_type) == 'transport' ? 'checked' : '' }}>
                            <span class="icon">🚚</span>
                            <span class="label">Transport</span>
                        </label>
                        <label class="expense-type-option {{ old('expense_type', $expense->expense_type) == 'subcontractor' ? 'selected' : '' }}" data-type="subcontractor">
                            <input type="radio" name="expense_type" value="subcontractor" {{ old('expense_type', $expense->expense_type) == 'subcontractor' ? 'checked' : '' }}>
                            <span class="icon">🤝</span>
                            <span class="label">Subcontractor</span>
                        </label>
                        <label class="expense-type-option {{ in_array(old('expense_type', $expense->expense_type), ['miscellaneous', null, '']) ? 'selected' : '' }}" data-type="miscellaneous">
                            <input type="radio" name="expense_type" value="miscellaneous" {{ in_array(old('expense_type', $expense->expense_type), ['miscellaneous', null, '']) ? 'checked' : '' }}>
                            <span class="icon">📦</span>
                            <span class="label">Other</span>
                        </label>
                    </div>
                    @error('expense_type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Materials Fields -->
                <div class="form-section materials-fields {{ old('expense_type', $expense->expense_type) == 'materials' ? 'visible' : '' }}" id="materialsFields">
                    <h3>🧱 Material Details</h3>

                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name" value="{{ old('item_name', $expense->item_name) }}" placeholder="e.g., Cement, Sand, Iron rods">
                        @error('item_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" step="0.01" value="{{ old('quantity', $expense->quantity) }}" placeholder="0">
                            @error('quantity')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unit">Unit</label>
                            <select name="unit" id="unit">
                                <option value="">Select unit</option>
                                @php $currentUnit = old('unit', $expense->unit); @endphp
                                <option value="pieces" {{ $currentUnit == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                <option value="kg" {{ $currentUnit == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="bags" {{ $currentUnit == 'bags' ? 'selected' : '' }}>Bags</option>
                                <option value="tons" {{ $currentUnit == 'tons' ? 'selected' : '' }}>Tons</option>
                                <option value="liters" {{ $currentUnit == 'liters' ? 'selected' : '' }}>Liters</option>
                                <option value="meters" {{ $currentUnit == 'meters' ? 'selected' : '' }}>Meters</option>
                                <option value="sqm" {{ $currentUnit == 'sqm' ? 'selected' : '' }}>Square Meters</option>
                                <option value="cbm" {{ $currentUnit == 'cbm' ? 'selected' : '' }}>Cubic Meters</option>
                                <option value="rolls" {{ $currentUnit == 'rolls' ? 'selected' : '' }}>Rolls</option>
                                <option value="sheets" {{ $currentUnit == 'sheets' ? 'selected' : '' }}>Sheets</option>
                                <option value="boxes" {{ $currentUnit == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                <option value="trips" {{ $currentUnit == 'trips' ? 'selected' : '' }}>Trips</option>
                            </select>
                            @error('unit')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unit_price">Unit Price (RWF)</label>
                            <input type="number" name="unit_price" id="unit_price" step="0.01" value="{{ old('unit_price', $expense->expense_type === 'labor' ? $expense->price_per_one : $expense->unit_price) }}" placeholder="0.00">
                            @error('unit_price')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Labor Fields -->
                <div class="form-section labor-fields {{ old('expense_type', $expense->expense_type) == 'labor' ? 'visible' : '' }}" id="laborFields">
                    <h3>👷 Labor Details</h3>
                    <p style="color: #666; font-size: 0.9rem;">Labor expense selected. Use the project phase selector above to assign to design or execution phase.</p>
                </div>

                <!-- Amount & Description -->
                <div class="form-section">
                    <h3>💰 Amount & Details</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="amount">Total Amount (RWF) <span class="required">*</span></label>
                            <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount', $expense->amount) }}" required placeholder="0.00">
                            <div class="help-text" id="calculatedAmount"></div>
                            @error('amount')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                @php $currentCategory = old('category', $expense->category); @endphp
                                <option value="Materials" {{ $currentCategory == 'Materials' ? 'selected' : '' }}>Materials</option>
                                <option value="Labor" {{ $currentCategory == 'Labor' ? 'selected' : '' }}>Labor</option>
                                <option value="Equipment" {{ $currentCategory == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="Transport" {{ $currentCategory == 'Transport' ? 'selected' : '' }}>Transport</option>
                                <option value="Subcontractor" {{ $currentCategory == 'Subcontractor' ? 'selected' : '' }}>Subcontractor</option>
                                <option value="Utilities" {{ $currentCategory == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="Permits" {{ $currentCategory == 'Permits' ? 'selected' : '' }}>Permits</option>
                                <option value="Miscellaneous" {{ $currentCategory == 'Miscellaneous' || !$currentCategory ? 'selected' : '' }}>Miscellaneous</option>
                            </select>
                            @error('category')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="Describe this expense (optional)...">{{ old('description', $expense->description) }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="method">Payment Method</label>
                            <select name="method" id="method">
                                @php $currentMethod = old('method', $expense->method); @endphp
                                <option value="cash" {{ $currentMethod == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ $currentMethod == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="mobile_money" {{ $currentMethod == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="cheque" {{ $currentMethod == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="credit" {{ $currentMethod == 'credit' ? 'selected' : '' }}>Credit</option>
                            </select>
                            @error('method')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status">
                                @php $currentStatus = old('status', $expense->status); @endphp
                                <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $currentStatus == 'approved' ? 'selected' : '' }}>Approved</option>
                            </select>
                            @error('status')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit">💾 Update Expense</button>
                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const expenseTypeOptions = document.querySelectorAll('.expense-type-option');
            const materialsFields = document.getElementById('materialsFields');
            const laborFields = document.getElementById('laborFields');
            const categorySelect = document.getElementById('category');
            const projectSelect = document.getElementById('project_id');
            const projectPhaseSection = document.getElementById('projectPhaseSection');
            const phaseSelect = document.getElementById('phase');

            // Show/hide phase section based on project selection
            function togglePhaseSection() {
                if (projectSelect.value) {
                    projectPhaseSection.style.display = 'block';
                } else {
                    projectPhaseSection.style.display = 'none';
                    // Clear phase selection when no project
                    if (phaseSelect) {
                        phaseSelect.value = '';
                    }
                }
            }

            projectSelect.addEventListener('change', togglePhaseSection);

            // Expense type selection
            expenseTypeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected from all
                    expenseTypeOptions.forEach(opt => opt.classList.remove('selected'));
                    // Add selected to clicked
                    this.classList.add('selected');

                    const type = this.dataset.type;

                    // Show/hide conditional fields
                    if (type === 'materials') {
                        materialsFields.classList.add('visible');
                        laborFields.classList.remove('visible');
                        categorySelect.value = 'Materials';
                    } else if (type === 'labor') {
                        materialsFields.classList.remove('visible');
                        laborFields.classList.add('visible');
                        categorySelect.value = 'Labor';
                    } else {
                        materialsFields.classList.remove('visible');
                        laborFields.classList.remove('visible');
                        const categoryMap = {
                            'equipment': 'Equipment',
                            'transport': 'Transport',
                            'subcontractor': 'Subcontractor',
                            'miscellaneous': 'Miscellaneous'
                        };
                        if (categoryMap[type]) {
                            categorySelect.value = categoryMap[type];
                        }
                    }
                });
            });

            // Auto-calculate amount
            const quantityInput = document.getElementById('quantity');
            const unitPriceInput = document.getElementById('unit_price');
            const amountInput = document.getElementById('amount');
            const calculatedAmount = document.getElementById('calculatedAmount');

            function calculateAmount() {
                const qty = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(unitPriceInput.value) || 0;
                if (qty > 0 && price > 0) {
                    const total = qty * price;
                    amountInput.value = total.toFixed(2);
                    calculatedAmount.textContent = `Calculated: ${qty} × ${price.toLocaleString()} = RWF ${total.toLocaleString()}`;
                } else {
                    calculatedAmount.textContent = '';
                }
            }

            quantityInput.addEventListener('input', calculateAmount);
            unitPriceInput.addEventListener('input', calculateAmount);
        });
    </script>
</body>
</html>
