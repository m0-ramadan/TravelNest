@extends('admin.layout.master')

@section('title', 'المعالم السياحية')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">المعالم السياحية</h4>
            <a href="{{ route('admin.attractions.create') }}" class="btn btn-primary">إضافة معلم</a>
        </div>

        <form method="GET" class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="q" class="form-control" placeholder="بحث..."
                            value="{{ request('q') }}">
                    </div>

                    <div class="col-md-4">
                        <select name="destination_id" class="form-select">
                            <option value="">كل الوجهات</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination->id }}"
                                    {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                                    {{ adminTrans($destination->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">فلترة</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>الوجهة</th>
                            <th>Slug</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attractions as $attraction)
                            <tr>
                                <td>{{ $attraction->id }}</td>
                                <td>
                                    @if ($attraction->image)
                                        <img src="{{ asset('storage/' . $attraction->image) }}"
                                            style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ adminTrans($attraction->name) }}</td>
                                <td>{{ adminTrans(optional($attraction->destination)->name) ?: '-' }}</td>
                                <td>{{ $attraction->slug }}</td>
                                <td>
                                    @if ($attraction->is_active)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-danger">غير مفعل</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('admin.attractions.show', $attraction) }}"
                                        class="btn btn-sm btn-info">عرض</a>

                                    <a href="{{ route('admin.attractions.edit', $attraction) }}"
                                        class="btn btn-sm btn-warning">تعديل</a>

                                    <form action="{{ route('admin.attractions.destroy', $attraction) }}" method="POST"
                                        onsubmit="return confirm('متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $attractions->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
