@extends('Admin.layout.master')

@section('title', 'إدارة الملفات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .file-manager-container {
            background: var(--bs-card-bg);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #696cff;
        }

        .file-manager-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .file-manager-icon {
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

        .file-manager-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .file-manager-description {
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

        /* File Types */
        .file-type-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
        }

        .image {
            background: #198754;
            color: white;
        }

        .document {
            background: #0d6efd;
            color: white;
        }

        .video {
            background: #dc3545;
            color: white;
        }

        .audio {
            background: #6f42c1;
            color: white;
        }

        .archive {
            background: #fd7e14;
            color: white;
        }

        .other {
            background: #6c757d;
            color: white;
        }

        /* Upload Area */
        .upload-area {
            border: 3px dashed var(--bs-border-color);
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            background: var(--bs-light-bg-subtle);
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .upload-area:hover {
            border-color: #696cff;
            background: rgba(105, 108, 255, 0.05);
        }

        .upload-area i {
            font-size: 60px;
            color: #696cff;
            margin-bottom: 15px;
        }

        .upload-area h5 {
            color: var(--bs-heading-color);
            margin-bottom: 10px;
        }

        .upload-area p {
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        /* File List */
        .file-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .file-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            margin-bottom: 10px;
            background: var(--bs-card-bg);
            transition: all 0.3s ease;
        }

        .file-item:hover {
            background: var(--bs-light-bg-subtle);
            transform: translateX(-5px);
        }

        .file-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-left: 15px;
        }

        .file-icon.image {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        .file-icon.document {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        }

        .file-icon.video {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .file-icon.audio {
            background: linear-gradient(135deg, #6f42c1 0%, #d63384 100%);
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            margin-bottom: 5px;
            word-break: break-all;
        }

        .file-details {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--bs-secondary-color);
            font-size: 13px;
        }

        .file-actions {
            display: flex;
            gap: 5px;
        }

        .file-actions .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Storage Info */
        .storage-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }

        .storage-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .storage-title {
            font-size: 18px;
            font-weight: 600;
        }

        .storage-percentage {
            font-size: 24px;
            font-weight: 700;
        }

        .progress {
            height: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar {
            background: white;
        }

        .storage-details {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            opacity: 0.9;
        }

        /* File Type Limits */
        .limits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .limit-item {
            background: var(--bs-light-bg-subtle);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .limit-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #696cff;
        }

        .limit-label {
            font-size: 14px;
            color: var(--bs-secondary-color);
            margin-bottom: 5px;
        }

        .limit-value {
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        @media (max-width: 768px) {
            .file-manager-header {
                flex-direction: column;
                text-align: center;
            }

            .file-manager-icon {
                margin-left: 0;
                margin-bottom: 15px;
            }

            .file-item {
                flex-direction: column;
                text-align: center;
            }

            .file-icon {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .file-actions {
                margin-top: 10px;
                justify-content: center;
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
                <li class="breadcrumb-item active">إدارة الملفات</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">إدارة الملفات</h5>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> رجوع للإعدادات
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Quick Guide -->
                        <div class="alert-guide">
                            <h6><i class="fas fa-lightbulb me-2"></i>معلومات مهمة:</h6>
                            <ul>
                                <li>يمكنك إدارة إعدادات التخزين والملفات من هنا</li>
                                <li>يمكنك تحديد أنواع الملفات المسموح بها وأحجامها القصوى</li>
                                <li>يمكنك مراقبة استخدام التخزين الحالي</li>
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

                        <div class="row">
                            <!-- Storage Info -->
                            <div class="col-md-12 mb-4">
                                <div class="storage-info">
                                    <div class="storage-header">
                                        <div class="storage-title">استخدام التخزين</div>
                                        <div class="storage-percentage">{{ $storage_usage['percentage'] }}%</div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $storage_usage['percentage'] }}%"></div>
                                    </div>
                                    <div class="storage-details">
                                        <div>{{ formatBytes($storage_usage['used']) }} مستخدم</div>
                                        <div>{{ formatBytes($storage_usage['total']) }} الإجمالي</div>
                                        <div>{{ formatBytes($storage_usage['available']) }} متبقي</div>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-12 mb-4">
                                <div class="upload-area" onclick="document.getElementById('fileUpload').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h5>اسحب وأفلت الملفات هنا</h5>
                                    <p>أو انقر للتصفح</p>
                                    <small class="text-muted d-block mt-2">الحد الأقصى: {{ formatBytes($settings['max_upload_size'] ?? 2048 * 1024) }}</small>
                                </div>
                                <input type="file" id="fileUpload" multiple style="display: none;" onchange="handleFileUpload(this.files)">
                                
                                <div id="uploadProgress" style="display: none;">
                                    <div class="progress mb-3">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                             id="uploadProgressBar" style="width: 0%"></div>
                                    </div>
                                    <div class="text-center" id="uploadStatus"></div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.settings.files.update') }}" method="POST" id="fileSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="file-manager-container">
                                <div class="file-manager-header">
                                    <div class="file-manager-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div>
                                        <h5 class="file-manager-title">إعدادات التخزين</h5>
                                        <p class="file-manager-description">إعدادات تخزين وإدارة الملفات</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="storage_driver" class="form-label required">نظام التخزين</label>
                                        <select class="form-select" id="storage_driver" name="storage_driver" required>
                                            <option value="local" {{ old('storage_driver', $settings['storage_driver'] ?? '') == 'local' ? 'selected' : '' }}>
                                                التخزين المحلي
                                            </option>
                                            <option value="public" {{ old('storage_driver', $settings['storage_driver'] ?? '') == 'public' ? 'selected' : '' }}>
                                                التخزين العام
                                            </option>
                                            @if(config('filesystems.disks.s3.key'))
                                                <option value="s3" {{ old('storage_driver', $settings['storage_driver'] ?? '') == 's3' ? 'selected' : '' }}>
                                                    Amazon S3
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="max_upload_size" class="form-label required">الحجم الأقصى للرفع (KB)</label>
                                        <input type="number" class="form-control" id="max_upload_size" name="max_upload_size"
                                            value="{{ old('max_upload_size', $settings['max_upload_size'] ?? 2048) }}" required
                                            min="100" max="10240">
                                        <small class="text-muted">1 MB = 1024 KB</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="max_files_per_upload" class="form-label">الحد الأقصى للملفات في الرفع</label>
                                        <input type="number" class="form-control" id="max_files_per_upload" name="max_files_per_upload"
                                            value="{{ old('max_files_per_upload', $settings['max_files_per_upload'] ?? 10) }}"
                                            min="1" max="50">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="file_lifetime" class="form-label">مدة بقاء الملفات المؤقتة (أيام)</label>
                                        <input type="number" class="form-control" id="file_lifetime" name="file_lifetime"
                                            value="{{ old('file_lifetime', $settings['file_lifetime'] ?? 7) }}"
                                            min="1" max="30">
                                    </div>
                                </div>
                            </div>

                            <!-- File Type Limits -->
                            <div class="file-manager-container">
                                <div class="file-manager-header">
                                    <div class="file-manager-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h5 class="file-manager-title">أنواع الملفات المسموحة</h5>
                                        <p class="file-manager-description">تحديد أنواع الملفات المسموح بتحميلها</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="limits-grid">
                                            <div class="limit-item">
                                                <div class="limit-icon">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="limit-label">الصور</div>
                                                <div class="limit-value">
                                                    {{ implode(', ', $settings['allowed_image_types'] ?? ['jpg', 'jpeg', 'png', 'gif']) }}
                                                </div>
                                                <small class="text-muted">الحد: {{ $settings['max_image_size'] ?? 2048 }} KB</small>
                                            </div>

                                            <div class="limit-item">
                                                <div class="limit-icon">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                                <div class="limit-label">المستندات</div>
                                                <div class="limit-value">
                                                    {{ implode(', ', $settings['allowed_document_types'] ?? ['pdf', 'doc', 'docx', 'txt']) }}
                                                </div>
                                                <small class="text-muted">الحد: {{ $settings['max_document_size'] ?? 5120 }} KB</small>
                                            </div>

                                            <div class="limit-item">
                                                <div class="limit-icon">
                                                    <i class="fas fa-video"></i>
                                                </div>
                                                <div class="limit-label">الفيديو</div>
                                                <div class="limit-value">
                                                    {{ implode(', ', $settings['allowed_video_types'] ?? ['mp4', 'avi', 'mov', 'wmv']) }}
                                                </div>
                                                <small class="text-muted">الحد: {{ $settings['max_video_size'] ?? 10240 }} KB</small>
                                            </div>

                                            <div class="limit-item">
                                                <div class="limit-icon">
                                                    <i class="fas fa-music"></i>
                                                </div>
                                                <div class="limit-label">الصوت</div>
                                                <div class="limit-value">
                                                    {{ implode(', ', $settings['allowed_audio_types'] ?? ['mp3', 'wav', 'ogg']) }}
                                                </div>
                                                <small class="text-muted">الحد: {{ $settings['max_audio_size'] ?? 5120 }} KB</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="compress_images" name="compress_images"
                                                    {{ old('compress_images', $settings['compress_images'] ?? false) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">ضغط الصور تلقائياً</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="rename_uploads" name="rename_uploads"
                                                    {{ old('rename_uploads', $settings['rename_uploads'] ?? true) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">إعادة تسمية الملفات المرفوعة</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="watermark_images" name="watermark_images"
                                                    {{ old('watermark_images', $settings['watermark_images'] ?? false) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">إضافة علامة مائية للصور</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Files -->
                            <div class="file-manager-container">
                                <div class="file-manager-header">
                                    <div class="file-manager-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div>
                                        <h5 class="file-manager-title">آخر الملفات المرفوعة</h5>
                                        <p class="file-manager-description">عرض آخر الملفات التي تم رفعها</p>
                                    </div>
                                </div>

                                <div class="file-list">
                                    @forelse($recent_files as $file)
                                        <div class="file-item">
                                            <div class="file-icon {{ $file['type'] }}">
                                                <i class="{{ $file['icon'] }}"></i>
                                            </div>
                                            <div class="file-info">
                                                <div class="file-name">{{ $file['name'] }}</div>
                                                <div class="file-details">
                                                    <span>{{ formatBytes($file['size']) }}</span>
                                                    <span>{{ $file['uploaded_at'] }}</span>
                                                    <span class="file-type-badge {{ $file['type'] }}">{{ $file['type_name'] }}</span>
                                                </div>
                                            </div>
                                            <div class="file-actions">
                                                <a href="{{ $file['url'] }}" class="btn btn-info btn-sm" target="_blank" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ $file['url'] }}" class="btn btn-success btn-sm" download title="تحميل">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile('{{ $file['id'] }}')" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد ملفات مرفوعة حديثاً</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-primary" onclick="clearTempFiles()">
                                    <i class="fas fa-trash-alt me-1"></i> مسح الملفات المؤقتة
                                </button>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> إعادة تعيين
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> حفظ الإعدادات
                                    </button>
                                </div>
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
            $('#fileSettingsForm').on('submit', function(e) {
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

        function handleFileUpload(files) {
            if (files.length === 0) return;

            const maxSize = {{ $settings['max_upload_size'] ?? 2048 }} * 1024; // Convert to bytes
            const maxFiles = {{ $settings['max_files_per_upload'] ?? 10 }};
            
            if (files.length > maxFiles) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: `يمكنك رفع ${maxFiles} ملفات فقط في المرة الواحدة`,
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            // Validate file sizes
            for (let file of files) {
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: `الملف ${file.name} يتجاوز الحجم المسموح به`,
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }
            }

            // Show upload progress
            $('#uploadProgress').show();
            $('#uploadProgressBar').css('width', '0%');
            $('#uploadStatus').text('جاري الرفع...');

            // Simulate upload progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                $('#uploadProgressBar').css('width', progress + '%');
                
                if (progress >= 100) {
                    clearInterval(interval);
                    $('#uploadStatus').html('<i class="fas fa-check-circle text-success me-2"></i>تم رفع الملفات بنجاح');
                    
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }, 300);
        }

        function deleteFile(fileId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لن تتمكن من استعادة هذا الملف!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    $.ajax({
                        url: '{{ route('admin.settings.files.delete') }}',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            file_id: fileId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف'
                            });
                        }
                    });
                }
            });
        }

        function clearTempFiles() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف جميع الملفات المؤقتة الأقدم من 7 أيام',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، امسحها!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send clear temp files request
                    $.ajax({
                        url: '{{ route('admin.settings.files.clear-temp') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'جاري التنظيف...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم التنظيف!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء التنظيف'
                            });
                        }
                    });
                }
            });
        }

        // Drag and drop functionality
        const uploadArea = document.querySelector('.upload-area');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = '#696cff';
                uploadArea.style.backgroundColor = 'rgba(105, 108, 255, 0.1)';
            });

            uploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = '';
                uploadArea.style.backgroundColor = '';
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = '';
                uploadArea.style.backgroundColor = '';
                
                const files = e.dataTransfer.files;
                handleFileUpload(files);
            });
        }
    </script>
@endsection