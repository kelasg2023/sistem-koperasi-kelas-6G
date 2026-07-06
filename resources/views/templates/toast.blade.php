<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        // Fallback setTimeout in case module loads slightly later than DOMContentLoaded
        setTimeout(() => {
            if (typeof Swal === 'undefined' && !window.Swal) return;
            const swalInstance = window.Swal || Swal;
            
            const Toast = swalInstance.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', swalInstance.stopTimer)
                    toast.addEventListener('mouseleave', swalInstance.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('status'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('status') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: "{{ $errors->first() }}"
                });
            @endif
        }, 100);
    });
</script>
