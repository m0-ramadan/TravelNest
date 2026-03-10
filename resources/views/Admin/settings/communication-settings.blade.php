@extends('Admin.layout.master')

@section('title', 'إعدادات التواصل')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .settings-card {
            background: var(--bs-card-bg);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #696cff;
        }

        .settings-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .settings-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-left: 15px;
        }

        .settings-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .settings-description {
            color: var(--bs-secondary-color);
            font-size: 14px;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .alert-guide {
            background: var(--bs-info-bg-subtle);
            border-right: 4px solid #696cff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-guide h6 {
            color: #696cff;
            margin-bottom: 10px;
        }

        .alert-guide ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert-guide li {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .toggle-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #696cff;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(30px);
        }

        .toggle-label {
            font-weight: 500;
            color: var(--bs-body-color);
        }

        .social-media-item {
            background: var(--bs-light-bg-subtle);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .social-media-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-left: 10px;
        }

        .social-name {
            font-weight: 600;
            flex: 1;
        }

        .social-toggle {
            margin-left: 10px;
        }

        .notification-item {
            background: var(--bs-light-bg-subtle);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .notification-title {
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        .notification-description {
            color: var(--bs-secondary-color);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .email-template {
            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: var(--bs-card-bg);
        }

        .email-template-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .email-template-title {
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        .email-template-preview {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            min-height: 200px;
            font-family: Arial, sans-serif;
        }

        @media (max-width: 768px) {
            .settings-header {
                flex-direction: column;
                text-align: center;
            }

            .settings-icon {
                margin-left: 0;
                margin-bottom: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.settings.index') }}">الإعدادات</a>
                </li>
                <li class="breadcrumb-item active">إعدادات التواصل</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">إعدادات التواصل</h5>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> رجوع للإعدادات
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Quick Guide -->
                        <div class="alert-guide">
                            <h6><i class="fas fa-lightbulb me-2"></i>معلومات مهمة:</h6>
                            <ul>
                                <li>إعدادات التواصل تشمل البريد الإلكتروني والرسائل والإشعارات</li>
                                <li>يمكنك تخصيص قوالب البريد الإلكتروني المرسلة للمستخدمين</li>
                                <li>يمكنك تفعيل أو تعطيل وسائل التواصل الاجتماعي</li>
                                <li>الأحقل ذات العلامة (*) إلزامية</li>
                            </ul>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.settings.communication.update') }}" method="POST" id="communicationSettingsForm">
                            @csrf
                            @method('PUT')

                            <!-- Email Settings -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">إعدادات البريد الإلكتروني</h5>
                                        <p class="settings-description">إعدادات إرسال واستقبال البريد الإلكتروني</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email_from_name" class="form-label required">اسم المرسل</label>
                                        <input type="text" class="form-control" id="email_from_name" name="email_from_name"
                                            value="{{ old('email_from_name', $settings['email_from_name'] ?? '') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email_from_address" class="form-label required">بريد المرسل</label>
                                        <input type="email" class="form-control" id="email_from_address" name="email_from_address"
                                            value="{{ old('email_from_address', $settings['email_from_address'] ?? '') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email_reply_to" class="form-label">بريد الرد</label>
                                        <input type="email" class="form-control" id="email_reply_to" name="email_reply_to"
                                            value="{{ old('email_reply_to', $settings['email_reply_to'] ?? '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email_bcc" class="form-label">نسخة مخفية</label>
                                        <input type="email" class="form-control" id="email_bcc" name="email_bcc"
                                            value="{{ old('email_bcc', $settings['email_bcc'] ?? '') }}"
                                            placeholder="يمكن إضافة أكثر من بريد مفصول بفاصلة">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="email_html" name="email_html"
                                                    {{ old('email_html', $settings['email_html'] ?? true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">إرسال البريد بصيغة HTML</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">وسائل التواصل الاجتماعي</h5>
                                        <p class="settings-description">روابط حسابات التواصل الاجتماعي</p>
                                    </div>
                                </div>

                                <div class="row">
                                    @php
                                        $social_media = [
                                            'facebook' => ['name' => 'فيسبوك', 'icon' => 'fab fa-facebook-f', 'color' => '#1877F2'],
                                            'twitter' => ['name' => 'تويتر', 'icon' => 'fab fa-twitter', 'color' => '#1DA1F2'],
                                            'instagram' => ['name' => 'إنستجرام', 'icon' => 'fab fa-instagram', 'color' => '#E4405F'],
                                            'linkedin' => ['name' => 'لينكد إن', 'icon' => 'fab fa-linkedin-in', 'color' => '#0A66C2'],
                                            'youtube' => ['name' => 'يوتيوب', 'icon' => 'fab fa-youtube', 'color' => '#FF0000'],
                                            'whatsapp' => ['name' => 'واتساب', 'icon' => 'fab fa-whatsapp', 'color' => '#25D366'],
                                            'telegram' => ['name' => 'تيليجرام', 'icon' => 'fab fa-telegram', 'color' => '#0088CC'],
                                            'tiktok' => ['name' => 'تيك توك', 'icon' => 'fab fa-tiktok', 'color' => '#000000'],
                                        ];
                                    @endphp

                                    @foreach($social_media as $key => $platform)
                                        <div class="col-md-6 mb-3">
                                            <div class="social-media-item">
                                                <div class="social-media-header">
                                                    <div class="social-icon" style="background-color: {{ $platform['color'] }}">
                                                        <i class="{{ $platform['icon'] }}"></i>
                                                    </div>
                                                    <div class="social-name">{{ $platform['name'] }}</div>
                                                    <div class="social-toggle toggle-container">
                                                        <label class="toggle-switch">
                                                            <input type="checkbox" id="{{ $key }}_enabled" 
                                                                name="social_media[{{ $key }}][enabled]"
                                                                {{ old("social_media.{$key}.enabled", $settings["social_{$key}_enabled"] ?? false) ? 'checked' : '' }}>
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <input type="url" class="form-control" 
                                                    id="{{ $key }}_url" 
                                                    name="social_media[{{ $key }}][url]"
                                                    value="{{ old("social_media.{$key}.url", $settings["social_{$key}_url"] ?? '') }}"
                                                    placeholder="رابط {{ $platform['name'] }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Notifications -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">الإشعارات</h5>
                                        <p class="settings-description">إعدادات إرسال الإشعارات للمستخدمين</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="notification-item">
                                            <div class="notification-header">
                                                <div class="notification-title">إشعارات البريد الإلكتروني</div>
                                                <div class="toggle-container">
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" id="email_notifications" name="email_notifications"
                                                            {{ old('email_notifications', $settings['email_notifications'] ?? true) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="notification-description">إرسال إشعارات عبر البريد الإلكتروني للأحداث المهمة</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="notification-item">
                                            <div class="notification-header">
                                                <div class="notification-title">إشعارات نظامية</div>
                                                <div class="toggle-container">
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" id="system_notifications" name="system_notifications"
                                                            {{ old('system_notifications', $settings['system_notifications'] ?? true) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="notification-description">عرض إشعارات نظامية داخل لوحة التحكم</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="notification-item">
                                            <div class="notification-header">
                                                <div class="notification-title">إشعارات المستخدمين الجدد</div>
                                                <div class="toggle-container">
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" id="new_user_notification" name="new_user_notification"
                                                            {{ old('new_user_notification', $settings['new_user_notification'] ?? true) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="notification-description">إرسال إشعار عند تسجيل مستخدم جديد</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="notification-item">
                                            <div class="notification-header">
                                                <div class="notification-title">إشعارات الطلبات الجديدة</div>
                                                <div class="toggle-container">
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" id="new_order_notification" name="new_order_notification"
                                                            {{ old('new_order_notification', $settings['new_order_notification'] ?? true) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="notification-description">إرسال إشعار عند إنشاء طلب جديد</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="admin_notification_email" class="form-label">بريد إشعارات المدير</label>
                                        <input type="email" class="form-control" id="admin_notification_email" name="admin_notification_email"
                                            value="{{ old('admin_notification_email', $settings['admin_notification_email'] ?? '') }}"
                                            placeholder="بريد إلكتروني لإرسال الإشعارات الإدارية">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Form Settings -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">إعدادات نموذج التواصل</h5>
                                        <p class="settings-description">إعدادات نموذج التواصل في الموقع</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="contact_form_enabled" name="contact_form_enabled"
                                                    {{ old('contact_form_enabled', $settings['contact_form_enabled'] ?? true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">تفعيل نموذج التواصل</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_form_email" class="form-label">بريد استقبال الرسائل</label>
                                        <input type="email" class="form-control" id="contact_form_email" name="contact_form_email"
                                            value="{{ old('contact_form_email', $settings['contact_form_email'] ?? '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="contact_form_cc_sender" name="contact_form_cc_sender"
                                                    {{ old('contact_form_cc_sender', $settings['contact_form_cc_sender'] ?? true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">إرسال نسخة للمرسل</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_form_thankyou_message" class="form-label">رسالة الشكر</label>
                                        <textarea class="form-control" id="contact_form_thankyou_message" name="contact_form_thankyou_message" 
                                            rows="2">{{ old('contact_form_thankyou_message', $settings['contact_form_thankyou_message'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> إعادة تعيين
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> حفظ الإعدادات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Form submission
            $('#communicationSettingsForm').on('submit', function(e) {
                // Show loading
                Swal.fire({
                    title: 'جاري حفظ الإعدادات...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });

            // Toggle social media fields
            $('input[name^="social_media"]').on('change', function() {
                const inputName = $(this).attr('name');
                const platform = inputName.match(/\[(.*?)\]/)[1];
                const enabled = $(this).is(':checked');
                
                const urlInput = $(`#${platform}_url`);
                urlInput.prop('disabled', !enabled);
                
                if (!enabled) {
                    urlInput.val('');
                }
            });

            // Initialize social media fields state
            $('input[name^="social_media"]').each(function() {
                const inputName = $(this).attr('name');
                const platform = inputName.match(/\[(.*?)\]/)[1];
                const enabled = $(this).is(':checked');
                
                const urlInput = $(`#${platform}_url`);
                urlInput.prop('disabled', !enabled);
            });
        });
    </script>
@endsection