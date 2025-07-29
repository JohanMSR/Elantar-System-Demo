@extends('layouts.master')

@section('title')
@lang('translation.projects_title') - @lang('translation.business-center')
@endsection

@push('css')
  {{--  <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btnRango {
            background-color: #318ce7;
            margin-bottom: 10px !important;
        }

        .campo-date {
            width: 150px !important;
        }

        .tabla-informe {
            display: block;
            overflow: auto;
            width: 300px;
        }

        .margin-input {
            margin-bottom: 10px !important;
        }

        .margin-select option:hover {
            background-color: blue;
        }

        .small-label {
            font-size: 0.8em;
            background-color: #f0f0f0;
            padding: 2px 4px;
            border-radius: 4px;
            display: inline-block;
        }

        /* Fix for pagination arrows */
        .pagination-links svg {
            width: 20px;
            height: 20px;
            max-width: 20px;
            max-height: 20px;
        }
        
        .pagination .page-link {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }

        /* Estilos responsivos para móvil */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 0.5rem;
            }

            .row {
                margin: 0;
            }

            .col-12 {
                padding: 0.5rem;
            }

            .form-control, .form-select {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }

            .campo-date {
                width: 100% !important;
            }

            .btnRango {
                width: 100%;
                margin-bottom: 0.5rem !important;
            }

            .advanced-search-btn {
                width: 100%;
                padding: 0.6rem 1rem;
            }

            .table-responsive {
                margin: 0;
                padding: 0;
            }

            .table {
                font-size: 0.875rem;
            }

            .table td, .table th {
                padding: 0.5rem;
            }

            .d-flex.justify-content-center.gap-3 {
                flex-direction: column;
                gap: 0.5rem !important;
            }

            .pagination-container {
                flex-direction: column;
                align-items: center;
            }

            .pagination-info {
                margin-bottom: 1rem;
                text-align: center;
            }

            .pagination-links {
                justify-content: center;
            }

            #principal-head {
                font-size: 1.25rem;
                text-align: center;
                margin-bottom: 1rem;
            }

            .scheduler-border {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .scheduler-border legend {
                font-size: 0.875rem;
                padding: 0 0.5rem;
            }

            .row.row-col-md-auto {
                flex-direction: column;
            }

            .col-md-auto {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .form-label {
                margin-bottom: 0.25rem;
            }
            
            /* Fix para el botón de búsqueda en responsive */
            .search-header-container .input-group .btn {
                min-width: 44px;
                height: 38px;
            }

            .col-12.d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                align-items: stretch !important;
            }
            
            .advanced-search-btn, .ventas-totales-compact {
                width: 100%;
                margin-bottom: 0.75rem;
                justify-content: center;
            }
            
            .advanced-search-btn {
                margin-bottom: 1rem;
            }
        }

        /* Ajustes para pantallas muy pequeñas */
        @media (max-width: 576px) {
            .table td, .table th {
                padding: 0.25rem;
                font-size: 0.75rem;
            }

            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            .pagination .page-link {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }
            
            /* Fix para el botón de búsqueda en pantallas muy pequeñas */
            .search-header-container .input-group {
                flex-wrap: nowrap;
            }
            
            .search-header-container .input-group .form-control,
            .search-header-container .input-group .btn {
                height: 38px;
                font-size: 0.875rem;
            }
        }

        /* Estilos para el buscador en el header */
        .search-header-container {
            width: 100%;
            max-width: 450px;
        }
        
        .search-header-container .input-group .form-control {
            background-color: white;
            color: #333;
            border: 1px solid #fff;
            border-radius: 30px 0 0 30px;
            height: 38px;
        }
        
        .search-header-container .input-group .btn-outline-light {
            background-color: white;
            color: var(--color-primary);
            border-color: #fff;
        }
        
        .search-header-container .input-group .btn {
            background-color: white;
            color: var(--color-primary);
            border-color: #fff;
            height: 38px;
        }
        
        .search-header-container .input-group .btn:first-of-type {
            border-radius: 0;
        }
        
        .search-header-container .input-group .btn:last-of-type {
            border-radius: 0 30px 30px 0;
        }
        
        #principal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        
        /* Ajuste responsive para el buscador */
        @media (max-width: 992px) {
            .search-header-container {
                width: 100%;
                margin-top: 15px;
                order: 2;
            }
            
            #principal-head {
                flex-direction: column;
            }
        }
        
        .search-input-container {
            position: relative;
        }
        
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #3B82F6;
        }
        
        .project-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .project-header {
            background: linear-gradient(90deg, #4F94FC, #3B82F6);
            color: white;
            padding: 15px 20px;
            font-weight: 500;
        }
        
        .project-body {
            padding: 20px;
        }
        
        .total-sales {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .total-amount {
            font-size: 1.8rem;
            font-weight: 400;
            color: #3B82F6;
        }
        
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .date-input {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 12px;
        }
        
        .filter-select {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 12px;
        }
        
        .filter-btn {
            background-color: #3B82F6;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .filter-btn:hover {
            background-color: #2563eb;
        }
        
        .download-btn {
            background-color: #10B981;
            color: white;
        }
        
        .download-btn:hover {
            background-color: #059669;
        }
        
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .table thead {
            background-color: #3B82F6;
            color: white;
        }
        
        .table th {
            padding: 12px 15px;
            text-align: center;
            font-weight: 500;
        }
        
        .table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .action-icon {
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .action-icon:hover {
            color: #3B82F6;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination .page-link {
            padding: 8px 12px;
            margin: 0 5px;
            border-radius: 5px;
            color: #3B82F6;
            background-color: white;
            border: 1px solid #e2e8f0;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #3B82F6;
            color: white;
            border-color: #3B82F6;
        }
        
        .status-completed {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .status-next {
            background-color: #FEF3C7;
            color: #92400E;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        /* Estilos para el botón de búsqueda avanzada */
        .advanced-search-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 46px;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: #fff;
            border: none;
            border-radius: var(--radius-md, 8px);
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            margin-bottom: 0.5rem;
        }

        .advanced-search-btn:hover {
            filter: brightness(1.05);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: #fff;
        }

        .advanced-search-btn i {
            margin-right: 0.5rem;
        }

        .ventas-totales-highlight {
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 5px 15px;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
        }
        
        /* Estilos para modales (como en shop.blade.php) */
        .modal-content {
            border-radius: var(--radius-md, 8px);
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: white;
            border-radius: var(--radius-md, 8px) var(--radius-md, 8px) 0 0;
            padding: 1rem 1.5rem;
        }

        .modal-header .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            color: white;
        }

        .modal-header .modal-title svg {
            margin-right: 0.75rem;
            color: white;
            stroke: white;
        }

        .modal-header .btn-close {
            color: white;
            background-color: transparent;
            opacity: 0.8;
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #444;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 1rem 1.5rem;
        }
        
        /* Estilos para el botón modal */
        .modal-btn {
            display: inline-block;
            width: auto;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: #fff;
            border: none;
            border-radius: var(--radius-md, 8px);
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .modal-btn:hover {
            filter: brightness(1.05);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: #fff;
        }

        /* Estilos para el display de ventas totales compacto */
        .ventas-totales-compact {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 46px;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: #fff;
            border-radius: var(--radius-md, 8px);
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            margin-bottom: 0.5rem;
        }
        
        .ventas-totales-compact .ventas-label {
            margin-right: 10px;
            font-weight: 500;
            color: #fff;
        }
        
        .ventas-totales-compact .total-amount {
            font-weight: 700;
            font-size: 1.15rem;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .bell{
            cursor: pointer;
        }
    </style>    
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')

    @php
        use Carbon\Carbon;
        $bandRregistro = '0';
        $registroMensaje = '';
        if (session()->exists('success_register')) {
            $bandRregistro = '1';
            $registroMensaje = session('success_register');
        } else {
            if (session()->exists('error')) {
                $bandRregistro = '2';
                $registroMensaje = session('error');
                session()->forget('error');
            }else{
                if(session()->exists('error_f')){
                    $bandRregistro = '2';
                    $registroMensaje = session('error_f');
                    session()->forget('error_f');
                }
            }
        }
        
        // Verificar si hay un ID de aplicación para abrir el chat
        $openChatApplicationId = isset($open_chat) ? $open_chat : null;
        $projectsTitle = __('translation.projects_title');
    @endphp

<div class="container-fluid bg-light">
    <x-page-header :title="$projectsTitle" icon="briefcase">
        <div class="search-header-container">
            <form method="GET" action="{{route('project_Search')}}/{{ $type }}" id="searchForm" class="needs-validation mb-0" novalidate>
                @csrf
                <div class="input-group">
                    <input type="text" id="search" name="search_term" class="form-control" placeholder="Buscar por ID proyecto o nombre cliente" value="{{ $client_name ?? $project_id ?? '' }}">
                    <input type="hidden" name="client_name" id="hidden_client_name">
                    <input type="hidden" name="project_id" id="hidden_project_id">
                    <button type="submit" class="btn btn-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('account') }}/{{ $type }}" class="btn btn-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </x-page-header>
        
    <div class="row mb-4 mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <button class="advanced-search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearchCollapse" aria-expanded="false" aria-controls="advancedSearchCollapse">
                <i class="fas fa-search-plus"></i> Búsqueda avanzada
            </button>
            
            <div class="ventas-totales-compact">
                @php
                    $auxTotal = explode(".",Number::currency((int)$total));
                    $total = $auxTotal[0]; 
                @endphp
                <span class="ventas-label">Ventas totales:</span>  
                <span id="h3TotalVentas" class="total-amount">{{$total}}</span>
            </div>
        </div>
    </div>

    <div class="row mb-4 collapse" id="advancedSearchCollapse">
        <div class="col-12">
            <div class="card rounded-3 border-0 shadow-sm">
                <div class="card-body">
                    <form id="formProjects" class="needs-validation" method="GET" action="{{route('project_Search')}}/{{ $type }}" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3 mb-3">
                                <label for="select_order" class="form-label">Ordenar por:</label>
                                <select id="select_order" name="select_order" class="form-select">
                                    <option value="1" @if($select_order==1) selected @endif>ID Proyecto</option>
                                    <option value="2" @if($select_order==2) selected @endif>Fecha de creación</option>
                                    <option value="3" @if($select_order==3) selected @endif>Ciudad</option>
                                    <option value="4" @if($select_order==4) selected @endif>Estado</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="startDate" class="form-label">Fecha inicial</label>
                                <input id="startDate" name="startDate" type="text" class="form-control" 
                                    placeholder="mm/dd/yyyy" value="{{ $date1 }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="endDate" class="form-label">Fecha final</label>
                                <input id="endDate" name="endDate" type="text" class="form-control"
                                    placeholder="mm/dd/yyyy" value="{{ $date2 }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="select_statusApp" class="form-label">Estatus</label>
                                <select id="select_statusApp" name="select_statusApp" class="form-select">
                                    <option value="">Todos los Estatus</option>
                                    @foreach($select_statusApp as $status)
                                        <option value="{{$status->co_estatus_aplicacion}}" 
                                            @if($status->selected ==true) selected @endif>
                                            {{$status->tx_nombre}}
                                        </option>    
                                    @endforeach
                                </select>
                            </div>

                            @if ($type != 'ownprojects')
                                <div class="col-md-3 mb-3">
                                    <label for="select_analyst" class="form-label">Analista</label>
                                    <select id="select_analyst" name="select_analyst" class="form-select">
                                        <option value="">Todos los Analistas</option>
                                        @foreach($select_analyst as $analyst)
                                            <option value="{{$analyst->co_usuario}}" @if($analyst->selected == true) selected @endif> 
                                                {{$analyst->analista}}
                                            </option>    
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($type == 'ownprojects') 
                                <div class="col-md-3 mb-3 d-none">
                                    <label for="select_analyst" class="form-label">Analista</label>
                                    <select id="select_analyst" name="select_analyst" class="form-select">
                                        <option value="">Todos los Analistas</option>
                                        <option value="{{$co_usuario_logueado}}"></option>    
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-3 mb-3">
                                <label for="project_id" class="form-label">ID Proyecto</label>
                                <input id="project_id" name="project_id" type="text" class="form-control"
                                    placeholder="ID Proyecto" value="{{ $project_id ?? '' }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="client_name" class="form-label">Nombre Cliente</label>
                                <input id="client_name" name="client_name" type="text" class="form-control"
                                    placeholder="Nombre Cliente" value="{{ $client_name ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="row g-2 w-100">
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">APLICAR FILTROS</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="row g-2 w-100">
                                    <div class="col-12">
                                        <a href="{{ route('account') }}/{{ $type }}" class="btn btn-secondary w-100">Reiniciar</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="row g-2 w-100">
                                    <div class="col-12">
                                        <button type="button" id="btndownloadProjects" class="btn btn-info text-white w-100">DESCARGAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
            
            <div class="table-responsive" id="divTableLoading">
                @php
                    // Definimos los encabezados para la tabla
                    $headers = [
                        ['title' => 'Acciones'],
                        ['title' => 'ID Proyecto<br>Nombre Apellido', 'class' => 'text-nowrap'],
                        ['title' => 'Fecha de<br>creación', 'class' => 'text-nowrap'],
                        ['title' => 'Agendador'],
                        ['title' => 'Analista'],
                        ['title' => 'Estado<br>Ciudad', 'class' => 'text-nowrap'],
                        ['title' => 'Estatus<br>Actual', 'class' => 'text-nowrap'],
                        ['title' => 'Monto']
                    ];
                @endphp
                
                <x-data-table 
                    :headers="$headers" 
                    :data="$proyectos->items()" 
                    :perPage="$perPage" 
                    :currentPage="$proyectos->currentPage()" 
                    :totalItems="$proyectos->total()" 
                    tableId="divTableLoading" 
                    :isLoading="false"
                >
                    @foreach($proyectos as $item)
                        <tr class="text-center">
                            <td>
                                <div class="d-flex justify-content-center gap-3">
                                    <div class="text-center" 
                                        data_id_item="{{$item->co_cliente}}"
                                        data_item="{{$item->co_cliente}}&{{$item->tx_primer_nombre}}&{{$item->tx_primer_apellido}}&{{$item->tx_estado}}&{{$item->tx_ciudad}}&{{$item->tx_direccion1}} {{$item->tx_direccion2}}&{{$item->tx_zip}}&{{$item->tx_telefono}}&{{$item->tx_email}}" 
                                        data_co_aplicacion="{{$item->co_aplicacion}}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#myModal1" 
                                        style="cursor:pointer;">
                                        <i data-feather="message-circle"></i>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('forms.general-info.edit', ['co_aplicacion' => $item->co_aplicacion]) }}" style="color: inherit;">
                                            <i data-feather="edit"></i>
                                        </a>
                                    </div>
                                    <div class="text-center">
                                        @if($item->notificaciones > 0)
                                            <span class="badge bg-danger" 
                                                id="badge_{{ $item->co_aplicacion }}" 
                                                style="cursor: pointer;" 
                                                title="Ver notificaciones"
                                                data_co_aplicacion="{{ $item->co_aplicacion }}">
                                                {{$item->notificaciones}}
                                            </span>
                                        @else
                                                <div class="text-center bell" 
                                                    data_co_aplicacion="{{$item->co_aplicacion}}"
                                                    >
                                                    <i data-feather="bell" class="menu-icon"></i>
                                                </div>
                                            
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{$item->co_aplicacion}}</div>
                                <div>{{$item->tx_primer_nombre}} {{$item->tx_primer_apellido}}</div>
                            </td>
                            <td>
                                @php
                                    $fecha_r = Carbon::parse($item->fe_creacion);
                                    $item->fe_creacion = $fecha_r->isoFormat('MM/DD/YYYY');                                           
                                @endphp
                                <div>{{$item->fe_creacion}}</div>
                                <div><i class="fas fa-check-square text-success"></i></div>
                            </td>
                            <td>{{$item->settername}}</td>
                            <td>{{$item->ownername}}</td>
                            <td>
                                <div>{{$item->tx_estado}}</div>
                                <div>{{$item->tx_ciudad}}</div>
                            </td>
                            <td>
                                @php
                                    if($item->fe_activacion_estatus_mas_reciente){
                                        $fecha_r2 = Carbon::parse($item->fe_activacion_estatus_mas_reciente);
                                        $item->fe_activacion_estatus_mas_reciente = $fecha_r2->isoFormat('MM/DD/YYYY');
                                    }else
                                        $item->fe_activacion_estatus_mas_reciente = "";                                          
                                @endphp
                                <div>{{$item->fe_activacion_estatus_mas_reciente}}</div>
                                <div class="status-badge">
                                    @if($item->estatus_mas_reciente!="")
                                        {{$item->estatus_mas_reciente}}
                                    @else 
                                        ---
                                    @endif
                                </div>
                                <div><i class="fas fa-check-square text-success"></i></div>
                            </td>
                            @php
                                $auxPrecio = explode(".",Number::currency((int)$item->nu_precio_total));
                                $item->nu_precio_total = $auxPrecio[0]; 
                            @endphp                                
                            <td>{{$item->nu_precio_total}}</td>
                        </tr>
                    @endforeach
                    
                    <x-slot name="pagination">
                        {{ $proyectos->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </x-slot>
                </x-data-table>
            </div>
        </div>
    </div>
</div>
{{-- modal info --}}
<div class="modal fade" id="myModal1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i data-feather="info-circle"></i> Información del Proyecto</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2 mb-3 text-dark text-start">
                            <label for="forma_0" class="form-label">
                                <h5>ID. Cliente</h5>
                            </label>
                            <input type="text" class="form-control border-success" id="forma_0" readonly>
                        </div>
                        <div class="col-md-5 mb-3 text-dark text-start">
                            <label for="forma_1" class="form-label">
                                <h5>Nombres</h5>
                            </label>
                            <input type="text" class="form-control border-primary" id="forma_1" readonly>
                        </div>
                        <div class="col-md-5 mb-3 text-dark text-start">
                            <label for="forma_2" class="form-label">
                                <h5>Apellido</h5>
                            </label>
                            <input type="text" class="form-control border-primary" id="forma_2" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 mb-3 text-dark text-start">
                            <label for="forma_3" class="form-label">
                                <h5>Estado</h5>
                            </label>
                            <input type="text" class="form-control border-secondary" id="forma_3" readonly>
                        </div>
                        <div class="col-md-2 mb-3 text-dark text-start">
                            <label for="forma_4" class="form-label">
                                <h5>Ciudad</h5>
                            </label>
                            <input type="text" class="form-control border-secondary" id="forma_4" readonly>
                        </div>
                        <div class="col-md-6 mb-3 text-dark text-start">
                            <label for="forma_5" class="form-label">
                                <h5>Dirección</h5>
                            </label>
                            <input type="text" class="form-control border-secondary" id="forma_5" readonly>
                        </div>
                        <div class="col-md-2 mb-3 text-dark text-start">
                            <label for="forma_6" class="form-label">
                                <h5>Código Postal</h5>
                            </label>
                            <input type="text" class="form-control border-warning" id="forma_6" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 mb-3 text-dark text-start">
                            <label for="forma_7" class="form-label">
                                <h5>Número Telefónico</h5>
                            </label>
                            <input type="text" class="form-control border-info" id="forma_7" readonly>
                        </div>
                        <div class="col-md-6 mb-3 text-dark text-start">
                            <label for="forma_8" class="form-label">
                                <h5>Email</h5>
                            </label>
                            <input type="text" class="form-control border-info" id="forma_8" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="progress m-3">
                        <div name="partbar" class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="row m-2">
                        <div class="col-12 align-middle justify-content-center d-flex flex-wrap">
                            <div class="m-1">
                                <button id="btn_verify" class="modal-btn">@lang('translation.text_send_document')</button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    {{--componente de documentos--}}
                    <div id="document-wrapper" class="row my-4 d-none">
                        <x-document title="Subir Documentos" app_document_id="" />
                    </div>
                    {{--final componente de documentos--}}
                    
                    <hr>
                    
                    {{--chat--}}  
                    <div class="row my-4">
                        <div id="chat-wrapper" class="col-12">
                            @include('components.chat', ['applicationId' => ''])
                        </div>                                
                    </div> 
                    {{--fin chat--}}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal Notification --}}
<div class="modal fade" id="notificationsModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i data-feather="info-circle"></i> Notificaciones del Proyecto</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="notificationsModalBody">
                <div class="container-fluid" >
                    
                </div>
            </div>
        </div>
    </div>
</div>
{{-- fin modal Notification --}}
{{--contiene la url de la imagen de perfil de usuario --}}
<input type="hidden" id="chat-profile-url" value="{{ asset('img/profile/no.png') }}">
<input type="hidden" id="user-id" value="{{ Auth::id() }}">
@endsection

@push('scripts')
<script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>
<script>
    (function () {
    'use strict';
    window.addEventListener('load', function () {
        // Función para validar formato de fecha mm/dd/yyyy
        function validarFormatoFecha(fecha) {
            if (!fecha) return true; // Si está vacío, es válido (no es obligatorio)
            
            const formatoFecha = /^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/;
            if (!formatoFecha.test(fecha)) return false;

            const partes = fecha.split('/');
            const mes = parseInt(partes[0], 10);
            const dia = parseInt(partes[1], 10);
            const año = parseInt(partes[2], 10);
            
            const fechaObj = new Date(año, mes - 1, dia);
            return fechaObj.getMonth() === mes - 1 && 
                   fechaObj.getDate() === dia && 
                   fechaObj.getFullYear() === año;
        }

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                let isValid = true;
                let firstInvalidField = null;
                
                // Validar campo startDate
                const startDateField = form.querySelector('#startDate');
                if (startDateField && startDateField.value.trim() && !validarFormatoFecha(startDateField.value.trim())) {
                    isValid = false;
                    startDateField.classList.add('is-invalid');
                    
                    // Borrar el valor actual
                    startDateField.value = '';
                    
                    // Agregar mensaje de error si no existe
                    let errorDiv = startDateField.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Formato mm/dd/yyyy';
                        startDateField.parentNode.insertBefore(errorDiv, startDateField.nextSibling);
                    }
                    
                    if (!firstInvalidField) {
                        firstInvalidField = startDateField;
                    }
                } else if (startDateField) {
                    startDateField.classList.remove('is-invalid');
                    const errorDiv = startDateField.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                }
                
                // Validar campo endDate
                const endDateField = form.querySelector('#endDate');
                if (endDateField && endDateField.value.trim() && !validarFormatoFecha(endDateField.value.trim())) {
                    isValid = false;
                    endDateField.classList.add('is-invalid');
                    
                    // Borrar el valor actual
                    endDateField.value = '';
                    
                    // Agregar mensaje de error si no existe
                    let errorDiv = endDateField.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Formato mm/dd/yyyy';
                        endDateField.parentNode.insertBefore(errorDiv, endDateField.nextSibling);
                    }
                    
                    if (!firstInvalidField) {
                        firstInvalidField = endDateField;
                    }
                } else if (endDateField) {
                    endDateField.classList.remove('is-invalid');
                    const errorDiv = endDateField.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                }

                const projectIdField = document.getElementById('project_id');
                const projectIdValue = projectIdField.value.trim();

                if (projectIdValue && !/^\d+$/.test(projectIdValue)) {
                    isValid = false;
                    projectIdField.classList.add('is-invalid');
                    let errorDiv = projectIdField.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Solo digitos';
                        projectIdField.parentNode.insertBefore(errorDiv, projectIdField.nextSibling);
                    }
                }

                // Si hay errores, prevenir el envío y enfocar el primer campo con error
                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Quitar la clase was-validated
                    form.classList.remove('was-validated');
                    
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                    }
                }
            }, false);

            document.getElementById('project_id').addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorDiv = this.nextElementSibling;
                 if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            });

            // Limpiar errores cuando el usuario modifica los campos
            const dateFields = form.querySelectorAll('#startDate, #endDate');
            dateFields.forEach(function(field) {
                field.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const errorDiv = this.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                });
            });
            
            // Estilos para asegurar que los mensajes aparezcan debajo
            const styleElement = document.createElement('style');
            styleElement.textContent = `
                .invalid-feedback {
                    display: block !important;
                    margin-top: 0.25rem;
                    font-size: 80%;
                    color: #dc3545;
                }
            `;
            document.head.appendChild(styleElement);
        });
    }, false);
})();
</script>

