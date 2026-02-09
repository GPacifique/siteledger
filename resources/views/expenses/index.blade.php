@extends('layouts.app')

@section('title', 'Expenses Management')

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
                    <span style="color: white; font-weight: 600;">Expenses</span>
                </div>
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    <span style="font-size: 3rem; animation: bounce 2s infinite;">💸</span>
                    Expenses
                </h1>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 1.1rem;">Track and manage all project expenses</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('expenses.create') }}" class="btn" style="background: rgba(255,255,255,0.95); color: #dc2626; font-weight: 700; padding: 1rem 2rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; box-shadow: 0 8px 25px rgba(0,0,0,0.2); transition: all 0.3s ease; border: none;">
                    <span style="font-size: 1.3rem;">➕</span> Add Expense
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Colorful Expense Summary Stats -->
    <div class="grid grid-cols-6 gap-lg mb-2xl animate-fade-in-up">
        @include('components.stat-card', [
            'icon' => '🧱',
            'label' => 'Materials',
            'value' => 'RWF ' . number_format($statistics['by_type']['materials'] ?? 0, 0),
            'variant' => 'sunset',
            'class' => 'stat-card-enhanced sunset',
            'style' => 'background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); border: 2px solid transparent; background-clip: padding-box;'
        ])

        @include('components.stat-card', [
            'icon' => '👷',
            'label' => 'Labor',
            'value' => 'RWF ' . number_format($statistics['by_type']['labor'] ?? 0, 0),
            'variant' => 'ocean',
            'class' => 'stat-card-enhanced ocean',
            'style' => 'background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%); border: 2px solid transparent; background-clip: padding-box;'
        ])

        @include('components.stat-card', [
            'icon' => '📐',
            'label' => 'Design Phase',
            'value' => 'RWF ' . number_format($statistics['by_phase']['design'] ?? 0, 0),
            'variant' => 'purple',
            'class' => 'stat-card-enhanced purple',
            'style' => 'background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border: 2px solid transparent; background-clip: padding-box;'
        ])

        @include('components.stat-card', [
            'icon' => '🏗️',
            'label' => 'Execution',
            'value' => 'RWF ' . number_format($statistics['by_phase']['execution'] ?? 0, 0),
            'variant' => 'rainbow',
            'class' => 'stat-card-enhanced',
            'style' => 'background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid transparent; background-clip: padding-box;'
        ])

        @include('components.stat-card', [
            'icon' => '🏢',
            'label' => 'Office',
            'value' => 'RWF ' . number_format($statistics['by_type']['office'] ?? 0, 0),
            'variant' => 'secondary',
            'style' => 'background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #ef4444;'
        ])

        @include('components.stat-card', [
            'icon' => '💰',
            'label' => 'Total Expenses',
            'value' => 'RWF ' . number_format($statistics['grand_total'], 0),
            'variant' => 'primary',
            'style' => 'background: linear-gradient(135deg, #fecaca 0%, #f87171 100%); border-left: 4px solid #dc2626; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);'
        ])
    </div>

    <!-- Advanced Filters -->
    <div class="card mb-xl">
        <div class="card-header">
            <h3 class="card-title">
                <span class="icon">🔍</span>
                Advanced Filters
            </h3>
            <button type="button" class="btn btn-ghost btn-sm" onclick="toggleFilters()">
                <span id="filter-toggle-text">Show Filters</span>
            </button>
        </div>
        <div class="card-body" id="filters-panel" style="display: none;">
            <form method="GET" action="{{ route('expenses.index') }}" class="filter-form">
                <div class="grid grid-cols-4 gap-md mb-lg">
                    <!-- Search -->
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search"
                               value="{{ request('search') }}"
                               placeholder="Search expenses..."
                               class="form-input">
                    </div>

                    <!-- Category Filter -->
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control-colorful">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Project Filter -->
                    <div class="form-group">
                        <label for="project_id">Project</label>
                        <select name="project_id" id="project_id" class="form-control-colorful">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Expense Type Filter -->
                    <div class="form-group">
                        <label for="expense_type">Type</label>
                        <select name="expense_type" id="expense_type" class="form-control-colorful">
                            <option value="">All Types</option>
                            @foreach($expenseTypes as $type)
                                <option value="{{ $type }}"
                                        {{ request('expense_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-md">
                    <!-- Date From -->
                    <div class="form-group">
                        <label for="date_from">From Date</label>
                        <input type="date" name="date_from" id="date_from"
                               value="{{ request('date_from') }}"
                               class="form-control-colorful">
                    </div>

                    <!-- Date To -->
                    <div class="form-group">
                        <label for="date_to">To Date</label>
                        <input type="date" name="date_to" id="date_to"
                               value="{{ request('date_to') }}"
                               class="form-input">
                    </div>

                    <!-- Phase Filter -->
                    <div class="form-group">
                        <label for="phase">Phase</label>
                        <select name="phase" id="phase" class="form-select">
                            <option value="">All Phases</option>
                            @foreach($phases as $phase)
                                <option value="{{ $phase }}"
                                        {{ request('phase') == $phase ? 'selected' : '' }}>
                                    {{ ucfirst($phase) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="form-group" style="align-self: end;">
                        <button type="submit" class="btn btn-primary mr-sm">Apply Filters</button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Professional Quick Stats Bar -->
    <div class="stats-bar mb-xl" style="background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 50%, #db2777 100%); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 40px rgba(124, 58, 237, 0.3);">
        <div class="stat-item" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
            <span class="stat-label" style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;">📊 Total Expenses</span>
            <span class="stat-value" style="color: white; font-size: 2rem; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">{{ $statistics['count'] }}</span>
        </div>
        <div class="stat-item" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
            <span class="stat-label" style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;">📈 Average Amount</span>
            <span class="stat-value" style="color: white; font-size: 1.5rem; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">RWF {{ number_format($statistics['avg_amount'], 0) }}</span>
        </div>
        <div class="stat-item" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
            <span class="stat-label" style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;">📅 This Month</span>
            <span class="stat-value" style="color: #fde047; font-size: 1.5rem; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">RWF {{ number_format($statistics['month_total'], 0) }}</span>
        </div>
        <div class="stat-item" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
            <span class="stat-label" style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;">📆 Today</span>
            <span class="stat-value" style="color: #4ade80; font-size: 1.5rem; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">RWF {{ number_format($statistics['today_total'], 0) }}</span>
        </div>
    </div>

    <!-- Enhanced Colorful Expenses Table -->
    <div class="card-colorful" style="background: var(--white); border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header" style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 50%, #f59e0b 100%); color: white; padding: 2rem; border-radius: 24px 24px 0 0;">
            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                <span style="font-size: 1.8rem; animation: bounce 2s infinite;">💸</span>
                Expense Records
            </h3>
            <div class="card-actions">
                <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 0.75rem 1.5rem; border-radius: 50px; font-weight: 700; border: 1px solid rgba(255,255,255,0.3);">{{ $expenses->total() }} total expenses</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($expenses->count() > 0)
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table-enhanced" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                <th style="padding: 1.25rem; text-align: left; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none;">
                                    <span style="animation: pulse 2s infinite;">📅</span> Date
                                </th>
                                <th style="padding: 1.25rem; text-align: left; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none;">
                                    <span style="animation: bounce 2s infinite;">📦</span> Item
                                </th>
                                <th style="padding: 1.25rem; text-align: left; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none;">
                                    <span style="animation: pulse 2s infinite;">📁</span> Category
                                </th>
                                <th style="padding: 1.25rem; text-align: left; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none;">
                                    <span style="animation: bounce 2s infinite;">🏷️</span> Type
                                </th>
                                <th style="padding: 1.25rem; text-align: left; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none;">
                                    <span style="animation: pulse 2s infinite;">🏗️</span> Project
                                </th>
                                <th style="padding: 1.25rem; text-align: right; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none;">
                                    <span style="animation: bounce 2s infinite;">💰</span> Amount
                                </th>
                                <th style="padding: 1.25rem; text-align: center; color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; border: none; width: 120px;">
                                    <span style="animation: pulse 2s infinite;">⚡</span> Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr class="expense-row" data-expense-id="{{ $expense->id }}">
                                    <td>
                                        <div class="date-display">
                                            <strong>{{ $expense->date->format('M j') }}</strong>
                                            <small class="text-muted d-block">{{ $expense->days_ago }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="item-info">
                                            <strong>{{ $expense->item_name }}</strong>
                                            @if($expense->notes)
                                                <small class="text-muted d-block">{{ Str::limit($expense->notes, 50) }}</small>
                                            @endif
                                            @if($expense->quantity && $expense->unit)
                                                <small class="text-info d-block">
                                                    {{ $expense->quantity }} {{ $expense->unit }}
                                                    @if($expense->getEffectiveUnitPrice())
                                                        @ RWF {{ number_format($expense->getEffectiveUnitPrice(), 0) }}
                                                    @endif
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($expense->category)
                                            <span class="badge badge-category">{{ $expense->category->name }}</span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="type-display">
                                            <span class="badge badge-{{
                                                match($expense->expense_type) {
                                                    'materials' => 'error',
                                                    'labor' => 'success',
                                                    'office' => 'info',
                                                    'equipment' => 'warning',
                                                    'transport' => 'secondary',
                                                    default => 'primary'
                                                }
                                            }}">
                                                {{ $expense->type_label }}
                                            </span>
                                            @if($expense->phase_label)
                                                <small class="text-muted d-block">{{ $expense->phase_label }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($expense->project)
                                            <a href="{{ route('projects.show', $expense->project) }}" class="project-link">
                                                {{ $expense->project->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">General</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <span class="amount-display">{{ $expense->formatted_amount }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('expenses.show', $expense) }}"
                                               class="btn btn-ghost btn-sm" title="View">
                                                👁️
                                            </a>
                                            <a href="{{ route('expenses.edit', $expense) }}"
                                               class="btn btn-ghost btn-sm" title="Edit">
                                                ✏️
                                            </a>
                                            <button type="button"
                                                    class="btn btn-ghost btn-sm text-danger"
                                                    title="Delete"
                                                    onclick="deleteExpense({{ $expense->id }})">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $expenses->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <h3>No expenses found</h3>
                    <p>Start tracking your expenses by creating your first expense record.</p>
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                        ➕ Add First Expense
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Enhanced color palette for expenses */
:root {
    --expense-materials: #ef4444;
    --expense-materials-light: #fef2f2;
    --expense-materials-dark: #991b1b;

    --expense-labor: #10b981;
    --expense-labor-light: #f0fdf4;
    --expense-labor-dark: #047857;

    --expense-office: #3b82f6;
    --expense-office-light: #eff6ff;
    --expense-office-dark: #1d4ed8;

    --expense-equipment: #f59e0b;
    --expense-equipment-light: #fffbeb;
    --expense-equipment-dark: #d97706;

    --expense-transport: #8b5cf6;
    --expense-transport-light: #f5f3ff;
    --expense-transport-dark: #7c3aed;

    --expense-design: #06b6d4;
    --expense-design-light: #f0fdfa;
    --expense-design-dark: #0891b2;

    --expense-execution: #f97316;
    --expense-execution-light: #fff7ed;
    --expense-execution-dark: #ea580c;
}

/* Modern expense-specific styles */
.stats-bar {
    display: flex;
    gap: var(--space-lg);
    padding: var(--space-lg);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-xl);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.stat-item {
    text-align: center;
    flex: 1;
    padding: var(--space-md);
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
    position: relative;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.7);
}

.stat-label {
    display: block;
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: var(--space-xs);
    font-weight: 500;
}

.stat-value {
    display: block;
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: #dc2626;
    text-shadow: 0 1px 2px rgba(220, 38, 38, 0.1);
}

.date-display strong {
    color: var(--expense-design);
    font-weight: 700;
}

.item-info strong {
    color: var(--gray-900);
    font-size: 1.05em;
}

.type-display {
    display: flex;
    flex-direction: column;
    gap: var(--space-xs);
}

.amount-display {
    font-weight: 800;
    font-size: 1.15rem;
    background: linear-gradient(135deg, #dc2626, #ea580c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: none;
}

.expense-row {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid transparent;
    background: linear-gradient(90deg, transparent 0%, rgba(249, 115, 22, 0.02) 100%);
}

.expense-row td {
    padding: 1.25rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    vertical-align: middle;
}

.expense-row:nth-child(even) {
    background: linear-gradient(90deg, rgba(251, 146, 60, 0.05) 0%, rgba(249, 115, 22, 0.02) 100%);
}

.expense-row:hover {
    background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);
    border-left-color: #f97316;
    transform: translateX(4px);
    box-shadow: 0 8px 25px rgba(249, 115, 22, 0.2);
}

.filter-form .grid {
    align-items: end;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #fef7e7 0%, #feefc3 50%, #fef3c7 100%);
    border-radius: 24px;
    border: 2px dashed #f97316;
    position: relative;
    overflow: hidden;
}

.empty-state::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 20%, rgba(249, 115, 22, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(234, 88, 12, 0.1) 0%, transparent 50%);
    z-index: 0;
}

.empty-state > * {
    position: relative;
    z-index: 1;
}

.empty-state-icon {
    font-size: 5rem;
    margin-bottom: 1.5rem;
    animation: bounce 2s infinite;
}

.empty-state h3 {
    margin-bottom: var(--space-md);
    color: var(--gray-700);
    font-weight: 600;
}

.empty-state p {
    color: var(--gray-500);
    margin-bottom: var(--space-lg);
}

/* Enhanced Badge variants with specific colors */
.badge {
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-md);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: var(--space-xs);
}

.badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Expense type specific badges */
.badge-error {
    background: var(--expense-materials-light);
    color: var(--expense-materials-dark);
    border-color: var(--expense-materials);
}

.badge-success {
    background: var(--expense-labor-light);
    color: var(--expense-labor-dark);
    border-color: var(--expense-labor);
}

.badge-warning {
    background: var(--expense-equipment-light);
    color: var(--expense-equipment-dark);
    border-color: var(--expense-equipment);
}

.badge-info {
    background: var(--expense-office-light);
    color: var(--expense-office-dark);
    border-color: var(--expense-office);
}

.badge-secondary {
    background: var(--expense-transport-light);
    color: var(--expense-transport-dark);
    border-color: var(--expense-transport);
}

.badge-primary {
    background: var(--expense-design-light);
    color: var(--expense-design-dark);
    border-color: var(--expense-design);
}

/* Category badges */
.badge-category {
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    color: #4338ca;
    border: 1px solid #a5b4fc;
    font-weight: 700;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.badge-category:hover {
    background: linear-gradient(135deg, #4338ca, #6366f1);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
}

/* Amount highlighting based on value */
.amount-high {
    color: #991b1b !important;
    font-weight: 800;
    text-shadow: 0 1px 3px rgba(153, 27, 27, 0.2);
}

.amount-medium {
    color: #dc2626 !important;
    font-weight: 700;
    text-shadow: 0 1px 2px rgba(220, 38, 38, 0.2);
}

.amount-low {
    color: #ef4444 !important;
    font-weight: 600;
    text-shadow: 0 1px 1px rgba(239, 68, 68, 0.1);
}

/* Project link styling */
.project-link {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    background: rgba(124, 58, 237, 0.05);
}

.project-link:hover {
    color: white;
    background: linear-gradient(135deg, #7c3aed, #9333ea);
    border-bottom-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

/* Action button enhancements */
.btn-group-sm {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-group-sm .btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
    font-size: 1.1rem;
    background: rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.05);
}

.btn-group-sm .btn:nth-child(1):hover {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}

.btn-group-sm .btn:nth-child(2):hover {
    background: linear-gradient(135deg, #10b981, #059669);
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.btn-group-sm .btn:nth-child(3):hover {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white !important;
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}

/* Card enhancements */
.card {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

/* Filter panel styling */
#filters-panel {
    background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: var(--radius-lg);
    margin-top: var(--space-md);
}

/* Table header enhancements */
.table thead th {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: var(--gray-700);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: var(--font-size-sm);
    border-bottom: 2px solid #cbd5e1;
}

/* Professional Animations */
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        animation-timing-function: cubic-bezier(.215,.61,.355,1);
        transform: translateY(0);
    }
    40%, 43% {
        animation-timing-function: cubic-bezier(.755,.05,.855,.06);
        transform: translateY(-8px);
    }
    70% {
        animation-timing-function: cubic-bezier(.755,.05,.855,.06);
        transform: translateY(-4px);
    }
    90% {
        transform: translateY(-2px);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Card Footer Enhancement */
.card-footer {
    background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);
    padding: 1.5rem;
    border-top: 1px solid rgba(249, 115, 22, 0.2);
    border-radius: 0 0 24px 24px;
}

/* Date Display Enhancement */
.date-display {
    padding: 0.5rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
    border-radius: 10px;
    border-left: 3px solid #06b6d4;
}

.date-display strong {
    color: #0891b2;
    font-weight: 700;
    font-size: 1.1rem;
}

/* Item Info Enhancement */
.item-info {
    padding: 0.5rem 0;
}

.item-info strong {
    color: #1e293b;
    font-size: 1.05rem;
    font-weight: 700;
}
</style>

<script>
function toggleFilters() {
    const panel = document.getElementById('filters-panel');
    const toggleText = document.getElementById('filter-toggle-text');

    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        toggleText.textContent = 'Hide Filters';
    } else {
        panel.style.display = 'none';
        toggleText.textContent = 'Show Filters';
    }
}

function deleteExpense(expenseId) {
    if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/expenses/${expenseId}`;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-submit filter form when certain fields change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitFields = ['category_id', 'project_id', 'expense_type', 'phase'];

    autoSubmitFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });

    // Show filters if any are applied
    const hasFilters = {{ request()->hasAny(['search', 'category_id', 'project_id', 'expense_type', 'date_from', 'date_to', 'phase']) ? 'true' : 'false' }};
    if (hasFilters) {
        toggleFilters();
    }

    // Dynamic amount color coding
    const amountDisplays = document.querySelectorAll('.amount-display');
    amountDisplays.forEach(element => {
        const amountText = element.textContent.replace(/[^0-9]/g, '');
        const amount = parseInt(amountText);

        if (amount > 500000) {
            element.classList.add('amount-high');
        } else if (amount > 100000) {
            element.classList.add('amount-medium');
        } else {
            element.classList.add('amount-low');
        }
    });
});
</script>
@endsection
