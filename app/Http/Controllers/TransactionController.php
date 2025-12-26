<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Traits\Downloadable;
use App\Services\RbacFilterService;
use Inertia\Inertia;

class TransactionController extends Controller
{
    use Downloadable;

    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        $this->middleware('auth');
        $this->rbacFilterService = $rbacFilterService;
    }

    public function index(Request $request)
    {
        return Inertia::render('Admin/Placeholder', ['page' => 'Transactions', 'filterContext' => $this->rbacFilterService->getFilterContext()]);
    }

    public function create()
    {
        // Generate automatic reference
        $autoReference = $this->generateReference();
        return view('transactions.create', compact('autoReference'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:255|unique:transactions,reference',
            'date' => 'required|date',
            'type' => 'required|string|in:revenue,expense,payroll,transfer',
            'category' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
        ]);

        // If reference is empty, generate one
        if (empty($validated['reference'])) {
            $validated['reference'] = $this->generateReference();
        }

        $validated = $this->ensureTenantId($validated);
        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully!');
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $r, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $r->validate([
            'reference' => 'required|string|max:255|unique:transactions,reference,' . $id,
            'date' => 'required|date',
            'type' => 'required|string|in:revenue,expense,payroll,transfer',
            'category' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully!');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Generate unique transaction reference
     */
    private function generateReference()
    {
        do {
            $reference = 'TXN-' . date('Ymd') . '-' . date('His') . '-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (Transaction::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Export transactions as CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = $request->get('filename', 'transactions');

        $transactions = Transaction::latest('date')->get();

        $headers = [
            'id' => 'ID',
            'reference' => 'Reference',
            'type' => 'Type',
            'amount' => 'Amount (RWF)',
            'description' => 'Description',
            'date' => 'Date',
            'status' => 'Status',
            'created_at' => 'Created Date'
        ];

        // Transform data for CSV
        $csvData = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'reference' => $transaction->reference ?? 'N/A',
                'type' => ucfirst($transaction->type ?? 'N/A'),
                'amount' => $transaction->amount ?? 0,
                'description' => $transaction->description ?? 'N/A',
                'date' => $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') : 'N/A',
                'status' => ucfirst($transaction->status ?? 'completed'),
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s')
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export transactions as PDF
     */
    public function exportPdf(Request $request)
    {
        $filename = $request->get('filename', 'transactions');

        $transactions = Transaction::latest('date')->get();

        $html = $this->generatePdfHtml('exports.financial-pdf', [
            'data' => $transactions,
            'title' => 'Transactions Report',
            'subtitle' => 'Complete list of all transactions',
            'totalRecords' => $transactions->count()
        ]);

        return $this->downloadPdf($html, $filename);
    }
}
