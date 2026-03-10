{{-- resources/views/Admin/ads/index.blade.php --}}
@extends('Admin.layout.master')

@section('title', 'إدارة الإعلانات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .ads-dashboard {
            padding: 20px 0;
        }

        /* Welcome Card */
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

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .stat-card.ads {
            border-left-color: #696cff;
        }

        .stat-card.types {
            border-left-color: #20c997;
        }

        .stat-card.icons {
            border-left-color: #ffc107;
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

        .stat-card.ads .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.types .stat-icon {
            background: linear-gradient(135deg, #20c997 0%, #0dcaf0 100%);
        }

        .stat-card.icons .stat-icon {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
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
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }

        .stat-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid var(--bs-border-color);
        }

        /* Search & Filter Section */
        .search-filter-section {
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

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 45px;
            border-radius: 10px;
            border: 2px solid var(--bs-border-color);
            height: 50px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
        }

        .search-box .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--bs-secondary-color);
            font-size: 18px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .filter-tab {
            padding: 8px 20px;
            border-radius: 25px;
            background: var(--bs-light-bg-subtle);
            color: var(--bs-secondary-color);
            border: 1px solid var(--bs-border-color);
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-tab:hover {
            background: var(--bs-card-bg);
            border-color: #696cff;
            color: #696cff;
        }

        .filter-tab.active {
            background: #696cff;
            color: white;
            border-color: #696cff;
        }

        .sort-dropdown {
            position: relative;
        }

        .sort-btn {
            background: var(--bs-light-bg-subtle);
            border: 1px solid var(--bs-border-color);
            color: var(--bs-body-color);
            padding: 10px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .sort-btn:hover {
            background: var(--bs-card-bg);
            border-color: #696cff;
            color: #696cff;
        }

        .sort-dropdown-content {
            display: none;
            position: absolute;
            background: var(--bs-card-bg);
            min-width: 250px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            z-index: 1000;
            padding: 10px 0;
            margin-top: 5px;
            left: 0;
            border: 1px solid var(--bs-border-color);
        }

        .sort-dropdown:hover .sort-dropdown-content {
            display: block;
        }

        .sort-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-item:hover {
            background: #696cff;
            color: white;
        }

        .sort-item.active {
            background: rgba(105, 108, 255, 0.1);
            color: #696cff;
            font-weight: 600;
        }

        /* Table Card */
        .table-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
        }

        .ads-icon-cell {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto;
        }

        .ads-type-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .type-banner {
            background: rgba(105, 108, 255, 0.2);
            color: #696cff;
            border: 1px solid rgba(105, 108, 255, 0.3);
        }

        .type-popup {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .type-sidebar {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .type-footer {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .description-cell {
            max-width: 300px;
            color: var(--bs-body-color);
            line-height: 1.6;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-action.btn-view {
            background: #0dcaf0;
            color: white;
        }

        .btn-action.btn-edit {
            background: #ffc107;
            color: #000;
        }

        .btn-action.btn-delete {
            background: #dc3545;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 70px;
            color: var(--bs-secondary-color);
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: var(--bs-secondary-color);
            margin-bottom: 20px;
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
            padding: 20px 25px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid var(--bs-border-color);
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--bs-heading-color);
        }

        .form-control,
        .form-select {
            border: 2px solid var(--bs-border-color);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
        }

        .icon-preview {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-top: 10px;
        }

        /* Detail View */
        .detail-item {
            padding: 15px;
            background: var(--bs-light-bg-subtle);
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--bs-border-color);
        }

        .detail-label {
            color: var(--bs-secondary-color);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--bs-heading-color);
            font-size: 16px;
            font-weight: 600;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            color: var(--bs-body-color);
            background: var(--bs-light-bg-subtle);
            transition: all 0.3s;
            text-decoration: none;
        }

        .page-link:hover {
            background: #696cff;
            color: white;
        }

        .page-item.active .page-link {
            background: #696cff;
            color: white;
        }

        .page-item.disabled .page-link {
            background: var(--bs-light-bg-subtle);
            color: var(--bs-secondary-color);
            opacity: 0.5;
            pointer-events: none;
        }

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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- مسار التنقل -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active">الإعلانات</li>
            </ol>
        </nav>

        <div class="ads-dashboard">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="welcome-header">
                    <div class="welcome-icon">
                        <i class="fas fa-ad"></i>
                    </div>
                    <div class="welcome-content">
                        <h3>مرحباً بك في إدارة الإعلانات</h3>
                        <p>من هنا يمكنك إدارة جميع إعلانات الموقع وإضافة وتعديل الإعلانات بكل سهولة</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            إجمالي الإعلانات: <strong>{{ number_format($stats['total'] ?? 0) }}</strong> |
                            أنواع الإعلانات: <strong>{{ number_format($stats['types_count'] ?? 0) }}</strong> |
                            آخر تحديث: <strong>{{ now()->format('H:i') }}</strong>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-clock me-1"></i> {{ now()->format('H:i') }}
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar me-1"></i> {{ now()->translatedFormat('l، d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card ads">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-ad"></i>
                        </div>
                        <div>
                            <div class="stat-title">إجمالي الإعلانات</div>
                            <div class="stat-description">جميع الإعلانات في الموقع</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
                    <div class="stat-footer">
                        <span>بانر: {{ number_format($stats['banner'] ?? 0) }}</span>
                        <span>منبثقة: {{ number_format($stats['popup'] ?? 0) }}</span>
                    </div>
                </div>

                <div class="stat-card types">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <div class="stat-title">أنواع الإعلانات</div>
                            <div class="stat-description">توزيع الإعلانات حسب النوع</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($stats['types_count'] ?? 0) }}</div>
                    <div class="stat-footer">
                        <span>جانبي: {{ number_format($stats['sidebar'] ?? 0) }}</span>
                        <span>تذييل: {{ number_format($stats['footer'] ?? 0) }}</span>
                    </div>
                </div>

                <div class="stat-card icons">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-icons"></i>
                        </div>
                        <div>
                            <div class="stat-title">إعلانات بأيقونات</div>
                            <div class="stat-description">إعلانات تحتوي على أيقونات</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($stats['with_icons'] ?? 0) }}</div>
                    <div class="stat-footer">
                        <span>نسبة:
                            @php
                                $total = $stats['total'] ?? 1;
                                $withIcons = $stats['with_icons'] ?? 0;
                                $percentage = $total > 0 ? round(($withIcons / $total) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Section -->
            <div class="search-filter-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h5 class="section-title">بحث وتصفية</h5>
                        <p class="section-description">ابحث في الإعلانات وصنفها حسب الفلاتر المختلفة</p>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" placeholder="بحث في الإعلانات..." id="searchInput"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-3">
                            <div class="sort-dropdown">
                                <button class="sort-btn" type="button">
                                    <i class="fas fa-sort-amount-down"></i>
                                    الترتيب
                                </button>
                                <div class="sort-dropdown-content">
                                    <div class="sort-item {{ request('sort_by') == 'created_at' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                                        onclick="sortBy('created_at', 'desc')">
                                        <i class="fas fa-clock"></i> الأحدث أولاً
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'created_at' && request('sort_direction') == 'asc' ? 'active' : '' }}"
                                        onclick="sortBy('created_at', 'asc')">
                                        <i class="fas fa-history"></i> الأقدم أولاً
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'type' && request('sort_direction') == 'asc' ? 'active' : '' }}"
                                        onclick="sortBy('type', 'asc')">
                                        <i class="fas fa-sort-alpha-down"></i> النوع (أ-ي)
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'type' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                                        onclick="sortBy('type', 'desc')">
                                        <i class="fas fa-sort-alpha-up"></i> النوع (ي-أ)
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn" style="background: #696cff; color: white;"
                                data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fas fa-plus-circle me-2"></i>إضافة إعلان
                            </button>
                        </div>
                    </div>
                </div>

                <div class="filter-tabs">
                    <div class="filter-tab {{ !request('type') ? 'active' : '' }}" onclick="filterBy('all')">
                        <i class="fas fa-ad"></i> جميع الإعلانات
                    </div>
                    <div class="filter-tab {{ request('type') == 'banner' ? 'active' : '' }}" onclick="filterBy('banner')">
                        <i class="fas fa-image"></i> بانر
                    </div>
                    <div class="filter-tab {{ request('type') == 'popup' ? 'active' : '' }}" onclick="filterBy('popup')">
                        <i class="fas fa-window-restore"></i> منبثقة
                    </div>
                    <div class="filter-tab {{ request('type') == 'sidebar' ? 'active' : '' }}"
                        onclick="filterBy('sidebar')">
                        <i class="fas fa-columns"></i> جانبي
                    </div>
                    <div class="filter-tab {{ request('type') == 'footer' ? 'active' : '' }}"
                        onclick="filterBy('footer')">
                        <i class="fas fa-shoe-prints"></i> تذييل
                    </div>
                    <div class="filter-tab {{ request('type') == 'with_icon' ? 'active' : '' }}"
                        onclick="filterBy('with_icon')">
                        <i class="fas fa-icons"></i> بأيقونة
                    </div>
                </div>
            </div>

            <!-- Ads Table -->
            <div class="table-card">
                <div class="table-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">قائمة الإعلانات</h5>
                            <small class="opacity-75">عرض وإدارة جميع إعلانات الموقع</small>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <span class="badge bg-light text-dark">
                                    إجمالي: {{ $ads->total() }}
                                </span>
                                <button class="btn btn-light" onclick="refreshData()">
                                    <i class="fas fa-sync-alt me-2"></i>تحديث
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th width="80">الأيقونة</th>
                                <th>النوع</th>
                                <th>الوصف</th>
                                <th width="180">تاريخ الإضافة</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="adsTable">
                            @forelse ($ads as $ad)
                                @php
                                    // معالجة الأيقونة
                                    $icon = trim($ad->icon ?? '');

                                    if ($icon === '') {
                                        $iconClass = 'fas fa-ad';
                                    } elseif (str_starts_with($icon, 'fa-')) {
                                        $iconClass = 'fas ' . $icon; // مثال: fa-truck-fast
                                    } else {
                                        $iconClass = $icon; // مثال: "fas fa-truck-fast" أو "fab fa-facebook"
                                    }
                                @endphp

                                <tr data-id="{{ $ad->id }}">
                                    <td>{{ $loop->iteration + $ads->perPage() * ($ads->currentPage() - 1) }}</td>

                                    <td>
                                        <div class="ads-icon-cell">
                                            <i class="{{ $iconClass }}"></i>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="ads-type-badge type-{{ $ad->type }}">
                                            @switch($ad->type)
                                                @case('banner')
                                                    <i class="fas fa-image me-1"></i>بانر
                                                @break

                                                @case('popup')
                                                    <i class="fas fa-window-restore me-1"></i>منبثقة
                                                @break

                                                @case('sidebar')
                                                    <i class="fas fa-columns me-1"></i>جانبي
                                                @break

                                                @case('footer')
                                                    <i class="fas fa-shoe-prints me-1"></i>تذييل
                                                @break

                                                @default
                                                    {{ $ad->type }}
                                            @endswitch
                                        </span>
                                    </td>

                                    <td class="description-cell">
                                        <div class="mb-1">{{ \Illuminate\Support\Str::limit($ad->description, 100) }}
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-hashtag me-1"></i> #{{ $ad->id }}
                                            @if (!empty($ad->icon))
                                                <span class="ms-2"><i class="fas fa-icons"></i></span>
                                            @endif
                                        </small>
                                    </td>

                                    <td>
                                        <div class="mb-1">
                                            <i class="fas fa-calendar text-muted me-1"></i>
                                            <small>{{ $ad->created_at?->translatedFormat('d M Y') }}</small>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            <small>{{ $ad->created_at?->translatedFormat('h:i A') }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn-action btn-view" data-bs-toggle="modal"
                                                data-bs-target="#viewModal" onclick="viewAd({{ $ad->id }})"
                                                title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button type="button" class="btn-action btn-edit" data-bs-toggle="modal"
                                                data-bs-target="#editModal" onclick="editAd({{ $ad->id }})"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-delete delete-btn"
                                                title="حذف" data-id="{{ $ad->id }}"
                                                data-description="{{ \Illuminate\Support\Str::limit($ad->description, 30) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-ad"></i>
                                                </div>
                                                <h5 class="empty-state-text">لا توجد إعلانات</h5>
                                                <p class="text-muted mb-3">لم يتم إضافة أي إعلانات حتى الآن</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addModal">
                                                    <i class="fas fa-plus-circle me-2"></i>إضافة إعلان جديد
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($ads->hasPages())
                        <div class="m-3">
                            <nav>
                                <ul class="pagination">
                                    {{-- Previous Page --}}
                                    @if ($ads->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $ads->previousPageUrl() }}" rel="prev">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pages --}}
                                    @foreach ($ads->getUrlRange(1, $ads->lastPage()) as $page => $url)
                                        @if ($page == $ads->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page --}}
                                    @if ($ads->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $ads->nextPageUrl() }}" rel="next">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal إضافة إعلان -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-plus-circle me-2"></i>إضافة إعلان جديد
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.ads.store') }}" method="POST" id="addForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-tag me-1"></i>
                                            نوع الإعلان <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" name="type" required>
                                            <option value="">اختر النوع</option>
                                            <option value="banner">🏴 بانر</option>
                                            <option value="popup">🪟 نافذة منبثقة</option>
                                            <option value="sidebar">📑 شريط جانبي</option>
                                            <option value="footer">👣 تذييل</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-icons me-1"></i>
                                            الأيقونة <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="icon" id="addIcon"
                                            placeholder="مثال: fas fa-ad" required>
                                        <small class="text-muted">أدخل كلاس الأيقونة من Font Awesome</small>
                                        <div class="icon-preview mt-2" id="addIconPreview">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-align-left me-1"></i>
                                            الوصف <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" name="description" rows="4" placeholder="أدخل نص الإعلان هنا..." required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>إلغاء
                                </button>
                                <button type="submit" class="btn btn-primary" id="addSubmitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="addSpinner"></span>
                                    <i class="fas fa-save me-1"></i>
                                    حفظ الإعلان
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal تعديل إعلان -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>
                                تعديل الإعلان
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST" id="editForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-tag me-1"></i>
                                            نوع الإعلان <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" name="type" id="editType" required>
                                            <option value="">اختر النوع</option>
                                            <option value="banner">🏴 بانر</option>
                                            <option value="popup">🪟 نافذة منبثقة</option>
                                            <option value="sidebar">📑 شريط جانبي</option>
                                            <option value="footer">👣 تذييل</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-icons me-1"></i>
                                            الأيقونة <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="icon" id="editIcon" required>
                                        <small class="text-muted">أدخل كلاس الأيقونة من Font Awesome</small>
                                        <div class="icon-preview mt-2" id="editIconPreview">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-align-left me-1"></i>
                                            الوصف <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" name="description" id="editDescription" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>إلغاء
                                </button>
                                <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="editSpinner"></span>
                                    <i class="fas fa-save me-1"></i>
                                    تحديث الإعلان
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal عرض إعلان -->
            <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-info-circle me-2"></i>
                                تفاصيل الإعلان
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="ads-icon-cell mx-auto" style="width: 80px; height: 80px; font-size: 36px;"
                                    id="viewIcon">
                                    <i class="fas fa-ad"></i>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">نوع الإعلان</div>
                                <div class="detail-value">
                                    <span class="ads-type-badge" id="viewType"></span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">الأيقونة</div>
                                <div class="detail-value" id="viewIconClass"></div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">تاريخ الإضافة</div>
                                <div class="detail-value" id="viewDate"></div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">آخر تحديث</div>
                                <div class="detail-value" id="viewUpdated"></div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">الوصف</div>
                                <div class="detail-value" id="viewDescription"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>إغلاق
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // ============================================
            // المتغيرات العامة
            // ============================================
            let currentAdId = null;

            // ============================================
            // تهيئة الصفحة
            // ============================================

            // ============================================
            // دوال الإعلانات
            // ============================================
            function editAd(id) {
                currentAdId = id;

                $.ajax({
                    url: "{{ url('admin/ads') }}/" + id, // لازم يكون عندك GET /admin/ads/{id}
                    type: "GET",
                    success: function(res) {
                        // لو الـ API بيرجع {data:{...}} أو بيرجع الإعلان مباشرة
                        const ad = res.data ?? res;

                        $('#editType').val(ad.type);
                        $('#editIcon').val(ad.icon || '');
                        $('#editDescription').val(ad.description || '');

                        // معاينة الأيقونة
                        $('#editIconPreview i').attr('class', ad.icon || 'fas fa-image');

                        // تحديث action بتاع الفورم
                        $('#editForm').attr('action', "{{ url('admin/ads') }}/" + id);
                    },
                    error: function() {
                        showNotification('error', 'خطأ', 'حدث خطأ أثناء جلب بيانات الإعلان');
                        $('#editModal').modal('hide');
                    }
                });
            }

            function viewAd(id) {
                $.ajax({
                    url: "{{ url('admin/ads') }}/" + id, // لازم يكون عندك GET /admin/ads/{id}
                    type: "GET",
                    success: function(res) {
                        const ad = res.data ?? res;

                        // أيقونة كبيرة
                        $('#viewIcon i').attr('class', ad.icon || 'fas fa-ad');

                        // النوع
                        $('#viewType')
                            .text(ad.type || '-')
                            .removeClass()
                            .addClass('ads-type-badge type-' + (ad.type || ''));

                        // باقي التفاصيل
                        $('#viewIconClass').text(ad.icon || '-');
                        $('#viewDescription').text(ad.description || '-');

                        // لو راجع created_at بصيغة ISO، اعرضه زي ما هو
                        $('#viewDate').text(ad.created_at || '-');
                        $('#viewUpdated').text(ad.updated_at || '-');
                    },
                    error: function() {
                        showNotification('error', 'خطأ', 'حدث خطأ أثناء جلب بيانات الإعلان');
                        $('#viewModal').modal('hide');
                    }
                });
            }
            $(document).on('click', '.delete-btn', function() {
                const adId = $(this).data('id');
                const adDescription = $(this).data('description') || '';

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    html: `سيتم حذف الإعلان <strong>${adDescription}</strong> نهائياً`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('admin.ads.destroy', ':id') }}".replace(':id', adId),
                        type: "POST", // Laravel method spoof
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'جاري الحذف...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف',
                                text: res.message || 'تم حذف الإعلان بنجاح',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                // شيل الصف من الجدول بدون ريفريش
                                $('tr[data-id="' + adId + '"]').remove();
                            });
                        },
                        error: function(xhr) {
                            let msg = 'حدث خطأ أثناء الحذف';

                            if (xhr.status === 419) msg =
                                'انتهت الجلسة (CSRF) — اعمل Refresh للصفحة';
                            else if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: msg
                            });
                        }
                    });
                });
            });

            // ============================================
            // دوال المساعدة
            // ============================================

            /**
             * عرض إشعار
             */

            /**
             * تصفية حسب النوع
             */
            function filterBy(type) {
                const params = {};
                if (type !== 'all') {
                    params.type = type;
                }
                updateUrl(params, true);
            }

            /**
             * ترتيب حسب الحقل
             */
            function sortBy(sortBy, sortDirection) {
                updateUrl({
                    sort_by: sortBy,
                    sort_direction: sortDirection
                }, true);
            }

            /**
             * تحديث URL
             */
            function updateUrl(params, reload = true) {
                const url = new URL(window.location.href);
                const searchParams = new URLSearchParams(url.search);

                Object.keys(params).forEach(key => {
                    const value = params[key];

                    if (value === null || value === undefined || value === '') {
                        searchParams.delete(key);
                    } else {
                        searchParams.set(key, value);
                    }
                });

                // إعادة تعيين الصفحة إلى 1 إذا كان هناك بحث جديد
                if (params.search !== undefined) {
                    searchParams.set('page', '1');
                }

                url.search = searchParams.toString();

                if (reload) {
                    window.location.href = url.toString();
                } else {
                    window.location.href = url.toString();
                }
            }
        </script>
    @endsection
