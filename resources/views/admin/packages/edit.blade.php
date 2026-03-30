@extends('admin.layout.master')

@section('title', 'تعديل باقة')

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

        .main-card {
            background: var(--dark-card);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .main-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 25px 30px;
        }

        .form-body {
            padding: 30px;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 20px;
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
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">الباقات</a></li>
                <li class="breadcrumb-item active">تعديل باقة</li>
            </ol>
        </nav>

        <div class="main-card">
            <div class="main-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">تعديل بيانات الباقة</h5>
                    <small class="opacity-75">{{ $package->name ?? '' }}</small>
                </div>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-light">رجوع</a>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="section-title">البيانات الأساسية</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم الباقة</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', adminTrans($package->name)) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control"
                                value="{{ old('slug', $package->slug) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">التصنيف</label>
                            <select name="category_id" class="form-select">
                                <option value="">اختر التصنيف</option>
                                @foreach ($categories ?? collect() as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $package->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ adminTrans($category->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الوجهة</label>
                            <select name="destination_id" class="form-select">
                                <option value="">اختر الوجهة</option>
                                @foreach ($destinations ?? collect() as $destination)
                                    <option value="{{ $destination->id }}"
                                        {{ old('destination_id', $package->destination_id) == $destination->id ? 'selected' : '' }}>
                                        {{ adminTrans($destination->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">العملة</label>
                            <select name="currency_id" class="form-select">
                                <option value="">اختر العملة</option>
                                @foreach ($currencies ?? collect() as $currency)
                                    <option value="{{ $currency->id }}"
                                        {{ old('currency_id', $package->currency_id) == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">عدد الأيام</label>
                            <input type="number" name="duration_days" class="form-control"
                                value="{{ old('duration_days', $package->duration_days) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">السعر الأساسي</label>
                            <input type="number" step="0.01" name="base_price" class="form-control"
                                value="{{ old('base_price', $package->base_price) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', $package->sort_order ?? 0) }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">وصف مختصر</label>
                            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', adminTrans($package->short_description)) }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">الوصف الكامل</label>
                            <textarea name="description" class="form-control" rows="6">{{ old('description', adminTrans($package->description)) }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الصورة الرئيسية</label>
                            <input type="file" name="featured_image" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3 d-flex gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_active"
                                    id="is_active" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">مفعلة</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_featured"
                                    id="is_featured"
                                    {{ old('is_featured', $package->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">مميزة</label>
                            </div>
                        </div>
                    </div>

                    <div class="section-title mt-4">SEO</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="seo_title" class="form-control"
                                value="{{ old('seo_title', adminTrans($package->seo_title)) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', adminTrans($package->seo_description)) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary" type="submit">حفظ</button>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
