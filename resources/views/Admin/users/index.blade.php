@extends('Admin.layout.master')

@section('title', 'إدارة المستخدمين والمحافظ')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .users-dashboard {
            padding: 20px 0;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .welcome-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .welcome-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-left: 20px;
        }

        .welcome-content h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .welcome-content p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 5px solid;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.users {
            border-left-color: #696cff;
        }

        .stat-card.wallet {
            border-left-color: #20c997;
        }

        .stat-card.deposit {
            border-left-color: #28a745;
        }

        .stat-card.withdrawal {
            border-left-color: #dc3545;
        }

        .stat-card.social {
            border-left-color: #1877f2;
        }

        .stat-card.orders {
            border-left-color: #fd7e14;
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

        .stat-card.users .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.wallet .stat-icon {
            background: linear-gradient(135deg, #20c997 0%, #0dcaf0 100%);
        }

        .stat-card.deposit .stat-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stat-card.withdrawal .stat-icon {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .stat-card.social .stat-icon {
            background: linear-gradient(135deg, #1877f2 0%, #0dcaf0 100%);
        }

        .stat-card.orders .stat-icon {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        }

        .stat-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .stat-description {
            font-size: 13px;
            color: var(--bs-secondary-color);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }

        .stat-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid var(--bs-border-color);
        }

        .stat-change {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-change.positive {
            color: #28a745;
        }

        .stat-change.negative {
            color: #dc3545;
        }

        /* Search & Filter Section */
        .search-filter-section {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .section-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-left: 15px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .section-description {
            color: var(--bs-secondary-color);
            font-size: 14px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 45px;
            border-radius: 10px;
            border: 2px solid var(--bs-border-color);
            height: 50px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
        }

        .search-box .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--bs-secondary-color);
            font-size: 18px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .filter-tab {
            padding: 8px 20px;
            border-radius: 25px;
            background: var(--bs-light-bg-subtle);
            color: var(--bs-secondary-color);
            border: 1px solid var(--bs-border-color);
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-tab:hover {
            background: var(--bs-card-bg);
            border-color: #696cff;
            color: #696cff;
        }

        .filter-tab.active {
            background: #696cff;
            color: white;
            border-color: #696cff;
        }

        .sort-dropdown {
            position: relative;
        }

        .sort-btn {
            background: var(--bs-light-bg-subtle);
            border: 1px solid var(--bs-border-color);
            color: var(--bs-body-color);
            padding: 10px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .sort-btn:hover {
            background: var(--bs-card-bg);
            border-color: #696cff;
            color: #696cff;
        }

        .sort-dropdown-content {
            display: none;
            position: absolute;
            background: var(--bs-card-bg);
            min-width: 250px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            z-index: 1000;
            padding: 10px 0;
            margin-top: 5px;
            left: 0;
            border: 1px solid var(--bs-border-color);
        }

        .sort-dropdown:hover .sort-dropdown-content {
            display: block;
        }

        .sort-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-item:hover {
            background: #696cff;
            color: white;
        }

        .sort-item.active {
            background: rgba(105, 108, 255, 0.1);
            color: #696cff;
            font-weight: 600;
        }

        /* Table Card */
        .table-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .user-avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
            border: 3px solid white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-details h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .user-details p {
            margin: 0;
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        /* Wallet Card */
        .balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .balance-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .balance-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .balance-subvalue {
            font-size: 14px;
            opacity: 0.8;
        }

        .quick-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        .btn-quick-action {
            flex: 1;
            padding: 8px;
            border-radius: 8px;
            border: none;
            color: white;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-quick-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-deposit {
            background: #28a745;
        }

        .btn-withdraw {
            background: #dc3545;
        }

        .btn-history {
            background: #6f42c1;
        }

        /* Badges */
        .badge-custom {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-social {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-email {
            background: #e7f5ff;
            color: #0c63e4;
        }

        .badge-google {
            background: #ea4335;
            color: white;
        }

        .badge-facebook {
            background: #1877f2;
            color: white;
        }

        .badge-apple {
            background: #000000;
            color: white;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .social-icons {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        .social-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
        }

        .icon-google {
            background: #ea4335;
        }

        .icon-facebook {
            background: #1877f2;
        }

        .icon-apple {
            background: #000000;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
            display: inline-block;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #696cff;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-action.btn-info {
            background: #0dcaf0;
            color: white;
        }

        .btn-action.btn-warning {
            background: #ffc107;
            color: #000;
        }

        .btn-action.btn-success {
            background: #28a745;
            color: white;
        }

        .btn-action.btn-danger {
            background: #dc3545;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 70px;
            color: var(--bs-secondary-color);
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: var(--bs-secondary-color);
            margin-bottom: 20px;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 25px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid var(--bs-border-color);
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--bs-border-color);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
        }

        .input-group-text {
            background: var(--bs-light-bg-subtle);
            border: 2px solid var(--bs-border-color);
            border-left: none;
            border-radius: 0 10px 10px 0;
            color: var(--bs-body-color);
            font-weight: 600;
        }

        /* Transaction History */
        .transaction-history {
            max-height: 400px;
            overflow-y: auto;
        }

        .transaction-item {
            padding: 15px;
            border-bottom: 1px solid var(--bs-border-color);
            transition: all 0.3s;
        }

        .transaction-item:hover {
            background: var(--bs-light-bg-subtle);
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .type-deposit {
            background: #d4edda;
            color: #155724;
        }

        .type-withdrawal {
            background: #f8d7da;
            color: #721c24;
        }

        .type-transfer {
            background: #e7f5ff;
            color: #0c63e4;
        }

        .transaction-amount {
            font-weight: 700;
            font-size: 16px;
        }

        .transaction-date {
            color: var(--bs-secondary-color);
            font-size: 12px;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            color: var(--bs-body-color);
            background: var(--bs-light-bg-subtle);
            transition: all 0.3s;
        }

        .page-link:hover {
            background: #696cff;
            color: white;
        }

        .page-item.active .page-link {
            background: #696cff;
            color: white;
        }

        .page-item.disabled .page-link {
            background: var(--bs-light-bg-subtle);
            color: var(--bs-secondary-color);
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-header {
                flex-direction: column;
                text-align: center;
            }

            .welcome-icon {
                margin-left: 0;
                margin-bottom: 15px;
            }

            .stat-header {
                flex-direction: column;
                text-align: center;
            }

            .stat-icon {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
            }

            .quick-actions {
                flex-direction: column;
            }

            .action-buttons {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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
                <li class="breadcrumb-item active">المستخدمين والمحافظ</li>
            </ol>
        </nav>

        <div class="users-dashboard">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="welcome-header">
                    <div class="welcome-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="welcome-content">
                        <h3>مرحباً بك في إدارة المستخدمين</h3>
                        <p>من هنا يمكنك إدارة جميع المستخدمين ومحافظهم المالية وعمليات الإيداع والسحب</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            إجمالي المستخدمين: <strong>{{ number_format($totalUsers ?? $users->total()) }}</strong> |
                            إجمالي المحافظ:
                            <strong>{{ number_format($totalWallets ?? App\Models\Wallet\UserWallet::count()) }}</strong> |
                            آخر تحديث: <strong>{{ now()->format('H:i') }}</strong>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-clock me-1"></i> {{ now()->format('H:i') }}
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar me-1"></i> {{ now()->translatedFormat('l، d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card users">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="stat-title">إجمالي المستخدمين</div>
                            <div class="stat-description">جميع المستخدمين المسجلين</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($totalUsers ?? $users->total()) }}</div>
                    <div class="stat-footer">
                        <span class="stat-change {{ $userGrowth >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-arrow-{{ $userGrowth >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($userGrowth) }}% عن الشهر الماضي
                        </span>
                        {{-- <span class="text-muted">نشط اليوم: {{ $activeToday ?? 0 }}</span> --}}
                    </div>
                </div>

                <div class="stat-card wallet">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="stat-title">إجمالي أرصدة المحافظ</div>
                            <div class="stat-description">مجموع أرصدة جميع المستخدمين</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($totalWalletBalance, 2) }} ج.م</div>
                    <div class="stat-footer">
                        <span>عدد المحافظ:
                            {{ number_format($totalWallets ?? App\Models\Wallet\UserWallet::count()) }}</span>
                        <span class="text-muted">متوسط الرصيد: {{ number_format($avgWalletBalance ?? 0, 2) }} ج.م</span>
                    </div>
                </div>

                <div class="stat-card deposit">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div class="stat-title">إجمالي الإيداعات</div>
                            <div class="stat-description">جميع عمليات الإيداع الناجحة</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($totalDeposits, 2) }} ج.م</div>
                    <div class="stat-footer">
                        <span>عدد العمليات: {{ number_format($totalDepositCount ?? 0) }}</span>
                        <span class="text-muted">اليوم: {{ number_format($todayDeposits ?? 0, 2) }} ج.م</span>
                    </div>
                </div>

                <div class="stat-card withdrawal">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div>
                            <div class="stat-title">إجمالي السحوبات</div>
                            <div class="stat-description">جميع عمليات السحب الناجحة</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($totalWithdrawals, 2) }} ج.م</div>
                    <div class="stat-footer">
                        <span>عدد العمليات: {{ number_format($totalWithdrawalCount ?? 0) }}</span>
                        <span class="text-muted">اليوم: {{ number_format($todayWithdrawals ?? 0, 2) }} ج.م</span>
                    </div>
                </div>

                <div class="stat-card social">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div>
                            <div class="stat-title">مستخدمي التواصل الاجتماعي</div>
                            <div class="stat-description">Google, Facebook, Apple</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($socialUsers) }}</div>
                    <div class="stat-footer">
                        <span>Google: {{ $googleUsers ?? 0 }}</span>
                        <span>Facebook: {{ $facebookUsers ?? 0 }}</span>
                        <span>Apple: {{ $appleUsers ?? 0 }}</span>
                    </div>
                </div>

                <div class="stat-card orders">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <div class="stat-title">الطلبات والتقييمات</div>
                            <div class="stat-description">نشاط المستخدمين</div>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="stat-footer">
                        <span>تقييمات: {{ number_format($totalReviews) }}</span>
                        <span class="text-muted">مفضلة: {{ number_format($totalFavourites) }}</span>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Section -->
            <div class="search-filter-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h5 class="section-title">بحث وتصفية</h5>
                        <p class="section-description">ابحث عن المستخدمين وصنفهم حسب الفلاتر المختلفة</p>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control"
                                placeholder="بحث بالاسم، البريد الإلكتروني، رقم الهاتف..." id="searchInput"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-3">
                            <div class="sort-dropdown">
                                <button class="sort-btn">
                                    <i class="fas fa-sort-amount-down"></i>
                                    الترتيب
                                </button>
                                <div class="sort-dropdown-content">
                                    <div class="sort-item {{ request('sort_by') == 'created_at' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                                        onclick="sortBy('created_at', 'desc')">
                                        <i class="fas fa-clock"></i> الأحدث أولاً
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'created_at' && request('sort_direction') == 'asc' ? 'active' : '' }}"
                                        onclick="sortBy('created_at', 'asc')">
                                        <i class="fas fa-history"></i> الأقدم أولاً
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'name' && request('sort_direction') == 'asc' ? 'active' : '' }}"
                                        onclick="sortBy('name', 'asc')">
                                        <i class="fas fa-sort-alpha-down"></i> الاسم (أ-ي)
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'name' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                                        onclick="sortBy('name', 'desc')">
                                        <i class="fas fa-sort-alpha-up"></i> الاسم (ي-أ)
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'wallet_balance' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                                        onclick="sortBy('wallet_balance', 'desc')">
                                        <i class="fas fa-coins"></i> أعلى رصيد أولاً
                                    </div>
                                    <div class="sort-item {{ request('sort_by') == 'orders_count' && request('sort_direction') == 'desc' ? 'active' : '' }}"
                                        onclick="sortBy('orders_count', 'desc')">
                                        <i class="fas fa-shopping-cart"></i> أكثر طلبات
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.create') }}" class="btn"
                                style="background: #696cff; color: white;">
                                <i class="fas fa-user-plus me-2"></i>إضافة مستخدم
                            </a>
                        </div>
                    </div>
                </div>

                <div class="filter-tabs">
                    <div class="filter-tab {{ !request('type') ? 'active' : '' }}" onclick="filterBy('all')">
                        <i class="fas fa-users"></i> جميع المستخدمين
                    </div>
                    <div class="filter-tab {{ request('type') == 'social' ? 'active' : '' }}"
                        onclick="filterBy('social')">
                        <i class="fas fa-share-alt"></i> مستخدمي السوشيال ميديا
                    </div>
                    <div class="filter-tab {{ request('type') == 'email' ? 'active' : '' }}" onclick="filterBy('email')">
                        <i class="fas fa-envelope"></i> مستخدمي البريد الإلكتروني
                    </div>
                    <div class="filter-tab {{ request('type') == 'with_wallet' ? 'active' : '' }}"
                        onclick="filterBy('with_wallet')">
                        <i class="fas fa-wallet"></i> لديهم محفظة
                    </div>
                    <div class="filter-tab {{ request('type') == 'active' ? 'active' : '' }}"
                        onclick="filterBy('active')">
                        <i class="fas fa-check-circle"></i> نشط
                    </div>
                    <div class="filter-tab {{ request('type') == 'inactive' ? 'active' : '' }}"
                        onclick="filterBy('inactive')">
                        <i class="fas fa-times-circle"></i> غير نشط
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-card">
                <div class="table-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">قائمة المستخدمين والمحافظ</h5>
                            <small class="opacity-75">عرض وإدارة جميع مستخدمي النظام ومحافظهم المالية</small>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button class="btn btn-light" onclick="exportUsers()">
                                    <i class="fas fa-download me-2"></i>تصدير
                                </button>
                                <button class="btn btn-light" onclick="refreshData()">
                                    <i class="fas fa-sync-alt me-2"></i>تحديث
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>المستخدم</th>
                                <th>المحفظة</th>
                                <th>معلومات الاتصال</th>
                                <th>طريقة التسجيل</th>
                                <th>الحالة</th>
                                <th>تاريخ التسجيل</th>
                                <th width="180">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="usersTable">
                            @forelse($users as $user)
                                @php
                                    $wallet = $user->userWallet;
                                    $walletBalance = $wallet ? $wallet->balance : 0;
                                    $availableBalance = $wallet ? $wallet->available_balance : 0;
                                @endphp
                                <tr data-id="{{ $user->id }}">
                                    <td>{{ $loop->iteration + $users->perPage() * ($users->currentPage() - 1) }}</td>
                                    <td>
                                        <div class="user-info">
                                            @if ($user->image)
                                                <img src="{{ asset('storage/' . $user->image) }}"
                                                    alt="{{ $user->name }}" class="user-avatar">
                                            @else
                                                <div class="user-avatar-placeholder">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="user-details">
                                                <h6 class="mb-1">{{ $user->name }}</h6>
                                                <p class="mb-0">
                                                    <i class="fas fa-id-card me-1"></i> #{{ $user->id }}
                                                    @if ($user->orders_count ?? 0 > 0)
                                                        <span class="ms-2 badge bg-info">
                                                            {{ $user->orders_count }} طلب
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($wallet)
                                            <div class="balance-card">
                                                <div class="balance-label">
                                                    <i class="fas fa-coins me-1"></i>الرصيد المتاح
                                                </div>
                                                <div class="balance-value">
                                                    {{ number_format($availableBalance, 2) }} ج.م
                                                </div>
                                                <div class="balance-subvalue">
                                                    الكلي: {{ number_format($walletBalance, 2) }} ج.م
                                                </div>
                                                <div class="quick-actions">
                                                    <button type="button" class="btn-quick-action btn-deposit"
                                                        onclick="showDepositModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                        <i class="fas fa-plus-circle"></i> إيداع
                                                    </button>
                                                    <button type="button" class="btn-quick-action btn-withdraw"
                                                        onclick="showWithdrawModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                        <i class="fas fa-minus-circle"></i> سحب
                                                    </button>
                                                    <button type="button" class="btn-quick-action btn-history"
                                                        onclick="showTransactionHistory({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                        <i class="fas fa-history"></i> سجل
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center p-3 bg-light rounded">
                                                <i class="fas fa-exclamation-triangle text-warning mb-2"></i>
                                                <p class="mb-2 small">لا توجد محفظة</p>
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    onclick="createWallet({{ $user->id }})">
                                                    <i class="fas fa-plus-circle me-1"></i>إنشاء محفظة
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-3">
                                            <i class="fas fa-envelope text-muted me-1"></i>
                                            <small>{{ $user->email }}</small>
                                        </div>
                                        @if ($user->phone)
                                            <div class="mb-1">
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                <small>{{ $user->phone }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->google_id || $user->facebook_id || $user->apple_id)
                                            <span class="badge-custom badge-social">
                                                <i class="fas fa-share-alt me-1"></i>
                                                سوشيال
                                            </span>
                                            <div class="social-icons">
                                                @if ($user->google_id)
                                                    <span class="social-icon icon-google" title="تسجيل بجوجل">
                                                        <i class="fab fa-google"></i>
                                                    </span>
                                                @endif
                                                @if ($user->facebook_id)
                                                    <span class="social-icon icon-facebook" title="تسجيل بفيسبوك">
                                                        <i class="fab fa-facebook-f"></i>
                                                    </span>
                                                @endif
                                                @if ($user->apple_id)
                                                    <span class="social-icon icon-apple" title="تسجيل بأبل">
                                                        <i class="fab fa-apple"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge-custom badge-email">
                                                <i class="fas fa-envelope me-1"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($user->is_active))
                                            <div class="d-flex align-items-center gap-2">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" class="status-toggle"
                                                        data-id="{{ $user->id }}"
                                                        {{ $user->is_active ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <span
                                                    class="badge-custom {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                                    {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge-custom badge-active">نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <i class="fas fa-calendar text-muted me-1"></i>
                                            <small>{{ $user->created_at->translatedFormat('d M Y') }}</small>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            <small>{{ $user->created_at->translatedFormat('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="btn btn-action btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="btn btn-action btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-action btn-success" title="إيداع سريع"
                                                onclick="quickDeposit({{ $user->id }})">
                                                <i class="fas fa-bolt"></i>
                                            </button>
                                            <button type="button" class="btn btn-action btn-danger delete-btn"
                                                title="حذف" data-id="{{ $user->id }}"
                                                data-name="{{ addslashes($user->name) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h5 class="empty-state-text">لا توجد مستخدمين</h5>
                                            <p class="text-muted mb-3">لم يتم إضافة أي مستخدمين حتى الآن</p>
                                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-2"></i>إضافة مستخدم جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="m-3">
                        <nav>
                            <ul class="pagination">
                                {{-- Previous Page --}}
                                @if ($users->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->previousPageUrl() }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pages --}}
                                @foreach ($users->links()->elements[0] as $page => $url)
                                    @if ($page == $users->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page --}}
                                @if ($users->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->nextPageUrl() }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        إيداع رصيد
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="depositForm">
                    @csrf
                    <input type="hidden" name="user_id" id="depositUserId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    المستخدم
                                </label>
                                <input type="text" class="form-control" id="depositUserName" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
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
                            <label class="form-label">
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
                            <label class="form-label">
                                <i class="fas fa-credit-card me-1"></i>
                                طريقة الدفع
                            </label>
                            <select class="form-select" name="payment_method" id="paymentMethod">
                                <option value="bank_transfer">🏦 تحويل بنكي</option>
                                <option value="credit_card">💳 بطاقة ائتمان</option>
                                <option value="cash">💰 نقدي</option>
                                <option value="system">⚙️ نظامي</option>
                                <option value="manual">📝 يدوي</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-hashtag me-1"></i>
                                رقم المرجع/المعاملة
                            </label>
                            <input type="text" class="form-control" name="reference"
                                placeholder="رقم التحويل أو المعاملة (اختياري)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
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
                        <button type="submit" class="btn btn-primary" id="depositSubmitBtn">
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
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-minus-circle me-2"></i>
                        سحب رصيد
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="withdrawForm">
                    @csrf
                    <input type="hidden" name="user_id" id="withdrawUserId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    المستخدم
                                </label>
                                <input type="text" class="form-control" id="withdrawUserName" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
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
                            <label class="form-label">
                                <i class="fas fa-coins me-1"></i>
                                المبلغ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="amount" id="withdrawAmount"
                                    placeholder="أدخل المبلغ" min="1" max="100000" step="0.01" required>
                                <span class="input-group-text">ج.م</span>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                الحد الأدنى: 1 ريال | الحد الأقصى: 100,000 ريال
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-exchange-alt me-1"></i>
                                طريقة السحب
                            </label>
                            <select class="form-select" name="withdrawal_method" id="withdrawalMethod">
                                <option value="bank_transfer">🏦 تحويل بنكي</option>
                                <option value="cash">💰 نقدي</option>
                                <option value="wallet">📱 محفظة رقمية</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>
                                معلومات السحب
                            </label>
                            <textarea class="form-control" name="withdrawal_details" rows="2"
                                placeholder="رقم الحساب البنكي، رقم المحفظة... (اختياري)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
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
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-history me-2"></i>
                        سجل المعاملات
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        console.log('Swal typeof:', typeof Swal);
        console.log('Swal.fire typeof:', typeof Swal?.fire);
        console.log('Swal.version:', Swal?.version);
        console.log('Swal keys:', Object.keys(Swal || {}));
    </script>
    <script>
        // ============================================
        // المتغيرات العامة
        // ============================================
        let currentUserId = null;
        let depositModal = null;
        let withdrawModal = null;
        let historyModal = null;

        // ============================================
        // تهيئة الصفحة
        // ============================================
        $(document).ready(function() {
            // تهيئة الـ Modals
            depositModal = new bootstrap.Modal(document.getElementById('depositModal'));
            withdrawModal = new bootstrap.Modal(document.getElementById('withdrawModal'));
            historyModal = new bootstrap.Modal(document.getElementById('historyModal'));

            // تهيئة Tooltips
            // $('[data-bs-toggle="tooltip"]').tooltip();

            // let searchTimeout;
            // let lastSearch = $('#searchInput').val() || '';

            // $('#searchInput').on('input', function() {
            //     clearTimeout(searchTimeout);

            //     const val = ($(this).val() || '').trim();

            //     // لو نفس القيمة متعملش أي حاجة
            //     if (val === lastSearch) return;

            //     searchTimeout = setTimeout(() => {
            //         lastSearch = val;
            //         updateUrl({
            //             search: val,
            //             page: 1
            //         }, true); // true => بدون reload
            //     }, 500);
            // });

            // تحديث البيانات كل 30 ثانية
            //   setInterval(refreshStats, 30000);

            // ============================================
            // تبديل حالة المستخدم
            // ============================================
            $('.status-toggle').on('change', function() {
                const userId = $(this).data('id');
                const isChecked = $(this).is(':checked');
                const $toggle = $(this);

                $.ajax({
                    url: "{{ url('admin/users') }}/" + userId + "/toggle-status",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'PATCH'
                    },
                    beforeSend: function() {
                        $toggle.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            const $row = $(`tr[data-id="${userId}"]`);
                            const $badge = $row.find('.badge-custom');

                            if (response.is_active) {
                                $badge.removeClass('badge-inactive').addClass('badge-active')
                                    .text('نشط');
                            } else {
                                $badge.removeClass('badge-active').addClass('badge-inactive')
                                    .text('غير نشط');
                            }

                            showNotification('success', 'نجاح', response.message);
                        }
                    },
                    error: function(xhr) {
                        // إعادة التبديل إلى الحالة السابقة
                        $toggle.prop('checked', !isChecked);
                        showNotification('error', 'خطأ', 'حدث خطأ أثناء تغيير الحالة');
                    },
                    complete: function() {
                        $toggle.prop('disabled', false);
                    }
                });
            });

            // ============================================
            // حذف المستخدم
            // ============================================
            $('.delete-btn').on('click', function() {
                const userId = $(this).data('id');
                const userName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    html: `سيتم حذف المستخدم <strong>${userName}</strong> نهائياً<br>جميع بياناته وطلباته ومحفظته سيتم حذفها`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/users') }}/" + userId + "/destroy",

                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'جاري الحذف...',
                                    allowOutsideClick: false,
                                    didOpen: () => Swal.showLoading()
                                });
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.success ||
                                        'تم حذف المستخدم بنجاح',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                let errorMessage = 'حدث خطأ أثناء الحذف';
                                if (xhr.responseJSON?.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: errorMessage,
                                    confirmButtonText: 'حسناً'
                                });
                            }
                        });
                    }
                });
            });

            // ============================================
            // إيداع رصيد
            // ============================================
            $('#depositForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#depositSubmitBtn');
                const spinner = $('#depositSpinner');
                const userId = $('#depositUserId').val();

                if (!userId) {
                    showNotification('error', 'خطأ', 'لم يتم تحديد المستخدم');
                    return;
                }

                // التحقق من المبلغ
                const amount = $('#depositAmount').val();
                if (!amount || amount <= 0) {
                    showNotification('error', 'خطأ', 'الرجاء إدخال مبلغ صحيح');
                    return;
                }

                // إظهار التحميل
                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: "{{ url('admin/users') }}/" + userId + "/wallet/deposit",

                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            depositModal.hide();

                            Swal.fire({
                                icon: 'success',
                                title: 'تم الإيداع بنجاح!',
                                html: `
                                    <div class="text-center">
                                        <div style="font-size: 48px; color: #28a745; margin-bottom: 15px;">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h4>${response.data.amount} ج.م</h4>
                                        <p class="text-muted">تمت إضافتها إلى رصيد المستخدم</p>
                                        <hr>
                                        <div class="row text-start">
                                            <div class="col-6">
                                                <small class="text-muted">الرصيد الجديد:</small>
                                                <strong>${response.data.new_balance} ج.م</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">رقم المعاملة:</small>
                                                <strong>${response.data.reference}</strong>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: 'تم',
                                confirmButtonColor: '#696cff'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            showNotification('error', 'خطأ', response.message ||
                                'حدث خطأ أثناء الإيداع');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ أثناء الإيداع';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat()
                                    .join('\n');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }
                        showNotification('error', 'خطأ', errorMessage);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

            // ============================================
            // سحب رصيد
            // ============================================
            $('#withdrawForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#withdrawSubmitBtn');
                const spinner = $('#withdrawSpinner');
                const userId = $('#withdrawUserId').val();

                if (!userId) {
                    showNotification('error', 'خطأ', 'لم يتم تحديد المستخدم');
                    return;
                }

                // التحقق من المبلغ
                const amount = $('#withdrawAmount').val();
                const available = parseFloat($('#availableBalance').val().replace(/,/g, ''));

                if (!amount || amount <= 0) {
                    showNotification('error', 'خطأ', 'الرجاء إدخال مبلغ صحيح');
                    return;
                }

                if (parseFloat(amount) > available) {
                    showNotification('error', 'خطأ', 'المبلغ المطلوب أكبر من الرصيد المتاح');
                    return;
                }

                // إظهار التحميل
                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({

                    url: "{{ url('admin/users') }}/" + userId + "/wallet/withdraw",

                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            withdrawModal.hide();

                            Swal.fire({
                                icon: 'success',
                                title: 'تم السحب بنجاح!',
                                html: `
                                    <div class="text-center">
                                        <div style="font-size: 48px; color: #28a745; margin-bottom: 15px;">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h4>${response.data.amount} ج.م</h4>
                                        <p class="text-muted">تم سحبها من رصيد المستخدم</p>
                                        <hr>
                                        <div class="row text-start">
                                            <div class="col-6">
                                                <small class="text-muted">الرصيد الجديد:</small>
                                                <strong>${response.data.new_balance} ج.م</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">رقم المعاملة:</small>
                                                <strong>${response.data.reference}</strong>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: 'تم',
                                confirmButtonColor: '#696cff'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            showNotification('error', 'خطأ', response.message ||
                                'حدث خطأ أثناء السحب');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ أثناء السحب';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat()
                                    .join('\n');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }
                        showNotification('error', 'خطأ', errorMessage);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

            // رسائل الجلسة
            @if (session('success'))
                showNotification('success', 'نجاح', "{{ session('success') }}");
            @endif

            @if (session('error'))
                showNotification('error', 'خطأ', "{{ session('error') }}");
            @endif
        });

        // ============================================
        // دوال المحفظة
        // ============================================

        /**
         * عرض مودال الإيداع
         */
        function showDepositModal(userId, userName) {
            if (!userId) {
                showNotification('error', 'خطأ', 'معرف المستخدم غير صحيح');
                return;
            }

            currentUserId = userId;

            $.ajax({

                url: "{{ url('admin/users') }}/" + userId + "/wallet/info",

                type: 'GET',
                beforeSend: function() {
                    Swal.fire({
                        title: 'جاري التحميل...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    Swal.close();

                    if (response.success) {
                        $('#depositUserId').val(userId);
                        $('#depositUserName').val(userName);
                        $('#currentBalance').val(response.data.balance);
                        $('#depositAmount').val('');
                        $('#depositForm')[0].reset();
                        depositModal.show();
                    } else {
                        showNotification('error', 'خطأ', response.message || 'فشل في تحميل معلومات المحفظة');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let errorMessage = 'فشل في تحميل معلومات المحفظة';
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification('error', 'خطأ', errorMessage);
                }
            });
        }

        /**
         * عرض مودال السحب
         */
        function showWithdrawModal(userId, userName) {
            if (!userId) {
                showNotification('error', 'خطأ', 'معرف المستخدم غير صحيح');
                return;
            }

            currentUserId = userId;

            $.ajax({

                url: "{{ url('admin/users') }}/" + userId + "/wallet/info",

                type: 'GET',
                beforeSend: function() {
                    Swal.fire({
                        title: 'جاري التحميل...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    Swal.close();

                    if (response.success) {
                        $('#withdrawUserId').val(userId);
                        $('#withdrawUserName').val(userName);
                        $('#availableBalance').val(response.data.available_balance);
                        $('#withdrawAmount').val('');
                        $('#withdrawForm')[0].reset();
                        withdrawModal.show();
                    } else {
                        showNotification('error', 'خطأ', response.message || 'فشل في تحميل معلومات المحفظة');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let errorMessage = 'فشل في تحميل معلومات المحفظة';
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification('error', 'خطأ', errorMessage);
                }
            });
        }

        /**
         * عرض سجل المعاملات
         */
        function showTransactionHistory(userId, userName) {
            if (!userId) {
                showNotification('error', 'خطأ', 'معرف المستخدم غير صحيح');
                return;
            }

            currentUserId = userId;

            $('#historyUserName').html(`
                <i class="fas fa-user me-2"></i>
                سجل معاملات: <strong>${userName}</strong>
            `);
            $('#historyUserInfo').html(`
                <i class="fas fa-info-circle me-1"></i>
                جميع المعاملات المالية للمستخدم
            `);

            $('#transactionHistory').html(`
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mt-3 text-muted">جاري تحميل سجل المعاملات...</p>
                </div>
            `);

            loadTransactions(userId);
            historyModal.show();
        }

        /**
         * تحميل المعاملات
         */
        function loadTransactions(userId) {
            $.ajax({
                url: "{{ url('admin/users') }}/" + userId + "/wallet/transactions",

                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        let html = '';

                        if (response.data.transactions && response.data.transactions.length > 0) {
                            response.data.transactions.forEach(transaction => {
                                let typeClass = '';
                                let typeText = '';
                                let typeIcon = '';

                                switch (transaction.type) {
                                    case 'deposit':
                                        typeClass = 'type-deposit';
                                        typeText = 'إيداع';
                                        typeIcon = 'fas fa-plus-circle';
                                        break;
                                    case 'withdrawal':
                                        typeClass = 'type-withdrawal';
                                        typeText = 'سحب';
                                        typeIcon = 'fas fa-minus-circle';
                                        break;
                                    case 'transfer_in':
                                        typeClass = 'type-transfer';
                                        typeText = 'تحويل وارد';
                                        typeIcon = 'fas fa-arrow-right';
                                        break;
                                    case 'transfer_out':
                                        typeClass = 'type-transfer';
                                        typeText = 'تحويل صادر';
                                        typeIcon = 'fas fa-arrow-left';
                                        break;
                                    case 'payment':
                                        typeClass = 'type-transfer';
                                        typeText = 'دفع';
                                        typeIcon = 'fas fa-shopping-cart';
                                        break;
                                    case 'refund':
                                        typeClass = 'type-deposit';
                                        typeText = 'استرداد';
                                        typeIcon = 'fas fa-undo-alt';
                                        break;
                                    case 'adjustment':
                                        typeClass = 'type-transfer';
                                        typeText = 'تعديل';
                                        typeIcon = 'fas fa-sliders-h';
                                        break;
                                    default:
                                        typeClass = 'type-transfer';
                                        typeText = transaction.type || 'معاملة';
                                        typeIcon = 'fas fa-exchange-alt';
                                }

                                const amount = parseFloat(transaction.amount || 0);
                                const formattedAmount = amount.toLocaleString('ar-SA', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });

                                html += `
                                    <div class="transaction-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="transaction-type ${typeClass}">
                                                    <i class="${typeIcon} me-1"></i>
                                                    ${typeText}
                                                </span>
                                                <strong>${transaction.description || 'لا يوجد وصف'}</strong>
                                            </div>
                                            <div class="text-end">
                                                <div class="transaction-amount ${amount >= 0 ? 'text-success' : 'text-danger'}">
                                                    ${amount >= 0 ? '+' : ''}${formattedAmount} ج.م
                                                </div>
                                                <div class="transaction-date">
                                                    <i class="fas fa-clock me-1"></i>
                                                    ${transaction.formatted_date || transaction.created_at || ''}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-hashtag me-1"></i> 
                                                    المرجع: ${transaction.reference || '---'}
                                                </small>
                                                <small class="text-muted ms-3">
                                                    <i class="fas fa-info-circle me-1"></i> 
                                                    الحالة: <span class="badge bg-${transaction.status === 'completed' ? 'success' : 'warning'}">
                                                        ${transaction.status_text || transaction.status || 'مكتمل'}
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = `
                                <div class="text-center p-5">
                                    <div style="font-size: 60px; color: var(--bs-secondary-color); margin-bottom: 20px;">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">لا توجد معاملات حتى الآن</h5>
                                    <p class="text-muted">لم يقم هذا المستخدم بأي عمليات مالية</p>
                                </div>
                            `;
                        }

                        $('#transactionHistory').html(html);
                    } else {
                        $('#transactionHistory').html(`
                            <div class="text-center p-5">
                                <div style="font-size: 60px; color: #ffc107; margin-bottom: 20px;">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <h5 class="text-warning">${response.message || 'لا توجد بيانات'}</h5>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'فشل في تحميل سجل المعاملات';
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#transactionHistory').html(`
                        <div class="text-center p-5">
                            <div style="font-size: 60px; color: #dc3545; margin-bottom: 20px;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5 class="text-danger">${errorMessage}</h5>
                            <button class="btn btn-outline-primary mt-3" onclick="loadTransactions(${userId})">
                                <i class="fas fa-sync-alt me-2"></i>إعادة المحاولة
                            </button>
                        </div>
                    `);
                }
            });
        }

        /**
         * إنشاء محفظة جديدة
         */
        function createWallet(userId) {
            if (!userId) {
                showNotification('error', 'خطأ', 'معرف المستخدم غير صحيح');
                return;
            }

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
                        url: "{{ url('admin/users') }}/" + userId + "/wallet/create",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'جاري إنشاء المحفظة...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم إنشاء المحفظة',
                                    text: response.message || 'تم إنشاء المحفظة بنجاح',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: response.message || 'فشل في إنشاء المحفظة',
                                    confirmButtonText: 'حسناً'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'فشل في إنشاء المحفظة';
                            if (xhr.responseJSON?.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: errorMessage,
                                confirmButtonText: 'حسناً'
                            });
                        }
                    });
                }
            });
        }

        /**
         * إيداع سريع
         */
        function quickDeposit(userId) {
            if (!userId) {
                showNotification('error', 'خطأ', 'معرف المستخدم غير صحيح');
                return;
            }

            Swal.fire({
                title: 'إيداع سريع',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">المبلغ (ريال)</label>
                            <div class="input-group">
                                <input type="number" id="quickAmount" class="form-control form-control-lg" 
                                    placeholder="أدخل المبلغ" min="1" max="10000" step="0.01" required>
                                <span class="input-group-text">ج.م</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">الملاحظات</label>
                            <textarea id="quickDescription" class="form-control" rows="2" 
                                placeholder="ملاحظات حول الإيداع (اختياري)"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            الحد الأقصى للإيداع السريع: 10,000 ريال
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'إيداع',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                preConfirm: () => {
                    const amount = $('#quickAmount').val();

                    if (!amount) {
                        Swal.showValidationMessage('الرجاء إدخال المبلغ');
                        return false;
                    }

                    if (parseFloat(amount) <= 0) {
                        Swal.showValidationMessage('المبلغ يجب أن يكون أكبر من صفر');
                        return false;
                    }

                    if (parseFloat(amount) > 10000) {
                        Swal.showValidationMessage('المبلغ يتجاوز الحد الأقصى المسموح به');
                        return false;
                    }

                    return {
                        amount: parseFloat(amount).toFixed(2),
                        description: $('#quickDescription').val() || 'إيداع سريع'
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/users') }}/" + userId + "/wallet/quick-deposit",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            amount: result.value.amount,
                            description: result.value.description
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'جاري الإيداع...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الإيداع بنجاح!',
                                    html: `
                                        <div class="text-center">
                                            <div style="font-size: 48px; color: #28a745; margin-bottom: 15px;">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <h3>${response.data.amount} ج.م</h3>
                                            <p class="text-muted">تم إضافتها إلى محفظة المستخدم</p>
                                            <hr>
                                            <p class="mb-0">
                                                <strong>الرصيد الجديد:</strong> ${response.data.new_balance} ج.م
                                            </p>
                                        </div>
                                    `,
                                    showConfirmButton: true,
                                    confirmButtonText: 'تم',
                                    confirmButtonColor: '#696cff'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: response.message || 'حدث خطأ أثناء الإيداع',
                                    confirmButtonText: 'حاول مرة أخرى'
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
                                confirmButtonText: 'حاول مرة أخرى'
                            });
                        }
                    });
                }
            });
        }

        /**
         * تصدير المعاملات الحالية
         */
        function exportCurrentTransactions() {
            if (!currentUserId) {
                showNotification('error', 'خطأ', 'لم يتم تحديد المستخدم');
                return;
            }

            Swal.fire({
                title: 'تصدير سجل المعاملات',
                text: 'سيتم تصدير جميع المعاملات إلى ملف CSV',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'تصدير',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#696cff',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.users.wallet.export-transactions', '') }}/" +
                        currentUserId;
                }
            });
        }

        /**
         * تصدير جميع المستخدمين
         */
        function exportUsers() {
            Swal.fire({
                title: 'تصدير المستخدمين',
                text: 'سيتم تصدير جميع المستخدمين إلى ملف Excel',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'تصدير',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#696cff',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.users.export') }}";
                }
            });
        }

        /**
         * تحديث الإحصائيات
         */
        function refreshStats() {
            $.ajax({
                url: '{{ route('admin.users.stats') }}',
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        $('.stat-card.users .stat-value').text(data.totalUsers);
                        $('.stat-card.wallet .stat-value').text(data.totalWalletBalance + ' ج.م');
                        $('.stat-card.deposit .stat-value').text(data.totalDeposits + ' ج.م');
                        $('.stat-card.withdrawal .stat-value').text(data.totalWithdrawals + ' ج.م');
                    }
                }
            });
        }

        /**
         * تحديث البيانات
         */
        function refreshData() {
            location.reload();
        }

        // ============================================
        // دوال المساعدة
        // ============================================

        /**
         * عرض إشعار
         */
        function showNotification(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                timer: 2000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        }

        /**
         * تصفية حسب النوع
         */
        function filterBy(type) {
            const params = {};
            if (type !== 'all') {
                params.type = type;
            }
            updateUrl(params);
        }

        /**
         * ترتيب حسب الحقل
         */
        function sortBy(sortBy, sortDirection) {
            updateUrl({
                sort_by: sortBy,
                sort_direction: sortDirection
            });
        }

        /**
         * تحديث URL
         */
        function updateUrl(params, usePushState = false) {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);

            Object.keys(params).forEach(key => {
                const v = params[key];

                if (v === null || v === undefined || v === '') {
                    searchParams.delete(key);
                } else {
                    searchParams.set(key, v);
                }
            });

            url.search = searchParams.toString();

            // ✅ بدل ما تعمل reload (اللي ممكن يعمل popups/redirects)
            if (usePushState) {
                window.history.replaceState({}, '', url.toString());
                return;
            }

            window.location.assign(url.toString());
        }
    </script>
@endsection
