<?php

namespace App\Http\Controllers\Admin;


use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Mail\TestSmtpMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use App\Models\ImportantModel;
class SettingsController extends Controller
{
    /**
     * Display SMTP settings page.
     */
    public function smtp()
    {
        $settings = [
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => config('mail.mailers.smtp.password'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'mail_auth' => config('mail.mailers.smtp.auth'),
            'mail_verify_peer' => config('mail.mailers.smtp.verify_peer', true),
            'mail_timeout' => config('mail.mailers.smtp.timeout', 30),
            'mail_max_attempts' => config('mail.mailers.smtp.max_attempts', 3),
        ];

        return view('Admin.settings.smtp-settings', compact('settings'));
    }

    /**
     * Update SMTP settings.
     */
    public function updateSmtp(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|in:ssl,tls,null',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'mail_auth' => 'boolean',
            'mail_verify_peer' => 'boolean',
            'mail_timeout' => 'integer|min:10|max:120',
            'mail_max_attempts' => 'integer|min:1|max:10',
        ]);

        // Update .env file or database settings
        $this->updateEnvSettings([
            'MAIL_HOST' => $validated['mail_host'],
            'MAIL_PORT' => $validated['mail_port'],
            'MAIL_USERNAME' => $validated['mail_username'],
            'MAIL_PASSWORD' => $validated['mail_password'] ?: config('mail.mailers.smtp.password'),
            'MAIL_ENCRYPTION' => $validated['mail_encryption'] == 'null' ? '' : $validated['mail_encryption'],
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME' => $validated['mail_from_name'],
            'MAIL_AUTH' => $validated['mail_auth'] ? 'true' : 'false',
        ]);

        // Update database settings
        foreach ($validated as $key => $value) {
            if ($key !== 'mail_password' || !empty($value)) {
                setting([$key => $value]);
            }
        }

        setting()->save();

        // Clear mail cache
        Cache::forget('mail.config');

