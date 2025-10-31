@extends('layouts.admin')

@section('title', 'Tạo Đơn hàng mới')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                        <div>
                            <h2 class="fw-bold text-dark mb-1">Tạo Đơn hàng mới</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                                    </li>
                                    <li class="breadcrumb-item active">Tạo mới</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST" id="createOrderForm">
            @csrf

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Customer Selection -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-user text-primary me-2"></i>Chọn khách hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold required">Khách hàng</label>
                                <select name="user_id"
                                    class="form-select form-select-lg @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fa-solid fa-box text-primary me-2"></i>Sản phẩm
                                </h5>
                                <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                    <i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="orderItems">
                                <!-- Items will be added here dynamically -->
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    Nhấn "Thêm sản phẩm" để bắt đầu thêm sản phẩm vào đơn hàng
                                </div>
                            </div>

                            <!-- Template for order item -->
                            <template id="itemTemplate">
                                <div class="order-item border rounded p-3 mb-3">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold">Sản phẩm</label>
                                            <select name="items[INDEX][product_id]" class="form-select product-select"
                                                required>
                                                <option value="">-- Chọn sản phẩm --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} ({{ number_format($product->price) }}đ)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Số lượng</label>
                                            <input type="number" name="items[INDEX][quantity]"
                                                class="form-control quantity-input" value="1" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Đơn giá</label>
                                            <input type="number" name="items[INDEX][price]"
                                                class="form-control price-input" step="0.01" required readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100 remove-item-btn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-map-marker-alt text-danger me-2"></i>Địa chỉ giao hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Người nhận</label>
                                    <input type="text" name="receiver_name"
                                        class="form-control form-control-lg @error('receiver_name') is-invalid @enderror"
                                        value="{{ old('receiver_name') }}" required>
                                    @error('receiver_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Số điện thoại</label>
                                    <input type="text" name="phone"
                                        class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold required">Tỉnh/Thành phố</label>
                                    <input type="text" name="province"
                                        class="form-control form-control-lg @error('province') is-invalid @enderror"
                                        value="{{ old('province') }}" required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold required">Quận/Huyện</label>
                                    <input type="text" name="district"
                                        class="form-control form-control-lg @error('district') is-invalid @enderror"
                                        value="{{ old('district') }}" required>
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold required">Phường/Xã</label>
                                    <input type="text" name="ward"
                                        class="form-control form-control-lg @error('ward') is-invalid @enderror"
                                        value="{{ old('ward') }}" required>
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold required">Địa chỉ chi tiết</label>
                                    <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="card border-0 shadow-sm mb-3 position-sticky" style="top: 20px;">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-calculator text-success me-2"></i>Tổng đơn hàng
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Phí vận chuyển</label>
                                <input type="number" name="shipping_fee" id="shippingFee"
                                    class="form-control form-control-lg" value="{{ old('shipping_fee', 0) }}"
                                    step="0.01" min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" class="form-select form-select-lg">
                                    <option value="pending" selected>Chờ xử lý</option>
                                    <option value="paid">Đã thanh toán</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ghi chú</label>
                                <textarea name="admin_note" rows="3" class="form-control" placeholder="Ghi chú nội bộ...">{{ old('admin_note') }}</textarea>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính:</span>
                                <span class="fw-semibold" id="subtotal">0đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Phí vận chuyển:</span>
                                <span class="fw-semibold" id="shippingDisplay">0đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5 mb-0">Tổng cộng:</span>
                                <span class="h4 mb-0 text-primary fw-bold" id="grandTotal">0đ</span>
                            </div>

                            <input type="hidden" name="total_amount" id="totalAmount" value="0">

                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                                <i class="fa-solid fa-save me-2"></i> Tạo đơn hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 0;
            const orderItemsContainer = document.getElementById('orderItems');
            const itemTemplate = document.getElementById('itemTemplate');
            const addItemBtn = document.getElementById('addItemBtn');

            // Add item
            addItemBtn.addEventListener('click', function() {
                const template = itemTemplate.content.cloneNode(true);
                const itemDiv = template.querySelector('.order-item');

                // Replace INDEX with actual index
                itemDiv.innerHTML = itemDiv.innerHTML.replace(/INDEX/g, itemIndex);

                // Clear info alert if exists
                const infoAlert = orderItemsContainer.querySelector('.alert-info');
                if (infoAlert) infoAlert.remove();

                orderItemsContainer.appendChild(template);
                itemIndex++;

                attachItemEvents();
            });

            // Attach events to new items
            function attachItemEvents() {
                // Remove item
                document.querySelectorAll('.remove-item-btn').forEach(btn => {
                    btn.removeEventListener('click', removeItem);
                    btn.addEventListener('click', removeItem);
                });

                // Product change
                document.querySelectorAll('.product-select').forEach(select => {
                    select.removeEventListener('change', updatePrice);
                    select.addEventListener('change', updatePrice);
                });

                // Quantity change
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.removeEventListener('input', calculateTotal);
                    input.addEventListener('input', calculateTotal);
                });
            }

            function removeItem(e) {
                e.target.closest('.order-item').remove();
                calculateTotal();

                // Show info alert if no items
                if (orderItemsContainer.children.length === 0) {
                    orderItemsContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    Nhấn "Thêm sản phẩm" để bắt đầu thêm sản phẩm vào đơn hàng
                </div>
            `;
                }
            }

            function updatePrice(e) {
                const select = e.target;
                const priceInput = select.closest('.order-item').querySelector('.price-input');
                const selectedOption = select.options[select.selectedIndex];
                const price = selectedOption.dataset.price || 0;

                priceInput.value = price;
                calculateTotal();
            }

            // Shipping fee change
            document.getElementById('shippingFee').addEventListener('input', calculateTotal);

            function calculateTotal() {
                let subtotal = 0;

                document.querySelectorAll('.order-item').forEach(item => {
                    const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
                    const price = parseFloat(item.querySelector('.price-input').value) || 0;
                    subtotal += quantity * price;
                });

                const shipping = parseFloat(document.getElementById('shippingFee').value) || 0;
                const total = subtotal + shipping;

                document.getElementById('subtotal').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) +
                    'đ';
                document.getElementById('shippingDisplay').textContent = new Intl.NumberFormat('vi-VN').format(
                    shipping) + 'đ';
                document.getElementById('grandTotal').textContent = new Intl.NumberFormat('vi-VN').format(total) +
                    'đ';
                document.getElementById('totalAmount').value = total;
            }
        });
    </script>
@endpush
