<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectFinancialService
{
    protected PhaseCostService $phaseCostService;

    public function __construct(PhaseCostService $phaseCostService)
    {
        $this->phaseCostService = $phaseCostService;
    }

    /**
     * Aggregate project costs by summing phase costs and expenses
     */
    public function aggregateProjectCosts(Project $project): array
    {
        $project->loadMissing(['phases', 'payments', 'expenses']);

        $phaseTotals = collect();
        foreach ($project->phases as $phase) {
            $phaseTotals->push($this->phaseCostService->recalculatePhaseCosts($phase));
        }

        $sumPhases = $phaseTotals->sum('total');
        $sumExpenses = $project->expenses->sum('amount');
        $sumPayments = $project->payments->sum('amount');

        $totalCost = $sumPhases + $sumExpenses;
        $profit = ($project->contract_value ?? 0) - $totalCost;

        // Optionally persist aggregated totals to projects table in a cached columns
        DB::table('projects')->where('id', $project->id)->update([
            'updated_at' => now()
        ]);

        return [
            'phases_total' => $sumPhases,
            'expenses_total' => $sumExpenses,
            'payments_total' => $sumPayments,
            'total_cost' => $totalCost,
            'profit' => $profit
        ];
    }
}
