<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class PopulateProjectData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:populate-data {id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate sample project data for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectId = $this->argument('id');

        $project = Project::find($projectId);

        if (!$project) {
            $this->error("Project {$projectId} not found.");
            return;
        }

        $this->info("Found project: {$project->name}");
        $this->info("Current values:");
        $this->info("  Design Phase Value: RWF " . number_format($project->design_phase_value ?? 0, 2));
        $this->info("  Design Phase Paid: RWF " . number_format($project->design_phase_paid ?? 0, 2));

        // Update with sample data if values are zero or add some payment progress
        if (($project->design_phase_paid ?? 0) == 0) {
            $this->info("Adding some payment data to show real progress...");
            $project->update([
                'design_phase_paid' => 32500000, // 32.5 Million paid out of 50M (65% progress)
                'execution_phase_paid' => 150000000, // 150 Million paid out of 450M (33% progress)
                'design_phase_status' => 'in_progress',
                'execution_phase_status' => 'in_progress',
                'project_type' => 'DESIGN_EXECUTION', // Make sure project_type is set correctly
            ]);
            $this->info("Project updated with payment data.");
        }

        // Ensure project_type is set for dashboard calculations
        if (!$project->project_type) {
            $this->info("Setting project type to DESIGN_EXECUTION...");
            $project->update(['project_type' => 'DESIGN_EXECUTION']);
        }

        // Refresh and show the calculated values
        $project = $project->fresh();
        $this->info("\nUpdated values:");
        $this->info("  Design Phase Value: RWF " . number_format($project->design_phase_value ?? 0, 2));
        $this->info("  Design Phase Paid: RWF " . number_format($project->design_phase_paid ?? 0, 2));
        $this->info("  Design Phase Remaining: RWF " . number_format($project->design_phase_remaining ?? 0, 2));
        $this->info("  Design Phase Progress: " . ($project->design_phase_progress ?? 0) . "%");
        $this->info("  Execution Phase Value: RWF " . number_format($project->execution_phase_value ?? 0, 2));
        $this->info("  Execution Phase Paid: RWF " . number_format($project->execution_phase_paid ?? 0, 2));
        $this->info("  Execution Phase Remaining: RWF " . number_format($project->execution_phase_remaining ?? 0, 2));
        $this->info("  Execution Phase Progress: " . ($project->execution_phase_progress ?? 0) . "%");

        $this->info("\nProject data populated successfully!");
    }
}
