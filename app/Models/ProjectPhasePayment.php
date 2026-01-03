<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ProjectPhasePayment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'project_id',
        'phase',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'description',
        'status',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Available phases
     */
    public const PHASES = [
        'design' => 'Design Phase',
        'execution' => 'Execution Phase',
    ];

    /**
     * Available payment methods
     */
    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'check' => 'Check',
        'mobile_money' => 'Mobile Money',
        'other' => 'Other',
    ];

    /**
     * Available statuses
     */
    public const STATUSES = [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Get the project this payment belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who received the payment
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get the tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope to filter by phase
     */
    public function scopeForPhase($query, $phase)
    {
        return $query->where('phase', $phase);
    }

    /**
     * Scope to filter by project
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope for completed payments only
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get phase label
     */
    public function getPhaseLabelAttribute()
    {
        return self::PHASES[$this->phase] ?? ucfirst($this->phase);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst($this->payment_method ?? 'N/A');
    }
}
