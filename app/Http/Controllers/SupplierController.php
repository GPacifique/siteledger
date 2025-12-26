<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\BusinessQueryService;
use App\Services\RbacFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SupplierController extends Controller
{
    protected BusinessQueryService $queryService;
    protected RbacFilterService $rbacFilterService;

    public function __construct(BusinessQueryService $queryService, RbacFilterService $rbacFilterService)
    {
        $this->middleware('auth');
        $this->middleware('tenant.data');
        $this->queryService = $queryService;
        $this->rbacFilterService = $rbacFilterService;
    }

    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        $filteredSuppliers = $this->rbacFilterService->filterSuppliers($query)->get();

        return Inertia::render('Admin/Suppliers', [
            'suppliers' => $filteredSuppliers,
            'filterContext' => $this->rbacFilterService->getFilterContext(),
        ]);
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,NULL,id,tenant_id,' . app('currentTenant')->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_rating' => 'nullable|in:excellent,good,fair,poor',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'active';
        $validated = $this->ensureTenantId($validated);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplierData = $this->queryService->buildRoleBasedQuery('suppliers')
                             ->where('id', $supplier->id)
                             ->first();

        if (!$supplierData) {
            abort(404, 'Supplier not found or access denied.');
        }

        // Get supplier's purchase history
        $recentPurchases = $this->queryService->buildRoleBasedQuery('purchase_orders')
                                ->where('supplier_id', $supplier->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        $stats = [
            'total_orders' => $this->queryService->buildRoleBasedQuery('purchase_orders')
                                   ->where('supplier_id', $supplier->id)
                                   ->count(),
            'total_spent' => $this->queryService->buildRoleBasedQuery('purchase_orders')
                                  ->where('supplier_id', $supplier->id)
                                  ->sum('total_amount'),
            'average_order' => $this->queryService->buildRoleBasedQuery('purchase_orders')
                                    ->where('supplier_id', $supplier->id)
                                    ->avg('total_amount'),
        ];

        return view('suppliers.show', compact('supplier', 'recentPurchases', 'stats'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $supplierData = $this->queryService->buildRoleBasedQuery('suppliers')
                             ->where('id', $supplier->id)
                             ->first();

        if (!$supplierData) {
            abort(404, 'Supplier not found or access denied.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id . ',id,tenant_id,' . app('currentTenant')->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_rating' => 'nullable|in:excellent,good,fair,poor',
            'status' => 'required|in:active,inactive,suspended',
            'notes' => 'nullable|string',
        ]);

        $validated['updated_by'] = Auth::id();
        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        $supplierData = $this->queryService->buildRoleBasedQuery('suppliers')
                             ->where('id', $supplier->id)
                             ->first();

        if (!$supplierData) {
            abort(404, 'Supplier not found or access denied.');
        }

        // Check for existing purchase orders
        $hasPurchases = $this->queryService->buildRoleBasedQuery('purchase_orders')
                             ->where('supplier_id', $supplier->id)
                             ->exists();

        if ($hasPurchases) {
            return back()->with('error', 'Cannot delete supplier with existing purchase orders.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    // Additional methods like create, edit, export, search similar to CustomerController
}
