{{-- resources/views/Admin/orders/create.blade.php --}}
@extends('Admin.layout.master')

@section('title', 'إنشاء طلب جديد')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .form-section {
            background: rgba(105, 108, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(105, 108, 255, 0.1);
        }

        .form-section h6 {
            color: #696cff;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .alert-guide {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            color: white;
        }

        .alert-guide h6 {
            color: white;
            margin-bottom: 15px;
        }

        .alert-guide ul {
            margin-bottom: 0;
            padding-right: 20px;
        }

        .alert-guide li {
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* نافذة اختيار المنتجات */
        .products-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .products-modal.active {
            display: flex;
        }

        /* .product-info .product-details h6 {
                            color: #ffffff !important;
                        } */

        .products-modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .products-modal-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .products-modal-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .products-modal-header .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
        }

        .products-modal-header .close-btn:hover {
            color: #dc3545;
        }

        .products-search {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .search-input-group {
            display: flex;
            gap: 10px;
        }

        .search-input-group input {
            flex: 1;
            padding: 10px 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .search-input-group input:focus {
            outline: none;
            border-color: #696cff;
        }

        .search-input-group button {
            padding: 10px 20px;
            background: #696cff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-input-group button:hover {
            background: #4f52d9;
        }

        .products-list {
            padding: 20px;
            overflow-y: auto;
            max-height: 400px;
        }

        .product-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .product-card:hover {
            border-color: #696cff;
            background: rgba(105, 108, 255, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card.selected {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.05);
        }

        .product-card.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            left: 10px;
            color: #28a745;
            font-size: 18px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .product-details {
            flex: 1;
        }

        .product-details h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #495e72;
        }

        .product-details p {
            margin: 0;
            color: #6c757d;
            font-size: 13px;
        }

        .product-price {
            font-weight: 600;
            color: #28a745;
            font-size: 16px;
            margin-left: 15px;
        }

        .product-stock {
            font-size: 12px;
            color: #6c757d;
            min-width: 80px;
        }

        .provider-like4app {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 11px;
        }

        .provider-internal {
            background: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 11px;
        }

        .pagination-container {
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 13px;
        }

        .pagination-buttons {
            display: flex;
            gap: 10px;
        }

        .pagination-buttons button {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            color: #6c757d;
        }

        .pagination-buttons button:hover:not(:disabled) {
            background: #696cff;
            color: white;
            border-color: #696cff;
        }

        .pagination-buttons button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .modal-actions {
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f8f9fa;
        }

        .modal-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .modal-actions .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .modal-actions .btn-cancel:hover {
            background: #5a6268;
        }

        .modal-actions .btn-add {
            background: #28a745;
            color: white;
        }

        .modal-actions .btn-add:hover {
            background: #218838;
        }

        .modal-actions .btn-add:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background: rgba(105, 108, 255, 0.1);
            padding: 12px;
            text-align: right;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .item-row:hover {
            background: rgba(105, 108, 255, 0.05);
        }

        .quantity-input {
            width: 80px;
            text-align: center;
        }

        .price-input {
            width: 120px;
            text-align: left;
        }

        .remove-item {
            color: #dc3545;
            cursor: pointer;
            transition: color 0.3s;
        }

        .remove-item:hover {
            color: #bd2130;
        }

        .summary-card {
            background: rgba(105, 108, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(105, 108, 255, 0.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            font-weight: 600;
            color: #495057;
        }

        .summary-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .total-row {
            font-size: 18px;
            color: #198754;
        }

        .empty-items {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-items i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #dee2e6;
        }

        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .selected-products-badge {
            display: inline-block;
            padding: 5px 10px;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 10px;
        }

        .btn-select-products {
            padding: 10px 20px;
            background: #696cff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-select-products:hover {
            background: #4f52d9;
        }

        .btn-select-products i {
            margin-left: 8px;
        }

        .loading-spinner {
            text-align: center;
            padding: 40px;
        }

        .loading-spinner i {
            font-size: 40px;
            color: #696cff;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">الطلبات</a>
                </li>
                <li class="breadcrumb-item active">إنشاء طلب جديد</li>
            </ol>
        </nav>

        <div class="row">
            <!-- العمود الرئيسي -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            إنشاء طلب جديد
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> رجوع للقائمة
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- رسائل المعالجة -->
                        <div id="processing-messages" style="display: none;">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                <span id="processing-message-text"></span>
                            </div>
                        </div>

                        <!-- دليل سريع -->
                        <div class="alert-guide">
                            <h6 class="mb-3">
                                <i class="fas fa-lightbulb me-2"></i>
                                معلومات مهمة
                            </h6>
                            <ul>
                                <li>يمكنك إنشاء طلب يدوياً لأي عميل</li>
                                <li>المنتجات التي تحمل علامة <span class="badge provider-like4app">لايك كارد</span> سيتم
                                    معالجتها عبر خدمة لايك كارد</li>
                                <li>سيتم خصم المنتجات الداخلية من المخزون تلقائياً</li>
                                <li>يمكنك اختيار منتج واحد أو أكثر وتحديد الكمية لكل منتج</li>
                                <li>يتم تسجيل جميع العمليات مع لايك كارد في سجل الطلب</li>
                            </ul>
                        </div>

                        <form action="{{ route('admin.orders.store') }}" method="POST" id="createOrderForm">
                            @csrf

                            <!-- معلومات العميل -->
                            <div class="form-section">
                                <h6><i class="fas fa-user me-2"></i>معلومات العميل</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">اختر عميل (اختياري)</label>
                                        <select class="form-select select2" id="user_id" name="user_id">
                                            <option value="">إنشاء طلب بدون حساب</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} - {{ $user->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="help-text">إذا اخترت عميلاً، سيتم ملء المعلومات تلقائياً</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="customer_name" class="form-label required">اسم العميل</label>
                                        <input type="text"
                                            class="form-control @error('customer_name') is-invalid @enderror"
                                            id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                                            required>
                                        @error('customer_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="customer_email" class="form-label">البريد الإلكتروني (اختياري)</label>
                                        <input type="email"
                                            class="form-control @error('customer_email') is-invalid @enderror"
                                            id="customer_email" name="customer_email" value="{{ old('customer_email') }}">
                                        @error('customer_email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="customer_phone" class="form-label">رقم الهاتف (اختياري)</label>
                                        <input type="tel"
                                            class="form-control @error('customer_phone') is-invalid @enderror"
                                            id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                                        @error('customer_phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- المنتجات -->
                            <div class="form-section">
                                <h6><i class="fas fa-shopping-cart me-2"></i>المنتجات</h6>

                                <div class="mb-4">
                                    <button type="button" class="btn-select-products" onclick="openProductsModal()">
                                        <i class="fas fa-plus-circle"></i>
                                        اختيار المنتجات
                                    </button>
                                    <span id="selectedProductsCount" class="selected-products-badge"
                                        style="display: none;">0 منتج</span>
                                </div>

                                <!-- جدول المنتجات المختارة -->
                                <div class="table-responsive">
                                    <table class="items-table" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th width="300">المنتج</th>
                                                <th width="100">الكمية</th>
                                                <th width="120">السعر للوحدة</th>
                                                <th width="120">المجموع</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsTableBody">
                                            <!-- سيتم إضافة العناصر هنا ديناميكياً -->
                                        </tbody>
                                    </table>

                                    <div class="empty-items" id="emptyItemsMessage">
                                        <i class="fas fa-shopping-cart"></i>
                                        <p>لا توجد منتجات في الطلب</p>
                                        <p class="text-muted">اضغط على "اختيار المنتجات" لإضافة منتجات</p>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات إضافية -->
                            <div class="form-section">
                                <h6><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">طريقة الدفع (اختياري)</label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror"
                                            id="payment_method" name="payment_method">
                                            <option value="">اختر طريقة الدفع</option>
                                            <option value="cash"
                                                {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                                            <option value="credit_card"
                                                {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان
                                            </option>
                                            <option value="bank_transfer"
                                                {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي
                                            </option>
                                            <option value="wallet"
                                                {{ old('payment_method') == 'wallet' ? 'selected' : '' }}>محفظة إلكترونية
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label required">حالة الطلب</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>قيد
                                                الانتظار</option>
                                            <option value="processing"
                                                {{ old('status') == 'processing' ? 'selected' : '' }}>تحت المعالجة</option>
                                            <option value="delivered"
                                                {{ old('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                            <option value="cancelled"
                                                {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 mb-3">
                                        <label for="notes" class="form-label">ملاحظات</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="help-text">ملاحظات إضافية حول الطلب (اختياري)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                        <i class="fas fa-undo me-1"></i>
                                        إعادة تعيين
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i class="fas fa-save me-1"></i>
                                        إنشاء الطلب
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- العمود الجانبي -->
            <div class="col-lg-4">
                <!-- ملخص الطلب -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            ملخص الطلب
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="summary-card">
                            <div class="summary-row">
                                <span class="summary-label">المجموع الجزئي:</span>
                                <span class="summary-value" id="subtotalDisplay">0.00 ج.م</span>
                                <input type="hidden" id="subtotal" name="subtotal" value="0">
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">الخصم:</span>
                                <div class="input-group input-group-sm" style="width: 140px;">
                                    <input type="number" class="form-control" id="discount_amount"
                                        name="discount_amount" value="{{ old('discount_amount', 0) }}" step="0.01"
                                        min="0">
                                    <span class="input-group-text">ج.م</span>
                                </div>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">الضريبة:</span>
                                <div class="input-group input-group-sm" style="width: 140px;">
                                    <input type="number" class="form-control" id="tax_amount" name="tax_amount"
                                        value="{{ old('tax_amount', 0) }}" step="0.01" min="0">
                                    <span class="input-group-text">ج.م</span>
                                </div>
                            </div>

                            <div class="summary-row total-row">
                                <span class="summary-label">الإجمالي:</span>
                                <span class="summary-value" id="totalDisplay">0.00 ج.م</span>
                                <input type="hidden" id="total_amount" name="total_amount" value="0">
                            </div>

                            <hr>

                            <div class="summary-row">
                                <span class="summary-label">عدد المنتجات:</span>
                                <span class="summary-value" id="itemsCount">0</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">منتجات لايك كارد:</span>
                                <span class="summary-value" id="like4appCount">0</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">منتجات داخلية:</span>
                                <span class="summary-value" id="internalCount">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة التعليمات -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            نصائح سريعة
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>العميل:</strong> اختر عميلاً موجوداً لملء البيانات تلقائياً
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>المنتجات:</strong> يمكنك اختيار منتج واحد أو أكثر
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>الكمية:</strong> يمكنك تعديل الكمية لكل منتج
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>البحث:</strong> استخدم البحث للعثور على المنتجات بسرعة
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- نافذة اختيار المنتجات -->
    <div id="productsModal" class="products-modal">
        <div class="products-modal-content">
            <div class="products-modal-header">
                <h5><i class="fas fa-boxes me-2"></i>اختر المنتجات</h5>
                <button type="button" class="close-btn" onclick="closeProductsModal()">&times;</button>
            </div>

            <div class="products-search">
                <div class="search-input-group">
                    <input type="text" id="productSearch" placeholder="بحث عن منتج بالاسم...">
                    <button type="button" onclick="searchProducts(1)">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="products-list" id="productsList">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p class="mt-2">جاري تحميل المنتجات...</p>
                </div>
            </div>

            <div class="pagination-container" id="paginationContainer">
                <div class="pagination-info" id="paginationInfo"></div>
                <div class="pagination-buttons">
                    <button type="button" id="prevPage" onclick="changePage('prev')" disabled>
                        <i class="fas fa-chevron-right"></i> السابق
                    </button>
                    <button type="button" id="nextPage" onclick="changePage('next')" disabled>
                        التالي <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeProductsModal()">إلغاء</button>
                <button type="button" class="btn-add" onclick="addSelectedProducts()" id="addSelectedBtn" disabled>
                    <i class="fas fa-plus-circle"></i> إضافة المنتجات المحددة
                </button>
            </div>
        </div>
    </div>

    <!-- Template لعنصر المنتج في الجدول -->
    <template id="productItemTemplate">
        <tr class="item-row" data-product-id="{product_id}" data-provider="{provider}">
            <td>
                <div class="product-info">
                    <img src="{image}" alt="{name}" class="product-image"
                        onerror="this.src='{{ asset('assets/img/products/default-product.png') }}'">
                    <div class="product-details">
                        <h6>{name}</h6>
                        <p>
                            <span class="badge {provider_badge_class}">{provider_label}</span>
                        </p>
                        <input type="hidden" name="items[{index}][product_id]" value="{product_id}">
                    </div>
                </div>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="items[{index}][quantity]"
                    value="{quantity}" min="1" max="{stock}" onchange="updateItem(this)">
            </td>
            <td>
                <div class="input-group">
                    <input type="number" class="form-control price-input" name="items[{index}][price]" value="{price}"
                        step="0.01" min="0" onchange="updateItem(this)">
                    <span class="input-group-text">ج.م</span>
                </div>
            </td>
            <td>
                <span class="item-total">{total} ج.م</span>
            </td>
            <td>
                <i class="fas fa-times remove-item" onclick="removeItem(this)"></i>
            </td>
        </tr>
    </template>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ==================== الثوابت العامة ====================
        const STORAGE_URL = "{{ asset('storage') }}";
        const DEFAULT_PRODUCT_IMAGE = "{{ asset('storage/products/default-product.png') }}";

        // ==================== المتغيرات العامة ====================
        let itemIndex = 0;
        let like4appCount = 0;
        let internalCount = 0;
        let selectedProducts = [];
        let currentPage = 1;
        let lastPage = 1;
        let searchTerm = '';
        let isLoading = false;

        // ==================== الدوال الرئيسية ====================
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: 'اختر عميل',
                allowClear: true,
                dir: 'rtl'
            });

            // تحديث الإجماليات عند تغيير الخصم أو الضريبة
            $('#discount_amount, #tax_amount').on('input', updateSummary);

            // ملء معلومات العميل عند اختياره
            $('#user_id').on('change', function() {
                const userId = $(this).val();
                if (userId) {
                    const selectedOption = $(this).find('option:selected');
                    const userText = selectedOption.text();
                    const parts = userText.split(' - ');
                    if (parts.length >= 2) {
                        $('#customer_name').val(parts[0]);
                        $('#customer_email').val(parts[1]);
                    }
                } else {
                    $('#customer_name').val('');
                    $('#customer_email').val('');
                    $('#customer_phone').val('');
                }
            });

            // البحث عند الضغط على Enter
            $('#productSearch').on('keypress', function(e) {
                if (e.which === 13) {
                    searchProducts(1);
                }
            });

            // التحقق من صحة النموذج قبل الإرسال
            $('#createOrderForm').on('submit', function(e) {
                if ($('#itemsTableBody tr').length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد منتجات',
                        text: 'يجب إضافة منتج واحد على الأقل إلى الطلب',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return false;
                }

                const name = $('#customer_name').val().trim();
                if (!name) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'الاسم مطلوب',
                        text: 'يرجى إدخال اسم العميل',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#696cff'
                    });
                    return false;
                }

                const submitBtn = $('#submit-btn');
                submitBtn.html('<i class="fas fa-spinner spin me-2"></i>جاري إنشاء الطلب...');
                submitBtn.prop('disabled', true);

                return true;
            });
        });

        // ==================== دوال نافذة المنتجات ====================
        function openProductsModal() {
            $('#productsModal').addClass('active');
            loadProducts(1);
        }

        function closeProductsModal() {
            $('#productsModal').removeClass('active');
            // لا نمسح selectedProducts هنا، فقط نخفي النافذة
        }

        function loadProducts(page = 1) {
            if (isLoading) return;

            isLoading = true;
            $('#productsList').html(`
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p class="mt-2">جاري تحميل المنتجات...</p>
                </div>
            `);

            $.ajax({
                url: '{{ route('admin.orders.search-products') }}',
                method: 'GET',
                data: {
                    q: searchTerm,
                    page: page
                },
                success: function(response) {
                    displayProducts(response);
                    currentPage = response.current_page;
                    lastPage = response.last_page;
                    updatePaginationButtons();
                    isLoading = false;
                },
                error: function(xhr) {
                    console.error('Error loading products:', xhr);
                    $('#productsList').html(`
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                            <p class="mt-2">حدث خطأ أثناء تحميل المنتجات</p>
                            <button class="btn btn-sm btn-primary mt-2" onclick="loadProducts(1)">إعادة المحاولة</button>
                        </div>
                    `);
                    isLoading = false;
                }
            });
        }

        function displayProducts(response) {
            const products = response.items;
            let html = '';

            if (products.length === 0) {
                html = `
            <div class="text-center py-4">
                <i class="fas fa-box-open fa-3x text-muted"></i>
                <p class="mt-2">لا توجد منتجات</p>
            </div>
        `;
            } else {
                products.forEach(product => {
                    const provider = product.external_id ? 'like4app' : 'internal';
                    const providerClass = provider === 'like4app' ? 'provider-like4app' : 'provider-internal';
                    const providerLabel = provider === 'like4app' ? 'لايك كارد' : 'داخلي';
                    const isSelected = selectedProducts.some(p => p.id === product.id);

                    const image = product.image_path ? `${STORAGE_URL}/${product.image_path}` :
                        DEFAULT_PRODUCT_IMAGE;
                    const price = parseFloat(product.final_price || product.price || 0);
                    const stock = parseInt(product.stock || 0);

                    // حل مشكلة الاسم
                    let productName = '';

                    if (typeof product.name === 'string') {
                        productName = product.name;
                    } else if (typeof product.name === 'object' && product.name !== null) {
                        productName = product.name.ar || product.name.en || Object.values(product.name)[0] || '';
                    } else {
                        productName = '';
                    }

                    const safeName = encodeURIComponent(productName);

                    html += `
                <div class="product-card ${isSelected ? 'selected' : ''}" 
                     data-product-id="${product.id}"
                     onclick="toggleProduct(${product.id}, '${safeName}', ${price}, ${stock}, '${provider}', '${image}')">
                    <img src="${image}" 
                         alt="${productName}" 
                         class="product-image"
                         onerror="this.src='${DEFAULT_PRODUCT_IMAGE}'">
                    <div class="product-details">
                        <h6>${productName}</h6>
                        <p>
                            <span class="${providerClass}">${providerLabel}</span>
                        </p>
                    </div>
                    <div class="product-price">${price.toFixed(2)} ج.م</div>
                    <div class="product-stock">المخزون: ${provider === 'like4app' ? 'غير محدود' : stock}</div>
                </div>
            `;
                });
            }

            $('#productsList').html(html);

            const perPage = response.per_page || 10;
            const from = response.total > 0 ? ((response.current_page - 1) * perPage) + 1 : 0;
            const to = Math.min(response.current_page * perPage, response.total);

            $('#paginationInfo').html(`
        عرض ${from} - ${to} من ${response.total} منتج
    `);
        }

        function toggleProduct(id, encodedName, price, stock, provider, image) {
            const name = decodeURIComponent(encodedName);
            const index = selectedProducts.findIndex(p => p.id === id);

            if (index === -1) {
                if (provider === 'internal' && stock <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'المنتج غير متوفر',
                        text: 'هذا المنتج نفد من المخزون',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return;
                }

                selectedProducts.push({
                    id: id,
                    name: name,
                    price: price,
                    stock: stock,
                    provider: provider,
                    image: image
                });
            } else {
                selectedProducts.splice(index, 1);
            }

            $(`.product-card[data-product-id="${id}"]`).toggleClass(
                'selected',
                selectedProducts.some(p => p.id === id)
            );

            updateSelectedCount();
        }

        function updateSelectedCount() {
            const count = selectedProducts.length;
            const btn = $('#addSelectedBtn');
            const badge = $('#selectedProductsCount');

            if (count > 0) {
                btn.prop('disabled', false);
                badge.text(`${count} منتج مختار`).show();
            } else {
                btn.prop('disabled', true);
                badge.hide();
            }
        }

        function searchProducts(page = 1) {
            searchTerm = $('#productSearch').val();
            loadProducts(page);
        }

        function changePage(direction) {
            if (direction === 'next' && currentPage < lastPage) {
                loadProducts(currentPage + 1);
            } else if (direction === 'prev' && currentPage > 1) {
                loadProducts(currentPage - 1);
            }
        }

        function updatePaginationButtons() {
            $('#prevPage').prop('disabled', currentPage <= 1);
            $('#nextPage').prop('disabled', currentPage >= lastPage);
        }

        function addSelectedProducts() {
            selectedProducts.forEach(product => {
                addProductToTable(product);
            });

            // تفريغ المنتجات المختارة بعد الإضافة
            selectedProducts = [];
            updateSelectedCount();
            closeProductsModal();
        }

        // ==================== دوال جدول المنتجات ====================
        function addProductToTable(product) {
            // التحقق من عدم إضافة المنتج مسبقاً
            if ($(`tr[data-product-id="${product.id}"]`).length > 0) {
                return;
            }

            const template = document.getElementById('productItemTemplate').innerHTML;
            const providerClass = product.provider === 'like4app' ? 'provider-like4app' : 'provider-internal';
            const providerLabel = product.provider === 'like4app' ? 'لايك كارد' : 'داخلي';

            // تحديد الحد الأقصى للكمية
            const maxStock = product.provider === 'like4app' ? 999999 : (product.stock || 0);

            const html = template
                .replace(/{product_id}/g, product.id)
                .replace(/{name}/g, product.name)
                .replace(/{price}/g, product.price.toFixed(2))
                .replace(/{stock}/g, maxStock)
                .replace(/{image}/g, product.image)
                .replace(/{index}/g, itemIndex)
                .replace(/{total}/g, product.price.toFixed(2))
                .replace(/{provider}/g, product.provider)
                .replace(/{provider_badge_class}/g, providerClass)
                .replace(/{provider_label}/g, providerLabel)
                .replace(/{quantity}/g, 1);

            $('#itemsTableBody').append(html);
            $('#emptyItemsMessage').hide();

            // تحديث العدادات
            if (product.provider === 'like4app') {
                like4appCount++;
            } else {
                internalCount++;
            }

            itemIndex++;
            updateSummary();
            updateItemsCount();
        }

        function updateItem(input) {
            const row = $(input).closest('tr');
            const quantity = parseFloat(row.find('.quantity-input').val()) || 1;
            const price = parseFloat(row.find('.price-input').val()) || 0;
            const total = (quantity * price).toFixed(2);

            row.find('.item-total').text(total + ' ج.م');
            updateSummary();
        }

        function removeItem(icon) {
            const row = $(icon).closest('tr');
            const provider = row.data('provider');

            // تحديث العدادات
            if (provider === 'like4app') {
                like4appCount = Math.max(0, like4appCount - 1);
            } else {
                internalCount = Math.max(0, internalCount - 1);
            }

            row.remove();

            if ($('#itemsTableBody tr').length === 0) {
                $('#emptyItemsMessage').show();
            }

            updateSummary();
            updateItemsCount();
        }

        function updateSummary() {
            let subtotal = 0;

            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
                const price = parseFloat($(this).find('.price-input').val()) || 0;
                subtotal += quantity * price;
            });

            const discount = parseFloat($('#discount_amount').val()) || 0;
            const tax = parseFloat($('#tax_amount').val()) || 0;
            const total = subtotal - discount + tax;

            $('#subtotalDisplay').text(subtotal.toFixed(2) + ' ج.م');
            $('#subtotal').val(subtotal.toFixed(2));
            $('#totalDisplay').text(total.toFixed(2) + ' ج.م');
            $('#total_amount').val(total.toFixed(2));
        }

        function updateItemsCount() {
            const totalItems = $('.item-row').length;
            $('#itemsCount').text(totalItems);
            $('#like4appCount').text(like4appCount);
            $('#internalCount').text(internalCount);
        }

        // ==================== دوال إضافية ====================
        function resetForm() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم مسح جميع البيانات المدخلة',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، إعادة تعيين',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#createOrderForm')[0].reset();
                    $('#itemsTableBody').empty();
                    $('#emptyItemsMessage').show();
                    $('#subtotalDisplay').text('0.00 ج.م');
                    $('#subtotal').val('0');
                    $('#totalDisplay').text('0.00 ج.م');
                    $('#total_amount').val('0');
                    $('#itemsCount').text('0');
                    $('#like4appCount').text('0');
                    $('#internalCount').text('0');
                    $('.select2').val(null).trigger('change');
                    $('#selectedProductsCount').hide();

                    itemIndex = 0;
                    like4appCount = 0;
                    internalCount = 0;
                    selectedProducts = [];

                    Swal.fire({
                        icon: 'success',
                        title: 'تم الإعادة',
                        text: 'تم إعادة تعيين النموذج بنجاح',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function fillSampleCustomer() {
            $('#customer_name').val('عميل تجريبي');
            $('#customer_email').val('');
            $('#customer_phone').val('');
        }

        function showProcessingMessage(message) {
            $('#processing-message-text').text(message);
            $('#processing-messages').fadeIn(300);
        }

        function hideProcessingMessage() {
            $('#processing-messages').fadeOut(300);
        }

        function showToast(title, message, type = 'info') {
            const toastId = 'toast-' + Date.now();
            const bgColor = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            } [type] || 'bg-info';

            const toastHtml = `
                <div id="${toastId}" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
                    <div class="toast align-items-center text-white ${bgColor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong>${title}:</strong> ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement.querySelector('.toast'), {
                delay: 5000,
                autohide: true
            });
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', function() {
                $(this).remove();
            });
        }

        // تحميل المنتجات عند فتح النافذة
        $(document).ready(function() {
            // تحميل أول صفحة عند فتح النافذة لأول مرة
            // سيتم التحميل عند فتح النافذة فقط
        });
    </script>
@endsection
