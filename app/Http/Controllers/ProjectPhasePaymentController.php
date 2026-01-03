<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectPhasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPhasePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant.data');
    }

    /**
     * Show payments for a specific project phase
     */
    public function index(Project $project, $phase = null)
    {
        $query = $project->phasePayments()->with('receiver')->orderBy('payment_date', 'desc');

        if ($phase && in_array($phase, ['design', 'execution'])) {
            $query->where('phase', $phase);
        }

        $payments = $query->get();

        return view('projects.phase-payments.index', compact('project', 'payments', 'phase'));
    }

    /**
     * Show form to create a new phase payment
     */
    public function create(Project $project, $phase)
    {
        if (!in_array($phase, ['design', 'execution'])) {
            return back()->with('error', 'Invalid phase specified.');
        }

        $phaseValue = $phase === 'design' ? $project->design_phase_value : $project->execution_phase_value;
        $phasePaid = $phase === 'design' ? $project->design_phase_paid : $project->execution_phase_paid;
        $phaseRemaining = $phaseValue - $phasePaid;

        return view('projects.phase-payments.create', compact('project', 'phase', 'phaseValue', 'phasePaid', 'phaseRemaining'));
    }

    /**
     * Store a new phase payment
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'phase' => 'required|in:design,execution',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|in:cash,bank_transfer,check,mobile_money,other',
            'reference_number' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,completed,cancelled',
        ]);

        // Add tenant and user info
        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['project_id'] = $project->id;
        $validated['received_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'completed';

        // Create the payment
        ProjectPhasePayment::create($validated);

        // Recalculate project totals
        $project->recalculatePhasePaid();

        return redirect()->route('projects.show', $project)
            ->with('success', ucfirst($validated['phase']) . ' phase payment recorded successfully.');
    }

    /**
     * Show form to edit a phase payment
     */
    public function edit(Project $project, ProjectPhasePayment $payment)
    {
        // Ensure payment belongs to this project
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $phase = $payment->phase;
        $phaseValue = $phase === 'design' ? $project->design_phase_value : $project->execution_phase_value;
        $phasePaid = $phase === 'design' ? $project->design_phase_paid : $project->execution_phase_paid;
        $phaseRemaining = $phaseValue - $phasePaid + $payment->amount; // Add back current payment for editing

        return view('projects.phase-payments.edit', compact('project', 'payment', 'phase', 'phaseValue', 'phasePaid', 'phaseRemaining'));
    }

    /**
     * Update a phase payment
     */
    public function update(Request $request, Project $project, ProjectPhasePayment $payment)
    {
        // Ensure payment belongs to this project
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|in:cash,bank_transfer,check,mobile_money,other',
            'reference_number' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,completed,cancelled',
        ]);

        $payment->update($validated);

        // Recalculate project totals
        $project->recalculatePhasePaid();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Phase payment updated successfully.');
    }

    /**
     * Delete a phase payment
     */
    public function destroy(Project $project, ProjectPhasePayment $payment)
    {
        // Ensure payment belongs to this project
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $payment->delete();

        // Recalculate project totals
        $project->recalculatePhasePaid();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Phase payment deleted successfully.');
    }
}
