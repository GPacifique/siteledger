<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhaseCost extends Model
{
    protected $table = 'phase_costs';
    protected $fillable = [
        'project_id','phase_id','labor_total','materials_total','equipment_total','transport_total','other_total','total','calculated_at'
    ];

    protected $casts = [
        'labor_total' => 'decimal:2',
        'materials_total' => 'decimal:2',
        'equipment_total' => 'decimal:2',
        'transport_total' => 'decimal:2',
        'other_total' => 'decimal:2',
        'total' => 'decimal:2',
        'calculated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
