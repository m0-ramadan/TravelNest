@extends('admin.layout.master')

@section('title', 'إنشاء مقال بالذكاء الاصطناعي')

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
        .form-select,
        textarea {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            color: #fff;
            border-radius: 10px;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
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

        .preview-box {
            background: rgba(255, 255, 255, .05);
            border: 1px dashed rgba(255, 255, 255, .15);
            border-radius: 12px;
            padding: 20px;
            min-height: 180px;
            white-space: pre-wrap;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">المقالات</a></li>
                <li class="breadcrumb-item active">إنشاء مقال بالذكاء الاصطناعي</li>
            </ol>
        </nav>

        <div class="order-card">
            <div class="order-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إنشاء مقال بالذكاء الاصطناعي</h5>
                        <small class="opacity-75">اكتب فكرة أو تعليمات وسيتم تجهيز مسودة المقال</small>
                    </div>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right me-2"></i>رجوع
                    </a>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.articles.store-with-ai') }}" method="POST">
                    @csrf

                    <div class="section-title">إعدادات التوليد</div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">الفكرة / البرومبت</label>
                            <textarea name="prompt" class="form-control" rows="6"
                                placeholder="اكتب موضوع المقال، الجمهور المستهدف، النبرة، النقاط الرئيسية...">{{ old('prompt') }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">اللغة</label>
                            <input type="text" name="locale" class="form-control" value="{{ old('locale', 'ar') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع المقال</label>
                            <input type="text" name="post_type" class="form-control"
                                value="{{ old('post_type', 'blog') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">النبرة</label>
                            <input type="text" name="tone" class="form-control" value="{{ old('tone', 'احترافية') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الكلمات المفتاحية</label>
                            <input type="text" name="keywords" class="form-control" value="{{ old('keywords') }}">
                        </div>
                    </div>

                    <div class="section-title mt-4">نتيجة متوقعة / معاينة</div>

                    <div class="preview-box">
                        سيتم هنا عرض نتيجة التوليد أو النص الأولي بعد ربط الـ endpoint الخاص بالذكاء الاصطناعي.
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-wand-magic-sparkles me-2"></i>توليد المقال
                        </button>
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-secondary">
                            <i class="fas fa-pen-to-square me-2"></i>إنشاء يدوي
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
