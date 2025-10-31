@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="page-heading mb-4">
        <h3 class="fw-bold text-primary">
            <i class="fa-solid fa-circle-info me-2"></i> Thông tin người dùng
        </h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <i class="fa-solid fa-circle-user fa-6x text-secondary"></i>
                    <h5 class="mt-3 fw-bold">{{ $user->username }}</h5>
                    <span
                        class="badge rounded-pill
                    @if ($user->role === 'admin') bg-danger
                    @elseif($user->role === 'staff') bg-warning text-dark
                    @else bg-secondary @endif">
                        <i class="fa-solid fa-user-shield me-1"></i> {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="30%">Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối</th>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
