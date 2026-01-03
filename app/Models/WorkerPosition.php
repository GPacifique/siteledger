<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class WorkerPosition extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'hourly_rate',
        'daily_rate',
        'category',
        'seniority_level',
        'is_active',
        'worker_count',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'worker_count' => 'integer',
        'seniority_level' => 'integer',
    ];

    /**
     * Get all workers with this position
     */
    public function workers()
    {
        return $this->hasMany(Worker::class, 'position', 'name');
    }

    /**
     * Get count of active workers with this position
     */
    public function getActiveWorkerCount()
    {
        return $this->workers()->where('status', 'active')->count();
    }

    /**
     * Seniority level labels
     */
    public static function seniorityLevels()
    {
        return [
            1 => 'Junior',
            2 => 'Mid-Level',
            3 => 'Senior',
            4 => 'Lead',
            5 => 'Manager',
        ];
    }

    /**
     * Position categories
     */
    public static function categories()
    {
        return [
            'Management',
            'Technical',
            'Labor',
            'Skilled',
            'Administrative',
            'Other',
        ];
    }
}
