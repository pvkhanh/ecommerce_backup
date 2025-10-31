@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-file-invoice text-primary me-2"></i>
                            Chi tiết đơn hàng #{{ $order->order_number }}
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                                <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-lg">
                            <i class="fa-solid fa-pen me-2"></i> Chỉnh sửa
                        </a>
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                            class="btn btn-success btn-lg">
                            <i class="fa-solid fa-print me-2"></i> In hóa đơn
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">
                    <i class="fa-solid fa-timeline text-primary me-2"></i>Trạng thái đơn hàng
                </h5>
                <div class="order-timeline">
                    @php
                        $statuses = [
                            'pending' => ['icon' => 'clock', 'label' => 'Chờ xử lý', 'color' => 'warning'],
                            'paid' => ['icon' => 'credit-card', 'label' => 'Đã thanh toán', 'color' => 'info'],
                            'shipped' => ['icon' => 'truck', 'label' => 'Đang giao', 'color' => 'primary'],
                            'completed' => ['icon' => 'check-circle', 'label' => 'Hoàn thành', 'color' => 'success'],
                        ];
                        $currentStatus = $order->status->value;
                        $currentIndex = array_search($currentStatus, array_keys($statuses));
                        if ($currentIndex === false && $currentStatus !== 'cancelled') {
                            $currentIndex = 0;
                        }
                    @endphp

                    <div class="row">
                        @foreach ($statuses as $key => $status)
                            @php
                                $index = array_search($key, array_keys($statuses));
                                $isActive = $currentStatus !== 'cancelled' && $index <= $currentIndex;
                                $isCurrent = $key === $currentStatus;
                            @endphp
                            <div class="col-3">
                                <div
                                    class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                    <div class="timeline-icon bg-{{ $status['color'] }}">
                                        <i class="fa-solid fa-{{ $status['icon'] }}"></i>
                                    </div>
                                    <div class="timeline-label">{{ $status['label'] }}</div>
                                    @if ($key === 'pending' && $order->created_at)
                                        <div class="timeline-time">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    @elseif($key === 'shipped' && $order->shipped_at)
                                        <div class="timeline-time">{{ $order->shipped_at->format('d/m/Y H:i') }}</div>
                                    @elseif($key === 'completed' && $order->completed_at)
                                        <div class="timeline-time">{{ $order->completed_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($order->status->value === 'cancelled')
                        <div class="alert alert-danger mt-4 mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-ban fs-3 me-3"></i>
                                <div>
                                    <strong>Đơn hàng đã bị hủy</strong>
                                    @if ($order->cancelled_at)
                                        <p class="mb-0 small">Thời gian: {{ $order->cancelled_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                    @if ($order->admin_note)
                                        <p class="mb-0 small">Lý do: {{ $order->admin_note }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-box text-primary me-2"></i>Sản phẩm đã đặt
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
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 60px; height: 60px;">
                                                            <i class="fa-solid fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ $item->product->name }}</div>
                                                        @if ($item->variant)
                                                            <div class="small text-muted">
                                                                <i class="fa-solid fa-tag me-1"></i>
                                                                {{ $item->variant->name }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="fw-semibold">{{ number_format($item->price) }}đ</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-primary fs-6 px-3 py-2">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-end">
                                                <span class="fw-bold text-primary fs-6">
                                                    {{ number_format($item->price * $item->quantity) }}đ
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-end fw-semibold">Tạm tính:</td>
                                        <td class="px-4 py-3 text-end fw-bold">
                                            {{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity)) }}đ
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-end fw-semibold">Phí vận chuyển:</td>
                                        <td class="px-4 py-3 text-end fw-bold">
                                            {{ number_format($order->shipping_fee) }}đ
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="px-4 py-3 text-end fw-bold fs-5">Tổng cộng:</td>
                                        <td class="px-4 py-3 text-end fw-bold text-primary fs-4">
                                            {{ number_format($order->total_amount) }}đ
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if ($order->shippingAddress)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-location-dot text-primary me-2"></i>Địa chỉ giao hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Người nhận</label>
                                    <div class="fw-semibold">{{ $order->shippingAddress->receiver_name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Số điện thoại</label>
                                    <div class="fw-semibold">
                                        <i class="fa-solid fa-phone text-primary me-2"></i>
                                        {{ $order->shippingAddress->phone }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small mb-1">Địa chỉ</label>
                                    <div class="fw-semibold">
                                        <i class="fa-solid fa-map-marker-alt text-primary me-2"></i>
                                        {{ $order->shippingAddress->address }},
                                        {{ $order->shippingAddress->ward }},
                                        {{ $order->shippingAddress->district }},
                                        {{ $order->shippingAddress->province }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if ($order->customer_note || $order->admin_note)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-note-sticky text-primary me-2"></i>Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($order->customer_note)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Ghi chú của khách hàng:</label>
                                    <div class="p-3 bg-light rounded">{{ $order->customer_note }}</div>
                                </div>
                            @endif
                            @if ($order->admin_note)
                                <div>
                                    <label class="text-muted small mb-1">Ghi chú nội bộ (Admin):</label>
                                    <div class="p-3 bg-warning bg-opacity-10 rounded">{{ $order->admin_note }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-user text-primary me-2"></i>Thông tin khách hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($order->user)
                            <div class="text-center mb-3">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                        style="width: 80px; height: 80px;">
                                        <i class="fa-solid fa-user text-primary fs-1"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold mb-1">{{ $order->user->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="fa-solid fa-envelope me-2"></i>{{ $order->user->email }}
                                </p>
                            </div>
                            <hr>
                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $order->user->email }}" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-envelope me-2"></i>Gửi email
                                </a>
                                @if ($order->shippingAddress)
                                    <a href="tel:{{ $order->shippingAddress->phone }}" class="btn btn-outline-success">
                                        <i class="fa-solid fa-phone me-2"></i>Gọi điện
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">Không có thông tin khách hàng</p>
                        @endif
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-credit-card text-primary me-2"></i>Thông tin thanh toán
                        </h5>
                    </div>
                    <div class="card-body">
                        @php $payment = $order->payments->first(); @endphp
                        @if ($payment)
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Phương thức</label>
                                <div class="fw-semibold">
                                    <i class="fa-solid fa-{{ $payment->payment_method->icon() }} text-primary me-2"></i>
                                    {{ $payment->payment_method->label() }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Trạng thái</label>
                                <div>
                                    @if ($payment->status->value === 'success')
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fa-solid fa-check-circle me-1"></i>Đã thanh toán
                                        </span>
                                    @elseif($payment->status->value === 'pending')
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                            <i class="fa-solid fa-clock me-1"></i>Chờ thanh toán
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            <i class="fa-solid fa-times-circle me-1"></i>Thất bại
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if ($payment->transaction_id)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Mã giao dịch</label>
                                    <div class="fw-semibold">{{ $payment->transaction_id }}</div>
                                </div>
                            @endif
                            @if ($payment->paid_at)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Thời gian thanh toán</label>
                                    <div class="fw-semibold">{{ $payment->paid_at->format('d/m/Y H:i') }}</div>
                                </div>
                            @endif
                            <hr>
                            <div class="d-grid gap-2">
                                @if ($payment->status->value === 'pending')
                                    <form action="{{ route('admin.orders.confirm-payment', $order->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fa-solid fa-check me-2"></i>Xác nhận thanh toán
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">Chưa có thông tin thanh toán</p>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-bolt text-primary me-2"></i>Thao tác nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($order->status->value === 'pending')
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST"
                                    class="quick-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fa-solid fa-credit-card me-2"></i>Đánh dấu đã thanh toán
                                    </button>
                                </form>
                            @endif

                            @if ($order->status->value === 'paid')
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST"
                                    class="quick-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="shipped">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa-solid fa-truck me-2"></i>Đánh dấu đang giao
                                    </button>
                                </form>
                            @endif

                            @if ($order->status->value === 'shipped')
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST"
                                    class="quick-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fa-solid fa-check-circle me-2"></i>Hoàn thành đơn hàng
                                    </button>
                                </form>
                            @endif

                            @if (in_array($order->status->value, ['pending', 'paid']))
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                    data-bs-target="#cancelModal">
                                    <i class="fa-solid fa-ban me-2"></i>Hủy đơn hàng
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-ban text-danger me-2"></i>Hủy đơn hàng
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            Bạn có chắc chắn muốn hủy đơn hàng <strong>#{{ $order->order_number }}</strong>?
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do hủy <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="4" required placeholder="Nhập lý do hủy đơn hàng..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-ban me-2"></i>Xác nhận hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/order.js') }}"></script>
    <script>
        // Quick status update with confirmation
        document.querySelectorAll('.quick-status-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const status = this.querySelector('input[name="status"]').value;
                const statusText = {
                    'paid': 'đã thanh toán',
                    'shipped': 'đang giao hàng',
                    'completed': 'hoàn thành'
                };

                Swal.fire({
                    title: 'Xác nhận',
                    text: `Bạn có chắc muốn đánh dấu đơn hàng ${statusText[status]}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endpush
