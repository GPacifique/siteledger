<?php

namespace App\Services;

use App\Models\Project;

class ProjectStatusService
{
    /** Update project status based on phase statuses */
    public function updateProjectStatus(Project $project): Project
    {
        $project->loadMissing('phases');

        if ($project->phases->contains(fn($p) => $p->status === 'in_progress')) {
            $project->status = 'active';
        } elseif ($project->phases->every(fn($p) => $p->status === 'completed')) {
            $project->status = 'completed';
        } elseif ($project->phases->every(fn($p) => $p->status === 'pending')) {
            $project->status = 'planning';
        } elseif ($project->phases->contains(fn($p) => $p->status === 'on_hold')) {
            $project->status = 'on_hold';
        }

        $project->save();
        return $project;
    }
}
