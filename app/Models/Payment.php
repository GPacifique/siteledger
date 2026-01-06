<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'user_id',
        'project_id',
        'phase',
        'amount',
        'method',
        'reference',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Phase constants
     */
    public const PHASES = [
        'design' => 'Design Phase',
        'execution' => 'Execution Phase',
    ];

    /**
     * Worker relationship (employee_id references workers table)
     */
    public function employee()
    {
        return $this->belongsTo(Worker::class, 'employee_id');
    }

    /**
     * Project relationship
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Example: if payments belong to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Example: if payments belong to an order/invoice
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
