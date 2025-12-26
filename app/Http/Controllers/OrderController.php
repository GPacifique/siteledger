<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\RbacFilterService;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        // Require authentication; change or add role/permission middleware as needed
        $this->middleware('auth');
        $this->rbacFilterService = $rbacFilterService;
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Placeholder', ['page' => 'Orders', 'filterContext' => $this->rbacFilterService->getFilterContext()]);
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, &$order) {
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'total' => 0,
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['price'];
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                $total += $lineTotal;
            }
            $order->update(['total' => $total]);
        });

        return redirect()->route('orders.index')->with('success', 'Order created.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load('items', 'payments');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load('items');
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }

    /**
     * Add an item to an order.
     */
    public function addItem(Request $request, Order $order)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $lineTotal = $validated['quantity'] * $validated['unit_price'];

        $order->items()->create([
            'product_name' => $validated['product_name'],
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'line_total' => $lineTotal,
        ]);

        // Recalculate total
        $order->refresh();
        $newTotal = $order->items()->sum('line_total');
        $order->update(['total' => $newTotal]);

        return back()->with('success', 'Item added.');
    }

    /**
     * Remove an item from an order.
     */
    public function removeItem(Order $order, OrderItem $item)
    {
        // Ensure the item belongs to the order
        if ($item->order_id !== $order->id) {
            abort(404);
        }

        $item->delete();

        // Recalculate total
        $newTotal = $order->items()->sum('line_total');
        $order->update(['total' => $newTotal]);

        return back()->with('success', 'Item removed.');
    }

    /**
     * Mark order as paid by creating a Payment record.
     */
    public function markAsPaid(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'reference' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($order, $validated) {
            Payment::create([
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'reference' => $validated['reference'] ?? null,
                'user_id' => Auth::id(),
                'order_id' => $order->id,
            ]);

            // Optionally change order status to completed/processing if fully paid
            $paid = $order->payments()->sum('amount');
            if ($paid >= $order->total) {
                $order->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Payment recorded.');
    }
}
