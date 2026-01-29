<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\DesignPhase;

class DesignPhaseSeeder extends Seeder
{
    public function run(): void
    {
        // For each project, add all standard design phases
        $phases = ['Concept', 'Preliminary', 'Detailed', 'Final'];
        $status = 'pending';
        foreach (Project::all() as $project) {
            foreach ($phases as $phase) {
                DesignPhase::create([
                    'project_id' => $project->id,
                    'phase_name' => $phase,
                    'status' => $status,
                ]);
            }
        }
    }
}
