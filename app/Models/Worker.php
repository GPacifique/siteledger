<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Worker extends Model
{
    use HasFactory, BelongsToTenant;

    // Fillable fields for mass assignment
    protected $fillable = [
        'tenant_id',
        // base identity fields (actual controller uses these extensively)
        'first_name','last_name','email','phone','position','status','notes','created_by',
        // monetary fields (stored in RWF)
        'daily_wage','currency',
        // metadata
        'hired_at',
        // direct project link
        'project_id',
    ];
    /**
     * The project this worker is directly assigned to (if any).
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Cast attributes to native types
    protected $casts = [
        'hired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the worker's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: 'Unknown';
    }

    // daily_wage is now stored directly in RWF, no conversion needed

    /**
     * A worker can have many tasks (assigned via worker_id field).
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'worker_id', 'id');
    }

    /**
     * A worker can have many daily payments.
     */
    public function payments()
    {
        return $this->hasMany(WorkerPayment::class);
    }

    /**
     * Projects this worker participates in via tasks.
     */
    public function projects()
    {
        return Project::whereIn('id', $this->tasks()->pluck('project_id'))->distinct();
    }

    /**
     * The user who created this worker (attendant).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the total wages for this worker.
     */
    public function totalWages()
    {
        // Prefer payments if present; fall back to tasks daily_wage when used
        $payments = (float) $this->payments()->sum('amount');
        if ($payments > 0) {
            return $payments;
        }
        return $this->tasks()->sum('daily_wage');
    }

    /**
     * Get wages for a specific date.
     */
    public function wagesByDate($date)
    {
        $byPayments = (float) $this->payments()->whereDate('paid_on', $date)->sum('amount');
        if ($byPayments > 0) {
            return $byPayments;
        }
        return $this->tasks()
                    ->where('date', $date)
                    ->sum('daily_wage');
    }
}
