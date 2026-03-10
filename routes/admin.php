<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BannerItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ErrorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LogisticServiceController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitorController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin panel, including authentication and resource management.
|
*/

// Add this route BEFORE any middleware groups
Route::get('admin/categories/tree', [CategoryController::class, 'getTree'])->name('admin.categories.tree.data');

// Authentication Routes
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'loginPage'])->name('login.page');
    Route::post('login/post', [AdminAuthController::class, 'login'])->name('login');

    // Password Reset Routes
    Route::get('forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AdminAuthController::class, 'sendResetOtp'])->name('password.email');
    Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AdminAuthController::class, 'resetPassword'])->name('password.update');
});
Route::get('/', [AdminAuthController::class, 'home'])->name('admin.index')->middleware('auth:admin');

// Admin Routes (Authenticated)
Route::prefix('admin')->as('admin.')->middleware('auth:admin')->group(function () {
    // Dashboard
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/visitors/chart', [VisitorController::class, 'chartData'])
        ->name('visitors.chart');

    // Settings
    Route::prefix('settings')->as('setting.')->group(function () {
        Route::get('pages', [SettingController::class, 'pages'])->name('pages');
        Route::get('edit', [SettingController::class, 'edit'])->name('edit');
        Route::post('update', [SettingController::class, 'update'])->name('update');
        Route::post('update-pages', [SettingController::class, 'updatepages'])->name('updatepages');
    });

    // Resource Routes
    Route::resources([
        'admins' => AdminController::class,
        // 'permissions' => PermissionsController::class,
        'roles' => RolesController::class,
        'countries' => CountryController::class,
        'contactus' => ContactUsController::class,
        'faqs' => FaqController::class,
        'logistic-services' => LogisticServiceController::class,
        'employees' => EmployeeController::class,
        'managers' => ManagerController::class,
        'regions' => RegionController::class,
    ]);

    Route::prefix('admins/')->name('admins.')->group(function () {
        Route::post('check-email', [AdminController::class, 'checkEmail'])->name('check-email');
        Route::post('{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('{admin}/reset-password', [AdminController::class, 'resetPassword'])->name('reset-password');
        Route::post('bulk/delete', [AdminController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('bulk/status', [AdminController::class, 'bulkStatus'])->name('bulk-status');
        Route::get('export/data', [AdminController::class, 'export'])->name('export');
    });

    Route::prefix('languages')->name('languages.')->group(function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index');
        Route::get('/create', [LanguageController::class, 'create'])->name('create');
        Route::post('/', [LanguageController::class, 'store'])->name('store');
        Route::get('/{language}/edit', [LanguageController::class, 'edit'])->name('edit');
        Route::put('/{language}', [LanguageController::class, 'update'])->name('update');
        Route::delete('/{language}', [LanguageController::class, 'destroy'])->name('destroy');

        // مسارات إضافية
        Route::post('{language}/toggle', [LanguageController::class, 'toggle'])->name('toggle');
        Route::post('{language}/set-default', [LanguageController::class, 'setDefault'])->name('set-default');
        Route::post('toggle-all', [LanguageController::class, 'toggleAll'])->name('toggle-all');
    });

    // coupons
    Route::prefix('coupons')->name('coupons.')->group(function () {
        //  Route::resource('/', CouponController::class);
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
        Route::get('/{coupon}', [CouponController::class, 'show'])->name('show');
        Route::post('bulk-action', [CouponController::class, 'bulkAction'])->name('bulk-action');
        Route::post('{coupon}/duplicate', [CouponController::class, 'duplicate'])->name('duplicate');
        Route::post('generate-code', [CouponController::class, 'generateCode'])->name('generate-code');
        Route::post('validate-code', [CouponController::class, 'validateCode'])->name('validate-code');
        Route::get('export', [CouponController::class, 'export'])->name('export');
    });

    // errors
    Route::prefix('errors')->name('errors.')->group(function () {
        Route::get('/', [ErrorController::class, 'index'])->name('index');
        Route::get('/php-errors', [ErrorController::class, 'phpErrors'])->name('php-errors');
        Route::get('/search', [ErrorController::class, 'search'])->name('search');
        Route::get('/download/{filename}', [ErrorController::class, 'download'])->name('download');
        Route::delete('/destroy', [ErrorController::class, 'destroy'])->name('destroy');
        Route::post('/clear-all', [ErrorController::class, 'clearAll'])->name('clear-all');
    });

    // social-media
    Route::prefix('social-media')->name('social-media.')->group(function () {
        Route::get('/', [SocialMediaController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [SocialMediaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SocialMediaController::class, 'update'])->name('update');
        Route::post('/bulk-update', [SocialMediaController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Users
    Route::prefix('users')->as('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->withoutMiddleware('admin:1')->middleware('admin:1,0');
        Route::get('show/{id}', [UserController::class, 'show'])->name('show')->withoutMiddleware('admin:1')->middleware('admin:1,0');
        Route::get('verify/email/{id}', [UserController::class, 'verifyEmail'])->name('verify-email');
        Route::get('verify/{id}', [UserController::class, 'verify'])->name('verify');
        Route::post('reject/{id}', [UserController::class, 'reject'])->name('reject');
        Route::post('notify', [UserController::class, 'sendNotify'])->name('sendnotify');
        Route::get('archive', [UserController::class, 'archive'])->name('archive');
        Route::get('restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('force-delete/{id}', [UserController::class, 'forceDelete'])->name('forcedelete');
        Route::post('wallet-control', [UserController::class, 'walletControl'])->name('walletcontrol')->withoutMiddleware('admin:1')->middleware('admin:1,0');
        Route::post('package-control', [UserController::class, 'packageControl'])->name('package-control');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/export', [CategoryController::class, 'export'])->name('export');
        Route::post('/update-order', [CategoryController::class, 'updateOrder'])->name('update-order');
        Route::post('/generate-slug', [CategoryController::class, 'generateSlug'])->name('generate-slug');
        Route::post('/ai-enhance', [CategoryController::class, 'aiEnhance'])->name('ai-enhance');
        Route::post('/ai-translate', [CategoryController::class, 'aiTranslate'])->name('ai-translate');
        Route::post('/ai-generate-seo', [CategoryController::class, 'aiGenerateSeo'])->name('ai-generate-seo');
        Route::post('/ai-enhance-full', [CategoryController::class, 'aiEnhanceFull'])->name('ai-enhance-full');
        Route::get('/tree', [CategoryController::class, 'getTree'])->name('tree');

        // Bulk Actions
        Route::post('/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-activate', [CategoryController::class, 'bulkActivate'])->name('bulk-activate');
        Route::post('/bulk-deactivate', [CategoryController::class, 'bulkDeactivate'])->name('bulk-deactivate');
        Route::post('/bulk-move', [CategoryController::class, 'bulkMove'])->name('bulk-move');

        // Single Category
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{category}/duplicate', [CategoryController::class, 'duplicate'])->name('duplicate');
        Route::post('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
    });
    // Ads Management Routes
    Route::prefix('ads')->name('ads.')->group(function () {
        Route::get('/', [AdsController::class, 'index'])->name('index');           // List all ads
        Route::post('/', [AdsController::class, 'store'])->name('store');          // Store new ad
        Route::get('/{id}', [AdsController::class, 'show'])->name('show');         // Show single ad (AJAX)
        Route::put('/{id}', [AdsController::class, 'update'])->name('update');     // Update ad
        Route::delete('/{id}', [AdsController::class, 'destroy'])->name('destroy'); // Delete ad

        // Optional: Additional useful routes
        Route::get('/create', [AdsController::class, 'create'])->name('create');   // Show create form (if needed)
        Route::get('/{id}/edit', [AdsController::class, 'edit'])->name('edit');    // Show edit form (if needed)
    });

    // Products
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');

        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [ProductController::class, 'show'])->name('show');
        Route::post('quick-add', [ProductController::class, 'quickAdd'])->name('quick-add');
        Route::post('bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::get('export', [ProductController::class, 'export'])->name('export');
        Route::get('toggle-status/{id}', [ProductController::class, 'toggleStatus'])->name('toggle-status');

        Route::post('{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');

        // طرق الذكاء الاصطناعي
        Route::post('/ai/enhance', [ProductController::class, 'enhanceWithAI'])->name('ai-enhance');
        Route::post('/ai/generate-seo', [ProductController::class, 'generateSEOWithAI'])->name('ai-generate-seo');
        Route::post('/ai/generate-text-ads', [ProductController::class, 'generateTextAdsWithAI'])->name('ai-generate-text-ads');
        Route::post('/ai/translate', [ProductController::class, 'translateWithAI'])->name('ai-translate');
        Route::post('/ai/enhance-full', [ProductController::class, 'enhanceFullProductWithAI'])->name('ai-enhance-full');
        // طرق إنشاء المنتج الجديد
        Route::get('/create-with-ai', [ProductController::class, 'createWithAI'])
            ->name('admin.products.create-with-ai');

        Route::post('/store-with-ai', [ProductController::class, 'storeWithAI'])
            ->name('admin.products.store-with-ai');
    });
    Route::post('products/update/{id}', [ProductController::class, 'update'])->name('products.update');

    // Contacts
    // Route::prefix('contacts')->as('contact.')->group(function () {
    //     Route::get('/', [ContactController::class, 'index'])->name('index');
    //     Route::get('read/{id}', [ContactController::class, 'read'])->name('read');
    //     Route::delete('delete/{id}', [ContactController::class, 'destroy'])->name('destroy');
    // });

    // Subscriptions
    Route::prefix('subscriptions')->as('subscribe.')->group(function () {
        Route::get('/', [SubscribeController::class, 'index'])->name('index');
    });

    // Additional Routes
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/export', [ProductController::class, 'export'])->name('export');
        Route::post('/{product}/update-image', [ProductController::class, 'updateImage'])
            ->name('update-image');
    });

    // Payment Method
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::patch(
        'payment-methods/toggle-status/{paymentMethod}',
        [PaymentMethodController::class, 'toggleStatus']
    )->name('payment-methods.toggle-status');

    // // Users
    // Route::prefix('users')->as('users.')->group(function () {
    //     Route::get('/', [UserController::class, 'index'])->name('index');
    //     Route::get('/create', [UserController::class, 'create'])->name('create');
    //     Route::post('/', [UserController::class, 'store'])->name('store');
    //     Route::get('/{user}', [UserController::class, 'show'])->name('show');
    //     Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    //     Route::put('/{user}', [UserController::class, 'update'])->name('update');
    //     Route::patch('/{user}', [UserController::class, 'update']);
    //     Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    //     Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    //     Route::get('/{user}/orders', [UserController::class, 'orders'])->name('orders');
    //     Route::get('/{user}/reviews', [UserController::class, 'reviews'])->name('reviews');
    //     Route::get('/{user}/favourites', [UserController::class, 'favourites'])->name('favourites');
    //     Route::get('/{user}/activities', [UserController::class, 'activities'])->name('activities');
    // });
    // Users
    // Users
    Route::prefix('users')->as('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::patch('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{user}/orders', [UserController::class, 'orders'])->name('orders');
        Route::get('/{user}/reviews', [UserController::class, 'reviews'])->name('reviews');
        Route::get('/{user}/favourites', [UserController::class, 'favourites'])->name('favourites');
        Route::get('/{user}/activities', [UserController::class, 'activities'])->name('activities');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/stats', [UserController::class, 'getStats'])->name('stats');
        // Wallet Routes - يجب أن يكون الـ parameter قبل wallet
        Route::prefix('{user}/wallet')->as('wallet.')->group(function () {
            Route::get('/info', [UserController::class, 'walletInfo'])->name('info');
            Route::post('/create', [UserController::class, 'createWallet'])->name('create');
            Route::post('/deposit', [UserController::class, 'deposit'])->name('deposit');
            Route::post('/withdraw', [UserController::class, 'withdraw'])->name('withdraw');
            Route::post('/quick-deposit', [UserController::class, 'quickDeposit'])->name('quick-deposit');
            Route::get('/transactions', [UserController::class, 'getTransactions'])->name('transactions');
            Route::get('/export-transactions', [UserController::class, 'exportTransactions'])->name('export-transactions');
        });
    });
    // Banner Routes
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::get('/create', [BannerController::class, 'create'])->name('create');
        Route::post('/', [BannerController::class, 'store'])->name('store');
        Route::get('/{banner}', [BannerController::class, 'show'])->name('show');
        Route::get('/{banner}/edit', [BannerController::class, 'edit'])->name('edit');
        Route::put('/{banner}', [BannerController::class, 'update'])->name('update');
        Route::delete('/{banner}', [BannerController::class, 'destroy'])->name('destroy');
        Route::post('/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('toggle-status');

        // Banner Items Routes - إضافة route للعرض
        Route::get('/items/{bannerItem}', [BannerItemController::class, 'show'])->name('items.show'); // أضف هذا السطر
        Route::post('/items', [BannerItemController::class, 'store'])->name('items.store');
        Route::put('/items/{bannerItem}', [BannerItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{bannerItem}', [BannerItemController::class, 'destroy'])->name('items.destroy');
        Route::post('/items/{bannerItem}/toggle-status', [BannerItemController::class, 'toggleStatus'])->name('items.toggle-status');
        Route::post('/items/reorder', [BannerItemController::class, 'reorder'])->name('items.reorder');
    });

    // Orders
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');

        // لازم ييجوا قبل {id} و {order}
        Route::get('/search-products', [OrderController::class, 'searchProducts'])->name('search-products');
        Route::get('/get-product/{id}', [OrderController::class, 'getProduct'])->name('get-product');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
        Route::get('/{order}/print', [OrderController::class, 'print'])->name('print');
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');

        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    });

    // Routes for Roles and Permissions
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::post('/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('permissions.sync');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::post('/generate', [PermissionController::class, 'generateForModule'])->name('generate');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });

    // Route::prefix('articles')->name('articles.')->group(function () {
    //     // مقالات
    //     Route::resource('/', ArticleController::class);
    //     Route::post('/bulk-actions', [ArticleController::class, 'bulkActions'])->name('bulk-actions');
    //     Route::patch('/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('toggle-status');
    //     Route::patch('/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])->name('toggle-featured');
    // });
    // Routes for AI-powered articles

    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/create-with-ai', [ArticleController::class, 'create'])->name('create');
        Route::post('/store-with-ai', [ArticleController::class, 'storeWithAI'])->name('store-with-ai');
        Route::post('/ai-enhance', [ArticleController::class, 'enhanceWithAI'])->name('ai-enhance');
        Route::post('/ai-generate', [ArticleController::class, 'generateWithAI'])->name('ai-generate');

        // إضافة الرو routes الجديدة للذكاء الاصطناعي
        Route::post('/ai-generate-full', [ArticleController::class, 'generateFullArticle'])->name('ai-generate-full');
        Route::post('/ai-generate-title', [ArticleController::class, 'generateTitle'])->name('ai-generate-title');
        Route::post('/ai-generate-content', [ArticleController::class, 'generateContent'])->name('ai-generate-content');
        Route::post('/ai-enhance-content', [ArticleController::class, 'enhanceContent'])->name('ai-enhance-content');
        Route::post('/ai-generate-excerpt', [ArticleController::class, 'generateExcerpt'])->name('ai-generate-excerpt');
        Route::post('/ai-translate-all', [ArticleController::class, 'translateAll'])->name('ai-translate-all');
        Route::post('/ai-improve-all', [ArticleController::class, 'improveAll'])->name('ai-improve-all');
        Route::post('/ai-generate-meta-title', [ArticleController::class, 'generateMetaTitle'])->name('ai-generate-meta-title');
        Route::post('/ai-generate-meta-description', [ArticleController::class, 'generateMetaDescription'])->name('ai-generate-meta-description');
        Route::post('/ai-generate-keywords', [ArticleController::class, 'generateKeywords'])->name('ai-generate-keywords');
        Route::post('/ai-enhance-text', [ArticleController::class, 'enhanceText'])->name('ai-enhance-text');

        // المقالات
        Route::resource('/', ArticleController::class);
        Route::post('/bulk-actions', [ArticleController::class, 'bulkActions'])->name('bulk-actions');
        Route::patch('/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])->name('toggle-featured');
    });

    // إحصائيات المقالات
    Route::get('/articles/statistics', [ArticleController::class, 'statistics'])->name('articles.statistics');

    Route::prefix('assign-roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'assignIndex'])->name('assign.index');
        Route::post('/', [RoleController::class, 'assignRoles'])->name('assign.store');
    });

    Route::prefix('static-pages')->name('static-pages.')->group(function () {
        // CRUD الأساسي
        Route::get('/', [StaticPageController::class, 'index'])->name('index');
        Route::get('/create', [StaticPageController::class, 'create'])->name('create');
        Route::post('/', [StaticPageController::class, 'store'])->name('store');
        Route::get('/{staticPage}', [StaticPageController::class, 'show'])->name('show');
        Route::get('/{page}/edit', [StaticPageController::class, 'edit'])->name('edit');
        Route::put('/{page}', [StaticPageController::class, 'update'])->name('update');
        name:
        Route::delete('/{page}', [StaticPageController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [StaticPageController::class, 'bulkAction'])->name('bulk-action');
        // طرق الذكاء الاصطناعي
        Route::get('/{page}/edit-with-ai', [StaticPageController::class, 'editWithAI'])->name('edit-with-ai');
        Route::prefix('ai')->name('ai.')->group(function () {
            // تحسينات عامة
            Route::post('/enhance-title', [StaticPageController::class, 'enhanceTitleWithAI'])
                ->name('enhance-title');

            Route::post('/translate-content', [StaticPageController::class, 'translateContentWithAI'])->name('translate-content');
            Route::post('/translate', [StaticPageController::class, 'translateWithAI'])->name('translate');
            Route::post('/enhance-content', [StaticPageController::class, 'enhanceContentWithAI'])
                ->name('enhance-content');

            Route::post('/expand-content', [StaticPageController::class, 'enhanceContentWithAI'])
                ->name('expand-content');

            Route::post('/simplify-content', [StaticPageController::class, 'enhanceContentWithAI'])
                ->name('simplify-content');

            Route::post('/translate', [StaticPageController::class, 'translateWithAI'])
                ->name('translate');

            // القوالب
            Route::post('/load-template', [StaticPageController::class, 'loadTemplateWithAI'])
                ->name('load-template');

            Route::post('/generate-from-prompt', [StaticPageController::class, 'generateFromPromptWithAI'])
                ->name('generate-from-prompt');

            Route::post('/generate-page', [StaticPageController::class, 'generatePageWithAI'])
                ->name('generate-page');

            // المكونات
            Route::post('/generate-title', [StaticPageController::class, 'generateTitleWithAI'])
                ->name('generate-title');

            Route::post('/generate-content', [StaticPageController::class, 'generateContentWithAI'])
                ->name('generate-content');

            Route::post('/format-content', [StaticPageController::class, 'formatContentWithAI'])
                ->name('format-content');

            Route::post('/check-grammar', [StaticPageController::class, 'checkGrammarWithAI'])
                ->name('check-grammar');

            Route::post('/enhance-text', [StaticPageController::class, 'enhanceTextWithAI'])
                ->name('enhance-text');

            Route::post('/add-section', [StaticPageController::class, 'addSectionWithAI'])
                ->name('add-section');

            // SEO (مرة واحدة بس)
            Route::post('/generate-meta-title', [StaticPageController::class, 'generateMetaTitleWithAI'])
                ->name('generate-meta-title');

            Route::post('/generate-meta-description', [StaticPageController::class, 'generateMetaDescriptionWithAI'])
                ->name('generate-meta-description');

            Route::post('/generate-keywords', [StaticPageController::class, 'generateKeywordsWithAI'])
                ->name('generate-keywords');
        });
    });

    Route::prefix('/settings')->name('settings.')->group(function () {
        // Index
        Route::get('/', [SettingsController::class, 'index'])->name('index');

        // SMTP Settings
        Route::prefix('smtp')->group(function () {
            Route::get('/', [SettingsController::class, 'smtp'])->name('smtp');
            Route::put('/', [SettingsController::class, 'updateSmtp'])->name('smtp.update');
            Route::post('/test', [SettingsController::class, 'testSmtp'])->name('smtp.test');
        });

        // General Settings
        Route::prefix('general')->group(function () {
            Route::get('/', [SettingsController::class, 'general'])->name('general');
            Route::put('/', [SettingsController::class, 'updateGeneral'])->name('general.update');
        });
        // Communication Settings
        Route::prefix('communication')->group(function () {
            Route::get('/', [SettingsController::class, 'communication'])->name('communication');
            Route::put('/', [SettingsController::class, 'updateCommunication'])->name('communication.update');
        });
        // File Manager
        Route::prefix('files')->group(function () {
            Route::get('/', [SettingsController::class, 'files'])->name('files');
            Route::put('/', [SettingsController::class, 'updateFiles'])->name('files.update');
            Route::delete('/', [SettingsController::class, 'deleteFile'])->name('files.delete');
            Route::post('/clear-temp', [SettingsController::class, 'clearTempFiles'])->name('files.clear-temp');
        });
        Route::get('/', [SettingsController::class, 'index'])->name('index');

        // AJAX endpoints
        Route::get('/storage-usage', [SettingsController::class, 'getStorageUsage'])->name('storage-usage');
        Route::get('/quick-stats', [SettingsController::class, 'getQuickStats'])->name('quick-stats');
        Route::get('/recent-activities', [SettingsController::class, 'getRecentActivitiesAjax'])->name('recent-activities');
        Route::get('/system-status', [SettingsController::class, 'getSystemStatus'])->name('system-status');
        Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
        Route::post('/toggle-maintenance', [SettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
    });
    // مسارات التواصل
    Route::prefix('contact-us')->name('contact-us.')->group(function () {
        Route::get('/', [ContactUsController::class, 'index'])->name('index');
        Route::get('/{contactUs}', [ContactUsController::class, 'show'])->name('show');
        Route::post('/{contactUs}/reply', [ContactUsController::class, 'reply'])->name('reply');
        Route::post('/{contactUs}/status', [ContactUsController::class, 'updateStatus'])->name('status');
        Route::delete('/{contactUs}', [ContactUsController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/status', [ContactUsController::class, 'bulkStatus'])->name('bulk-status');
        Route::post('/bulk/destroy', [ContactUsController::class, 'bulkDestroy'])->name('bulk-destroy');
    });
    Route::get('order/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
});

// Visitor stats route (outside admin group)
Route::get('/orders/stats/{year}', [VisitorController::class, 'ordersStats']);
