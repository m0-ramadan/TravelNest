@extends('Admin.layout.master')

@section('title', 'تعيين الرتب للمستخدمين')

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

        .assign-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .user-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-right: 4px solid transparent;
        }

        .user-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            font-weight: bold;
        }

        .role-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
            margin: 3px;
        }

        .badge-super_admin {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(253, 126, 20, 0.2) 100%);
            color: #fd7e14;
            border: 1px solid rgba(253, 126, 20, 0.3);
        }

        .badge-admin {
            background: rgba(133, 100, 4, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .badge-editor {
            background: rgba(12, 84, 96, 0.2);
            color: #0dcaf0;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .badge-viewer {
            background: linear-gradient(135deg, rgba(21, 87, 36, 0.2) 0%, rgba(32, 201, 151, 0.2) 100%);
            color: #20c997;
            border: 1px solid rgba(32, 201, 151, 0.3);
        }

        .role-checkbox {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            margin: 5px 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .role-checkbox:hover {
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
        }

        .role-checkbox input[type="checkbox"] {
            margin-left: 10px;
            transform: scale(1.2);
            accent-color: var(--primary-color);
        }

        .role-checkbox label {
            cursor: pointer;
            color: #fff;
            font-weight: 500;
        }

        .assign-btn {
            background: var(--primary-gradient);
            border: none;
            padding: 8px 25px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .assign-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
            color: white;
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

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
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

        .modal-content {
            background: var(--dark-card);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .pagination {
            gap: 5px;
        }

        .page-link {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
        }

        .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.02);
            color: rgba(255, 255, 255, 0.3);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #fff;
        }

        .user-email {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 5px;
        }

        .user-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 768px) {
            .user-item {
                padding: 15px;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
            }

            .user-meta {
                justify-content: center;
                flex-wrap: wrap;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 15px;
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
                    <a href="{{ route('admin.roles.index') }}">الرتب</a>
                </li>
                <li class="breadcrumb-item active">تعيين الرتب</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="assign-card">
                    <!-- رأس الصفحة -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">تعيين الرتب للمستخدمين</h5>
                            <small class="opacity-75">إدارة صلاحيات المستخدمين عن طريق تعيين الرتب المناسبة</small>
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" placeholder="بحث عن مستخدم..." id="searchInput"
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- محتوى الصفحة -->
                    @if ($admins->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="empty-state-text">لا يوجد مستخدمين</h5>
                            <p class="text-muted">لم يتم إضافة أي مستخدمين حتى الآن</p>
                            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>إضافة مستخدم جديد
                            </a>
                        </div>
                    @else
                        <!-- قائمة المستخدمين -->
                        @foreach ($admins as $admin)
                            <div class="user-item">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                                            </div>
                                            <div class="user-details">
                                                <div class="user-name">{{ $admin->name }}</div>
                                                <div class="user-email">{{ $admin->email }}</div>
                                                <div class="user-meta">
                                                    <span>
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $admin->created_at->translatedFormat('d M Y') }}
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-shield-alt me-1"></i>
                                                        {{ $admin->roles->count() }} رتبة
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($admin->roles as $role)
                                                <span class="role-badge badge-{{ $role->name }}">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ $role->display_name ?: $role->name }}
                                                </span>
                                            @endforeach
                                            @if ($admin->roles->isEmpty())
                                                <span class="text-muted">لا يوجد رتب</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-primary assign-btn" data-bs-toggle="modal"
                                            data-bs-target="#assignModal" data-admin-id="{{ $admin->id }}"
                                            data-admin-name="{{ $admin->name }}"
                                            data-admin-roles="{{ $admin->roles->pluck('id')->join(',') }}">
                                            <i class="fas fa-edit me-2"></i>تعديل الرتب
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- روابط التصفح -->
                        @if ($admins->hasPages())
                            <div class="mt-4">
                                <nav>
                                    <ul class="pagination justify-content-center">
                                        {{-- Previous Page Link --}}
                                        @if ($admins->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true">
                                                <span class="page-link" aria-hidden="true">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $admins->previousPageUrl() }}"
                                                    rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($admins->links()->elements[0] as $page => $url)
                                            @if ($page == $admins->currentPage())
                                                <li class="page-item active" aria-current="page">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($admins->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $admins->nextPageUrl() }}"
                                                    rel="next">›</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled" aria-disabled="true">
                                                <span class="page-link" aria-hidden="true">›</span>
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
    </div>

    <!-- Modal تعيين الرتب -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">تعيين الرتب للمستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.roles.assign.store') }}" method="POST" id="assignForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6 class="mb-3">المستخدم: <span id="modalUserName" class="text-primary"></span></h6>
                            <input type="hidden" name="admin_id" id="modalAdminId">

                            <div class="alert alert-info bg-opacity-10"
                                style="background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.2); color: #fff;">
                                <i class="fas fa-info-circle me-2"></i>
                                يمكنك اختيار رتبة واحدة أو أكثر للمستخدم. الصلاحيات ستتحدد بناءً على الرتب المختارة.
                            </div>
                        </div>

                        <div class="row">
                            @foreach ($roles as $role)
                                <div class="col-md-6">
                                    <div class="role-checkbox">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                @if (in_array($role->name, ['super_admin', 'admin']) && $role->name == 'super_admin') data-protected="true" @endif>
                                            <label class="form-check-label d-block" for="role_{{ $role->id }}">
                                                <span class="role-badge badge-{{ $role->name }} mb-2 d-inline-block">
                                                    {{ $role->display_name ?: $role->name }}
                                                </span>
                                                <small class="d-block text-muted">
                                                    {{ $role->description ?? 'لا يوجد وصف' }}
                                                </small>
                                                <small class="d-block mt-1">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $role->users_count }} مستخدم
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary assign-btn">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // البحث مع تأخير
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchValue = $(this).val();
                    const url = new URL(window.location.href);
                    if (searchValue) {
                        url.searchParams.set('search', searchValue);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }, 500);
            });

            // تجهيز Modal قبل العرض
            $('#assignModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const adminId = button.data('admin-id');
                const adminName = button.data('admin-name');
                const adminRoles = button.data('admin-roles') ? button.data('admin-roles').toString().split(
                    ',') : [];

                const modal = $(this);
                modal.find('#modalAdminId').val(adminId);
                modal.find('#modalUserName').text(adminName);

                // تفعيل الرتب الحالية
                modal.find('input[name="roles[]"]').prop('checked', false);
                adminRoles.forEach(roleId => {
                    if (roleId) {
                        modal.find(`input[name="roles[]"][value="${roleId}"]`).prop('checked',
                            true);
                    }
                });

                // تعطيل خاص للمشرف الرئيسي إذا كان المشرف الوحيد
                const superAdminCheckbox = modal.find('input[data-protected="true"]');
                if (superAdminCheckbox.length > 0) {
                    @php
                        $superAdminCount = \App\Models\Admin::role('super_admin')->count();
                    @endphp

                    if ({{ $superAdminCount }} <= 1 && superAdminCheckbox.prop('checked')) {
                        superAdminCheckbox.prop('disabled', true);
                        superAdminCheckbox.closest('.role-checkbox').append(
                            '<small class="text-warning d-block mt-1"><i class="fas fa-exclamation-triangle me-1"></i>لا يمكن إزالة هذا الدور لأنه المشرف الوحيد</small>'
                        );
                    } else {
                        superAdminCheckbox.prop('disabled', false);
                    }
                }
            });

            // تنظيف Modal عند الإغلاق
            $('#assignModal').on('hidden.bs.modal', function() {
                $(this).find('input[name="roles[]"]').prop('disabled', false);
                $(this).find('.text-warning').remove();
            });

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

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الإدخال',
                    html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
