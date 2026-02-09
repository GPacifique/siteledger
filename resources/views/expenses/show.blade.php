@extends('layouts.app')

@section('title', 'Expense Details')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
@endsection

@section('content')
<div class="page-container">
    <!-- Page Header -->
    @include('components.page-header', [
        'title' => 'Expense Details',
        'subtitle' => 'View expense information',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Expenses', 'url' => route('expenses.index')],
            ['label' => 'Details', 'url' => '#']
        ],
        'actions' => [
            ['label' => 'Edit', 'url' => route('expenses.edit', $expense), 'icon' => '✏️', 'class' => 'btn-primary'],
            ['label' => 'Delete', 'url' => '#', 'icon' => '🗑️', 'class' => 'btn-danger', 'onclick' => 'deleteExpense()']
        ]
    ])

    <div class="grid grid-cols-12 gap-xl">
        <!-- Main Content -->
        <div class="col-span-8">
            <!-- Expense Overview Card -->
            <div class="card mb-xl">
                <div class="card-header">
                    <div class="expense-header">
                        <div class="expense-icon">
                            {{
                                match($expense->expense_type) {
                                    'materials' => '🧱',
                                    'labor' => '👷',
                                    'equipment' => '🔧',
                                    'transport' => '🚚',
                                    'office' => '🏢',
                                    default => '📦'
                                }
                            }}
                        </div>
                        <div class="expense-meta">
                            <h1 class="expense-title">{{ $expense->item_name }}</h1>
                            <div class="expense-details">
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
                                    <span class="badge badge-outline">{{ $expense->phase_label }}</span>
                                @endif
                                <span class="text-muted">{{ $expense->days_ago }}</span>
                            </div>
                        </div>
                        <div class="expense-amount">
                            <div class="amount-value">{{ $expense->formatted_amount }}</div>
                            @if($expense->quantity && $expense->unit && $expense->getEffectiveUnitPrice())
                                <div class="amount-breakdown">
                                    {{ $expense->quantity }} {{ $expense->unit }} × RWF {{ number_format($expense->getEffectiveUnitPrice(), 0) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($expense->notes)
                        <div class="expense-notes">
                            <h4>
                                <span class="icon">📝</span>
                                Notes
                            </h4>
                            <p>{{ $expense->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="icon">📊</span>
                        Detailed Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="detail-grid">
                        <div class="detail-section">
                            <h5>Basic Information</h5>
                            <div class="detail-rows">
                                <div class="detail-row">
                                    <span class="detail-label">Date</span>
                                    <span class="detail-value">{{ $expense->date->format('F j, Y') }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Item/Service</span>
                                    <span class="detail-value">{{ $expense->item_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Category</span>
                                    <span class="detail-value">
                                        @if($expense->category)
                                            {{ $expense->category->name }}
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Type</span>
                                    <span class="detail-value">{{ $expense->type_label }}</span>
                                </div>
                                @if($expense->phase)
                                    <div class="detail-row">
                                        <span class="detail-label">Phase</span>
                                        <span class="detail-value">{{ $expense->phase_label }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="detail-section">
                            <h5>Quantity & Pricing</h5>
                            <div class="detail-rows">
                                @if($expense->quantity)
                                    <div class="detail-row">
                                        <span class="detail-label">Quantity</span>
                                        <span class="detail-value">{{ $expense->quantity }}</span>
                                    </div>
                                @endif
                                @if($expense->unit)
                                    <div class="detail-row">
                                        <span class="detail-label">Unit</span>
                                        <span class="detail-value">{{ $expense->unit }}</span>
                                    </div>
                                @endif
                                @if($expense->getEffectiveUnitPrice())
                                    <div class="detail-row">
                                        <span class="detail-label">Unit Price</span>
                                        <span class="detail-value">RWF {{ number_format($expense->getEffectiveUnitPrice(), 2) }}</span>
                                    </div>
                                @endif
                                <div class="detail-row highlight">
                                    <span class="detail-label">Total Amount</span>
                                    <span class="detail-value">{{ $expense->formatted_amount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-4 space-y-lg">
            <!-- Project Information -->
            @if($expense->project)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <span class="icon">🏗️</span>
                            Project
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="project-info">
                            <h5>
                                <a href="{{ route('projects.show', $expense->project) }}" class="text-primary">
                                    {{ $expense->project->name }}
                                </a>
                            </h5>
                            @if($expense->project->description)
                                <p class="text-muted">{{ Str::limit($expense->project->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <span class="icon">🏢</span>
                            General Expense
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">This expense is not assigned to any specific project.</p>
                    </div>
                </div>
            @endif

            <!-- Record Information -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <span class="icon">ℹ️</span>
                        Record Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="detail-rows">
                        <div class="detail-row">
                            <span class="detail-label">Created by</span>
                            <span class="detail-value">
                                @if($expense->user)
                                    {{ $expense->user->name }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Created on</span>
                            <span class="detail-value">{{ $expense->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @if($expense->updated_at != $expense->created_at)
                            <div class="detail-row">
                                <span class="detail-label">Last updated</span>
                                <span class="detail-value">{{ $expense->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <span class="icon">⚡</span>
                        Quick Actions
                    </h4>
                </div>
                <div class="card-body">
                    <div class="action-buttons space-y-sm">
                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-primary btn-block">
                            <span>✏️</span>
                            Edit Expense
                        </a>
                        <a href="{{ route('expenses.create') }}" class="btn btn-secondary btn-block">
                            <span>➕</span>
                            Add New Expense
                        </a>
                        <button type="button" class="btn btn-danger btn-block" onclick="deleteExpense()">
                            <span>🗑️</span>
                            Delete Expense
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
/* Expense Header */
.expense-header {
    display: flex;
    align-items: flex-start;
    gap: var(--space-lg);
}

.expense-icon {
    font-size: 3rem;
    width: 4rem;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    border-radius: var(--radius-lg);
}

.expense-meta {
    flex: 1;
}

.expense-title {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--space-sm);
}

.expense-details {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    flex-wrap: wrap;
}

.expense-amount {
    text-align: right;
}

.amount-value {
    font-size: var(--font-size-3xl);
    font-weight: 800;
    color: var(--primary);
    line-height: 1;
}

.amount-breakdown {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-top: var(--space-xs);
}

/* Notes Section */
.expense-notes {
    padding: var(--space-lg);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--primary);
}

.expense-notes h4 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-md);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.expense-notes p {
    color: var(--gray-700);
    line-height: 1.6;
    margin: 0;
}

/* Detail Grid */
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-xl);
}

.detail-section h5 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-lg);
    padding-bottom: var(--space-sm);
    border-bottom: 2px solid var(--gray-200);
}

.detail-rows {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-sm) 0;
}

.detail-row.highlight {
    background: var(--primary-50);
    padding: var(--space-md);
    border-radius: var(--radius-md);
    margin-top: var(--space-md);
    font-weight: 600;
}

.detail-label {
    color: var(--gray-600);
    font-weight: 500;
}

.detail-value {
    color: var(--gray-900);
    font-weight: 600;
    text-align: right;
}

/* Project Info */
.project-info h5 {
    margin-bottom: var(--space-sm);
}

.project-info p {
    font-size: var(--font-size-sm);
    line-height: 1.5;
    margin: 0;
}

/* Action Buttons */
.action-buttons .btn {
    justify-content: flex-start;
    gap: var(--space-sm);
}

/* Badge variants */
.badge {
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-outline {
    background: transparent;
    border: 1px solid var(--gray-300);
    color: var(--gray-700);
}

.badge-error { background: var(--error-light); color: var(--error-dark); }
.badge-success { background: var(--success-light); color: var(--success-dark); }
.badge-warning { background: var(--warning-light); color: var(--warning-dark); }
.badge-info { background: var(--info-light); color: var(--info-dark); }
.badge-secondary { background: var(--gray-100); color: var(--gray-700); }
.badge-primary { background: var(--primary-100); color: var(--primary-700); }

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-12 .col-span-8,
    .grid-cols-12 .col-span-4 {
        grid-column: span 12;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .expense-header {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .expense-amount {
        text-align: center;
        margin-top: var(--space-md);
    }
}
</style>

<script>
function deleteExpense() {
    if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
