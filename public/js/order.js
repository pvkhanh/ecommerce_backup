/**
 * Order Management JavaScript
 * Handles AJAX operations for order management
 */

document.addEventListener('DOMContentLoaded', function() {

    // Initialize tooltips
    initializeTooltips();

    // Quick status update
    initializeQuickStatusUpdate();

    // Delete confirmation
    initializeDeleteConfirmation();

    // Export orders
    initializeExport();

    // Real-time search
    initializeSearch();
});

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
}

/**
 * Quick status update via AJAX
 */
function initializeQuickStatusUpdate() {
    const statusButtons = document.querySelectorAll('.quick-status-btn');

    statusButtons.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();

            const orderId = this.dataset.orderId;
            const status = this.dataset.status;
            const url = this.dataset.url;

            try {
                // Show loading
                Swal.fire({
                    title: 'Đang cập nhật...',
                    html: '<div class="spinner-border text-primary"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to show updated status
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message,
                    confirmButtonText: 'Đóng'
                });
            }
        });
    });
}

/**
 * Delete confirmation with SweetAlert2
 */
function initializeDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const deleteUrl = this.dataset.action;
            const orderNumber = this.dataset.order;

            Swal.fire({
                title: 'Xác nhận xóa',
                html: `
                    <div class="text-center">
                        <i class="fa-solid fa-box-open text-warning mb-3" style="font-size: 64px;"></i>
                        <p class="mb-2">Bạn có chắc chắn muốn xóa đơn hàng</p>
                        <p class="fw-bold text-warning fs-5 mb-2">#${orderNumber}</p>
                        <div class="alert alert-info mt-3">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <small>Đơn hàng sẽ được chuyển vào thùng rác, có thể khôi phục sau.</small>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-trash me-2"></i> Xóa',
                cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy bỏ',
                reverseButtons: true,
                width: '600px',
                customClass: {
                    confirmButton: 'btn btn-warning btn-lg px-4',
                    cancelButton: 'btn btn-secondary btn-lg px-4'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    submitDeleteForm(deleteUrl);
                }
            });
        });
    });
}

/**
 * Submit delete form
 */
function submitDeleteForm(url) {
    Swal.fire({
        title: 'Đang xóa...',
        html: '<div class="spinner-border text-warning"></div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Export orders to Excel
 */
function initializeExport() {
    const exportBtn = document.getElementById('exportBtn');

    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Xuất dữ liệu',
                html: `
                    <div class="text-start">
                        <p class="mb-3">Bạn muốn xuất dữ liệu với bộ lọc hiện tại?</p>
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <small>File Excel sẽ được tải xuống tự động</small>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-download me-2"></i> Xuất Excel',
                cancelButtonText: 'Hủy',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get current filter params
                    const urlParams = new URLSearchParams(window.location.search);
                    const exportUrl = this.href + '?' + urlParams.toString();

                    // Download file
                    window.location.href = exportUrl;

                    Swal.fire({
                        icon: 'success',
                        title: 'Đang tải xuống...',
                        text: 'File Excel đang được chuẩn bị',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    }
}

/**
 * Real-time search with debounce
 */
function initializeSearch() {
    const searchInput = document.querySelector('input[name="search"]');

    if (searchInput) {
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                // Auto submit form after 500ms of no typing
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
}

/**
 * Order status color classes
 */
const statusColors = {
    pending: 'warning',
    paid: 'info',
    shipped: 'primary',
    completed: 'success',
    cancelled: 'danger'
};

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Show notification
 */
function showNotification(type, message) {
    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    toast.fire({
        icon: type,
        title: message
    });
}

/**
 * Confirm action
 */
function confirmAction(title, text, icon = 'warning') {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    });
}

// Export functions for global use
window.OrderManager = {
    formatCurrency,
    showNotification,
    confirmAction,
    statusColors
};