<script>
    // Script para procesar el formulario de búsqueda en el header
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const searchTerm = document.getElementById('search').value.trim();
                const hiddenClientName = document.getElementById('hidden_client_name');
                const hiddenProjectId = document.getElementById('hidden_project_id');
                
                // Si el término de búsqueda es numérico, es un ID de proyecto
                if (/^\d+$/.test(searchTerm)) {
                    hiddenProjectId.value = searchTerm;
                    hiddenClientName.value = '';
                } else {
                    // Si no es numérico, es un nombre de cliente
                    // Limpiamos espacios extra y normalizamos el formato
                    const normalizedName = searchTerm
                        .replace(/\s+/g, ' ') // Reemplaza múltiples espacios por uno solo
                        .trim(); // Elimina espacios al inicio y final
                    
                    hiddenClientName.value = normalizedName;
                    hiddenProjectId.value = '';
                }
                
                // Enviar el formulario
                this.submit();
            });
        }
    });
</script>

<script>
        const bandRregistro = "{{ $bandRregistro }}";
        const registroMensaje = "{{ $registroMensaje }}";
        window.onload = function() {
            if (bandRregistro == "1") {
                Swal.fire({
                    title: "Exito!",
                    text: registroMensaje,
                    icon: "success"
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
            
            // Verificar si hay un ID de aplicación para abrir automáticamente el modal
            const openChatApplicationId = "{{ $openChatApplicationId ?? '' }}";
            if (openChatApplicationId) {
                openChatModal(openChatApplicationId);
            }
        };
        
        // Función para abrir automáticamente el modal del chat para una aplicación específica
        function openChatModal(applicationId) {
            // Primero verificar si el proyecto está en la tabla actual
            const rows = document.querySelectorAll('tr');
            let targetElement = null;
            let projectExistsInTable = false;
            
            rows.forEach(row => {
                // Buscar el ID de aplicación en la celda correspondiente
                const appIdCell = row.querySelector('td:nth-child(2) div:first-child');
                if (appIdCell && appIdCell.textContent.trim() == applicationId) {
                    // Encontramos la fila con la aplicación requerida
                    targetElement = row.querySelector('[data-bs-toggle="modal"]');
                    projectExistsInTable = true;
                }
            });
            
            // Si el proyecto existe en la tabla actual, abrir el modal directamente
            if (projectExistsInTable && targetElement) {
                // Simular clic en el elemento para abrir el modal
                targetElement.click();
                
                // Asegurarse de que el chat esté visible
                setTimeout(() => {
                    const chatWrapper = document.getElementById('chat-wrapper');
                    if (chatWrapper) {
                        chatWrapper.scrollIntoView({ behavior: 'smooth' });
                    }
                }, 500);
                return;
            }
            
            // Si no está en la tabla, verificar si estamos en un ciclo de búsqueda
            const urlParams = new URLSearchParams(window.location.search);
            const currentProjectId = urlParams.get('project_id');
            const searchAttempted = urlParams.get('search_attempted');
            
            // Solo mostrar mensaje si ya intentamos buscar y no se encontró
            if (searchAttempted === '1' && currentProjectId === applicationId && !projectExistsInTable) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Información',
                    html: 'No se encontró la aplicación con ID: ' + applicationId,
                    showConfirmButton: true,
                    buttonsStyling: false,
                    showCloseButton: true,
                    customClass: {
                        confirmButton: 'btn btn-primary mb-1',
                    }
                });
                return;
            }
            
            // Si no se encuentra la aplicación y no se ha intentado buscar, buscarla
            if (!projectExistsInTable) {
                // Crear y enviar formulario de búsqueda para ese ID específico
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route("project_Search") }}/{{ $type }}';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'project_id';
                input.value = applicationId;
                
                const openChatInput = document.createElement('input');
                openChatInput.type = 'hidden';
                openChatInput.name = 'open_chat';
                openChatInput.value = applicationId;
                
                // Agregar flag para indicar que ya se intentó buscar
                const searchAttemptedInput = document.createElement('input');
                searchAttemptedInput.type = 'hidden';
                searchAttemptedInput.name = 'search_attempted';
                searchAttemptedInput.value = '1';
                
                form.appendChild(input);
                form.appendChild(openChatInput);
                form.appendChild(searchAttemptedInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Handle per page selector change
        $('#perPageSelect').change(function() {
            const perPage = $(this).val();
            const currentUrl = window.location.href;
            
            // Build the new URL with the perPage parameter
            let newUrl;
            if (currentUrl.includes('?')) {
                if (currentUrl.includes('perPage=')) {
                    newUrl = currentUrl.replace(/perPage=\d+/g, 'perPage=' + perPage);
                } else {
                    newUrl = currentUrl + '&perPage=' + perPage;
                }
            } else {
                newUrl = currentUrl + '?perPage=' + perPage;
            }
            
            // Reset to first page when changing per_page
            if (newUrl.includes('page=')) {
                newUrl = newUrl.replace(/page=\d+/g, 'page=1');
            }
            
            window.location.href = newUrl;
        });

        $('#myModal1').on('shown.bs.modal', function(event){

            const datos = $(event.relatedTarget).attr('data_item');
            const aux_item = datos.split('&');
            
            aux_item.forEach((item, i)=>{
                $("#forma_"+i).val(item);
            });

            //------------------------------------------
            const id_cliente = $(event.relatedTarget).attr('data_id_item');
            const co_aplicacion = $(event.relatedTarget).attr('data_co_aplicacion');
            
            jQuery.ajax({                        
                headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'GET',
                url: '/account/projects/urls?co_cliente=' + id_cliente + '&co_aplicacion=' + co_aplicacion,
                dataType: 'JSON',
                beforeSend: function() {
                    $("#btn_precla").prop('disabled', 'disabled');
                    $("#btn_precla").html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="visually-hidden" role="status">Loading...</span>'); 
                    //---------------
                    $("#btn_autor").prop('disabled', 'disabled');
                    $("#btn_autor").html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="visually-hidden" role="status">Loading...</span>');
                    //--------------------
                    $("#btn_photo").prop('disabled', 'disabled');
                    $("#btn_photo").html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="visually-hidden" role="status">Loading...</span>');
                    //---------------------------
                    $("#btn_verifica").prop('disabled', 'disabled');
                    $("#btn_verifica").html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="visually-hidden" role="status">Loading...</span>');
                    //---------------------------
                    $("#btn_referred").prop('disabled', 'disabled');
                    $("#btn_referred").html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="visually-hidden" role="status">Loading...</span>');

                },
                success: function(data) {                   
                   
                    const badge = $("#badge_" + co_aplicacion);
                    if (badge.length > 0) {
                        let parentDiv = badge.closest('div'); 
                        const newBell = `
                            <div class="text-center bell" 
                                data_co_aplicacion="${co_aplicacion}"
                            >
                            <i data-feather="bell" class="menu-icon"></i>
                            </div>`;
                        parentDiv.html(newBell);
                        feather.replace();
                    }
                },
                complete: function() {
                    $("#btn_precla").removeAttr('disabled');
                    $("#btn_precla").html('Proposals');
                    //---------------------------
                    $("#btn_autor").removeAttr('disabled');
                    $("#btn_autor").html('Formulario de Autorización de Pago');
                    //-------------------
                    $("#btn_photo").removeAttr('disabled');
                    $("#btn_photo").html('Agregar Foto');
                    //---------------------
                    $("#btn_verifica").removeAttr('disabled');
                    $("#btn_verifica").html('Verificación de Documentos');
                    //--------------------------------
                    $("#btn_referred").removeAttr('disabled');
                    $("#btn_referred").html('Programa de Referidos');
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ups..',
                        html: data.responseJSON.msg,
                        showConfirmButton: true,
                        buttonsStyling: false,
                        showCloseButton: true,
                        customClass: {
                            confirmButton: 'btn btn-primary mb-1',
                        }
                    }).then(function(){
                        $("#btn_precla").removeAttr('href');
                        $("#btn_autor").removeAttr('href');
                        $("#btn_photo").removeAttr('href');
                        $("#btn_verifica").removeAttr('href');
                        $("#btn_referred").removeAttr('href');      
                    })

                                              
                }
            });
            //------------------------------------------
            //const button = event.relatedTarget;
            const applicationId = $(event.relatedTarget).attr('data_co_aplicacion');
            
            
            // Actualizar el ID del contenedor del chat
            const chatContainer = document.querySelector('#chat-wrapper .chat-container');
            chatContainer.id = `chat-${applicationId}`;
            const token = $('input[name="_token"]').val();
            // Inicializar nueva instancia del chat
            if (window.ChatManager) {
                chatInstance = new ChatManager(applicationId, token);
            }

            if (window.DocumentManager) {
                documentInstance = new DocumentManager(applicationId, token);                
            }

        });

        $('#myModal1').on('hidden.bs.modal', function (event) {
  
            $("#btn_precla").html('');
            $("#btn_precla").attr('href','');
            //---------------------------
            $("#btn_autor").html('');
            $("#btn_autor").attr('href','');
            //-------------------
            $("#btn_photo").html('');
            $("#btn_photo").attr('href','');
            //---------------------
            $("#btn_verifica").html('');
            $("#btn_verifica").attr('href','');
            //--------------------------------
            $("#btn_referred").html('');
            $("#btn_referred").attr('href','');
            //--------------------------------
            for(let i=0; i<=9; i++){
               $("#forma_"+i).val("");
            }
            if (chatInstance) {
               //chatInstance.cleanup();
                chatInstance.destroy().then(() => {
                     chatInstance = null;
                    
                }).catch(error => {
                    console.error('Error al finalizar el chat:', error);
                });
            }
            if(documentInstance){
                documentInstance.cleanup();
                documentInstance = null;
                $('#document-wrapper').addClass('d-none')

            }
        })

       
        $("#btn_verify").click(function(event) {
            event.preventDefault();
            const btnVerify = document.getElementById('btn_verify'); 
            if ($('#document-wrapper').hasClass('d-none')) {
                if(documentInstance){
                    btnVerify.disabled = true;
                    documentInstance.show().then(() => {
                        $('#document-wrapper').removeClass('d-none');
                        btnVerify.disabled = false;
                    }).catch(error => {
                        console.error('Error al mostrar el documento:', error);
                        btnVerify.disabled = false;
                    });
                }
            } else {
                $('#document-wrapper').addClass('d-none')
                if(documentInstance){
                    documentInstance.cleanup();
                }
            }
        });
        $('#btn_document_verify').on('click', function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario           
            const btnDocumentVerify = document.getElementById('btn_document_verify'); 
            if(documentInstance){
                btnDocumentVerify.disabled = true;
                documentInstance.send().then(result => {
                    if (result.success) {                       
                        Swal.fire({
                            icon: 'info',
                            title: 'Informacion',
                            html: JSON.stringify(result.data.message),
                            showConfirmButton: true,
                            buttonsStyling: false,
                            showCloseButton: true,
                            customClass: {
                                confirmButton: 'btn btn-primary mb-1',
                            }
                        });
                        documentInstance.cleanup();
                        $('#document-wrapper').addClass('d-none');
                        
                    } else {                        
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            html: 'Problemas en la carga de documentos. Contacte al administrador.',
                            showConfirmButton: true,
                            buttonsStyling: false,
                            showCloseButton: true,
                            customClass: {
                                confirmButton: 'btn btn-primary mb-1',
                            }
                        });
                    }
                    
                }).catch(error => {
                    
                    Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            html: 'Problemas en la carga de documentos. Contacte al administrador.',
                            showConfirmButton: true,
                            buttonsStyling: false,
                            showCloseButton: true,
                            customClass: {
                                confirmButton: 'btn btn-primary mb-1',
                            }
                        });
                        btnDocumentVerify.disabled = false;
                });
                btnDocumentVerify.disabled = false;
            }
                
        });
                
    </script>    
