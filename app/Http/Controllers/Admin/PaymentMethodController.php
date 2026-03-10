<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        return view('Admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('Admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:payment_methods,key',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'is_payment' => 'boolean',
        ]);

        // معالجة رفع الصورة
        if ($request->hasFile('icon')) {
            $fileName = time() . '_' . $request->icon->getClientOriginalName();
            $request->icon->storeAs('payment-methods', $fileName, 'public');
            $validated['icon'] = $fileName;
        }

        // تعيين القيم الافتراضية
        $validated['is_active'] = $request->has('is_active');
        $validated['is_payment'] = $request->has('is_payment');

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'تم إضافة وسيلة الدفع بنجاح.');
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return view('Admin.payment-methods.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('Admin.payment-methods.edit', compact('paymentMethod'));
    }


    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:payment_methods,key,' . $paymentMethod->id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'boolean',
            'is_payment' => 'boolean',
        ]);

        try {
            // معالجة رفع الصورة الجديدة
            if ($request->hasFile('icon')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($paymentMethod->icon && Storage::disk('public')->exists('payment-methods/' . $paymentMethod->icon)) {
                    Storage::disk('public')->delete('payment-methods/' . $paymentMethod->icon);
                }

                // رفع الصورة الجديدة
                $fileName = time() . '_' . uniqid() . '.' . $request->icon->getClientOriginalExtension();
                $request->icon->storeAs('payment-methods', $fileName, 'public');
                $validated['icon'] = $fileName;
            } else {
                // الاحتفاظ بالصورة القديمة إذا لم يتم رفع صورة جديدة
                unset($validated['icon']);
            }

            // تحديث وسيلة الدفع
            $paymentMethod->update($validated);

            return redirect()->route('admin.payment-methods.index')
                ->with('success', 'تم تحديث وسيلة الدفع بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating payment method: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث وسيلة الدفع.');
        }
    }


    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'تم حذف وسيلة الدفع بنجاح.');
    }

    // public function toggleStatus(PaymentMethod $paymentMethod)
    // {
    //     $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

    //     return redirect()->back()->with('success', 'تم تغيير الحالة بنجاح');
    // }
    /**
     * Toggle payment method status (active/inactive)
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->update([
                'is_active' => !$paymentMethod->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير الحالة بنجاح',
                'is_active' => $paymentMethod->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling payment method status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة'
            ], 500);
        }
    }
}
