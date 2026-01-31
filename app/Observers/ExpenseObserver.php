<?php
namespace App\Observers;

use App\Models\Expense;
use App\Services\PhaseCostService;
use App\Services\ProjectService;

class ExpenseObserver
{
    protected PhaseCostService $phaseCostService;
    protected ProjectService $projectService;

    public function __construct()
    {
        $this->phaseCostService = new PhaseCostService();
        $this->projectService = new ProjectService();
    }

    public function created(Expense $expense)
    {
        $this->handleChange($expense);
    }

    public function updated(Expense $expense)
    {
        $this->handleChange($expense);
    }

    public function deleted(Expense $expense)
    {
        $this->handleChange($expense);
    }

    protected function handleChange(Expense $expense)
    {
        if ($expense->phase_id) {
            $this->phaseCostService->recalculateForPhase($expense->phase_id);
        }
        if ($expense->project_id) {
            $this->projectService->recalculateTotals($expense->project_id);
        }
    }
}
