<?php

namespace App\Http\Controllers\Admin;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Wallet\UserWallet;
use App\Models\Wallet\LedgerEntry;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
      /**
     * إحصائيات المستخدمين
     */
public function getStats()
{
    // إجمالي المستخدمين
    $totalUsers = User::count();

    // إجمالي المحافظ
    $totalWallets = UserWallet::count();

    // إجمالي رصيد كل المحافظ
    $totalWalletBalance = UserWallet::sum('balance');

    // متوسط رصيد المحفظة
    $avgWalletBalance = $totalWallets > 0 ? $totalWalletBalance / $totalWallets : 0;

    // إجمالي الإيداعات المكتملة
    $totalDeposits = LedgerEntry::where('type', LedgerEntry::TYPE_DEPOSIT)
        ->where('status', LedgerEntry::STATUS_COMPLETED)
        ->sum('amount');

    $totalDepositCount = LedgerEntry::where('type', LedgerEntry::TYPE_DEPOSIT)
        ->where('status', LedgerEntry::STATUS_COMPLETED)
        ->count();

    // إجمالي السحوبات المكتملة
    $totalWithdrawals = LedgerEntry::where('type', LedgerEntry::TYPE_WITHDRAWAL)
        ->where('status', LedgerEntry::STATUS_COMPLETED)
        ->sum('amount');

    $totalWithdrawalCount = LedgerEntry::where('type', LedgerEntry::TYPE_WITHDRAWAL)
        ->where('status', LedgerEntry::STATUS_COMPLETED)
        ->count();

    // الإيداعات اليوم
    $todayDeposits = LedgerEntry::where('type', LedgerEntry::TYPE_DEPOSIT)
        ->where('status', LedgerEntry::STATUS_COMPLETED)
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    // السحوبات اليوم
    $todayWithdrawals = LedgerEntry::where('type', LedgerEntry::TYPE_WITHDRAWAL)
        ->where('status', LedgerEntry::STATUS_COMPLETED)
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    // عدد المستخدمين عبر الشبكات الاجتماعية
    $socialUsers = User::where(function($q) {
        $q->whereNotNull('google_id')
          ->orWhereNotNull('facebook_id')
          ->orWhereNotNull('apple_id');
    })->count();

    $googleUsers = User::whereNotNull('google_id')->count();
    $facebookUsers = User::whereNotNull('facebook_id')->count();
    $appleUsers = User::whereNotNull('apple_id')->count();

    // المستخدمين النشطين اليوم
    // $activeToday = User::where('last_login_at', '>=', Carbon::today())->count();

    // نمو المستخدمين (لو عندك دالة calculateUserGrowth)
    $userGrowth = $this->calculateUserGrowth();

    // إحصائيات الطلبات والمراجعات والمفضلة
    $totalOrders = Order::count();
    $totalReviews = Review::count();
    $totalFavourites = Favourite::count();

    // إرجاع كل الإحصائيات كمصفوفة Array
    return [
        'totalUsers' => $totalUsers,
        'totalWallets' => $totalWallets,
        'totalWalletBalance' => $totalWalletBalance,
        'avgWalletBalance' => $avgWalletBalance,
        'totalDeposits' => $totalDeposits,
        'totalDepositCount' => $totalDepositCount,
        'totalWithdrawals' => $totalWithdrawals,
        'totalWithdrawalCount' => $totalWithdrawalCount,
        'todayDeposits' => $todayDeposits,
        'todayWithdrawals' => $todayWithdrawals,
        'socialUsers' => $socialUsers,
        'googleUsers' => $googleUsers,
        'facebookUsers' => $facebookUsers,
        'appleUsers' => $appleUsers,
        // 'activeToday' => $activeToday,
        'userGrowth' => $userGrowth,
        'totalOrders' => $totalOrders,
        'totalReviews' => $totalReviews,
        'totalFavourites' => $totalFavourites
    ];
}


    /**
     * حساب نسبة نمو المستخدمين
     */
    private function calculateUserGrowth()
    {
        $lastMonth = Carbon::now()->subMonth();
        $usersLastMonth = User::where('created_at', '<=', $lastMonth)->count();
        $usersNow = User::count();
        
        if ($usersLastMonth > 0) {
            return round((($usersNow - $usersLastMonth) / $usersLastMonth) * 100, 1);
        }
        
        return 0;
    }

    /**
     * تصدير المستخدمين
     */
    public function export()
    {
        $users = User::with('userWallet')->get();

        $fileName = 'users_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Arabic support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'ID',
                'الاسم',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'رصيد المحفظة',
                'الرصيد المتاح',
                'طريقة التسجيل',
                'تاريخ التسجيل',
                'آخر دخول',
                'الحالة'
            ]);

            foreach ($users as $user) {
                $registrationMethod = 'بريد إلكتروني';
                if ($user->google_id) $registrationMethod = 'Google';
                elseif ($user->facebook_id) $registrationMethod = 'Facebook';
                elseif ($user->apple_id) $registrationMethod = 'Apple';
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '---',
                    $user->userWallet ? number_format($user->userWallet->balance, 2) : '0.00',
                    $user->userWallet ? number_format($user->userWallet->available_balance, 2) : '0.00',
                    $registrationMethod,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : '---',
                    isset($user->is_active) && !$user->is_active ? 'غير نشط' : 'نشط'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
  public function index(Request $request)
    {
        $query = User::with('userWallet');

        // البحث
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // الفلترة حسب النوع
        if ($request->filled('type')) {
            $type = $request->get('type');
            if ($type === 'social') {
                $query->where(function ($q) {
                    $q->whereNotNull('google_id')
                        ->orWhereNotNull('facebook_id')
                        ->orWhereNotNull('apple_id');
                });
            } elseif ($type === 'email') {
                $query->whereNull('google_id')
                    ->whereNull('facebook_id')
                    ->whereNull('apple_id');
            } elseif ($type === 'with_wallet') {
                $query->whereHas('userWallet');
            }
        }

        // الفلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status'));
        }

        // الترتيب حسب رصيد المحفظة
        if ($request->filled('sort_by') && $request->get('sort_by') === 'wallet_balance') {
            $query->leftJoin('user_wallets', 'users.id', '=', 'user_wallets.user_id')
                ->select('users.*', 'user_wallets.balance as wallet_balance')
                ->orderBy('user_wallets.balance', $request->get('sort_direction', 'desc'));
        } else {
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);
        }

        // $users = $query->paginate(15)->withQueryString();
        $users = $query->paginate(15);

        // إحصائيات المحافظ
        $totalWalletBalance = UserWallet::sum('balance');
        $totalDeposits = LedgerEntry::where('type', LedgerEntry::TYPE_DEPOSIT)
            ->where('status', LedgerEntry::STATUS_COMPLETED)
            ->sum('amount');
        $totalWithdrawals = LedgerEntry::where('type', LedgerEntry::TYPE_WITHDRAWAL)
            ->where('status', LedgerEntry::STATUS_COMPLETED)
            ->sum('amount');