        return back()->with('success', 'تم تحديث إعدادات البريد بنجاح.');
    }

    /**
     * Test SMTP settings.
     */
    public function testSmtp(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        try {
            // Temporarily update mail configuration
            config([
                'mail.mailers.smtp.host' => $request->mail_host,
                'mail.mailers.smtp.port' => $request->mail_port,
                'mail.mailers.smtp.username' => $request->mail_username,
                'mail.mailers.smtp.password' => $request->mail_password ?: config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.encryption' => $request->mail_encryption,
                'mail.from.address' => $request->mail_from_address,
                'mail.from.name' => $request->mail_from_name,
            ]);

            // Send test email
            Mail::to($request->test_email)->send(new TestSmtpMail());

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال البريد التجريبي بنجاح. يرجى التحقق من صندوق الوارد.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال البريد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display general settings page.
     */

public function general()
{
    // 1️⃣ الإعدادات العامة (غير مترجمة)
    $settings = [
        'site_url' => setting('site_url', config('app.url')),
        'site_logo' => setting('site_logo', ''),
        'site_favicon' => setting('site_favicon', ''),
        'site_language' => setting('site_language', 'ar'),
        'site_currency' => setting('site_currency', 'EGP'),
        'site_timezone' => setting('site_timezone', 'Asia/Riyadh'),
        'date_format' => setting('date_format', 'Y-m-d'),
        'time_format' => setting('time_format', '24'),
        'site_maintenance' => setting('site_maintenance', false),
        'user_registration' => setting('user_registration', true),
        'email_verification' => setting('email_verification', false),
        'allow_comments' => setting('allow_comments', true),
        'items_per_page' => setting('items_per_page', 15),
        'cache_ttl' => setting('cache_ttl', 60),
        'contact_email' => setting('contact_email', ''),
        'contact_phone' => setting('contact_phone', ''),
        'contact_address' => setting('contact_address', ''),
    ];

    // 2️⃣ اللغات من قاعدة البيانات
    $languages = Language::all();

    // 3️⃣ جلب النصوص المهمة كلها مرة واحدة
    $importantTexts = ImportantModel::pluck('important_text', 'model')->toArray();

    // 4️⃣ تعبئة القيم المترجمة داخل $settings
    foreach ($languages as $lang) {
        $settings["site_name_{$lang->code}"] =
            $importantTexts["site_name_{$lang->code}"] ?? '';

        $settings["site_title_{$lang->code}"] =
            $importantTexts["site_title_{$lang->code}"] ?? '';

        $settings["site_description_{$lang->code}"] =
            $importantTexts["site_description_{$lang->code}"] ?? '';

        $settings["site_keywords_{$lang->code}"] =
            $importantTexts["site_keywords_{$lang->code}"] ?? '';
    }

    // 5️⃣ العملات
    $currencies = [
        'SAR' => ['name' => 'ريال سعودي', 'symbol' => 'ر.س'],
        'USD' => ['name' => 'دولار أمريكي', 'symbol' => '$'],
        'EUR' => ['name' => 'يورو', 'symbol' => '€'],
        'EGP' => ['name' => 'جنيه مصري', 'symbol' => 'ج.م'],
        'AED' => ['name' => 'درهم إماراتي', 'symbol' => 'د.إ'],
    ];

    // 6️⃣ المناطق الزمنية
    $timezones = [
        'Africa/Cairo',
        'Asia/Riyadh',
        'Asia/Dubai',
        'Asia/Beirut',
        'Asia/Amman',
        'Asia/Baghdad',
        'Europe/London',
        'Europe/Paris',
        'America/New_York',
        'America/Los_Angeles',
    ];

    return view(
        'Admin.settings.general-settings',
        compact('settings', 'languages', 'currencies', 'timezones')
    );
}

    /**
     * Update general settings.
     */

public function updateGeneral(Request $request)
{
    // 1) لغات السيستم
    $langs = Language::all();

    // 2) قواعد التحقق الأساسية
    $rules = [
        'site_url' => 'required|url',
        'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico|max:1024',
        'site_language' => 'required|string|max:10',
        'site_currency' => 'required|string|max:10',
        'site_timezone' => 'required|string|max:50',
        'date_format' => 'required|string|max:20',
        'time_format' => 'required|in:12,24',
        'site_maintenance' => 'boolean',
        'user_registration' => 'boolean',
        'email_verification' => 'boolean',
        'allow_comments' => 'boolean',
        'items_per_page' => 'required|integer|min:5|max:100',
        'cache_ttl' => 'nullable|integer|min:1|max:1440',
        'contact_email' => 'required|email',
        'contact_phone' => 'nullable|string|max:20',
        'contact_address' => 'nullable|string',
    ];

    // 3) قواعد التحقق للحقول المترجمة (Tabs)
    foreach ($langs as $lang) {
        $rules["site_name_{$lang->code}"] = 'required|string|max:255';
        $rules["site_title_{$lang->code}"] = 'required|string|max:255';
        $rules["site_description_{$lang->code}"] = 'nullable|string';
        $rules["site_keywords_{$lang->code}"] = 'nullable|string';
    }

    $validated = $request->validate($rules);

    // 4) Upload logo
    if ($request->hasFile('site_logo')) {
        $logoPath = $request->file('site_logo')->store('settings', 'public');
        $validated['site_logo'] = $logoPath;

        $oldLogo = setting('site_logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }
    } else {
        unset($validated['site_logo']);
    }

    // 5) Upload favicon
    if ($request->hasFile('site_favicon')) {
        $faviconPath = $request->file('site_favicon')->store('settings', 'public');
        $validated['site_favicon'] = $faviconPath;

        $oldFavicon = setting('site_favicon');
        if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
            Storage::disk('public')->delete($oldFavicon);
        }
    } else {
        unset($validated['site_favicon']);
    }

    // 6) حفظ الإعدادات العامة (غير مترجمة) في setting()
    $generalKeys = [
        'site_url',
        'site_logo',
        'site_favicon',
        'site_language',
        'site_currency',
        'site_timezone',
        'date_format',
        'time_format',
        'site_maintenance',
        'user_registration',
        'email_verification',
        'allow_comments',
        'items_per_page',
        'cache_ttl',
        'contact_email',
        'contact_phone',
        'contact_address',
    ];

    foreach ($generalKeys as $key) {
        if (array_key_exists($key, $validated)) {
            setting([$key => $validated[$key]]);
        }
    }

    // 7) حفظ الحقول المترجمة داخل important_models
    // model = key (مثلاً site_title_ar) | important_text = value
    foreach ($langs as $lang) {
        $translatedKeys = [
            "site_name_{$lang->code}",
            "site_title_{$lang->code}",
            "site_description_{$lang->code}",
            "site_keywords_{$lang->code}",
        ];
foreach ($translatedKeys as $tKey) {
    if (!array_key_exists($tKey, $validated)) {
        continue;
    }

    // تجاهل null (خصوصًا description/keywords)
    if ($validated[$tKey] === null) {
        // لو تحب تمسح القديم بدل التجاهل:
        // ImportantModel::where('model', $tKey)->delete();
        continue;
    }

    ImportantModel::updateOrCreate(
        ['model' => $tKey],
        ['important_text' => $validated[$tKey]]
    );
}
    }

    // 8) حفظ setting() مرة واحدة
    setting()->save();

    // 9) تحديث .env (هنا هنستخدم اللغة الأساسية المختارة)
    $defaultLang = $validated['site_language']; // مثال: ar

    $appName = $validated["site_name_{$defaultLang}"] ?? ($validated["site_name_ar"] ?? null);
    $appUrl  = $validated['site_url'];

    // $this->updateEnvSettings([
    //     'APP_NAME' => $appName ?: config('app.name'),
    //     'APP_URL' => $appUrl,
    //     'APP_LOCALE' => $validated['site_language'],
    //     'APP_TIMEZONE' => $validated['site_timezone'],
    // ]);

    // 10) Clear cache
    Cache::flush();

    return back()->with('success', 'تم تحديث الإعدادات العامة بنجاح.');
}
    /**
     * Display communication settings page.
     */
    public function communication()
    {
        $settings = [
            'email_from_name' => setting('email_from_name', config('mail.from.name')),
            'email_from_address' => setting('email_from_address', config('mail.from.address')),
            'email_reply_to' => setting('email_reply_to', ''),
            'email_bcc' => setting('email_bcc', ''),
            'email_html' => setting('email_html', true),
            
            // Social Media
            'social_facebook_enabled' => setting('social_facebook_enabled', false),
            'social_facebook_url' => setting('social_facebook_url', ''),
            'social_twitter_enabled' => setting('social_twitter_enabled', false),
            'social_twitter_url' => setting('social_twitter_url', ''),
            'social_instagram_enabled' => setting('social_instagram_enabled', false),
            'social_instagram_url' => setting('social_instagram_url', ''),
            'social_linkedin_enabled' => setting('social_linkedin_enabled', false),
            'social_linkedin_url' => setting('social_linkedin_url', ''),
            'social_youtube_enabled' => setting('social_youtube_enabled', false),
            'social_youtube_url' => setting('social_youtube_url', ''),
            'social_whatsapp_enabled' => setting('social_whatsapp_enabled', false),
            'social_whatsapp_url' => setting('social_whatsapp_url', ''),
            'social_telegram_enabled' => setting('social_telegram_enabled', false),
            'social_telegram_url' => setting('social_telegram_url', ''),
            'social_tiktok_enabled' => setting('social_tiktok_enabled', false),
            'social_tiktok_url' => setting('social_tiktok_url', ''),
            
            // Notifications
            'email_notifications' => setting('email_notifications', true),
            'system_notifications' => setting('system_notifications', true),
            'new_user_notification' => setting('new_user_notification', true),
            'new_order_notification' => setting('new_order_notification', true),
            'admin_notification_email' => setting('admin_notification_email', ''),
            
            // Contact Form
            'contact_form_enabled' => setting('contact_form_enabled', true),
            'contact_form_email' => setting('contact_form_email', ''),
            'contact_form_cc_sender' => setting('contact_form_cc_sender', true),
            'contact_form_thankyou_message' => setting('contact_form_thankyou_message', 'شكراً لتواصلك معنا. سنرد عليك قريباً.'),
        ];

        return view('Admin.settings.communication-settings', compact('settings'));
    }

    /**
     * Update communication settings.
     */
    public function updateCommunication(Request $request)
    {
        $validated = $request->validate([
            'email_from_name' => 'required|string|max:255',
            'email_from_address' => 'required|email',
            'email_reply_to' => 'nullable|email',
            'email_bcc' => 'nullable|string',
            'email_html' => 'boolean',
            
            // Social Media validation
            'social_media.*.enabled' => 'boolean',
            'social_media.*.url' => 'nullable|url',
            
            // Notifications
            'email_notifications' => 'boolean',
            'system_notifications' => 'boolean',
            'new_user_notification' => 'boolean',
            'new_order_notification' => 'boolean',
            'admin_notification_email' => 'nullable|email',
            
            // Contact Form
            'contact_form_enabled' => 'boolean',
            'contact_form_email' => 'nullable|email',
            'contact_form_cc_sender' => 'boolean',
            'contact_form_thankyou_message' => 'nullable|string',
        ]);

        // Update email settings in .env
        if (isset($validated['email_from_name']) || isset($validated['email_from_address'])) {
            $this->updateEnvSettings([
                'MAIL_FROM_NAME' => $validated['email_from_name'] ?? config('mail.from.name'),
                'MAIL_FROM_ADDRESS' => $validated['email_from_address'] ?? config('mail.from.address'),
            ]);
        }

        // Process social media settings
        if (isset($validated['social_media'])) {
            foreach ($validated['social_media'] as $platform => $data) {
                $validated["social_{$platform}_enabled"] = $data['enabled'] ?? false;
                $validated["social_{$platform}_url"] = $data['url'] ?? '';
            }
            unset($validated['social_media']);
        }

        // Save settings
        foreach ($validated as $key => $value) {
            setting([$key => $value]);
        }

        setting()->save();

        return back()->with('success', 'تم تحديث إعدادات التواصل بنجاح.');
    }

    /**
     * Display file manager settings page.
     */
    public function files()
    {
        $settings = [
            'storage_driver' => setting('storage_driver', config('filesystems.default')),
            'max_upload_size' => setting('max_upload_size', 2048), // KB
            'max_files_per_upload' => setting('max_files_per_upload', 10),
            'file_lifetime' => setting('file_lifetime', 7), // days
            
            // File type limits
            'allowed_image_types' => setting('allowed_image_types', ['jpg', 'jpeg', 'png', 'gif']),
            'max_image_size' => setting('max_image_size', 2048), // KB
            'allowed_document_types' => setting('allowed_document_types', ['pdf', 'doc', 'docx', 'txt']),
            'max_document_size' => setting('max_document_size', 5120), // KB
            'allowed_video_types' => setting('allowed_video_types', ['mp4', 'avi', 'mov', 'wmv']),
            'max_video_size' => setting('max_video_size', 10240), // KB
            'allowed_audio_types' => setting('allowed_audio_types', ['mp3', 'wav', 'ogg']),
            'max_audio_size' => setting('max_audio_size', 5120), // KB
            
            // Advanced settings
            'compress_images' => setting('compress_images', false),
            'rename_uploads' => setting('rename_uploads', true),
            'watermark_images' => setting('watermark_images', false),
        ];

        // Calculate storage usage
        $storage_usage = $this->calculateStorageUsage();

        // Get recent files
        $recent_files = $this->getRecentFiles();

        return view('Admin.settings.file-manager', compact('settings', 'storage_usage', 'recent_files'));
    }

    /**
     * Update file manager settings.
     */
    public function updateFiles(Request $request)
    {
        $validated = $request->validate([
            'storage_driver' => 'required|in:local,public,s3',
            'max_upload_size' => 'required|integer|min:100|max:10240',
            'max_files_per_upload' => 'required|integer|min:1|max:50',
            'file_lifetime' => 'required|integer|min:1|max:30',
            'compress_images' => 'boolean',
            'rename_uploads' => 'boolean',
            'watermark_images' => 'boolean',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            setting([$key => $value]);
        }

        setting()->save();

        // Update filesystem configuration
        if ($validated['storage_driver'] !== config('filesystems.default')) {
            $this->updateEnvSettings([
                'FILESYSTEM_DISK' => $validated['storage_driver']
            ]);
        }

        return back()->with('success', 'تم تحديث إعدادات الملفات بنجاح.');
    }

    /**
     * Delete a file.
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'file_id' => 'required|string',
        ]);

        try {
            // In a real application, you would have a File model
            // For now, we'll simulate file deletion
            $filePath = $request->file_id;
            
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الملف بنجاح.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear temporary files.
     */
    public function clearTempFiles()
    {
        try {
            $tempPath = storage_path('app/temp');
            $daysAgo = setting('file_lifetime', 7);
            
            if (File::exists($tempPath)) {
                $files = File::allFiles($tempPath);
                $deletedCount = 0;
                
                foreach ($files as $file) {
                    if ($file->getMTime() < now()->subDays($daysAgo)->timestamp) {
                        File::delete($file->getPathname());
                        $deletedCount++;
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => "تم حذف {$deletedCount} ملفاً مؤقتاً."
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'لا توجد ملفات مؤقتة للحذف.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الملفات المؤقتة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate storage usage.
     */
    private function calculateStorageUsage()
    {
        $totalSize = 0;
        
        // Calculate size of public storage
        $publicPath = storage_path('app/public');
        if (File::exists($publicPath)) {
            foreach (File::allFiles($publicPath) as $file) {
                $totalSize += $file->getSize();
            }
        }
        
        // In a real application, you might have other storage locations
        $maxSize = 1073741824; // 1GB in bytes for demonstration
        
        $used = $totalSize;
        $available = max(0, $maxSize - $used);
        $percentage = $maxSize > 0 ? round(($used / $maxSize) * 100, 2) : 0;
        
        return [
            'used' => $used,
            'total' => $maxSize,
            'available' => $available,
            'percentage' => $percentage,
        ];
    }

    /**
     * Get recent files.
     */
    private function getRecentFiles()
    {
        $files = [];
        
        // In a real application, you would fetch files from database
        // For demonstration, we'll create sample data
        
        $sampleFiles = [
            [
                'id' => '1',
                'name' => 'company-logo.png',
                'type' => 'image',
                'type_name' => 'صورة',
                'icon' => 'fas fa-image',
                'size' => 102400, // 100KB
                'url' => '#',
                'uploaded_at' => 'قبل 2 ساعة'
            ],
            [
                'id' => '2',
                'name' => 'product-catalog.pdf',
                'type' => 'document',
                'type_name' => 'مستند',
                'icon' => 'fas fa-file-pdf',
                'size' => 512000, // 500KB
                'url' => '#',
                'uploaded_at' => 'قبل يوم'
            ],
            [
                'id' => '3',
                'name' => 'promo-video.mp4',
                'type' => 'video',
                'type_name' => 'فيديو',
                'icon' => 'fas fa-video',
                'size' => 5242880, // 5MB
                'url' => '#',
                'uploaded_at' => 'قبل 3 أيام'
            ],
        ];
        
        return $sampleFiles;
    }

    /**
     * Update .env file settings.
     */
    private function updateEnvSettings(array $settings)
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            return false;
        }
        
        $envContent = File::get($envPath);
        
        foreach ($settings as $key => $value) {
            // Escape special characters in value
            $escapedValue = '"' . addcslashes($value, '"') . '"';
            
            // Pattern to find the setting
            $pattern = "/^{$key}=.*/m";
            
            if (preg_match($pattern, $envContent)) {
                // Replace existing setting
                $envContent = preg_replace($pattern, "{$key}={$escapedValue}", $envContent);
            } else {
                // Add new setting at the end
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }
        
        File::put($envPath, $envContent);
        
        return true;
    }

  /**
     * Display settings dashboard.
     */
    public function index()
    {
        // Storage usage
        $storageUsage = $this->calculateStorageUsage();
        $storageUsage['available_human'] = $this->formatBytes($storageUsage['available']);
        $storageUsage['used_human'] = $this->formatBytes($storageUsage['used']);
        $storageUsage['total_human'] = $this->formatBytes($storageUsage['total']);

        // Recent activities
        $recentActivities = $this->getRecentActivities();

        // Settings counts
        $settingsCounts = [
            'generalSettingsCount' => $this->countGeneralSettings(),
            'communicationSettingsCount' => $this->countCommunicationSettings(),
            'smtpSettingsCount' => $this->countSmtpSettings(),
        ];

        // Last updates
        $lastUpdates = [
            'lastGeneralUpdate' => $this->getLastUpdate('general'),
            'lastCommunicationUpdate' => $this->getLastUpdate('communication'),
            'lastSmtpTest' => $this->getLastSmtpTest(),
        ];

        // System status
        $systemStatus = [
            'smtpStatus' => $this->getSmtpStatus(),
            'cacheStatus' => $this->getCacheStatus(),
            'maintenanceMode' => app()->isDownForMaintenance(),
        ];

        return view('Admin.settings.index', array_merge(
            compact('storageUsage', 'recentActivities'),
            $settingsCounts,
            $lastUpdates,
            $systemStatus
        ));
    }

    /**
     * Get recent activities for dashboard.
     */
    private function getRecentActivities()
    {
        // In a real application, you would fetch from database
        // For demonstration, we'll create sample data
        
        return [
            [
                'type' => 'update',
                'icon' => 'edit',
                'title' => 'تحديث الإعدادات العامة',
                'description' => 'تم تعديل شعار الموقع والإعدادات الأساسية',
                'time' => 'قبل 30 دقيقة'
            ],
            [
                'type' => 'test',
                'icon' => 'paper-plane',
                'title' => 'اختبار البريد الإلكتروني',
                'description' => 'تم إرسال بريد اختباري إلى admin@example.com',
                'time' => 'قبل ساعتين'
            ],
            [
                'type' => 'add',
                'icon' => 'plus',
                'title' => 'إضافة وسائل تواصل',
                'description' => 'تم إضافة حسابات التواصل الاجتماعي',
                'time' => 'قبل 3 أيام'
            ],
            [
                'type' => 'delete',
                'icon' => 'trash',
                'title' => 'حذف ملفات مؤقتة',
                'description' => 'تم تنظيف 15 ملفاً مؤقتاً قديماً',
                'time' => 'قبل 5 أيام'
            ],
        ];
    }

    /**
     * Count general settings.
     */
    private function countGeneralSettings()
    {
        $settings = [
            'site_name', 'site_title', 'site_description', 'site_keywords', 'site_url',
            'site_logo', 'site_favicon', 'site_language', 'site_currency', 'site_timezone',
            'date_format', 'time_format', 'site_maintenance', 'user_registration',
            'email_verification', 'allow_comments', 'items_per_page', 'cache_ttl',
            'contact_email', 'contact_phone', 'contact_address'
        ];
        
        $count = 0;
        foreach ($settings as $setting) {
            if (setting($setting) !== null) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Count communication settings.
     */
    private function countCommunicationSettings()
    {
        $settings = [
            'email_from_name', 'email_from_address', 'email_reply_to', 'email_bcc', 'email_html',
            'social_facebook_enabled', 'social_facebook_url', 'social_twitter_enabled', 'social_twitter_url',
            'social_instagram_enabled', 'social_instagram_url', 'social_linkedin_enabled', 'social_linkedin_url',
            'social_youtube_enabled', 'social_youtube_url', 'social_whatsapp_enabled', 'social_whatsapp_url',
            'social_telegram_enabled', 'social_telegram_url', 'social_tiktok_enabled', 'social_tiktok_url',
            'email_notifications', 'system_notifications', 'new_user_notification', 'new_order_notification',
            'admin_notification_email', 'contact_form_enabled', 'contact_form_email',
            'contact_form_cc_sender', 'contact_form_thankyou_message'
        ];
        
        $count = 0;
        foreach ($settings as $setting) {
            if (setting($setting) !== null) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Count SMTP settings.
     */
    private function countSmtpSettings()
    {
        $settings = [
            'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption',
            'mail_from_address', 'mail_from_name', 'mail_auth', 'mail_verify_peer',
            'mail_timeout', 'mail_max_attempts'
        ];
        
        $count = 0;
        foreach ($settings as $setting) {
            if (setting($setting) !== null) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get last update time for a settings category.
     */
    private function getLastUpdate($category)
    {
        $timestamp = setting("last_update_{$category}");
        
        if (!$timestamp) {
            return 'لم يتم التحديث';
        }
        
        $date = Carbon::parse($timestamp);
        $now = Carbon::now();
        
        $diff = $date->diffForHumans($now, [
            'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
            'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS,
            'locale' => 'ar'
        ]);
        
        return $diff;
    }

    /**
     * Get last SMTP test time.
     */
    private function getLastSmtpTest()
    {
        $timestamp = setting('last_smtp_test');
        
        if (!$timestamp) {
            return 'لم يتم الاختبار';
        }
        
        $date = Carbon::parse($timestamp);
        $now = Carbon::now();
        
        if ($now->diffInHours($date) < 24) {
            return 'اليوم ' . $date->format('H:i');
        } elseif ($now->diffInDays($date) < 7) {
            return 'قبل ' . $now->diffInDays($date) . ' أيام';
        } else {
            return $date->translatedFormat('d F Y');
        }
    }

    /**
     * Get SMTP status.
     */
    private function getSmtpStatus()
    {
        $host = setting('mail_host');
        $username = setting('mail_username');
        $password = setting('mail_password');
        
        if ($host && $username && $password) {
            return 'active';
        }
        
        return 'inactive';
    }

    /**
     * Get cache status.
     */
    private function getCacheStatus()
    {
        return setting('cache_ttl', 0) > 0 ? 'enabled' : 'disabled';
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * AJAX: Get storage usage.
     */
    public function getStorageUsage()
    {
        $storageUsage = $this->calculateStorageUsage();
        $storageUsage['available_human'] = $this->formatBytes($storageUsage['available']);
        
        return response()->json($storageUsage);
    }

    /**
     * AJAX: Get quick stats.
     */
    public function getQuickStats()
    {
        return response()->json([
            'generalSettingsCount' => $this->countGeneralSettings(),
            'communicationSettingsCount' => $this->countCommunicationSettings(),
            'smtpSettingsCount' => $this->countSmtpSettings(),
            'storagePercentage' => $this->calculateStorageUsage()['percentage'],
        ]);
    }

    /**
     * AJAX: Get recent activities.
     */
    public function getRecentActivitiesAjax()
    {
        $activities = $this->getRecentActivities();
        return response()->json($activities);
    }

    /**
     * AJAX: Get system status.
     */
    public function getSystemStatus()
    {
        return response()->json([
            'smtp' => [
                'class' => $this->getSmtpStatus() == 'active' ? 'success' : 'warning',
                'text' => $this->getSmtpStatus() == 'active' ? 'نشط' : 'يتطلب إعداد'
            ],
            'storage' => [
                'class' => $this->calculateStorageUsage()['percentage'] < 80 ? 'success' : 'warning',
                'text' => $this->calculateStorageUsage()['percentage'] . '%'
            ],
            'cache' => [
                'class' => $this->getCacheStatus() == 'enabled' ? 'success' : 'warning',
                'text' => $this->getCacheStatus() == 'enabled' ? 'مفعل' : 'معطل'
            ],
            'maintenance' => [
                'class' => app()->isDownForMaintenance() ? 'warning' : 'success',
                'text' => app()->isDownForMaintenance() ? 'مفعل' : 'معطل'
            ]
        ]);
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'تم مسح التخزين المؤقت بنجاح.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء مسح الكاش: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:enable,disable'
        ]);
        
        try {
            if ($request->mode == 'enable') {
                Artisan::call('down', [
                    '--secret' => 'maintenance-bypass-' . rand(1000, 9999)
                ]);
                $message = 'تم تفعيل وضع الصيانة. الموقع الآن مغلق للزوار.';
            } else {
                Artisan::call('up');
                $message = 'تم تعطيل وضع الصيانة. الموقع الآن مفتوح للزوار.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير وضع الصيانة: ' . $e->getMessage()
            ], 500);
        }
    }
}