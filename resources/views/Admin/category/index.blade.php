@extends('Admin.layout.master')

@section('title', 'إدارة الأقسام')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        /* Category Card */
        .category-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            background: var(--bs-card-bg);
        }

        .category-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
            border-color: #696cff;
        }

        .category-card.selected {
            border: 2px solid #696cff;
            box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
        }

        .category-image {
            height: 160px;
            overflow: hidden;
            position: relative;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image img {
            transform: scale(1.05);
        }

        .category-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .badge-parent {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .badge-child {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
            color: white;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .category-content {
            padding: 15px;
        }

        .category-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--bs-heading-color);
            line-height: 1.4;
            height: 45px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .category-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .category-slug {
            font-size: 11px;
            color: #6c757d;
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 15px;
            display: inline-block;
        }

        .category-stats {
            display: flex;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: #696cff;
        }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
        }

        .category-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        .category-actions .btn {
            flex: 1;
            padding: 6px 10px;
            font-size: 12px;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-active {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);
            color: white;
        }

        /* Filter Card */
        .filter-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--bs-border-color);
        }

        .filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: #696cff;
            font-size: 20px;
        }

        .filter-title h5 {
            margin-bottom: 0;
            font-weight: 700;
            color: var(--bs-heading-color);
        }

        /* Bulk Actions */
        .bulk-actions-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            display: none;
            color: white;
            animation: slideDown 0.3s ease;
        }

        .bulk-actions-container.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bulk-action-select {
            max-width: 200px;
            display: inline-block;
            margin-left: 10px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .bulk-action-select option {
            background: #667eea;
            color: white;
        }

        /* Table Styles */
        .category-table-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .parent-category {
            background: rgba(105, 108, 255, 0.05);
            font-weight: 600;
        }

        .child-category {
            background: var(--bs-card-bg);
        }

        .child-category td:first-child {
            position: relative;
        }

        .child-category td:first-child::before {
            content: '↳';
            position: absolute;
            right: 15px;
            color: #696cff;
            font-size: 18px;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 16px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        .quick-action-btn:hover {
            background: #696cff;
            color: white;
            border-color: #696cff;
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .view-toggle-btn {
            padding: 8px 16px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-toggle-btn.active {
            background: #696cff;
            color: white;
        }

        .view-toggle-btn:not(:last-child) {
            border-left: 1px solid #dee2e6;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 70px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            font-size: 20px;
            font-weight: 700;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
            margin-bottom: 20px;
        }

        /* Reorder Modal Styles */
        .sortable-categories,
        .sortable-children {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 8px;
            padding: 12px 15px;
            cursor: move;
            transition: all 0.3s ease;
        }

        .category-item:hover {
            background: #f8f9fa;
            border-color: #696cff;
            box-shadow: 0 2px 8px rgba(105, 108, 255, 0.2);
        }

        .category-item.dragging {
            opacity: 0.5;
            transform: scale(0.98);
        }

        .category-item .handle {
            cursor: move;
            color: #adb5bd;
            margin-left: 10px;
        }

        .category-item .handle:hover {
            color: #696cff;
        }

        .category-name {
            font-weight: 600;
            color: #495057;
        }

        .parent-category-item {
            background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
            border-right: 4px solid #667eea;
        }

        .child-category-item {
            margin-right: 30px;
            background: white;
            border-right: 4px solid #0dcaf0;
        }

        .order-badge {
            background: #696cff;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .children-container {
            margin-right: 30px;
            margin-top: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .view-toggle {
                margin-top: 10px;
            }

            .quick-actions {
                margin-top: 10px;
            }

            .bulk-action-select {
                max-width: 100%;
                margin-top: 10px;
            }

            .filter-header {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Checkbox Style */
        .form-check-input {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #696cff;
            border-color: #696cff;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
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
                <li class="breadcrumb-item active">
                    <i class="fas fa-sitemap ms-1"></i>
                    الأقسام
                </li>
            </ol>
        </nav>

        <!-- Header Actions -->
        <div class="filter-card">
            <div class="filter-header">
                <div class="filter-title">
                    <i class="fas fa-sitemap"></i>
                    <h5>إدارة الأقسام</h5>
                </div>
                <div class="d-flex gap-3">
                    <!-- View Toggle -->
                    <div class="view-toggle">
                        <button class="view-toggle-btn active" onclick="toggleView('grid')" id="gridViewBtn">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button class="view-toggle-btn" onclick="toggleView('table')" id="tableViewBtn">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>

                    <!-- Add Category Button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus-circle me-1"></i>
                            إضافة قسم
                        </button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#addCategoryModal">
                                    <i class="fas fa-folder me-2"></i> قسم رئيسي
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#addSubcategoryModal">
                                    <i class="fas fa-level-up-alt fa-rotate-90 me-2"></i> قسم فرعي
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="reorderCategories()">
                                    <i class="fas fa-sort-amount-down me-2"></i> إعادة الترتيب
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm"
                        class="d-flex flex-wrap gap-3">
                        <!-- Search -->
                        <div class="position-relative" style="min-width: 250px;">
                            <input type="text" class="form-control" name="search" id="searchInput"
                                placeholder="بحث في الأقسام..." value="{{ request('search') }}">
                            <i class="fas fa-search position-absolute"
                                style="left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
                            @if (request('search'))
                                <button type="button" id="clearSearch" class="btn position-absolute"
                                    style="right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none;">
                                    <i class="fas fa-times text-muted"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Parent Filter -->
                        <select class="form-select select2" name="parent_id" id="parentFilter" style="min-width: 200px;">
                            <option value="">جميع الأقسام</option>
                            <option value="null" {{ request('parent_id') === 'null' ? 'selected' : '' }}>
                                الأقسام الرئيسية فقط
                            </option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Status Filter -->
                        <select class="form-select" name="status_id" id="statusFilter" style="min-width: 150px;">
                            <option value="">جميع الحالات</option>
                            <option value="1" {{ request('status_id') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="2" {{ request('status_id') == '2' ? 'selected' : '' }}>غير نشط</option>
                        </select>

                        <!-- Order By -->
                        <select class="form-select" name="order_by" id="orderByFilter" style="min-width: 150px;">
                            <option value="order" {{ request('order_by') == 'order' ? 'selected' : '' }}>الترتيب</option>
                            <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>
                                تاريخ الإضافة
                            </option>
                        </select>

                        <!-- Sort Direction -->
                        <select class="form-select" name="sort_dir" id="sortDirFilter" style="min-width: 120px;">
                            <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                            <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                        </select>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="d-flex justify-content-end gap-2">
                        <!-- Quick Filters -->
                        <div class="quick-actions">
                            <button type="button" class="quick-action-btn" onclick="applyQuickFilter('status_id', '1')">
                                <i class="fas fa-check-circle"></i> النشطة
                            </button>
                            <button type="button" class="quick-action-btn" onclick="applyQuickFilter('status_id', '2')">
                                <i class="fas fa-times-circle"></i> غير النشطة
                            </button>
                            <button type="button" class="quick-action-btn"
                                onclick="applyQuickFilter('parent_id', 'null')">
                                <i class="fas fa-folder"></i> الرئيسية
                            </button>
                            <button type="button" class="quick-action-btn" onclick="clearFilters()">
                                <i class="fas fa-filter-circle-xmark"></i> إعادة تعيين
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions-container" id="bulkActions">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllBulk"
                            style="background: white; border-color: white;">
                        <label class="form-check-label text-white fw-bold" for="selectAllBulk">
                            تحديد الكل
                        </label>
                    </div>
                    <span class="text-white fw-bold" id="selectedCount">0 قسم محدد</span>

                    <select class="form-select bulk-action-select" id="bulkActionSelect">
                        <option value="">اختر إجراء جماعي...</option>
                        <option value="activate">تفعيل الأقسام المحددة</option>
                        <option value="deactivate">تعطيل الأقسام المحددة</option>
                        <option value="move_to_parent">نقل إلى قسم رئيسي</option>
                        <option value="delete">حذف الأقسام المحددة</option>
                    </select>

                    <button type="button" class="btn btn-light" onclick="applyBulkAction()">
                        <i class="fas fa-check me-1"></i>
                        تطبيق
                    </button>
                </div>

                <button type="button" class="btn btn-outline-light btn-sm" onclick="hideBulkActions()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Additional Options -->
            <div id="bulkActionOptions" class="mt-3" style="display: none;">
                <div id="moveToParentOptions" class="row g-3" style="display: none;">
                    <div class="col-md-6">
                        <label class="text-white fw-bold mb-2">اختر القسم الرئيسي:</label>
                        <select class="form-select" id="bulkParentSelect">
                            <option value="">-- اختر القسم الرئيسي --</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Grid View -->
        <div id="gridView" class="view-container">
            @if ($categories->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="categoriesGrid">
                    @foreach ($categories as $category)
                        <div class="col">
                            <div class="category-card" data-category-id="{{ $category->id }}">
                                <div class="category-image">
                                    <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/default-category.png') }}"
                                        alt="{{ $category->name }}">

                                    <div class="category-badges">
                                        @if (is_null($category->parent_id))
                                            <span class="badge-parent">
                                                <i class="fas fa-folder me-1"></i> رئيسي
                                            </span>
                                        @else
                                            <span class="badge-child">
                                                <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i> فرعي
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Checkbox for selection -->
                                    <div class="position-absolute" style="top: 10px; left: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input category-checkbox" type="checkbox"
                                                value="{{ $category->id }}" id="grid_category_{{ $category->id }}"
                                                style=" border-color: white;">
                                        </div>
                                    </div>
                                </div>

                                <div class="category-content">
                                    <h6 class="category-title" title="{{ $category->name }}">
                                        {{ Str::limit($category->name, 40) }}
                                    </h6>

                                    @if ($category->slug)
                                        <div class="category-meta">
                                            <span class="category-slug">
                                                <i class="fas fa-link me-1"></i> {{ $category->slug }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="mb-2">
                                        @if ($category->status_id == 1)
                                            <span class="status-badge status-active">
                                                <i class="fas fa-check-circle"></i> نشط
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-times-circle"></i> غير نشط
                                            </span>
                                        @endif
                                    </div>

                                    @if (!is_null($category->parent_id) && $category->parent)
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                                            تابع لـ: <strong>{{ $category->parent->name }}</strong>
                                        </div>
                                    @endif

                                    <div class="category-stats">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $category->products_count ?? 0 }}</div>
                                            <div class="stat-label">منتجات</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $category->children_count ?? 0 }}</div>
                                            <div class="stat-label">أقسام فرعية</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $category->order }}</div>
                                            <div class="stat-label">الترتيب</div>
                                        </div>
                                    </div>

                                    <div class="category-actions">
                                        <a href="{{ route('admin.categories.show', $category->id) }}"
                                            class="btn btn-outline-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                            class="btn btn-outline-primary btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                            onclick="duplicateCategory({{ $category->id }}, '{{ $category->name }}')"
                                            title="نسخ">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-category"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                            title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($categories->hasPages())
                    <div class="mt-4">
                        <nav>
                            <ul class="pagination justify-content-center">
                                {{-- Previous Page Link --}}
                                @if ($categories->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $categories->previousPageUrl() }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                    @if ($page == $categories->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($categories->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $categories->nextPageUrl() }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-sitemap"></i>
                    <h5>لا توجد أقسام</h5>
                    <p>ابدأ بإضافة أقسام جديدة لتنظيم منتجاتك</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus-circle me-1"></i>
                        إضافة قسم جديد
                    </button>
                </div>
            @endif
        </div>

        <!-- Table View -->
        <div id="tableView" class="view-container" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllTable">
                                        </div>
                                    </th>
                                    <th width="80">الصورة</th>
                                    <th>اسم القسم</th>
                                    <th>الرابط (Slug)</th>
                                    <th>القسم الرئيسي</th>
                                    <th>الحالة</th>
                                    <th>الترتيب</th>
                                    <th>المنتجات</th>
                                    <th>الأقسام الفرعية</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr
                                        class="{{ is_null($category->parent_id) ? 'parent-category' : 'child-category' }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input category-checkbox" type="checkbox"
                                                    value="{{ $category->id }}" id="table_category_{{ $category->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            @if ($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}"
                                                    alt="{{ $category->name }}" class="category-table-image">
                                            @else
                                                <div
                                                    class="category-table-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-folder text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if ($category->description)
                                                <br>
                                                <small
                                                    class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary">{{ $category->slug ?? '---' }}</span>
                                        </td>
                                        <td>
                                            @if ($category->parent)
                                                {{ $category->parent->name }}
                                            @else
                                                <span class="badge bg-label-info">قسم رئيسي</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->status_id == 1)
                                                <span class="status-badge status-active">
                                                    <i class="fas fa-check-circle"></i> نشط
                                                </span>
                                            @else
                                                <span class="status-badge status-inactive">
                                                    <i class="fas fa-times-circle"></i> غير نشط
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $category->order }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $category->products_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $category->children_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.categories.show', $category->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="duplicateCategory({{ $category->id }}, '{{ $category->name }}')"
                                                    title="نسخ">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-category"
                                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                    title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-sitemap"></i>
                                                <h5>لا توجد أقسام</h5>
                                                <p>ابدأ بإضافة أقسام جديدة لتنظيم منتجاتك</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($categories->hasPages())
                        <div class="mt-4">
                            <nav>
                                <ul class="pagination justify-content-center">
                                    {{ $categories->links() }}
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                    id="addCategoryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-folder-plus ms-2"></i>
                            إضافة قسم جديد
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">اسم القسم</label>
                                <input type="text" class="form-control" name="name" id="add_name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">الرابط (Slug)</label>
                                <input type="text" class="form-control" name="slug" id="add_slug">
                                <small class="text-muted">سيتم إنشاؤه تلقائياً إذا تركت فارغاً</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">الوصف</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">القسم الرئيسي</label>
                                <select class="form-select select2-modal" name="parent_id">
                                    <option value="">قسم رئيسي (بدون أب)</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">ترتيب العرض</label>
                                <input type="number" class="form-control" name="order" value="0"
                                    min="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">الحالة</label>
                                <select class="form-select" name="status_id" required>
                                    <option value="1" selected>نشط</option>
                                    <option value="2">غير نشط</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">صورة القسم</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">صورة فرعية</label>
                                <input type="file" class="form-control" name="sub_image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ القسم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Subcategory Modal -->
    <div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                    id="addSubcategoryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-level-up-alt fa-rotate-90 ms-2"></i>
                            إضافة قسم فرعي
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">اسم القسم الفرعي</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">القسم الرئيسي</label>
                                <select class="form-select select2-modal" name="parent_id" id="sub_parent_id" required>
                                    <option value="">اختر القسم الرئيسي</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">الوصف</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">الرابط (Slug)</label>
                                <input type="text" class="form-control" name="slug">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">ترتيب العرض</label>
                                <input type="number" class="form-control" name="order" value="0"
                                    min="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">الحالة</label>
                                <select class="form-select" name="status_id" required>
                                    <option value="1" selected>نشط</option>
                                    <option value="2">غير نشط</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">صورة القسم</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">إضافة قسم فرعي</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-edit ms-2"></i>
                            تعديل القسم
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">اسم القسم</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">الرابط (Slug)</label>
                                <input type="text" class="form-control" name="slug" id="edit_slug">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">الوصف</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">القسم الرئيسي</label>
                                <select class="form-select select2-modal" name="parent_id" id="edit_parent_id">
                                    <option value="">قسم رئيسي (بدون أب)</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">ترتيب العرض</label>
                                <input type="number" class="form-control" name="order" id="edit_order"
                                    min="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">الحالة</label>
                                <select class="form-select" name="status_id" id="edit_status_id" required>
                                    <option value="1">نشط</option>
                                    <option value="2">غير نشط</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">صورة القسم</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="text-muted">اترك فارغاً للاحتفاظ بالصورة الحالية</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تحديث القسم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reorder Modal -->
    <div class="modal fade" id="reorderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sort-amount-down ms-2"></i>
                        إعادة ترتيب الأقسام
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        اسحب وأفلت الأقسام لتغيير ترتيبها. التغييرات ستحفظ تلقائياً.
                    </div>

                    <div id="categoryTree" class="sortable-container">
                        <!-- Categories will be loaded via AJAX -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-2">جاري تحميل الأقسام...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" id="saveOrderBtn" style="display: none;">
                        <i class="fas fa-save me-1"></i>
                        حفظ الترتيب
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-container">
            <div class="spinner"></div>
            <h6 class="mt-3">جاري التحميل...</h6>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'اختر...',
                allowClear: true,
                width: '100%'
            });

            $('.select2-modal').select2({
                placeholder: 'اختر...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#addCategoryModal, #addSubcategoryModal, #editCategoryModal')
            });

            // Auto-generate slug
            $('#add_name').on('keyup', function() {
                const name = $(this).val();
                if (name && !$('#add_slug').val()) {
                    const slug = name.toLowerCase()
                        .replace(/\s+/g, '-')
                        .replace(/[^\u0600-\u06FFa-z0-9\-]/g, '');
                    $('#add_slug').val(slug);
                }
            });

            // Search functionality
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $('#filterForm').submit();
                }, 500);
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                $('#filterForm').submit();
            });

            // Filter change
            $('#parentFilter, #statusFilter, #orderByFilter, #sortDirFilter').on('change', function() {
                $('#filterForm').submit();
            });

            // Select all checkboxes
            $('#selectAllBulk, #selectAllTable').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.category-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // Category checkbox selection
            $(document).on('change', '.category-checkbox', function() {
                updateSelectedCount();

                // Update select all state
                const totalCheckboxes = $('.category-checkbox').length;
                const checkedCheckboxes = $('.category-checkbox:checked').length;
                const allChecked = totalCheckboxes === checkedCheckboxes;

                $('#selectAllBulk, #selectAllTable').prop('checked', allChecked);
            });

            // Bulk action select change
            $('#bulkActionSelect').on('change', function() {
                const action = $(this).val();
                $('#bulkActionOptions').hide().find('> div').hide();

                if (action === 'move_to_parent') {
                    $('#bulkActionOptions').show();
                    $('#moveToParentOptions').show();
                }
            });

            // Delete category
            $(document).on('click', '.delete-category', function() {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');

                Swal.fire({
                    title: 'تأكيد الحذف',
                    html: `هل أنت متأكد من حذف القسم <strong>"${categoryName}"</strong>؟`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteCategory(categoryId);
                    }
                });
            });

            // View toggle
            const savedView = localStorage.getItem('categories_view') || 'grid';
            toggleView(savedView);

            // Save order button click
            $('#saveOrderBtn').on('click', function() {
                saveCategoryOrder();
            });
        });

        // Toggle View
        function toggleView(viewType) {
            $('.view-toggle-btn').removeClass('active');
            $('.view-container').hide();

            if (viewType === 'grid') {
                $('#gridView').show();
                $('#gridViewBtn').addClass('active');
                localStorage.setItem('categories_view', 'grid');
            } else {
                $('#tableView').show();
                $('#tableViewBtn').addClass('active');
                localStorage.setItem('categories_view', 'table');
            }
        }

        // Update Selected Count
        function updateSelectedCount() {
            const selectedCount = $('.category-checkbox:checked').length;
            $('#selectedCount').text(selectedCount + ' قسم محدد');

            if (selectedCount > 0) {
                $('#bulkActions').addClass('show');
            } else {
                $('#bulkActions').removeClass('show');
            }
        }

        // Hide Bulk Actions
        function hideBulkActions() {
            $('#bulkActions').removeClass('show');
            $('.category-checkbox').prop('checked', false);
            $('#selectAllBulk, #selectAllTable').prop('checked', false);
            $('#selectedCount').text('0 قسم محدد');
        }

        // Apply Quick Filter
        function applyQuickFilter(filter, value) {
            const url = new URL(window.location.href);
            url.searchParams.delete('page');

            if (filter === 'parent_id' && value === 'null') {
                url.searchParams.set('parent_id', 'null');
            } else {
                url.searchParams.set(filter, value);
            }

            window.location.href = url.toString();
        }

        // Clear Filters
        function clearFilters() {
            window.location.href = '{{ route('admin.categories.index') }}';
        }

        // Apply Bulk Action
        function applyBulkAction() {
            const action = $('#bulkActionSelect').val();
            const selectedCategories = [];

            $('.category-checkbox:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            if (selectedCategories.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'الرجاء اختيار قسم على الأقل',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#696cff'
                });
                return;
            }

            if (!action) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'الرجاء اختيار إجراء',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#696cff'
                });
                return;
            }

            let additionalData = {};

            switch (action) {
                case 'move_to_parent':
                    const parentId = $('#bulkParentSelect').val();
                    if (!parentId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'الرجاء اختيار القسم الرئيسي',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                        return;
                    }
                    additionalData.parent_id = parentId;
                    break;
            }

            Swal.fire({
                title: 'تأكيد الإجراء',
                text: `سيتم تطبيق الإجراء على ${selectedCategories.length} قسم`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'تطبيق',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkAction(action, selectedCategories, additionalData);
                }
            });
        }

        // Perform Bulk Action
        function performBulkAction(action, categoryIds, additionalData = {}) {
            showSpinner();

            let url = '';
            let method = 'POST';
            let data = {
                _token: '{{ csrf_token() }}',
                category_ids: categoryIds,
                ...additionalData
            };

            switch (action) {
                case 'activate':
                    url = '{{ route('admin.categories.bulk-activate') }}';
                    break;
                case 'deactivate':
                    url = '{{ route('admin.categories.bulk-deactivate') }}';
                    break;
                case 'move_to_parent':
                    url = '{{ route('admin.categories.bulk-move') }}';
                    break;
                case 'delete':
                    url = '{{ route('admin.categories.bulk-delete') }}';
                    method = 'DELETE';
                    break;
            }

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response) {
                    hideSpinner();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم!',
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
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                    }
                },
                error: function(xhr) {
                    hideSpinner();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء المعالجة',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }

        // Delete Category
        function deleteCategory(categoryId) {
            showSpinner();

            $.ajax({
                url: '{{ route('admin.categories.destroy', '') }}/' + categoryId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideSpinner();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم!',
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
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                    }
                },
                error: function(xhr) {
                    hideSpinner();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }

        // Duplicate Category
        function duplicateCategory(categoryId, categoryName) {
            Swal.fire({
                title: 'نسخ القسم',
                html: `
                    <div class="text-end">
                        <div class="mb-3">
                            <label class="form-label">اسم القسم الجديد</label>
                            <input type="text" id="duplicate_name" class="form-control" 
                                   value="${categoryName} (نسخة)" dir="rtl">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'نسخ',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                preConfirm: () => {
                    const name = document.getElementById('duplicate_name').value;
                    if (!name) {
                        Swal.showValidationMessage('يرجى إدخال اسم القسم');
                        return false;
                    }
                    return {
                        name
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();

                    $.ajax({
                        url: '{{ route('admin.categories.duplicate', '') }}/' + categoryId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: result.value.name
                        },
                        success: function(response) {
                            hideSpinner();
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم!',
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
                                    text: response.message,
                                    confirmButtonText: 'حسناً',
                                    confirmButtonColor: '#696cff'
                                });
                            }
                        },
                        error: function(xhr) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء النسخ',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // Edit Category
        function editCategory(categoryId) {
            showSpinner();

            $.ajax({
                url: '{{ route('admin.categories.edit', '') }}/' + categoryId,
                type: 'GET',
                success: function(response) {
                    hideSpinner();

                    // Fill form with category data
                    $('#edit_name').val(response.data.name);
                    $('#edit_slug').val(response.data.slug);
                    $('#edit_description').val(response.data.description);
                    $('#edit_parent_id').val(response.data.parent_id).trigger('change');
                    $('#edit_order').val(response.data.order);
                    $('#edit_status_id').val(response.data.status_id);

                    // Set form action
                    $('#editCategoryForm').attr('action', '{{ route('admin.categories.update', '') }}/' +
                        categoryId);

                    // Show modal
                    $('#editCategoryModal').modal('show');
                },
                error: function(xhr) {
                    hideSpinner();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'فشل في تحميل بيانات القسم',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }

        // Reorder Categories
        function reorderCategories() {
            $('#reorderModal').modal('show');
            loadCategoryTree();
        }

        // Load Category Tree
        function loadCategoryTree() {
            $('#categoryTree').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mt-2">جاري تحميل الأقسام...</p>
                </div>
            `);

            $.ajax({
                url: '{{ route('admin.categories.tree') }}',
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        renderCategoryTree(response.data);
                        $('#saveOrderBtn').show();
                    } else {
                        $('#categoryTree').html('<div class="alert alert-danger">' + (response.message ||
                            'فشل في تحميل الأقسام') + '</div>');
                        $('#saveOrderBtn').hide();
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'فشل في تحميل الأقسام';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#categoryTree').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                    $('#saveOrderBtn').hide();
                }
            });
        }

        // Render Category Tree - FIXED VERSION
        function renderCategoryTree(categories) {
            if (!categories || categories.length === 0) {
                $('#categoryTree').html('<div class="alert alert-warning">لا توجد أقسام لعرضها</div>');
                $('#saveOrderBtn').hide();
                return;
            }

            let html = '<div class="sortable-categories">';

            // Sort categories by order
            categories.sort((a, b) => (a.order || 0) - (b.order || 0));

            categories.forEach(function(category) {
                // Get category name safely
                let categoryName = category.name;
                if (typeof categoryName === 'object') {
                    // If name is translatable object, get Arabic or English
                    categoryName = categoryName.ar || categoryName.en || 'قسم';
                }

                html += `
                    <div class="category-item parent-category-item" data-id="${category.id}" data-order="${category.order || 0}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-grip-vertical handle ms-2"></i>
                                <i class="fas fa-folder text-warning ms-2"></i>
                                <span class="category-name">${categoryName}</span>
                                ${category.children_count > 0 ? 
                                    `<span class="badge bg-info ms-2">${category.children_count}</span>` : ''}
                            </div>
                            <span class="order-badge">${category.order || 0}</span>
                        </div>
                    </div>
                `;

                // Add children if exist
                if (category.children && category.children.length > 0) {
                    // Sort children by order
                    category.children.sort((a, b) => (a.order || 0) - (b.order || 0));

                    html += '<div class="children-container">';

                    category.children.forEach(function(child) {
                        // Get child name safely
                        let childName = child.name;
                        if (typeof childName === 'object') {
                            childName = childName.ar || childName.en || 'قسم فرعي';
                        }

                        html += `
                            <div class="category-item child-category-item" data-id="${child.id}" 
                                 data-order="${child.order || 0}" data-parent-id="${category.id}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-grip-vertical handle ms-2"></i>
                                        <i class="fas fa-level-up-alt fa-rotate-90 text-info ms-2"></i>
                                        <span class="category-name">${childName}</span>
                                    </div>
                                    <span class="order-badge">${child.order || 0}</span>
                                </div>
                            </div>
                        `;
                    });

                    html += '</div>';
                }
            });

            html += '</div>';
            $('#categoryTree').html(html);

            // Initialize Sortable
            initializeSortable();
        }

        // Initialize Sortable
        function initializeSortable() {
            // Parent categories sortable
            new Sortable(document.querySelector('.sortable-categories'), {
                group: 'categories',
                animation: 200,
                handle: '.handle',
                ghostClass: 'bg-light',
                draggable: '.parent-category-item',
                onEnd: function() {
                    updateOrderNumbers();
                }
            });

            // Children categories sortable
            document.querySelectorAll('.children-container').forEach(function(el) {
                new Sortable(el, {
                    group: 'children',
                    animation: 200,
                    handle: '.handle',
                    ghostClass: 'bg-light',
                    draggable: '.child-category-item',
                    onEnd: function() {
                        updateOrderNumbers();
                    }
                });
            });
        }

        // Update Order Numbers
        function updateOrderNumbers() {
            // Update parent categories order
            $('.parent-category-item').each(function(index) {
                const newOrder = index + 1;
                $(this).find('.order-badge').text(newOrder);
                $(this).data('order', newOrder);
            });

            // Update children categories order in each container
            $('.children-container').each(function() {
                $(this).find('.child-category-item').each(function(index) {
                    const newOrder = index + 1;
                    $(this).find('.order-badge').text(newOrder);
                    $(this).data('order', newOrder);
                });
            });
        }

        // Save Category Order
        function saveCategoryOrder() {
            const categories = [];

            // Get parent categories
            $('.parent-category-item').each(function(index) {
                const categoryId = $(this).data('id');
                const order = index + 1;

                categories.push({
                    id: categoryId,
                    order: order,
                    parent_id: null
                });

                // Get children of this parent
                const parentElement = $(this);
                const childrenContainer = parentElement.next('.children-container');

                if (childrenContainer.length) {
                    childrenContainer.find('.child-category-item').each(function(childIndex) {
                        const childId = $(this).data('id');
                        categories.push({
                            id: childId,
                            order: childIndex + 1,
                            parent_id: categoryId
                        });
                    });
                }
            });

            showSpinner();

            $.ajax({
                url: '{{ route('admin.categories.update-order') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    categories: categories
                },
                success: function(response) {
                    hideSpinner();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم!',
                            text: 'تم حفظ الترتيب بنجاح',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#reorderModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: response.message || 'فشل في حفظ الترتيب',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                    }
                },
                error: function(xhr) {
                    hideSpinner();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء حفظ الترتيب',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }

        // Show Spinner
        function showSpinner() {
            $('#spinnerOverlay').fadeIn();
        }

        // Hide Spinner
        function hideSpinner() {
            $('#spinnerOverlay').fadeOut();
        }

        // Export Categories
        function exportCategories() {
            const filters = new URLSearchParams(window.location.search);
            const url = '{{ route('admin.categories.export') }}?' + filters.toString();
            window.open(url, '_blank');

            Swal.fire({
                icon: 'success',
                title: 'جاري التصدير',
                text: 'سيبدأ تحميل الملف خلال لحظات',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Preview Image
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle Status
        function toggleStatus(categoryId) {
            showSpinner();

            $.ajax({
                url: "{{ url('admin/categories') }}/" + categoryId + "/toggle-status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideSpinner();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم!',
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
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#696cff'
                        });
                    }
                },
                error: function(xhr) {
                    hideSpinner();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }
    </script>
@endsection
