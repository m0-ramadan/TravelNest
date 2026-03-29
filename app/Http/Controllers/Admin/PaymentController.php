<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = '%' . $request->string('q') . '%';
                $query->where('transaction_reference', 'like', $search)
                    ->orWhere('gateway_reference', 'like', $search)
                    ->orWhere('status', 'like', $search)
                    ->orWhere('currency_code', 'like', $search);
            })
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.payments.index', compact('payments'));
    }

    public function create(): View
    {
        return $this->view('admin.payments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'gateway_reference' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'currency_code' => ['required', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'max:50'],
            'payment_type' => ['nullable', 'string', 'max:50'],
            'paid_at' => ['nullable', 'date'],
            'gateway_payload' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        Payment::create($data);

        return redirect()->route('admin.payments.index')->with('success', 'Payment created.');
    }

    public function show(Payment $payment): View
    {
        return $this->view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        return $this->view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'gateway_reference' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'currency_code' => ['required', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'max:50'],
            'payment_type' => ['nullable', 'string', 'max:50'],
            'paid_at' => ['nullable', 'date'],
            'gateway_payload' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $payment->update($data);

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted.');
    }

    public function statistics()
    {
        return response()->json([
            'total' => Payment::count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'sum_paid' => Payment::where('status', 'paid')->sum('amount'),
        ]);
    }

    public function updateStatus(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate(['status' => ['required', 'string', 'max:50']]);
        $payment->update(['status' => $request->input('status')]);

        return back()->with('success', 'Payment status updated.');
    }

    public function refund(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status' => 'refunded',
            'notes' => trim(($payment->notes ?? '') . PHP_EOL . 'Refunded at: ' . now()),
        ]);

        return back()->with('success', 'Payment refunded.');
    }

    public function export()
    {
        return response()->json(Payment::latest()->get());
    }
}
