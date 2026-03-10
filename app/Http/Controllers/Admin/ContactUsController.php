<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\ContactUsReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ContactUsController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request)
    {
        $query = ContactUs::with(['user', 'lastReply']);

        // بحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // فلتر حسب الحالة
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // فلتر حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortBy, ['created_at', 'first_name', 'last_name', 'email', 'status'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->latest();
        }

        // إحصائيات
        $stats = [
            'total' => ContactUs::count(),
            'pending' => ContactUs::where('status', 'pending')->count(),
            'replied' => ContactUs::where('status', 'replied')->count(),
            'archived' => ContactUs::where('status', 'archived')->count(),
        ];

        $messages = $query->paginate(15);

        return view('Admin.contact_us.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified message.
     */
    public function show(ContactUs $contactUs)
    {
        // تحميل العلاقات
        $contactUs->load(['user', 'replies.user']);

        // تحديث الحالة إلى مقروء إذا كانت pending
        if ($contactUs->status === 'pending') {
            $contactUs->update(['status' => 'read']);
        }

        return view('Admin.contact_us.show', compact('contactUs'));
    }

    /**
     * Reply to a contact message.
     */
    public function reply(Request $request, ContactUs $contactUs)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:2|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // إضافة الرد
            $reply = $contactUs->addReply(
                auth()->id(),
                $request->message,
                'admin'
            );

            // تحديث حالة الرسالة إلى تم الرد
            $contactUs->update(['status' => 'replied']);

            // هنا يمكنك إرسال إشعار أو بريد إلكتروني للعميل

return response()->json([
    'success' => true,
    'message' => 'تم إرسال الرد بنجاح',
    'reply' => [
        'id' => $reply->id,
        'message' => $reply->message,
        'created_at' => optional($reply->created_at)->diffForHumans() ?? now()->diffForHumans(),
        'user_name' => optional(auth()->user())->name ?? 'مسؤول',
    ]
]);

} catch (\Exception $e) {
    Log::error('ContactUs reply error', [
        'contact_us_id' => $contactUs->id,
        'user_id' => auth()->id(),
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);

    return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء إرسال الرد',
        // مؤقتًا للتشخيص (شيله في production):
        'debug' => $e->getMessage(),
    ], 500);
}
    }

    /**
     * Update message status.
     */
    public function updateStatus(Request $request, ContactUs $contactUs)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,read,replied,archived'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contactUs->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحالة بنجاح',
                'status' => $request->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الحالة'
            ], 500);
        }
    }

    /**
     * Bulk update status.
     */
    public function bulkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_us,id',
            'status' => 'required|in:pending,read,replied,archived'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            ContactUs::whereIn('id', $request->ids)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحالات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الحالات'
            ], 500);
        }
    }

    /**
     * Remove the specified message.
     */
    public function destroy(ContactUs $contactUs)
    {
        try {
            // حذف الردود المرتبطة أولاً (إذا لم تكن على مستوى قاعدة البيانات cascade)
            $contactUs->replies()->delete();
            $contactUs->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرسالة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الرسالة'
            ], 500);
        }
    }

    /**
     * Bulk delete messages.
     */
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_us,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // حذف الردود المرتبطة أولاً
            ContactUsReply::whereIn('contact_us_id', $request->ids)->delete();
            ContactUs::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرسائل المحددة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الرسائل'
            ], 500);
        }
    }
}