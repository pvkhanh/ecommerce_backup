@extends('layouts.admin')

@section('title', 'Tạo đơn hàng mới')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-1"><i class="fa-solid fa-cart-plus me-2"></i> Tạo đơn hàng mới</h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Khách hàng</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="total" class="form-label">Tổng tiền (₫)</label>
                        <input type="number" name="total" id="total" class="form-control" value="{{ old('total') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="note" class="form-label">Ghi chú</label>
                        <input type="text" name="note" id="note" class="form-control" value="{{ old('note') }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-save me-1"></i> Lưu đơn hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
