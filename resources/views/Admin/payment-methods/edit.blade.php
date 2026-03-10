@extends('Admin.layout.master')

@section('title', 'تعديل وسيلة الدفع: ' . $paymentMethod->name)

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* --primary-color: #FF6B35; */
            /* --primary-hover: #E55A2B; */
            --secondary-color: #426788;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-color: #dee2e6;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
        }

        body {
            font-family: "Cairo", sans-serif;
            /* background-color: #f5f7fb; */
        }

        .form-card {
            /* background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%); */
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            padding: 30px;
            border: 1px solid rgba(255, 107, 53, 0.1);
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }

        .form-header {
            border-bottom: 1px solid rgba(255, 107, 53, 0.2);
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .form-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: 0;
            width: 100px;
            height: 2px;
            background: linear-gradient(to left, var(--primary-color), transparent);
        }

        .info-card {
            background: linear-gradient(135deg, rgba(66, 103, 136, 0.1) 0%, rgba(255, 107, 53, 0.05) 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border-right: 4px solid var(--primary-color);
            border: 1px solid rgba(66, 103, 136, 0.1);
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(66, 103, 136, 0.1);
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            min-width: 140px;
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 14px;
        }

        .info-value {
            /* color: var(--text-dark); */
            font-weight: 500;
            font-size: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(255, 107, 53, 0.3);
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 20px;
        }

        .image-upload-container {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            /* background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); */
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .image-upload-container:hover {
            border-color: var(--primary-color);
            /* background: linear-gradient(135deg, #fff8f6 0%, #fff0ed 100%); */
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.15);
        }

        .image-upload-container.active {
            border-color: var(--primary-color);
            /* background: linear-gradient(135deg, #fff0ed 0%, #ffe8e0 100%); */
        }

        .image-preview {
            max-width: 120px;
            max-height: 120px;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 8px;
            /* background: white; */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .upload-icon {
            font-size: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .image-upload-container:hover .upload-icon {
            transform: scale(1.1);
        }

        .preview-card {
            /* background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%); */
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(66, 103, 136, 0.2);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 20px;
        }

        .preview-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            /* background: linear-gradient(to left, var(--primary-color), var(--secondary-color)); */
            border-radius: 12px 12px 0 0;
        }

        .preview-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            /* background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 2px solid rgba(255, 107, 53, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .preview-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .preview-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 12px;
            text-align: center;
            line-height: 1.4;
        }

        .preview-key {
            /* background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); */
            padding: 8px 16px;
            border-radius: 25px;
            font-family: 'Cairo', monospace;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid rgba(66, 103, 136, 0.1);
            color: var(--secondary-color);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-inactive {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .type-badge {
            background: linear-gradient(135deg, #e7f5ff 0%, #d0ebff 100%);
            color: #0c63e4;
            border: 1px solid #d0ebff;
        }

        .form-control {
            border: 1px solid rgba(66, 103, 136, 0.2);
            border-radius: 10px;
            padding: 12px 15px;
            font-family: "Cairo", sans-serif;
            transition: all 0.3s;
            /* background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%); */
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.25);
            background: linear-gradient(135deg, #fff8f6 0%, #ffffff 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-family: "Cairo", sans-serif;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-family: "Cairo", sans-serif;
            transition: all 0.3s;
        }

        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(66, 103, 136, 0.3);
        }

        .btn-outline-danger {
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-family: "Cairo", sans-serif;
            transition: all 0.3s;
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-outline-info {
            border: 2px solid var(--info-color);
            color: var(--info-color);
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-family: "Cairo", sans-serif;
            transition: all 0.3s;
        }

        .btn-outline-info:hover {
            background: var(--info-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.25);
        }

        .required::after {
            content: " *";
            color: var(--danger-color);
            font-weight: bold;
        }

        .alert-guide {
            background: linear-gradient(135deg, #e7f7ff 0%, #d0ebff 100%);
            border-right: 4px solid var(--primary-color);
            border-radius: 12px;
            margin-bottom: 25px;
            padding: 20px;
            border-left: none;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            border-radius: 12px;
            border-right: 4px solid var(--success-color);
        }

        .alert-danger {
            /* background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); */
            border: 1px solid #f5c6cb;
            border-radius: 12px;
            border-right: 4px solid var(--danger-color);
        }

        .breadcrumb {
            /* background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); */
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 107, 53, 0.1);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
            transform: translateX(-3px);
        }

        .input-group {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group .form-control {
            border: 1px solid rgba(66, 103, 136, 0.2);
            border-right: none;
        }

        .input-group .btn {
            background: linear-gradient(135deg, var(--secondary-color), #3a5d7a);
            border: 1px solid rgba(66, 103, 136, 0.2);
            border-left: none;
            /* color: white; */
            transition: all 0.3s;
        }

        .input-group .btn:hover {
            background: linear-gradient(135deg, #3a5d7a, var(--secondary-color));
            transform: none;
        }

        .help-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 5px;
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }

            .preview-card {
                margin-top: 30px;
                position: static;
            }

            .section-title {
                font-size: 16px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .info-label {
                min-width: auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payment-methods.index') }}">وسائل الدفع</a></li>
                <li class="breadcrumb-item active">تعديل: {{ $paymentMethod->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="form-card">
                    <div class="form-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1" style="color: var(--secondary-color);">
                                    <i class="fas fa-edit me-2" style="color: var(--primary-color);"></i>
                                    تعديل وسيلة الدفع
                                </h5>
                                <p class="text-muted mb-0">ID: #{{ $paymentMethod->id }}</p>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.payment-methods.show', $paymentMethod) }}"
                                    class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i>عرض
                                </a>
                                <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>رجوع
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات وسيلة الدفع -->
                    <div class="info-card">
                        <h6 class="mb-3" style="color: var(--secondary-color);">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات وسيلة الدفع
                        </h6>
                        <div class="info-row">
                            <div class="info-label">تاريخ الإضافة:</div>
                            <div class="info-value">
                                {{ $paymentMethod->created_at->translatedFormat('d M Y - h:i A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">آخر تحديث:</div>
                            <div class="info-value">
                                {{ $paymentMethod->updated_at->translatedFormat('d M Y - h:i A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">الحالة الحالية:</div>
                            <div class="info-value">
                                @if ($paymentMethod->is_active)
                                    <span class="status-badge status-active">
                                        <i class="fas fa-check-circle"></i> نشط
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-times-circle"></i> غير نشط
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="alert-guide">
                        <h6 style="color: var(--secondary-color);">
                            <i class="fas fa-lightbulb me-2"></i>
                            نصائح للتعديل:
                        </h6>
                        <ul class="mb-0">
                            <li>يمكنك تعديل أي معلومات عن وسيلة الدفع</li>
                            <li>المعرف (Key) يجب أن يكون فريداً ولا يتكرر</li>
                            <li>تغيير الحالة سيؤثر على ظهور وسيلة الدفع للعملاء</li>
                            <li>احفظ التغييرات بعد الانتهاء</li>
                        </ul>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST"
                        enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-8">
                                <!-- معلومات أساسية -->
                                <h6 class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    المعلومات الأساسية
                                </h6>

                                <div class="mb-4">
                                    <label for="name" class="form-label required">اسم وسيلة الدفع</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $paymentMethod->name) }}" required>
                                    <div class="help-text">الاسم الذي سيظهر للعملاء</div>
                                </div>

                                <div class="mb-4">
                                    <label for="key" class="form-label required">المعرف (Key)</label>
                                    <input type="text" class="form-control" id="key" name="key"
                                        value="{{ old('key', $paymentMethod->key) }}" required>
                                    <div class="help-text">معرف فريد يستخدم في النظام</div>
                                </div>

                                <!-- رفع الصورة -->
                                <h6 class="section-title mt-4">
                                    <i class="fas fa-image"></i>
                                    صورة الوسيلة
                                </h6>

                                <div class="mb-4">
                                    <label class="form-label">أيقونة / صورة الوسيلة</label>
                                    <div class="image-upload-container" id="uploadArea">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <p class="mb-2" style="color: var(--secondary-color); font-weight: 600;">
                                            انقر لرفع صورة
                                        </p>
                                        <small class="text-muted">الصيغ المدعومة: JPG, PNG, SVG</small>

                                        @if ($paymentMethod->icon)
                                            <img id="imagePreview" class="image-preview mt-3"
                                                src="{{ Storage::url($paymentMethod->icon) }}" alt="معاينة الصورة"
                                                style="display: block;">
                                        @else
                                            <img id="imagePreview" class="image-preview mt-3" alt="معاينة الصورة">
                                        @endif

                                        <input type="file" id="icon" name="icon" accept="image/*"
                                            class="d-none" onchange="handlePreviewImage(event)">

                                    </div>
                                    <div class="help-text">الحجم الموصى به: 100×100 بكسل</div>
                                </div>

                                <!-- الإعدادات -->
                                <h6 class="section-title mt-4">
                                    <i class="fas fa-cog"></i>
                                    الإعدادات
                                </h6>

                                <div class="mb-4">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1"
                                            {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <strong style="color: var(--secondary-color);">نشط</strong>
                                            <small class="d-block text-muted">وسائل الدفع النشطة فقط ستظهر للعملاء</small>
                                        </label>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_payment"
                                            name="is_payment" value="1"
                                            {{ old('is_payment', $paymentMethod->is_payment) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_payment">
                                            <strong style="color: var(--secondary-color);">طريقة دفع</strong>
                                            <small class="d-block text-muted">طريقة دفع فعلاً أم طريقة أخرى</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- معاينة -->
                                <div class="preview-card">
                                    <h6 class="mb-3 text-center" style="color: var(--secondary-color); font-weight: 700;">
                                        <i class="fas fa-eye me-2"></i>
                                        معاينة وسيلة الدفع
                                    </h6>

                                    <div class="preview-icon">
                                        @if ($paymentMethod->icon)
                                            <img id="previewImage" src="{{ Storage::url($paymentMethod->icon) }}"
                                                alt="معاينة الأيقونة">
                                        @else
                                            <img id="previewImage" src="{{ asset('images/default-payment.png') }}"
                                                alt="معاينة الأيقونة">
                                        @endif
                                    </div>

                                    <div class="preview-name" id="previewName">
                                        {{ old('name', $paymentMethod->name) }}
                                    </div>

                                    <div class="preview-key" id="previewKey">
                                        {{ old('key', $paymentMethod->key) }}
                                    </div>

                                    <div class="text-center mt-3">
                                        <span
                                            class="status-badge {{ old('is_active', $paymentMethod->is_active) ? 'status-active' : 'status-inactive' }}"
                                            id="previewStatus">
                                            @if (old('is_active', $paymentMethod->is_active))
                                                <i class="fas fa-check-circle"></i> نشط
                                            @else
                                                <i class="fas fa-times-circle"></i> غير نشط
                                            @endif
                                        </span>
                                        <span class="status-badge type-badge" id="previewType">
                                            @if (old('is_payment', $paymentMethod->is_payment))
                                                <i class="fas fa-credit-card"></i> دفع
                                            @else
                                                <i class="fas fa-exchange-alt"></i> أخرى
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- أزرار التحكم -->
                                <div class="mt-4">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-save me-2"></i>حفظ التعديلات
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                            <i class="fas fa-trash me-2"></i>حذف وسيلة الدفع
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث المعاينة عند تغيير الاسم
            const nameInput = document.getElementById('name');
            const keyInput = document.getElementById('key');

            nameInput.addEventListener('input', function() {
                document.getElementById('previewName').textContent = this.value ||
                    '{{ $paymentMethod->name }}';
            });

            keyInput.addEventListener('input', function() {
                document.getElementById('previewKey').textContent = this.value ||
                    '{{ $paymentMethod->key }}';
            });

            // تحديث حالة النشاط
            document.getElementById('is_active').addEventListener('change', function() {
                const badge = document.getElementById('previewStatus');
                badge.className = this.checked ? 'status-badge status-active' :
                    'status-badge status-inactive';
                if (this.checked) {
                    badge.innerHTML = '<i class="fas fa-check-circle"></i> نشط';
                } else {
                    badge.innerHTML = '<i class="fas fa-times-circle"></i> غير نشط';
                }
            });

            // تحديث نوع وسيلة الدفع
            document.getElementById('is_payment').addEventListener('change', function() {
                const badge = document.getElementById('previewType');
                if (this.checked) {
                    badge.innerHTML = '<i class="fas fa-credit-card"></i> دفع';
                } else {
                    badge.innerHTML = '<i class="fas fa-exchange-alt"></i> أخرى';
                }
            });

            // رفع الصورة
            const uploadArea = document.getElementById('uploadArea');
            uploadArea.addEventListener('click', function() {
                document.getElementById('icon').click();
            });

            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('active');
            });

            uploadArea.addEventListener('dragleave', function() {
                this.classList.remove('active');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('active');
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const input = document.getElementById('icon');
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    previewImage({
                        target: input
                    });
                }
            });

            // التحقق من النموذج
            document.getElementById('editForm').addEventListener('submit', function(e) {
                if (!nameInput.value || !keyInput.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'بيانات ناقصة',
                        text: 'يرجى ملء جميع الحقول المطلوبة',
                        confirmButtonColor: 'var(--primary-color)',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

            // تأثيرات عند التركيز
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
        });

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const mainPreview = document.getElementById('previewImage');
            const uploadArea = document.getElementById('uploadArea');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    mainPreview.src = e.target.result;
                    uploadArea.style.background = 'linear-gradient(135deg, #fff0ed 0%, #ffe8e0 100%)';

                    // تأثير ظهور الصورة
                    preview.style.opacity = '0';
                    preview.style.transform = 'scale(0.8)';

                    setTimeout(() => {
                        preview.style.opacity = '1';
                        preview.style.transform = 'scale(1)';
                        preview.style.transition = 'all 0.3s ease';
                    }, 10);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function handlePreviewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const mainPreview = document.getElementById('previewImage');
            const uploadArea = document.getElementById('uploadArea');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    mainPreview.src = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function confirmDelete() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف وسيلة الدفع "{{ $paymentMethod->name }}" نهائياً',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger-color)',
                cancelButtonColor: 'var(--secondary-color)',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn-danger',
                    cancelButton: 'btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // رسائل التنبيه من الجلسة
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: "{{ session('success') }}",
                confirmButtonColor: 'var(--primary-color)',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
