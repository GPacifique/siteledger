<?php
$p = App\Models\Project::find(1);
if ($p) {
    $p->design_phase_value = 50000000;
    $p->execution_phase_value = 450000000;
    $p->save();

    // Check if payment exists to avoid duplicate if I run this multiple times
    if (!App\Models\Payment::where('project_id', 1)->where('reference_number', 'REF001')->exists()) {
        App\Models\Payment::create([
            'tenant_id' => $p->tenant_id,
            'project_id' => $p->id,
            'amount' => 10000000,
            'date' => now(),
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF001',
            'phase' => 'design',
            'status' => 'completed',
            'remarks' => 'Initial Design Payment'
        ]);
    }

    (new App\Services\ProjectService())->recalculateTotals(1);
    echo "Updated Project 1 with phase values and payment.\n";
} else {
    echo "Project 1 not found.\n";
}
