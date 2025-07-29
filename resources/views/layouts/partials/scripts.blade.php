{{-- scripts js de vendors comunes a toda la app --}}

<script src="{{ \App\Helpers\AssetVersioning::version('vendor/bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('vendor/feather-icons/feather.min.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('vendor/jquery-3.7.1.min.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('vendor/blockUI.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('vendor/moment/moment.min.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('js/app.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('js/notification-handler.js') }}"></script>
<script src="{{ \App\Helpers\AssetVersioning::version('js/notification-test.js') }}"></script>

{{-- scripts js insertado por la vista actual  --}}

@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: "{{ session('success') }}",
        showConfirmButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#3085d6',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: "{{ str_replace('\n', '<br>', session('error')) }}",
        showConfirmButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#d33'
    });
</script>
@endif
