<?php

namespace App\Services;

use App\Models\Phase;
use App\Models\Expense;
use App\Models\PhaseCost;
use Illuminate\Support\Facades\DB;

class PhaseCostService
{
    /**
     * Recalculate costs for a phase: materials + labor + other expenses
     * Returns array with totals.
     */
    public function recalculatePhaseCosts(Phase $phase): array
    {
        $phase->loadMissing(['materials', 'tasks', 'project', 'expenses']);

        $totalMaterial = $phase->materials->sum(function($m) { return ($m->unit_cost * $m->quantity); });

        // Labor: from tasks actual_hours * hourly_rate
        $totalLabor = $phase->tasks->sum(function($t) { return (($t->actual_hours ?? 0) * ($t->hourly_rate ?? 0)); });

        // Other expenses linked to phase
        $totalOther = $phase->expenses->sum('amount');

        $total = $totalMaterial + $totalLabor + $totalOther;

        // Persist (simple upsert into phase_costs table)
        // Ensure we're using the correct project_id if possible, mostly implied by phase
        $projectId = $phase->project_id;

        DB::table('phase_costs')->updateOrInsert(
            ['phase_id' => $phase->id],
            [
                'project_id' => $projectId,
                'materials_total' => $totalMaterial,
                'labor_total' => $totalLabor,
                'other_total' => $totalOther,
                'total' => $total,
                'calculated_at' => now()
            ]
        );

        return [
            'material' => $totalMaterial,
            'labor' => $totalLabor,
            'other' => $totalOther,
            'total' => $total
        ];
    }

    /**
     * Recalculate aggregated costs for a phase and persist to phase_costs.
     */
    public function recalculateForPhase(int $phaseId): void
    {
        if (!$phaseId) return;

        $sums = Expense::where('phase_id', $phaseId)
            ->selectRaw("SUM(CASE WHEN expense_type = 'labor' THEN total ELSE 0 END) as labor_total,")
            ->selectRaw("SUM(CASE WHEN expense_type = 'material' THEN total ELSE 0 END) as materials_total,")
            ->selectRaw("SUM(CASE WHEN expense_type = 'equipment' THEN total ELSE 0 END) as equipment_total,")
            ->selectRaw("SUM(CASE WHEN expense_type = 'transport' THEN total ELSE 0 END) as transport_total,")
            ->selectRaw("SUM(CASE WHEN expense_type NOT IN ('labor','material','equipment','transport') THEN total ELSE 0 END) as other_total,")
            ->first();

        $labor = (float) ($sums->labor_total ?? 0);
        $materials = (float) ($sums->materials_total ?? 0);
        $equipment = (float) ($sums->equipment_total ?? 0);
        $transport = (float) ($sums->transport_total ?? 0);
        $other = (float) ($sums->other_total ?? 0);
        $total = $labor + $materials + $equipment + $transport + $other;

        $projectId = Expense::where('phase_id', $phaseId)->value('project_id');
        
        if (!$projectId) {
            $projectId = DB::table('phases')->where('id', $phaseId)->value('project_id');
        }

        PhaseCost::updateOrCreate([
            'phase_id' => $phaseId,
        ], [
            'project_id' => $projectId,
            'labor_total' => $labor,
            'materials_total' => $materials,
            'equipment_total' => $equipment,
            'transport_total' => $transport,
            'other_total' => $other,
            'total' => $total,
            'calculated_at' => now(),
        ]);
    }
}
