@extends('Admin.layout.master')

@section('title', 'وسائل الدفع')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-hover: #E55A2B;
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

        .table-card {
            /* background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%); */
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(255, 107, 53, 0.1);
            position: relative;
        }

        .table-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to left, var(--primary-color), var(--secondary-color));
        }

        .table-header {
            /* background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)); */
            color: white;
            padding: 25px;
            position: relative;
        }

        .table-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: 0;
            width: 100%;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 45px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transition: all 0.3s;
        }

        .search-box input:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.9);
        }

        .stats-card {
            /* background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%); */
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-top: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            border: 1px solid rgba(66, 103, 136, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to left, var(--primary-color), transparent);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
        }

        .stats-icon.icon-inactive {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .stats-icon.icon-payment {
            background: linear-gradient(135deg, #20c997, #28a745);
        }

        .stats-icon.icon-method {
            background: linear-gradient(135deg, var(--secondary-color), #3a5d7a);
        }

        .stats-number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--secondary-color);
            line-height: 1;
        }

        .stats-label {
            color: var(--text-muted);
            font-size: 15px;
            font-weight: 500;
        }

        .icon-preview {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(66, 103, 136, 0.05));
            color: var(--primary-color);
            border: 2px solid rgba(255, 107, 53, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .icon-preview img {
            width: 100%;
            height: 100%;
            border-radius: 8px;
            object-fit: cover;
        }

        .status-badge {
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .status-active {
            /* background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); */
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-inactive {
            /* background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); */
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .type-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .type-payment {
            /* background: linear-gradient(135deg, #e7f5ff 0%, #d0ebff 100%); */
            color: #0c63e4;
            border: 1px solid #d0ebff;
        }

        .type-method {
            /* background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); */
            color: var(--secondary-color);
            border: 1px solid #e9ecef;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-action.btn-info {
            background: linear-gradient(135deg, var(--info-color), #0dcaf0);
            color: white;
        }

        .btn-action.btn-info:hover {
            background: linear-gradient(135deg, #0dcaf0, var(--info-color));
            box-shadow: 0 5px 15px rgba(13, 202, 240, 0.3);
        }

        .btn-action.btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #fd7e14);
            color: #212529;
        }

        .btn-action.btn-warning:hover {
            background: linear-gradient(135deg, #fd7e14, var(--warning-color));
            box-shadow: 0 5px 15px rgba(253, 126, 20, 0.3);
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc3545);
            color: white;
        }

        .btn-action.btn-danger:hover {
            background: linear-gradient(135deg, #dc3545, var(--danger-color));
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
            cursor: pointer;
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
            background: linear-gradient(135deg, #6c757d, #495057);
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        input:checked+.toggle-slider {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        }

        input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 25px;
            opacity: 0.7;
        }

        .empty-state-text {
            color: var(--secondary-color);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .empty-state-description {
            color: var(--text-muted);
            margin-bottom: 25px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 600;
            font-family: "Cairo", sans-serif;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }

        .breadcrumb {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 10px;
            padding: 15px 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 107, 53, 0.1);
            margin-bottom: 30px;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 700;
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

        .table-hover tbody tr {
            transition: all 0.3s;
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(66, 103, 136, 0.03));
            transform: translateX(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        code {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid rgba(66, 103, 136, 0.2);
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 13px;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #0dcaf0, #17a2b8) !important;
            border: none;
        }

        @media (max-width: 768px) {
            .stats-card {
                padding: 20px;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
                margin-bottom: 15px;
            }

            .stats-number {
                font-size: 24px;
            }

            .action-buttons {
                flex-wrap: wrap;
                justify-content: flex-start;
            }

            .table-header {
                padding: 20px;
            }

            .search-box input {
                width: 100%;
                margin-bottom: 15px;
            }

            .table-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active">وسائل الدفع</li>
            </ol>
        </nav>

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number">
                        {{ $paymentMethods->where('is_active', true)->count() }}
                    </div>
                    <div class="stats-label">وسائل دفع نشطة</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-inactive">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="stats-number">
                        {{ $paymentMethods->where('is_active', false)->count() }}
                    </div>
                    <div class="stats-label">وسائل دفع غير نشطة</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-payment">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stats-number">
                        {{ $paymentMethods->where('is_payment', true)->count() }}
                    </div>
                    <div class="stats-label">طرق دفع</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon icon-method">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stats-number">
                        {{ $paymentMethods->where('is_payment', false)->count() }}
                    </div>
                    <div class="stats-label">طرق أخرى</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="table-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0">وسائل الدفع</h5>
                                <small class="opacity-85">إدارة جميع وسائل وطرق الدفع في النظام</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="search-box">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="form-control" placeholder="بحث في وسائل الدفع..."
                                        id="searchInput" style="width: 250px;">
                                </div>
                                <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-light"
                                    style="background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.3); color: white;">
                                    <i class="fas fa-plus me-2"></i>إضافة وسيلة دفع
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>الاسم</th>
                                    <th>المعرف</th>
                                    <th>الأيقونة</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th width="140" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="paymentMethodsTable">
                                @forelse($paymentMethods as $method)
                                    <tr data-id="{{ $method->id }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-preview">
                                                    @if ($method->icon)
                                                        @if (Str::startsWith($method->icon, 'http') || file_exists(public_path('storage/payment-methods/' . $method->icon)))
                                                            <img src="{{ $method->icon_url }}" alt="{{ $method->name }}">
                                                        @else
                                                            <i class="{{ $method->icon }}"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-credit-card"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong
                                                        style="color: var(--secondary-color);">{{ $method->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $method->key }}</code>
                                        </td>
                                        <td>
                                            @if ($method->icon)
                                                <span class="badge bg-info">
                                                    @if (Str::startsWith($method->icon, 'fas') || Str::startsWith($method->icon, 'fab'))
                                                        <i class="{{ $method->icon }} me-1"></i>
                                                        أيقونة
                                                    @else
                                                        <i class="fas fa-image me-1"></i>
                                                        صورة
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">بدون أيقونة</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($method->is_payment)
                                                <span class="type-badge type-payment">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    طريقة دفع
                                                </span>
                                            @else
                                                <span class="type-badge type-method">
                                                    <i class="fas fa-exchange-alt me-1"></i>
                                                    طريقة أخرى
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" class="status-toggle"
                                                        data-id="{{ $method->id }}"
                                                        {{ $method->is_active ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <span
                                                    class="status-badge {{ $method->is_active ? 'status-active' : 'status-inactive' }}">
                                                    <i
                                                        class="{{ $method->is_active ? 'fas fa-check-circle' : 'fas fa-times-circle' }}"></i>
                                                    {{ $method->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $method->created_at->translatedFormat('d M Y') }}
                                            </small>
                                            <br>
                                            <small class="text-muted" style="font-size: 11px;">
                                                {{ $method->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.payment-methods.show', $method) }}"
                                                    class="btn btn-action btn-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.payment-methods.edit', $method) }}"
                                                    class="btn btn-action btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-danger delete-btn"
                                                    title="حذف" data-id="{{ $method->id }}"
                                                    data-name="{{ $method->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                                <h5 class="empty-state-text">لا توجد وسائل دفع</h5>
                                                <p class="empty-state-description">
                                                    لم تقم بإضافة أي وسائل دفع حتى الآن. ابدأ بإضافة وسيلة دفع جديدة
                                                </p>
                                                <a href="{{ route('admin.payment-methods.create') }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>إضافة وسيلة دفع جديدة
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($paymentMethods->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                {{ $paymentMethods->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // البحث في الجدول
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#paymentMethodsTable tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $('.status-toggle').on('change', function() {
                const checkbox = $(this);
                const methodId = checkbox.data('id');
                const row = checkbox.closest('tr');
                const oldState = !checkbox.is(':checked'); // الحالة قبل التغيير

                $.ajax({
                    url: "{{ route('admin.payment-methods.toggle-status', '') }}/" + methodId,
                    type: 'PATCH',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        row.css('opacity', '0.6');
                    },
                    success: function(response) {
                        if (response.success) {
                            const statusBadge = row.find('.status-badge');
                            const badgeIcon = statusBadge.find('i');

                            if (response.is_active) {
                                statusBadge
                                    .removeClass('status-inactive')
                                    .addClass('status-active')
                                    .html('<i class="fa fa-check-circle"></i> نشط');
                            } else {
                                statusBadge
                                    .removeClass('status-active')
                                    .addClass('status-inactive')
                                    .html('<i class="fa fa-times-circle"></i> غير نشط');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'تم',
                                text: response.message,
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        // رجّع الحالة القديمة
                        checkbox.prop('checked', oldState);

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'فشل تحديث الحالة',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    complete: function() {
                        row.css('opacity', '1');
                    }
                });
            });

            // حذف وسيلة الدفع
            $('.delete-btn').on('click', function() {
                const methodId = $(this).data('id');
                const methodName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف وسيلة الدفع "${methodName}" نهائياً`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#426788',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn-danger',
                        cancelButton: 'btn-outline-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.payment-methods.destroy', '') }}/" +
                                methodId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            beforeSend: function() {
                                // إظهار مؤشر التحميل
                                Swal.fire({
                                    title: 'جاري الحذف...',
                                    text: 'يرجى الانتظار',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.success,
                                    confirmButtonColor: '#FF6B35',
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
                                    confirmButtonColor: '#dc3545',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });

            // رسائل التنبيه من الجلسة
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#FF6B35',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#dc3545',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
