@extends('admin.layout.master')

@section('title', 'إضافة صفحة')

@section('css')

    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #20c997;
            --danger-color: #fd7e14;
            --info-color: #0c63e4;
            --warning-color: #ffc107;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .panel-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .3);
            padding: 0;
            border: 1px solid rgba(255, 255, 255, .1);
            overflow: hidden;
        }

        .panel-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
        }

        .panel-body {
            padding: 30px;
        }

        .stats-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .3);
            border-top: 4px solid var(--primary-color);
            transition: transform .3s ease;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
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

        .stats-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #fff;
        }

        .stats-label {
            color: rgba(255, 255, 255, .7);
            font-size: 14px;
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
            min-height: 46px;
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

        .item-row {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 15px;
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

        .btn-outline-light {
            border-color: rgba(255, 255, 255, .3);
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 60px;
            color: rgba(255, 255, 255, .1);
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: rgba(255, 255, 255, .7);
            margin-bottom: 20px;
        }

        .badge-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
        }

        .status-active {
            background: linear-gradient(135deg, rgba(21, 87, 36, .2) 0%, rgba(32, 201, 151, .2) 100%);
            color: var(--success-color);
            border: 1px solid rgba(32, 201, 151, .3);
        }

        .status-inactive {
            background: linear-gradient(135deg, rgba(220, 53, 69, .2) 0%, rgba(253, 126, 20, .2) 100%);
            color: var(--danger-color);
            border: 1px solid rgba(253, 126, 20, .3);
        }

        .status-featured {
            background: rgba(12, 99, 228, .2);
            color: var(--info-color);
            border: 1px solid rgba(12, 99, 228, .3);
        }
    </style>

@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.static-pages.index') }}">الصفحات الثابتة</a></li>
                <li class="breadcrumb-item active">إضافة صفحة</li>
            </ol>
        </nav>

        <div class="panel-card">
            <div class="panel-header">
                <h5 class="mb-0">إضافة صفحة جديدة</h5>
                <small class="opacity-75">إدارة محتوى الصفحة</small>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.static-pages.store') }}" method="POST">

                    @csrf
                    <div class="section-title">بيانات الصفحة</div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">المحتوى</label>
                            <textarea name="content" class="form-control" rows="12">{{ old('content') }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">حفظ</button>
                        <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
