<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\Downloadable;
use App\Services\RbacFilterService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    use Downloadable;

    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        $this->rbacFilterService = $rbacFilterService;
    }

    public function index()
    {
        return Inertia::render('Admin/Placeholder', ['page' => 'Products', 'filterContext' => $this->rbacFilterService->getFilterContext()]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $validated = $this->ensureTenantId($validated);
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    /**
     * Export products as CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = $request->get('filename', 'products');

        $products = Product::latest()->get();

        $headers = [
            'id' => 'ID',
            'name' => 'Product Name',
            'description' => 'Description',
            'price' => 'Price (RWF)',
            'quantity' => 'Quantity',
            'category' => 'Category',
            'created_at' => 'Created Date'
        ];

        // Transform data for CSV
        $csvData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name ?? 'N/A',
                'description' => $product->description ?? 'N/A',
                'price' => $product->price ?? 0,
                'quantity' => $product->quantity ?? 0,
                'category' => $product->category ?? 'N/A',
                'created_at' => $product->created_at->format('Y-m-d H:i:s')
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export products as PDF
     */
    public function exportPdf(Request $request)
    {
        $filename = $request->get('filename', 'products');

        $products = Product::latest()->get();

        $html = $this->generatePdfHtml('exports.products-pdf', [
            'data' => $products,
            'title' => 'Products Report',
            'subtitle' => 'Complete list of all products',
            'totalRecords' => $products->count()
        ]);

        return $this->downloadPdf($html, $filename);
    }
}
