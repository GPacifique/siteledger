<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Employee;
use App\Traits\Downloadable;
use App\Services\RbacFilterService;
use Inertia\Inertia;

class PaymentController extends Controller
{
    use Downloadable;

    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        // Protect with authentication (adjust as needed)
        $this->middleware('auth');
        $this->rbacFilterService = $rbacFilterService;
    }

    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $payments = Payment::with('employee')->latest('created_at')->get();
        return view('payments.index', ['payments' => $payments]);
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('payments.create', compact('employees'));
    }

    /**
     * Generate a unique payment reference (AJAX endpoint)
     */
    public function generateReference()
    {
        return response()->json([
            'reference' => $this->generatePaymentReference()
        ]);
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        // Custom validation for payment method
        $validatedData = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'custom_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
            'status' => 'nullable|in:pending,completed,failed',
        ]);

        // Validate payment method options
        $validMethods = ['cash', 'bank_transfer', 'mobile_money', 'credit_card', 'debit_card', 'check', 'wire_transfer', 'paypal', 'crypto', 'other'];

        if (!in_array($validatedData['method'], $validMethods)) {
            return back()->withErrors(['method' => 'Invalid payment method selected.']);
        }

        $data = $request->only('employee_id', 'amount', 'method', 'reference', 'status');

        // Use custom method if "other" was selected and custom_method is provided
        if ($validatedData['method'] === 'other') {
            if (empty($validatedData['custom_method'])) {
                return back()->withErrors(['custom_method' => 'Custom payment method is required when "Other" is selected.']);
            }
            $data['method'] = $validatedData['custom_method'];
        }

        // Auto-generate reference if not provided
        if (empty($data['reference'])) {
            $data['reference'] = $this->generatePaymentReference();
        }

        $data = $this->ensureTenantId($data);
        Payment::create($data);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load('employee');
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Custom validation for payment method
        $validatedData = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'custom_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
            'status' => 'nullable|in:pending,completed,failed',
        ]);

        // Validate payment method options
        $validMethods = ['cash', 'bank_transfer', 'mobile_money', 'credit_card', 'debit_card', 'check', 'wire_transfer', 'paypal', 'crypto', 'other'];

        if (!in_array($validatedData['method'], $validMethods)) {
            return back()->withErrors(['method' => 'Invalid payment method selected.']);
        }

        $data = $request->only('employee_id', 'amount', 'method', 'reference', 'status');

        // Use custom method if "other" was selected and custom_method is provided
        if ($validatedData['method'] === 'other') {
            if (empty($validatedData['custom_method'])) {
                return back()->withErrors(['custom_method' => 'Custom payment method is required when "Other" is selected.']);
            }
            $data['method'] = $validatedData['custom_method'];
        }

        $payment->update($data);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Export payments as CSV
     */
    public function exportCsv(Request $request)
    {
        // Check permission for payment export
        if (!Auth::user()->can('payments.export')) {
            abort(403, 'You do not have permission to export payments.');
        }

        $filename = $request->get('filename', 'payments');

        $payments = Payment::with('employee')->latest()->get();

        $headers = [
            'id' => 'ID',
            'employee_name' => 'Employee',
            'amount' => 'Amount (RWF)',
            'created_at' => 'Payment Date',
            'description' => 'Description',
            'status' => 'Status',
            'method' => 'Method'
        ];

        // Transform data for CSV
        $csvData = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'employee_name' => $payment->employee ? $payment->employee->name : 'N/A',
                'amount' => $payment->amount ?? 0,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                'description' => $payment->reference ?? 'Payment',
                'status' => ucfirst($payment->status ?? 'completed'),
                'method' => $payment->method ?? 'N/A'
            ];
        });

        return $this->downloadCsv($csvData, $filename, array_keys($headers));
    }

    /**
     * Export payments as PDF
     */
    public function exportPdf(Request $request)
    {
        // Check permission for payment export
        if (!Auth::user()->can('payments.export')) {
            abort(403, 'You do not have permission to export payments.');
        }

        $filename = $request->get('filename', 'payments');

        $payments = Payment::with('employee')->latest()->get();

        $html = $this->generatePdfHtml('exports.financial-pdf', [
            'data' => $payments,
            'title' => 'Payments Report',
            'subtitle' => 'Complete list of all payments',
            'totalRecords' => $payments->count()
        ]);

        return $this->downloadPdf($html, $filename);
    }

    /**
     * Generate a unique payment reference
     */
    private function generatePaymentReference()
    {
        do {
            $date = now()->format('Ymd');
            $time = now()->format('His');
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $reference = "PAY-{$date}-{$time}-{$random}";

            // Add microseconds for additional uniqueness if needed
            if (Payment::where('reference', $reference)->exists()) {
                $microseconds = substr(microtime(), 2, 3);
                $reference = "PAY-{$date}-{$time}-{$microseconds}";
            }

        } while (Payment::where('reference', $reference)->exists());

        return $reference;
    }
}