<script src="{{ asset('js/chat.js') }}"></script>
<script src="{{ asset('js/document.js') }}"></script>

<script>
   $(document).ready(function() { 
    let chatInstance = null;    
    let documentInstance = null;
                
        
        $("#btndownloadProjects").click(function(event) {
            
            let form = $("#formProjects");
            let oldUrl = form.attr("action");
            let oldMethod = form.attr("method");
          
            let urlExport = "{{route('exportProjects')}}/{{$type}}"; 
            form.attr("method", "GET");
            form.attr("action", urlExport);
            form.submit();
            form.attr("method", oldMethod);
            form.attr("action", oldUrl);
            
        });        

        $('#startDate').datepicker({
            uiLibrary: 'bootstrap5',
            maxDate: function() {
                return $('#endDate').val();
            }
        });
        
        $('#endDate').datepicker({
            uiLibrary: 'bootstrap5',
            minDate: function() {
                return $('#startDate').val();
            }
        });

        function loadProjectsTable(data)
        {
            let tbodyTabla = document.getElementById('table-body'); 
            tbodyTabla.innerHTML ="";
            
            let auxTotalize = Number(data.total);
            const totalize = auxTotalize.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 0, 
                    maximumFractionDigits: 0 
                }); 
            document.getElementById('h3TotalVentas').innerHTML = totalize; 
            
            const baseUrlEdit = '{{ route("forms.general-info.edit", ["co_aplicacion" => ""]) }}';
            
            data.data.forEach(item=>{
                const str = item.fe_activacion_estatus_mas_reciente;
                let fecha_rf2 = "---";
                if (str !== null && str !== "" ){
                    const fecha = str.substring(0,10);
                    fecha_rf2 = moment(fecha).format("MM/DD/YYYY");                               
                }

                const str2 = item.fe_creacion;
                const fecha2 = str2.substring(0,10);
                const fechaf = moment(fecha2).format("MM/DD/YYYY"); 

                let estatus_mas_reciente = "---";
                if (item.estatus_mas_reciente !== null && item.estatus_mas_reciente !== "" ){ 
                    estatus_mas_reciente = item.estatus_mas_reciente;
                }

                let estado_app = "";
                if (item.tx_estado !== null && item.tx_estado !== "" ){
                    estado_app = item.tx_estado;
                }

                let ciudad_app = "";
                if (item.tx_ciudad !== null && item.tx_ciudad !== "" ){
                    ciudad_app = item.tx_ciudad;
                }                            

                let direccion_app = "";
                if (item.tx_direccion1 !== null && item.tx_direccion1 !== "" ){
                    direccion_app = item.tx_direccion1;
                }

                let direccion_app2 = "";
                if (item.tx_direccion2 !== null && item.tx_direccion2 !== "" ){
                    direccion_app2 = item.tx_direccion2;
                } 


                if (item.estatus_app_siguiente == null || item.estatus_app_siguiente == "" ){
                    item.estatus_app_siguiente = '';
                } 

                if (item.estatus_financ_app_siguiente == null || item.estatus_financ_app_siguiente == "" ){
                    item.estatus_financ_app_siguiente = '';
                } 

                if (item.estatus_financ_mas_reciente == null || item.estatus_financ_mas_reciente == "" ){
                    item.estatus_financ_mas_reciente = '';
                } 
                if(item.settername == null || item.settername == ''){
                    item.settername ='';
                }

                if(item.ownername == null || item.ownername == ''){
                    item.ownername ='';
                }

                const auxPrecio = Number(item.nu_precio_total);

                item.nu_precio_total = auxPrecio.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 0, 
                    maximumFractionDigits: 0 
                });                

                let filaHTM = '<tr class="text-center">';
                filaHTM+=`<td>`;
                filaHTM+=`<div class="d-flex justify-content-center gap-3">`;
                filaHTM+=`<div 
                            data_id_item="${item.co_cliente}"
                            data_item="${item.co_cliente}&${item.tx_primer_nombre}&${item.tx_primer_apellido}&${item.tx_estado}&${item.tx_ciudad}&${item.tx_direccion1} ${item.tx_direccion2}&${item.tx_zip}&${item.tx_telefono}&${item.tx_email}" 
                            data_co_aplicacion="${item.co_aplicacion}"
                            data-bs-toggle="modal" 
                            data-bs-target="#myModal1" 
                            style="cursor:pointer;"><i data-feather="message-circle"></i></div>`;
                        
                filaHTM+=`<div                          
                            style="cursor:pointer;">
                            <a href="${baseUrlEdit}${item.co_aplicacion}" style="color: inherit;">
                            <i data-feather="edit"></i>
                            </a>
                            </div>`;
                            
                filaHTM+=`</div>`;
                filaHTM+=`</td>`;

                filaHTM+=`<td class="">`;
                filaHTM+=`<div class="row">`;
                filaHTM+=`<div class="col-12">${item.co_aplicacion}</div>`;
                filaHTM+=`</div>`;
                filaHTM+=`<div class="row">`;        
                filaHTM+=`<div class="col-12">${item.tx_primer_nombre} ${item.tx_primer_apellido}</div>`;
                filaHTM+=`</div>`;                                    
                filaHTM+=`</td>`;

                filaHTM+=`<td class="">`;
                filaHTM+=`<div class="row">`;
                filaHTM+=`<div class="col-12 text-center">${fechaf}</div>`;
                filaHTM+=`</div>`;
                filaHTM+=`</td>`;

                filaHTM+=`<td>${item.settername}</td>`;
                filaHTM+=`<td>${item.ownername}</td>`;
                
                filaHTM+=`<td class="">`;
                filaHTM+=`<div class="row">`
                filaHTM+=`<div class="col-12">${estado_app}</div>`;
                filaHTM+=`<div class="col-12">${ciudad_app}</div>`;
                filaHTM+=`</div>`;
                filaHTM+=`</td>`;

                filaHTM+=`<td>`;
                filaHTM+=`<div class="row">`;
                filaHTM+=`<div>${fecha_rf2}</div>`;
                filaHTM+=`<div class="status-badge">${estatus_mas_reciente}</div>`;
                filaHTM+=`<div><i class="fas fa-check-square text-success"></i></div>`;
                filaHTM+=`</div>`;
                filaHTM+=`</td>`;
                
                filaHTM+=`<td>`;
                filaHTM+=`<div class="status-next">${item.estatus_app_siguiente}</div>`;
                filaHTM+=`<div><i class="fas fa-question-circle text-warning"></i></div>`;
                filaHTM+=`</td>`;                            
                filaHTM+=`<td class="">`;
                
                filaHTM+=`<div class="text-center">${item.nu_precio_total}</div>`;
                filaHTM+=`</td>`;

            filaHTM+=`</tr>`;                            
            console.log(filaHTM);
            $(tbodyTabla).append(filaHTM);
            });
            
            // Update pagination if available
            if (data.pagination) {
                updatePagination(data.pagination);
            }
        }
        
        function updatePagination(pagination) {
    // Create container for pagination info
    let paginationInfoHtml = `Mostrando ${pagination.from || 0} a ${pagination.to || 0} de ${pagination.total} registros`;
    
    // Create container for pagination links
    let paginationLinksHtml = '<ul class="pagination pagination-sm">';
    
    // Previous page link
    paginationLinksHtml += `
        <li class="page-item ${pagination.current_page <= 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" data-page="${pagination.current_page - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>`;
    
    // Generate page links
    if (pagination.links && pagination.links.length > 0) {
        pagination.links.forEach(link => {
            if (!isNaN(link.label)) { // Only numeric links
                paginationLinksHtml += `
                    <li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0)" data-page="${link.label}">${link.label}</a>
                    </li>`;
            }
        });
    }
    
    // Next page link
    paginationLinksHtml += `
        <li class="page-item ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" data-page="${pagination.current_page + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>`;
    
    paginationLinksHtml += '</ul>';
    
    // FORZAR LA ELIMINACIÓN de cualquier contenedor de paginación existente
    $('.pagination-container').remove();
    
    // Añadir un nuevo contenedor único
    $('#divTableLoading').after(`
        <div class="row pagination-container">
            <div class="col-md-6">
                <div class="pagination-info">
                    ${paginationInfoHtml}
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end pagination-links">
                    ${paginationLinksHtml}
                </div>
            </div>
        </div>
    `);
    
    // Add event listeners for pagination links
    $('.pagination .page-link').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            goToPage(page);
        }
    });
}
        function goToPage(page) {
            // Get current perPage value
            const perPage = $('#perPageSelect').val() || 10;
            
            jQuery.ajax({                        
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'GET',
                url: "{{route('orderProject')}}",
                data:{
                    'startDate': $('#startDate').val(),
                    'endDate' : $('#endDate').val(),
                    'order' : $('#select_order').val(),
                    'statusApp' : $('#select_statusApp').val(),
                    'analyst' : $('#select_analyst').val(),
                    'project_id' : $('#project_id').val(),
                    'client_name' : $('#client_name').val(),
                    'type' : "{{$type}}",
                    'page': page,
                    'perPage': perPage
                },
                dataType: 'JSON',
                beforeSend: function() {
                    divTableLoading.block({
                        message: '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    });
                },
                success: function(data) {
                    loadProjectsTable(data);
                },
                complete: function() {
                    divTableLoading.unblock();
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        html: data.responseJSON.msg,
                        showConfirmButton: true,
                        buttonsStyling: false,
                        showCloseButton: true,
                        customClass: {
                            confirmButton: 'btn btn-primary mb-1',
                        }
                    })                        
                }
            });
        }

        function filterAndOrder()
        {
            // Reset to page 1 when filters change
            jQuery.ajax({                        
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'GET',
                url: "{{route('orderProject')}}",
                data:{
                        'startDate': $('#startDate').val(),
                        'endDate' : $('#endDate').val(),
                        'order' : $('#select_order').val(),
                        'statusApp' : $('#select_statusApp').val(),
                        'analyst' : $('#select_analyst').val(),
                        'project_id' : $('#project_id').val(),
                        'client_name' : $('#client_name').val(),
                        'type' : "{{$type}}",
                        'page': 1,
                        'perPage': $('#perPageSelect').val() || 10
                },
                    dataType: 'JSON',
                    beforeSend: function() {
                        divTableLoading.block({
                            message: '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        });
                    },
                    success: function(data) {
                        loadProjectsTable(data);
                    },
                    complete: function() {
                        divTableLoading.unblock();
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Información',
                            html: data.responseJSON.msg,
                            showConfirmButton: true,
                            buttonsStyling: false,
                            showCloseButton: true,
                            customClass: {
                                confirmButton: 'btn btn-primary mb-1',
                            }
                        })                        
                    }
                });
        }

        function updateSelectStatusApp(data)
        {
           
           let my_select_status = $('#select_statusApp');
           
           if (data.select_statusApp.length > 0){
                $('#select_statusApp option').remove();
                let option = '<option value="">Todos los Estatus</option>';
                my_select_status.append(option);
                   data.select_statusApp.forEach(item=>{                    
                    option = `<option value="${item.co_estatus_aplicacion}" `;
                    if(item.selected == true)
                        option += `selected>`;
                    else
                        option += `>`
                    option += `${item.tx_nombre}`;
                    option +='</option>';
                    my_select_status.append(option);
                });

           }
        } 
        
        function updateSelectAnalyst(data)
        {
            let my_select_analyst = $('#select_analyst');
           
           if (data.select_analyst.length > 0){
                $('#select_analyst option').remove();
                let option = '<option value="">Todos los Analistas</option>';
                my_select_analyst.append(option);
                   data.select_analyst.forEach(item=>{                    
                    option = `<option value="${item.co_usuario}" `;
                    if(item.selected == true)
                        option += `selected>`;
                    else
                        option += `>`
                    option += `${item.analista}`;
                    option +='</option>';
                    my_select_analyst.append(option);
                });

           }
        }
        const divTableLoading = $('#divTableLoading');
        
        $('#select_order').on('change', function() {
             $('#formProjects').submit();
        });
    });    
