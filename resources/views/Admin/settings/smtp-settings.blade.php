@extends('Admin.layout.master')

@section('title', 'إعدادات البريد (SMTP)')

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

        .test-email-section {
            background: var(--bs-light-bg-subtle);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            border: 2px dashed var(--bs-border-color);
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

        .config-status {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .config-status.success {
            background: var(--bs-success-bg-subtle);
            border: 1px solid var(--bs-success-border-subtle);
            color: var(--bs-success-text);
        }

        .config-status.warning {
            background: var(--bs-warning-bg-subtle);
            border: 1px solid var(--bs-warning-border-subtle);
            color: var(--bs-warning-text);
        }

        .config-status.error {
            background: var(--bs-danger-bg-subtle);
            border: 1px solid var(--bs-danger-border-subtle);
            color: var(--bs-danger-text);
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

        .port-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
        }

        .port-465 {
            background: #198754;
            color: white;
        }

        .port-587 {
            background: #0d6efd;
            color: white;
        }

        .port-25 {
            background: #6f42c1;
            color: white;
        }

        .encryption-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
        }

        .ssl {
            background: #198754;
            color: white;
        }

        .tls {
            background: #0d6efd;
            color: white;
        }

        .none {
            background: #6c757d;
            color: white;
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
                <li class="breadcrumb-item active">إعدادات البريد (SMTP)</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">إعدادات البريد (SMTP)</h5>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> رجوع للإعدادات
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Configuration Status -->
                        @if(session('test_status'))
                            <div class="config-status {{ session('test_status') == 'success' ? 'success' : 'error' }}">
                                <i class="fas fa-{{ session('test_status') == 'success' ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
                                {{ session('test_message') }}
                            </div>
                        @endif

                        <!-- Quick Guide -->
                        <div class="alert-guide">
                            <h6><i class="fas fa-lightbulb me-2"></i>معلومات مهمة:</h6>
                            <ul>
                                <li>يستخدم SMTP لإرسال البريد الإلكتروني من تطبيقك</li>
                                <li>تأكد من صحة بيانات الاعتماد الخاصة بخادم البريد</li>
                                <li>يمكنك اختبار الإعدادات عن طريق إرسال بريد تجريبي</li>
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

                        <form action="{{ route('admin.settings.smtp.update') }}" method="POST" id="smtpSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">إعدادات الخادم</h5>
                                        <p class="settings-description">إعدادات خادم البريد الأساسية</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="mail_host" class="form-label required">خادم البريد (Host)</label>
                                        <input type="text" class="form-control" id="mail_host" name="mail_host"
                                            value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" required
                                            placeholder="مثال: smtp.gmail.com">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_port" class="form-label required">المنفذ (Port)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="mail_port" name="mail_port"
                                                value="{{ old('mail_port', $settings['mail_port'] ?? '') }}" required
                                                placeholder="مثال: 587">
                                            <span class="input-group-text">
                                                <span class="port-badge port-587" title="منفذ TLS">587</span>
                                                <span class="port-badge port-465" title="منفذ SSL">465</span>
                                                <span class="port-badge port-25" title="منفذ غير مشفر">25</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_username" class="form-label required">اسم المستخدم</label>
                                        <input type="text" class="form-control" id="mail_username" name="mail_username"
                                            value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" required
                                            placeholder="البريد الإلكتروني">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_password" class="form-label required">كلمة المرور</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="mail_password" name="mail_password"
                                                value="{{ old('mail_password', $settings['mail_password'] ?? '') }}"
                                                placeholder="أدخل كلمة المرور">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">اتركه فارغاً إذا كنت لا تريد تغيير كلمة المرور</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_encryption" class="form-label required">التشفير</label>
                                        <select class="form-select" id="mail_encryption" name="mail_encryption" required>
                                            <option value="">اختر نوع التشفير</option>
                                            <option value="ssl" {{ (old('mail_encryption', $settings['mail_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>
                                                SSL
                                            </option>
                                            <option value="tls" {{ (old('mail_encryption', $settings['mail_encryption'] ?? '') == 'tls') ? 'selected' : '' }}>
                                                TLS
                                            </option>
                                            <option value="" {{ (old('mail_encryption', $settings['mail_encryption'] ?? '') == '') ? 'selected' : '' }}>
                                                بدون تشفير
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_from_address" class="form-label required">عنوان المرسل</label>
                                        <input type="email" class="form-control" id="mail_from_address" name="mail_from_address"
                                            value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" required
                                            placeholder="مثال: noreply@example.com">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_from_name" class="form-label required">اسم المرسل</label>
                                        <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                            value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" required
                                            placeholder="مثال: اسم المتجر">
                                    </div>
                                </div>
                            </div>

                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">إعدادات متقدمة</h5>
                                        <p class="settings-description">إعدادات إضافية لخادم البريد</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="mail_auth" name="mail_auth"
                                                    {{ old('mail_auth', $settings['mail_auth'] ?? true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">المصادقة المطلوبة</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="mail_verify_peer" name="mail_verify_peer"
                                                    {{ old('mail_verify_peer', $settings['mail_verify_peer'] ?? true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">التحقق من الشهادة</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_timeout" class="form-label">مهلة الاتصال (ثانية)</label>
                                        <input type="number" class="form-control" id="mail_timeout" name="mail_timeout"
                                            value="{{ old('mail_timeout', $settings['mail_timeout'] ?? 30) }}" min="10" max="120">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mail_max_attempts" class="form-label">أقصى محاولات الإرسال</label>
                                        <input type="number" class="form-control" id="mail_max_attempts" name="mail_max_attempts"
                                            value="{{ old('mail_max_attempts', $settings['mail_max_attempts'] ?? 3) }}" min="1" max="10">
                                    </div>
                                </div>
                            </div>

                            <!-- Test Email Section -->
                            <div class="test-email-section">
                                <h6 class="mb-3"><i class="fas fa-vial me-2"></i>اختبار إعدادات البريد</h6>
                                <p class="text-muted mb-3">أدخل بريداً إلكترونياً لاختبار إعدادات SMTP بعد الحفظ</p>
                                
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="test_email" class="form-label">البريد الإلكتروني للاختبار</label>
                                        <input type="email" class="form-control" id="test_email" name="test_email"
                                            placeholder="أدخل بريداً إلكترونياً للاختبار">
                                    </div>
                                    <div class="col-md-4 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-primary w-100" onclick="testSMTPSettings()">
                                            <i class="fas fa-paper-plane me-2"></i>إرسال بريد تجريبي
                                        </button>
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
            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#mail_password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // Form submission
            $('#smtpSettingsForm').on('submit', function(e) {
                // Show loading
                Swal.fire({
                    title: 'جاري حفظ الإعدادات...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });

        function testSMTPSettings() {
            const testEmail = $('#test_email').val();
            
            if (!testEmail) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال بريد إلكتروني للاختبار',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            if (!validateEmail(testEmail)) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال بريد إلكتروني صحيح',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            Swal.fire({
                title: 'جاري إرسال البريد التجريبي...',
                text: 'قد يستغرق هذا بضع ثوانٍ',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Collect form data
            const formData = new FormData($('#smtpSettingsForm')[0]);
            formData.append('test_email', testEmail);

            $.ajax({
                url: '{{ route('admin.settings.smtp.test') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الإرسال!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'فشل الإرسال',
                            text: response.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء الإرسال',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
@endsection