@extends('admin.layout.master')

@section('title', 'إنشاء باقة بالذكاء الاصطناعي')

@section('css')
    <style>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            <div class="loader-title">جاري إنشاء الباقة...</div>
            <div class="loader-text">برجاء الانتظار أثناء معالجة الطلب</div>

            <div class="progress-wrapper">
                <div class="progress-bar-custom" id="progressBar"></div>
            </div>

            <div class="progress-percent" id="progressPercent">0%</div>
        </div>
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">إنشاء باقة بالذكاء الاصطناعي</h4>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                رجوع
            </a>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.packages.store-with-ai') }}" method="POST" id="packageAiForm">
                    @csrf

                    <!-- Prompt -->
                    <div class="mb-3">
                        <label class="form-label">وصف الرحلة (Prompt)</label>
                        <textarea name="prompt" class="form-control" rows="6"
                            placeholder="مثال: رحلة 5 أيام في الأقصر وأسوان تشمل نهر النيل والمعابد والفنادق الفاخرة">{{ old('prompt', '') }}</textarea>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label class="form-label">عدد الأيام</label>
                        <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days') }}">
                    </div>

                    <!-- Destination -->
                    <div class="mb-3">
                        <label class="form-label">الوجهة</label>
                        <select name="destination_id" class="form-control">
                            <option value="">اختر الوجهة</option>
                            @foreach ($destinations ?? [] as $destination)
                                <option value="{{ $destination->id }}">
                                    {{ adminTrans($destination->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" class="form-control">
                            <option value="">اختر التصنيف</option>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}">
                                    {{ adminTrans($category->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            توليد الباقة
                        </button>

                        <a href="{{ route('admin.packages.create') }}" class="btn btn-outline-secondary">
                            إنشاء يدوي
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
            const form = document.getElementById('packageAiForm');
            const submitBtn = document.getElementById('submitBtn');
            const pageLoader = document.getElementById('pageLoader');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');

            let progress = 0;
            let interval = null;
            let submitted = false;

            form.addEventListener('submit', function(event) {
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
