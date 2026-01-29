<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense - SiteLedger</title>
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
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .button-group { flex-direction: column; }
            .button-group .btn, .button-group button[type="submit"] { width: 100%; }
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
            <h2>💸 Add New Expense</h2>

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

            <form action="{{ route('expenses.store') }}" method="POST" id="expenseForm" novalidate>
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <h3>📋 Basic Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date">Date <span class="required">*</span></label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="project_id">Project <span class="required">*</span></label>
                            <select name="project_id" id="project_id" required>
                                <option value="">-- Select Project --</option>
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
                    </div>

                    <!-- Project Phase Section - Shown when project is selected -->
                    <div class="form-group" id="projectPhaseSection" style="display: none; margin-top: 1rem;">
                        <label for="phase">Project Phase</label>
                        <select name="phase" id="phase">
                            <option value="">-- Select Phase --</option>
                            <option value="design" {{ old('phase') == 'design' ? 'selected' : '' }}>📝 Design Phase - Planning, drawings, permits</option>
                            <option value="execution" {{ old('phase') == 'execution' ? 'selected' : '' }}>🔨 Execution Phase - Construction, installation</option>
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
                        <label class="expense-type-option" data-type="materials">
                            <input type="radio" name="expense_type" value="materials" {{ old('expense_type') == 'materials' ? 'checked' : '' }}>
                            <span class="icon">🧱</span>
                            <span class="label">Materials</span>
                        </label>
                        <label class="expense-type-option" data-type="labor">
                            <input type="radio" name="expense_type" value="labor" {{ old('expense_type') == 'labor' ? 'checked' : '' }}>
                            <span class="icon">👷</span>
                            <span class="label">Labor</span>
                        </label>
                        <label class="expense-type-option" data-type="equipment">
                            <input type="radio" name="expense_type" value="equipment" {{ old('expense_type') == 'equipment' ? 'checked' : '' }}>
                            <span class="icon">🔧</span>
                            <span class="label">Equipment</span>
                        </label>
                        <label class="expense-type-option" data-type="transport">
                            <input type="radio" name="expense_type" value="transport" {{ old('expense_type') == 'transport' ? 'checked' : '' }}>
                            <span class="icon">🚚</span>
                            <span class="label">Transport</span>
                        </label>
                        <label class="expense-type-option" data-type="subcontractor">
                            <input type="radio" name="expense_type" value="subcontractor" {{ old('expense_type') == 'subcontractor' ? 'checked' : '' }}>
                            <span class="icon">🤝</span>
                            <span class="label">Subcontractor</span>
                        </label>
                        <label class="expense-type-option" data-type="miscellaneous">
                            <input type="radio" name="expense_type" value="miscellaneous" {{ old('expense_type', 'miscellaneous') == 'miscellaneous' ? 'checked' : '' }}>
                            <span class="icon">📦</span>
                            <span class="label">Other</span>
                        </label>
                    </div>
                    @error('expense_type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Materials Fields (shown when materials is selected) -->

                <div class="form-section" id="dynamicFieldsSection">
                    <h3 id="dynamicFieldsTitle">🧱 Material Details</h3>
                    <div class="form-group" id="itemNameGroup">
                        <label for="item_name">Item Name <span class="required">*</span></label>
                        <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" placeholder="e.g., Cement, Sand, Iron rods">
                        @error('item_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="quantity" id="quantityLabel">Quantity <span class="required">*</span></label>
                            <div class="help-text" id="quantityHelp">Enter the number of items. For example, 40 bags of cement.</div>
                            <input type="number" name="quantity" id="quantity" step="0.01" value="{{ old('quantity') }}" placeholder="0" required>
                            @error('quantity')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="unit" id="unitLabel">Unit</label>
                            <select name="unit" id="unit">
                                <option value="">Select unit</option>
                                <option class="unit-material" value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                <option class="unit-material" value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option class="unit-material" value="bags" {{ old('unit') == 'bags' ? 'selected' : '' }}>Bags</option>
                                <option class="unit-material" value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>Tons</option>
                                <option class="unit-material" value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Liters</option>
                                <option class="unit-material" value="meters" {{ old('unit') == 'meters' ? 'selected' : '' }}>Meters</option>
                                <option class="unit-material" value="sqm" {{ old('unit') == 'sqm' ? 'selected' : '' }}>Square Meters</option>
                                <option class="unit-material" value="cbm" {{ old('unit') == 'cbm' ? 'selected' : '' }}>Cubic Meters</option>
                                <option class="unit-material" value="rolls" {{ old('unit') == 'rolls' ? 'selected' : '' }}>Rolls</option>
                                <option class="unit-material" value="sheets" {{ old('unit') == 'sheets' ? 'selected' : '' }}>Sheets</option>
                                <option class="unit-material" value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                <option class="unit-material" value="trips" {{ old('unit') == 'trips' ? 'selected' : '' }}>Trips</option>
                                <option class="unit-labor" value="person" {{ old('unit') == 'person' ? 'selected' : '' }} style="display:none;">Person</option>
                                <option class="unit-labor" value="day" {{ old('unit') == 'day' ? 'selected' : '' }} style="display:none;">Day</option>
                                <option class="unit-labor" value="hour" {{ old('unit') == 'hour' ? 'selected' : '' }} style="display:none;">Hour</option>
                            </select>
                            @error('unit')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="unit_price" id="unitPriceLabel">Unit Price (RWF) <span class="required">*</span></label>
                            <div class="help-text" id="unitPriceHelp">Enter the amount for one item.</div>
                            <input type="number" name="unit_price" id="unit_price" step="0.01" value="{{ old('unit_price') }}" placeholder="0.00" required>
                            @error('unit_price')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Amount & Description -->
                <div class="form-section">
                    <h3>💰 Amount & Details</h3>
                    <div class="help-text" style="margin-bottom: 10px; color: #444;">
                        For labor, <b>Quantity</b> = number of labors, <b>Unit Price</b> = amount for one labor. For materials, use the number of items and price per item.
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="expense_category_id">Category <span class="required">*</span></label>
                            <select name="expense_category_id" id="expense_category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('expense_category_id')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="total" style="font-weight: bold; color: #2d3436;">Total Amount (RWF) <span class="required">*</span></label>
                            <input type="number" name="total" id="total" step="0.01" value="{{ old('total') }}" required placeholder="0.00" readonly style="background: #eafaf1; color: #09804a; font-weight: bold; font-size: 1.2rem; border: 2px solid #55efc4;">
                            <div class="help-text" id="calculatedAmount" style="color: #09804a; font-weight: 600; margin-top: 0.25rem;">This value is auto-calculated from Quantity × Unit Price.</div>
                            @error('total')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="Describe this expense (optional)...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="method">Payment Method</label>
                            <select name="method" id="method">
                                <option value="cash" {{ old('method', 'cash') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="mobile_money" {{ old('method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="cheque" {{ old('method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="credit" {{ old('method') == 'credit' ? 'selected' : '' }}>Credit</option>
                            </select>
                            @error('method')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status">
                                <option value="completed" {{ old('status', 'completed') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            </select>
                            @error('status')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit">💾 Save Expense</button>
                    <a href="{{ route('expenses.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>

            <script>
        // Prevent submit if total is empty or zero
        document.getElementById('expenseForm').addEventListener('submit', function(e) {
            const total = document.getElementById('total');
            const totalVal = parseFloat(total.value) || 0;
            let errorDiv = document.getElementById('totalRequiredError');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'totalRequiredError';
                errorDiv.className = 'error';
                total.parentNode.appendChild(errorDiv);
            }
            if (totalVal <= 0) {
                errorDiv.textContent = 'Total amount is required and must be greater than zero.';
                total.focus();
                e.preventDefault();
            } else {
                errorDiv.textContent = '';
            }
        });
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
            // Check initial state
            togglePhaseSection();

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
                        // Set category based on expense type
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

                // Check initial state
                if (option.querySelector('input').checked) {
                    option.classList.add('selected');
                    const type = option.dataset.type;
                    if (type === 'materials') {
                        materialsFields.classList.add('visible');
                    } else if (type === 'labor') {
                        laborFields.classList.add('visible');
                    }
                }
            });

            // Auto-calculate amount from quantity * unit_price
            // Dynamic field logic for single set of fields
            const dynamicFieldsTitle = document.getElementById('dynamicFieldsTitle');
            const itemNameGroup = document.getElementById('itemNameGroup');
            const quantityLabel = document.getElementById('quantityLabel');
            const quantityHelp = document.getElementById('quantityHelp');
            const unitLabel = document.getElementById('unitLabel');
            const unitSelect = document.getElementById('unit');
            const unitPriceLabel = document.getElementById('unitPriceLabel');
            const unitPriceHelp = document.getElementById('unitPriceHelp');

            function setFieldsForType(type) {
                if (type === 'labor') {
                    dynamicFieldsTitle.textContent = '👷 Labor Details';
                    itemNameGroup.style.display = 'none';
                    quantityLabel.innerHTML = 'Quantity <span class="required">*</span>';
                    quantityHelp.textContent = 'Enter the number of labors. For example, if 40 labors worked, enter 40.';
                    unitLabel.textContent = 'Unit';
                    // Show only labor units
                    Array.from(unitSelect.options).forEach(opt => {
                        if (opt.classList.contains('unit-labor')) opt.style.display = '';
                        else if (opt.value !== '') opt.style.display = 'none';
                    });
                    unitPriceLabel.innerHTML = 'Unit Price (RWF) <span class="required">*</span>';
                    unitPriceHelp.textContent = 'Enter the amount for one labor (per person, per day, or per hour).';
                } else {
                    dynamicFieldsTitle.textContent = '🧱 Material Details';
                    itemNameGroup.style.display = '';
                    quantityLabel.innerHTML = 'Quantity <span class="required">*</span>';
                    quantityHelp.textContent = 'Enter the number of items. For example, 40 bags of cement.';
                    unitLabel.textContent = 'Unit';
                    // Show only material units
                    Array.from(unitSelect.options).forEach(opt => {
                        if (opt.classList.contains('unit-material')) opt.style.display = '';
                        else if (opt.value !== '') opt.style.display = 'none';
                    });
                    unitPriceLabel.innerHTML = 'Unit Price (RWF) <span class="required">*</span>';
                    unitPriceHelp.textContent = 'Enter the amount for one item.';
                }
            }

            // Expense type selection logic
            expenseTypeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const type = this.dataset.type;
                    setFieldsForType(type);
                    setTimeout(calculateAmount, 50);
                });
                // On page load, set correct fields
                if (option.querySelector('input').checked) {
                    setFieldsForType(option.dataset.type);
                }
            });

            // Auto-calculate amount from quantity * unit_price (single logic)
            function calculateAmount() {
                const quantity = document.getElementById('quantity');
                const unitPrice = document.getElementById('unit_price');
                const total = document.getElementById('total');
                const calculatedAmount = document.getElementById('calculatedAmount');
                const qty = parseFloat(quantity.value) || 0;
                const price = parseFloat(unitPrice.value) || 0;
                if (qty > 0 && price > 0) {
                    const totalVal = qty * price;
                    total.value = totalVal.toFixed(2);
                    calculatedAmount.textContent = `Calculated: ${qty} × ${price.toLocaleString()} = RWF ${totalVal.toLocaleString()}`;
                } else {
                    total.value = '';
                    calculatedAmount.textContent = '';
                }
            }
            document.getElementById('quantity').addEventListener('input', calculateAmount);
            document.getElementById('unit_price').addEventListener('input', calculateAmount);
            // Initial calculation on page load
            calculateAmount();
        });
    </script>
</body>
</html>
