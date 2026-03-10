@extends('Admin.layout.master')

@section('title', 'إدارة التواصل')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --text-muted: #6c757d;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .contact-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .contact-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
        }

        .stats-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border-top: 4px solid var(--primary-color);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .icon-total {
            background: var(--primary-gradient);
            color: white;
        }

        .icon-pending {
            background: rgba(133, 100, 4, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .icon-replied {
            background: rgba(12, 99, 228, 0.2);
            color: #0c63e4;
            border: 1px solid rgba(12, 99, 228, 0.3);
        }

        .icon-archived {
            background: rgba(56, 61, 65, 0.2);
            color: #adb5bd;
            border: 1px solid rgba(173, 181, 189, 0.3);
        }

        .stats-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #fff;
        }

        .stats-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .status-pending {
            background: rgba(133, 100, 4, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-read {
            background: rgba(0, 64, 133, 0.2);
            color: #0dcaf0;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .status-replied {
            background: linear-gradient(135deg, rgba(21, 87, 36, 0.2) 0%, rgba(32, 201, 151, 0.2) 100%);
            color: #20c997;
            border: 1px solid rgba(32, 201, 151, 0.3);
        }

        .status-archived {
            background: rgba(56, 61, 65, 0.2);
            color: #adb5bd;
            border: 1px solid rgba(173, 181, 189, 0.3);
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 40px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .search-box input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
        }

        .message-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border-right: 4px solid transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
        }

        .message-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
        }

        .message-item.pending {
            border-right-color: #ffc107;
        }

        .message-item.read {
            border-right-color: #0dcaf0;
        }

        .message-item.replied {
            border-right-color: #20c997;
        }

        .message-item.archived {
            border-right-color: #adb5bd;
            opacity: 0.7;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sender-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sender-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .sender-details h6 {
            margin: 0;
            color: #fff;
            font-weight: 600;
        }

        .sender-details small {
            color: rgba(255, 255, 255, 0.7);
        }

        .message-content {
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        .message-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message-meta {
            display: flex;
            gap: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
        }

        .message-actions {
            display: flex;
            gap: 10px;
        }

        .bulk-actions {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;
        }

        .bulk-actions.show {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .select-all {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .status-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .status-filter-btn {
            padding: 8px 20px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .status-filter-btn:hover,
        .status-filter-btn.active {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
        }

        .sort-dropdown {
            position: relative;
            display: inline-block;
        }

        .sort-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            padding: 8px 15px;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sort-dropdown-content {
            display: none;
            position: absolute;
            background: var(--dark-card);
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            z-index: 1;
            padding: 10px 0;
            margin-top: 5px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sort-dropdown:hover .sort-dropdown-content {
            display: block;
        }

        .sort-item {
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.3s;
            color: rgba(255, 255, 255, 0.8);
        }

        .sort-item:hover,
        .sort-item.active {
            background: rgba(105, 108, 255, 0.1);
            color: #fff;
        }

        .checkbox-message {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
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
                <li class="breadcrumb-item active">رسائل التواصل</li>
            </ol>
        </nav>

        <!-- الإحصائيات -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-total">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stats-number">
                        {{ number_format($stats['total']) }}
                    </div>
                    <div class="stats-label">إجمالي الرسائل</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number">
                        {{ number_format($stats['pending']) }}
                    </div>
                    <div class="stats-label">في انتظار الرد</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-replied">
                        <i class="fas fa-reply"></i>
                    </div>
                    <div class="stats-number">
                        {{ number_format($stats['replied']) }}
                    </div>
                    <div class="stats-label">تم الرد عليها</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-archived">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="stats-number">
                        {{ number_format($stats['archived']) }}
                    </div>
                    <div class="stats-label">مؤرشفة</div>
                </div>
            </div>
        </div>

        <!-- فلترة حسب الحالة -->
        <div class="status-filter">
            <button class="status-filter-btn {{ !request('status') || request('status') == 'all' ? 'active' : '' }}"
                onclick="filterByStatus('all')">
                جميع الرسائل
            </button>
            <button class="status-filter-btn {{ request('status') == 'pending' ? 'active' : '' }}"
                onclick="filterByStatus('pending')">
                <i class="fas fa-clock me-2"></i>في انتظار الرد
            </button>
            <button class="status-filter-btn {{ request('status') == 'read' ? 'active' : '' }}"
                onclick="filterByStatus('read')">
                <i class="fas fa-eye me-2"></i>مقروءة
            </button>
            <button class="status-filter-btn {{ request('status') == 'replied' ? 'active' : '' }}"
                onclick="filterByStatus('replied')">
                <i class="fas fa-reply me-2"></i>تم الرد
            </button>
            <button class="status-filter-btn {{ request('status') == 'archived' ? 'active' : '' }}"
                onclick="filterByStatus('archived')">
                <i class="fas fa-archive me-2"></i>مؤرشفة
            </button>
        </div>

        <!-- فلترة متقدمة -->
        <div class="filter-card">
            <h6 class="mb-3"><i class="fas fa-filter me-2"></i>فلترة متقدمة</h6>

            <div class="filter-row">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="بحث بالاسم، البريد، الهاتف، الشركة..."
                        id="searchInput" value="{{ request('search') }}">
                </div>

                <div class="sort-dropdown">
                    <button class="sort-btn">
                        <i class="fas fa-sort-amount-down"></i>
                        الترتيب حسب
                    </button>
                    <div class="sort-dropdown-content">
                        <div class="sort-item {{ request('sort_by') == 'created_at' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                            onclick="sortBy('created_at', 'desc')">
                            الأحدث أولاً
                        </div>
                        <div class="sort-item {{ request('sort_by') == 'created_at' && request('sort_direction') == 'asc' ? 'active' : '' }}"
                            onclick="sortBy('created_at', 'asc')">
                            الأقدم أولاً
                        </div>
                        <div class="sort-item {{ request('sort_by') == 'first_name' && request('sort_direction') == 'asc' ? 'active' : '' }}"
                            onclick="sortBy('first_name', 'asc')">
                            الاسم (أ-ي)
                        </div>
                        <div class="sort-item {{ request('sort_by') == 'first_name' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                            onclick="sortBy('first_name', 'desc')">
                            الاسم (ي-أ)
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <input type="date" class="form-control" id="dateFrom" placeholder="من تاريخ"
                        value="{{ request('date_from') }}">
                    <span class="input-group-text">إلى</span>
                    <input type="date" class="form-control" id="dateTo" placeholder="إلى تاريخ"
                        value="{{ request('date_to') }}">
                </div>
            </div>

            <div class="filter-row">
                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="fas fa-filter me-2"></i>تطبيق الفلاتر
                </button>
                <button class="btn btn-outline-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo me-2"></i>إعادة تعيين
                </button>
            </div>
        </div>

        <!-- الإجراءات الجماعية -->
        <div class="bulk-actions" id="bulkActions">
            <div class="select-all">
                <input type="checkbox" id="selectAll" class="checkbox-message">
                <label for="selectAll">تحديد الكل</label>
            </div>
            <span id="selectedCount" class="text-muted">0 رسالة محددة</span>
            <div class="btn-group">
                <button class="btn btn-sm btn-primary" onclick="bulkStatus('read')">
                    <i class="fas fa-eye"></i> تحديد كمقروء
                </button>
                <button class="btn btn-sm btn-info" onclick="bulkStatus('replied')">
                    <i class="fas fa-reply"></i> تحديد كتم الرد
                </button>
                <button class="btn btn-sm btn-secondary" onclick="bulkStatus('archived')">
                    <i class="fas fa-archive"></i> أرشفة
                </button>
                <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                    <i class="fas fa-trash"></i> حذف المحدد
                </button>
            </div>
        </div>

        <!-- قائمة الرسائل -->
        <div class="contact-card">
            <div class="contact-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">قائمة الرسائل</h5>
                        <small class="opacity-75">إدارة رسائل التواصل مع العملاء</small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if ($messages->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <h5 class="empty-state-text">لا توجد رسائل</h5>
                        <p class="text-muted">لم يتم استلام أي رسائل حتى الآن</p>
                    </div>
                @else
                    @foreach ($messages as $message)
                        <div class="message-item {{ $message->status }}" data-id="{{ $message->id }}">
                            <div class="message-header">
                                <div class="sender-info">
                                    <input type="checkbox" class="checkbox-message message-checkbox"
                                        value="{{ $message->id }}" onclick="event.stopPropagation()">
                                    <div class="sender-avatar">
                                        {{ strtoupper(substr($message->first_name, 0, 1)) }}{{ strtoupper(substr($message->last_name, 0, 1)) }}
                                    </div>
                                    <div class="sender-details">
                                        <h6>{{ $message->first_name }} {{ $message->last_name }}
                                            @if ($message->user)
                                                <small class="text-primary">(مسجل)</small>
                                            @endif
                                        </h6>
                                        <small>
                                            <i class="fas fa-envelope me-1"></i>{{ $message->email }}
                                            @if ($message->phone)
                                                <i class="fas fa-phone ms-2 me-1"></i>{{ $message->phone }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge-status status-{{ $message->status }}">
                                        @switch($message->status)
                                            @case('pending')
                                                في انتظار الرد
                                            @break

                                            @case('read')
                                                تمت المشاهدة
                                            @break

                                            @case('replied')
                                                تم الرد
                                            @break

                                            @case('archived')
                                                مؤرشفة
                                            @break

                                            @default
                                                {{ $message->status }}
                                        @endswitch
                                    </span>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $message->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>

                            @if ($message->company)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $message->company }}
                                    </small>
                                </div>
                            @endif

                            <div class="message-content">
                                {{ Str::limit($message->message, 200) }}
                            </div>

                            <div class="message-footer">
                                <div class="message-meta">
                                    @if ($message->lastReply)
                                        <span>
                                            <i class="fas fa-reply me-1"></i>
                                            آخر رد: {{ $message->lastReply->created_at->diffForHumans() }}
                                        </span>
                                    @endif
                                    @if ($message->replies_count > 0)
                                        <span>
                                            <i class="fas fa-comments me-1"></i>
                                            {{ $message->replies_count }} رد
                                        </span>
                                    @endif
                                </div>
                                <div class="message-actions">
                                    <a href="{{ route('admin.contact-us.show', $message->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>عرض
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $message->id }}" data-name="رسالة من {{ $message->first_name }}"
                                        onclick="event.stopPropagation()">
                                        <i class="fas fa-trash me-1"></i>حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- الترقيم الصفحي -->
                    @if ($messages->hasPages())
                        <div class="m-3">
                            <nav>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($messages->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link waves-effect" href="{{ $messages->previousPageUrl() }}"
                                                rel="prev">‹</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($messages->links()->elements[0] as $page => $url)
                                        @if ($page == $messages->currentPage())
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
                                    @if ($messages->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link waves-effect" href="{{ $messages->nextPageUrl() }}"
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
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // البحث مع تأخير
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });

            // النقر على الرسالة للذهاب إلى صفحة التفاصيل
            $('.message-item').on('click', function(e) {
                if (!$(e.target).closest('.message-actions, .checkbox-message').length) {
                    window.location.href = "{{ route('admin.contact-us.show', '') }}/" + $(this).data(
                        'id');
                }
            });

            // حذف الرسالة
            $('.delete-btn').on('click', function(e) {
                e.stopPropagation();
                const messageId = $(this).data('id');
                const messageName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف "${messageName}" نهائياً`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.contact-us.destroy', '') }}/" +
                                messageId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: 'حدث خطأ أثناء الحذف',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });

            // تحديد الكل
            $('#selectAll').on('change', function() {
                $('.message-checkbox').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            // تحديث عدد المحدد عند تغيير أي checkbox
            $(document).on('change', '.message-checkbox', function() {
                const allChecked = $('.message-checkbox:checked').length === $('.message-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
                updateSelectedCount();
            });

            // إظهار/إخفاء الإجراءات الجماعية عند وجود تحديد
            function updateSelectedCount() {
                const count = $('.message-checkbox:checked').length;
                $('#selectedCount').text(count + ' رسالة محددة');
                if (count > 0) {
                    $('#bulkActions').addClass('show');
                } else {
                    $('#bulkActions').removeClass('show');
                }
            }

            // رسائل التنبيه من الجلسة
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: "{{ session('error') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });

        // فلترة حسب الحالة
        function filterByStatus(status) {
            if (status === 'all') {
                updateUrl({
                    status: null
                });
            } else {
                updateUrl({
                    status: status
                });
            }
        }

        // ترتيب
        function sortBy(sortBy, sortDirection) {
            updateUrl({
                sort_by: sortBy,
                sort_direction: sortDirection
            });
        }

        // تطبيق الفلاتر
        function applyFilters() {
            const params = {
                search: $('#searchInput').val(),
                date_from: $('#dateFrom').val(),
                date_to: $('#dateTo').val()
            };

            updateUrl(params);
        }
        // إعادة تعيين الفلاتر
        function resetFilters() {
            $('#searchInput').val('');
            $('#dateFrom').val('');
            $('#dateTo').val('');

            window.location.href = "{{ route('admin.contact-us.index') }}";
        }

        // تحديث URL مع الباراميترات
        function updateUrl(params) {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);

            Object.keys(params).forEach(key => {
                if (params[key] === null || params[key] === '') {
                    searchParams.delete(key);
                } else {
                    searchParams.set(key, params[key]);
                }
            });

            searchParams.set('page', '1');
            url.search = searchParams.toString();
            window.location.href = url.toString();
        }

        // تحديث حالة مجموعة من الرسائل
        function bulkStatus(status) {
            const ids = $('.message-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            let statusText = '';
            switch (status) {
                case 'read':
                    statusText = 'كمقروءة';
                    break;
                case 'replied':
                    statusText = 'كتم الرد عليها';
                    break;
                case 'archived':
                    statusText = 'كأرشيف';
                    break;
            }

            Swal.fire({
                title: 'تأكيد التحديث',
                text: `هل أنت متأكد من تحديث ${ids.length} رسالة ${statusText}؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، تحديث',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.contact-us.bulk-status') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: ids,
                            status: status
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم التحديث',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء التحديث',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        // حذف مجموعة من الرسائل
        function bulkDelete() {
            const ids = $('.message-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            Swal.fire({
                title: 'تأكيد الحذف',
                text: `هل أنت متأكد من حذف ${ids.length} رسالة نهائياً؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.contact-us.bulk-destroy') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: ids
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء الحذف',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
