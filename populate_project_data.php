<?php
/**
 * Quick script to populate sample project phase data for testing
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

try {
    // Find project 1
    $project = App\Models\Project::find(1);

    if (!$project) {
        echo "Project 1 not found. Creating sample project...\n";

        // Create a sample project
        $project = App\Models\Project::create([
            'name' => 'Sample Construction Project',
            'client_id' => 1, // You may need to adjust this based on existing clients
            'contract_value' => 30000000, // 30 Million RWF
            'design_phase_value' => 10000000, // 10 Million RWF for design
            'design_phase_paid' => 6500000, // 6.5 Million paid so far
            'design_phase_status' => 'in_progress',
            'design_start_date' => '2026-01-01',
            'design_end_date' => '2026-03-31',
            'execution_phase_value' => 20000000, // 20 Million RWF for execution
            'execution_phase_paid' => 2000000, // 2 Million paid so far
            'execution_phase_status' => 'pending',
            'execution_start_date' => '2026-04-01',
            'execution_end_date' => '2026-12-31',
            'current_phase' => 'design',
            'status' => 'in_progress',
            'project_type' => 'DESIGN_EXECUTION',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'tenant_id' => 1, // Adjust based on your setup
        ]);
        echo "Created sample project with ID: {$project->id}\n";
    } else {
        echo "Found existing project: {$project->name}\n";
        echo "Current values:\n";
        echo "  Design Phase Value: RWF " . number_format($project->design_phase_value ?? 0, 2) . "\n";
        echo "  Design Phase Paid: RWF " . number_format($project->design_phase_paid ?? 0, 2) . "\n";

        // Update with sample data if values are zero
        if (($project->design_phase_value ?? 0) == 0) {
            echo "Updating project with sample data...\n";
            $project->update([
                'contract_value' => 30000000, // 30 Million RWF
                'design_phase_value' => 10000000, // 10 Million RWF for design
                'design_phase_paid' => 6500000, // 6.5 Million paid so far
                'design_phase_status' => 'in_progress',
                'design_start_date' => '2026-01-01',
                'design_end_date' => '2026-03-31',
                'execution_phase_value' => 20000000, // 20 Million RWF for execution
                'execution_phase_paid' => 2000000, // 2 Million paid so far
                'execution_phase_status' => 'pending',
                'execution_start_date' => '2026-04-01',
                'execution_end_date' => '2026-12-31',
                'current_phase' => 'design',
                'status' => 'in_progress',
                'project_type' => 'DESIGN_EXECUTION',
            ]);
            echo "Project updated with sample data.\n";
        }
    }

    // Refresh and show the calculated values
    $project = $project->fresh();
    echo "\nUpdated values:\n";
    echo "  Design Phase Value: RWF " . number_format($project->design_phase_value ?? 0, 2) . "\n";
    echo "  Design Phase Paid: RWF " . number_format($project->design_phase_paid ?? 0, 2) . "\n";
    echo "  Design Phase Remaining: RWF " . number_format($project->design_phase_remaining ?? 0, 2) . "\n";
    echo "  Design Phase Progress: " . ($project->design_phase_progress ?? 0) . "%\n";
    echo "  Execution Phase Value: RWF " . number_format($project->execution_phase_value ?? 0, 2) . "\n";
    echo "  Execution Phase Paid: RWF " . number_format($project->execution_phase_paid ?? 0, 2) . "\n";
    echo "  Execution Phase Remaining: RWF " . number_format($project->execution_phase_remaining ?? 0, 2) . "\n";
    echo "  Execution Phase Progress: " . ($project->execution_phase_progress ?? 0) . "%\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
