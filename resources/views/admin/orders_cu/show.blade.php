{{-- @extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <h4>Chi tiết đơn hàng #{{ $order->id }}</h4>

    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }}₫</p>
            <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <h5 class="mt-4">Sản phẩm trong đơn hàng</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">← Quay lại</a>
@endsection --}}


@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Đơn hàng #{{ $order->order_number }}</h5>
            <p><strong>Khách hàng:</strong>
                @if ($order->user)
                    {{ $order->user->username }} <br>
                    {{-- <small class="text-muted">{{ $order->user->email }}</small> --}}
                @else
                    <span class="badge bg-secondary">
                        <i class="fa-solid fa-user-slash me-1"></i> Khách chưa đăng ký
                    </span>
                @endif
            </p>

            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} ₫</p>
            <p><strong>Trạng thái:</strong> <span class="badge bg-info">{{ $order->status }}</span></p>
            <p><strong>Ngày tạo:</strong> {{ optional($order->created_at)->format('d/m/Y H:i') }}</p>
        </div>
    </div>
@endsection
