<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();
        $activeLanguagesCount = Language::active()->count();
        $inactiveLanguagesCount = Language::where('is_active', false)->count();
        $defaultLanguage = Language::where('is_default', true)->first(); // تصحيح هنا
        
        return view('Admin.languages.index', compact(
            'languages',
            'activeLanguagesCount',
            'inactiveLanguagesCount',
            'defaultLanguage'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:5|unique:languages,code',
            'direction' => 'required|in:rtl,ltr',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
        ]);

        // إذا كانت اللغة افتراضية، نزيل الافتراضية من اللغات الأخرى
        if ($request->has('is_default') && $request->is_default) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        $language = new Language();
        $language->name = $validated['name'];
        $language->code = strtolower($validated['code']);
        $language->direction = $validated['direction'];
        $language->sort_order = $validated['sort_order'] ?? 0;
        $language->is_active = $request->has('is_active');
        $language->is_default = $request->has('is_default');

        // رفع صورة العلم
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('languages/flags', 'public');
            $language->image_path = 'storage/' . $path;
        }

        $language->save();

        return redirect()->route('admin.languages.index')
            ->with('success', 'تم إضافة اللغة بنجاح');
    }

    public function edit(Language $language)
    {
        return view('Admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:5|unique:languages,code,' . $language->id,
            'direction' => 'required|in:rtl,ltr',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
        ]);

        // إذا كانت اللغة افتراضية، نزيل الافتراضية من اللغات الأخرى
        if ($request->has('is_default') && $request->is_default) {
            Language::where('is_default', true)->where('id', '!=', $language->id)->update(['is_default' => false]);
        }

        $language->name = $validated['name'];
        $language->code = strtolower($validated['code']);
        $language->direction = $validated['direction'];
        $language->sort_order = $validated['sort_order'] ?? $language->sort_order;
        $language->is_active = $request->has('is_active');
        $language->is_default = $request->has('is_default');

        // رفع صورة العلم
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($language->image_path) {
                $oldPath = str_replace('storage/', '', $language->image_path);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('languages/flags', 'public');
            $language->image_path = 'storage/' . $path;
        }

        $language->save();

        return redirect()->route('admin.languages.index')
            ->with('success', 'تم تحديث اللغة بنجاح');
    }

    public function destroy(Language $language)
    {
        // لا يمكن حذف اللغة الافتراضية
        if ($language->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف اللغة الافتراضية'
            ], 422);
        }

        // حذف صورة العلم إذا وجدت
        if ($language->image_path) {
            $oldPath = str_replace('storage/', '', $language->image_path);
            Storage::disk('public')->delete($oldPath);
        }

        $language->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف اللغة بنجاح'
        ]);
    }

    public function toggle(Language $language)
    {
        // لا يمكن تعطيل اللغة الافتراضية
        if ($language->is_default && $language->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تعطيل اللغة الافتراضية'
            ], 422);
        }

        $language->is_active = !$language->is_active;
        $language->save();

        return response()->json([
            'success' => true,
            'message' => $language->is_active ? 'تم تفعيل اللغة' : 'تم تعطيل اللغة'
        ]);
    }

    public function setDefault(Language $language)
    {
        // تأكد أن اللغة مفعلة
        if (!$language->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تفعيل اللغة أولاً قبل جعلها افتراضية'
            ], 422);
        }

        // إزالة الافتراضية من جميع اللغات
        Language::where('is_default', true)->update(['is_default' => false]);

        // تعيين اللغة الحالية كافتراضية
        $language->is_default = true;
        $language->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين اللغة كافتراضية بنجاح'
        ]);
    }

    public function toggleAll(Request $request)
    {
        $action = $request->input('action');
        
        if ($action === 'activate') {
            Language::where('is_active', false)->update(['is_active' => true]);
            $message = 'تم تفعيل جميع اللغات';
        } elseif ($action === 'deactivate') {
            // لا نعطل اللغة الافتراضية
            Language::where('is_default', false)->update(['is_active' => false]);
            $message = 'تم تعطيل جميع اللغات (باستثناء الافتراضية)';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'إجراء غير صالح'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}