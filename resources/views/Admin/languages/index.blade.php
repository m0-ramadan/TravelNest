@extends('Admin.layout.master')

@section('title', 'إدارة اللغات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .languages-dashboard {
            padding: 20px 0;
        }

        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .welcome-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .welcome-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-left: 20px;
        }

        .welcome-content h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .welcome-content p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 5px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.active {
            border-left-color: #198754;
        }

        .stat-card.inactive {
            border-left-color: #dc3545;
        }

        .stat-card.default {
            border-left-color: #696cff;
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-left: 15px;
            color: white;
        }

        .stat-card.active .stat-icon {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        .stat-card.inactive .stat-icon {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .stat-card.default .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .stat-description {
            font-size: 13px;
            color: var(--bs-secondary-color);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }

        /* Languages Table */
        .languages-table {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 0;
        }

        .section-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--bs-border-color);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--bs-light-bg-subtle);
            border-bottom: 2px solid var(--bs-border-color);
            font-weight: 600;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .language-flag {
            width: 30px;
            height: 20px;
            border-radius: 3px;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: var(--bs-success-bg-subtle);
            color: var(--bs-success-text);
        }

        .status-badge.inactive {
            background: var(--bs-danger-bg-subtle);
            color: var(--bs-danger-text);
        }

        .default-badge {
            background: var(--bs-primary-bg-subtle);
            color: var(--bs-primary-text);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .direction-badge {
            background: var(--bs-info-bg-subtle);
            color: var(--bs-info-text);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            background: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
        }

        .btn-delete {
            background: var(--bs-danger-bg-subtle);
            color: var(--bs-danger);
        }

        .btn-toggle {
            background: var(--bs-success-bg-subtle);
            color: var(--bs-success);
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .action-item {
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .action-item:hover {
            background: var(--bs-card-bg);
            border-color: #696cff;
            transform: translateX(-5px);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .action-content h6 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--bs-heading-color);
        }

        .action-content p {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        /* Tips Section */
        .tips-section {
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            background: var(--bs-card-bg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .tip-item:last-child {
            border-bottom: none;
        }

        .tip-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #696cff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .tip-content h6 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--bs-heading-color);
        }

        .tip-content p {
            font-size: 14px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 30px;
        }

        .modal-body {
            padding: 30px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: var(--bs-light-bg-subtle);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--bs-secondary-color);
            margin: 0 auto 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-header {
                flex-direction: column;
                text-align: center;
            }

            .welcome-icon {
                margin-left: 0;
                margin-bottom: 15px;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .action-buttons {
                flex-wrap: wrap;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
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
                <li class="breadcrumb-item active">إدارة اللغات</li>
            </ol>
        </nav>

        <div class="languages-dashboard">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="welcome-header">
                    <div class="welcome-icon">
                        <i class="fas fa-language"></i>
                    </div>
                    <div class="welcome-content">
                        <h3>مرحباً بك في إدارة اللغات</h3>
                        <p>من هنا يمكنك إدارة جميع لغات النظام وتخصيصها حسب احتياجاتك</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-0">قم بإضافة وتعديل اللغات المدعومة في نظامك. يمكنك تحديد اللغة الافتراضية، تفعيل أو
                            تعطيل اللغات، وإدارة خصائص كل لغة.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="mt-3">
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-clock me-1"></i> {{ now()->format('H:i') }}
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-calendar me-1"></i> {{ now()->translatedFormat('l، d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card active">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-title">اللغات النشطة</div>
                            <div class="stat-description">اللغات المفعلة حالياً</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ $activeLanguagesCount ?? 0 }}</div>
                    <div class="stat-actions">
                        <span class="badge bg-success">مفعل</span>
                        <span class="text-muted">آخر تحديث: {{ $lastLanguageUpdate ?? 'اليوم' }}</span>
                    </div>
                </div>

                <div class="stat-card inactive">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="stat-title">اللغات المعطلة</div>
                            <div class="stat-description">اللغات غير المفعلة</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ $inactiveLanguagesCount ?? 0 }}</div>
                    <div class="stat-actions">
                        <span class="badge bg-danger">معطل</span>
                        <span class="text-muted">يمكنك تفعيلها</span>
                    </div>
                </div>

                <div class="stat-card default">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <div class="stat-title">اللغة الافتراضية</div>
                            <div class="stat-description">لغة النظام الرئيسية</div>
                        </div>
                    </div>
                    <div class="stat-value">
                        @if ($defaultLanguage)
                            {{ strtoupper($defaultLanguage->code) }}
                        @else
                            غير محدد
                        @endif
                    </div>
                    <div class="stat-actions">
                        <span class="badge bg-primary">افتراضي</span>
                        <span class="text-muted">يمكنك تغييرها</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <h5 class="section-title">إجراءات سريعة</h5>
                        <p class="section-description">الوصول السريع إلى إدارة اللغات</p>
                    </div>
                </div>

                <div class="actions-grid">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addLanguageModal"
                        class="text-decoration-none">
                        <div class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-content">
                                <h6>إضافة لغة جديدة</h6>
                                <p>إضافة لغة جديدة للنظام</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" onclick="setDefaultLanguagePrompt()" class="text-decoration-none">
                        <div class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="action-content">
                                <h6>تغيير اللغة الافتراضية</h6>
                                <p>تحديد اللغة الرئيسية للنظام</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" onclick="toggleAllLanguages()" class="text-decoration-none">
                        <div class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-toggle-on"></i>
                            </div>
                            <div class="action-content">
                                <h6>تفعيل/تعطيل جميع اللغات</h6>
                                <p>إدارة حالة جميع اللغات دفعة واحدة</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" onclick="exportLanguages()" class="text-decoration-none">
                        <div class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-file-export"></i>
                            </div>
                            <div class="action-content">
                                <h6>تصدير قائمة اللغات</h6>
                                <p>تصدير جميع اللغات إلى ملف</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Languages Table -->
                <div class="col-lg-8 mb-4">
                    <div class="languages-table">
                        <div class="section-header">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">قائمة اللغات</h5>
                                    <small class="text-muted">إدارة جميع لغات النظام</small>
                                </div>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
                                <i class="fas fa-plus me-2"></i> إضافة لغة
                            </button>
                        </div>

                        @if ($languages->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>العلم</th>
                                            <th>اللغة</th>
                                            <th>الكود</th>
                                            <th>الاتجاه</th>
                                            <th>الحالة</th>
                                            <th>الافتراضي</th>
                                            <th>الترتيب</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($languages as $language)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($language->image_path)
                                                        <img src="{{ asset($language->image_path) }}"
                                                            alt="{{ $language->name }}" class="language-flag">
                                                    @else
                                                        <div
                                                            class="language-flag bg-secondary d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-flag text-white"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $language->name }}</strong>
                                                </td>
                                                <td>
                                                    <code>{{ strtoupper($language->code) }}</code>
                                                </td>
                                                <td>
                                                    <span class="direction-badge">
                                                        {{ $language->direction == 'rtl' ? 'rtl' : 'ltr' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="status-badge {{ $language->is_active ? 'active' : 'inactive' }}">
                                                        {{ $language->is_active ? 'نشط' : 'معطل' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($language->is_default)
                                                        <span class="default-badge">
                                                            <i class="fas fa-star me-1"></i> افتراضي
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-light text-dark">{{ $language->sort_order }}</span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn-icon btn-edit"
                                                            onclick="editLanguage({{ $language->id }})"
                                                            data-bs-toggle="tooltip" data-bs-title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn-icon btn-toggle"
                                                            onclick="toggleLanguage({{ $language->id }})"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-title="{{ $language->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                            <i
                                                                class="fas fa-toggle-{{ $language->is_active ? 'on' : 'off' }}"></i>
                                                        </button>
                                                        @if (!$language->is_default)
                                                            <button class="btn-icon btn-edit"
                                                                onclick="setAsDefault({{ $language->id }})"
                                                                data-bs-toggle="tooltip" data-bs-title="تعيين كافتراضي">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                            <button class="btn-icon btn-delete"
                                                                onclick="deleteLanguage({{ $language->id }})"
                                                                data-bs-toggle="tooltip" data-bs-title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-language"></i>
                                </div>
                                <h5 class="text-muted">لا توجد لغات مضافة</h5>
                                <p class="text-muted mb-4">ابدأ بإضافة لغات جديدة للنظام</p>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addLanguageModal">
                                    <i class="fas fa-plus me-2"></i> إضافة أول لغة
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips Section -->
                <div class="col-lg-4">
                    <div class="tips-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div>
                                <h5 class="section-title">نصائح مهمة</h5>
                                <p class="section-description">إرشادات لإدارة اللغات</p>
                            </div>
                        </div>

                        <div class="tips-list">
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>لغة افتراضية واحدة</h6>
                                    <p>يجب أن يكون لديك لغة افتراضية واحدة على الأقل مفعلة</p>
                                </div>
                            </div>

                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-toggle-on"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>تفعيل اللغات</h6>
                                    <p>يمكنك تفعيل أو تعطيل اللغات حسب الحاجة دون حذفها</p>
                                </div>
                            </div>

                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-sort"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>ترتيب اللغات</h6>
                                    <p>استخدم خاصية الترتيب لتحديد موقع اللغة في القوائم</p>
                                </div>
                            </div>

                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>أعلام اللغات</h6>
                                    <p>أضف أعلاماً للغات لتسهيل التعرف عليها</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Language Modal -->
    <div class="modal fade" id="addLanguageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> إضافة لغة جديدة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addLanguageForm" action="{{ route('admin.languages.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم اللغة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="مثال: العربية">
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">كود اللغة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" required
                                placeholder="مثال: ar" maxlength="5">
                            <small class="text-muted">يستخدم كاختصار للغة (مثال: ar, en, fr)</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="direction" class="form-label">اتجاه النص <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="direction" name="direction" required>
                                    <option value="rtl">من اليمين لليسار (RTL)</option>
                                    <option value="ltr">من اليسار لليمين (LTR)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">ترتيب العرض</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order"
                                    value="0" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">صورة العلم</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">الصورة المثالية: 30x20 بكسل</small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" checked>
                            <label class="form-check-label" for="is_active">
                                تفعيل اللغة مباشرةً
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default"
                                value="1">
                            <label class="form-check-label" for="is_default">
                                تعيين كاللغة الافتراضية
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة اللغة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Language Modal -->
    <div class="modal fade" id="editLanguageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i> تعديل اللغة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editLanguageForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editModalBody">
                        <!-- سيتم ملؤه بالجافاسكريبت -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                },
                order: [
                    [7, 'asc']
                ],
                pageLength: 10,
                responsive: true
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        function editLanguage(id) {
            $.ajax({
                url: '/admin/languages/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#editModalBody').html(response);
                    $('#editLanguageForm').attr('action', '/admin/languages/' + id);
                    $('#editLanguageModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء جلب بيانات اللغة'
                    });
                }
            });
        }

        function toggleLanguage(id) {
            $.ajax({
                url: '/admin/languages/' + id + '/toggle',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء تغيير الحالة'
                    });
                }
            });
        }

        function setAsDefault(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم تغيير اللغة الافتراضية للنظام',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، تعيين كافتراضي',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/languages/' + id + '/set-default',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء التعيين'
                            });
                        }
                    });
                }
            });
        }

        function deleteLanguage(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لا يمكنك التراجع عن هذا الإجراء!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/languages/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
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

        function setDefaultLanguagePrompt() {
            Swal.fire({
                title: 'اختر اللغة الافتراضية',
                input: 'select',
                inputOptions: @json(
                    $languages->mapWithKeys(function ($lang) {
                        return [$lang->id => $lang->name . ' (' . strtoupper($lang->code) . ')'];
                    })),
                inputPlaceholder: 'اختر اللغة',
                showCancelButton: true,
                confirmButtonText: 'تعيين',
                cancelButtonText: 'إلغاء',
                inputValidator: (value) => {
                    if (!value) {
                        return 'يجب اختيار لغة!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    setAsDefault(result.value);
                }
            });
        }


        function toggleAllLanguages() {
            Swal.fire({
                title: 'تفعيل/تعطيل جميع اللغات',
                input: 'select',
                inputOptions: {
                    'activate': 'تفعيل جميع اللغات',
                    'deactivate': 'تعطيل جميع اللغات (عدا الافتراضي)'
                },
                inputPlaceholder: 'اختر الإجراء',
                showCancelButton: true,
                confirmButtonText: 'تنفيذ',
                cancelButtonText: 'إلغاء',
                inputValidator: (value) => {
                    if (!value) {
                        return 'يجب اختيار إجراء!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/languages/toggle-all',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء التنفيذ'
                            });
                        }
                    });
                }
            });
        }

        function exportLanguages() {
            Swal.fire({
                title: 'تصدير اللغات',
                text: 'اختر صيغة الملف',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Excel',
                cancelButtonText: 'PDF',
                showDenyButton: true,
                denyButtonText: 'CSV'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/admin/languages/export/excel';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = '/admin/languages/export/pdf';
                } else if (result.isDenied) {
                    window.location.href = '/admin/languages/export/csv';
                }
            });
        }

        // Form validation
        $('#addLanguageForm, #editLanguageForm').on('submit', function(e) {
            const codeInput = $(this).find('input[name="code"]');
            if (codeInput.length) {
                const code = codeInput.val().trim().toLowerCase();
                codeInput.val(code);
            }
            return true;
        });
    </script>
@endsection
