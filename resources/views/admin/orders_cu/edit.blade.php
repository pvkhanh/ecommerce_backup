@extends('layouts.admin')

@section('title', 'Chỉnh sửa đơn hàng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-warning mb-1"><i class="fa-solid fa-pen-to-square me-2"></i> Chỉnh sửa đơn hàng</h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Khách hàng</label>
                        <select name="user_id" id="user_id" class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            @foreach (['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'] as $key => $label)
                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="total" class="form-label">Tổng tiền (₫)</label>
                        <input type="number" name="total" id="total" class="form-control"
                            value="{{ $order->total }}">
                    </div>

                    <div class="col-md-6">
                        <label for="note" class="form-label">Ghi chú</label>
                        <input type="text" name="note" id="note" class="form-control"
                            value="{{ $order->note }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật đơn hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
