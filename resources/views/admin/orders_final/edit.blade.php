@extends('layouts.admin')

@section('title', 'Chỉnh sửa Đơn hàng #' . $order->order_number)

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                        <div>
                            <h2 class="fw-bold text-dark mb-1">Chỉnh sửa Đơn hàng</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                                </ol>
                            </nav>
                        </div>
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
                    <!-- Order Info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-info-circle text-primary me-2"></i>Thông tin đơn hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-hashtag text-muted me-1"></i> Mã đơn hàng
                                    </label>
                                    <input type="text" class="form-control form-control-lg" value="{{ $order->order_number }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-list-check text-muted me-1"></i> Trạng thái
                                    </label>
                                    <select name="status" class="form-select form-select-lg @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $order->status->value) === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="paid" {{ old('status', $order->status->value) === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="shipped" {{ old('status', $order->status->value) === 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                                        <option value="completed" {{ old('status', $order->status->value) === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ old('status', $order->status->value) === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-dollar-sign text-muted me-1"></i> Tổng tiền
                                    </label>
                                    <input type="number" name="total_amount" class="form-control form-control-lg @error('total_amount') is-invalid @enderror"
                                           value="{{ old('total_amount', $order->total_amount) }}" step="0.01" required>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-truck text-muted me-1"></i> Phí vận chuyển
                                    </label>
                                    <input type="number" name="shipping_fee" class="form-control form-control-lg @error('shipping_fee') is-invalid @enderror"
                                           value="{{ old('shipping_fee', $order->shipping_fee) }}" step="0.01" required>
                                    @error('shipping_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-comment text-muted me-1"></i> Ghi chú khách hàng
                                    </label>
                                    <textarea name="customer_note" rows="3" class="form-control @error('customer_note') is-invalid @enderror"
                                              placeholder="Ghi chú từ khách hàng...">{{ old('customer_note', $order->customer_note) }}</textarea>
                                    @error('customer_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-sticky-note text-muted me-1"></i> Ghi chú nội bộ
                                    </label>
                                    <textarea name="admin_note" rows="3" class="form-control @error('admin_note') is-invalid @enderror"
                                              placeholder="Ghi chú nội bộ (chỉ admin xem)...">{{ old('admin_note', $order->admin_note) }}</textarea>
                                    @error('admin_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if($order->shippingAddress)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-map-marker-alt text-danger me-2"></i>Địa chỉ giao hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Người nhận</label>
                                    <input type="text" class="form-control form-control-lg" value="{{ $order->shippingAddress->receiver_name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <input type="text" class="form-control form-control-lg" value="{{ $order->shippingAddress->phone }}" disabled>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Địa chỉ đầy đủ</label>
                                    <textarea class="form-control" rows="2" disabled>{{ $order->shippingAddress->address }}, {{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }}, {{ $order->shippingAddress->province }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Customer Info -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-user text-primary me-2"></i>Khách hàng
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <div class="small text-muted">Họ tên</div>
                                <div class="fw-semibold">{{ $order->user->name ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="small text-muted">Email</div>
                                <div class="fw-semibold">{{ $order->user->email ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="small text-muted">Số điện thoại</div>
                                <div class="fw-semibold">{{ $order->user->phone ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-clock text-info me-2"></i>Thời gian
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <div class="small text-muted">Ngày tạo</div>
                                <div class="fw-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="small text-muted">Cập nhật lần cuối</div>
                                <div class="fw-semibold">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                            @if($order->completed_at)
                            <div>
                                <div class="small text-muted">Ngày hoàn thành</div>
                                <div class="fw-semibold">{{ $order->completed_at->format('d/m/Y H:i') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="fa-solid fa-save me-2"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fa-solid fa-times me-2"></i> Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
