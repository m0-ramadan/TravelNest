@extends('admin.layout.master')

@section('title', 'إضافة لغة')

@section('css')
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .order-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 0;
            border: 1px solid rgba(255, 255, 255, .1);
            overflow: hidden;
        }

        .order-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
        }

        .form-body {
            padding: 30px;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            padding-bottom: 10px;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            color: #fff;
            border-radius: 10px;
            min-height: 46px;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 .25rem rgba(105, 108, 255, .25);
        }

        .form-label {
            color: rgba(255, 255, 255, .85);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .15);
            color: #fff;
        }

        /* Loading Overlay */
        .page-loader {
            position: fixed;
            inset: 0;
            background: rgba(30, 30, 45, 0.92);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .page-loader.active {
            display: flex;
        }

        .loader-box {
            width: 100%;
            max-width: 420px;
            background: #2b3b4c;
            border-radius: 20px;
            padding: 30px 25px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .loader-spinner {
            width: 65px;
            height: 65px;
            border: 5px solid rgba(255, 255, 255, .15);
            border-top: 5px solid #696cff;
            border-radius: 50%;
            margin: 0 auto 20px;
            animation: spin 1s linear infinite;
        }

        .loader-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #fff;
        }

        .loader-text {
            font-size: 14px;
            color: rgba(255, 255, 255, .75);
            margin-bottom: 20px;
        }

        .progress-wrapper {
            width: 100%;
            height: 14px;
            background: rgba(255, 255, 255, .08);
            border-radius: 30px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar-custom {
            width: 0%;
            height: 100%;
            background: var(--primary-gradient);
            border-radius: 30px;
            transition: width .3s ease;
        }

        .progress-percent {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
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
    <div class="page-loader" id="pageLoader">
        <div class="loader-box">
            <div class="loader-spinner"></div>
            <div class="loader-title">جاري الإنشاء...</div>
            <div class="loader-text">برجاء الانتظار أثناء حفظ البيانات</div>

            <div class="progress-wrapper">
                <div class="progress-bar-custom" id="progressBar"></div>
            </div>

            <div class="progress-percent" id="progressPercent">0%</div>
        </div>
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">اللغات</a></li>
                <li class="breadcrumb-item active">إضافة لغة</li>
            </ol>
        </nav>

        <div class="order-card">
            <div class="order-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إضافة لغة جديدة</h5>
                        <small class="opacity-75">إدخال بيانات اللغة الأساسية</small>
                    </div>
                    <a href="{{ route('admin.languages.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right me-2"></i>رجوع
                    </a>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.languages.store') }}" method="POST" id="languageForm">
                    @csrf

                    <div class="section-title">بيانات اللغة</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم اللغة</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم المحلي</label>
                            <input type="text" name="native_name" class="form-control" value="{{ old('native_name') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الكود</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                                placeholder="ar / en / fr">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                        </div>

                        <div class="col-md-12 mb-3 d-flex gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="is_active"
                                    name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">مفعلة</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="is_default"
                                    name="is_default" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">افتراضية</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('languageForm');
            const submitBtn = document.getElementById('submitBtn');
            const pageLoader = document.getElementById('pageLoader');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');

            let progress = 0;
            let interval = null;
            let submitted = false;

            form.addEventListener('submit', function() {
                if (submitted) {
                    event.preventDefault();
                    return false;
                }

                submitted = true;
                submitBtn.disabled = true;
                pageLoader.classList.add('active');

                interval = setInterval(() => {
                    if (progress < 90) {
                        progress += Math.floor(Math.random() * 10) + 3;
                        if (progress > 90) progress = 90;

                        progressBar.style.width = progress + '%';
                        progressPercent.textContent = progress + '%';
                    }
                }, 200);
            });

            window.addEventListener('pageshow', function() {
                clearInterval(interval);
                progress = 0;
                if (progressBar) progressBar.style.width = '0%';
                if (progressPercent) progressPercent.textContent = '0%';
                if (pageLoader) pageLoader.classList.remove('active');
                if (submitBtn) submitBtn.disabled = false;
                submitted = false;
            });
        });
    </script>
@endsection
