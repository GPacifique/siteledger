<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Project extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'name',
        'start_date',
        'status',      // e.g., planned, active, completed
        'start_date',
        'end_date',
        'contract_value',
        'amount_paid',
        'amount_remaining',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_value' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_remaining' => 'decimal:2',
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

}
