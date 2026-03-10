@extends('Admin.layout.master')

@section('title', 'عرض المستخدم: ' . $user->name)

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .user-profile-dashboard {
            padding: 20px 0;
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .profile-avatar {
            position: relative;
            z-index: 1;
        }

        .profile-avatar-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .profile-avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 600;
            border: 5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .profile-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .profile-stats {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }

        .profile-stat-item {
            text-align: center;
        }

        .profile-stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .profile-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .profile-action-btn {
            padding: 12px 25px;
            border-radius: 10px;
            border: none;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .profile-action-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border-right: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .stat-card.orders {
            border-right-color: #0dcaf0;
        }

        .stat-card.wallet {
            border-right-color: #20c997;
        }

        .stat-card.reviews {
            border-right-color: #ffc107;
        }

        .stat-card.favourites {
            border-right-color: #dc3545;
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-left: 15px;
            color: white;
        }

        .stat-card.orders .stat-icon {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
        }

        .stat-card.wallet .stat-icon {
            background: linear-gradient(135deg, #20c997 0%, #198754 100%);
        }

        .stat-card.reviews .stat-icon {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .stat-card.favourites .stat-icon {
            background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);
        }

        .stat-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-subtitle {
            font-size: 13px;
            color: var(--bs-secondary-color);
        }

        /* Info Cards */
        .info-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: 1px solid var(--bs-border-color);
        }

        .info-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .info-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-left: 15px;
        }

        .info-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .info-subtitle {
            color: var(--bs-secondary-color);
            font-size: 13px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: rgb(39 9 9 / 15%);
            border-radius: 12px;
            transition: all 0.3s;
        }

        .info-item:hover {
            background: var(--bs-card-bg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .info-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .info-item-content {
            flex: 1;
        }

        .info-item-label {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 5px;
        }

        .info-item-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        .info-item-value.small {
            font-size: 14px;
            font-weight: 400;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-status.active {
            background: #d4edda;
            color: #155724;
        }

        .badge-status.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-status.verified {
            background: #cce5ff;
            color: #004085;
        }

        .badge-status.social {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Wallet Card */
        .wallet-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 25px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .wallet-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .wallet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .wallet-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wallet-title i {
            font-size: 28px;
        }

        .wallet-balance {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .wallet-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .wallet-amount {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .wallet-sub-amount {
            font-size: 18px;
            opacity: 0.8;
        }

        .wallet-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .wallet-stat {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px;
            backdrop-filter: blur(5px);
        }

        .wallet-stat-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .wallet-stat-value {
            font-size: 20px;
            font-weight: 700;
        }

        .wallet-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .btn-wallet {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            border: none;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-wallet:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Activity Timeline */
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            right: 30px;
            height: 100%;
            width: 2px;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            opacity: 0.3;
        }

        .timeline-item {
            position: relative;
            padding-right: 70px;
            margin-bottom: 25px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            right: 15px;
            top: 0;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
            z-index: 1;
        }

        .timeline-content {
            background: var(--bs-light-bg-subtle);
            border-radius: 12px;
            padding: 15px;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .timeline-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--bs-heading-color);
        }

        .timeline-time {
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        .timeline-text {
            font-size: 14px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        .timeline-amount {
            font-weight: 700;
            color: #28a745;
        }

        /* Recent Orders Table */
        .recent-orders-table {
            width: 100%;
        }

        .recent-orders-table th {
            font-size: 13px;
            font-weight: 600;
            color: var(--bs-secondary-color);
            padding: 12px 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .recent-orders-table td {
            padding: 15px;
            border-bottom: 1px solid var(--bs-border-color);
            vertical-align: middle;
        }

        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .order-status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .order-status.processing {
            background: #cce5ff;
            color: #004085;
        }

        .order-status.completed {
            background: #d4edda;
            color: #155724;
        }

        .order-status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-status.shipped {
            background: #d1ecf1;
            color: #0c5460;
        }

        .product-image-small {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Reviews */
        .review-item {
            padding: 20px;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .review-product {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .review-product img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }

        .review-rating {
            color: #ffc107;
        }

        .review-comment {
            color: var(--bs-secondary-color);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .review-date {
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        /* Favourites */
        .favourite-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid var(--bs-border-color);
            transition: all 0.3s;
        }

        .favourite-item:hover {
            background: var(--bs-light-bg-subtle);
        }

        .favourite-item img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }

        .favourite-info {
            flex: 1;
        }

        .favourite-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .favourite-price {
            font-size: 15px;
            font-weight: 700;
            color: #28a745;
        }

        .favourite-category {
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-header {
                padding: 25px;
            }

            .profile-stats {
                flex-wrap: wrap;
                gap: 15px;
            }

            .profile-actions {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .wallet-actions {
                flex-direction: column;
            }

            .timeline::before {
                right: 20px;
            }

            .timeline-item {
                padding-right: 50px;
            }

            .timeline-icon {
                right: 5px;
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}">المستخدمين</a>
                </li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>

        <div class="user-profile-dashboard">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-4">
                            <div class="profile-avatar">
                                @if ($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                                        class="profile-avatar-img">
                                @else
                                    <div class="profile-avatar-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="profile-badge">
                                    <i class="fas fa-id-card me-1"></i>
                                    ID: #{{ $user->id }}
                                </span>
                            </div>
                            <div>
                                <h2 class="mb-2">{{ $user->name }}</h2>
                                <div class="d-flex gap-3 mb-3">
                                    @if ($user->email_verified_at)
                                        <span class="badge-status verified">
                                            <i class="fas fa-check-circle me-1"></i>
                                            بريد موثق
                                        </span>
                                    @endif
                                    <span
                                        class="badge-status {{ isset($user->is_active) && $user->is_active ? 'active' : 'inactive' }}">
                                        <i
                                            class="fas fa-{{ isset($user->is_active) && $user->is_active ? 'check' : 'times' }} me-1"></i>
                                        {{ isset($user->is_active) && $user->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                    @if ($user->google_id || $user->facebook_id || $user->apple_id)
                                        <span class="badge-status social">
                                            <i class="fas fa-share-alt me-1"></i>
                                            سوشيال ميديا
                                        </span>
                                    @endif
                                </div>
                                <div class="profile-stats">
                                    <div class="profile-stat-item">
                                        <div class="profile-stat-value">{{ $user->orders_count ?? 0 }}</div>
                                        <div class="profile-stat-label">إجمالي الطلبات</div>
                                    </div>
                                    <div class="profile-stat-item">
                                        <div class="profile-stat-value">{{ $user->reviews_count ?? 0 }}</div>
                                        <div class="profile-stat-label">التقييمات</div>
                                    </div>
                                    <div class="profile-stat-item">
                                        <div class="profile-stat-value">{{ $user->favouriteProducts_count ?? 0 }}</div>
                                        <div class="profile-stat-label">المفضلة</div>
                                    </div>
                                    <div class="profile-stat-item">
                                        <div class="profile-stat-value">{{ $user->created_at->format('Y') }}</div>
                                        <div class="profile-stat-label">عضو منذ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="profile-actions">
                            <a href="{{ route('admin.users.edit', $user) }}" class="profile-action-btn">
                                <i class="fas fa-edit"></i>
                                تعديل الملف
                            </a>
                            <button type="button" class="profile-action-btn"
                                onclick="sendNotification({{ $user->id }})">
                                <i class="fas fa-bell"></i>
                                إرسال إشعار
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card orders">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <div class="stat-title">إجمالي الطلبات</div>
                            <div class="stat-subtitle">جميع طلبات المستخدم</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ $user->orders_count ?? 0 }}</div>
                    <div class="stat-subtitle">
                        @php
                            $totalSpent = $user->orders->sum('total_amount') ?? 0;
                        @endphp
                        إجمالي المشتريات: {{ number_format($totalSpent, 2) }} ج.م
                    </div>
                </div>

                <div class="stat-card wallet">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="stat-title">رصيد المحفظة</div>
                            <div class="stat-subtitle">الرصيد المتاح</div>
                        </div>
                    </div>
                    <div class="stat-value">
                        {{ $user->userWallet ? number_format($user->userWallet->available_balance, 2) : '0.00' }} ج.م
                    </div>
                    <div class="stat-subtitle">
                        الرصيد الكلي: {{ $user->userWallet ? number_format($user->userWallet->balance, 2) : '0.00' }} ج.م
                    </div>
                </div>

                <div class="stat-card reviews">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <div class="stat-title">التقييمات</div>
                            <div class="stat-subtitle">عدد التقييمات</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ $user->reviews_count ?? 0 }}</div>
                    <div class="stat-subtitle">
                        @php
                            $avgRating = $user->reviews->avg('rating') ?? 0;
                        @endphp
                        متوسط التقييم: {{ number_format($avgRating, 1) }}/5
                    </div>
                </div>

                <div class="stat-card favourites">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <div class="stat-title">المفضلة</div>
                            <div class="stat-subtitle">المنتجات المفضلة</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ $user->favouriteProducts_count ?? 0 }}</div>
                    <div class="stat-subtitle">
                        آخر إضافة: {{ $user->favouriteProducts->first()?->created_at?->format('d M Y') ?? 'لا يوجد' }}
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- User Information -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div>
                                <h5 class="info-title">معلومات المستخدم</h5>
                                <p class="info-subtitle">بيانات الحساب ومعلومات الاتصال</p>
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-item-content">
                                    <div class="info-item-label">البريد الإلكتروني</div>
                                    <div class="info-item-value">{{ $user->email }}</div>
                                    <small class="text-{{ $user->email_verified_at ? 'success' : 'danger' }}">
                                        <i
                                            class="fas fa-{{ $user->email_verified_at ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ $user->email_verified_at ? 'موثق' : 'غير موثق' }}
                                    </small>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-item-content">
                                    <div class="info-item-label">رقم الهاتف</div>
                                    <div class="info-item-value">{{ $user->phone ?? 'غير محدد' }}</div>
                                    @if ($user->phone)
                                        <small class="text-muted">
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            رقم موثق
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-item-content">
                                    <div class="info-item-label">العنوان</div>
                                    <div class="info-item-value small">
                                        @if ($user->address1 || $user->city || $user->country)
                                            {{ $user->address1 ?? '' }}<br>
                                            {{ $user->city ?? '' }}، {{ $user->country ?? '' }}
                                            @if ($user->post_code)
                                                <br>الرمز البريدي: {{ $user->post_code }}
                                            @endif
                                        @else
                                            غير محدد
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-item-content">
                                    <div class="info-item-label">تاريخ التسجيل</div>
                                    <div class="info-item-value">
                                        {{ $user->created_at->translatedFormat('d F Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $user->created_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="info-item-content">
                                    <div class="info-item-label">آخر دخول</div>
                                    <div class="info-item-value">
                                        {{ $user->last_login_at ? $user->last_login_at->translatedFormat('d F Y H:i') : 'غير متوفر' }}
                                    </div>
                                    @if ($user->last_login_at)
                                        <small class="text-muted">
                                            {{ $user->last_login_at->format('d M Y') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                <div class="info-item-content">
                                    <div class="info-item-label">طريقة التسجيل</div>
                                    <div class="info-item-value">
                                        @if ($user->google_id)
                                            <span class="badge bg-danger">Google</span>
                                        @elseif($user->facebook_id)
                                            <span class="badge bg-primary">Facebook</span>
                                        @elseif($user->apple_id)
                                            <span class="badge bg-dark">Apple</span>
                                        @else
                                            <span class="badge bg-info">بريد إلكتروني</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div>
                                <h5 class="info-title">آخر الطلبات</h5>
                                <p class="info-subtitle">أحدث
                                    {{ $user->orders->count() > 10 ? 10 : $user->orders->count() }} طلب</p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('admin.users.orders', $user) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    عرض الكل
                                    <i class="fas fa-arrow-left me-2"></i>
                                </a>
                            </div>
                        </div>

                        @if ($user->orders && $user->orders->count() > 0)
                            <div class="table-responsive">
                                <table class="recent-orders-table">
                                    <thead>
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->orders->take(5) as $order)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $order->order_number ?? $order->id }}</strong>
                                                </td>
                                                <td>
                                                    {{ $order->created_at->translatedFormat('d M Y') }}
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ number_format($order->total_amount, 2) }} ج.م</strong>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = match ($order->status) {
                                                            'pending' => 'pending',
                                                            'processing' => 'processing',
                                                            'completed' => 'completed',
                                                            'cancelled' => 'cancelled',
                                                            'shipped' => 'shipped',
                                                            default => 'pending',
                                                        };
                                                    @endphp
                                                    <span class="order-status {{ $statusClass }}">
                                                        {{ $order->status_text ?? $order->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                        class="btn btn-sm btn-icon btn-outline-info" title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div style="font-size: 60px; color: var(--bs-secondary-color); margin-bottom: 20px;">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h6 class="text-muted">لا توجد طلبات حتى الآن</h6>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Reviews -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h5 class="info-title">آخر التقييمات</h5>
                                <p class="info-subtitle">أحدث
                                    {{ $user->reviews->count() > 5 ? 5 : $user->reviews->count() }} تقييم</p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('admin.users.reviews', $user) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    عرض الكل
                                    <i class="fas fa-arrow-left me-2"></i>
                                </a>
                            </div>
                        </div>

                        @if ($user->reviews && $user->reviews->count() > 0)
                            @foreach ($user->reviews->take(5) as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-product">
                                            @if ($review->product && $review->product->photo)
                                                <img src="{{ asset('storage/' . $review->product->photo) }}"
                                                    alt="{{ $review->product->title }}">
                                            @else
                                                <div
                                                    class="product-image-small bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $review->product->title ?? 'منتج محذوف' }}</h6>
                                                <div class="review-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= $review->rate ? '' : '-o' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-date">
                                            {{ $review->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    @if ($review->review)
                                        <div class="review-comment">
                                            "{{ $review->review }}"
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div style="font-size: 60px; color: var(--bs-secondary-color); margin-bottom: 20px;">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h6 class="text-muted">لا توجد تقييمات حتى الآن</h6>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Wallet Card -->
                    <div class="info-card">
                        <div class="wallet-card">
                            <div class="wallet-header">
                                <div class="wallet-title">
                                    <i class="fas fa-wallet"></i>
                                    <h5 class="mb-0 text-white">محفظة المستخدم</h5>
                                </div>
                                @if ($user->userWallet)
                                    <span class="badge bg-light text-dark">
                                        {{ $user->userWallet->currency }}
                                    </span>
                                @endif
                            </div>

                            @if ($user->userWallet)
                                <div class="wallet-balance">
                                    <div class="wallet-label">الرصيد المتاح</div>
                                    <div class="wallet-amount">
                                        {{ number_format($user->userWallet->available_balance, 2) }} ج.م</div>
                                    <div class="wallet-sub-amount">
                                        الرصيد الكلي: {{ number_format($user->userWallet->balance, 2) }} ج.م
                                    </div>
                                </div>

                                <div class="wallet-stats">
                                    <div class="wallet-stat">
                                        <div class="wallet-stat-label">محجوز</div>
                                        <div class="wallet-stat-value">
                                            {{ number_format($user->userWallet->held_balance, 2) }} ج.م</div>
                                    </div>
                                    <div class="wallet-stat">
                                        <div class="wallet-stat-label">الحد اليومي</div>
                                        <div class="wallet-stat-value">
                                            {{ number_format($user->userWallet->daily_limit, 2) }} ج.م</div>
                                    </div>
                                </div>

                                <div class="wallet-actions">
                                    <button type="button" class="btn-wallet"
                                        onclick="showDepositModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i class="fas fa-plus-circle"></i>
                                        إيداع
                                    </button>
                                    <button type="button" class="btn-wallet"
                                        onclick="showWithdrawModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i class="fas fa-minus-circle"></i>
                                        سحب
                                    </button>
                                    <button type="button" class="btn-wallet"
                                        onclick="showTransactionHistory({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i class="fas fa-history"></i>
                                        سجل
                                    </button>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div style="font-size: 48px; color: rgba(255,255,255,0.5); margin-bottom: 15px;">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <p class="text-white mb-3">لا توجد محفظة لهذا المستخدم</p>
                                    <button type="button" class="btn-wallet" style="background: rgba(255,255,255,0.2);"
                                        onclick="createWallet({{ $user->id }})">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        إنشاء محفظة
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h5 class="info-title">النشاطات الحديثة</h5>
                                <p class="info-subtitle">آخر 10 نشاطات</p>
                            </div>
                        </div>

                        @if ($user->notifications && $user->notifications->count() > 0)
                            <div class="timeline">
                                @foreach ($user->notifications->take(10) as $notification)
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-{{ $notification->type_icon ?? 'bell' }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <span class="timeline-title">{{ $notification->title ?? 'إشعار' }}</span>
                                                <span
                                                    class="timeline-time">{{ $notification->created_at->format('d M Y') }}</span>
                                            </div>
                                            <p class="timeline-text">{{ $notification->message ?? $notification->body }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div style="font-size: 48px; color: var(--bs-secondary-color); margin-bottom: 15px;">
                                    <i class="fas fa-bell-slash"></i>
                                </div>
                                <p class="text-muted mb-0">لا توجد نشاطات حديثة</p>
                            </div>
                        @endif

                        <div class="text-center mt-3">
                            <a href="{{ route('admin.users.activities', $user) }}"
                                class="btn btn-sm btn-outline-primary">
                                عرض كل النشاطات
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Favourites -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div>
                                <h5 class="info-title">المنتجات المفضلة</h5>
                                <p class="info-subtitle">آخر
                                    {{ $user->favouriteProducts->count() > 5 ? 5 : $user->favouriteProducts->count() }}
                                    منتج</p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('admin.users.favourites', $user) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        @if ($user->favouriteProducts && $user->favouriteProducts->count() > 0)
                            @foreach ($user->favouriteProducts->take(5) as $product)
                                <div class="favourite-item">
                                    @if ($product->photo)
                                        <img src="{{ asset('storage/' . $product->photo) }}"
                                            alt="{{ $product->title }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px; border-radius: 10px;">
                                            <i class="fas fa-box fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="favourite-info">
                                        <div class="favourite-title">{{ $product->title }}</div>
                                        <div class="favourite-price">{{ number_format($product->price, 2) }} ج.م</div>
                                        <div class="favourite-category">
                                            {{ $product->category->title ?? 'غير مصنف' }}
                                        </div>
                                    </div>
                                    <div>
                                        <small
                                            class="text-muted">{{ $product->pivot->created_at?->format('d M Y') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div style="font-size: 48px; color: var(--bs-secondary-color); margin-bottom: 15px;">
                                    <i class="fas fa-heart-broken"></i>
                                </div>
                                <p class="text-muted mb-0">لا توجد منتجات مفضلة</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-plus-circle me-2"></i>
                        إيداع رصيد
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="depositForm">
                    @csrf
                    <input type="hidden" name="user_id" id="depositUserId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>
                                    المستخدم
                                </label>
                                <input type="text" class="form-control" id="depositUserName" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-wallet me-1"></i>
                                    الرصيد الحالي
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="currentBalance" readonly>
                                    <span class="input-group-text">ج.م</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-coins me-1"></i>
                                المبلغ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="amount" id="depositAmount"
                                    placeholder="أدخل المبلغ" min="1" max="100000" step="0.01" required>
                                <span class="input-group-text">ج.م</span>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                الحد الأدنى: 1 ريال | الحد الأقصى: 100,000 ريال
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-credit-card me-1"></i>
                                طريقة الدفع
                            </label>
                            <select class="form-select" name="payment_method">
                                <option value="bank_transfer">🏦 تحويل بنكي</option>
                                <option value="credit_card">💳 بطاقة ائتمان</option>
                                <option value="cash">💰 نقدي</option>
                                <option value="system">⚙️ نظامي</option>
                                <option value="manual">📝 يدوي</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-hashtag me-1"></i>
                                رقم المرجع
                            </label>
                            <input type="text" class="form-control" name="reference"
                                placeholder="رقم التحويل أو المعاملة (اختياري)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-edit me-1"></i>
                                ملاحظات
                            </label>
                            <textarea class="form-control" name="description" rows="3" placeholder="أضف أي ملاحظات حول الإيداع (اختياري)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>إلغاء
                        </button>
                        <button type="submit" class="btn"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <span class="spinner-border spinner-border-sm d-none" id="depositSpinner"></span>
                            <i class="fas fa-check-circle me-1"></i>
                            تأكيد الإيداع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-minus-circle me-2"></i>
                        سحب رصيد
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="withdrawForm">
                    @csrf
                    <input type="hidden" name="user_id" id="withdrawUserId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>
                                    المستخدم
                                </label>
                                <input type="text" class="form-control" id="withdrawUserName" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-wallet me-1"></i>
                                    الرصيد المتاح
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="availableBalance" readonly>
                                    <span class="input-group-text">ج.م</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-coins me-1"></i>
                                المبلغ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="amount" id="withdrawAmount"
                                    placeholder="أدخل المبلغ" min="1" max="100000" step="0.01" required>
                                <span class="input-group-text">ج.م</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-exchange-alt me-1"></i>
                                طريقة السحب
                            </label>
                            <select class="form-select" name="withdrawal_method">
                                <option value="bank_transfer">🏦 تحويل بنكي</option>
                                <option value="cash">💰 نقدي</option>
                                <option value="wallet">📱 محفظة رقمية</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-info-circle me-1"></i>
                                معلومات السحب
                            </label>
                            <textarea class="form-control" name="withdrawal_details" rows="2"
                                placeholder="رقم الحساب البنكي، رقم المحفظة... (اختياري)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-edit me-1"></i>
                                ملاحظات
                            </label>
                            <textarea class="form-control" name="description" rows="2" placeholder="أضف أي ملاحظات حول السحب (اختياري)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-danger" id="withdrawSubmitBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="withdrawSpinner"></span>
                            <i class="fas fa-check-circle me-1"></i>
                            تأكيد السحب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #6610f2 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-history me-2"></i>
                        سجل المعاملات
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h6 id="historyUserName" class="mb-2"></h6>
                            <p class="text-muted mb-0" id="historyUserInfo"></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-outline-primary" onclick="exportCurrentTransactions()">
                                <i class="fas fa-download me-2"></i>تصدير
                            </button>
                        </div>
                    </div>
                    <div class="transaction-history" id="transactionHistory">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-3 text-muted">جاري تحميل سجل المعاملات...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize modals
            const depositModal = new bootstrap.Modal(document.getElementById('depositModal'));
            const withdrawModal = new bootstrap.Modal(document.getElementById('withdrawModal'));
            const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));

            // Make modals globally accessible
            window.depositModal = depositModal;
            window.withdrawModal = withdrawModal;
            window.historyModal = historyModal;

            // Deposit Form Submit
            $('#depositForm').on('submit', function(e) {
                e.preventDefault();

                const userId = $('#depositUserId').val();
                const submitBtn = $('#depositSubmitBtn');
                const spinner = $('#depositSpinner');

                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.users.wallet.deposit', '') }}/" + userId,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            depositModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الإيداع بنجاح!',
                                text: `تم إضافة ${response.data.amount} ج.م إلى رصيد المستخدم`,
                                confirmButtonColor: '#696cff'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ أثناء الإيداع';
                        if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: errorMessage,
                            confirmButtonColor: '#696cff'
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

            // Withdraw Form Submit
            $('#withdrawForm').on('submit', function(e) {
                e.preventDefault();

                const userId = $('#withdrawUserId').val();
                const submitBtn = $('#withdrawSubmitBtn');
                const spinner = $('#withdrawSpinner');

                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.users.wallet.withdraw', '') }}/" + userId,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            withdrawModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم السحب بنجاح!',
                                text: `تم سحب ${response.data.amount} ج.م من رصيد المستخدم`,
                                confirmButtonColor: '#696cff'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ أثناء السحب';
                        if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: errorMessage,
                            confirmButtonColor: '#696cff'
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });
        });

        // Show Deposit Modal
        function showDepositModal(userId, userName) {
            $.ajax({
                url: "{{ route('admin.users.wallet.info', '') }}/" + userId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#depositUserId').val(userId);
                        $('#depositUserName').val(userName);
                        $('#currentBalance').val(response.data.balance);
                        $('#depositAmount').val('');
                        $('#depositForm')[0].reset();
                        window.depositModal.show();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'فشل في تحميل معلومات المحفظة',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }

        // Show Withdraw Modal
        function showWithdrawModal(userId, userName) {
            $.ajax({
                url: "{{ route('admin.users.wallet.info', '') }}/" + userId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#withdrawUserId').val(userId);
                        $('#withdrawUserName').val(userName);
                        $('#availableBalance').val(response.data.available_balance);
                        $('#withdrawAmount').val('');
                        $('#withdrawForm')[0].reset();
                        window.withdrawModal.show();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'فشل في تحميل معلومات المحفظة',
                        confirmButtonColor: '#696cff'
                    });
                }
            });
        }

        // Show Transaction History
        function showTransactionHistory(userId, userName) {
            $('#historyUserName').html(`
                <i class="fas fa-user me-2"></i>
                سجل معاملات: <strong>${userName}</strong>
            `);
            $('#historyUserInfo').html(`
                <i class="fas fa-info-circle me-1"></i>
                جميع المعاملات المالية للمستخدم
            `);

            loadTransactions(userId);
            window.historyModal.show();
        }

        // Load Transactions
        function loadTransactions(userId) {
            $.ajax({
                url: "{{ route('admin.users.wallet.transactions', '') }}/" + userId,
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        let html = '';

                        if (response.data.transactions && response.data.transactions.length > 0) {
                            response.data.transactions.forEach(transaction => {
                                let typeClass = transaction.type === 'deposit' ? 'success' : 'danger';
                                let typeIcon = transaction.type === 'deposit' ? 'plus-circle' :
                                    'minus-circle';
                                let typeText = transaction.type === 'deposit' ? 'إيداع' : 'سحب';

                                html += `
                                    <div class="transaction-item p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-${typeClass} p-2">
                                                    <i class="fas fa-${typeIcon} me-1"></i>
                                                    ${typeText}
                                                </span>
                                                <strong class="me-2">${transaction.description || 'معاملة مالية'}</strong>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold ${transaction.type === 'deposit' ? 'text-success' : 'text-danger'}">
                                                    ${transaction.type === 'deposit' ? '+' : '-'}${transaction.amount} ج.م
                                                </div>
                                                <small class="text-muted">${transaction.formatted_date}</small>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-hashtag me-1"></i> المرجع: ${transaction.reference || '---'}
                                            </small>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = `
                                <div class="text-center p-5">
                                    <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">لا توجد معاملات حتى الآن</h5>
                                </div>
                            `;
                        }

                        $('#transactionHistory').html(html);
                    }
                },
                error: function() {
                    $('#transactionHistory').html(`
                        <div class="text-center p-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                            <h5 class="text-danger">فشل في تحميل سجل المعاملات</h5>
                            <button class="btn btn-outline-primary mt-3" onclick="loadTransactions(${userId})">
                                <i class="fas fa-sync-alt me-2"></i>إعادة المحاولة
                            </button>
                        </div>
                    `);
                }
            });
        }

        // Create Wallet
        function createWallet(userId) {
            Swal.fire({
                title: 'إنشاء محفظة جديدة',
                text: 'هل تريد إنشاء محفظة لهذا المستخدم؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، إنشاء',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.users.wallet.create', '') }}/" + userId,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم إنشاء المحفظة',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'فشل في إنشاء المحفظة',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // Export Current Transactions
        function exportCurrentTransactions() {
            const userId = $('#depositUserId').val() || $('#withdrawUserId').val();
            if (!userId) return;

            window.location.href = "{{ route('admin.users.wallet.export-transactions', '') }}/" + userId;
        }

        // Send Notification
        function sendNotification(userId) {
            Swal.fire({
                title: 'إرسال إشعار',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">عنوان الإشعار</label>
                            <input type="text" id="notificationTitle" class="form-control" 
                                   placeholder="أدخل عنوان الإشعار">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">نص الإشعار</label>
                            <textarea id="notificationBody" class="form-control" rows="3" 
                                      placeholder="أدخل نص الإشعار"></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'إرسال',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#696cff',
                reverseButtons: true,
                preConfirm: () => {
                    const title = $('#notificationTitle').val();
                    const body = $('#notificationBody').val();

                    if (!title || !body) {
                        Swal.showValidationMessage('الرجاء إدخال عنوان ونص الإشعار');
                        return false;
                    }

                    return {
                        title,
                        body
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.sendnotify') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            user_id: userId,
                            title: result.value.title,
                            message: result.value.body
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الإرسال',
                                text: 'تم إرسال الإشعار بنجاح',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'فشل في إرسال الإشعار',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
