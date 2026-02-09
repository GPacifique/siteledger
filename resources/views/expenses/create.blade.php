@extends('layouts.app')

@section('title', 'Add New Expense')

@section('content')
<div class="page-container">
    <!-- Professional Gradient Page Header -->
    <div class="page-header-pro" style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 50%, #f97316 100%); border-radius: 24px; padding: 2.5rem; margin-bottom: 2rem; box-shadow: 0 20px 50px rgba(220, 38, 38, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -30%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); pointer-events: none;"></div>

        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <a href="{{ route('dashboard') }}" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.9rem;">🏠 Dashboard</a>
                    <span style="color: rgba(255,255,255,0.6);">/</span>
                    <a href="{{ route('expenses.index') }}" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.9rem;">💸 Expenses</a>
                    <span style="color: rgba(255,255,255,0.6);">/</span>
                    <span style="color: white; font-weight: 600;">Add New</span>
                </div>
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    <span style="font-size: 3rem; animation: bounce 2s infinite;">➕</span>
                    Add New Expense
                </h1>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 1.1rem;">Record a new expense entry</p>
            </div>
        </div>
    </div>

    <div class="card" style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.1); border: none;">
        <div class="card-header" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 1.5rem 2rem; border-bottom: 2px solid #fbbf24;">
            <h3 class="card-title" style="color: #92400e; margin: 0; font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.5rem; animation: bounce 2s infinite;">💸</span>
                Expense Information
            </h3>
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

            <form action="{{ route('expenses.store') }}" method="POST" id="expenseForm">
                @csrf

                <!-- Expense Type Selection -->
                <div class="form-section mb-xl">
                    <h4 class="section-title">
                        <span class="icon">📦</span>
                        Expense Type
                    </h4>
                    <div class="expense-type-grid">
                        <label class="expense-type-card {{ old('expense_type') == 'materials' ? 'selected' : '' }}" data-type="materials">
                            <input type="radio" name="expense_type" value="materials" {{ old('expense_type') == 'materials' ? 'checked' : '' }}>
                            <div class="card-icon">🧱</div>
                            <div class="card-title">Materials</div>
                            <div class="card-description">Construction materials, supplies</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type') == 'labor' ? 'selected' : '' }}" data-type="labor">
                            <input type="radio" name="expense_type" value="labor" {{ old('expense_type') == 'labor' ? 'checked' : '' }}>
                            <div class="card-icon">👷</div>
                            <div class="card-title">Labor</div>
                            <div class="card-description">Workforce, contractors</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type') == 'equipment' ? 'selected' : '' }}" data-type="equipment">
                            <input type="radio" name="expense_type" value="equipment" {{ old('expense_type') == 'equipment' ? 'checked' : '' }}>
                            <div class="card-icon">🔧</div>
                            <div class="card-title">Equipment</div>
                            <div class="card-description">Tools, machinery rental</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type') == 'transport' ? 'selected' : '' }}" data-type="transport">
                            <input type="radio" name="expense_type" value="transport" {{ old('expense_type') == 'transport' ? 'checked' : '' }}>
                            <div class="card-icon">🚚</div>
                            <div class="card-title">Transport</div>
                            <div class="card-description">Delivery, fuel, logistics</div>
                        </label>

                        <label class="expense-type-card {{ old('expense_type') == 'office' ? 'selected' : '' }}" data-type="office">
                            <input type="radio" name="expense_type" value="office" {{ old('expense_type', 'office') == 'office' ? 'checked' : '' }}>
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
                                           value="{{ old('date', date('Y-m-d')) }}"
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
                                           value="{{ old('item_name') }}"
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
                                                    {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>
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
                                              class="form-input">{{ old('notes') }}</textarea>
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
                                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                        <option value="design" {{ old('phase') == 'design' ? 'selected' : '' }}>
                                            📝 Design Phase
                                        </option>
                                        <option value="execution" {{ old('phase') == 'execution' ? 'selected' : '' }}>
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
                                               value="{{ old('quantity') }}"
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
                                               value="{{ old('unit') }}"
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
                                           value="{{ old('unit_price') }}"
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
                                           value="{{ old('total') }}"
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
                <div class="form-actions" style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #fef3c7;">
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%); color: white; font-weight: 700; padding: 1rem 2.5rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem; border: none; font-size: 1.1rem; box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3); transition: all 0.3s ease; cursor: pointer;">
                        <span style="font-size: 1.3rem;">💾</span>
                        Create Expense
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); color: #374151; font-weight: 600; padding: 1rem 2rem; border-radius: 50px; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #d1d5db; transition: all 0.3s ease;">
                        ❌ Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Expense Type Selection */
