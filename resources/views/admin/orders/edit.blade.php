@extends('layouts.admin')

@section('title', 'Chỉnh sửa đơn hàng #' . $order->order_number)

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-pen-to-square text-warning me-2"></i>
                            Chỉnh sửa đơn hàng #{{ $order->order_number }}
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->order_number }}</a>
                                </li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Order Items (Read Only) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-box text-primary me-2"></i>Sản phẩm đã đặt
                                <span class="badge bg-primary ms-2">{{ $order->orderItems->count() }} sản phẩm</span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3">Sản phẩm</th>
                                            <th class="px-4 py-3 text-center">Đơn giá</th>
                                            <th class="px-4 py-3 text-center">Số lượng</th>
                                            <th class="px-4 py-3 text-end">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderItems as $item)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        @if ($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}" class="rounded me-3"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fa-solid fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ $item->product->name }}</div>
                                                            @if ($item->variant)
                                                                <div class="small text-muted">{{ $item->variant->name }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    {{ number_format($item->price) }}đ
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="badge bg-primary px-3 py-2">{{ $item->quantity }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-end fw-bold text-primary">
                                                    {{ number_format($item->price * $item->quantity) }}đ
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-end fw-semibold">Tạm tính:</td>
                                            <td class="px-4 py-3 text-end fw-bold" id="subtotal">
                                                {{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity)) }}đ
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Fee -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-truck text-primary me-2"></i>Phí vận chuyển
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Phí ship <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" name="shipping_fee"
                                            class="form-control @error('shipping_fee') is-invalid @enderror"
                                            value="{{ old('shipping_fee', $order->shipping_fee) }}" min="0"
                                            step="1000" id="shippingFeeInput">
                                        <span class="input-group-text">đ</span>
                                        @error('shipping_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Nhập số tiền phí vận chuyển</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Tổng đơn hàng:</strong>
                                            <span class="fs-4 fw-bold" id="totalAmount">
                                                {{ number_format($order->total_amount) }}đ
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-note-sticky text-primary me-2"></i>Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Ghi chú của khách hàng</label>
                                    <textarea name="customer_note" class="form-control @error('customer_note') is-invalid @enderror" rows="3"
                                        placeholder="Ghi chú từ khách hàng..." readonly>{{ old('customer_note', $order->customer_note) }}</textarea>
                                    @error('customer_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Ghi chú nội bộ (Admin)</label>
                                    <textarea name="admin_note" class="form-control @error('admin_note') is-invalid @enderror" rows="3"
                                        placeholder="Ghi chú dành cho admin...">{{ old('admin_note', $order->admin_note) }}</textarea>
                                    @error('admin_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Status -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-flag text-primary me-2"></i>Trạng thái đơn hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <label class="form-label fw-semibold">
                                Trạng thái <span class="text-danger">*</span>
                            </label>
                            <select name="status"
                                class="form-select form-select-lg @error('status') is-invalid @enderror" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}"
                                        {{ old('status', $order->status->value) == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <i class="fa-solid fa-info-circle me-1"></i>
                                    Thay đổi trạng thái sẽ cập nhật timeline đơn hàng
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info (Read Only) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-user text-primary me-2"></i>Khách hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($order->user)
                                <div class="text-center">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                            style="width: 70px; height: 70px;">
                                            <i class="fa-solid fa-user text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $order->user->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $order->user->email }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Info (Read Only) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-credit-card text-primary me-2"></i>Thanh toán
                            </h5>
                        </div>
                        <div class="card-body">
                            @php $payment = $order->payments->first(); @endphp
                            @if ($payment)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Phương thức:</span>
                                    <span class="fw-semibold">{{ $payment->payment_method->label() }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Trạng thái:</span>
                                    <span class="badge bg-{{ $payment->status->color() }} px-3 py-2">
                                        {{ $payment->status->label() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-save me-2"></i> Lưu thay đổi
                                </button>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="btn btn-outline-secondary btn-lg">
                                    <i class="fa-solid fa-times me-2"></i> Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const subtotal = {{ $order->orderItems->sum(fn($item) => $item->price * $item->quantity) }};
                const shippingFeeInput = document.getElementById('shippingFeeInput');
                const totalAmountEl = document.getElementById('totalAmount');

                shippingFeeInput.addEventListener('input', function() {
                    const shippingFee = parseFloat(this.value) || 0;
                    const total = subtotal + shippingFee;
                    totalAmountEl.textContent = total.toLocaleString('vi-VN') + 'đ';
                });
            });
        </script>
    @endpush
@endsection
