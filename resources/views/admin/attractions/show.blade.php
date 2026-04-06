@extends('admin.layout.master')

@section('title', 'عرض المعلم')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">عرض المعلم</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.attractions.edit', $attraction) }}" class="btn btn-warning">تعديل</a>
                    <a href="{{ route('admin.attractions.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="card-body">
                @if ($attraction->image)
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/' . $attraction->image) }}" style="max-width:300px;border-radius:15px;">
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>الاسم:</strong>
                        <div>{{ adminTrans($attraction->name) ?: '-' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>الوجهة:</strong>
                        <div>{{ adminTrans(optional($attraction->destination)->name) ?: '-' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Slug:</strong>
                        <div>{{ $attraction->slug ?: '-' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>الحالة:</strong>
                        <div>{{ $attraction->is_active ? 'مفعل' : 'غير مفعل' }}</div>
                    </div>

                    <div class="col-12 mb-3">
                        <strong>الوصف المختصر:</strong>
                        <div>{!! adminTrans($attraction->short_description) ?: '-' !!}</div>
                    </div>

                    <div class="col-12 mb-3">
                        <strong>الوصف:</strong>
                        <div>{!! adminTrans($attraction->description) ?: '-' !!}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>SEO Title:</strong>
                        <div>{{ adminTrans($attraction->seo_title) ?: '-' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>SEO Description:</strong>
                        <div>{{ adminTrans($attraction->seo_description) ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
