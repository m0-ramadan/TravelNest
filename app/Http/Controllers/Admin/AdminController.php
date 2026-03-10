<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:عرض المشرفين')->only(['index', 'show']);
        // $this->middleware('permission:إضافة مشرف')->only(['create', 'store']);
        // $this->middleware('permission:تعديل مشرف')->only(['edit', 'update']);
        // $this->middleware('permission:حذف مشرف')->only(['destroy']);
        // $this->middleware('permission:تغيير حالة مشرف')->only(['toggleStatus']);
        // $this->middleware('permission:إعادة تعيين كلمة المرور')->only(['resetPassword']);
    }
/**
 * التحقق من توفر البريد الإلكتروني
 */
public function checkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $exists = Admin::where('email', $request->email)->exists();

    return response()->json([
        'available' => !$exists,
        'message' => $exists ? 'البريد الإلكتروني مستخدم بالفعل' : 'البريد الإلكتروني متاح'
    ]);
}
    /**
     * عرض قائمة المشرفين
     */
    public function index(Request $request)
    {
        if(!Auth::guard('admin')->check()) {
             return redirect()->route('admin.login.page');
        }
        $query = Admin::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // تصفية حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // تصفية حسب الدور
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // ترتيب
        $query->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_dir', 'desc'));

        // عدد العناصر لكل صفحة
        $perPage = $request->get('per_page', 10);

        $admins = $query->with('roles')
                       ->paginate($perPage)
                       ->withQueryString();

        // إحصائيات
        $stats = [
            'total' => Admin::count(),
            'active' => Admin::where('is_active', true)->count(),
            'inactive' => Admin::where('is_active', false)->count(),
            'super_admins' => Admin::role('super_admin')->count(),
            'admins' => Admin::role('admin')->count(),
            'moderators' => Admin::role('editor')->count(),
        ];

        // الأدوار المتاحة
        $roles = Role::where('guard_name', 'admin')
                    ->orderBy('name')
                    ->get(['id', 'name', 'display_name']);

        return view('Admin.admin.index', compact('admins', 'stats', 'roles'));
    }

    /**
     * عرض نموذج إضافة مشرف جديد
     */
public function create()
{
    $roles = Role::where('guard_name', 'admin')
                ->orderBy('name')
                ->get(['id', 'name', 'display_name', 'description']);
    
    $permissions = Permission::where('guard_name', 'admin')
                            ->orderBy('module')
                            ->orderBy('name')
                            ->get();
    
    return view('Admin.admin.create', compact('roles', 'permissions'));
}
    /**
     * حفظ مشرف جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string|unique:admins,phone',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active ?? true,
        ];

        // رفع الصورة
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('admins/avatars', 'public');
            $data['avatar'] = $path;
        }

        $admin = Admin::create($data);
        
        // إضافة الدور
        $role = Role::findById($request->role);
        $admin->assignRole($role);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'تم إضافة المشرف بنجاح');
    }

    /**
     * عرض تفاصيل مشرف
     */
    public function show(Admin $admin)
    {
        $admin->load('roles', 'permissions');
            $roles = Role::orderBy('name')
                ->get(['id', 'name', 'display_name', 'description']);
        return view('Admin.admin.show', compact('admin','roles'));
    }

    /**
     * عرض نموذج تعديل مشرف
     */
    public function edit(Admin $admin)
    {
        $roles = Role::where('guard_name', 'admin')
                    ->orderBy('name')
                    ->get(['id', 'name', 'display_name']);
        
        return view('Admin.admin.edit', compact('admin', 'roles'));
    }

    /**
     * تحديث بيانات مشرف
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|unique:admins,phone,' . $admin->id,
            'role' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ];

        // تحديث الصورة
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            
            $path = $request->file('avatar')->store('admins/avatars', 'public');
            $data['avatar'] = $path;
        }

        $admin->update($data);
        
        // تحديث الدور
        $admin->syncRoles([$request->role]);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'تم تحديث بيانات المشرف بنجاح');
    }

    /**
     * حذف مشرف
     */
    public function destroy(Admin $admin)
    {
        // لا يمكن حذف نفسه
        if ($admin->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك حذف حسابك الخاص'
            ], 403);
        }

        // لا يمكن حذف المشرف الرئيسي
        if ($admin->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف المشرف الرئيسي'
            ], 403);
        }

        // حذف الصورة
        if ($admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المشرف بنجاح'
        ]);
    }

    /**
     * تغيير حالة المشرف (نشط/غير نشط)
     */
    public function toggleStatus(Admin $admin)
    {
        // لا يمكن تغيير حالة نفسه
        if ($admin->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك تغيير حالة حسابك الخاص'
            ], 403);
        }

        // لا يمكن تغيير حالة المشرف الرئيسي
        if ($admin->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تغيير حالة المشرف الرئيسي'
            ], 403);
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $status = $admin->is_active ? 'تفعيل' : 'تعطيل';

        return response()->json([
            'success' => true,
            'message' => "تم {$status} حساب المشرف بنجاح",
            'status' => $admin->is_active,
            'status_text' => $admin->is_active ? 'نشط' : 'غير نشط',
            'badge_class' => $admin->is_active ? 'success' : 'danger'
        ]);
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request, Admin $admin)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin->password = Hash::make($request->password);
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
        ]);
    }

    /**
     * حذف متعدد للمشرفين
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:admins,id'
        ]);

        // لا يمكن حذف نفسه
        if (in_array(auth()->id(), $request->ids)) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك حذف حسابك الخاص'
            ], 403);
        }

        $count = 0;
        foreach ($request->ids as $id) {
            $admin = Admin::find($id);
            
            // لا يمكن حذف المشرف الرئيسي
            if ($admin && !$admin->isSuperAdmin()) {
                if ($admin->avatar) {
                    Storage::disk('public')->delete($admin->avatar);
                }
                $admin->delete();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "تم حذف {$count} مشرفين بنجاح"
        ]);
    }

    /**
     * تغيير حالة متعددة للمشرفين
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:admins,id',
            'status' => 'required|in:active,inactive'
        ]);

        // لا يمكن تغيير حالة نفسه
        if (in_array(auth()->id(), $request->ids)) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك تغيير حالة حسابك الخاص'
            ], 403);
        }

        $status = $request->status === 'active';
        $count = Admin::whereIn('id', $request->ids)
                     ->where('id', '!=', auth()->id())
                     ->whereDoesntHave('roles', function($q) {
                         $q->where('name', 'super_admin');
                     })
                     ->update(['is_active' => $status]);

        $statusText = $status ? 'تفعيل' : 'تعطيل';

        return response()->json([
            'success' => true,
            'message' => "تم {$statusText} {$count} مشرفين بنجاح"
        ]);
    }

    /**
     * تصدير بيانات المشرفين
     */
    public function export(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $admins = $query->with('roles')->get();

        // تنسيق البيانات للتصدير
        $data = $admins->map(function($admin) {
            return [
                'الاسم' => $admin->name,
                'البريد الإلكتروني' => $admin->email,
                'الهاتف' => $admin->phone ?? '-',
                'الدور' => $admin->roles->pluck('display_name')->implode(', '),
                'الحالة' => $admin->is_active ? 'نشط' : 'غير نشط',
                'تاريخ الإنشاء' => $admin->created_at->format('Y-m-d'),
            ];
        });

        // تصدير CSV
        $filename = 'admins_export_' . now()->format('Y-m_d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // إضافة BOM لدعم العربية في Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // رؤوس الأعمدة
        fputcsv($handle, array_keys($data->first() ?? []));
        
        // البيانات
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}