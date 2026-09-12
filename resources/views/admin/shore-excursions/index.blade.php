@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('Shore Excursions'))

@section('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0ea5e9 0%, #1e40af 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
            --dark-box: rgba(255, 255, 255, .05);
            --dark-border: rgba(255, 255, 255, .08);
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .stats-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            border-top: 4px solid #0ea5e9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .2);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .35);
        }

        .stats-number {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
        }

        .stats-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 4px;
        }

        .filter-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .filter-card .form-label {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            background-color: rgba(0, 0, 0, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
            border-radius: 8px;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25) !important;
        }

        .main-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, .1);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .main-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 18px 24px;
        }

        .table-dark-custom th {
            background: rgba(0, 0, 0, 0.25) !important;
            color: #38bdf8 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            font-weight: 600;
            font-size: 13px;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .table-dark-custom td {
            border-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            vertical-align: middle;
            font-size: 14px;
            padding: 14px 16px;
        }

        .table-dark-custom tbody tr:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .thumb-img {
            width: 72px;
            height: 52px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: #111;
            display: block;
        }

        .port-badge {
            background: rgba(14, 165, 233, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .price-tag {
            font-size: 16px;
            font-weight: 700;
            color: #facc15;
            font-family: monospace;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Shore Excursions</li>
            </ol>
        </nav>

        {{-- Top Statistics Row --}}
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ number_format($totalShore) }}</div>
                            <div class="stats-label">Total Shore Excursions</div>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(14, 165, 233, 0.15); color: #0ea5e9;">
                            <i class="fas fa-anchor fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <div class="stats-card" style="border-top-color: #22c55e;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number text-success">{{ number_format($activeShore) }}</div>
                            <div class="stats-label">Active Excursions (Visible Online)</div>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(34, 197, 94, 0.15); color: #22c55e;">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <a href="{{ route('admin.shore-excursions.bookings') }}" class="text-decoration-none">
                    <div class="stats-card" style="border-top-color: #f59e0b;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stats-number text-warning">{{ number_format($totalBookings) }}</div>
                                <div class="stats-label">Shore Excursion Bookings (Click to View)</div>
                            </div>
                            <div class="rounded-circle p-3" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.shore-excursions.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Search Excursion</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Type excursion title or keyword...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Port / Section</label>
                        <select name="port" class="form-select">
                            <option value="">All Ports & Sections</option>
                            @foreach ($sections as $key => $sec)
                                <option value="{{ $key }}" {{ request('port') === $key ? 'selected' : '' }}>
                                    {{ $sec['title_en'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.shore-excursions.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Main Table Card --}}
        <div class="main-card">
            <div class="main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 text-white"><i class="fas fa-anchor me-2"></i> Shore Excursions Management</h5>
                    <small class="opacity-75">Manage, schedule, and configure private and group cruise port shore
                        excursions</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.shore-excursions.bookings') }}" class="btn btn-warning text-dark fw-bold">
                        <i class="fas fa-calendar-check me-1"></i> All Bookings ({{ $totalBookings }})
                    </a>
                    <a href="{{ route('admin.shore-excursions.create') }}" class="btn btn-light fw-bold">
                        <i class="fas fa-plus me-1"></i> Add Shore Excursion
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-striped table-dark-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 90px;">Image</th>
                            <th>Excursion Title</th>
                            <th>Port / Destination</th>
                            <th>Starting From</th>
                            <th class="text-center">Bookings</th>
                            <th class="text-center">Status</th>
                            <th class="text-end" style="width: 170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $item)
                            @php
                                $destText = is_array($item->destinations_text)
                                    ? $item->destinations_text['en'] ??
                                        implode(', ', array_filter($item->destinations_text))
                                    : (string) $item->destinations_text;
                                $pickup = is_array($item->pickup_location)
                                    ? $item->pickup_location['en'] ??
                                        implode(', ', array_filter($item->pickup_location))
                                    : (string) $item->pickup_location;
                                $titleEn = is_array($item->title)
                                    ? $item->title['en'] ?? ($item->title['ar'] ?? $item->name)
                                    : $item->title ?? $item->name;
                                $titleAr = is_array($item->title) ? $item->title['ar'] ?? '' : '';
                                $imgSrc = get_package_image($item->featured_image);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($packages->currentPage() - 1) * $packages->perPage() }}</td>
                                <td>
                                    <img src="{{ $imgSrc }}" alt="{{ $titleEn }}" class="thumb-img"
                                        onerror="this.src='{{ asset('website/photos/home2.webp') }}'">
                                </td>
                                <td>
                                    <div class="fw-bold text-white fs-6">{{ $titleEn }}</div>
                                    @if ($titleAr && $titleAr !== $titleEn)
                                        <div class="small text-white-50">{{ $titleAr }}</div>
                                    @endif
                                    <div class="small text-info mt-1">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $item->duration_hours ? $item->duration_hours . ' Hours' : ($item->duration_days ? $item->duration_days . ' Days' : 'Day Tour') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="port-badge">
                                        <i class="fas fa-ship"></i> {{ $destText ?: 'Cruise Port' }}
                                    </div>
                                    @if ($pickup)
                                        <div class="small text-white-50 mt-1 text-truncate" style="max-width: 220px;"
                                            title="{{ $pickup }}">
                                            <i class="fas fa-map-marker-alt me-1 text-warning"></i> {{ $pickup }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="price-tag">
                                        ${{ number_format((float) ($item->price_from ?: $item->start_from_price), 2) }}
                                    </div>
                                    <div class="small text-white-50">per person in group</div>
                                </td>
                                <td class="text-center">
                                    @if ($item->bookings_count > 0)
                                        <a href="{{ route('admin.shore-excursions.package-bookings', $item) }}"
                                            class="badge bg-warning text-dark fs-6 py-2 px-3 text-decoration-none"
                                            title="View bookings for this excursion">
                                            <i class="fas fa-calendar-check me-1"></i> {{ $item->bookings_count }}
                                            Bookings
                                        </a>
                                    @else
                                        <span class="badge bg-secondary py-2 px-3">0 Bookings</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->is_active)
                                        <span class="badge bg-success px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('website.tours.show', $item->slug) }}" target="_blank"
                                            class="btn btn-sm btn-outline-info" title="Preview on Website">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.shore-excursions.package-bookings', $item) }}"
                                            class="btn btn-sm btn-outline-warning" title="View Bookings">
                                            <i class="fas fa-calendar-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.shore-excursions.edit', $item) }}"
                                            class="btn btn-sm btn-primary" title="Edit Excursion">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST"
                                            action="{{ route('admin.shore-excursions.destroy', $item) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this shore excursion?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-anchor fa-3x text-muted mb-3 d-block"></i>
                                    <h5>No shore excursions found matching your criteria</h5>
                                    <p class="text-muted small">Try adjusting your search filters or create a new shore
                                        excursion.</p>
                                    <a href="{{ route('admin.shore-excursions.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Add First Shore Excursion
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($packages->hasPages())
                <div class="p-3 border-top border-secondary">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
