<?php
require __DIR__ . '/bootstrap/app.php';

$app = app();

$project = App\Models\Project::find(9);

echo "Project 9 Status:\n";
echo "Contract Value: " . number_format($project->contract_value) . " RWF\n";
echo "Amount Paid: " . number_format($project->amount_paid) . " RWF\n";
echo "Amount Remaining: " . number_format($project->amount_remaining) . " RWF\n";
echo "Design Phase Paid: " . number_format($project->design_phase_paid) . " RWF\n";
echo "Execution Phase Paid: " . number_format($project->execution_phase_paid) . " RWF\n";
echo "\nHas Overpayment: " . ($project->hasOverpayment() ? 'Yes' : 'No') . "\n";
if ($project->hasOverpayment()) {
    echo "Overpayment Amount: " . number_format($project->getOverpaymentAmount()) . " RWF\n";
    echo "Actual Remaining: " . number_format($project->actual_remaining) . " RWF\n";
}
