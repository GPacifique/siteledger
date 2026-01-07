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

            <form action="{{ route('expenses.store') }}" method="POST" id="expenseForm">
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
                            <label for="project_id">Project</label>
                            <select name="project_id" id="project_id">
                                <option value="">-- Office Expense (No Project) --</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>
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
                <div class="form-section materials-fields" id="materialsFields">
                    <h3>🧱 Material Details</h3>

                    <div class="form-group">
                        <label for="item_name">Item Name <span class="required">*</span></label>
                        <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" placeholder="e.g., Cement, Sand, Iron rods">
                        @error('item_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" step="0.01" value="{{ old('quantity') }}" placeholder="0">
                            @error('quantity')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unit">Unit</label>
                            <select name="unit" id="unit">
                                <option value="">Select unit</option>
                                <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="bags" {{ old('unit') == 'bags' ? 'selected' : '' }}>Bags</option>
                                <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>Tons</option>
                                <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Liters</option>
                                <option value="meters" {{ old('unit') == 'meters' ? 'selected' : '' }}>Meters</option>
                                <option value="sqm" {{ old('unit') == 'sqm' ? 'selected' : '' }}>Square Meters</option>
                                <option value="cbm" {{ old('unit') == 'cbm' ? 'selected' : '' }}>Cubic Meters</option>
                                <option value="rolls" {{ old('unit') == 'rolls' ? 'selected' : '' }}>Rolls</option>
                                <option value="sheets" {{ old('unit') == 'sheets' ? 'selected' : '' }}>Sheets</option>
                                <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                <option value="trips" {{ old('unit') == 'trips' ? 'selected' : '' }}>Trips</option>
                            </select>
                            @error('unit')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unit_price">Unit Price (RWF)</label>
                            <input type="number" name="unit_price" id="unit_price" step="0.01" value="{{ old('unit_price') }}" placeholder="0.00">
                            @error('unit_price')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Labor Fields (shown when labor is selected) -->
                <div class="form-section labor-fields" id="laborFields">
                    <h3>👷 Labor Phase</h3>

                    <div class="form-group">
                        <label>Project Phase</label>
                        <div class="phase-selector">
                            <label class="phase-option design {{ old('phase') == 'design' ? 'selected' : '' }}">
                                <input type="radio" name="phase" value="design" {{ old('phase') == 'design' ? 'checked' : '' }}>
                                <span style="font-size: 1.5rem;">📝</span>
                                <div>
                                    <strong>Design Phase</strong>
                                    <div style="font-size: 0.85rem; color: #666;">Planning, drawings, permits</div>
                                </div>
                            </label>
                            <label class="phase-option execution {{ old('phase') == 'execution' ? 'selected' : '' }}">
                                <input type="radio" name="phase" value="execution" {{ old('phase') == 'execution' ? 'checked' : '' }}>
                                <span style="font-size: 1.5rem;">🔨</span>
                                <div>
                                    <strong>Execution Phase</strong>
                                    <div style="font-size: 0.85rem; color: #666;">Construction, installation</div>
                                </div>
                            </label>
                        </div>
                        @error('phase')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Amount & Description -->
                <div class="form-section">
                    <h3>💰 Amount & Details</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="amount">Total Amount (RWF) <span class="required">*</span></label>
                            <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount') }}" required placeholder="0.00">
                            <div class="help-text" id="calculatedAmount"></div>
                            @error('amount')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                <option value="Materials" {{ old('category') == 'Materials' ? 'selected' : '' }}>Materials</option>
                                <option value="Labor" {{ old('category') == 'Labor' ? 'selected' : '' }}>Labor</option>
                                <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="Transport" {{ old('category') == 'Transport' ? 'selected' : '' }}>Transport</option>
                                <option value="Subcontractor" {{ old('category') == 'Subcontractor' ? 'selected' : '' }}>Subcontractor</option>
                                <option value="Utilities" {{ old('category') == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="Permits" {{ old('category') == 'Permits' ? 'selected' : '' }}>Permits</option>
                                <option value="Miscellaneous" {{ old('category', 'Miscellaneous') == 'Miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                            </select>
                            @error('category')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea name="description" id="description" placeholder="Describe this expense..." required>{{ old('description') }}</textarea>
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
        document.addEventListener('DOMContentLoaded', function() {
            const expenseTypeOptions = document.querySelectorAll('.expense-type-option');
            const materialsFields = document.getElementById('materialsFields');
            const laborFields = document.getElementById('laborFields');
            const phaseOptions = document.querySelectorAll('.phase-option');
            const categorySelect = document.getElementById('category');

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

            // Phase selection
            phaseOptions.forEach(option => {
                option.addEventListener('click', function() {
                    phaseOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });

            // Auto-calculate amount from quantity * unit_price
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
