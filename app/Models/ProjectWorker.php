<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectWorker extends Model
{
    use HasFactory;

    protected $table = 'project_workers';

    protected $fillable = [
        'project_id', 'worker_id', 'phase_id', 'role_on_project', 'start_date', 'end_date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
