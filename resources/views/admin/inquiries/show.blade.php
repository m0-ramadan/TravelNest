@extends('admin.layout.master')

@section('title', 'عرض الاستفسار')

@section('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .profile-card {
            background: var(--dark-card);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .profile-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 30px;
        }

        .profile-body {
            padding: 30px;
        }

        .info-box {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 15px;
        }

        .info-label {
            color: rgba(255, 255, 255, .65);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #fff;
            font-weight: 600;
        }

        .message-box {
            white-space: pre-wrap;
            line-height: 1.9;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.inquiries.index') }}">الاستفسارات</a></li>
                <li class="breadcrumb-item active">عرض الاستفسار</li>
            </ol>
        </nav>

        <div class="profile-card">
            <div class="profile-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $inquiry->subject ?? 'بدون عنوان' }}</h4>
                    <small class="opacity-75">{{ $inquiry->full_name ?? $inquiry->name ?? '-' }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.inquiries.edit', $inquiry) }}" class="btn btn-light">تعديل</a>
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-light">رجوع</a>
                </div>
            </div>

            <div class="profile-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">الاسم</div>
                            <div class="info-value">{{ $inquiry->full_name ?? $inquiry->name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">البريد الإلكتروني</div>
                            <div class="info-value">{{ $inquiry->email ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">الهاتف</div>
                            <div class="info-value">{{ $inquiry->phone ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">الباقة</div>
                            <div class="info-value">{{ $inquiry->package->title ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">الحالة</div>
                            <div class="info-value">{{ $inquiry->status ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">عدد الأفراد</div>
                            <div class="info-value">{{ $inquiry->travellers_count ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">تاريخ السفر</div>
                            <div class="info-value">{{ optional($inquiry->travel_date)->translatedFormat('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">تاريخ الإنشاء</div>
                            <div class="info-value">
                                {{ optional($inquiry->created_at)->translatedFormat('d M Y - h:i A') ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-box">
                            <div class="info-label">الرسالة</div>
                            <div class="info-value message-box">{{ $inquiry->message ?? '-' }}</div>
                        </div>
                    </div>

                    @if ($inquiry->tailorMadeRequest)
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">الإقامة</div>
                                <div class="info-value">{{ $inquiry->tailorMadeRequest->accommodation_preference ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">بلد الإقامة</div>
                                <div class="info-value">{{ $inquiry->tailorMadeRequest->country_of_residence ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">تاريخ العودة</div>
                                <div class="info-value">
                                    {{ optional($inquiry->tailorMadeRequest->end_date)->translatedFormat('d M Y') ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">الرضع</div>
                                <div class="info-value">{{ $inquiry->tailorMadeRequest->infants ?? 0 }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
