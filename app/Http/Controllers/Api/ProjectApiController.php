<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectFinancialService;
use App\Services\ProjectStatusService;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    protected ProjectFinancialService $financialService;
    protected ProjectStatusService $statusService;

    public function __construct(ProjectFinancialService $financialService, ProjectStatusService $statusService)
    {
        $this->middleware('auth:api');
        $this->financialService = $financialService;
        $this->statusService = $statusService;
    }

    public function index()
    {
        $projects = Project::with('phases')->paginate(20);
        return response()->json($projects);
    }

    public function show(Project $project)
    {
        $project->load('phases', 'payments', 'expenses');
        return response()->json($project);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|integer',
            'project_type' => 'required|in:DESIGN,EXECUTION,DESIGN_EXECUTION',
            'contract_value' => 'required|numeric|min:0',
        ]);

        $project = Project::create($data);

        // optionally create default phases here (caller may provide phases array)

        // compute initial financials and status
        $this->financialService->aggregateProjectCosts($project);
        $this->statusService->updateProjectStatus($project);

        return response()->json($project, 201);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'contract_value' => 'nullable|numeric|min:0'
        ]);

        $project->update($data);
        $this->financialService->aggregateProjectCosts($project);
        $this->statusService->updateProjectStatus($project);

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['deleted' => true]);
    }
}
