@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <div class="container-fluid py-4">

        {{-- 🧭 Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="fas fa-receipt text-primary me-2"></i>Chi tiết đơn hàng #{{ $order->id }}
                </h2>
                <p class="text-muted mb-0">Cập nhật lần cuối: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
            </div>

            {{-- 🔧 Hành động nhanh --}}
            <div class="d-flex gap-2">
                @if (!in_array($order->status->value, ['shipped', 'completed', 'cancelled']))
                    <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Xác nhận hủy đơn hàng này?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-1"></i> Hủy đơn
                        </button>
                    </form>
                @endif

                @if ($order->status->value === 'paid')
                    <form action="{{ route('admin.orders.ship', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-truck me-1"></i> Xác nhận giao hàng
                        </button>
                    </form>
                @endif

                @if ($order->status->value === 'shipped')
                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i> Hoàn tất đơn
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- 🧱 Thông tin chung --}}
        <div class="row">
            {{-- 🧍 Thông tin khách hàng --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fas fa-user me-2"></i>Thông tin khách hàng
                    </div>
                    <div class="card-body">
                        <p><strong>Tên:</strong> {{ $order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $order->shippingAddress->phone ?? '—' }}</p>
                        <p><strong>Địa chỉ giao hàng:</strong><br>
                            {{ $order->shippingAddress->full_address ?? 'Chưa có địa chỉ' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- 💳 Thanh toán & Trạng thái --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="fas fa-credit-card me-2"></i>Thông tin thanh toán
                    </div>
                    <div class="card-body">
                        <p><strong>Phương thức:</strong> {{ $order->payment->method ?? 'Không rõ' }}</p>
                        <p><strong>Tổng tiền:</strong>
                            <span
                                class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                        </p>
                        <p><strong>Trạng thái:</strong>
                            @php
                                $statusColors = [
                                    'pending' => 'secondary',
                                    'paid' => 'info',
                                    'shipped' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span
                                class="badge bg-{{ $statusColors[$order->status->value] ?? 'secondary' }} text-uppercase px-3 py-2">
                                {{ ucfirst($order->status->value) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- 🚚 Vận chuyển --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="fas fa-shipping-fast me-2"></i>Thông tin vận chuyển
                    </div>
                    <div class="card-body">
                        <p><strong>Đơn vị vận chuyển:</strong> {{ $order->shipping_method ?? 'Chưa có' }}</p>
                        <p><strong>Mã vận đơn:</strong> {{ $order->tracking_number ?? '—' }}</p>
                        <p><strong>Ngày giao dự kiến:</strong>
                            {{ $order->shipped_at ? $order->shipped_at->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🛒 Danh sách sản phẩm --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white fw-bold">
                <i class="fas fa-box-open me-2"></i>Danh sách sản phẩm
            </div>
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Phân loại</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            @php
                                $product = $item->product;
                                $primaryImage =
                                    $product->images->firstWhere('pivot.is_main', true) ?? $product->images->first();
                                $imgPath = $primaryImage->path ?? 'images/default-product.png';
                            @endphp
                            <tr>
                                <td><img src="{{ asset('storage/' . $imgPath) }}" alt="product" class="rounded"
                                        style="width:60px; height:60px; object-fit:cover;"></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $item->variant->name ?? '—' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                <td class="fw-bold text-danger">
                                    {{ number_format($item->quantity * $item->price, 0, ',', '.') }}₫
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 🕓 Lịch sử đơn hàng --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-bold">
                <i class="fas fa-clock me-2 text-primary"></i>Lịch sử đơn hàng
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                    </li>
                    @if ($order->paid_at)
                        <li class="list-group-item"><strong>Thanh toán:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}
                        </li>
                    @endif
                    @if ($order->shipped_at)
                        <li class="list-group-item"><strong>Giao hàng:</strong>
                            {{ $order->shipped_at->format('d/m/Y H:i') }}</li>
                    @endif
                    @if ($order->completed_at)
                        <li class="list-group-item"><strong>Hoàn thành:</strong>
                            {{ $order->completed_at->format('d/m/Y H:i') }}</li>
                    @endif
                    @if ($order->cancelled_at)
                        <li class="list-group-item text-danger"><strong>Đã hủy:</strong>
                            {{ $order->cancelled_at->format('d/m/Y H:i') }}</li>
                    @endif
                </ul>
            </div>
        </div>

    </div>
@endsection
