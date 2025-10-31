{{-- Thông báo góc phải trên --}}
{{-- @if (session('success'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            toast: true,
            // position: 'top-end',
            position: 'bottom-end', // góc dưới bên phải
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
@endif --}}





{{-- Thông báo góc phải dưới --}}
{{-- @if (session('success') || session('error') || session('info') || session('warning'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end', // Góc dưới bên phải
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if (session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}'
            });
        @endif

        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif
    </script>
@endif --}}



@if (session('success') || session('error') || session('info') || session('warning'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end', // Góc dưới bên phải
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            customClass: {
                popup: 'rounded-lg shadow-lg text-white',
                timerProgressBar: 'bg-white/50'
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                background: 'linear-gradient(135deg, #10b981, #3b82f6)', // gradient xanh-lá -> xanh dương
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                background: 'linear-gradient(135deg, #ef4444, #f59e0b)', // gradient đỏ -> cam
            });
        @endif

        @if (session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}',
                background: 'linear-gradient(135deg, #3b82f6, #06b6d4)', // gradient xanh dương -> xanh biển
            });
        @endif

        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}',
                background: 'linear-gradient(135deg, #facc15, #f97316)', // gradient vàng -> cam
            });
        @endif
    </script>
@endif
