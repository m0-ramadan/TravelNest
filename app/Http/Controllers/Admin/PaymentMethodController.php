<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(Request $request): View
    {
        $payment_methods = PaymentMethod::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.payment-methods.index', ['payment_methods' => $payment_methods]);
    }

    public function create(): View
    {
        return $this->view('admin.payment-methods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string'],
            'code' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        PaymentMethod::create($data);

        return $this->success('admin.payment-methods.index', 'PaymentMethod created.');
    }

    public function show(PaymentMethod $paymentMethod): View
    {
        return $this->view('admin.payment-methods.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return $this->view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string'],
            'code' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $paymentMethod->update($data);

        return $this->success('admin.payment-methods.index', 'PaymentMethod updated.');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        return $this->success('admin.payment-methods.index', 'PaymentMethod deleted.');
    }

    public function toggleStatus(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->update(['is_active' => ! (bool) $paymentMethod->is_active]);
        return back()->with('success', 'Payment method status updated.');
    }

}
