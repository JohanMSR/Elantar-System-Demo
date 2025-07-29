@extends('layouts.master')

@section('title')
    @lang('translation.customers_title') - @lang('translation.business-center')
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .btnRango {
            background-color: var(--color-primary, #13c0e6);
            margin-bottom: 5px;
        }

        .campo-date {
            margin-bottom: 5px;
        }

        .tabla-informe {
            display: block;
            overflow: auto;
            width: 300px;
        }
        
        .form-control-plaintext {
            background-color: var(--color-input-bg-hover, #f8f9fa);
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--color-border, #ced4da);
            border-radius: var(--radius-sm, 0.25rem);
            transition: var(--transition-normal, all 0.3s ease);
        }
        
        .input-group {
            margin-top: 10px;
        }

        .send-icon {
            margin-left: 5px;
        }

        .table th {
            font-size: 1.2em;
            padding: 15px;
        }

        .file-upload-container {
            background: white;
            border: 1px dashed var(--color-border, #ccc);
            padding: 20px;
            text-align: center;
            cursor: pointer;
            border-radius: var(--radius-md, 8px);
            transition: var(--transition-normal, all 0.3s ease);
            box-shadow: var(--shadow-sm, 0 2px 4px rgba(0, 0, 0, 0.05));
        }
        
        .file-upload-container:hover {
            box-shadow: var(--shadow-md, 0 4px 6px rgba(0, 0, 0, 0.1));
            border-color: var(--color-primary, #13c0e6);
        }

        .file-info {
            margin-top: 10px;
            color: var(--color-light-text, #888);
        }

        .file-info img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px auto;
            border-radius: var(--radius-md, 4px);
        }

        @media (min-width: 768px) {
            .file-info img {
                max-height: 300px;
                width: auto;
                object-fit: contain;
            }
        }

        @media (max-width: 767px) {
            .file-info img {
                max-height: 200px;
            }
        }

        .details-section {
            margin-bottom: 20px;
        }

        .details-section h5 {
            margin-bottom: 15px;
            color: var(--color-dark, #162d92);
        }

        .table-header {
            background-color: var(--color-secondary, #4687e6);
            color: white;
        }

        .empty-table-placeholder {
            display: none;
            height: 200px;
            align-items: center;
            justify-content: center;
            color: var(--color-border, #ced4da);
        }

        .empty-table-icon {
            font-size: 4rem;
        }

        .filter-icon {
            position: relative;
            top: 2px;
            pointer-events: none;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .filter-dropdown .dropdown-menu {
            min-width: 150px;
        }

        tbody:empty+.empty-table-placeholder {
            display: flex;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .btn-custom {
            font-size: 1rem;
            padding: 1rem 1.5rem;
        }

        .btn-vertical-center {
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: normal;
        }

        .card {
            box-shadow: var(--shadow-card, 0 12px 30px rgba(19, 192, 230, 0.25));
            border: none;
            border-radius: var(--radius-md, 8px);
            transition: var(--transition-normal, all 0.3s ease);
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg, 0 10px 15px rgba(0, 0, 0, 0.1));
        }

        .card-header {
            background-color: var(--color-input-bg, #ffffff);
            font-weight: bold;
            width: 100%;
            border-bottom: 2px solid var(--color-border, #eaeaea);
            color: var(--color-dark, #162d92);
        }
        
        .card-title {
            color: var(--color-primary, #13c0e6);
            font-weight: 600;
            margin-bottom: 1.25rem;
            position: relative;
        }
        
        .card-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(to right, var(--color-primary, #13c0e6), var(--color-secondary, #4687e6));
            border-radius: 3px;
        }

        details[open] summary {
            background-color: var(--color-input-bg-hover, #e9ecef);
            padding: .75rem 1.25rem;
            border-bottom: 1px solid var(--color-border, rgba(0, 0, 0, .125));
            margin-bottom: 1rem;
        }

        summary {
            cursor: pointer;
            padding: .75rem 1.25rem;
            border-bottom: 1px solid var(--color-border, rgba(0, 0, 0, .125));
            margin-bottom: 1rem;
            list-style: none;
            position: relative;
            transition: var(--transition-fast, all 0.2s ease);
            border-radius: var(--radius-sm, 4px);
            color: var(--color-dark, #162d92);
        }
        
        summary:hover {
            background-color: var(--color-input-bg-hover, #f8f9fa);
        }

        summary::after {
            content: "+";
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s ease;
            color: var(--color-primary, #13c0e6);
        }

        details[open] summary::after {
            content: "-";
        }

        summary::-webkit-details-marker {
            display: none;
        }
        
        .row.custom-spacing > * {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }
        
        /* Custom column width for 5 equal columns */
        .col-md-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
        
        /* Responsive breakpoints */
        @media (max-width: 1200px) {
            .col-md-2-4 {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }
        }
        
        @media (max-width: 992px) {
            .col-md-2-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (max-width: 768px) {
            .col-md-2-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Adjust card heights */
        .card-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: var(--radius-md, 8px);
            box-shadow: var(--shadow-card, 0 12px 30px rgba(19, 192, 230, 0.25));
            transition: var(--transition-normal, all 0.3s ease);
        }
        
        .card-container:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg, 0 15px 35px rgba(19, 192, 230, 0.35));
        }

        /* Ensure consistent spacing */
        .custom-spacing {
            margin-left: -10px;
            margin-right: -10px;
        }
        
        .custom-spacing > * {
            padding-left: 10px !important;
            padding-right: 10px !important;
            margin-bottom: 20px;
        }
        
        .inactivo {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
            text-decoration: none;
        }
        
        .activate-link {
            text-decoration: none;
        }
        
        /* Estilos para las pestañas */
        .nav-tabs {
            border-bottom: 2px solid var(--color-border, #dee2e6);
            margin-bottom: 1.5rem;
        }
        
        .nav-tabs .nav-item {
            margin-bottom: -2px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--color-text, #495057);
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            transition: var(--transition-normal, all 0.3s ease);
            position: relative;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--color-primary, #13c0e6);
            border-color: transparent;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--color-primary, #13c0e6);
            background-color: transparent;
            border-color: var(--color-primary, #13c0e6);
            font-weight: 600;
        }
        
        .nav-tabs .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, var(--color-primary, #13c0e6), var(--color-secondary, #4687e6));
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: left;
        }
        
        .nav-tabs .nav-link:hover::after,
        .nav-tabs .nav-link.active::after {
            transform: scaleX(1);
        }
        
        /* Botones con efecto de gradiente */
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary, #13c0e6) 0%, var(--color-secondary, #4687e6) 100%);
            border: none;
            color: white;
            font-weight: 500;
            box-shadow: var(--shadow-btn, 0 5px 15px rgba(70, 135, 230, 0.3));
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(70, 135, 230, 0.4);
            filter: brightness(1.05);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(108, 117, 125, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
            filter: brightness(1.05);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
            border: none;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
            filter: brightness(1.05);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            border: none;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.3);
            filter: brightness(1.05);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border: none;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(245, 158, 11, 0.3);
            filter: brightness(1.05);
        }        
        .decimal-input{
           text-align: left;
        }
    </style>
    
@endpush

@section('content')
@php
        $bandRregistro = '0';
        $registroMensaje = '';
        if (session()->exists('success_register')) {
            $bandRregistro = '1';
            $registroMensaje = session('success_register');
            session()->forget('success_register');
        } else if(session()->exists('error_f')){
             $bandRregistro = '2';
             $registroMensaje = session('error_f');
             session()->forget('error_f');
        }
        $tabActive = '';
        if (session()->exists('tabActive')) {            
            $tabActive = session('tabActive');
            session()->forget('$tabActive');
        }

    @endphp

    <div class="container-fluid">
        <input type="hidden" id="user-id" value="{{ Auth::id() }}">
        <x-page-header title="Gestión de Negocio" icon="users">
            <a href="{{ route('dashboard.team') }}" class="btn btn-light d-flex align-items-center justify-content-center gap-1">
                <i data-feather="arrow-left" class="icon-sm"></i> Volver
            </a>
        </x-page-header>
        
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                   <!-- <input type="text" id="co_application" value="" readonly>-->
                    <section class="bg-light rounded p-4 shadow-sm animate-fade-in">
                        <div class="row justify-content-start align-items-stretch custom-spacing">
                            <!-- Estado del Proyecto -->
                            <div class="col-xl-2-4 col-lg-4 col-md-6 col-12 d-flex">
                                <div class="card-container p-3 w-100">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title">Estado del Proyecto</h5>
                                        <label for="etapaActual" class="font-weight-bold">Etapa Actual:</label>
                                        <input type="text" class="form-control-plaintext" id="etapaActual" value="{{ $app->orden_actual }}. {{ $app->etapa_actual }}" readonly>
                                        @if($app->codigo_estatus_actual != 369 && $app->codigo_estatus_actual != 378 && $app->codigo_estatus_actual != 372 )
                                            <label for="etapaSiguiente" class="font-weight-bold">Siguiente Etapa:</label>
                                            <input type="text" class="form-control-plaintext" id="etapaSiguiente" value="{{ $app->siguiente_orden }}. {{ $app->siguiente_etapa }}" readonly>
                                            <br>                                            
                                            <a id="completar-etapa-link"
                                                href="{{ route('dashboard.team.update-state', ['co_aplicacion' => $app->co_aplicacion]) }}" 
                                                class="btn btn-primary w-100 activate-link">
                                                Completar la siguiente Etapa
                                            </a>
                                        @endif
                                        @if($app->metodo_de_pago)
                                            @if($app->codigo_estatus_actual != 378 && $app->codigo_estatus_actual != 372 )
                                                    <form action="{{ route('dashboard.team.financial') }}" method="POST" class="mb-3">
                                                        @csrf
                                                        <div class="form-group">
                                                        <input type="hidden" name="co_aplicacion" value="{{ $app->co_aplicacion }}">                                                
                                                        <label for="status_financial" class="font-weight-bold">Estado de Financiero:</label>
                                                        <select name="status_financial" id="status_financial" 
                                                            class="form-select @error('status_financial') is-invalid @enderror">
                                                                                                            
                                                            @foreach($status_financial as $financial)
                                                                <option value="{{ $financial->co_estatus_financiero }}" 
                                                                        {{ ($app->codigo_estatus_financiero && $financial->co_estatus_financiero == $app->codigo_estatus_financiero) || 
                                                                        (empty($app->codigo_estatus_financiero) && $financial->co_estatus_financiero == 0) ? 'selected' : '' }}>
                                                                    {{ $financial->tx_nombre }}
                                                                </option>
                                                            @endforeach
                                                            
                                                        </select>
                                                        @error('status_financial')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        </div>
                                                        <br>
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            Actualizar Estado Financiero
                                                        </button>
                                                    </form>
                                                 @else
                                                     <div class="form-group">
                                                        <label for="status_financial" class="font-weight-bold">Estado de Financiero:</label>
                                                        <input type="text" class="form-control-plaintext" id="status_financial" value="{{ $app->f_etapa_actual }}" readonly>
                                                    </div>
                                                 @endif                                           
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Información del Cliente -->
                            <div class="col-xl-2-4 col-lg-4 col-md-6 col-12 d-flex">
                                <div class="card-container p-3 w-100">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title">Información del Cliente</h5>
                                        <label for="clientePrincipal" class="font-weight-bold">Cliente Principal:</label>
                                        <input type="text" class="form-control-plaintext" id="clientePrincipal" value="{{$app->cliente_principal_nom}}" readonly>
                                        <label for="telefono" class="font-weight-bold">Teléfono:</label>
                                        <input type="text" class="form-control-plaintext" id="telefono" value="{{$app->cliente_principal_tlf}}" readonly>
                                        <label for="email" class="font-weight-bold">Email:</label>
                                        <input type="text" class="form-control-plaintext" id="email" value="{{$app->cliente_principal_email}}" readonly>
                                        <label for="direccion" class="font-weight-bold">Dirección:</label>
                                        <input type="text" class="form-control-plaintext" id="direccion" value="{{$app->cp_direc ?? '' }}" readonly>
                                        <label for="idiomaPrincipal" class="font-weight-bold">Idioma Principal:</label>
                                        <input type="text" class="form-control-plaintext" id="idiomaPrincipal" value="{{$app->cp_idioma_principal}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Cliente Secundario -->
                            <div class="col-xl-2-4 col-lg-4 col-md-6 col-12 d-flex">
                                <div class="card-container p-3 w-100">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title">Cliente Secundario</h5>
                                        <label for="clienteSecundario" class="font-weight-bold">Cliente Secundario:</label>
                                        <input type="text" class="form-control-plaintext" id="clienteSecundario" value="{{$app->cs_primer_nombre}} {{$app->cs_inicial_seg}} {{$app->cs_primer_apel}}" readonly>
                                        <label for="telefonoSecundario" class="font-weight-bold">Teléfono:</label>
                                        <input type="text" class="form-control-plaintext" id="telefonoSecundario" value="{{$app->cs_telefono}}" readonly>
                                        <label for="emailSecundario" class="font-weight-bold">Email:</label>
                                        <input type="text" class="form-control-plaintext" id="emailSecundario" value="{{$app->cs_correo}}" readonly>
                                        <label for="direccionSecundario" class="font-weight-bold">Dirección:</label>
                                        <input type="text" class="form-control-plaintext" id="direccionSecundario" value="{{ $app->hay_un_cofirmante !== false && isset($app->cs_direc) ? $app->cs_direc : '' }}" readonly>
                                        <label for="empleadorSecundario" class="font-weight-bold">Empleador:</label>
                                        <input type="text" class="form-control-plaintext" id="empleadorSecundario" value="{{$app->cs_empleador}}" readonly>
                                        <label for="cargoSecundario" class="font-weight-bold">Cargo:</label>
                                        <input type="text" class="form-control-plaintext" id="cargoSecundario" value="{{$app->cs_cargo}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Detalles del Proyecto -->
                            <div class="col-xl-2-4 col-lg-4 col-md-6 col-12 d-flex">
                                <div class="card-container p-3 w-100">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title">Detalles del Proyecto</h5>
                                        <p class="font-weight-bold mb-2">Información Financiera:</p>
                                        <label for="metodoPago" class="font-weight-bold">Método de Pago:</label>
                                        <input type="text" class="form-control-plaintext" id="metodoPago" value="{{$app->metodo_de_pago_del_proyecto}}" readonly>
                                        <label for="precioTotal" class="font-weight-bold">Precio Total del Sistema:</label>
                                        <input type="text" class="form-control-plaintext" id="precioTotal" value="{{$app->precio_total}}" readonly>
                                        <label for="downPayment" class="font-weight-bold">Down Payment:</label>
                                        <input type="text" class="form-control-plaintext" id="downPayment" value="{{$app->down_payment}}" readonly>
                                        <label for="totalFinanciado" class="font-weight-bold">Total Financiado:</label>
                                        <input type="text" class="form-control-plaintext" id="totalFinanciado" value="{{$app->total_financiado}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Información del Sistema -->
                            <div class="col-xl-2-4 col-lg-4 col-md-6 col-12 d-flex">
                                <div class="card-container p-3 w-100">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title">Detalles del Sistema</h5>
                                        <p class="font-weight-bold mb-2">Información del Sistema:</p>
                                        <label for="waterSource" class="font-weight-bold">Water Source:</label>
                                        <input type="text" class="form-control-plaintext" id="waterSource" value="{{$app->is_water_source}}" readonly>
                                        <label for="promotions" class="font-weight-bold">Promotions:</label>
                                        <input type="text" class="form-control-plaintext" id="promotions" value="{{$app->promociones_incluidas ?? ''}}" readonly>
                                        <label for="sitePhotos" class="font-weight-bold">Site Photos:</label>
                                        <input type="text" class="form-control-plaintext" id="sitePhotos" value="" readonly>
                                        <label for="pruebaAgua" class="font-weight-bold">Prueba de Agua:</label>
                                        <input type="text" class="form-control-plaintext" id="pruebaAgua" value="" readonly>
                                        <br>
                                        @if($app->codigo_estatus_actual != 369 && $app->codigo_estatus_actual != 372 && $app->codigo_estatus_actual != 378)
                                            <a href="{{ route('dashboard.team.stop', ['co_aplicacion' => $app->co_aplicacion]) }}"
                                                id="stop-app-link"
                                                class="btn btn-warning w-100 mb-2 activate-link">
                                                Poner en Espera
                                            </a>
                                            <a href="{{ route('dashboard.team.cancel', ['co_aplicacion' => $app->co_aplicacion]) }}"
                                                id="cancel-app-link"
                                                class="btn btn-danger w-100 activate-link">
                                                Cancelar Proyecto
                                            </a>
                                        @endif

                                        @if($app->codigo_estatus_actual == 378)
                                            <a href="{{ route('dashboard.team.activate', ['co_aplicacion' => $app->co_aplicacion]) }}" 
                                                id="activate-app-link"
                                                class="btn btn-success w-100 mb-2 activate-link">
                                                Activar Proyecto
                                            </a>
                                            <a href="{{ route('dashboard.team.cancel', ['co_aplicacion' => $app->co_aplicacion]) }}" 
                                                id="cancel-app-link"
                                                class="btn btn-danger w-100 activate-link">
                                                Cancelar Proyecto
                                            </a>
                                        @endif    
                                        @if($app->codigo_estatus_actual == 372)
                                            <a href="{{ route('dashboard.team.activate', ['co_aplicacion' => $app->co_aplicacion]) }}"
                                                id="activate-app-link"
                                                class="btn btn-success w-100 mb-2 activate-link">
                                                Activar Proyecto
                                            </a>
                                            <a href="{{ route('dashboard.team.stop', ['co_aplicacion' => $app->co_aplicacion]) }}"
                                                id="stop-app-link"
                                                class="btn btn-warning w-100 activate-link">
                                                Poner en Espera
                                            </a>
                                        @endif    
                                    </div>
                                </div>
                            </div>
                            {{--Informacion banco --}}
                            <div class="col-xl-2-4 col-lg-4 col-md-6 col-12 d-flex">
                                <div class="card-container p-3 w-100">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title">Información Bancaria</h5>
                                        <label for="tx_titular_cuenta" class="font-weight-bold">Titular:</label>
                                        <input type="text" class="form-control-plaintext" id="tx_titular_cuenta" value="{{$app->tx_titular_cuenta ?? ''}}" readonly>
                                        <label for="tx_nombre_banco" class="font-weight-bold">Banco:</label>
                                        <input type="text" class="form-control-plaintext" id="tx_nombre_banco" value="{{$app->tx_nombre_banco ?? ''}}" readonly>
                                        <label for="tx_numero_cuenta" class="font-weight-bold">Número de Cuenta</label>
                                        <input type="text" class="form-control-plaintext" id="tx_numero_cuenta" value="{{$app->tx_numero_cuenta ?? ''}}" readonly>
                                        <label for="tx_numero_ruta" class="font-weight-bold">Número de Ruta</label>
                                        <input type="text" class="form-control-plaintext" id="tx_numero_cuenta" value="{{$app->tx_numero_ruta ?? ''}}" readonly>
                                        <label for="co_tipo_cuenta" class="font-weight-bold">Tipo de Cuenta:</label>
                                        <input type="text" class="form-control-plaintext" id="co_tipo_cuenta" value="{{$app->tx_tipo_cuenta ?? ''}}" readonly>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <br>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="info-analista-tab" data-bs-toggle="tab" href="#info-analista"
                                role="tab" aria-controls="info-analista" aria-selected="true">
                                <i data-feather="user" class="icon-sm me-2"></i>Información del Analista
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="info-general-tab" data-bs-toggle="tab" href="#info-general"
                                role="tab" aria-controls="info-general" aria-selected="false">
                                <i data-feather="info-circle" class="icon-sm me-2"></i>Información General
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="info-financiera-tab" data-bs-toggle="tab" href="#info-financiera"
                                role="tab" aria-controls="info-financiera" aria-selected="false">
                                <i data-feather="dollar-sign" class="icon-sm me-2"></i>Información Financiera
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="instalacion-tab" data-bs-toggle="tab" href="#instalacion" role="tab"
                                aria-controls="instalacion" aria-selected="false">
                                <i data-feather="settings" class="icon-sm me-2"></i>Instalación
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="documentos-tab" data-bs-toggle="tab" href="#documentos" role="tab"
                                aria-controls="documentos" aria-selected="false">
                                <i data-feather="file-text" class="icon-sm me-2"></i>Documentos
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="mensajes-tab" data-bs-toggle="tab" href="#mensajes" role="tab"
                                aria-controls="mensajes" aria-selected="false">
                                <i data-feather="message-circle" class="icon-sm me-2"></i>Chat
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="servicio-tecnico-tab" data-bs-toggle="tab" href="#servicio-tecnico"
                                role="tab" aria-controls="servicio-tecnico" aria-selected="false">
                                <i data-feather="settings" class="icon-sm me-2"></i>Servicio Técnico
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="log-app-tab" data-bs-toggle="tab" href="#log-app"
                                role="tab" aria-controls="log-app" aria-selected="false">
                                <i data-feather="activity" class="icon-sm me-2"></i>Logs
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="info-analista" role="tabpanel"
                            aria-labelledby="info-analista-tab">

                            <div class="row mt-4 animate-fade-in">
                                <div class="col-md-6">
                                    <div class="card p-4">
                                        <div class="card-body">
                                            <h5 class="card-title">Analista Principal</h5>
                                            
                                            <div class="d-flex flex-column gap-2">
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Nombre:</div>
                                                    <div class="col-md-8">{{$app->ap_nombre ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Fenix ID:</div>
                                                    <div class="col-md-8">{{$app->ap_id ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Teléfono:</div>
                                                    <div class="col-md-8">{{$app->ap_telefono ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Correo Electrónico:</div>
                                                    <div class="col-md-8">{{$app->ap_email ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Gerente de Oficina:</div>
                                                    <div class="col-md-8">{{$app->ap_gerente ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Teléfono de Gerente:</div>
                                                    <div class="col-md-8">{{$app->ap_gerente_tlf ?? 'No definido'}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card p-4">
                                        <div class="card-body">
                                            <h5 class="card-title">Analista Secundario</h5>

                                            <div class="d-flex flex-column gap-2">
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Nombre:</div>
                                                    <div class="col-md-8">{{$app->as_nombre ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Fenix ID:</div>
                                                    <div class="col-md-8">{{$app->as_id ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Teléfono:</div>
                                                    <div class="col-md-8">{{$app->as_telefono ?? 'No definido'}}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Correo Electrónico:</div>
                                                    <div class="col-md-8">{{$app->as_email ?? 'No definido'}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="info-general" role="tabpanel" aria-labelledby="info-general-tab">
                            <br>
                            <div class="container mt-2 animate-fade-in">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <details open>
                                            <summary class="card-header">Detalles del Pago</summary>
                                            <div class="row p-3">
                                                <div class="col-md-6 p-3">
                                                    <label for="metodoPago" class="font-weight-bold">Método de Pago:</label> 
                                                    <input type="text" class="form-control-plaintext" id="metodoPago" value="{{$app->metodo_de_pago ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="downPayment" class="font-weight-bold">Down Payment:</label> 
                                                    <input type="text" class="form-control-plaintext" id="downPayment" value="{{$app->down_payment ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="precioTotal" class="font-weight-bold">Precio Total del Sistema:</label> 
                                                    <input type="text" class="form-control-plaintext" id="precioTotal" value="{{$app->precio_total_del_sistema ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="importeFinanciado" class="font-weight-bold">Importe Total Financiado:</label> 
                                                    <input type="text" class="form-control-plaintext" id="importeFinanciado" value="{{$app->importe_total_financiado ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="adultos" class="font-weight-bold">Adultos en casa:</label> 
                                                    <input type="text" class="form-control-plaintext" id="adultos" value="{{$app->adultos_en_casa ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="ninos" class="font-weight-bold">Niños en casa:</label> 
                                                    <input type="text" class="form-control-plaintext" id="ninos" value="{{$app->ninos_en_casa ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="promociones" class="font-weight-bold">Promociones Incluidas:</label> 
                                                    <input type="text" class="form-control-plaintext" id="promociones" value="{{$app->promociones_incluidas ?? 'No definido'}}" readonly>
                                                </div>

                                            </div>
                                        </details>

                                        <details open>
                                            <summary class="card-header">Cliente Principal</summary>
                                            <div class="row p-3">
                                                <div class="col-md-6 p-3">
                                                    <label for="idioma" class="font-weight-bold">Idioma Principal:</label> 
                                                    <input type="text" class="form-control-plaintext" id="idioma" value="{{$app->cp_idioma_principal ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="primerNombre" class="font-weight-bold">Primer Nombre:</label> 
                                                    <input type="text" class="form-control-plaintext" id="primerNombre" value="{{$app->cp_primer_nombre ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="telefono" class="font-weight-bold">Teléfono:</label> 
                                                    <input type="text" class="form-control-plaintext" id="telefono" value="{{$app->cp_telefono ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="direccion" class="font-weight-bold">Dirección:</label> 
                                                    <input type="text" class="form-control-plaintext" id="direccion" value="{{$app->cp_direc ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="empleador" class="font-weight-bold">Empleador:</label> 
                                                    <input type="text" class="form-control-plaintext" id="empleador" value="{{$app->cp_empleador ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="telefonoTrabajo" class="font-weight-bold">Telefono del Trabajo</label>
                                                    <input type="text" class="form-control-plaintext" id="telefonoTrabajo" value="{{$app->cp_tlf_trabajo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="puesto" class="font-weight-bold">Puesto:</label> 
                                                    <input type="text" class="form-control-plaintext" id="puesto" value="{{$app->cp_cargo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="direccionTrabajo" class="font-weight-bold">Dirección de Trabajo:</label> 
                                                    <input type="text" class="form-control-plaintext" id="direccionTrabajo" value="{{$app->cp_dir_trabajo ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="apellido" class="font-weight-bold">Apellido:</label> 
                                                    <input type="text" class="form-control-plaintext" id="apellido" value="{{$app->cp_primer_apel ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="correo" class="font-weight-bold">Correo Electrónico:</label> 
                                                    <input type="text" class="form-control-plaintext" id="correo" value="{{$app->cp_correo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="aniosEmpleados" class="font-weight-bold">Años empleados:</label> 
                                                    <input type="text" class="form-control-plaintext" id="aniosEmpleados" value="{{$app->cp_tiempo_trabajo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="ingresoAlternativo" class="font-weight-bold">Ingreso Alternativo:</label> 
                                                    <input type="text" class="form-control-plaintext" id="ingresoAlternativo" value="{{$app->cp_ingreso_alterno ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="salario" class="font-weight-bold">Salario Mensual:</label> 
                                                    <input type="text" class="form-control-plaintext" id="salario" value="{{$app->cp_ingreso_principal ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cofirmante" class="font-weight-bold">¿Hay un co-firmante?:</label> 
                                                    <input type="text" class="form-control-plaintext" id="cofirmante" value="{{$app->hay_un_cofirmante ? 'Sí' : 'No' }}" readonly>
                                                </div>
                                            </div>
                                        </details>
                                        <details open>
                                            <summary class="card-header">Cliente Secundario</summary>
                                            <div class="row p-3">
                                                <div class="col-md-6 p-3">
                                                    <label for="cs_nombre" class="font-weight-bold">Nombre:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_nombre" value="{{$app->cs_primer_nombre ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cs_telefono" class="font-weight-bold">Teléfono:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_telefono" value="{{$app->cs_telefono ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cs_direccion" class="font-weight-bold">Dirección:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_direccion" value="{{ $app->hay_un_cofirmante !== false && isset($app->cs_direc) ? $app->cs_direc : 'No definido' }}" readonly>
                                                    
                                                    <label for="cs_empleador" class="font-weight-bold">Empleador:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_empleador" value="{{$app->cs_empleador ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="cs_apellido" class="font-weight-bold">Apellido:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_apellido" value="{{$app->cs_primer_apel ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cs_correo" class="font-weight-bold">Correo Electrónico:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_correo" value="{{$app->cs_correo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cs_cargo" class="font-weight-bold">Cargo:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_cargo" value="{{$app->cs_cargo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cs_telefono_trabajo" class="font-weight-bold">Teléfono del Trabajo:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_telefono_trabajo" value="{{$app->cs_tlf_trabajo ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="cs_direccion_trabajo" class="font-weight-bold">Dirección de Trabajo:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_direccion_trabajo" value="{{$app->cs_dir_trabajo ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="cs_ingreso_alterno" class="font-weight-bold">Ingreso Alternativo:</label>
                                                    <input type="text" class="form-control-plaintext" id="cs_ingreso_alterno" value="{{$app->cs_ingreso_alterno ?? 'No definido'}}" readonly>
                                                </div>
                                            </div>
                                        </details>
                                        <details open>
                                            <summary class="card-header">Información de la Hipoteca</summary>
                                            <div class="row p-3">
                                                <div class="col-md-6 p-3">
                                                    <label for="estadoHipoteca" class="font-weight-bold">Estado de la Hipoteca:</label> 
                                                    <input type="text" class="form-control-plaintext" id="estadoHipoteca" value="{{$app->estatus_hipoteca ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="companiaHipotecaria" class="font-weight-bold">Compañía Hipotecaria:</label> 
                                                    <input type="text" class="form-control-plaintext" id="companiaHipotecaria" value="{{$app->company_hipoteca ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="pagoHipoteca" class="font-weight-bold">Pago de Hipoteca o Alquiler:</label> 
                                                    <input type="text" class="form-control-plaintext" id="pagoHipoteca" value="{{$app->renta_hipoteca ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <label for="tiempoAqui" class="font-weight-bold">¿Cuánto tiempo aquí?:</label> 
                                                    <input type="text" class="form-control-plaintext" id="tiempoAqui" value="{{$app->tiempo_hipoteca ?? 'No definido'}}" readonly>
                                                </div>
                                            </div>
                                        </details>

                                        <details open>
                                            <summary class="card-header">Referencias</summary>
                                            <div class="row p-3">
                                                <div class="col-md-6 p-3">
                                                    <h4 class="mb-3">Referencia 1</h4>
                                                    <label for="nombreRef1" class="font-weight-bold">Nombre:</label> 
                                                    <input type="text" class="form-control-plaintext" id="nombreRef1" value="{{$app->nombre_ref1 ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="relacionRef1" class="font-weight-bold">Relación con el Cliente:</label> 
                                                    <input type="text" class="form-control-plaintext" id="relacionRef1" value="{{$app->relacion_ref1 ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="telefonoRef1" class="font-weight-bold">Teléfono:</label> 
                                                    <input type="text" class="form-control-plaintext" id="telefonoRef1" value="{{$app->telefono_ref1 ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <h4 class="mb-3">Referencia 2</h4>
                                                    <label for="nombreRef2" class="font-weight-bold">Nombre:</label> 
                                                    <input type="text" class="form-control-plaintext" id="nombreRef2" value="{{$app->nombre_ref2 ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="relacionRef2" class="font-weight-bold">Relación con el Cliente:</label> 
                                                    <input type="text" class="form-control-plaintext" id="relacionRef2" value="{{$app->relacion_ref2 ?? 'No definido'}}" readonly>
                                                    
                                                    <label for="telefonoRef2" class="font-weight-bold">Teléfono:</label> 
                                                    <input type="text" class="form-control-plaintext" id="telefonoRef2" value="{{$app->telefono_ref2 ?? 'No definido'}}" readonly>
                                                </div>
                                            </div>
                                        </details>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="info-financiera" role="tabpanel"
                            aria-labelledby="info-financiera-tab">
                            <br>
                            <div class="container animate-fade-in">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Información Financiera</h5>
                                    </div>
                                    <div class="card-body">
                                        {{--{{ route('dashboard.team.updatefinanciera', ['co_aplicacion' => $app->co_aplicacion]) }}--}}
                                        <form action="{{ route('dashboard.team.financial-info', ['co_aplicacion' => $app->co_aplicacion]) }}" 
                                            method="post"
                                            id="form-info-financiera">
                                            @csrf
                                            @method('PUT')
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="co_financiera" class="form-label">Nombre de la Financiera</label>
                                                        <select id="co_financiera" 
                                                            name="co_financiera" 
                                                            class="form-select">
                                                            <option value="">Selecciona una financiera</option>
                                                                                                                        
                                                            @foreach($financieras as $financiera)
                                                                <option value="{{ $financiera->co_financiera }}"
                                                                    {{ $financiera->co_financiera == $app->co_financiera ? 'selected' : '' }}>
                                                                    {{ $financiera->tx_nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{--</div>--}}
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="co_tipo_financiamiento" class="form-label">Tipo de Financiamiento</label>
                                                        <select id="co_tipo_financiamiento" 
                                                            name="co_tipo_financiamiento" 
                                                            class="form-select">
                                                            <option value="">Selecciona un tipo de financiamiento</option>
                                                                @foreach($tipos_financiamiento as $financiamiento)
                                                                    <option value="{{ $financiamiento->co_tipo_financiamiento }}"
                                                                        {{ $financiamiento->co_tipo_financiamiento == $app->co_tipo_financiamiento ? 'selected' : '' }}>
                                                                        {{ $financiamiento->tx_nombre }}
                                                                    </option>
                                                                @endforeach                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <label for="tx_rango" class="form-label">Rango</label>
                                                            <input type="text" id="tx_rango" name="tx_rango" class="form-control" value="{{ $app->tx_rango ?? '' }}">
                                                        </div>
                                                </div> 
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="tx_meses" class="form-label">Meses de Financiamiento</label>
                                                        <input type="text" id="tx_meses" name="tx_meses" class="form-control" value="{{ $app->tx_meses ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="nu_porcentajeap" class="form-label">Porcentaje de Aprobación(%)</label>
                                                        <input type="text" id="nu_porcentajeap" 
                                                        name="nu_porcentajeap"
                                                        class="form-control decimal-input" 
                                                        value="{{ $app->nu_porcentajeap ?? '0.00' }}">
                                                    </div>
                                                </div>                                                                                   
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="nu_tasa_interes" class="form-label">Tasa de Interés (%)</label>
                                                        <input type="text" id="nu_tasa_interes" 
                                                            name="nu_tasa_interes" 
                                                            class="form-control decimal-input" value="{{ $app->nu_tasa_interes ?? '0.00' }}">
                                                    </div>
                                                </div>                                                
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="nu_pago_mensual" class="form-label">Pago Mensual ($)</label>
                                                        <input type="text" id="nu_pago_mensual" name="nu_pago_mensual" class="form-control decimal-input" value="{{ $app->nu_pago_mensual ?? '0.00' }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <label for="nu_monto_financiado" class="form-label">Monto Financiado ($)</label>
                                                        <input type="text" id="nu_monto_financiado" 
                                                            name="nu_monto_financiado" 
                                                            class="form-control decimal-input" 
                                                            value="{{ $app->total_financiado ? $app->total_financiado :'0.00' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <button type="button" id="btn_guardar_financiera" class="btn btn-primary">Guardar</button>
                                                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="instalacion" role="tabpanel" aria-labelledby="instalacion-tab">
                            <br>
                            <div class="container bg-white rounded shadow-sm animate-fade-in">
                                <div class="details-container p-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Detalles de Instalación</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @php
                                                        use Carbon\Carbon;
                                                        if($app->fecha_estimada){
                                                            $app->fecha_estimada = Carbon::parse($app->fecha_estimada)->format('m/d/Y');
                                                        }
                                                @endphp
                                                <div class="col-md-3">
                                                    <label for="fechaInstalacion" class="font-weight-bold">Fecha Estimada de Instalación:</label>
                                                    <input type="text" class="form-control-plaintext" 
                                                    id="fechaInstalacion"                                            
                                                    value="{{$app->fecha_estimada ?? 'No definido'}}" 
                                                    readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="tiempoInstalacion" class="font-weight-bold">Tiempo Estimado de Instalación:</label>
                                                    <input type="text" class="form-control-plaintext" 
                                                    id="tiempoInstalacion"
                                                    value="{{$app->tiempo_estimado ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="tipoAgua" class="font-weight-bold">Tipo de Agua:</label>
                                                    <input type="text" class="form-control-plaintext"
                                                     id="tipoAgua" 
                                                     value="{{$app->is_water_source ?? 'No definido'}}" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="osmosisInversa" class="font-weight-bold">Solo Instalando ósmosis inversa:</label>
                                                    <input type="text" class="form-control-plaintext" id="osmosisInversa" value="{{ isset($app->solo_osmosis) ? ($app->solo_osmosis ? 'Sí' : 'No') : 'No definido' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
                            <br>
                            <div class="container animate-fade-in">

                                <div class="row">
                                    <div class="col-md-6 details-section">
                                        <h5 class="mb-4">Pre-instalación</h5>
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Pre-Calificación / Orden de Trabajo</p>
                                            <span class="file-info">
                                                @if($app->orden_de_trabajo) 
                                                    <a href="{{ Storage::url($app->orden_de_trabajo) }}" download class="text-primary">{{ basename($app->orden_de_trabajo) }}</a>                                                
                                                @else
                                                    <span class="text-muted">No hay archivo cargado...</span>
                                                @endif    
                                            </span>
                                            <br>
                                            @if($app->orden_de_trabajo) 
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                                    <a href="{{ route('dashboard.team.workorder', ['co_aplicacion' => $app->co_aplicacion]) }}" 
                                                        class="btn btn-primary">
                                                        <i data-feather="refresh-cw" class="me-1"></i>
                                                        Regenerar
                                                    </a>
                                                </div>
                                             @endif
                                             <br>
                                        </div>
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">ID del Cliente Principal</p>
                                            <span class="file-info">
                                                <div>
                                                    @if($app->fotoid_cliente1)
                                                        <a href="{{ Storage::url($app->fotoid_cliente1) }}" download class="text-primary">Identification Card</a>
                                                    @else
                                                        <span class="text-muted">No hay archivo cargado...</span>
                                                    @endif    
                                                </div>
                                                <div>
                                                    @if($app->fotoid_cliente1) 
                                                        <img aria-expanded="false" src="{{ Storage::url($app->fotoid_cliente1) }}" class="mt-3 img-thumbnail">                                                            
                                                    @endif
                                                </div>    
                                            </span>
                                            <br>
                                            @if($app->fotoid_cliente1)
                                                <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @endif
                                        </div>

                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">ID del Cliente Secundario</p>
                                            <span class="file-info">
                                                <div>
                                                    @if($app->fotoid_cliente2) 
                                                    <a href="{{ Storage::url($app->fotoid_cliente2) }}" download class="text-primary">Identification Card</a>
                                                    @else
                                                        <span class="text-muted">No hay archivo cargado...</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($app->fotoid_cliente2) 
                                                        <img aria-expanded="false" src="{{ Storage::url($app->fotoid_cliente2) }}" class="mt-3 img-thumbnail">                                                         
                                                    @endif                     
                                                </div>                  
                                            </span>
                                            <br>
                                            @if($app->fotoid_cliente2)
                                                <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @endif
                                        </div>

                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Grabación de Bienvenida</p>
                                            <p class="text-muted">No hay archivo cargado...</p>
                                            <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                        </div>


                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Fotos de la Ubicación de la Instalación</p>
                                            <p class="text-muted">No hay archivo cargado...</p>
                                            <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 details-section">
                                        <h5 class="mb-4">Post-instalación</h5>
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Contrato de Aquafeel</p>
                                            @if($app->contrato) 
                                                <a href="{{ Storage::url($app->contrato) }}" download class="text-primary">Contrato</a>
                                            @else
                                                <span class="text-muted">No hay archivo cargado...</span>
                                            @endif
                                            
                                                <form action="{{ route('document.storecontract', ['co_aplicacion' => $app->co_aplicacion]) }}" 
                                                method="post" enctype="multipart/form-data" id="form_upload_contract">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <input type="file" class="form-control" id="tx_url_img_contract" name="tx_url_img_contract" accept=".jpg, .jpeg, .png, .gif, .pdf">
                                                        <div class="d-flex justify-content-start mt-1">
                                                            <label for="tx_url_img_contract" class="text-muted small">Archivos Permitidos: jpg, jpeg, png, gif, pdf</label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                                        <div>
                                                            <i data-feather="check-circle" class="text-success me-1"></i>
                                                            <i data-feather="clock" class="text-secondary"></i>
                                                        </div>
                                                        <div>
                                                            <button type="button" id="btn_upload_contract" class="btn btn-primary me-2">Enviar</button>
                                                            <button type="reset" class="btn btn-secondary">Limpiar Selección</button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="form-control" id="tx_url_img_contract_backup" name="tx_url_img_contract_backup" value="{{ $app->contrato ?? '' }}">
                                                </form>                                                
                                        </div>

                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Resultados de la Prueba de Calidad del Agua</p>
                                            <p class="text-muted">No hay archivo cargado...</p>
                                            <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                        </div>
                                    </div>
                                </div>


                                <h5 class="mb-4">Comprobantes</h5>
                                <div class="row details-section">
                                    <div class="col-md-4">
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Comprobante de Propiedad</p>
                                            <p>
                                                @if($app->comprobante_propiedad) 
                                                <a href="{{ Storage::url($app->comprobante_propiedad) }}" download class="text-primary">Comprobante de Propiedad</a>                                                
                                                @else
                                                    <span class="text-muted">No hay archivo cargado...</span>
                                                @endif

                                            </p>
                                            @if($app->comprobante_propiedad)
                                                <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @else
                                                <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Cheque Anulado</p>
                                            <p>
                                                @if($app->comprobante_cheque_nulo) 
                                                <a href="{{ Storage::url($app->comprobante_cheque_nulo) }}" download class="text-primary">Cheque Anulado</a>                                                
                                                @else
                                                    <span class="text-muted">No hay archivo cargado...</span>
                                                @endif
                                            </p>
                                            @if($app->comprobante_cheque_nulo)
                                                <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @else
                                                <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Declaración de Impuestos</p>
                                            <p>
                                                @if($app->comprobante_impuesto) 
                                                <a href="{{ Storage::url($app->comprobante_impuesto) }}" download class="text-primary">Declaración de Impuestos</a>                                                
                                                @else
                                                    <span class="text-muted">No hay archivo cargado...</span>
                                                @endif
                                            </p>
                                            @if($app->comprobante_impuesto)
                                                <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @else
                                                <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @endif
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row details-section">
                                    <div class="col-md-4">
                                        <div class="file-upload-container mb-4 hover-scale">
                                            <p class="fw-bold mb-2">Otros Documentos</p>
                                            <p>
                                                @if($app->comprobante_otro_documento) 
                                                <a href="{{ Storage::url($app->comprobante_otro_documento) }}" download class="text-primary">Otros Documentos</a>                                                
                                                @else
                                                    <span class="text-muted">No hay archivo cargado...</span>
                                                @endif
                                            </p>
                                            @if($app->comprobante_otro_documento)
                                                <span><i data-feather="check-circle" class="text-success me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @else
                                                <span><i data-feather="alert-circle" class="text-warning me-1"></i> <i data-feather="clock" class="text-secondary"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
                        </div>
                        <div class="tab-pane fade" id="mensajes" role="tabpanel" aria-labelledby="mensajes-tab">
                            <br>                            
                            <div id="chat-wrapper" class="col-12 align-middle justify-content-center d-flex flex-wrap animate-fade-in">
                                @include('components.chat', ['applicationId' => $app->co_aplicacion])
                            </div>                                
                        </div>
                        <div class="tab-pane fade" id="servicio-tecnico" role="tabpanel"
                            aria-labelledby="servicio-tecnico-tab">
                            <br>
                            <div class="container mt-4 animate-fade-in">
                                <div class="row">
                                    <div class="col-md-2">
                                        <a href="#"
                                            class="btn btn-primary rounded-pill border btn-lg btn-vertical-center w-100">
                                            <i data-feather="package" class="me-2"></i> Crear Orden de <br>Servicio
                                        </a>
                                    </div>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <h2 class="mb-0">Servicio Técnico</h2>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Historial de Servicios</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="alert alert-info d-flex align-items-center">
                                                    <i data-feather="info-circle" class="me-3"></i>
                                                    <div>No hay servicios técnicos registrados para este cliente.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="log-app" role="tabpanel"
                            aria-labelledby="log-app-tab">
                            <br>
                            <div class="container mt-4 animate-fade-in">
                                <div class="row">
                                    <div class="col-md-10 mx-auto">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Historial de Actividades</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th class="col-9">Información</th>
                                                                <th class="col-3">Fecha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($logs ?? [] as $log)
                                                                <tr>
                                                                    <td>{{ $log->tx_accion }}</td>
                                                                    <td class="text-center">{{ $log->fe_registro }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="2" class="text-center py-4">No hay registros disponibles</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
        </br>
        {{--contiene la url de la imagen de perfil de usuario --}}
        <input type="hidden" id="chat-profile-url" value="{{ asset('img/profile/no.png') }}">
    </div>
@endsection

@push('scripts')

    <script>
        // Activar los íconos Feather
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        const bandRregistro = "{{ $bandRregistro }}";
        const registroMensaje = "{{ $registroMensaje }}";
        const tabActive = "{{ $tabActive }}";
        window.onload = function() {
            if (bandRregistro == "1") {
                Swal.fire({
                    title: "¡Éxito!",
                    text: registroMensaje,
                    icon: "success",
                    didClose: () => {
                        if(tabActive != ''){
                            const documentsTab = document.getElementById(tabActive);
                            if (documentsTab) {
                                documentsTab.click();
                                documentsTab.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                            if(tabActive == "documentos-tab"){
                                const firstInputInTab = document.getElementById('tx_url_img_contract');                                
                                firstInputInTab.focus();
                            }else if(tabActive == "info-financiera-tab"){
                                const firstInputInTab = document.getElementById('co_financiera');                                
                                firstInputInTab.focus();
                            }
                        }                        
                    }
                });
                
            } else {
                if (bandRregistro == "2") {
                    Swal.fire({
                        icon: "info",
                        title: "Información",
                        text: registroMensaje
                    });
                }
            }
        };
        
        $(document).ready(function() {
            // Añadir clases de animación a los elementos al cargar la página
            setTimeout(function() {
                $('.card, .card-container').addClass('animate-fade-in');
            }, 100);
            
            // Manejar los enlaces de activación para evitar doble clic
            $('a.activate-link').on('click', function(e) {
                const $link = $(this);
                if (!$link.data('clicked')) {
                    $link.data('clicked', true)
                    .addClass('inactivo');
                    return true;
                }
                return false;
            });
            
            // Manejar botones de submit para evitar doble envío
            $('button[type="submit"]').on('click', function(e) {
                const $button = $(this);
                if (!$button.data('clicked')) {
                    $button.data('clicked', true)
                    .addClass('inactivo');
                return true;
                }
                return false;
            });            
        });
    </script>
    <script src="{{ asset('js/chat.js') }}"></script>
    <script>
    
    let chatInstance = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        const applicationId = '{{ $app->co_aplicacion }}';
        const token = '{{ csrf_token() }}';

        // Obtener la pestaña por su ID correcto
        const mensajesTab = document.querySelector('[href="#mensajes"]');
        
        // Verificar si se accede desde una notificación de chat
        const urlParams = new URLSearchParams(window.location.search);
        const fromNotification = urlParams.has('from_notification');
        
        // Función para inicializar el chat
        function initializeChat() {
            if (!chatInstance) {
                try {
                    if (window.ChatManager) {
                        const chatContainer = document.querySelector('#chat-wrapper .chat-container');
                        if (chatContainer) {
                            chatContainer.id = `chat-${applicationId}`;
                            chatInstance = new ChatManager(applicationId, token);                            
                        } else {
                            console.error('No se encontró el contenedor del chat');
                        }
                    } else {
                        console.error('ChatManager no está disponible');
                    }
                } catch (error) {
                    console.error('Error al inicializar el chat:', error);
                }
            }
        }
        
        // Si viene desde una notificación, activar automáticamente la pestaña de mensajes
        if (fromNotification && mensajesTab) {
            // Activar la pestaña de mensajes
            const tabTrigger = new bootstrap.Tab(mensajesTab);
            tabTrigger.show();
            
            // Inicializar el chat después de un pequeño retraso para asegurar que el DOM esté listo
            setTimeout(function() {
                initializeChat();
            }, 500);
        }
        
        if (mensajesTab) {
            mensajesTab.addEventListener('shown.bs.tab', function (e) {
                initializeChat();
            });

            // Evento para cuando se cambia a otra pestaña
            mensajesTab.addEventListener('hidden.bs.tab', function (e) {
                if (chatInstance) {
                    chatInstance.destroy().then(() => {
                        chatInstance = null;                       
                    }).catch(error => {
                        console.error('Error al finalizar el chat:', error);
                    });
                }
            });
        } else {
            console.error('No se encontró el elemento de la pestaña de mensajes');
        }

        // Limpiar al salir de la página
        window.addEventListener('beforeunload', function() {
            if (chatInstance) {
                chatInstance.cleanup();
                chatInstance = null;
            }
        });
        
        const btnUploadContract = document.getElementById('btn_upload_contract');
        
        btnUploadContract.addEventListener('click', function(e) {
            
            e.preventDefault();
            const formContract = document.getElementById('form_upload_contract');
            const fileInput = document.getElementById('tx_url_img_contract');
            const backupInput = document.getElementById('tx_url_img_contract_backup');
            
            if (fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'Debe propocionar el contrato para agregarlo a la aplicación'
                });
                return false;
            }
            
            // Si todo está bien, enviar el formulario        
            formContract.submit();
        });
        
    });
</script>
<script src="{{ asset('js/decimalInput.js') }}"></script>
<script>    
    $('.decimal-input').each(function() {
        new DecimalInput(this);
    });
    $(document).ready(function() {
        const $selectFinanciera = $('#co_financiera');

        const btnGuardarFinanciera = $('#btn_guardar_financiera');        
        const formFinanciera = $('#form-info-financiera');
        $selectFinanciera.after('<div class="invalid-feedback d-block" id="error-financiera"></div>');
        
        btnGuardarFinanciera.on('click', function(e) {
            e.preventDefault();

            // Remover mensajes de error previos
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');

            let hasError = false;

            // Validar financiera
            if (!$selectFinanciera.val()) {
                $selectFinanciera.addClass('is-invalid');
                $selectFinanciera.after('<div class="invalid-feedback d-block" id="error-financiera">Debe seleccionar una aseguradora financiera</div>');
                hasError = true;
            }
        
            if (hasError) {
                $('.is-invalid').first().focus();
                return false;
            }
            
            formFinanciera.submit();
        });
        
        // Remover clase is-invalid cuando cambia el select de financiera
        $selectFinanciera.on('change', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
                $('#error-financiera').remove();
            }
        });
        
    });
</script>
@endpush


