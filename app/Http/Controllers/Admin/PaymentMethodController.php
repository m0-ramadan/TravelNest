<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use App\Traits\HandlesTranslatedFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    use HandlesTranslatedFields;

    public function index(Request $request): View
    {
        $paymentMethods = PaymentMethod::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $this->applyTranslatedSearch($query, ['name'], $request->string('q'));
            })
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create(): View
    {
        return $this->view('admin.payment-methods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'provider' => ['nullable', 'string'],
            'is_online' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = $this->translateModelFields($data, ['name']);

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
            'slug' => ['nullable', 'string'],
            'provider' => ['nullable', 'string'],
            'is_online' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = $this->translateModelFields($data, ['name']);

        $paymentMethod->update($data);

        return $this->success('admin.payment-methods.index', 'PaymentMethod updated.');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        return $this->success('admin.payment-methods.index', 'PaymentMethod deleted.');
    }
}
