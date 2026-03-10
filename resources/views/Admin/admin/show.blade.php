@extends('Admin.layout.master')

@section('title', 'إضافة مشرف جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .create-admin {
            padding: 20px 0;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .page-header-content {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .page-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .page-title h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-title p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 14px;
        }

        /* Form Cards */
        .form-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: 1px solid var(--bs-border-color);
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .card-title {
            flex: 1;
        }

        .card-title h5 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--bs-heading-color);
        }

        .card-title p {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: var(--bs-heading-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-label i {
            color: #667eea;
        }

        .required::after {
            content: '*';
            color: #dc3545;
            margin-right: 5px;
            font-weight: bold;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--bs-border-color);
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(rgb(39 9 9 / 15%));
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background: var(--bs-card-bg);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }

        .invalid-feedback {
            font-size: 12px;
            margin-top: 5px;
            color: #dc3545;
        }

        /* Avatar Upload */
        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 700;
            position: relative;
            overflow: hidden;
            border: 4px solid var(--bs-border-color);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-remove {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .avatar-preview:hover .avatar-remove {
            opacity: 1;
        }

        .avatar-remove:hover {
            background: #dc3545;
            transform: scale(1.1);
        }

        .avatar-controls {
            flex: 1;
        }

        .avatar-info {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-info i {
            color: #667eea;
        }

        /* Role Cards */
        .role-card {
            background: var(rgb(39 9 9 / 15%));
            border-radius: 12px;
            padding: 15px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .role-card:hover {
            background: var(--bs-card-bg);
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .role-card.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .role-card.selected .role-name,
        .role-card.selected .role-description,
        .role-card.selected .role-permissions {
            color: white;
        }

        .role-radio {
            display: none;
        }

        .role-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .role-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--bs-heading-color);
        }

        .role-description {
            font-size: 12px;
            color: var(--bs-secondary-color);
            margin-bottom: 10px;
        }

        .role-permissions {
            font-size: 11px;
            color: var(--bs-secondary-color);
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .role-permissions span {
            background: rgba(102, 126, 234, 0.1);
            padding: 3px 8px;
            border-radius: 15px;
        }

        .role-card.selected .role-permissions span {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Permissions Grid */
        .permissions-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--bs-border-color);
        }

        .permissions-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .permissions-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .permissions-title i {
            color: #667eea;
            font-size: 20px;
        }

        .permissions-title h6 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 0;
            color: var(--bs-heading-color);
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .permission-item {
            background: var(rgb(39 9 9 / 15%));
            border-radius: 10px;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .permission-item:hover {
            background: var(--bs-card-bg);
            border-color: #667eea;
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .permission-check {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .permission-info {
            flex: 1;
        }

        .permission-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 3px;
        }

        .permission-module {
            font-size: 11px;
            color: var(--bs-secondary-color);
        }

        /* Password Strength */
        .password-strength {
            margin-top: 10px;
        }

        .strength-meter {
            height: 5px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-meter-fill {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
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
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .toggle-label {
            margin-right: 10px;
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid var(--bs-border-color);
        }

        .btn {
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
        }

        .btn-secondary {
            background: var(--bs-secondary-color);
            color: white;
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .spinner-container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #696cff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header-content {
                flex-direction: column;
                text-align: center;
            }

            .avatar-upload {
                flex-direction: column;
                text-align: center;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }

            .permissions-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Success Animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4caf50;
        }

        .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }

        .check-icon::before,
        .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: white;
            transform: rotate(-45deg);
        }

        .icon-check {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 20px;
            border: 4px solid #4caf50;
            border-top: none;
            border-right: none;
            transform-origin: left top;
            animation: check-icon 0.8s;
        }

        @keyframes check-icon {
            0% {
                width: 0;
                height: 0;
                opacity: 0;
            }
            100% {
                width: 40px;
                height: 20px;
                opacity: 1;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">
                        <i class="fas fa-home ms-1"></i>
                        الرئيسية
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.admins.index') }}">
                        <i class="fas fa-user-shield ms-1"></i>
                        إدارة المشرفين
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <i class="fas fa-plus-circle ms-1"></i>
                    إضافة مشرف جديد
                </li>
            </ol>
        </nav>

        <div class="create-admin">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-content">
                    <div class="page-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="page-title">
                        <h3>إضافة مشرف جديد</h3>
                        <p>
                            <i class="fas fa-info-circle ms-1"></i>
                            قم بإدخال بيانات المشرف الجديد وتحديد صلاحياته
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.admins.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  id="createAdminForm">
                @csrf

                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-lg-8">
                        <div class="form-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="card-title">
                                    <h5>المعلومات الشخصية</h5>
                                    <p>أدخل البيانات الأساسية للمشرف الجديد</p>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fas fa-user"></i>
                                        الاسم الكامل
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="أدخل الاسم الكامل"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fas fa-envelope"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="example@domain.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i>
                                        رقم الهاتف
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="05xxxxxxxx"
                                           dir="ltr">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="fas fa-flag"></i>
                                        حالة الحساب
                                    </label>
                                    <div class="d-flex align-items-center h-100">
                                        <div class="d-flex align-items-center">
                                            <span class="toggle-label">نشط</span>
                                            <label class="toggle-switch me-2">
                                                <input type="checkbox" 
                                                       name="is_active" 
                                                       value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                        <small class="text-muted me-3">
                                            <i class="fas fa-info-circle"></i>
                                            تفعيل الحساب يسمح للمشرف بتسجيل الدخول
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="card-title">
                                    <h5>كلمة المرور</h5>
                                    <p>تعيين كلمة مرور قوية للمشرف</p>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Password -->
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fas fa-lock"></i>
                                        كلمة المرور
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           id="password"
                                           placeholder="أدخل كلمة المرور"
                                           minlength="8"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="password-strength" id="passwordStrength" style="display: none;">
                                        <div class="strength-meter">
                                            <div class="strength-meter-fill" id="strengthMeterFill"></div>
                                        </div>
                                        <span class="strength-text" id="strengthText"></span>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fas fa-lock"></i>
                                        تأكيد كلمة المرور
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           placeholder="أعد إدخال كلمة المرور"
                                           required>
                                    <div class="invalid-feedback" id="passwordMatchError" style="display: none;">
                                        كلمة المرور غير متطابقة
                                    </div>
                                </div>

                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        كلمة المرور يجب أن تكون 8 أحرف على الأقل وتحتوي على أحرف كبيرة وصغيرة وأرقام
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Avatar & Role -->
                    <div class="col-lg-4">
                        <!-- Avatar Upload -->
                        <div class="form-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="card-title">
                                    <h5>الصورة الشخصية</h5>
                                    <p>اختياري - رفع صورة المشرف</p>
                                </div>
                            </div>

                            <div class="avatar-upload">
                                <div class="avatar-preview" id="avatarPreview">
                                    <i class="fas fa-user"></i>
                                </div>
                                
                                <div class="avatar-controls">
                                    <input type="file" 
                                           class="form-control @error('avatar') is-invalid @enderror" 
                                           name="avatar" 
                                           id="avatarInput"
                                           accept="image/jpeg,image/png,image/jpg,image/gif">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="avatar-info">
                                        <i class="fas fa-info-circle"></i>
                                        الصيغ المسموحة: JPG, PNG, GIF (الحد الأقصى 2MB)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="form-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div class="card-title">
                                    <h5>الدور الوظيفي</h5>
                                    <p>تحديد صلاحيات المشرف - مطلوب</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                @foreach($roles as $role)
                                    <div class="col-12">
                                        <label class="role-card @if($loop->first) selected @endif">
                                            <input type="radio" 
                                                   name="role" 
                                                   value="{{ $role->id }}" 
                                                   class="role-radio"
                                                   {{ $loop->first ? 'checked' : '' }}
                                                   required>
                                            <div class="role-icon">
                                                <i class="fas 
                                                    @if($role->name == 'super_admin') fa-crown
                                                    @elseif($role->name == 'admin') fa-user-tie
                                                    @else fa-user-shield
                                                    @endif">
                                                </i>
                                            </div>
                                            <div class="role-name">{{ $role->display_name ?? $role->name }}</div>
                                            <div class="role-description">{{ $role->description ?? 'لا يوجد وصف' }}</div>
                                            <div class="role-permissions">
                                                @php
                                                    $permissions = $role->permissions()->limit(5)->get();
                                                @endphp
                                                @foreach($permissions as $permission)
                                                    <span>{{ $permission->display_name ?? $permission->name }}</span>
                                                @endforeach
                                                @if($role->permissions()->count() > 5)
                                                    <span>+{{ $role->permissions()->count() - 5 }}</span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('role')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Direct Permissions -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="card-title">
                            <h5>الصلاحيات الإضافية</h5>
                            <p>اختياري - صلاحيات إضافية للمشرف</p>
                        </div>
                    </div>

                    @php
                        $groupedPermissions = $permissions->groupBy('module');
                    @endphp

                    @if($groupedPermissions->count() > 0)
                        <div class="permissions-section">
                            <div class="permissions-header">
                                <div class="permissions-title">
                                    <i class="fas fa-check-circle"></i>
                                    <h6>حدد الصلاحيات الإضافية</h6>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                    <label class="form-check-label" for="selectAllPermissions">
                                        تحديد الكل
                                    </label>
                                </div>
                            </div>

                            @foreach($groupedPermissions as $module => $modulePermissions)
                                <div class="mb-4">
                                    <h6 class="mb-3" style="color: #667eea; font-weight: 700;">
                                        <i class="fas fa-folder ms-2"></i>
                                        {{ $module }}
                                    </h6>
                                    <div class="permissions-grid">
                                        @foreach($modulePermissions as $permission)
                                            <div class="permission-item">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       class="permission-check"
                                                       id="perm_{{ $permission->id }}"
                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <div class="permission-info">
                                                    <div class="permission-name">{{ $permission->display_name ?? $permission->name }}</div>
                                                    <div class="permission-module">{{ $permission->description ?? 'لا يوجد وصف' }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لا توجد صلاحيات إضافية متاحة</p>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        إلغاء والعودة
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i>
                        حفظ البيانات
                    </button>
                    <button type="reset" class="btn btn-success" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-container">
            <div class="spinner"></div>
            <h6 class="mt-3">جاري حفظ البيانات...</h6>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Role Card Selection
            $('.role-card').click(function() {
                $('.role-card').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('.role-radio').prop('checked', true);
            });

            // Avatar Preview
            $('#avatarInput').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                        $(this).val('');
                        return;
                    }

                    // Check file type
                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'الرجاء اختيار ملف صورة صحيح',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                        $(this).val('');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = $('#avatarPreview');
                        preview.empty();
                        preview.append(`<img src="${e.target.result}" alt="Avatar Preview">`);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Password Strength Checker
            $('#password').on('keyup', function() {
                const password = $(this).val();
                if (password.length > 0) {
                    $('#passwordStrength').show();
                    checkPasswordStrength(password);
                } else {
                    $('#passwordStrength').hide();
                }
                checkPasswordMatch();
            });

            $('#password_confirmation').on('keyup', function() {
                checkPasswordMatch();
            });

            function checkPasswordStrength(password) {
                let strength = 0;
                let tips = [];
                
                // Length check
                if (password.length >= 8) {
                    strength += 25;
                } else {
                    tips.push('يجب أن تكون 8 أحرف على الأقل');
                }
                
                // Contains lowercase
                if (password.match(/[a-z]+/)) {
                    strength += 25;
                } else {
                    tips.push('يجب أن تحتوي على أحرف صغيرة');
                }
                
                // Contains uppercase
                if (password.match(/[A-Z]+/)) {
                    strength += 25;
                } else {
                    tips.push('يجب أن تحتوي على أحرف كبيرة');
                }
                
                // Contains numbers
                if (password.match(/[0-9]+/)) {
                    strength += 25;
                } else {
                    tips.push('يجب أن تحتوي على أرقام');
                }
                
                // Update meter
                $('#strengthMeterFill').css('width', strength + '%');
                
                // Color and text based on strength
                if (strength < 50) {
                    $('#strengthMeterFill').css('background', '#dc3545');
                    $('#strengthText').text('ضعيفة - ' + tips.join(', ')).css('color', '#dc3545');
                } else if (strength < 75) {
                    $('#strengthMeterFill').css('background', '#ffc107');
                    $('#strengthText').text('متوسطة').css('color', '#ffc107');
                } else {
                    $('#strengthMeterFill').css('background', '#28a745');
                    $('#strengthText').text('قوية').css('color', '#28a745');
                }
            }

            function checkPasswordMatch() {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                
                if (confirmPassword.length > 0) {
                    if (password !== confirmPassword) {
                        $('#password_confirmation').addClass('is-invalid');
                        $('#passwordMatchError').show();
                        return false;
                    } else {
                        $('#password_confirmation').removeClass('is-invalid');
                        $('#passwordMatchError').hide();
                        return true;
                    }
                }
                return true;
            }

            // Select All Permissions
            $('#selectAllPermissions').change(function() {
                $('.permission-check').prop('checked', $(this).prop('checked'));
            });

            // Update select all based on individual checkboxes
            $('.permission-check').change(function() {
                const total = $('.permission-check').length;
                const checked = $('.permission-check:checked').length;
                $('#selectAllPermissions').prop('checked', total === checked && total > 0);
            });

            // Form Submission
            $('#createAdminForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate required fields
                const name = $('input[name="name"]').val().trim();
                const email = $('input[name="email"]').val().trim();
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                const role = $('input[name="role"]:checked').val();
                
                if (!name) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'الرجاء إدخال الاسم الكامل',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }
                
                if (!email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'الرجاء إدخال البريد الإلكتروني',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }
                
                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'الرجاء إدخال بريد إلكتروني صحيح',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }
                
                if (!password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'الرجاء إدخال كلمة المرور',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }
                
                if (password.length < 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }
                
                if (password !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'كلمة المرور غير متطابقة',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }

                if (!role) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'الرجاء اختيار دور للمشرف',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }

                // Confirm before submit
                Swal.fire({
                    title: 'تأكيد الحفظ',
                    text: 'هل أنت متأكد من صحة البيانات؟',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، حفظ',
                    cancelButtonText: 'مراجعة',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show spinner
                        showSpinner();
                        
                        // Disable submit button
                        $('#submitBtn').prop('disabled', true);
                        
                        // Submit form
                        this.submit();
                    }
                });
            });

            // Email availability check
            let emailCheckTimeout;
            $('input[name="email"]').on('keyup', function() {
                clearTimeout(emailCheckTimeout);
                const email = $(this).val();
                
                if (email.length > 5 && email.includes('@')) {
                    emailCheckTimeout = setTimeout(function() {
                        $.ajax({
                            url: '{{ route("admin.admins.check-email") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                email: email
                            },
                            success: function(response) {
                                if (!response.available) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'تنبيه',
                                        text: 'البريد الإلكتروني مستخدم بالفعل',
                                        confirmButtonText: 'حسناً',
                                        confirmButtonColor: '#696cff'
                                    });
                                }
                            }
                        });
                    }, 1000);
                }
            });

            // Prevent double submission
            $('#submitBtn').on('click', function() {
                $(this).prop('disabled', true);
            });
        });

        // Show Spinner
        function showSpinner() {
            $('#spinnerOverlay').fadeIn();
        }

        // Hide Spinner
        function hideSpinner() {
            $('#spinnerOverlay').fadeOut();
        }

        // Reset Form
        function resetForm() {
            Swal.fire({
                title: 'تأكيد إعادة التعيين',
                text: 'هل أنت متأكد من إعادة تعيين جميع الحقول؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fd7e14',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، إعادة تعيين',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#createAdminForm')[0].reset();
                    $('#avatarPreview').html('<i class="fas fa-user"></i>');
                    $('.role-card').removeClass('selected');
                    $('.role-card:first').addClass('selected');
                    $('.role-radio:first').prop('checked', true);
                    $('#passwordStrength').hide();
                    $('#passwordMatchError').hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'تم!',
                        text: 'تم إعادة تعيين الحقول',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Preview Image before upload
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').html(`<img src="${e.target.result}" alt="Preview">`);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Generate random password
        function generatePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";
            
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }
            
            $('#password').val(password);
            $('#password_confirmation').val(password);
            $('#password').trigger('keyup');
            $('#password_confirmation').trigger('keyup');
            
            Swal.fire({
                icon: 'success',
                title: 'تم!',
                text: 'تم إنشاء كلمة مرور عشوائية',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Confirm before leave if form is dirty
        let formChanged = false;
        
        $('#createAdminForm input, #createAdminForm select').change(function() {
            formChanged = true;
        });

        $(window).on('beforeunload', function() {
            if (formChanged) {
                return 'لديك تغييرات غير محفوظة. هل تريد المغادرة؟';
            }
        });

        $('#createAdminForm').on('submit', function() {
            formChanged = false;
        });
    </script>
@endsection