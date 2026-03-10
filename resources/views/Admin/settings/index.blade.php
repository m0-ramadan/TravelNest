@extends('Admin.layout.master')

@section('title', 'الإعدادات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .settings-dashboard {
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
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.general {
            border-left-color: #696cff;
        }

        .stat-card.communication {
            border-left-color: #198754;
        }

        .stat-card.smtp {
            border-left-color: #dc3545;
        }

        .stat-card.files {
            border-left-color: #fd7e14;
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

        .stat-card.general .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.communication .stat-icon {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        .stat-card.smtp .stat-icon {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .stat-card.files .stat-icon {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
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

        .stat-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
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
            margin-left: 15px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .section-description {
            color: var(--bs-secondary-color);
            font-size: 14px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .action-item {
            /* background: var(--bs-light-bg-subtle); */
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
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

        /* System Status */
        .system-status {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .status-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        .status-icon.success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        .status-icon.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .status-icon.error {
            background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);
        }

        .status-text h6 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 3px;
            color: var(--bs-heading-color);
        }

        .status-text p {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        .status-value {
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.success {
            background: var(--bs-success-bg-subtle);
            color: var(--bs-success-text);
        }

        .status-badge.warning {
            background: var(--bs-warning-bg-subtle);
            color: var(--bs-warning-text);
        }

        .status-badge.error {
            background: var(--bs-danger-bg-subtle);
            color: var(--bs-danger-text);
        }

        /* Recent Activity */
        .recent-activity {
            margin-top: 30px;
        }

        .activity-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            /* background: var(--bs-light-bg-subtle); */
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: #2f3349;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-left: 15px;
            color: white;
        }

        .activity-icon.update {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        }

        .activity-icon.add {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        .activity-icon.delete {
            background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);
        }

        .activity-icon.test {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 3px;
            color: var(--bs-heading-color);
        }

        .activity-description {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        .activity-time {
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        /* Tips Section */
        .tips-section {
            /* background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); */
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
        }

        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
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

            .stat-header {
                flex-direction: column;
                text-align: center;
            }

            .stat-icon {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .stat-actions {
                flex-direction: column;
                gap: 10px;
            }

            .actions-grid {
                grid-template-columns: 1fr;
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
                <li class="breadcrumb-item active">الإعدادات</li>
            </ol>
        </nav>

        <div class="settings-dashboard">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="welcome-header">
                    <div class="welcome-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="welcome-content">
                        <h3>مرحباً بك في لوحة الإعدادات</h3>
                        <p>من هنا يمكنك إدارة جميع إعدادات النظام وتخصيصها حسب احتياجاتك</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-0">قم بتكوين النظام الخاص بك من خلال الصفحات المختلفة أدناه. يمكنك تعديل الإعدادات العامة، البريد، التواصل، وإدارة الملفات.</p>
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
                <a href="{{ route('admin.settings.general') }}" class="text-decoration-none">
                    <div class="stat-card general">
                        <div class="stat-header">
                            <div class="stat-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div>
                                <div class="stat-title">الإعدادات العامة</div>
                                <div class="stat-description">تكوين النظام الأساسي</div>
                            </div>
                        </div>
                        <div class="stat-value">{{ $generalSettingsCount ?? 15 }} إعداد</div>
                        <div class="stat-actions">
                            <span class="badge bg-primary">محدث</span>
                            <span class="text-muted">آخر تحديث: {{ $lastGeneralUpdate ?? 'قبل ساعة' }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.settings.communication') }}" class="text-decoration-none">
                    <div class="stat-card communication">
                        <div class="stat-header">
                            <div class="stat-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <div class="stat-title">إعدادات التواصل</div>
                                <div class="stat-description">البريد والشبكات الاجتماعية</div>
                            </div>
                        </div>
                        <div class="stat-value">{{ $communicationSettingsCount ?? 22 }} إعداد</div>
                        <div class="stat-actions">
                            <span class="badge bg-success">نشط</span>
                            <span class="text-muted">آخر تحديث: {{ $lastCommunicationUpdate ?? 'قبل يومين' }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.settings.smtp') }}" class="text-decoration-none">
                    <div class="stat-card smtp">
                        <div class="stat-header">
                            <div class="stat-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="stat-title">إعدادات البريد</div>
                                <div class="stat-description">SMTP وخوادم البريد</div>
                            </div>
                        </div>
                        <div class="stat-value">{{ $smtpSettingsCount ?? 10 }} إعداد</div>
                        <div class="stat-actions">
                            <span class="badge bg-warning text-dark">يتطلب اختبار</span>
                            <span class="text-muted">آخر اختبار: {{ $lastSmtpTest ?? 'لم يتم' }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.settings.files') }}" class="text-decoration-none">
                    <div class="stat-card files">
                        <div class="stat-header">
                            <div class="stat-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div>
                                <div class="stat-title">إدارة الملفات</div>
                                <div class="stat-description">التخزين والملفات</div>
                            </div>
                        </div>
                        <div class="stat-value">{{ $storageUsage['percentage'] ?? 0 }}% استخدام</div>
                        <div class="stat-actions">
                            <span class="badge bg-info">طبيعي</span>
                            <span class="text-muted">متاح: {{ $storageUsage['available_human'] ?? '1.5 GB' }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="row">
                <!-- Quick Actions -->
                <div class="col-lg-8 mb-4">
                    <div class="quick-actions">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <h5 class="section-title">إجراءات سريعة</h5>
                                <p class="section-description">الوصول السريع إلى الإعدادات المهمة</p>
                            </div>
                        </div>

                        <div class="actions-grid">
                            <a href="{{ route('admin.settings.general') }}#site_logo" class="text-decoration-none">
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>تغيير شعار الموقع</h6>
                                        <p>رفع شعار جديد للموقع</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.smtp') }}#test_email" class="text-decoration-none">
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>اختبار إعدادات البريد</h6>
                                        <p>إرسال بريد تجريبي</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.communication') }}#social_media" class="text-decoration-none">
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>تحديث روابط التواصل</h6>
                                        <p>إضافة/تعديل وسائل التواصل</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.files') }}#fileUpload" class="text-decoration-none">
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>رفع ملفات جديدة</h6>
                                        <p>إضافة ملفات إلى التخزين</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.general') }}#site_maintenance" class="text-decoration-none">
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>وضع الصيانة</h6>
                                        <p>تفعيل/تعطيل وضع الصيانة</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.communication') }}#email_templates" class="text-decoration-none">
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>قوالب البريد</h6>
                                        <p>تعديل قوالب البريد الإلكتروني</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="recent-activity">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h5 class="section-title">النشاط الحديث</h5>
                                <p class="section-description">آخر التعديلات على الإعدادات</p>
                            </div>
                        </div>

                        <div class="activity-list">
                            @forelse($recentActivities as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon {{ $activity['type'] }}">
                                        <i class="fas fa-{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-description">{{ $activity['description'] }}</div>
                                    </div>
                                    <div class="activity-time">{{ $activity['time'] }}</div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">لا توجد نشاطات حديثة</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- System Status & Tips -->
                <div class="col-lg-4">
                    <!-- System Status -->
                    <div class="system-status">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div>
                                <h5 class="section-title">حالة النظام</h5>
                                <p class="section-description">مراقبة أداء النظام</p>
                            </div>
                        </div>

                        <div class="status-list">
                            <div class="status-item">
                                <div class="status-label">
                                    <div class="status-icon success">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <div class="status-text">
                                        <h6>حالة الخادم</h6>
                                        <p>الأداء والتوافر</p>
                                    </div>
                                </div>
                                <div class="status-value">
                                    <span class="status-badge success">جيد</span>
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="status-label">
                                    <div class="status-icon {{ $smtpStatus == 'active' ? 'success' : 'warning' }}">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="status-text">
                                        <h6>البريد الإلكتروني</h6>
                                        <p>إعدادات SMTP</p>
                                    </div>
                                </div>
                                <div class="status-value">
                                    <span class="status-badge {{ $smtpStatus == 'active' ? 'success' : 'warning' }}">
                                        {{ $smtpStatus == 'active' ? 'نشط' : 'يتطلب إعداد' }}
                                    </span>
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="status-label">
                                    <div class="status-icon {{ $storageUsage['percentage'] < 80 ? 'success' : 'warning' }}">
                                        <i class="fas fa-hdd"></i>
                                    </div>
                                    <div class="status-text">
                                        <h6>التخزين</h6>
                                        <p>المساحة المستخدمة</p>
                                    </div>
                                </div>
                                <div class="status-value">
                                    {{ $storageUsage['percentage'] ?? 0 }}%
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="status-label">
                                    <div class="status-icon {{ $cacheStatus == 'enabled' ? 'success' : 'warning' }}">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div class="status-text">
                                        <h6>التخزين المؤقت</h6>
                                        <p>تفعيل/تعطيل الكاش</p>
                                    </div>
                                </div>
                                <div class="status-value">
                                    <span class="status-badge {{ $cacheStatus == 'enabled' ? 'success' : 'warning' }}">
                                        {{ $cacheStatus == 'enabled' ? 'مفعل' : 'معطل' }}
                                    </span>
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="status-label">
                                    <div class="status-icon {{ $maintenanceMode ? 'warning' : 'success' }}">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="status-text">
                                        <h6>وضع الصيانة</h6>
                                        <p>تفعيل/تعطيل الموقع</p>
                                    </div>
                                </div>
                                <div class="status-value">
                                    <span class="status-badge {{ $maintenanceMode ? 'warning' : 'success' }}">
                                        {{ $maintenanceMode ? 'مفعل' : 'معطل' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div class="tips-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div>
                                <h5 class="section-title">نصائح مهمة</h5>
                                <p class="section-description">إرشادات لاستخدام أفضل</p>
                            </div>
                        </div>

                        <div class="tips-list">
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>الأمان أولاً</h6>
                                    <p>تأكد من تحديث كلمات المرور بانتظام وحماية بيانات الإتصال</p>
                                </div>
                            </div>

                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>النسخ الاحتياطي</h6>
                                    <p>احرص على عمل نسخ احتياطية للإعدادات قبل التعديلات الكبيرة</p>
                                </div>
                            </div>

                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>تحسين الأداء</h6>
                                    <p>تفعيل التخزين المؤقت يحسن من أداء النظام بشكل ملحوظ</p>
                                </div>
                            </div>

                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="tip-content">
                                    <h6>المتابعة المستمرة</h6>
                                    <p>راجع إعدادات البريد والتخزين بانتظام للتأكد من عملها بشكل صحيح</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Refresh storage usage every 30 seconds
            function refreshStorageUsage() {
                $.ajax({
                    url: '{{ route('admin.settings.storage-usage') }}',
                    type: 'GET',
                    success: function(data) {
                        $('#storagePercentage').text(data.percentage + '%');
                        $('#storageAvailable').text(data.available_human);
                        
                        // Update progress bar
                        $('.storage-progress .progress-bar').css('width', data.percentage + '%');
                    }
                });
            }

            // Refresh every 30 seconds
            setInterval(refreshStorageUsage, 30000);

            // Quick stats update
            function updateQuickStats() {
                $.ajax({
                    url: '{{ route('admin.settings.quick-stats') }}',
                    type: 'GET',
                    success: function(data) {
                        // Update all stat values
                        Object.keys(data).forEach(function(key) {
                            const element = $('#' + key);
                            if (element.length) {
                                element.text(data[key]);
                            }
                        });
                    }
                });
            }

            // Update stats every minute
            setInterval(updateQuickStats, 60000);

            // Activity auto-refresh
            function refreshActivities() {
                $.ajax({
                    url: '{{ route('admin.settings.recent-activities') }}',
                    type: 'GET',
                    success: function(data) {
                        const container = $('.activity-list');
                        container.empty();
                        
                        if (data.length === 0) {
                            container.html(`
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">لا توجد نشاطات حديثة</p>
                                </div>
                            `);
                            return;
                        }
                        
                        data.forEach(function(activity) {
                            const item = `
                                <div class="activity-item">
                                    <div class="activity-icon ${activity.type}">
                                        <i class="fas fa-${activity.icon}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">${activity.title}</div>
                                        <div class="activity-description">${activity.description}</div>
                                    </div>
                                    <div class="activity-time">${activity.time}</div>
                                </div>
                            `;
                            container.append(item);
                        });
                    }
                });
            }

            // Refresh activities every 2 minutes
            setInterval(refreshActivities, 120000);

            // System status check
            function checkSystemStatus() {
                $.ajax({
                    url: '{{ route('admin.settings.system-status') }}',
                    type: 'GET',
                    success: function(data) {
                        // Update status badges
                        Object.keys(data).forEach(function(key) {
                            const element = $('.' + key + '-status');
                            if (element.length) {
                                element.removeClass('success warning error');
                                element.addClass(data[key].class);
                                element.text(data[key].text);
                            }
                        });
                    }
                });
            }

            // Check system status every minute
            setInterval(checkSystemStatus, 60000);

            // Initialize charts if needed
            initializeCharts();
        });

        function initializeCharts() {
            // Storage usage chart
            const storageCtx = document.getElementById('storageChart');
            if (storageCtx) {
                new Chart(storageCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['مستخدم', 'متاح'],
                        datasets: [{
                            data: [{{ $storageUsage['percentage'] ?? 25 }}, {{ 100 - ($storageUsage['percentage'] ?? 25) }}],
                            backgroundColor: ['#696cff', '#e9ecef'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true
                            }
                        }
                    }
                });
            }

            // Settings activity chart
            const activityCtx = document.getElementById('activityChart');
            if (activityCtx) {
                new Chart(activityCtx, {
                    type: 'line',
                    data: {
                        labels: ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'],
                        datasets: [{
                            label: 'نشاط الإعدادات',
                            data: [12, 19, 8, 15, 22, 18, 10],
                            borderColor: '#696cff',
                            backgroundColor: 'rgba(105, 108, 255, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                rtl: true,
                                labels: {
                                    font: {
                                        family: 'Cairo'
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        // Quick action functions
        function testSmtpQuick() {
            Swal.fire({
                title: 'اختبار البريد الإلكتروني',
                input: 'email',
                inputLabel: 'أدخل بريدك الإلكتروني',
                inputPlaceholder: 'example@domain.com',
                showCancelButton: true,
                confirmButtonText: 'إرسال اختبار',
                cancelButtonText: 'إلغاء',
                showLoaderOnConfirm: true,
                preConfirm: (email) => {
                    return $.ajax({
                        url: '{{ route('admin.settings.smtp.test') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            test_email: email
                        }
                    }).then(response => {
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error.responseJSON?.message || error.statusText}`
                        );
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: result.value.success ? 'success' : 'error',
                        title: result.value.success ? 'تم الإرسال!' : 'فشل الإرسال',
                        text: result.value.message
                    });
                }
            });
        }

        function clearCache() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم مسح جميع البيانات المؤقتة للنظام',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، امسح الكاش',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.settings.clear-cache') }}',
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
                            Swal.fire({
                                icon: 'success',
                                title: 'تم التنظيف!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
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

        function toggleMaintenance() {
            const isMaintenance = {{ $maintenanceMode ? 'true' : 'false' }};
            
            Swal.fire({
                title: isMaintenance ? 'تعطيل وضع الصيانة' : 'تفعيل وضع الصيانة',
                text: isMaintenance ? 
                    'سيتم فتح الموقع للزوار مجدداً' : 
                    'سيتم إغلاق الموقع للصيانة، الزوار لن يتمكنوا من الوصول',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isMaintenance ? '#198754' : '#fd7e14',
                cancelButtonColor: '#3085d6',
                confirmButtonText: isMaintenance ? 'نعم، افتح الموقع' : 'نعم، أغلق للصيانة',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.settings.toggle-maintenance') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            mode: isMaintenance ? 'disable' : 'enable'
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'جاري التغيير...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
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
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء التغيير'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection