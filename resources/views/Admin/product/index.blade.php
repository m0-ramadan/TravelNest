@extends('Admin.layout.master')

@section('title', 'إدارة المنتجات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .product-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .product-image {
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .badge-new {
            background: #ff6b6b;
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 5px;
        }

        .badge-discount {
            background: #28a745;
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 5px;
        }

        .badge-out-of-stock {
            background: #6c757d;
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 5px;
        }

        .badge-low-stock {
            background: #ffc107;
            color: #212529;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 5px;
        }

        .product-content {
            padding: 15px;
        }

        .product-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
            line-height: 1.4;
            height: 45px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-category {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 8px;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .current-price {
            font-size: 18px;
            font-weight: 700;
            color: #2ecc71;
        }

        .old-price {
            font-size: 14px;
            color: #95a5a6;
            text-decoration: line-through;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 12px;
        }

        .product-stock {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stock-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .stock-indicator.in-stock {
            background-color: #2ecc71;
        }

        .stock-indicator.low-stock {
            background-color: #f39c12;
        }

        .stock-indicator.out-of-stock {
            background-color: #e74c3c;
        }

        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        .product-actions .btn {
            flex: 1;
            padding: 5px 10px;
            font-size: 12px;
        }

        /* DataTable Custom Styles */
        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_length select {
            padding: 4px 8px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .dataTables_filter input {
            padding: 4px 8px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .dt-buttons .btn {
            padding: 5px 10px;
            font-size: 13px;
            margin-right: 5px;
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
        }

        .view-toggle-btn {
            padding: 8px 15px;
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

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border-radius: 5px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-draft {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Bulk Actions */
        .bulk-actions-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;
        }

        .bulk-actions-container.show {
            display: block;
        }

        .bulk-action-select {
            max-width: 200px;
            display: inline-block;
            margin-left: 10px;
        }

        /* Advanced Filters */
        .advanced-filters {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            display: none;
        }

        .advanced-filters.show {
            display: block;
        }

        .filter-section {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
        }

        .filter-section-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        /* Product Table */
        .product-table-image {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            object-fit: cover;
        }

        .product-table-name {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .view-toggle {
                margin-top: 10px;
            }

            .quick-actions {
                margin-top: 10px;
            }

            .product-table-image {
                width: 40px;
                height: 40px;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 60px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
            margin-bottom: 20px;
        }

        .btn-outline-primary.active {
            background-color: #0d6efd !important;
            color: #fff !important;
        }

        .btn-outline-warning.active {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .btn-outline-danger.active {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        .btn-outline-secondary.active {
            background-color: #6c757d !important;
            color: #fff !important;
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
                <li class="breadcrumb-item active">المنتجات</li>
            </ol>
        </nav>

        <!-- Header Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <!-- Search Form -->
                            <form method="GET" action="{{ route('admin.products.index') }}" id="searchForm"
                                class="d-flex">
                                <div class="position-relative" style="min-width: 300px;">
                                    <input type="text" class="form-control" name="search" id="globalSearch"
                                        placeholder="بحث في المنتجات..." value="{{ request('search') }}">
                                    <i class="fas fa-search position-absolute"
                                        style="left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>

                                    @if (request('search'))
                                        <button type="button" id="clearSearch" class="btn position-absolute"
                                            style="right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none;">
                                            <i class="fas fa-times text-muted"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Hidden inputs to preserve other filters -->
                                @if (request('category_id'))
                                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                @endif
                                @if (request('status_id'))
                                    <input type="hidden" name="status_id" value="{{ request('status_id') }}">
                                @endif
                                @if (request('price_from'))
                                    <input type="hidden" name="price_from" value="{{ request('price_from') }}">
                                @endif
                                @if (request('price_to'))
                                    <input type="hidden" name="price_to" value="{{ request('price_to') }}">
                                @endif
                                @if (request('stock_from'))
                                    <input type="hidden" name="stock_from" value="{{ request('stock_from') }}">
                                @endif
                                @if (request('stock_to'))
                                    <input type="hidden" name="stock_to" value="{{ request('stock_to') }}">
                                @endif
                            </form>

                            <!-- Quick Filters -->
                            <div class="quick-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="applyFilter('status_id', '1')">
                                    <i class="fas fa-check-circle"></i> النشطة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm"
                                    onclick="applyFilter('has_discount', '1')">
                                    <i class="fas fa-percentage"></i> ذات الخصم
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    onclick="applyFilter('stock', 'low')">
                                    <i class="fas fa-exclamation-triangle"></i> منخفضة المخزون
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                    <i class="fas fa-filter-circle-xmark"></i> إعادة التعيين
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <!-- View Toggle -->
                            <div class="view-toggle">
                                <button class="view-toggle-btn active" onclick="toggleView('grid')">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button class="view-toggle-btn" onclick="toggleView('table')">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>

                            <!-- Add Product Button -->
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> إضافة منتج
                            </a>

                            <!-- More Actions -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="toggleAdvancedFilters()">
                                            <i class="fas fa-filter me-2"></i> فلاتر متقدمة
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportProducts()">
                                            <i class="fas fa-file-export me-2"></i> تصدير البيانات
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="showBulkActions()">
                                            <i class="fas fa-layer-group me-2"></i> إجراءات جماعية
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="advanced-filters mt-4" id="advancedFilters">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="filter-section">
                                    <h6 class="filter-section-title">التصنيفات</h6>
                                    <select class="form-select select2" id="categoryFilter" name="category_id">
                                        <option value="">جميع التصنيفات</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="filter-section">
                                    <h6 class="filter-section-title">الحالة</h6>
                                    <select class="form-select" id="statusFilter" name="status_id">
                                        <option value="">جميع الحالات</option>
                                        <option value="1">نشط</option>
                                        <option value="2">غير نشط</option>
                                        <option value="3">مسودة</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="filter-section">
                                    <h6 class="filter-section-title">نطاق السعر</h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="form-control" placeholder="من"
                                                name="price_from" id="priceFrom">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control" placeholder="إلى" name="price_to"
                                                id="priceTo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="filter-section">
                                    <h6 class="filter-section-title">نطاق المخزون</h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="form-control" placeholder="من"
                                                name="stock_from" id="stockFrom">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control" placeholder="إلى" name="stock_to"
                                                id="stockTo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearAdvancedFilters()">
                                <i class="fas fa-redo me-1"></i> إعادة تعيين الفلاتر
                            </button>
                            <button type="button" class="btn btn-primary" onclick="applyAdvancedFilters()">
                                <i class="fas fa-filter me-1"></i> تطبيق الفلاتر
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bulk Actions -->
                <div class="bulk-actions-container" id="bulkActions">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="selectAllBulk">
                                <label class="form-check-label" for="selectAllBulk">
                                    تم تحديد <span id="selectedCount">0</span> منتج
                                </label>
                            </div>
                            <select class="form-select bulk-action-select" id="bulkActionSelect">
                                <option value="">اختر إجراء...</option>
                                <option value="activate">تفعيل</option>
                                <option value="deactivate">تعطيل</option>
                                <option value="move_to_category">نقل إلى تصنيف</option>
                                <option value="delete">حذف</option>
                            </select>
                            <button type="button" class="btn btn-primary ms-2" onclick="applyBulkAction()">
                                تطبيق
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="hideBulkActions()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Additional options for certain bulk actions -->
                    <div id="bulkActionOptions" class="mt-3" style="display: none;">
                        <div id="categoryOptions" class="row g-3" style="display: none;">
                            <div class="col-md-6">
                                <select class="form-select" id="bulkCategorySelect">
                                    <option value="">اختر التصنيف</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="view-container">
            @if ($products->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4" id="productsGrid">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="product-card" data-product-id="{{ $product->id }}">
                                <div class="product-image">
                                    <img src="{{ $product->image_path ? asset('storage/'.$product->image_path) : asset('storage/products/default-product.png') }}"
                                        alt="{{ $product->name }}">

                                    <div class="product-badges">
                                        @if ($product->created_at->gt(now()->subDays(7)))
                                            <span class="badge-new">جديد</span>
                                        @endif
                                        @if ($product->has_discount)
                                            <span class="badge-discount">خصم</span>
                                        @endif
                                        @if ($product->stock == 0)
                                            <span class="badge-out-of-stock">نفذ من المخزون</span>
                                        @elseif($product->stock < 10)
                                            <span class="badge-low-stock">مخزون منخفض</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="product-content">
                                    <h6 class="product-title" title="{{ $product->name }}">
                                        {{ Str::limit($product->name, 50) }}
                                    </h6>

                                    <div class="product-category">
                                        <i class="fas fa-folder me-1"></i>
                                        {{ $product->category->name ?? 'غير مصنف' }}
                                    </div>

                                    <div class="product-price">
                                        <span class="current-price">
                                            {{ number_format($product->price, 2) }} {{ $product->price_text ?? 'ج.م' }}
                                        </span>
                                        @if ($product->has_discount && $product->discount)
                                            <span class="old-price">
                                                {{ number_format($product->price, 2) }} {{ $product->price_text ?? 'ج.م' }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="product-meta">
                                        <div class="product-stock">
                                            <span
                                                class="stock-indicator {{ $product->stock == 0 ? 'out-of-stock' : ($product->stock < 10 ? 'low-stock' : 'in-stock') }}"></span>
                                            {{ $product->stock ?? 0 }} قطعة
                                        </div>
                                    </div>

                                    <div class="product-actions">
                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                            class="btn btn-outline-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-outline-primary btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                            onclick="duplicateProduct({{ $product->id }})" title="نسخ">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-product"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <div class="form-check" style="padding-top: 5px;">
                                            <input class="form-check-input product-checkbox" type="checkbox"
                                                value="{{ $product->id }}" id="product_{{ $product->id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="m-3">
                        <nav>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($products->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link waves-effect" href="{{ $products->previousPageUrl() }}"
                                            rel="prev">‹</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($products->links()->elements[0] as $page => $url)
                                    @if ($page == $products->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link waves-effect">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link waves-effect"
                                                href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($products->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link waves-effect" href="{{ $products->nextPageUrl() }}"
                                            rel="next">›</a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link waves-effect" aria-hidden="true">›</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h5>لا توجد منتجات</h5>
                    <p>ابدأ بإضافة منتجات جديدة إلى متجرك</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> إضافة منتج جديد
                    </a>
                </div>
            @endif
        </div>

        <!-- Table View -->
        <div id="tableView" class="view-container" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="productsTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllTable">
                                        </div>
                                    </th>
                                    <th width="80">الصورة</th>
                                    <th>المنتج</th>
                                    <th>التصنيف</th>
                                    <th>السعر</th>
                                    <th>المخزون</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr data-product-id="{{ $product->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input product-checkbox" type="checkbox"
                                                    value="{{ $product->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{ $product->image_path ? asset('storage/'.$product->image_path) : asset('storage/products/default-product.png') }}"
                                                alt="{{ $product->name }}" class="product-table-image">
                                        </td>
                                        <td>
                                            <div class="product-table-name" title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </div>
                                            <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary">
                                                {{ $product->category->name ?? 'غير مصنف' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="text-success">{{ number_format($product->price, 2) }}
                                                    {{ $product->price_text ?? 'ج.م' }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="stock-indicator {{ $product->stock == 0 ? 'out-of-stock' : ($product->stock < 10 ? 'low-stock' : 'in-stock') }}"></span>
                                                {{ $product->stock ?? 0 }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($product->status_id == 1)
                                                <span class="status-badge status-active">نشط</span>
                                            @elseif($product->status_id == 2)
                                                <span class="status-badge status-inactive">غير نشط</span>
                                            @else
                                                <span class="status-badge status-draft">مسودة</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->created_at->format('Y/m/d') }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="btn btn-sm btn-outline-primary" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="duplicateProduct({{ $product->id }})" title="نسخ">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-product"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="m-3">
                            <nav>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link waves-effect" href="{{ $products->previousPageUrl() }}"
                                                rel="prev">‹</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($products->links()->elements[0] as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link waves-effect">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link waves-effect"
                                                    href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($products->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link waves-effect" href="{{ $products->nextPageUrl() }}"
                                                rel="next">›</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link waves-effect" aria-hidden="true">›</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Edit Modal -->
    <div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل سريع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickEditForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">السعر</label>
                                <input type="number" class="form-control" name="price" step="0.01">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المخزون</label>
                                <input type="number" class="form-control" name="stock" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الحالة</label>
                                <select class="form-select" name="status_id">
                                    <option value="1">نشط</option>
                                    <option value="2">غير نشط</option>
                                    <option value="3">مسودة</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">التصنيف</label>
                                <select class="form-select" name="category_id">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="saveQuickEdit()">حفظ</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'اختر',
                allowClear: true
            });

            // Initialize DataTable
            $('#productsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                },
                responsive: true,
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                dom: '<"row"<"col-sm-12"tr>>',
            });

            // Search functionality
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            $('#globalSearch').on('keyup', debounce(function() {
                const searchValue = $(this).val().trim();

                if (searchValue === '') {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('search');
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                    return;
                }

                $('#searchForm').submit();
            }, 500));

            // Clear search
            $(document).on('click', '#clearSearch', function() {
                $('#globalSearch').val('');
                $('#searchForm').submit();
            });

            // Product checkbox selection
            $(document).on('change', '.product-checkbox', function() {
                updateSelectedCount();
            });

            // Select all checkboxes
            $('#selectAllBulk, #selectAllTable').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.product-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // Delete product
            $(document).on('click', '.delete-product', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف المنتج "${productName}" بشكل دائم`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteProduct(productId);
                    }
                });
            });

            // View toggle buttons
            $('.view-toggle-btn').on('click', function() {
                const viewType = $(this).has('.fa-th-large').length ? 'grid' : 'table';
                toggleView(viewType);
            });

            // Fill search input with current search value
            @if (request('search'))
                $('#globalSearch').val('{{ request('search') }}');
            @endif
        });

        // View Toggle Function
        window.toggleView = function(viewType) {
            $('.view-toggle-btn').removeClass('active');
            $('.view-container').hide();

            if (viewType === 'grid') {
                $('#gridView').show();
                $('.view-toggle-btn:first').addClass('active');
            } else {
                $('#tableView').show();
                $('.view-toggle-btn:last').addClass('active');
                $('#productsTable').DataTable().columns.adjust().responsive.recalc();
            }
        }

        // Quick Filters Function
        window.applyFilter = function(filter, value) {
            const url = new URL(window.location.href);
            url.searchParams.delete('page');

            if (value === 'low') {
                url.searchParams.set('stock_from', '1');
                url.searchParams.set('stock_to', '10');
                url.searchParams.delete('stock');
            } else {
                url.searchParams.set(filter, value);
            }

            window.location.href = url.toString();
        }

        window.clearFilters = function() {
            const searchValue = $('#globalSearch').val();
            let url = '{{ route('admin.products.index') }}';

            if (searchValue) {
                url += '?search=' + encodeURIComponent(searchValue);
            }

            window.location.href = url;
        }

        // Advanced Filters Functions
        window.toggleAdvancedFilters = function() {
            $('#advancedFilters').toggleClass('show');
        }

        window.clearAdvancedFilters = function() {
            $('#filterForm')[0].reset();
            $('.select2').val(null).trigger('change');
        }

        window.applyAdvancedFilters = function() {
            const formData = new FormData($('#filterForm')[0]);
            const params = new URLSearchParams();

            // Add current search if exists
            const searchValue = $('#globalSearch').val();
            if (searchValue) {
                params.append('search', searchValue);
            }

            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            window.location.href = '{{ route('admin.products.index') }}?' + params.toString();
        }

        // Bulk Actions Functions
        window.showBulkActions = function() {
            $('#bulkActions').addClass('show');
        }

        window.hideBulkActions = function() {
            $('#bulkActions').removeClass('show');
            $('.product-checkbox').prop('checked', false);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const selectedCount = $('.product-checkbox:checked').length;
            $('#selectedCount').text(selectedCount);

            if (selectedCount > 0) {
                $('#bulkActions').addClass('show');
            } else {
                $('#bulkActions').removeClass('show');
            }
        }

        $(document).on('change', '#bulkActionSelect', function() {
            const action = $(this).val();
            $('#bulkActionOptions').hide().find('> div').hide();

            if (action === 'move_to_category') {
                $('#bulkActionOptions').show();
                $('#categoryOptions').show();
            }
        });

        window.applyBulkAction = function() {
            const action = $('#bulkActionSelect').val();
            const selectedProducts = [];

            $('.product-checkbox:checked').each(function() {
                selectedProducts.push($(this).val());
            });

            if (selectedProducts.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'لم يتم الاختيار',
                    text: 'يرجى اختيار منتجات على الأقل',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            if (!action) {
                Swal.fire({
                    icon: 'warning',
                    title: 'لم يتم اختيار إجراء',
                    text: 'يرجى اختيار الإجراء المطلوب',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            let additionalData = {};

            switch (action) {
                case 'move_to_category':
                    const categoryId = $('#bulkCategorySelect').val();
                    if (!categoryId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تصنيف مطلوب',
                            text: 'يرجى اختيار التصنيف',
                            confirmButtonText: 'حسناً'
                        });
                        return;
                    }
                    additionalData.category_id = categoryId;
                    break;
            }

            Swal.fire({
                title: 'تأكيد الإجراء',
                text: `سيتم تطبيق الإجراء على ${selectedProducts.length} منتج`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'تطبيق',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkAction(action, selectedProducts, additionalData);
                }
            });
        }

        function performBulkAction(action, productIds, additionalData = {}) {
            $.ajax({
                url: '{{ route('admin.products.bulk-action') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    product_ids: productIds,
                    ...additionalData
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'جاري المعالجة...',
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
                            title: 'تم بنجاح!',
                            text: response.message,
                            timer: 1500,
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
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء المعالجة'
                    });
                }
            });
        }

        // Product Operations Functions
        function deleteProduct(productId) {
            $.ajax({
                url: `/admin/products/${productId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف!',
                            text: 'تم حذف المنتج بنجاح',
                            timer: 1500,
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

        window.duplicateProduct = function(productId) {
            Swal.fire({
                title: 'نسخ المنتج',
                input: 'text',
                inputLabel: 'أدخل اسم للمنتج المنسوخ:',
                showCancelButton: true,
                confirmButtonText: 'نسخ',
                cancelButtonText: 'إلغاء',
                showLoaderOnConfirm: true,
                preConfirm: (name) => {
                    if (!name) {
                        Swal.showValidationMessage('يجب إدخال اسم للمنتج');
                        return false;
                    }

                    return fetch(`/admin/products/${productId}/duplicate`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                name: name
                             })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'حدث خطأ أثناء النسخ');
                            }
                            return data;
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'تم النسخ!',
                        text: 'تم نسخ المنتج بنجاح',
                        icon: 'success',
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        window.location.href = '/admin/products/' + result.value.data.id + '/edit';
                    });
                }
            });
        }
        // Export Function
        window.exportProducts = function() {
            const filters = new URLSearchParams(window.location.search);
            let url = '{{ route('admin.products.export') }}?';
            url += `&${filters.toString()}`;
            
            window.open(url, '_blank');
        }
    </script>
@endsection