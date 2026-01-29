<?php
namespace App\Http\Controllers;

use App\Models\Laborer;
use App\Models\LaborExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaborExpenseController extends Controller
{
    // List labor expenses with filters and summary
    public function index(Request $request)
    {
        $query = LaborExpense::with('laborer');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('laborer_id')) {
            $query->where('laborer_id', $request->laborer_id);
        }
        if ($request->filled('category')) {
            $query->whereHas('laborer', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        $expenses = $query->orderBy('date', 'desc')->get();

        $totalLaborers = Laborer::count();
        $totalCost = $expenses->sum('amount');

        // Group by period for summary
        $daily = $expenses->where('date', Carbon::today()->toDateString())->sum('amount');
        $weekly = $expenses->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $monthly = $expenses->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');

        return view('labor_expenses.index', compact('expenses', 'totalLaborers', 'totalCost', 'daily', 'weekly', 'monthly'));
    }

    // Show form to add labor expense
    public function create()
    {
        $laborers = Laborer::where('status', 'active')->get();
        return view('labor_expenses.create', compact('laborers'));
    }

    // Store labor expense
    public function store(Request $request)
    {
        $data = $request->validate([
            'laborer_id' => 'required|exists:laborers,id',
            'date' => 'required|date',
            'units' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0.01',
        ]);
        $data['amount'] = $data['units'] * $data['rate'];
        LaborExpense::create($data);
        return redirect()->route('labor_expenses.index')->with('success', 'Labor expense added.');
    }
}
