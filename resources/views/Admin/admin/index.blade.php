@extends('Admin.layout.master')

@section('title', 'إدارة المشرفين')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .admins-dashboard {
            padding: 20px 0;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-right: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card:hover::before {
            transform: scale(3);
        }

        .stat-card.total {
            border-right-color: #696cff;
        }

        .stat-card.active {
            border-right-color: #198754;
        }

        .stat-card.inactive {
            border-right-color: #dc3545;
        }

        .stat-card.super-admin {
            border-right-color: #fd7e14;
        }

        .stat-card.admin {
            border-right-color: #0dcaf0;
        }

        .stat-card.moderator {
            border-right-color: #6f42c1;
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
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
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.active .stat-icon {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        .stat-card.inactive .stat-icon {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .stat-card.super-admin .stat-icon {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        }

        .stat-card.admin .stat-icon {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
        }

        .stat-card.moderator .stat-icon {
            background: linear-gradient(135deg, #6f42c1 0%, #d63384 100%);
        }

        .stat-info {
            flex: 1;
        }

        .stat-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--bs-secondary-color);
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--bs-heading-color);
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 12px;
            color: var(--bs-secondary-color);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Filter Section */
        .filter-section {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--bs-border-color);
        }

        .filter-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .filter-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-left: 15px;
            box-shadow: 0 5px 10px rgba(102, 126, 234, 0.3);
        }

        .filter-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .filter-subtitle {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .input-group-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--bs-border-color);
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.15);
        }

        /* Admins Table */
        .admins-table-section {
            background: var(--bs-card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--bs-border-color);
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .table-title {
            display: flex;
            align-items: center;
        }

        .table-icon {
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
            box-shadow: 0 5px 10px rgba(102, 126, 234, 0.3);
        }

        .table-title h5 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--bs-heading-color);
        }

        .table-title p {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin-bottom: 0;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border: none;
        }

        .btn-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
            border: none;
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);
            border: none;
        }

        .admins-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .admins-table th {
            background: rgb(39 9 9 / 15%);
            padding: 15px;
            font-weight: 700;
            color: var(--bs-heading-color);
            border: none;
            font-size: 14px;
            text-align: center;
        }

        .admins-table td {
            background: rgb(39 9 9 / 15%);
            padding: 15px;
            vertical-align: middle;
            border: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .admins-table tbody tr {
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .admins-table tbody tr:hover td {
            background: var(--bs-card-bg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: scale(1.01);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: right;
        }

        .admin-avatar {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 600;
            position: relative;
            flex-shrink: 0;
        }

        .admin-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .admin-avatar .status-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--bs-card-bg);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .admin-avatar .status-dot.active {
            background: #28a745;
            animation: pulse 2s infinite;
        }

        .admin-avatar .status-dot.inactive {
            background: #dc3545;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        .admin-details {
            text-align: right;
        }

        .admin-details h6 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--bs-heading-color);
        }

        .admin-details span {
            font-size: 12px;
            color: var(--bs-secondary-color);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .role-badge {
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .role-badge i {
            font-size: 14px;
        }

        .role-badge.super_admin {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
            color: white;
        }

        .role-badge.admin {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
            color: white;
        }

        .role-badge.moderator {
            background: linear-gradient(135deg, #6f42c1 0%, #d63384 100%);
            color: white;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .status-badge i {
            font-size: 14px;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #dc3545 0%, #d63384 100%);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .action-btn:hover::before {
            width: 100px;
            height: 100px;
        }

        .action-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .action-btn.view {
            background: linear-gradient(135deg, #696cff 0%, #4a4c9e 100%);
        }

        .action-btn.edit {
            background: linear-gradient(135deg, #198754 0%, #0f5132 100%);
        }

        .action-btn.delete {
            background: linear-gradient(135deg, #dc3545 0%, #a11a2a 100%);
        }

        .action-btn.status {
            background: linear-gradient(135deg, #fd7e14 0%, #b85c0a 100%);
        }

        .action-btn.reset-password {
            background: linear-gradient(135deg, #0dcaf0 0%, #0a97c2 100%);
        }

        /* Bulk Actions */
        .bulk-actions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 20px;
            border-radius: 12px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            color: white;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bulk-actions .form-check-label {
            color: white;
        }

        .bulk-actions .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .bulk-actions .btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Pagination */
        .pagination {
            margin-top: 30px;
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            border-radius: 10px;
            margin: 0 3px;
            color: var(--bs-secondary-color);
            border: 1px solid var(--bs-border-color);
            padding: 10px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .page-item.disabled .page-link {
            background: var(--bs-light-bg-subtle);
            color: var(--bs-secondary-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 60px;
            color: var(--bs-secondary-color);
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h5 {
            font-size: 20px;
            font-weight: 700;
            color: var(--bs-heading-color);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--bs-secondary-color);
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .table-actions {
                width: 100%;
                flex-direction: column;
            }

            .table-actions .btn {
                width: 100%;
            }

            .bulk-actions {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .admins-table {
                display: block;
                overflow-x: auto;
            }

            .admin-info {
                flex-direction: column;
                text-align: center;
            }

            .admin-details {
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-section .row > div {
                margin-bottom: 15px;
            }

            .d-flex {
                flex-direction: column;
            }

            .d-flex .btn {
                width: 100%;
                margin: 5px 0;
            }
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .spinner-container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #696cff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Tooltip */
        .tooltip {
            font-family: "Cairo", sans-serif;
        }

        .tooltip .tooltip-inner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
        }

        .bs-tooltip-top .tooltip-arrow::before {
            border-top-color: #764ba2;
        }

        /* Checkbox Style */
        .form-check-input {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid var(--bs-border-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #696cff;
            border-color: #696cff;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.15);
            border-color: #696cff;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">
                        <i class="fas fa-home ms-1"></i>
                        الرئيسية
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <i class="fas fa-user-shield ms-1"></i>
                    إدارة المشرفين
                </li>
            </ol>
        </nav>

        <div class="admins-dashboard">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">إجمالي المشرفين</div>
                            <div class="stat-value">{{ $stats['total'] }}</div>
                            <div class="stat-change">
                                <i class="fas fa-user-plus"></i>
                                جميع المشرفين المسجلين
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-card active">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">المشرفين النشطين</div>
                            <div class="stat-value">{{ $stats['active'] }}</div>
                            <div class="stat-change">
                                <i class="fas fa-chart-line"></i>
                                {{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}% من الإجمالي
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-card inactive">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">المشرفين غير النشطين</div>
                            <div class="stat-value">{{ $stats['inactive'] }}</div>
                            <div class="stat-change">
                                <i class="fas fa-chart-line"></i>
                                {{ $stats['total'] > 0 ? round(($stats['inactive'] / $stats['total']) * 100) : 0 }}% من الإجمالي
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-card super-admin">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">المشرفين الرئيسيين</div>
                            <div class="stat-value">{{ $stats['super_admins'] }}</div>
                            <div class="stat-change">
                                <i class="fas fa-star"></i>
                                صلاحيات كاملة
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-card admin">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">مديرين النظام</div>
                            <div class="stat-value">{{ $stats['admins'] }}</div>
                            <div class="stat-change">
                                <i class="fas fa-tools"></i>
                                صلاحيات إدارية
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-card moderator">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">مشرفين</div>
                            <div class="stat-value">{{ $stats['moderators'] }}</div>
                            <div class="stat-change">
                                <i class="fas fa-eye"></i>
                                صلاحيات محدودة
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-header">
                    <div class="filter-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <h5 class="filter-title">فلترة البحث</h5>
                        <p class="filter-subtitle">ابحث وصفي المشرفين حسب المعايير التالية</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.admins.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-search ms-1"></i>
                                بحث
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="البحث بالاسم أو البريد أو الهاتف..."
                                       autocomplete="off">
                            </div>
                            <small class="text-muted">يمكنك البحث بالاسم والبريد الإلكتروني ورقم الهاتف</small>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-flag ms-1"></i>
                                الحالة
                            </label>
                            <select class="form-select" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-tag ms-1"></i>
                                الدور
                            </label>
                            <select class="form-select" name="role">
                                <option value="">جميع الأدوار</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->display_name ?? $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">
                                <i class="fas fa-list ms-1"></i>
                                عرض
                            </label>
                            <select class="form-select" name="per_page">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-sort ms-1"></i>
                                ترتيب
                            </label>
                            <select class="form-select" name="sort_by">
                                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>الاسم</option>
                                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>البريد</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-sort-amount-down ms-1"></i>
                                الاتجاه
                            </label>
                            <select class="form-select" name="sort_dir">
                                <option value="desc" {{ request('sort_dir', 'desc') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                                <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <hr class="my-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>
                                    تطبيق الفلتر
                                </button>
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    إلغاء الفلتر
                                </a>
                                <a href="{{ route('admin.admins.create') }}" class="btn btn-success ms-auto">
                                    <i class="fas fa-plus me-1"></i>
                                    إضافة مشرف جديد
                                </a>
                                <button type="button" class="btn btn-info" onclick="exportAdmins()">
                                    <i class="fas fa-file-export me-1"></i>
                                    تصدير CSV
                                </button>
                                <button type="button" class="btn btn-warning" onclick="printTable()">
                                    <i class="fas fa-print me-1"></i>
                                    طباعة
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Admins Table -->
            <div class="admins-table-section">
                <div class="table-header">
                    <div class="table-title">
                        <div class="table-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div>
                            <h5>قائمة المشرفين</h5>
                            <p>
                                <i class="fas fa-users me-1"></i>
                                إجمالي {{ $admins->total() }} مشرف، 
                                عرض {{ $admins->firstItem() ?? 0 }} - {{ $admins->lastItem() ?? 0 }}
                            </p>
                        </div>
                    </div>
                    <div class="table-actions">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="refreshTable()" data-bs-toggle="tooltip" title="تحديث">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleBulkActions()" data-bs-toggle="tooltip" title="تحديد متعدد">
                                <i class="fas fa-check-double"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="toggleColumns()" data-bs-toggle="tooltip" title="إظهار/إخفاء الأعمدة">
                                <i class="fas fa-columns"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions" style="display: none;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label fw-bold" for="selectAll">
                            تحديد الكل
                        </label>
                    </div>
                    <span id="selectedCount" class="text-white fw-bold">0 مشرف محدد</span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> حذف المحدد
                        </button>
                        <button type="button" class="btn btn-sm" onclick="bulkStatus('active')">
                            <i class="fas fa-check-circle"></i> تفعيل
                        </button>
                        <button type="button" class="btn btn-sm" onclick="bulkStatus('inactive')">
                            <i class="fas fa-times-circle"></i> تعطيل
                        </button>
                    </div>
                    <button type="button" class="btn-close ms-auto" onclick="toggleBulkActions()" aria-label="Close"></button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="admins-table" id="adminsTable">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input bulk-checkbox-header" type="checkbox">
                                    </div>
                                </th>
                                <th>المشرف</th>
                                <th>معلومات الاتصال</th>
                                <th>الدور</th>
                                <th>الحالة</th>
                                <th>آخر نشاط</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="280">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                                <tr onclick="viewAdminDetails({{ $admin->id }})">
                                    <td onclick="event.stopPropagation()">
                                        <div class="form-check">
                                            <input class="form-check-input bulk-checkbox" 
                                                   type="checkbox" 
                                                   value="{{ $admin->id }}"
                                                   {{ $admin->id === auth()->id() ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="admin-info">
                                            <div class="admin-avatar">
                                                @if($admin->avatar)
                                                    <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}">
                                                @else
                                                    {{ substr($admin->name, 0, 1) }}
                                                @endif
                                                <span class="status-dot {{ $admin->is_active ? 'active' : 'inactive' }}"></span>
                                            </div>
                                            <div class="admin-details">
                                                <h6>{{ $admin->name }}</h6>
                                                <span>
                                                    <i class="fas fa-envelope"></i>
                                                    {{ $admin->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="d-block mb-1">
                                                <i class="fas fa-envelope ms-1"></i>
                                                {{ $admin->email }}
                                            </span>
                                            @if($admin->phone)
                                                <span class="d-block text-muted small">
                                                    <i class="fas fa-phone ms-1"></i>
                                                    {{ $admin->phone }}
                                                </span>
                                            @else
                                                <span class="d-block text-muted small">
                                                    <i class="fas fa-phone-slash ms-1"></i>
                                                    لا يوجد رقم
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($admin->roles as $role)
                                            <span class="role-badge {{ $role->name }}">
                                                <i class="fas 
                                                    @if($role->name == 'super_admin') fa-crown
                                                    @elseif($role->name == 'admin') fa-user-tie
                                                    @else fa-user-shield
                                                    @endif">
                                                </i>
                                                {{ $role->display_name ?? $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $admin->is_active ? 'active' : 'inactive' }}">
                                            <i class="fas {{ $admin->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $admin->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="fas fa-clock ms-1"></i>
                                            {{ $admin->updated_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="fas fa-calendar ms-1"></i>
                                            {{ $admin->created_at->format('Y-m-d') }}
                                            <br>
                                            {{ $admin->created_at->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td onclick="event.stopPropagation()">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.admins.show', $admin->id) }}" 
                                               class="action-btn view" 
                                               data-bs-toggle="tooltip" 
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                                               class="action-btn edit"
                                               data-bs-toggle="tooltip" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if(!$admin->isSuperAdmin() && $admin->id !== auth()->id())
                                                <button type="button" 
                                                        class="action-btn status" 
                                                        onclick="toggleAdminStatus({{ $admin->id }}, event)"
                                                        data-bs-toggle="tooltip" 
                                                        title="{{ $admin->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas {{ $admin->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                                </button>
                                                
                                                <button type="button" 
                                                        class="action-btn reset-password" 
                                                        onclick="resetPassword({{ $admin->id }}, event)"
                                                        data-bs-toggle="tooltip" 
                                                        title="إعادة تعيين كلمة المرور">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                
                                                <button type="button" 
                                                        class="action-btn delete" 
                                                        onclick="deleteAdmin({{ $admin->id }}, event)"
                                                        data-bs-toggle="tooltip" 
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-user-shield"></i>
                                            <h5>لا يوجد مشرفين</h5>
                                            <p class="text-muted">لم يتم إضافة أي مشرفين حتى الآن</p>
                                            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                إضافة مشرف جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($admins->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $admins->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-container">
            <div class="spinner"></div>
            <h6 class="mt-3">جاري التحميل...</h6>
        </div>
    </div>

    <!-- Column Toggle Modal -->
    <div class="modal fade" id="columnToggleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-columns ms-2"></i>
                        إظهار/إخفاء الأعمدة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input column-toggle" type="checkbox" id="colPhone" checked>
                        <label class="form-check-label" for="colPhone">معلومات الاتصال</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input column-toggle" type="checkbox" id="colRole" checked>
                        <label class="form-check-label" for="colRole">الدور</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input column-toggle" type="checkbox" id="colStatus" checked>
                        <label class="form-check-label" for="colStatus">الحالة</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input column-toggle" type="checkbox" id="colLastActivity" checked>
                        <label class="form-check-label" for="colLastActivity">آخر نشاط</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input column-toggle" type="checkbox" id="colCreatedAt" checked>
                        <label class="form-check-label" for="colCreatedAt">تاريخ الإنشاء</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="applyColumnToggle()">تطبيق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // تفعيل tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // تحديد/إلغاء تحديد الكل
            $('#selectAll').change(function() {
                $('.bulk-checkbox:enabled').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            $('.bulk-checkbox-header').change(function() {
                $('.bulk-checkbox:enabled').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            // تحديث عداد المحدد عند تغيير أي checkbox
            $(document).on('change', '.bulk-checkbox', function() {
                updateSelectedCount();
                
                // تحديث حالة select all
                var totalCheckboxes = $('.bulk-checkbox:enabled').length;
                var checkedCheckboxes = $('.bulk-checkbox:enabled:checked').length;
                var allChecked = totalCheckboxes === checkedCheckboxes;
                
                $('#selectAll').prop('checked', allChecked);
                $('.bulk-checkbox-header').prop('checked', allChecked);
            });

            // تحميل تفضيلات الأعمدة المحفوظة
            loadColumnPreferences();

            // Auto-refresh كل 5 دقائق
            setInterval(function() {
                refreshTableSilent();
            }, 300000);
        });

        // تحديث عداد المحددين
        function updateSelectedCount() {
            var count = $('.bulk-checkbox:enabled:checked').length;
            $('#selectedCount').text(count + ' مشرف محدد');
            
            if (count > 0) {
                $('#bulkActions').show();
            } else {
                $('#bulkActions').hide();
            }
        }

        // عرض تفاصيل المشرف
        function viewAdminDetails(id) {
            window.location.href = '{{ route("admin.admins.index") }}/' + id;
        }

        // تبديل ظهور إجراءات المجموعة
        function toggleBulkActions() {
            $('#bulkActions').toggle();
            if ($('#bulkActions').is(':visible')) {
                updateSelectedCount();
            }
        }

        // تبديل ظهور الأعمدة
        function toggleColumns() {
            $('#columnToggleModal').modal('show');
        }

        // تحميل تفضيلات الأعمدة
        function loadColumnPreferences() {
            var preferences = localStorage.getItem('adminTableColumns');
            if (preferences) {
                var cols = JSON.parse(preferences);
                $('.column-toggle').each(function() {
                    var id = $(this).attr('id');
                    var isChecked = cols[id] !== false;
                    $(this).prop('checked', isChecked);
                });
                applyColumnToggle();
            }
        }

        // تطبيق إظهار/إخفاء الأعمدة
        function applyColumnToggle() {
            var preferences = {};
            
            $('.column-toggle').each(function() {
                var id = $(this).attr('id');
                var isChecked = $(this).prop('checked');
                preferences[id] = isChecked;
                
                // إخفاء/إظهار الأعمدة
                if (id === 'colPhone') {
                    $('.admins-table th:nth-child(3), .admins-table td:nth-child(3)').toggle(isChecked);
                } else if (id === 'colRole') {
                    $('.admins-table th:nth-child(4), .admins-table td:nth-child(4)').toggle(isChecked);
                } else if (id === 'colStatus') {
                    $('.admins-table th:nth-child(5), .admins-table td:nth-child(5)').toggle(isChecked);
                } else if (id === 'colLastActivity') {
                    $('.admins-table th:nth-child(6), .admins-table td:nth-child(6)').toggle(isChecked);
                } else if (id === 'colCreatedAt') {
                    $('.admins-table th:nth-child(7), .admins-table td:nth-child(7)').toggle(isChecked);
                }
            });
            
            localStorage.setItem('adminTableColumns', JSON.stringify(preferences));
            $('#columnToggleModal').modal('hide');
        }

        // حذف متعدد
        function bulkDelete() {
            var ids = [];
            $('.bulk-checkbox:enabled:checked').each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'الرجاء تحديد مشرفين على الأقل',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#696cff'
                });
                return;
            }

            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل أنت متأكد من حذف ' + ids.length + ' مشرف؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    
                    $.ajax({
                        url: '{{ route("admin.admins.bulk-delete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: function(response) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // تغيير حالة متعددة
        function bulkStatus(status) {
            var ids = [];
            $('.bulk-checkbox:enabled:checked').each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'الرجاء تحديد مشرفين على الأقل',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#696cff'
                });
                return;
            }

            var action = status === 'active' ? 'تفعيل' : 'تعطيل';
            
            Swal.fire({
                title: 'تأكيد ' + action,
                text: 'هل أنت متأكد من ' + action + ' ' + ids.length + ' مشرف؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'active' ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، ' + action,
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    
                    $.ajax({
                        url: '{{ route("admin.admins.bulk-status") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids,
                            status: status
                        },
                        success: function(response) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // تغيير حالة مشرف
        function toggleAdminStatus(id, event) {
            event.stopPropagation();
            
            Swal.fire({
                title: 'تأكيد تغيير الحالة',
                text: 'هل أنت متأكد من تغيير حالة هذا المشرف؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#fd7e14',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، تغيير',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    
                    $.ajax({
                        url: '{{ route("admin.admins.toggle-status", "") }}/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // إعادة تعيين كلمة المرور
        function resetPassword(id, event) {
            event.stopPropagation();
            
            Swal.fire({
                title: 'إعادة تعيين كلمة المرور',
                html: `
                    <div class="text-end">
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" id="password" class="form-control" placeholder="أدخل كلمة المرور الجديدة">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" id="password_confirmation" class="form-control" placeholder="أعد إدخال كلمة المرور">
                        </div>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'حفظ',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#0dcaf0',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                preConfirm: () => {
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;
                    
                    if (!password || !passwordConfirmation) {
                        Swal.showValidationMessage('يرجى إدخال كلمة المرور وتأكيدها');
                        return false;
                    }
                    
                    if (password.length < 8) {
                        Swal.showValidationMessage('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
                        return false;
                    }
                    
                    if (password !== passwordConfirmation) {
                        Swal.showValidationMessage('كلمة المرور غير متطابقة');
                        return false;
                    }
                    
                    return { 
                        password: password, 
                        password_confirmation: passwordConfirmation 
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    
                    $.ajax({
                        url: '{{ route("admin.admins.reset-password", "") }}/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            password: result.value.password,
                            password_confirmation: result.value.password_confirmation
                        },
                        success: function(response) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // حذف مشرف
        function deleteAdmin(id, event) {
            event.stopPropagation();
            
            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل أنت متأكد من حذف هذا المشرف؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    
                    $.ajax({
                        url: '{{ route("admin.admins.destroy", "") }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'success',
                                title: 'تم!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            hideSpinner();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
                }
            });
        }

        // تحديث الجدول
        function refreshTable() {
            Swal.fire({
                title: 'جاري التحديث...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            location.reload();
        }

        // تحديث الجدول بصمت
        function refreshTableSilent() {
            $.ajax({
                url: window.location.href,
                type: 'GET',
                success: function(response) {
                    // تحديث البيانات بدون إعادة تحميل الصفحة
                    console.log('تم تحديث البيانات');
                }
            });
        }

        // تصدير البيانات
        function exportAdmins() {
            var params = new URLSearchParams(window.location.search);
            var url = '{{ route("admin.admins.export") }}?' + params.toString();
            
            window.location.href = url;
            
            Swal.fire({
                icon: 'success',
                title: 'جاري التصدير',
                text: 'سيبدأ تحميل الملف خلال لحظات',
                timer: 2000,
                showConfirmButton: false
            });
        }


        // إظهار مؤشر التحميل
        function showSpinner() {
            $('#spinnerOverlay').fadeIn();
        }

        // إخفاء مؤشر التحميل
        function hideSpinner() {
            $('#spinnerOverlay').fadeOut();
        }

        // حفظ الفلتر في localStorage
        function saveFilterPreferences() {
            var preferences = {
                search: $('input[name="search"]').val(),
                status: $('select[name="status"]').val(),
                role: $('select[name="role"]').val(),
                per_page: $('select[name="per_page"]').val(),
                sort_by: $('select[name="sort_by"]').val(),
                sort_dir: $('select[name="sort_dir"]').val()
            };
            
            localStorage.setItem('adminFilterPreferences', JSON.stringify(preferences));
        }

        // تحميل الفلتر من localStorage
        function loadFilterPreferences() {
            var preferences = localStorage.getItem('adminFilterPreferences');
            if (preferences) {
                var prefs = JSON.parse(preferences);
                $('input[name="search"]').val(prefs.search || '');
                $('select[name="status"]').val(prefs.status || '');
                $('select[name="role"]').val(prefs.role || '');
                $('select[name="per_page"]').val(prefs.per_page || '10');
                $('select[name="sort_by"]').val(prefs.sort_by || 'created_at');
                $('select[name="sort_dir"]').val(prefs.sort_dir || 'desc');
            }
        }

        // حفظ الفلتر عند التقديم
        $('#filterForm').on('submit', function() {
            saveFilterPreferences();
        });

        // تفعيل البحث التلقائي بعد 1.5 ثانية من التوقف عن الكتابة
        var searchTimeout;
        $('input[name="search"]').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                $('#filterForm').submit();
            }, 1500);
        });

        // تحديث عند تغيير per_page
        $('select[name="per_page"]').on('change', function() {
            $('#filterForm').submit();
        });
    </script>
@endsection