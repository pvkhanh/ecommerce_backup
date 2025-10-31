@props(['show' => null, 'edit' => null, 'delete' => null])

<div class="d-flex justify-content-center align-items-center gap-2">
    @if ($show)
        <a href="{{ $show }}" class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="tooltip"
            title="Xem chi tiết">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    @if ($edit)
        <a href="{{ $edit }}" class="btn btn-sm btn-outline-success rounded-circle" data-bs-toggle="tooltip"
            title="Chỉnh sửa">
            <i class="fas fa-pen-to-square"></i>
        </a>
    @endif

    @if ($delete)
        <form action="{{ $delete }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" data-bs-toggle="tooltip"
                title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el))
        });
    </script>
@endpush
