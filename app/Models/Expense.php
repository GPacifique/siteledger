<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\BelongsToTenant;

class Expense extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'project_id',
        'expense_category_id',
        'client_id',
        'expense_type',
        'phase',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'price_per_one',
        'total',
        'date',
        'notes',
        'user_id',
        'tenant_id',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'price_per_one' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected $appends = [
        'formatted_amount',
        'type_label',
        'phase_label',
        'days_ago'
    ];

    /**
     * Expense belongs to a client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Expense belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Expense belongs to a category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * Expense belongs to a project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get formatted amount attribute
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'RWF ' . number_format($this->total, 0, '.', ',');
    }

    /**
     * Get human-readable type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->expense_type) {
            'materials' => 'Materials',
            'labor' => 'Labor',
            'office' => 'Office Supplies',
            'equipment' => 'Equipment',
            'transport' => 'Transportation',
            default => ucfirst($this->expense_type)
        };
    }

    /**
     * Get human-readable phase label
     */
    public function getPhaseLabelAttribute(): ?string
    {
        if (!$this->phase) return null;

        return match($this->phase) {
            'design' => 'Design Phase',
            'execution' => 'Execution Phase',
            default => ucfirst($this->phase)
        };
    }

    /**
     * Get days ago from the expense date
     */
    public function getDaysAgoAttribute(): string
    {
        $days = (int) abs(now()->diffInDays($this->date));

        if ($days === 0) return 'Today';
        if ($days === 1) return 'Yesterday';
        if ($days <= 7) return $days . ' days ago';
        if ($days <= 30) return $this->date->format('M j');

        return $this->date->format('M j, Y');
    }

    /**
     * Scope for filtering by expense type
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('expense_type', $type);
    }

    /**
     * Scope for filtering by phase
     */
    public function scopeInPhase(Builder $query, string $phase): Builder
    {
        return $query->where('phase', $phase);
    }

    /**
     * Scope for recent expenses
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    /**
     * Scope for this month's expenses
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    /**
     * Check if this is a labor expense
     */
    public function isLabor(): bool
    {
        return $this->expense_type === 'labor';
    }

    /**
     * Check if this is a material expense
     */
    public function isMaterial(): bool
    {
        return $this->expense_type === 'materials';
    }

    /**
     * Get the effective unit price (handles labor vs material pricing)
     */
    public function getEffectiveUnitPrice(): ?float
    {
        return $this->isLabor() ? $this->price_per_one : $this->unit_price;
    }
}
