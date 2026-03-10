@extends('Admin.layout.master')

@section('title', 'إضافة وسيلة دفع جديدة')

@section('css')
    <style>
        :root {
            --primary-color: #696cff;
            --border-color: #dee2e6;
            --bg-light: #f8f9fa;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
        }

        .form-card {
            /* background: white; */
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .form-header {
            border-bottom: 2px solid var(--bg-light);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            position: relative;
        }

        .section-title::before {
            content: "";
            position: absolute;
            right: 0;
            bottom: -2px;
            width: 60px;
            height: 2px;
            background: var(--primary-color);
        }

        .image-upload-container {
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            /* background: var(--bg-light); */
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .image-upload-container:hover {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.05);
        }

        .image-preview {
            max-width: 100px;
            max-height: 100px;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 5px;
            /* background: white; */
            display: none;
        }

        .upload-icon {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .preview-card {
            /* background: var(--bg-light); */
            border-radius: 12px;
            padding: 20px;
            border: 2px solid var(--border-color);
        }

        .preview-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }

        .preview-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .preview-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
            text-align: center;
        }

        .preview-key {
            /* background: white; */
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            font-family: monospace;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
            margin: 5px;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .type-badge {
            /* background: #e7f5ff; */
            color: #0c63e4;
        }

        .form-switch {
            cursor: pointer;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .alert-guide {
            /* background: #e7f7ff; */
            border-right: 4px solid var(--primary-color);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 15px;
            }

            .preview-card {
                margin-top: 20px;
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
                <li class="breadcrumb-item active">إضافة جديدة</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="form-card">
                    <div class="form-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><i class="fas fa-plus-circle me-2"></i>إضافة وسيلة دفع جديدة</h5>
                                <p class="text-muted mb-0">أضف وسيلة دفع جديدة للنظام</p>
                            </div>
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>العودة
                            </a>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data"
                        id="createForm">
                        @csrf

                        <div class="row">
                            <div class="col-lg-8">
                                <!-- معلومات أساسية -->
                                <h6 class="section-title">المعلومات الأساسية</h6>

                                <div class="mb-4">
                                    <label for="name" class="form-label required">اسم وسيلة الدفع</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="مثال: بطاقة ائتمان، PayPal" required>
                                    <small class="text-muted">الاسم الذي سيظهر للعملاء</small>
                                </div>

                                <div class="mb-4">
                                    <label for="key" class="form-label required">المعرف (Key)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="key" name="key"
                                            value="{{ old('key') }}" placeholder="مثال: credit-card, paypal" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="generateKey()">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">معرف فريد يستخدم في النظام</small>
                                </div>

                                <!-- رفع الصورة -->
                                <h6 class="section-title mt-4">صورة الوسيلة</h6>

                                <div class="mb-4">
                                    <label class="form-label">أيقونة / صورة الوسيلة</label>
                                    <div class="image-upload-container" id="uploadArea">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <p class="mb-2">انقر لرفع صورة</p>
                                        <small class="text-muted">الصيغ المدعومة: JPG, PNG, SVG</small>
                                        <img id="imagePreview" class="image-preview mt-3" alt="معاينة الصورة">
                                        <input type="file" id="icon" name="icon" accept="image/*" class="d-none"
                                            onchange="handlePreviewImage(event)">

                                    </div>
                                    <small class="text-muted">الحجم الموصى به: 100×100 بكسل</small>
                                </div>

                                <!-- الإعدادات -->
                                <h6 class="section-title mt-4">الإعدادات</h6>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            <strong>نشط</strong>
                                            <small class="d-block text-muted">وسائل الدفع النشطة فقط ستظهر للعملاء</small>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_payment" name="is_payment"
                                            value="1" checked>
                                        <label class="form-check-label" for="is_payment">
                                            <strong>طريقة دفع</strong>
                                            <small class="d-block text-muted">طريقة دفع فعلاً أم طريقة أخرى</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- معاينة -->
                                <div class="preview-card">
                                    <h6 class="mb-3 text-center">معاينة</h6>

                                    <div class="preview-icon">
                                        <img id="previewImage" src="{{ asset('images/default-payment.png') }}"
                                            alt="معاينة الأيقونة">
                                    </div>

                                    <div class="preview-name" id="previewName">اسم الوسيلة</div>
                                    <div class="preview-key" id="previewKey">key</div>

                                    <div class="text-center mt-3">
                                        <span class="status-badge status-active" id="previewStatus">نشط</span>
                                        <span class="status-badge type-badge" id="previewType">دفع</span>
                                    </div>
                                </div>

                                <!-- أزرار التحكم -->
                                <div class="mt-4">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-2"></i>حفظ
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="resetForm()">
                                            <i class="fas fa-redo me-2"></i>إعادة تعيين
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
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث المعاينة عند تغيير الاسم
            const nameInput = document.getElementById('name');
            const keyInput = document.getElementById('key');

            nameInput.addEventListener('input', function() {
                document.getElementById('previewName').textContent = this.value || 'اسم الوسيلة';
                if (!keyInput.value) {
                    generateKey();
                }
            });

            keyInput.addEventListener('input', function() {
                document.getElementById('previewKey').textContent = this.value || 'key';
            });

            // تحديث حالة النشاط
            document.getElementById('is_active').addEventListener('change', function() {
                const badge = document.getElementById('previewStatus');
                badge.textContent = this.checked ? 'نشط' : 'غير نشط';
                badge.className = this.checked ? 'status-badge status-active' :
                    'status-badge status-inactive';
            });

            // تحديث نوع وسيلة الدفع
            document.getElementById('is_payment').addEventListener('change', function() {
                document.getElementById('previewType').textContent = this.checked ? 'دفع' : 'أخرى';
            });

            // رفع الصورة
            document.getElementById('uploadArea').addEventListener('click', function() {
                document.getElementById('icon').click();
            });

            // التحقق من النموذج
            document.getElementById('createForm').addEventListener('submit', function(e) {
                if (!nameInput.value || !keyInput.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'بيانات ناقصة',
                        text: 'يرجى ملء جميع الحقول المطلوبة',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        function generateKey() {
            const name = document.getElementById('name').value;
            if (name) {
                const key = name
                    .toLowerCase()
                    .replace(/[^a-z0-9\u0621-\u064A]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');

                document.getElementById('key').value = key;
                document.getElementById('previewKey').textContent = key;
            }
        }

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const mainPreview = document.getElementById('previewImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    mainPreview.src = e.target.result;
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

        function resetForm() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم مسح جميع البيانات المدخلة',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، امسح',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('createForm').reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    document.getElementById('previewImage').src = '{{ asset('images/default-payment.png') }}';
                    document.getElementById('previewName').textContent = 'اسم الوسيلة';
                    document.getElementById('previewKey').textContent = 'key';

                    Swal.fire({
                        icon: 'success',
                        title: 'تم الإعادة',
                        text: 'تم إعادة تعيين النموذج بنجاح',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>
@endsection
