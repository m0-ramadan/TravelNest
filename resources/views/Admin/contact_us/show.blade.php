@extends('Admin.layout.master')

@section('title', 'تفاصيل الرسالة')

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

        .message-detail-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
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

        .sender-info {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sender-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #fff;
            font-size: 16px;
            font-weight: 500;
        }

        .message-content-box {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .message-content-box p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            font-size: 16px;
        }

        .replies-section {
            margin-top: 30px;
        }

        .reply-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border-right: 4px solid var(--primary-color);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .reply-item.admin {
            border-right-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.05);
        }

        .reply-item.user {
            border-right-color: #20c997;
            background: rgba(32, 201, 151, 0.05);
        }

        .reply-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .reply-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .reply-info h6 {
            margin: 0;
            color: #fff;
            font-weight: 600;
        }

        .reply-info small {
            color: rgba(255, 255, 255, 0.6);
        }

        .reply-message {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            padding-right: 55px;
        }

        .reply-form {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
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

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
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
                    <a href="{{ route('admin.contact-us.index') }}">رسائل التواصل</a>
                </li>
                <li class="breadcrumb-item active">تفاصيل الرسالة</li>
            </ol>
        </nav>

        <div class="message-detail-card">
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">تفاصيل الرسالة</h5>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.contact-us.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-2"></i>عودة
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- معلومات المرسل -->
                <div class="sender-info">
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <div class="sender-avatar-large mx-auto">
                                {{ strtoupper(substr($contactUs->first_name, 0, 1)) }}{{ strtoupper(substr($contactUs->last_name, 0, 1)) }}
                            </div>
                            <h4>{{ $contactUs->first_name }} {{ $contactUs->last_name }}</h4>
                            <span class="badge-status status-{{ $contactUs->status }}">
                                @switch($contactUs->status)
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
                                        {{ $contactUs->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                            </div>
                            <div class="info-value">
                                <a href="mailto:{{ $contactUs->email }}" class="text-white">
                                    {{ $contactUs->email }}
                                </a>
                            </div>
                        </div>

                        @if ($contactUs->phone)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-phone me-2"></i>رقم الهاتف
                                </div>
                                <div class="info-value">
                                    <a href="tel:{{ $contactUs->phone }}" class="text-white">
                                        {{ $contactUs->phone }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($contactUs->company)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-building me-2"></i>الشركة
                                </div>
                                <div class="info-value">
                                    {{ $contactUs->company }}
                                </div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar me-2"></i>تاريخ الإرسال
                            </div>
                            <div class="info-value">
                                {{ $contactUs->created_at->format('Y-m-d h:i A') }}
                            </div>
                        </div>

                        @if ($contactUs->user)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user me-2"></i>حساب مستخدم
                                </div>
                                <div class="info-value">
                                    <a href="{{ route('admin.users.show', $contactUs->user_id) }}" class="text-white">
                                        {{ $contactUs->user->name }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- محتوى الرسالة -->
                <div class="message-content-box">
                    <h6 class="mb-3"><i class="fas fa-envelope-open-text me-2"></i>نص الرسالة</h6>
                    <p>{{ $contactUs->message }}</p>
                </div>

                <!-- الردود السابقة -->
                @if ($contactUs->replies->isNotEmpty())
                    <div class="replies-section">
                        <h6 class="mb-3"><i class="fas fa-comments me-2"></i>الردود السابقة</h6>

                        @foreach ($contactUs->replies as $reply)
                            <div class="reply-item {{ $reply->sender_type }}">
                                <div class="reply-header">
                                    <div class="reply-avatar">
                                        {{ strtoupper(substr($reply->user->name ?? 'مسؤول', 0, 1)) }}
                                    </div>
                                    <div class="reply-info">
                                        <h6>{{ $reply->user->name ?? 'مسؤول' }}
                                            @if ($reply->sender_type === 'admin')
                                                <small class="text-primary">(مسؤول)</small>
                                            @else
                                                <small class="text-success">(مستخدم)</small>
                                            @endif
                                        </h6>
                                        <small>
                                            <i class="far fa-clock me-1"></i>
                                            {{ $reply->created_at->format('Y-m-d h:i A') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="reply-message">
                                    {{ $reply->message }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- نموذج الرد -->
                <div class="reply-form">
                    <h6 class="mb-3"><i class="fas fa-reply me-2"></i>إضافة رد</h6>
                    <form id="replyForm">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" id="replyMessage" rows="5" placeholder="اكتب ردك هنا..." required></textarea>
                            <div class="invalid-feedback" id="messageError"></div>
                        </div>
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary" id="sendReplyBtn">
                                <i class="fas fa-paper-plane me-2"></i>إرسال الرد
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="updateStatus('read')">
                                <i class="fas fa-eye me-2"></i>تحديد كمقروء
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="updateStatus('archived')">
                                <i class="fas fa-archive me-2"></i>أرشفة
                            </button>
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="{{ $contactUs->id }}">
                                <i class="fas fa-trash me-2"></i>حذف
                            </button>
                        </div>
                    </form>
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
            // إرسال الرد
            $('#replyForm').on('submit', function(e) {
                e.preventDefault();

                const message = $('#replyMessage').val().trim();

                if (!message) {
                    $('#messageError').text('الرجاء كتابة الرد').show();
                    $('#replyMessage').addClass('is-invalid');
                    return;
                }

                $('#sendReplyBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...');

                $.ajax({
                    url: "{{ route('admin.contact-us.reply', $contactUs->id) }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        message: message
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الإرسال',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        $('#sendReplyBtn').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>إرسال الرد');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.message) {
                                $('#messageError').text(errors.message[0]).show();
                                $('#replyMessage').addClass('is-invalid');
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.responseJSON.message || 'حدث خطأ أثناء إرسال الرد',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            });

            // تحديث الحالة
            window.updateStatus = function(status) {
                let statusText = '';
                switch (status) {
                    case 'read':
                        statusText = 'كمقروءة';
                        break;
                    case 'archived':
                        statusText = 'كأرشيف';
                        break;
                }

                Swal.fire({
                    title: 'تأكيد التحديث',
                    text: `هل أنت متأكد من تحديث الرسالة ${statusText}؟`,
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
                            url: "{{ route('admin.contact-us.status', $contactUs->id) }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
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
            };

            // حذف الرسالة
            $('.delete-btn').on('click', function() {
                const messageId = $(this).data('id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم حذف الرسالة وجميع الردود المرتبطة بها نهائياً',
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
                            url: "{{ route('admin.contact-us.destroy', $contactUs->id) }}",
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
                                    window.location.href = "{{ route('admin.contact-us.index') }}";
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

            // إزالة رسالة الخطأ عند الكتابة
            $('#replyMessage').on('input', function() {
                $(this).removeClass('is-invalid');
                $('#messageError').hide();
            });
        });
    </script>
@endsection