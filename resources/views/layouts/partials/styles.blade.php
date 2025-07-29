{{-- precarga de imagenes --}}

<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Home=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Home=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Reportes=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Reportes=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Universidad=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Universidad=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Eventos=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Eventos=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Tienda=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Tienda=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Clientes=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Clientes=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Ajustes=Default.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('img/navbar/Ajustes=Hover.svg') }}">
<link rel="preload" as="image" href="{{ \App\Helpers\AssetVersioning::version('vendor/data-picker-bootstrap5/fonts/gijgo-material.svg') }}">

{{-- styles css  de vendors comunes a toda la app --}}
<link href="{{ \App\Helpers\AssetVersioning::version('vendor/bootstrap-5/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ \App\Helpers\AssetVersioning::version('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ \App\Helpers\AssetVersioning::version('css/app.css') }}" rel="stylesheet" type="text/css" />

{{-- styles css  insertado por la vista actual --}}

@stack('css')
