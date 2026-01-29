<?php
namespace App\Http\Controllers;

use App\Models\Laborer;
use Illuminate\Http\Request;

class LaborerController extends Controller
{
    public function index()
    {
        $laborers = Laborer::all();
        return view('laborers.index', compact('laborers'));
    }

    public function create()
    {
        return view('laborers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        Laborer::create($data);
        return redirect()->route('laborers.index')->with('success', 'Laborer added.');
    }
}