.expense-type-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.25rem;
}

.expense-type-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 20px;
    background: white;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.expense-type-card:hover {
    transform: translateY(-8px);
}

.expense-type-card.selected {
    transform: translateY(-8px) scale(1.02);
}

.expense-type-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.expense-type-card .card-icon {
    font-size: 2.75rem;
    margin-bottom: 0.75rem;
    animation: bounce 2s infinite;
}

.expense-type-card .card-title {
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--space-xs);
    font-size: 1.1rem;
}

.expense-type-card .card-description {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
}

/* Colorful type cards */
.expense-type-card[data-type="materials"] { border-color: #fed7aa; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); }
.expense-type-card[data-type="materials"]:hover, .expense-type-card[data-type="materials"].selected { border-color: #f97316; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); box-shadow: 0 8px 25px rgba(249, 115, 22, 0.25); }

.expense-type-card[data-type="labor"] { border-color: #a7f3d0; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); }
.expense-type-card[data-type="labor"]:hover, .expense-type-card[data-type="labor"].selected { border-color: #10b981; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.25); }

.expense-type-card[data-type="equipment"] { border-color: #fde68a; background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%); }
.expense-type-card[data-type="equipment"]:hover, .expense-type-card[data-type="equipment"].selected { border-color: #f59e0b; background: linear-gradient(135deg, #fef9c3 0%, #fde68a 100%); box-shadow: 0 8px 25px rgba(245, 158, 11, 0.25); }

.expense-type-card[data-type="transport"] { border-color: #ddd6fe; background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); }
.expense-type-card[data-type="transport"]:hover, .expense-type-card[data-type="transport"].selected { border-color: #8b5cf6; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); box-shadow: 0 8px 25px rgba(139, 92, 246, 0.25); }

.expense-type-card[data-type="office"] { border-color: #bfdbfe; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); }
.expense-type-card[data-type="office"]:hover, .expense-type-card[data-type="office"].selected { border-color: #3b82f6; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.25); }

/* Professional form section styling */
.form-section {
    padding: 1.75rem;
    background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
    border-radius: 20px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.form-section:hover {
    border-color: #cbd5e1;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    background: linear-gradient(135deg, #dc2626, #ea580c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #fef3c7;
}

.section-title .icon {
    font-size: 1.5rem;
    -webkit-text-fill-color: initial;
}

/* Enhanced form input styling */
.form-input,
.form-select,
.form-textarea {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.875rem 1rem;
    transition: all 0.3s ease;
    font-size: 1rem;
}

/* Enhanced form input focus states */
.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
    background: white;
}

/* Form label styling */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.95rem;
}

/* Required field indicators */
.form-label.required::after {
    content: " *";
    color: #dc2626;
    font-weight: bold;
}

/* Form help text */
.form-help {
    font-size: 0.85rem;
    color: #6b7280;
    margin-top: 0.5rem;
    font-style: italic;
}

/* Alert styling - colorful */
.alert-error {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 2px solid #fca5a5;
    color: #991b1b;
    border-radius: 16px;
    padding: 1.25rem;
}

.alert-error strong {
    color: #7f1d1d;
}

/* Enhanced form styles */
.total-input {
    font-weight: 700;
    font-size: 1.25rem;
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 2px solid #fca5a5;
    color: #dc2626;
    border-radius: 16px;
    padding: 1rem 1.25rem;
}

.total-input:focus {
    box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
}

/* Animation keyframes */
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        animation-timing-function: cubic-bezier(.215,.61,.355,1);
        transform: translateY(0);
    }
    40%, 43% {
        animation-timing-function: cubic-bezier(.755,.05,.855,.06);
        transform: translateY(-6px);
    }
    70% {
        animation-timing-function: cubic-bezier(.755,.05,.855,.06);
        transform: translateY(-3px);
    }
    90% {
        transform: translateY(-1px);
    }
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
