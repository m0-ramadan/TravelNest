@extends('admin.layout.master')

@section('title', 'تعديل معلم')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تعديل معلم</h5>
                <a href="{{ route('admin.attractions.index') }}" class="btn btn-secondary">رجوع</a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.attractions.update', $attraction) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الوجهة</label>
                            <select name="destination_id" class="form-select">
                                <option value="">اختر الوجهة</option>
                                @foreach ($destinations as $destination)
                                    <option value="{{ $destination->id }}"
                                        {{ old('destination_id', $attraction->destination_id) == $destination->id ? 'selected' : '' }}>
                                        {{ adminTrans($destination->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control"
                                value="{{ old('slug', $attraction->slug) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', adminTrans($attraction->name)) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الصورة</label>
                            @if ($attraction->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $attraction->image) }}"
                                        style="width:120px;height:120px;object-fit:cover;border-radius:10px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">وصف مختصر</label>
                            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', adminTrans($attraction->short_description)) }}</textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="6">{{ old('description', adminTrans($attraction->description)) }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">SEO Title</label>
                            <input type="text" name="seo_title" class="form-control"
                                value="{{ old('seo_title', adminTrans($attraction->seo_title)) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">SEO Description</label>
                            <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', adminTrans($attraction->seo_description)) }}</textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $attraction->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label">مفعل</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary">تحديث</button>
                </form>
            </div>
        </div>
    </div>
@endsection
