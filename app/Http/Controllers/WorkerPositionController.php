<?php

namespace App\Http\Controllers;

use App\Models\WorkerPosition;
use Illuminate\Http\Request;

class WorkerPositionController extends Controller
{
    /**
     * Display a listing of worker positions
     */
    public function index()
    {
        $positions = WorkerPosition::orderBy('category')->orderBy('name')->get();
        $categories = WorkerPosition::categories();

        // Group by category for better display
        $positionsByCategory = $positions->groupBy('category');

        return view('positions.index', compact('positions', 'positionsByCategory', 'categories'));
    }

    /**
     * Show the form for creating a new position
     */
    public function create()
    {
        $categories = WorkerPosition::categories();
        $seniorityLevels = WorkerPosition::seniorityLevels();

        return view('positions.create', compact('categories', 'seniorityLevels'));
    }

    /**
     * Store a newly created position
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:worker_positions,name|max:100',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|in:' . implode(',', WorkerPosition::categories()),
            'seniority_level' => 'nullable|integer|between:1,5',
            'is_active' => 'boolean',
        ]);

        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['is_active'] = $request->boolean('is_active', true);

        WorkerPosition::create($data);

        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    /**
     * Display the specified position
     */
    public function show(WorkerPosition $position)
    {
        $position->load('workers');
        $activeWorkers = $position->getActiveWorkerCount();
        $totalWorkers = $position->workers()->count();

        return view('positions.show', compact('position', 'activeWorkers', 'totalWorkers'));
    }

    /**
     * Show the form for editing the specified position
     */
    public function edit(WorkerPosition $position)
    {
        $categories = WorkerPosition::categories();
        $seniorityLevels = WorkerPosition::seniorityLevels();

        return view('positions.edit', compact('position', 'categories', 'seniorityLevels'));
    }

    /**
     * Update the specified position
     */
    public function update(Request $request, WorkerPosition $position)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:worker_positions,name,' . $position->id . '|max:100',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|in:' . implode(',', WorkerPosition::categories()),
            'seniority_level' => 'nullable|integer|between:1,5',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $position->update($data);

        return redirect()->route('positions.show', $position)->with('success', 'Position updated successfully.');
    }

    /**
     * Remove the specified position
     */
    public function destroy(WorkerPosition $position)
    {
        // Check if any workers have this position
        $workerCount = $position->workers()->count();
        if ($workerCount > 0) {
            return redirect()->route('positions.index')->with('error', "Cannot delete position '{$position->name}' - it has $workerCount assigned workers.");
        }

        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}
