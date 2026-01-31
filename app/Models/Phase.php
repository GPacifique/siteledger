<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $table = 'phases';

    protected $fillable = [
        'project_id',
        'position',
        'name',
        'planned_start',
        'planned_end',
        'actual_start',
        'actual_end',
        'status',
        'budget',
        'notes',
    ];

    protected $casts = [
        'planned_start' => 'date',
        'planned_end' => 'date',
        'actual_start' => 'date',
        'actual_end' => 'date',
        'budget' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
