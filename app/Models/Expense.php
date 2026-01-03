<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class Expense extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'tenant_id',
        'date',
        'category',
        'expense_type',
        'phase',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'description',
        'project_id',
        'client_id',
        'amount',
        'method',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    // Expense types
    public const EXPENSE_TYPES = [
        'materials' => 'Materials',
        'labor' => 'Labor',
        'equipment' => 'Equipment',
        'transport' => 'Transport',
        'subcontractor' => 'Subcontractor',
        'utilities' => 'Utilities',
        'permits' => 'Permits',
        'miscellaneous' => 'Miscellaneous',
    ];

    // Phases (matching project phases)
    public const PHASES = [
        'design' => 'Design Phase',
        'execution' => 'Execution Phase',
    ];

    // Units for materials
    public const UNITS = [
        'pieces' => 'Pieces',
        'kg' => 'Kilograms (kg)',
        'bags' => 'Bags',
        'tons' => 'Tons',
        'liters' => 'Liters',
        'meters' => 'Meters',
        'sqm' => 'Square Meters',
        'cbm' => 'Cubic Meters',
        'rolls' => 'Rolls',
        'sheets' => 'Sheets',
        'boxes' => 'Boxes',
        'trips' => 'Trips',
        'days' => 'Days',
        'hours' => 'Hours',
    ];

    // Optional: centralised categories
    public const CATEGORIES = [
        'Materials',
        'Labor',
        'Equipment',
        'Subcontractor',
        'Transport',
        'Utilities',
        'Permits',
        'Miscellaneous',
    ];

    /**
     * Expense belongs to a Project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Expense belongs to a Client (vendor / supplier / worker).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Expense belongs to a User (registered by).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * Expense belongs to a Tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Check if expense is for materials
     */
    public function isMaterials(): bool
    {
        return $this->expense_type === 'materials';
    }

    /**
     * Check if expense is for labor
     */
    public function isLabor(): bool
    {
        return $this->expense_type === 'labor';
    }

    /**
     * Get formatted expense type
     */
    public function getExpenseTypeLabelAttribute(): string
    {
        return self::EXPENSE_TYPES[$this->expense_type] ?? ucfirst($this->expense_type ?? 'General');
    }

    /**
     * Get formatted phase
     */
    public function getPhaseLabelAttribute(): string
    {
        return self::PHASES[$this->phase] ?? ucfirst($this->phase ?? 'N/A');
    }
}
