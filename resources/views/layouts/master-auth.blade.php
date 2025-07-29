{{-- Layout maestro para las pantallas del Auth --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Aquafeel es una organización comprometida en proporcionar agua potable, 
limpia y segura a nuestras comunidades, al tiempo que facilita el ahorro de 
dinero y promueve la sostenibilidad y la salud.">
    <meta name="author" content="AQUAFEEL Global">
    <!-- App favicon -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" sizes="512x512" href="{{ asset('/icono.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('/icono.png') }}">
    <link rel="shortcut icon" href="{{ asset('icono.png') }}">
    <!-- Styles css -->
    @include('layouts.partials.styles')
</head>

<body>
    
    <div class="main-content-auth">
        <div class="page-content">
            <div class="container-fluid">
                <!-- content of the pages -->
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Script js -->
    @include('layouts.partials.scripts')
</body>

</html>