</script>

<script>
    // Mostrar/ocultar búsqueda avanzada al cargar la página si hay filtros aplicados
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const projectId = document.getElementById('project_id').value;
        const clientName = document.getElementById('client_name').value;
        const statusApp = document.getElementById('select_statusApp').value;
        
        // Verificar si algún filtro está activo
        if (startDate || endDate || projectId || clientName || statusApp) {
            const advancedSearchCollapse = new bootstrap.Collapse(document.getElementById('advancedSearchCollapse'), {
                toggle: true
            });
        }
    });    
</script>
<script>
    $(document).ready(function() {

        $(document).on('click', '.badge.bg-danger, .bell', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const clickedElement = $(this); // Referencia al elemento que recibió el clic
            const applicationId = clickedElement.attr('data_co_aplicacion');
            

            if (!applicationId) {
                console.error('No se encontró el ID de la aplicación');
                return;
            }

            let urlDestiny;

            // Determinar si se hizo clic en el badge o en el div del ícono
            if (clickedElement.hasClass('badge')) { // O .is('.badge.bg-danger') para ser más específico
                // Si es el badge, cargar solo notificaciones no leídas                
                urlDestiny = `/account/notifications/${applicationId}`; // O solo `/account/notifications/${applicationId}` si 'unread' es el default
            } else {
                // Si es el div del ícono, cargar todas las notificaciones                
                urlDestiny = `/account/notifications/${applicationId}/all`;
            }

            // Limpiar el contenido anterior del modal y mostrar spinner
            $('#notificationsModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

            // Mostrar el modal base inmediatamente
            const modal = new bootstrap.Modal(document.getElementById('notificationsModal'));
            modal.show();

            // Realizar la petición AJAX para obtener el contenido del modal
            $.ajax({
                url: urlDestiny,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {                    
                    // Inyectar solo el contenido recibido dentro del modal-body
                    $('#notificationsModalBody').html(response);

                    // Si necesitas inicializar JS o Feather icons dentro del modal
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    // Si necesitas re-ejecutar algún script específico del contenido del modal, añádelo aquí.

                },
                error: function(xhr) {
                    console.error('Error en la petición AJAX:', xhr);
                    // Mostrar mensaje de error dentro del modal body
                    $('#notificationsModalBody').html('<div class="alert alert-danger" role="alert">No se pudieron cargar las notificaciones. Por favor, intente nuevamente.</div>');
                }
            });
        });


        $('#notificationsModal').on('hidden.bs.modal', function () {
            $('#notificationsModalBody').empty(); // Limpiar contenido al cerrar            
        });
    });
</script>
@endpush