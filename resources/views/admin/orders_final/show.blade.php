{{-- @extends('admin.layouts.app')

@section('title', 'Chi tiết Đơn hàng #' . $order->order_number)

@section('content')
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.orders.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Đơn hàng #{{ $order->order_number }}</h1>
                        <p class="text-gray-600 mt-1">Chi tiết và quản lý đơn hàng</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn-secondary">
                        <i class="fas fa-print mr-2"></i>In hóa đơn
                    </a>
                    <button type="button" onclick="openStatusModal()" class="btn-primary">
                        <i class="fas fa-edit mr-2"></i>Cập nhật trạng thái
                    </button>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Trạng thái đơn hàng</h3>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        @php
                            $statuses = ['pending', 'paid', 'shipped', 'completed'];
                            $currentIndex = array_search($order->status->value, $statuses);
                            if ($order->status->value === 'cancelled') {
                                $currentIndex = -1;
                            }
                        @endphp

                        @foreach ($statuses as $index => $status)
                            <div class="flex flex-col items-center flex-1 relative">
                                <div
                                    class="status-step {{ $index <= $currentIndex ? 'status-step-completed' : 'status-step-pending' }}">
                                    @if ($index < $currentIndex)
                                        <i class="fas fa-check"></i>
                                    @elseif($index === $currentIndex)
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    @else
                                        <span>{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <p
                                    class="text-xs font-medium mt-2 text-center {{ $index <= $currentIndex ? 'text-blue-600' : 'text-gray-500' }}">
                                    @switch($status)
                                        @case('pending')
                                            Chờ xử lý
                                        @break

                                        @case('paid')
                                            Đã thanh toán
                                        @break

                                        @case('shipped')
                                            Đang giao hàng
                                        @break

                                        @case('completed')
                                            Hoàn thành
                                        @break
                                    @endswitch
                                </p>
                                @if ($index < count($statuses) - 1)
                                    <div
                                        class="status-line {{ $index < $currentIndex ? 'status-line-completed' : 'status-line-pending' }}">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($order->status->value === 'cancelled')
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2 text-red-700">
                            <i class="fas fa-times-circle text-xl"></i>
                            <span class="font-semibold">Đơn hàng đã bị hủy</span>
                        </div>
                        @if ($order->delivered_at)
                            <p class="text-sm text-red-600 mt-1">Thời gian: {{ $order->delivered_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Sản phẩm đã đặt</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($order->orderItems as $item)
                                <div
                                    class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div
                                        class="w-20 h-20 bg-white rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden">
                                        @if ($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-300 text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $item->product->name ?? 'N/A' }}</h4>
                                        @if ($item->variant)
                                            <p class="text-sm text-gray-600 mt-1">
                                                Phân loại: <span class="font-medium">{{ $item->variant->name }}</span>
                                            </p>
                                        @endif
                                        <div class="flex items-center gap-4 mt-2">
                                            <span class="text-sm text-gray-600">Số lượng: <span
                                                    class="font-medium text-gray-900">{{ $item->quantity }}</span></span>
                                            <span class="text-sm text-gray-600">Đơn giá: <span
                                                    class="font-medium text-gray-900">{{ number_format($item->price) }}đ</span></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-blue-600">
                                            {{ number_format($item->price * $item->quantity) }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Tạm tính:</span>
                                    <span
                                        class="font-medium">{{ number_format($order->total_amount - $order->shipping_fee) }}đ</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Phí vận chuyển:</span>
                                    <span class="font-medium">{{ number_format($order->shipping_fee) }}đ</span>
                                </div>
                                <div
                                    class="flex justify-between text-lg font-bold text-gray-900 pt-3 border-t border-gray-200">
                                    <span>Tổng cộng:</span>
                                    <span class="text-blue-600">{{ number_format($order->total_amount) }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Notes -->
                @if ($order->customer_note)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-alt text-blue-600"></i>
                            Ghi chú từ khách hàng
                        </h3>
                        <p class="text-gray-700 bg-blue-50 p-4 rounded-lg border border-blue-100">
                            {{ $order->customer_note }}</p>
                    </div>
                @endif

                <!-- Admin Notes -->
                @if ($order->admin_note)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-amber-600"></i>
                            Ghi chú nội bộ
                        </h3>
                        <p class="text-gray-700 bg-amber-50 p-4 rounded-lg border border-amber-100">
                            {{ $order->admin_note }}</p>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        Thông tin khách hàng
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Họ tên</p>
                            <p class="font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <p class="font-medium text-gray-900">{{ $order->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Số điện thoại</p>
                            <p class="font-medium text-gray-900">{{ $order->user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if ($order->shippingAddress)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            Địa chỉ giao hàng
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Người nhận</p>
                                <p class="font-medium text-gray-900">{{ $order->shippingAddress->receiver_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Số điện thoại</p>
                                <p class="font-medium text-gray-900">{{ $order->shippingAddress->phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Địa chỉ</p>
                                <p class="font-medium text-gray-900">
                                    {{ $order->shippingAddress->address }}<br>
                                    {{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }}<br>
                                    {{ $order->shippingAddress->province }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-green-600"></i>
                        Thông tin thanh toán
                    </h3>
                    @if ($order->payments->count() > 0)
                        @foreach ($order->payments as $payment)
                            <div class="space-y-3 {{ !$loop->last ? 'pb-3 mb-3 border-b border-gray-200' : '' }}">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Phương thức</p>
                                    <p class="font-medium text-gray-900">{{ $payment->payment_method->value ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Trạng thái</p>
                                    @php
                                        $paymentStatus = $payment->status->value;
                                        $statusColors = [
                                            'success' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$paymentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Số tiền</p>
                                    <p class="font-bold text-lg text-blue-600">{{ number_format($payment->amount) }}đ</p>
                                </div>
                                @if ($payment->transaction_id)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Mã giao dịch</p>
                                        <p class="font-mono text-sm text-gray-700">{{ $payment->transaction_id }}</p>
                                    </div>
                                @endif
                                @if ($payment->paid_at)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Thời gian thanh toán</p>
                                        <p class="text-sm text-gray-900">{{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Chưa có thông tin thanh toán</p>
                    @endif
                </div>

                <!-- Order Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-purple-600"></i>
                        Thông tin đơn hàng
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Ngày tạo</p>
                            <p class="font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Ngày giao hàng</p>
                            <p class="font-medium text-gray-900">
                                {{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : 'Chưa giao' }}</p>
                        </div>
                        @if ($order->completed_at)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Ngày hoàn thành</p>
                                <p class="font-medium text-gray-900">{{ $order->completed_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-xl font-bold text-gray-800">Cập nhật trạng thái đơn hàng</h3>
                <button type="button" onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái mới</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="paid" {{ $order->status->value === 'paid' ? 'selected' : '' }}>Đã thanh toán
                            </option>
                            <option value="shipped" {{ $order->status->value === 'shipped' ? 'selected' : '' }}>Đang giao
                                hàng</option>
                            <option value="completed" {{ $order->status->value === 'completed' ? 'selected' : '' }}>Hoàn
                                thành</option>
                            <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Hủy
                                đơn</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú (tùy chọn)</label>
                        <textarea name="admin_note" rows="4" class="form-input" placeholder="Nhập ghi chú nội bộ...">{{ $order->admin_note }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeStatusModal()" class="btn-secondary">Hủy</button>
                    <button type="submit" class="btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-back {
            @apply w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors shadow-sm;
        }

        .btn-primary {
            @apply px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md;
        }

        .btn-secondary {
            @apply px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors;
        }

        .status-step {
            @apply w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg relative z-10;
        }

        .status-step-completed {
            @apply bg-blue-600 text-white shadow-lg;
        }

        .status-step-pending {
            @apply bg-gray-200 text-gray-500;
        }

        .status-line {
            @apply absolute top-6 left-1/2 right-0 h-1 -z-10;
            width: calc(100% - 3rem);
        }

        .status-line-completed {
            @apply bg-blue-600;
        }

        .status-line-pending {
            @apply bg-gray-200;
        }

        .form-input,
        .form-select {
            @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
        }

        .modal {
            @apply fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4;
        }

        .modal.active {
            @apply flex;
        }

        .modal-content {
            @apply bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden;
        }

        .modal-header {
            @apply flex items-center justify-between p-6 border-b border-gray-200;
        }

        .modal-body {
            @apply p-6;
        }

        .modal-footer {
            @apply flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50;
        }
    </style>

    <script>
        function openStatusModal() {
            document.getElementById('statusModal').classList.add('active');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('statusModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
@endsection --}}




{{-- Bản 2: Ổn Claude --}}

{{-- @extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->order_number)

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
                            <h2 class="fw-bold text-dark mb-1">Đơn hàng #{{ $order->order_number }}</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                                    </li>
                                    <li class="breadcrumb-item active">Chi tiết</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                            class="btn btn-success btn-lg">
                            <i class="fa-solid fa-print me-2"></i> In hóa đơn
                        </a>
                        <button type="button" onclick="openStatusModal()" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-edit me-2"></i> Cập nhật trạng thái
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-timeline text-primary me-2"></i>Trạng thái đơn hàng
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="position-relative">
                    @php
                        $statuses = ['pending', 'paid', 'shipped', 'completed'];
                        $currentIndex = array_search($order->status->value, $statuses);
                        if ($order->status->value === 'cancelled') {
                            $currentIndex = -1;
                        }
                    @endphp

                    <div class="d-flex justify-content-between position-relative" style="z-index: 1;">
                        @foreach ($statuses as $index => $status)
                            <div class="text-center" style="flex: 1;">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2 {{ $index <= $currentIndex ? 'bg-primary text-white' : 'bg-light text-muted' }}"
                                    style="width: 50px; height: 50px; font-size: 20px; position: relative; z-index: 2;">
                                    @if ($index < $currentIndex)
                                        <i class="fa-solid fa-check"></i>
                                    @elseif($index === $currentIndex)
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    @else
                                        <span>{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div
                                    class="fw-semibold small {{ $index <= $currentIndex ? 'text-primary' : 'text-muted' }}">
                                    @switch($status)
                                        @case('pending')
                                            Chờ xử lý
                                        @break

                                        @case('paid')
                                            Đã thanh toán
                                        @break

                                        @case('shipped')
                                            Đang giao hàng
                                        @break

                                        @case('completed')
                                            Hoàn thành
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Progress Line -->
                    <div class="position-absolute top-0 start-0 w-100" style="height: 25px; z-index: 0;">
                        <div class="position-relative h-100 mx-5">
                            <div class="position-absolute w-100 bg-light" style="height: 4px; top: 23px;"></div>
                            <div class="position-absolute bg-primary"
                                style="height: 4px; top: 23px; width: {{ $currentIndex > 0 ? ($currentIndex / 3) * 100 : 0 }}%; transition: width 0.5s;">
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->status->value === 'cancelled')
                    <div class="alert alert-danger mt-4 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-ban fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Đơn hàng đã bị hủy</h6>
                                @if ($order->delivered_at)
                                    <p class="mb-0 small">Thời gian: {{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
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
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Sản phẩm</th>
                                        <th class="px-4 py-3 text-center">Số lượng</th>
                                        <th class="px-4 py-3 text-end">Đơn giá</th>
                                        <th class="px-4 py-3 text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px;">
                                                        @if ($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}" class="img-fluid rounded">
                                                        @else
                                                            <i class="fa-solid fa-image text-muted fs-4"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark">
                                                            {{ $item->product->name ?? 'N/A' }}</div>
                                                        @if ($item->variant)
                                                            <div class="small text-muted">{{ $item->variant->name }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center px-4 py-3">
                                                <span class="badge bg-light text-dark fs-6">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end px-4 py-3">{{ number_format($item->price) }}đ</td>
                                            <td class="text-end px-4 py-3 fw-bold text-primary">
                                                {{ number_format($item->price * $item->quantity) }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Summary -->
                        <div class="p-4 bg-light border-top">
                            <div class="row">
                                <div class="col-md-6 ms-auto">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Tạm tính:</span>
                                        <span
                                            class="fw-semibold">{{ number_format($order->total_amount - $order->shipping_fee) }}đ</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Phí vận chuyển:</span>
                                        <span class="fw-semibold">{{ number_format($order->shipping_fee) }}đ</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="h5 mb-0">Tổng cộng:</span>
                                        <span
                                            class="h4 mb-0 text-primary fw-bold">{{ number_format($order->total_amount) }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if ($order->customer_note || $order->admin_note)
                    <div class="row g-3">
                        @if ($order->customer_note)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                                    <div class="card-body">
                                        <h6 class="fw-semibold mb-3">
                                            <i class="fa-solid fa-comment-alt text-primary me-2"></i>Ghi chú của khách hàng
                                        </h6>
                                        <p class="mb-0 text-muted">{{ $order->customer_note }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($order->admin_note)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                                    <div class="card-body">
                                        <h6 class="fw-semibold mb-3">
                                            <i class="fa-solid fa-sticky-note text-warning me-2"></i>Ghi chú nội bộ
                                        </h6>
                                        <p class="mb-0 text-muted">{{ $order->admin_note }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-user text-primary me-2"></i>Thông tin khách hàng
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Họ tên</div>
                            <div class="fw-semibold">{{ $order->user->name ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Email</div>
                            <div class="fw-semibold">{{ $order->user->email ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="small text-muted mb-1">Số điện thoại</div>
                            <div class="fw-semibold">{{ $order->user->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if ($order->shippingAddress)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-map-marker-alt text-danger me-2"></i>Địa chỉ giao hàng
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="small text-muted mb-1">Người nhận</div>
                                <div class="fw-semibold">{{ $order->shippingAddress->receiver_name }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="small text-muted mb-1">Số điện thoại</div>
                                <div class="fw-semibold">{{ $order->shippingAddress->phone }}</div>
                            </div>
                            <div>
                                <div class="small text-muted mb-1">Địa chỉ</div>
                                <div class="fw-semibold">
                                    {{ $order->shippingAddress->address }},
                                    {{ $order->shippingAddress->ward }},
                                    {{ $order->shippingAddress->district }},
                                    {{ $order->shippingAddress->province }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-credit-card text-success me-2"></i>Thanh toán
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($order->payments->count() > 0)
                            @foreach ($order->payments as $payment)
                                <div class="{{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                    <div class="mb-2">
                                        <div class="small text-muted mb-1">Phương thức</div>
                                        <div class="fw-semibold">{{ $payment->payment_method->value ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="small text-muted mb-1">Trạng thái</div>
                                        @php
                                            $statusConfig = [
                                                'success' => ['class' => 'success', 'text' => 'Thành công'],
                                                'pending' => ['class' => 'warning', 'text' => 'Chờ thanh toán'],
                                                'failed' => ['class' => 'danger', 'text' => 'Thất bại'],
                                            ];
                                            $config = $statusConfig[$payment->status->value] ?? [
                                                'class' => 'secondary',
                                                'text' => $payment->status->value,
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }}">{{ $config['text'] }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <div class="small text-muted mb-1">Số tiền</div>
                                        <div class="fw-bold text-primary fs-5">{{ number_format($payment->amount) }}đ
                                        </div>
                                    </div>
                                    @if ($payment->transaction_id)
                                        <div class="mb-2">
                                            <div class="small text-muted mb-1">Mã giao dịch</div>
                                            <div class="font-monospace small">{{ $payment->transaction_id }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center mb-0">Chưa có thông tin thanh toán</p>
                        @endif
                    </div>
                </div>

                <!-- Order Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-info-circle text-info me-2"></i>Thông tin đơn hàng
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="small text-muted mb-1">Ngày tạo</div>
                            <div class="fw-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="small text-muted mb-1">Ngày giao hàng</div>
                            <div class="fw-semibold">
                                {{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : 'Chưa giao' }}</div>
                        </div>
                        @if ($order->completed_at)
                            <div class="mt-2">
                                <div class="small text-muted mb-1">Ngày hoàn thành</div>
                                <div class="fw-semibold">{{ $order->completed_at->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-edit text-primary me-2"></i>Cập nhật trạng thái
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Trạng thái mới</label>
                            <select name="status" class="form-select form-select-lg" required>
                                <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Chờ xử
                                    lý</option>
                                <option value="paid" {{ $order->status->value === 'paid' ? 'selected' : '' }}>Đã thanh
                                    toán</option>
                                <option value="shipped" {{ $order->status->value === 'shipped' ? 'selected' : '' }}>Đang
                                    giao hàng</option>
                                <option value="completed" {{ $order->status->value === 'completed' ? 'selected' : '' }}>
                                    Hoàn thành</option>
                                <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>
                                    Hủy đơn</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Ghi chú (tùy chọn)</label>
                            <textarea name="admin_note" rows="3" class="form-control" placeholder="Nhập ghi chú nội bộ...">{{ $order->admin_note }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-2"></i>Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openStatusModal() {
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }
    </script>
@endpush --}}






{{-- Bản  3: Chat gpt --}}
{{-- @extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->order_number)

@push('styles')
<style>
    /* General improvements */
    .card {
        border-radius: 0.8rem;
    }
    .card-header {
        border-radius: 0.8rem 0.8rem 0 0;
    }
    .page-actions .btn {
        min-width: 160px;
    }

    /* Timeline */
    .order-timeline {
        position: relative;
        padding: 1.5rem 0;
    }
    .timeline-line {
        position:absolute;
        left: 6%;
        right: 6%;
        top: 46px;
        height: 6px;
        background: #e9ecef;
        border-radius: 6px;
        z-index: 0;
    }
    .timeline-progress {
        position:absolute;
        left: 6%;
        top: 46px;
        height: 6px;
        background: linear-gradient(90deg,#0d6efd,#6610f2);
        border-radius: 6px;
        z-index: 1;
        transition: width 0.6s cubic-bezier(.2,.9,.2,1);
    }
    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .timeline-step .circle {
        width:58px; height:58px;
        border-radius:50%;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#f1f3f5;
        font-size:18px;
        transition: transform .3s, box-shadow .3s, background .3s;
    }
    .timeline-step.done .circle {
        background: linear-gradient(90deg,#198754,#20c997);
        color:#fff;
        transform: scale(1.06);
        box-shadow: 0 6px 20px rgba(25,135,84,0.18);
    }
    .timeline-step.active .circle {
        background: linear-gradient(90deg,#0d6efd,#6610f2);
        color: #fff;
        transform: scale(1.06);
        box-shadow: 0 6px 20px rgba(13,110,253,0.18);
    }
    .timeline-step small {
        display:block;
        margin-top:0.6rem;
    }

    /* Table & product */
    .product-thumb {
        width:72px;
        height:72px;
        border-radius:.5rem;
        overflow:hidden;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#f8f9fa;
    }
    .product-thumb img { width:100%; height:100%; object-fit:cover; }

    .order-summary .h4 { font-weight:700; }

    /* Sidebar cards */
    .info-label { color:#6c757d; font-size:.85rem; }

    /* Modal tweak */
    .modal-content { border-radius: 0.8rem; }
    .modal-backdrop.show { backdrop-filter: blur(4px); }

    /* Small responsive */
    @media (max-width: 992px) {
        .timeline-line, .timeline-progress { left: 4%; right: 4%; }
        .page-actions .btn { min-width: 120px; font-size: .92rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
                <div>
                    <h2 class="fw-bold mb-0">Đơn hàng #{{ $order->order_number }}</h2>
                    <small class="text-muted">Tạo: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                    <div>
                        <nav aria-label="breadcrumb" class="mt-1">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 text-md-end mt-3 mt-md-0 page-actions">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-success me-2">
                <i class="fa-solid fa-print me-1"></i> In hóa đơn
            </a>
            <button type="button" onclick="openStatusModal()" class="btn btn-primary me-2">
                <i class="fa-solid fa-edit me-1"></i> Cập nhật trạng thái
            </button>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-warning">
                <i class="fa-solid fa-pen me-1"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <!-- Timeline -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-timeline text-primary me-2"></i>Trạng thái đơn hàng</h5>
                <div class="small text-muted">Trạng thái hiện tại:
                    <span class="fw-semibold ms-1">
                        @php
                            $statusTexts = [
                                'pending' => 'Chờ xử lý',
                                'paid' => 'Đã thanh toán',
                                'shipped' => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                        @endphp
                        {{ $statusTexts[$order->status->value] ?? $order->status->value }}
                    </span>
                </div>
            </div>

            @php
                $statuses = ['pending','paid','shipped','completed'];
                $currentIndex = array_search($order->status->value, $statuses);
                if ($order->status->value === 'cancelled') $currentIndex = -1;
                $progressWidth = $currentIndex > 0 ? ($currentIndex / (count($statuses)-1)) * 100 : ($currentIndex === 0 ? 0 : 100);
            @endphp

            <div class="order-timeline">
                <div class="timeline-line"></div>
                <div class="timeline-progress" style="width: {{ $progressWidth }}%;"></div>

                <div class="d-flex" style="position:relative; z-index:2;">
                    @foreach ($statuses as $idx => $s)
                        @php
                            $isDone = $idx < $currentIndex;
                            $isActive = $idx === $currentIndex;
                        @endphp
                        <div class="timeline-step {{ $isDone ? 'done' : '' }} {{ $isActive ? 'active' : '' }}">
                            <div class="circle">
                                @if ($isDone)
                                    <i class="fa-solid fa-check"></i>
                                @elseif($isActive)
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                @else
                                    <span>{{ $idx + 1 }}</span>
                                @endif
                            </div>
                            <small class="mt-2">
                                @switch($s)
                                    @case('pending') Chờ xử lý @break
                                    @case('paid') Đã thanh toán @break
                                    @case('shipped') Đang giao hàng @break
                                    @case('completed') Hoàn thành @break
                                @endswitch
                            </small>
                        </div>
                    @endforeach
                </div>

                @if ($order->status->value === 'cancelled')
                    <div class="alert alert-danger mt-4 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-ban fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Đơn hàng đã bị hủy</h6>
                                @if ($order->updated_at)
                                    <p class="mb-0 small">Thời gian: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                @endif
                                @if ($order->admin_note)
                                    <p class="mb-0 small">Ghi chú: {{ $order->admin_note }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content: left (items) + right (sidebar) -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Order items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-box text-primary me-2"></i>Sản phẩm đã đặt</h5>
                    <div class="small text-muted">Số lượng mặt hàng: <span class="fw-semibold">{{ $order->orderItems->sum('quantity') }}</span></div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Sản phẩm</th>
                                    <th class="px-4 py-3 text-center">SKU / Variant</th>
                                    <th class="px-4 py-3 text-center">Số lượng</th>
                                    <th class="px-4 py-3 text-end">Đơn giá</th>
                                    <th class="px-4 py-3 text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="product-thumb me-3">
                                                    @if ($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                                    @else
                                                        <i class="fa-solid fa-image text-muted fs-4"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $item->product->name ?? 'N/A' }}</div>
                                                    @if ($item->product && $item->product->category)
                                                        <div class="small text-muted">{{ $item->product->category->name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center px-4 py-3">
                                            <div class="small text-muted mb-1">{{ $item->product->sku ?? ($item->variant->sku ?? '—') }}</div>
                                            <div class="fw-semibold">{{ $item->variant->name ?? '-' }}</div>
                                        </td>

                                        <td class="text-center px-4 py-3">
                                            <span class="badge bg-light text-dark fs-6">{{ $item->quantity }}</span>
                                        </td>

                                        <td class="text-end px-4 py-3">{{ number_format($item->price) }}đ</td>

                                        <td class="text-end px-4 py-3 fw-bold text-primary">{{ number_format($item->price * $item->quantity) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="p-4 bg-light border-top">
                        <div class="row">
                            <div class="col-md-6 ms-auto">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tạm tính</span>
                                    <span class="fw-semibold">{{ number_format($order->total_amount - $order->shipping_fee - ($order->discount_amount ?? 0)) }}đ</span>
                                </div>

                                @if (!empty($order->discount_amount) && $order->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Giảm giá</span>
                                        <span class="fw-semibold text-danger">-{{ number_format($order->discount_amount) }}đ</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí vận chuyển</span>
                                    <span class="fw-semibold">{{ number_format($order->shipping_fee) }}đ</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">Tổng cộng</span>
                                    <span class="h4 mb-0 text-primary fw-bold">{{ number_format($order->total_amount) }}đ</span>
                                </div>
                                <div class="small text-muted mt-2">Thanh toán:
                                    @php
                                        $payment = $order->payments->first();
                                    @endphp
                                    <span class="fw-semibold">{{ $payment ? ($payment->status->value === 'success' ? 'Đã thanh toán' : ucfirst($payment->status->value)) : 'Chưa thanh toán' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if ($order->customer_note || $order->admin_note)
                <div class="row g-3">
                    @if ($order->customer_note)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3"><i class="fa-solid fa-comment-alt text-primary me-2"></i>Ghi chú khách hàng</h6>
                                    <p class="mb-0 text-muted">{{ $order->customer_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($order->admin_note)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3"><i class="fa-solid fa-sticky-note text-warning me-2"></i>Ghi chú nội bộ</h6>
                                    <p class="mb-0 text-muted">{{ $order->admin_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Status history if any -->
            @if (method_exists($order, 'statusLogs') && $order->statusLogs->count() > 0)
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-clock-rotate-left me-2"></i>Lịch sử trạng thái</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach ($order->statusLogs as $log)
                                <li class="mb-2">
                                    <div class="small text-muted">{{ $log->created_at->format('d/m/Y H:i') }} — <span class="fw-semibold">{{ $log->admin?->name ?? $log->performed_by ?? 'System' }}</span></div>
                                    <div>{{ $log->message ?? ucfirst($log->status) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right column: sidebar -->
        <div class="col-lg-4">
            <!-- Customer -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user text-primary me-2"></i>Khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="info-label">Họ tên</div>
                        <div class="fw-semibold">{{ $order->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="info-label">Email</div>
                        <div class="fw-semibold">{{ $order->user->email ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="info-label">Số điện thoại</div>
                        <div class="fw-semibold">{{ $order->user->phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Shipping address -->
            @if ($order->shippingAddress)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-map-marker-alt text-danger me-2"></i>Địa chỉ giao hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="info-label">Người nhận</div>
                            <div class="fw-semibold">{{ $order->shippingAddress->receiver_name }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="info-label">Số điện thoại</div>
                            <div class="fw-semibold">{{ $order->shippingAddress->phone }}</div>
                        </div>
                        <div>
                            <div class="info-label">Địa chỉ</div>
                            <div class="fw-semibold">
                                {{ $order->shippingAddress->address }},
                                {{ $order->shippingAddress->ward }},
                                {{ $order->shippingAddress->district }},
                                {{ $order->shippingAddress->province }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-credit-card text-success me-2"></i>Thanh toán</h6>
                </div>
                <div class="card-body">
                    @if ($order->payments->count())
                        @foreach ($order->payments as $payment)
                            <div class="{{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="info-label">Phương thức</div>
                                <div class="fw-semibold mb-2">{{ $payment->payment_method->value ?? 'N/A' }}</div>

                                <div class="info-label">Trạng thái</div>
                                @php
                                    $pStatus = $payment->status->value ?? 'pending';
                                    $pMap = [
                                        'success' => ['class'=>'success','text'=>'Thành công'],
                                        'pending' => ['class'=>'warning','text'=>'Chờ thanh toán'],
                                        'failed'  => ['class'=>'danger','text'=>'Thất bại'],
                                    ];
                                    $pc = $pMap[$pStatus]['class'] ?? 'secondary';
                                    $pt = $pMap[$pStatus]['text'] ?? ucfirst($pStatus);
                                @endphp
                                <div class="mb-2"><span class="badge bg-{{ $pc }}">{{ $pt }}</span></div>

                                <div class="info-label">Số tiền</div>
                                <div class="fw-bold text-primary fs-5">{{ number_format($payment->amount) }}đ</div>

                                @if ($payment->transaction_id)
                                    <div class="info-label mt-2">Mã giao dịch</div>
                                    <div class="font-monospace small">{{ $payment->transaction_id }}</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Chưa có thông tin thanh toán</p>
                    @endif
                </div>
            </div>

            <!-- Order meta -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-info-circle text-info me-2"></i>Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="info-label">Ngày tạo</div>
                        <div class="fw-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="info-label">Ngày giao hàng</div>
                        <div class="fw-semibold">{{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : 'Chưa giao' }}</div>
                    </div>
                    @if ($order->completed_at)
                        <div class="mb-2">
                            <div class="info-label">Ngày hoàn thành</div>
                            <div class="fw-semibold">{{ $order->completed_at->format('d/m/Y H:i') }}</div>
                        </div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-primary w-100 mb-2"><i class="fa-solid fa-pen me-1"></i> Chỉnh sửa đơn</a>
                        <button type="button" class="btn btn-outline-danger w-100 btn-soft-delete" data-action="{{ route('admin.orders.destroy', $order->id) }}"><i class="fa-solid fa-trash me-1"></i> Chuyển thùng rác</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="statusForm" action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-edit text-primary me-2"></i>Cập nhật trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái mới</label>
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="paid" {{ $order->status->value === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="shipped" {{ $order->status->value === 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ $order->status->value === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú nội bộ (tùy chọn)</label>
                        <textarea name="admin_note" rows="3" class="form-control" placeholder="Ghi chú...">{{ $order->admin_note }}</textarea>
                    </div>

                    <div id="statusPreview" class="mt-2">
                        <div class="small text-muted">Xem trước trạng thái:</div>
                        <div class="mt-1"><span id="previewBadge" class="badge bg-secondary">—</span></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- SweetAlert2 (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Open modal
    function openStatusModal() {
        const modalEl = document.getElementById('statusModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        // init preview
        updatePreview(document.querySelector('#statusModal select[name="status"]').value);
    }

    // Preview badge
    function updatePreview(status) {
        const map = {
            'pending': { class: 'bg-warning text-dark', text: 'Chờ xử lý' },
            'paid': { class: 'bg-info text-white', text: 'Đã thanh toán' },
            'shipped': { class: 'bg-primary text-white', text: 'Đang giao hàng' },
            'completed': { class: 'bg-success text-white', text: 'Hoàn thành' },
            'cancelled': { class: 'bg-danger text-white', text: 'Đã hủy' }
        };
        const preview = document.getElementById('previewBadge');
        const cfg = map[status] || { class:'bg-secondary', text: status };
        preview.className = 'badge ' + cfg.class;
        preview.textContent = cfg.text;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.querySelector('#statusModal select[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                updatePreview(this.value);
            });
        }

        // AJAX submit status form
        const statusForm = document.getElementById('statusForm');
        if (statusForm) {
            statusForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const form = e.target;
                const data = new FormData(form);

                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: data
                    });

                    if (res.ok) {
                        const json = await res.json().catch(() => null);
                        const modalEl = document.getElementById('statusModal');
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        bsModal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Cập nhật thành công',
                            text: json?.message ?? 'Trạng thái đã được cập nhật.',
                            timer: 1400,
                            showConfirmButton: false
                        });

                        // reload to reflect changes (or you can implement partial update)
                        setTimeout(()=> location.reload(), 900);
                    } else {
                        const errText = await res.text();
                        Swal.fire({ icon: 'error', title: 'Lỗi', html: errText || 'Có lỗi xảy ra' });
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: err.message || 'Có lỗi mạng' });
                } finally {
                    submitBtn.disabled = false;
                }
            });
        }

        // Soft delete button (move to trash)
        document.querySelectorAll('.btn-soft-delete').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.dataset.action;
                Swal.fire({
                    title: 'Xác nhận',
                    html: `Đơn hàng sẽ được chuyển vào thùng rác. Bạn có chắc chắn muốn thực hiện?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Chuyển thùng rác',
                    cancelButtonText: 'Hủy',
                    focusCancel: true,
                }).then(result => {
                    if (result.isConfirmed) {
                        // submit delete form
                        const f = document.createElement('form');
                        f.method = 'POST';
                        f.action = action;
                        const token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = document.querySelector('meta[name="csrf-token"]').content;
                        f.appendChild(token);
                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        f.appendChild(method);
                        document.body.appendChild(f);
                        f.submit();
                    }
                });
            });
        });
    });
</script>
@endpush --}}




{{-- resources/views/admin/orders/show.blade.php Bản 3 --}}
@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . ($order->order_number ?? $order->id))

@push('styles')
    <style>
        /* Light, modern admin look */
        .card {
            border-radius: 12px;
        }

        .card-header {
            border-radius: 12px 12px 0 0;
        }

        .page-actions .btn {
            min-width: 130px;
        }

        .product-thumb {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .timeline-line {
            height: 6px;
            background: #e9ecef;
            border-radius: 6px;
            position: absolute;
            left: 6%;
            right: 6%;
            top: 38px;
            z-index: 0;
        }

        .timeline-progress {
            height: 6px;
            background: linear-gradient(90deg, #0d6efd, #6f42c1);
            border-radius: 6px;
            position: absolute;
            left: 6%;
            top: 38px;
            z-index: 1;
            transition: width .5s ease;
        }

        .timeline-step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .timeline-step .circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f3f5;
            font-size: 16px;
            transition: transform .2s ease;
        }

        .timeline-step.done .circle {
            background: linear-gradient(90deg, #198754, #20c997);
            color: #fff;
            transform: scale(1.06);
            box-shadow: 0 8px 20px rgba(25, 135, 84, .12);
        }

        .timeline-step.active .circle {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            color: #fff;
            transform: scale(1.06);
            box-shadow: 0 8px 20px rgba(13, 110, 253, .12);
        }

        .badge-status {
            padding: .5rem .75rem;
            border-radius: .6rem;
            font-weight: 600;
        }

        .summary-row {
            font-size: 1.02rem;
        }

        @media (max-width: 992px) {

            .timeline-line,
            .timeline-progress {
                left: 3%;
                right: 3%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">

        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h3 class="fw-bold mb-1">Đơn hàng #{{ $order->order_number ?? $order->id }}</h3>
                <div class="text-muted small">Tạo: {{ $order->created_at->format('d/m/Y H:i') }} • ID: {{ $order->id }}
                </div>
            </div>

            <div class="d-flex gap-2 page-actions">
                {{-- Export (nếu route export cần id, route('admin.orders.export', ['id' => $order->id]) ) --}}
                <a href="{{ route('admin.orders.export') }}?id={{ $order->id }}" class="btn btn-outline-success">
                    <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
                </a>

                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-print me-1"></i> In hóa đơn
                </a>

                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
                    <i class="fa-solid fa-arrow-left me-1"></i> Danh sách
                </a>
            </div>
        </div>

        {{-- Timeline card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body position-relative">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-timeline text-primary me-2"></i>Trạng thái đơn hàng
                    </h6>
                    {{-- Current status badge --}}
                    @php
                        $statusVal = is_object($order->status) ? $order->status->value : $order->status ?? 'pending';
                        $statusMap = [
                            'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ xử lý'],
                            'paid' => ['class' => 'bg-info text-white', 'text' => 'Đã thanh toán'],
                            'shipped' => ['class' => 'bg-primary text-white', 'text' => 'Đang giao'],
                            'completed' => ['class' => 'bg-success text-white', 'text' => 'Hoàn thành'],
                            'cancelled' => ['class' => 'bg-danger text-white', 'text' => 'Đã hủy'],
                            'canceled' => ['class' => 'bg-danger text-white', 'text' => 'Đã hủy'],
                        ];
                        $statusCfg = $statusMap[$statusVal] ?? [
                            'class' => 'bg-secondary text-white',
                            'text' => ucfirst($statusVal),
                        ];
                    @endphp
                    <span class="badge badge-status {{ $statusCfg['class'] }}">{{ $statusCfg['text'] }}</span>
                </div>

                @php
                    $steps = ['pending', 'paid', 'shipped', 'completed'];
                    $idx = array_search($statusVal, $steps);
                    if ($statusVal === 'cancelled' || $statusVal === 'canceled') {
                        $idx = -1;
                    }
                    $progress = $idx > 0 ? ($idx / (count($steps) - 1)) * 100 : ($idx === 0 ? 0 : 100);
                @endphp

                <div style="position:relative; padding: 1.5rem 0;">
                    <div class="timeline-line"></div>
                    <div class="timeline-progress" style="width: {{ $progress }}%;"></div>

                    <div class="d-flex" style="position:relative; z-index:2;">
                        @foreach ($steps as $sindex => $s)
                            @php
                                $done = $sindex < $idx;
                                $active = $sindex === $idx;
                            @endphp
                            <div class="timeline-step {{ $done ? 'done' : '' }} {{ $active ? 'active' : '' }}">
                                <div class="circle mb-2">
                                    @if ($done)
                                        <i class="fa-solid fa-check"></i>
                                    @elseif($active)
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    @else
                                        <span>{{ $sindex + 1 }}</span>
                                    @endif
                                </div>
                                <div class="{{ $done || $active ? 'fw-semibold' : 'text-muted' }} small">
                                    @switch($s)
                                        @case('pending')
                                            Chờ xử lý
                                        @break

                                        @case('paid')
                                            Đã thanh toán
                                        @break

                                        @case('shipped')
                                            Đang giao hàng
                                        @break

                                        @case('completed')
                                            Hoàn thành
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (in_array($statusVal, ['cancelled', 'canceled']))
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="fa-solid fa-ban me-2"></i>Đơn hàng đã bị hủy
                            @if (!empty($order->cancelled_at))
                                <div class="small text-muted mt-1">Thời gian:
                                    {{ $order->cancelled_at->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Main content: left list of items, right sidebar --}}
        <div class="row g-4">
            {{-- LEFT: Items + summary --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-box-open text-primary me-2"></i>Sản phẩm</h5>
                        <div class="small text-muted">Số mặt hàng: <span
                                class="fw-semibold">{{ $order->orderItems->sum('quantity') ?? 0 }}</span></div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:80px;">Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($order->orderItems ?? [] as $item)
                                        @php
                                            $prod = $item->product ?? null;
                                            // support both product->image (string) or product->images pivot collection
                                            $imagePath =
                                                $prod?->image ??
                                                ($prod?->images?->first()?->path ?? 'images/default-product.png');
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="product-thumb">
                                                    @if ($imagePath)
                                                        <img src="{{ asset('storage/' . $imagePath) }}"
                                                            alt="{{ $prod->name ?? '' }}">
                                                    @else
                                                        <i class="fa-solid fa-image text-muted"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $prod->name ?? 'Sản phẩm đã bị xóa' }}</div>
                                                @if ($item->variant)
                                                    <div class="small text-muted">Variant: {{ $item->variant->name }}</div>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                                                <div>Không có sản phẩm nào trong đơn này.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- summary footer --}}
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end summary-row">Tạm tính:</td>
                                        <td class="text-end summary-row">
                                            {{ number_format($order->total_amount - ($order->shipping_fee ?? 0), 0, ',', '.') }}
                                            đ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end summary-row">Phí vận chuyển:</td>
                                        <td class="text-end summary-row">
                                            {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</td>
                                    </tr>
                                    @if (!empty($order->discount_amount) && $order->discount_amount > 0)
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-end summary-row">Giảm giá:</td>
                                            <td class="text-end text-danger">
                                                -{{ number_format($order->discount_amount, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end summary-row h6">Tổng cộng:</td>
                                        <td class="text-end h5 text-primary fw-bold">
                                            {{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if (!empty($order->customer_note) || !empty($order->admin_note))
                    <div class="row g-3">
                        @if (!empty($order->customer_note))
                            <div class="col-md-6">
                                <div class="card border-start border-primary border-4 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-semibold"><i class="fa-solid fa-comment text-primary me-2"></i>Ghi chú
                                            khách</h6>
                                        <p class="mb-0 text-muted">{{ $order->customer_note }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (!empty($order->admin_note))
                            <div class="col-md-6">
                                <div class="card border-start border-warning border-4 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-semibold"><i class="fa-solid fa-sticky-note text-warning me-2"></i>Ghi
                                            chú nội bộ</h6>
                                        <p class="mb-0 text-muted">{{ $order->admin_note }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- optional: status log (if exists) --}}
                @if (method_exists($order, 'statusLogs') && $order->statusLogs->count())
                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-history me-2"></i>Lịch sử trạng thái</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @foreach ($order->statusLogs as $log)
                                    <li class="mb-2">
                                        <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }} —
                                            {{ $log->admin?->name ?? ($log->performed_by ?? 'System') }}</small>
                                        <div>{{ $log->message ?? ucfirst($log->status) }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Sidebar (customer, shipping, payment, actions) --}}
            <div class="col-lg-4">
                {{-- Customer --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user text-primary me-2"></i>Khách hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">Tên</div>
                        <div class="fw-semibold mb-2">{{ $order->user->name ?? 'N/A' }}</div>

                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold mb-2">{{ $order->user->email ?? 'N/A' }}</div>

                        <div class="small text-muted">Điện thoại</div>
                        <div class="fw-semibold mb-2">
                            {{ $order->shippingAddress->phone ?? ($order->shipping_phone ?? 'N/A') }}</div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-map-marker-alt text-danger me-2"></i>Địa chỉ
                            giao</h6>
                    </div>
                    <div class="card-body">
                        @if ($order->shippingAddress)
                            <div class="small text-muted">Người nhận</div>
                            <div class="fw-semibold mb-2">{{ $order->shippingAddress->receiver_name }}</div>
                            <div class="small text-muted">Địa chỉ</div>
                            <div class="fw-semibold">{{ $order->shippingAddress->address }},
                                {{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }},
                                {{ $order->shippingAddress->province }}</div>
                        @else
                            <div class="text-muted">Không có địa chỉ giao hàng.</div>
                        @endif
                    </div>
                </div>

                {{-- Payment --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-credit-card text-success me-2"></i>Thanh toán
                        </h6>
                    </div>
                    <div class="card-body">
                        @php $payment = $order->payments->first() ?? null; @endphp
                        <div class="small text-muted">Phương thức</div>
                        <div class="fw-semibold mb-2">
                            {{ $payment?->payment_method->value ?? ($order->payment_method ?? 'N/A') }}</div>

                        <div class="small text-muted">Trạng thái</div>
                        @php
                            $pstatus = $payment?->status->value ?? ($payment?->status ?? null);
                            $pclass =
                                $pstatus === 'success'
                                    ? 'bg-success text-white'
                                    : ($pstatus === 'pending'
                                        ? 'bg-warning text-dark'
                                        : ($pstatus === 'failed'
                                            ? 'bg-danger text-white'
                                            : 'bg-secondary text-white'));
                            $ptext =
                                $pstatus === 'success'
                                    ? 'Thành công'
                                    : ($pstatus === 'pending'
                                        ? 'Chờ thanh toán'
                                        : ($pstatus === 'failed'
                                            ? 'Thất bại'
                                            : $pstatus ?? 'Chưa có'));
                        @endphp
                        <div class="mb-2"><span class="badge {{ $pclass }}">{{ $ptext }}</span></div>

                        <div class="small text-muted">Tổng thanh toán</div>
                        <div class="fw-semibold text-primary">
                            {{ number_format($payment?->amount ?? $order->total_amount, 0, ',', '.') }} đ</div>

                        @if ($payment?->transaction_id)
                            <div class="small text-muted mt-2">Mã giao dịch</div>
                            <div class="font-monospace small">{{ $payment->transaction_id }}</div>
                        @endif
                    </div>
                </div>

                {{-- Actions: Update status (modal), Cancel (disabled if shipped/completed), Ship/Complete buttons --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <!-- Open modal to update status -->
                            <button class="btn btn-primary" type="button" onclick="openStatusModal()">
                                <i class="fa-solid fa-edit me-1"></i> Cập nhật trạng thái
                            </button>

                            {{-- Cancel: blocked if already shipped/completed/cancelled --}}
                            @php
                                $cannotCancel = in_array($statusVal, ['shipped', 'completed', 'cancelled', 'canceled']);
                            @endphp

                            <form id="form-cancel" action="{{ route('admin.orders.cancel', $order->id) }}"
                                method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger"
                                    {{ $cannotCancel ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-times-circle me-1"></i> Hủy đơn
                                </button>
                            </form>

                            {{-- Ship button: only when paid --}}
                            @if ($statusVal === 'paid')
                                <form action="{{ route('admin.orders.ship', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Chuyển đơn sang trạng thái ĐANG GIAO?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-truck me-1"></i> Đang giao
                                    </button>
                                </form>
                            @endif

                            {{-- Complete button: only when shipped --}}
                            @if ($statusVal === 'shipped')
                                <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Xác nhận hoàn tất đơn?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-check me-1"></i> Hoàn thành
                                    </button>
                                </form>
                            @endif

                            {{-- Delete (soft) --}}
                            <form id="form-delete" action="{{ route('admin.orders.destroy', $order->id) }}"
                                method="POST" onsubmit="return confirm('Chuyển đơn vào thùng rác?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-trash-can me-1"></i> Chuyển thùng rác
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Update Status Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="statusForm" class="modal-content" action="{{ route('admin.orders.update-status', $order->id) }}"
                method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-edit me-2"></i> Cập nhật trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label fw-semibold">Trạng thái mới</label>
                    <select name="status" id="statusSelect" class="form-select mb-3" required>
                        {{-- Prefer using enum if exists --}}
                        @if (class_exists(\App\Enums\OrderStatus::class))
                            @foreach (\App\Enums\OrderStatus::cases() as $s)
                                @php $val = $s->value; @endphp
                                <option value="{{ $val }}" @selected((is_object($order->status) ? $order->status->value : $order->status) === $val)>
                                    {{ ucfirst($val) }}
                                </option>
                            @endforeach
                        @else
                            <option value="pending" {{ $statusVal === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="paid" {{ $statusVal === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="shipped" {{ $statusVal === 'shipped' ? 'selected' : '' }}>Đang giao</option>
                            <option value="completed" {{ $statusVal === 'completed' ? 'selected' : '' }}>Hoàn thành
                            </option>
                            <option value="cancelled"
                                {{ in_array($statusVal, ['cancelled', 'canceled']) ? 'selected' : '' }}>Hủy</option>
                        @endif
                    </select>

                    <label class="form-label fw-semibold">Ghi chú nội bộ (tuỳ chọn)</label>
                    <textarea name="admin_note" class="form-control mb-2" rows="3" placeholder="Ghi chú...">{{ old('admin_note', $order->admin_note ?? '') }}</textarea>

                    <div class="small text-muted">Lưu ý: Nếu đơn đã ở trạng thái <strong>Đang giao</strong> hoặc
                        <strong>Hoàn thành</strong>, sẽ không thể hủy.</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" id="statusSubmit" class="btn btn-primary"><i
                            class="fa-solid fa-save me-1"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openStatusModal() {
            const modalEl = document.getElementById('statusModal');
            const bs = new bootstrap.Modal(modalEl);
            bs.show();

            // Preview badge behavior (optional)
            updatePreview(document.getElementById('statusSelect').value);
        }

        function updatePreview(status) {
            // optional - preview text in modal (not implemented visually here)
            // could be extended to show a colored badge inside the modal
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('statusForm');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('statusSubmit');
                submitBtn.disabled = true;

                const data = new FormData(form);
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: data
                    });

                    if (res.ok) {
                        const json = await res.json().catch(() => null);
                        bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Cập nhật thành công',
                            text: json?.message ?? 'Trạng thái đã được cập nhật.',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 900);
                    } else {
                        const text = await res.text();
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            html: text || 'Có lỗi xảy ra'
                        });
                    }
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi mạng',
                        text: err.message || 'Vui lòng thử lại'
                    });
                } finally {
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
@endpush
