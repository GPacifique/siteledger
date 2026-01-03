<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Project extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * Available project phases
     */
    public const PHASES = [
        'design' => 'Design Phase',
        'execution' => 'Execution Phase',
    ];

    /**
     * Available phase statuses
     */
    public const PHASE_STATUSES = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
    ];

    protected $fillable = [
        'tenant_id',
        'client_id',
        'name',
        'start_date',
        'status',      // e.g., planned, active, completed
        'end_date',
        'contract_value',
        'amount_paid',
        'amount_remaining',
        'notes',
        // Phase fields
        'current_phase',
        'design_phase_value',
        'design_phase_paid',
        'design_start_date',
        'design_end_date',
        'design_phase_status',
        'execution_phase_value',
        'execution_phase_paid',
        'execution_start_date',
        'execution_end_date',
        'execution_phase_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_value' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_remaining' => 'decimal:2',
        // Phase casts
        'design_phase_value' => 'decimal:2',
        'design_phase_paid' => 'decimal:2',
        'design_start_date' => 'date',
        'design_end_date' => 'date',
        'execution_phase_value' => 'decimal:2',
        'execution_phase_paid' => 'decimal:2',
        'execution_start_date' => 'date',
        'execution_end_date' => 'date',
    ];
public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function workers()
    {
        // Get workers through tasks assigned to this project
        return Worker::whereIn('id', $this->tasks()->pluck('assigned_to'))->distinct();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get phase payments for this project
     */
    public function phasePayments()
    {
        return $this->hasMany(ProjectPhasePayment::class);
    }

    /**
     * Get design phase payments
     */
    public function designPhasePayments()
    {
        return $this->hasMany(ProjectPhasePayment::class)->where('phase', 'design');
    }

    /**
     * Get execution phase payments
     */
    public function executionPhasePayments()
    {
        return $this->hasMany(ProjectPhasePayment::class)->where('phase', 'execution');
    }

    /**
     * Get design phase remaining amount
     */
    public function getDesignPhaseRemainingAttribute()
    {
        return max(0, $this->design_phase_value - $this->design_phase_paid);
    }

    /**
     * Get execution phase remaining amount
     */
    public function getExecutionPhaseRemainingAttribute()
    {
        return max(0, $this->execution_phase_value - $this->execution_phase_paid);
    }

    /**
     * Get total phase value (design + execution)
     */
    public function getTotalPhaseValueAttribute()
    {
        return $this->design_phase_value + $this->execution_phase_value;
    }

    /**
     * Get total phase paid (design + execution)
     */
    public function getTotalPhasePaidAttribute()
    {
        return $this->design_phase_paid + $this->execution_phase_paid;
    }

    /**
     * Get current phase label
     */
    public function getCurrentPhaseLabelAttribute()
    {
        return self::PHASES[$this->current_phase] ?? ucfirst($this->current_phase ?? 'design');
    }

    /**
     * Check if design phase is complete
     */
    public function isDesignPhaseComplete()
    {
        return $this->design_phase_status === 'completed';
    }

    /**
     * Check if execution phase is complete
     */
    public function isExecutionPhaseComplete()
    {
        return $this->execution_phase_status === 'completed';
    }

    /**
     * Get design phase progress percentage
     */
    public function getDesignPhaseProgressAttribute()
    {
        if ($this->design_phase_value <= 0) return 0;
        return min(100, round(($this->design_phase_paid / $this->design_phase_value) * 100, 1));
    }

    /**
     * Get execution phase progress percentage
     */
    public function getExecutionPhaseProgressAttribute()
    {
        if ($this->execution_phase_value <= 0) return 0;
        return min(100, round(($this->execution_phase_paid / $this->execution_phase_value) * 100, 1));
    }

    /**
     * Update phase paid amounts from payments
     */
    public function recalculatePhasePaid()
    {
        $this->design_phase_paid = $this->phasePayments()
            ->where('phase', 'design')
            ->where('status', 'completed')
            ->sum('amount');

        $this->execution_phase_paid = $this->phasePayments()
            ->where('phase', 'execution')
            ->where('status', 'completed')
            ->sum('amount');

        // Also update total amount_paid
        $this->amount_paid = $this->design_phase_paid + $this->execution_phase_paid;
        $this->amount_remaining = $this->contract_value - $this->amount_paid;

        $this->save();
    }

}
