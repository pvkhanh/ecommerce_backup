{{-- @extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Danh sách đơn hàng</h4>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($order->total, 0, ',', '.') }}₫</td>
                    <td>
                        <span
                            class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">Xem</a>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa đơn hàng này?')" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Không có đơn hàng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('components.pagination', ['data' => $orders])
@endsection --}}

@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="fa-solid fa-receipt me-2"></i> Quản lý đơn hàng
            </h3>
            <p class="text-muted mb-0">Danh sách tất cả đơn hàng trong hệ thống</p>
        </div>
    </div>

    {{-- Ô tìm kiếm --}}
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control border-primary"
                placeholder="🔍 Tìm kiếm theo mã đơn hoặc email khách hàng..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i> Tìm
            </button>
        </div>
    </form>

    {{-- Bảng danh sách --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:60px;">#</th>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Tổng tiền</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-center" style="width:160px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            // ✅ Xử lý status an toàn theo kiểu dữ liệu thực tế
                            $statusValue = is_object($order->status)
                                ? $order->status->value ?? ($order->status->name ?? 'unknown')
                                : $order->status ?? 'unknown';

                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];

                            $color = $statusColors[$statusValue] ?? 'secondary';

                            // ✅ Tổng tiền an toàn (chuẩn repo)
                            $total =
                                $order->total ??
                                ($order->total_amount ??
                                    ($order->relationLoaded('items')
                                        ? $order->items->sum(fn($i) => $i->quantity * $i->price)
                                        : 0));
                        @endphp

                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">
                                <i class="fa-solid fa-hashtag text-muted me-1"></i>{{ $order->code ?? 'ORD-' . $order->id }}
                            </td>
                            {{-- <td>
                                <div class="fw-semibold">{{ $order->user->name ?? 'Khách vãng lai' }}</div>
                                <div class="small text-muted">{{ $order->user->email ?? '—' }}</div>
                            </td> --}}
                            <td>
                                @if ($order->user)
                                    <div class="fw-semibold">{{ $order->user->username }}</div>
                                    <div class="small text-muted">{{ $order->user->email }}</div>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fa-solid fa-user-slash me-1"></i> Khách chưa đăng ký
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($statusValue) }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold text-success">
                                {{ number_format($total, 0, ',', '.') }} ₫
                            </td>
                            <td class="text-center text-muted">{{ $order->created_at->format('d/m/Y') }}</td>

                            <td class="text-center">
                                <x-action-buttons :show="route('admin.orders.show', $order->id)" :delete="route('admin.orders.destroy', $order->id)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa-regular fa-circle-xmark fs-4 d-block mb-2"></i>
                                Không có đơn hàng nào được tìm thấy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Phân trang --}}
    <div class="mt-3">
        {{ $orders->links('components.pagination') }}
    </div>
@endsection
