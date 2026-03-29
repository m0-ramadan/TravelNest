@extends('admin.layout.master')

@section('title', 'إنشاء باقة بالذكاء الاصطناعي')

@section('content')

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

                <form action="{{ route('admin.packages.store-with-ai') }}" method="POST">
                    @csrf

                    <!-- Prompt -->
                    <div class="mb-3">
                        <label class="form-label">وصف الرحلة (Prompt)</label>
                        <textarea name="prompt" class="form-control" rows="6"
                            placeholder="مثال: رحلة 5 أيام في الأقصر وأسوان تشمل نهر النيل والمعابد والفنادق الفاخرة">
                        {{ old('prompt') }}
                    </textarea>
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
                                    {{ $destination->name }}
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
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
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
