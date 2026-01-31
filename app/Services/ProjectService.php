<?php
namespace App\Services;

use App\Models\Project;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\WorkerPayment;

class ProjectService
{
    /**
     * Recalculate totals for a project: expenses, payments, worker payments, and profit.
     */
    public function recalculateTotals(int $projectId): void
    {
        if (!$projectId) return;

        $project = Project::find($projectId);
        if (!$project) return;

        $expenses = (float) Expense::where('project_id', $projectId)->sum('total');
        $payments = (float) Payment::where('project_id', $projectId)->sum('amount');

        // Calculate Phase Specific Payments
        $designPaid = (float) Payment::where('project_id', $projectId)
                                     ->where('phase', 'design')
                                     ->sum('amount');

        $executionPaid = (float) Payment::where('project_id', $projectId)
                                       ->where('phase', 'execution')
                                       ->sum('amount');

        // Worker payments (if model exists)
        $workerPayments = 0;
        if (class_exists(WorkerPayment::class)) {
            $workerPayments = (float) WorkerPayment::where('project_id', $projectId)->sum('amount');
        }

        $totalSpent = $expenses + $workerPayments;

        $project->forceFill([
            'amount_spent' => $totalSpent,
            'amount_paid' => $payments,
            'profit' => ($project->contract_value ?? 0) - $totalSpent,
            'design_phase_paid' => $designPaid,
            'execution_phase_paid' => $executionPaid,
        ])->save();
    }
}