$userGrowth = $this->calculateUserGrowth();
            $socialUsers = User::where(function($q) {
                $q->whereNotNull('google_id')
                  ->orWhereNotNull('facebook_id')
                  ->orWhereNotNull('apple_id');
            })->count();
            $stats = $this->getStats();
       
return view('Admin.users.index', [
    'users' => $users,                       // قائمة المستخدمين مع الباجينيشن
    'socialUsers' => $stats['socialUsers'],  // عدد المستخدمين عبر الشبكات الاجتماعية
    'totalWalletBalance' => $stats['totalWalletBalance'], // إجمالي رصيد المحافظ
    'totalDeposits' => $stats['totalDeposits'],           // إجمالي الإيداعات المكتملة
    'totalWithdrawals' => $stats['totalWithdrawals'],     // إجمالي السحوبات المكتملة
    'userGrowth' => $stats['userGrowth'],                 // نمو المستخدمين
    'totalUsers' => $stats['totalUsers'],                 // إجمالي المستخدمين
    'totalWallets' => $stats['totalWallets'],             // إجمالي المحافظ
    'avgWalletBalance' => $stats['avgWalletBalance'],     // متوسط رصيد المحفظة
    'totalDepositCount' => $stats['totalDepositCount'],   // عدد الإيداعات المكتملة
    'totalWithdrawalCount' => $stats['totalWithdrawalCount'], // عدد السحوبات المكتملة
    'todayDeposits' => $stats['todayDeposits'],           // الإيداعات اليوم
    'todayWithdrawals' => $stats['todayWithdrawals'],     // السحوبات اليوم
    'googleUsers' => $stats['googleUsers'],               // عدد مستخدمي Google
    'facebookUsers' => $stats['facebookUsers'],           // عدد مستخدمي Facebook
    'appleUsers' => $stats['appleUsers'],                 // عدد مستخدمي Apple
    // 'activeToday' => $stats['activeToday'],               // المستخدمين النشطين اليوم
    'totalOrders' => $stats['totalOrders'],               // إجمالي الطلبات
    'totalReviews' => $stats['totalReviews'],             // إجمالي المراجعات
    'totalFavourites' => $stats['totalFavourites'],       // إجمالي المفضلات
]);
    }

    // دوال المحفظة الجديدة
    public function walletInfo(User $user)
    {
        $wallet = $user->userWallet;
        
        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم ليس لديه محفظة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => number_format($wallet->balance, 2),
                'available_balance' => number_format($wallet->available_balance, 2),
                'held_balance' => number_format($wallet->held_balance, 2),
                'currency' => $wallet->currency,
                'status' => $wallet->status
            ]
        ]);
    }

    public function createWallet(User $user)
    {
        try {
            DB::beginTransaction();

            // التحقق من وجود محفظة
            if ($user->userWallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم لديه محفظة بالفعل'
                ], 400);
            }

            // إنشاء المحفظة
            $wallet = UserWallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'held_balance' => 0,
                'currency' => 'SAR',
                'status' => 'active',
                'daily_limit' => 10000,
                'monthly_limit' => 50000
            ]);

            // إنشاء سجل المحفظة
            LedgerEntry::create([
                'wallet_id' => $wallet->id,
                'wallet_type' => 'user',
                'owner_type' => 'user',
                'owner_id' => $user->id,
                'type' => LedgerEntry::TYPE_ADJUSTMENT,
                'amount' => 0,
                'balance_before' => 0,
                'balance_after' => 0,
                'description' => 'إنشاء محفظة جديدة',
                'status' => LedgerEntry::STATUS_COMPLETED,
                'reference' => 'WALLET_CREATE_' . time(),
                'metadata' => [
                    'created_by_admin' => auth()->id(),
                    'created_at' => now()->toIso8601String()
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المحفظة بنجاح',
                'data' => $wallet
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء المحفظة: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01|max:100000',
            'payment_method' => 'required|string|in:bank_transfer,credit_card,cash,system,manual',
            'reference' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            $wallet = $user->userWallet;

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم ليس لديه محفظة'
                ], 400);
            }

            if ($wallet->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'المحفظة غير نشطة'
                ], 400);
            }

            $amount = $validated['amount'];
            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            // تحديث رصيد المحفظة
            $wallet->updateBalance($amount, 'increment');
            $wallet->updateDailyTotals($amount, 'deposit');

            // إنشاء سجل الإيداع
            $ledgerEntry = LedgerEntry::create([
                'wallet_id' => $wallet->id,
                'wallet_type' => 'user',
                'owner_type' => 'user',
                'owner_id' => $user->id,
                'type' => LedgerEntry::TYPE_DEPOSIT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'available_balance_before' => $wallet->available_balance,
                'available_balance_after' => $wallet->available_balance + $amount,
                'payment_method' => $validated['payment_method'],
                'description' => $validated['description'] ?? 'إيداع رصيد بواسطة المشرف',
                'status' => LedgerEntry::STATUS_COMPLETED,
                'reference' => $validated['reference'] ?? 'DEP_' . time() . '_' . rand(1000, 9999),
                'metadata' => [
                    'deposited_by_admin' => auth()->id(),
                    'admin_name' => auth()->user()->name,
                    'deposit_date' => now()->toIso8601String()
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم الإيداع بنجاح',
                'data' => [
                    'amount' => number_format($amount, 2),
                    'new_balance' => number_format($balanceAfter, 2),
                    'reference' => $ledgerEntry->reference,
                    'transaction_id' => $ledgerEntry->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'فشل في عملية الإيداع: ' . $e->getMessage()
            ], 500);
        }
    }

    public function withdraw(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01|max:100000',
            'withdrawal_method' => 'required|string|in:bank_transfer,cash,wallet',
            'withdrawal_details' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            $wallet = $user->userWallet;

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم ليس لديه محفظة'
                ], 400);
            }

            if ($wallet->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'المحفظة غير نشطة'
                ], 400);
            }

            $amount = $validated['amount'];

            // التحقق من الرصيد المتاح
            if ($wallet->available_balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرصيد غير كافي للسحب'
                ], 400);
            }

            // التحقق من الحد اليومي
            if (!$wallet->canWithdrawToday($amount)) {
                return response()->json([
                    'success' => false,
                    'message' => 'تجاوز الحد اليومي للسحب'
                ], 400);
            }

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore - $amount;

            // تحديث رصيد المحفظة
            $wallet->updateBalance($amount, 'decrement');
            $wallet->updateDailyTotals($amount, 'withdrawal');

            // إنشاء سجل السحب
            $ledgerEntry = LedgerEntry::create([
                'wallet_id' => $wallet->id,
                'wallet_type' => 'user',
                'owner_type' => 'user',
                'owner_id' => $user->id,
                'type' => LedgerEntry::TYPE_WITHDRAWAL,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'available_balance_before' => $wallet->available_balance,
                'available_balance_after' => $wallet->available_balance - $amount,
                'payment_method' => $validated['withdrawal_method'],
                'description' => $validated['description'] ?? 'سحب رصيد بواسطة المشرف',
                'status' => LedgerEntry::STATUS_COMPLETED,
                'reference' => 'WTH_' . time() . '_' . rand(1000, 9999),
                'metadata' => [
                    'withdrawn_by_admin' => auth()->id(),
                    'admin_name' => auth()->user()->name,
                    'withdrawal_date' => now()->toIso8601String(),
                    'withdrawal_details' => $validated['withdrawal_details']
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم السحب بنجاح',
                'data' => [
                    'amount' => number_format($amount, 2),
                    'new_balance' => number_format($balanceAfter, 2),
                    'reference' => $ledgerEntry->reference,
                    'transaction_id' => $ledgerEntry->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'فشل في عملية السحب: ' . $e->getMessage()
            ], 500);
        }
    }

    public function quickDeposit(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $wallet = $user->userWallet;

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم ليس لديه محفظة'
                ], 400);
            }

            $amount = $validated['amount'];
            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            // تحديث رصيد المحفظة
            $wallet->updateBalance($amount, 'increment');
            $wallet->updateDailyTotals($amount, 'deposit');

            // إنشاء سجل الإيداع
            LedgerEntry::create([
                'wallet_id' => $wallet->id,
                'wallet_type' => 'user',
                'owner_type' => 'user',
                'owner_id' => $user->id,
                'type' => LedgerEntry::TYPE_DEPOSIT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $validated['description'] ?? 'إيداع سريع بواسطة المشرف',
                'status' => LedgerEntry::STATUS_COMPLETED,
                'reference' => 'QDEP_' . time() . '_' . rand(1000, 9999),
                'metadata' => [
                    'quick_deposit' => true,
                    'deposited_by_admin' => auth()->id()
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم الإيداع السريع بنجاح',
                'data' => [
                    'amount' => number_format($amount, 2),
                    'new_balance' => number_format($balanceAfter, 2)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'فشل في عملية الإيداع: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTransactions(User $user)
    {
        $transactions = LedgerEntry::where('owner_type', 'user')
            ->where('owner_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $formattedTransactions = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => number_format($transaction->amount, 2),
                'description' => $transaction->description,
                'status' => $transaction->status,
                'status_text' => $this->getStatusText($transaction->status),
                'reference' => $transaction->reference,
                'formatted_date' => $transaction->created_at->format('Y-m-d H:i:s'),
                'metadata' => $transaction->metadata
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $formattedTransactions,
                'pagination' => [
                    'total' => $transactions->total(),
                    'per_page' => $transactions->perPage(),
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage()
                ]
            ]
        ]);
    }

    public function exportTransactions(User $user)
    {
        $transactions = LedgerEntry::where('owner_type', 'user')
            ->where('owner_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'transactions_' . $user->id . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // كتابة العنوان
            fputcsv($file, [
                'ID',
                'النوع',
                'المبلغ',
                'الوصف',
                'الحالة',
                'المرجع',
                'التاريخ',
                'رصيد قبل',
                'رصيد بعد'
            ]);

            // كتابة البيانات
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $this->getTypeText($transaction->type),
                    $transaction->amount,
                    $transaction->description,
                    $this->getStatusText($transaction->status),
                    $transaction->reference,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->balance_before,
                    $transaction->balance_after
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getStatusText($status)
    {
        $statuses = [
            LedgerEntry::STATUS_PENDING => 'قيد الانتظار',
            LedgerEntry::STATUS_COMPLETED => 'مكتمل',
            LedgerEntry::STATUS_FAILED => 'فشل',
            LedgerEntry::STATUS_CANCELLED => 'ملغي',
            LedgerEntry::STATUS_APPROVED => 'معتمد',
            LedgerEntry::STATUS_PROCESSING => 'قيد المعالجة'
        ];

        return $statuses[$status] ?? $status;
    }

    private function getTypeText($type)
    {
        $types = [
            LedgerEntry::TYPE_DEPOSIT => 'إيداع',
            LedgerEntry::TYPE_WITHDRAWAL => 'سحب',
            LedgerEntry::TYPE_TRANSFER_IN => 'تحويل وارد',
            LedgerEntry::TYPE_TRANSFER_OUT => 'تحويل صادر',
            LedgerEntry::TYPE_PAYMENT => 'دفع',
            LedgerEntry::TYPE_REFUND => 'استرداد',
            LedgerEntry::TYPE_ADJUSTMENT => 'تعديل'
        ];

        return $types[$type] ?? $type;
    }

    // الدوال القديمة (أبقاها كما هي)
    public function create()
    {
        return view('Admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // إنشاء محفظة تلقائية للمستخدم الجديد
        $this->createWallet($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إضافة المستخدم وإنشاء محفظة له بنجاح.');
    }

    public function show(User $user)
    {

        return view('Admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('Admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    public function destroy(User $user)
    {
        // حذف الصورة إذا وجدت
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        // حذف المحفظة المرتبطة
        if ($user->userWallet) {
            $user->userWallet->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم والمحفظة المرتبطة به بنجاح.');
    }

    public function toggleStatus(User $user)
    {
        if (isset($user->is_active)) {
            $user->update(['is_active' => !$user->is_active]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير الحالة بنجاح',
            'is_active' => $user->is_active ?? null
        ]);
    }

    public function orders(User $user)
    {
        $orders = $user->orders()->latest()->paginate(10);
        return view('Admin.users.orders', compact('user', 'orders'));
    }

    public function reviews(User $user)
    {
        $reviews = $user->reviews()->latest()->paginate(10);
        return view('Admin.users.reviews', compact('user', 'reviews'));
    }

    public function favourites(User $user)
    {
        $favourites = $user->favouriteProducts()->paginate(12);
        return view('Admin.users.favourites', compact('user', 'favourites'));
    }

    public function activities(User $user)
    {
        // يمكنك إضافة سجل الأنشطة هنا
        return view('Admin.users.activities', compact('user'));
    }





}
