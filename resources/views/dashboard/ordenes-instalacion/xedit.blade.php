@extends('layouts.master')

@section('title')
    Órdenes de Instalación - Centro de Negocio
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
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
        
        /* Estilos para status badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }
        
        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-aprobado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-rechazado {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-pagado {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        /* Estilos para iconos de acciones */
        .action-icons {
            display: flex;
            gap: 3px;
            justify-content: center;
        }
        
        .action-icons .action-icon {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            background-color: transparent;
            color: #64748b;
        }
        
        .action-icons .action-icon:hover {
            color: #3B82F6;
            transform: scale(1.1);
        }
        
        /* Estilos específicos de team.blade.php */
        .table-code {
            width: 80px;
        }
        
        .icon-sm {
            width: 16px;
            height: 16px;
            stroke-width: 2.5;
        }
        
        .text-secondary {
            color: #64748b !important;
        }
        
        .btn-transparent {
            background-color: transparent;
            border: none;
            padding: 0.375rem;
            transition: color 0.15s ease-in-out;
        }
        
        .btn-transparent:hover {
            color: var(--color-primary, #3B82F6) !important;
        }
        
        .width-80 {
            width: 80px;
        }
        
        .min-width-100 {
            min-width: 100px;
        }
        
        .min-width-110 {
            min-width: 110px;
        }
        
        .min-width-120 {
            min-width: 120px;
        }
        
        .min-width-130 {
            min-width: 130px;
        }
        
        .min-width-150 {
            min-width: 150px;
        }
        
        .bell {
            cursor: pointer;
        }
        
        .info-section {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            color: #6c757d;
        }
        
        .chat-section {
            flex: 1;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .chat-modal-content {
            /*height: 600px;*/
            display: flex;
            flex-direction: column;
        }
        
        .chat-modal-body {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
    </style>
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="container-fluid bg-light">
    <x-page-header title="Órdenes de Instalación" icon="settings">
        <div class="search-header-container">
            <form action="{{ route('installation.search') }}" method="GET" class="needs-validation mb-0" novalidate>
                @csrf
                <div class="input-group">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Buscar" onkeypress="if(event.keyCode == 13) { event.preventDefault(); this.form.submit(); }">
                    <button type="submit" class="btn btn-light d-flex align-items-center justify-content-center">
                        <i data-feather="search" class="icon-sm"></i>
                    </button>
                    <a href="{{ route('installation') }}" class="btn btn-light d-flex align-items-center justify-content-center">
                        <i data-feather="x" class="icon-sm"></i>
                    </a>
                </div>
            </form>
        </div>
    </x-page-header>
    <br>    
    
    
    <div class="row mb-4 mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <button class="advanced-search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearchCollapse" aria-expanded="false" aria-controls="advancedSearchCollapse">
                <i class="fas fa-search-plus"></i> Búsqueda avanzada
            </button>
        </div>
    </div>

    <div class="row mb-4 collapse" id="advancedSearchCollapse">
        <div class="col-12">
            <div class="card rounded-3 border-0 shadow-sm">
                <div class="card-body">
                    <form id="formBusqueda" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3 mb-3">
                                <label for="fechaInicio" class="form-label">Fecha inicial</label>
                                <input id="fechaInicio" name="fechaInicio" type="text" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" autocomplete="off" readonly 
                                    value="{{ request('fechaInicio') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="fechaFin" class="form-label">Fecha final</label>
                                <input id="fechaFin" name="fechaFin" type="text" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" autocomplete="off" readonly 
                                    value="{{ request('fechaFin') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="ordenarPor" class="form-label">Ordenar por</label>
                                <select id="ordenarPor" name="order" class="form-select">
                                    <option value="1" {{ request('order') == '1' ? 'selected' : '' }}>Más reciente</option>
                                    <option value="2" {{ request('order') == '2' ? 'selected' : '' }}>Fecha de instalación</option>
                                    <option value="3" {{ request('order') == '3' ? 'selected' : '' }}>Estatus</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="estatusOrden" class="form-label">Estatus de la Orden</label>
                                <select id="estatusOrden" name="co_estatus_orden" class="form-select">
                                    <option value="">Todos los Estatus</option>
                                    @foreach($status_ordenes as $status)
                                        <option value="{{ $status->co_estatus_orden }}" 
                                            {{ request('co_estatus_orden') == $status->co_estatus_orden ? 'selected' : '' }}>
                                            {{ $status->tx_estatus_orden }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="row g-2 w-100">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary w-100" onclick="buscarOrdenes()">APLICAR FILTROS</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="row g-2 w-100">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary w-100" onclick="limpiarFiltros()">Reiniciar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="row g-2 w-100">
                                    <div class="col-12">
                                        <button type="button" id="btnDescargar" class="btn btn-info text-white w-100" onclick="descargarReporte()">DESCARGAR</button>
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
        <div class="col-12">
            <div class="card-body p-0">
                        
            @php
                $headers = [
                    ['title' => 'Acciones', 'class' => 'width-80'],
                    ['title' => 'ID<br>Apellido, Nombre', 'class' => 'table-code'],
                    ['title' => 'Teléfono', 'class' => 'min-width-120'],
                    ['title' => 'Estatus', 'class' => 'min-width-130'],
                    ['title' => 'Acción', 'class' => 'min-width-120'],
                    ['title' => 'Total Gasto', 'class' => 'min-width-110'],
                    ['title' => 'Manager', 'class' => 'min-width-120'],
                    ['title' => 'Instalador', 'class' => 'min-width-120'],
                    ['title' => 'Fecha de Instalación', 'class' => 'min-width-130']
                ];
                
                // Usar datos reales del controlador
                //$ordenesData = collect($ordenes);
                
                // Paginación real se manejará cuando se implemente
                $perPage = $paginatedData->perPage();
                $currentPage = $paginatedData->currentPage();
                $totalItems = $paginatedData->total();
            @endphp

            <x-data-table 
                :headers="$headers"
                :data="$paginatedData"
                :perPage="$perPage"
                :currentPage="$currentPage"
                :totalItems="$totalItems"
                tableId="ordenes-instalacion-table"
                emptyMessage="No hay órdenes de instalación disponibles para los filtros seleccionados">
                
                @foreach($paginatedData as $orden)
                    <tr>
                        <td class="width-80">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <div class="text-center">
                                    <button class="btn btn-transparent" onclick="openChatModal({{ $orden->co_orden }})">
                                        <i data-feather="message-circle"></i>
                                    </button>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-transparent" onclick="editOrden({{ $orden->co_orden }})">
                                        <i data-feather="edit"></i>
                                    </button>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-transparent" onclick="openMoneyModal({{ $orden->co_orden }})">
                                        <i data-feather="dollar-sign"></i>
                                    </button>
                                </div>
                                <div class="text-center">
                                    @if($orden->notific_no_vistas > 0)
                                        <span class="badge bg-danger" 
                                            id="badge_{{ $orden->co_aplicacion }}" 
                                            style="cursor: pointer;" 
                                            title="Ver notificaciones"
                                            data_co_aplicacion="{{ $orden->co_aplicacion }}"
                                            data_co_orden="{{ $orden->co_orden }}"
                                            >
                                            {{ $orden->notific_no_vistas }}
                                        </span>
                                    @elseif($orden->notific_vistas > 0)
                                        <div class="text-center bell" style="cursor: pointer;" 
                                            data_co_aplicacion="{{ $orden->co_aplicacion }}"
                                            data_co_orden="{{ $orden->co_orden }}"
                                            >
                                            <i data-feather="bell" class="menu-icon"></i>
                                        </div>                                        
                                    @endif
                                </div>
                            </div>                                    
                        </td>
                        <td class="table-code" style="text-align: center; vertical-align: middle;">
                            <div class="fw-bold">{{ $orden->co_orden }}</div>
                            <div>{{ $orden->tx_primer_apellido_cliente }}, {{ $orden->tx_primer_nombre_cliente }}</div>
                        </td>                                                
                        <td class="min-width-120">{{ $orden->tx_telefono }}</td>
                        <td class="min-width-130">
                            @php
                                $statusClass = match(strtolower($orden->estatus_de_la_orden)) {
                                    'aprobado' => 'status-aprobado',
                                    'pendiente' => 'status-pendiente',
                                    'pagado' => 'status-pagado',
                                    'rechazado' => 'status-rechazado',
                                    default => 'status-pendiente'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $orden->estatus_de_la_orden }}</span>
                        </td>
                        <td class="min-width-120">{{ $orden->accion }}</td>
                        <td class="min-width-110">${{ number_format($orden->total_gastos_orden, 2) }}</td>
                        <td class="min-width-120">{{ $orden->tx_primer_apellido_manager }}, {{ $orden->tx_primer_nombre_manager }}</td>
                        <td class="min-width-120">{{ $orden->primer_apellido_plomero }} {{ $orden->primer_nombre_plomero }}</td>
                        <td class="min-width-130" style="text-align: center; vertical-align: middle;">{{ $orden->fecha_instalacion }}</td>
                    </tr>
                @endforeach
                <x-slot name="pagination">                    
                    {{ $paginatedData->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
                </x-slot>
            </x-data-table>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <!-- Modal de Chat -->
    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content chat-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">
                        <i data-feather="message-circle" class="me-2"></i>
                        Chat - Orden <span id="chatOrderId"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body chat-modal-body">
                    <!-- Información de la orden -->
                    <div class="info-section">
                        <h6 class="mb-3">Información de la Orden</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Cliente:</span>
                                    <span class="info-value" id="chatClientName"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Teléfono:</span>
                                    <span class="info-value" id="chatClientPhone"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Financiera:</span>
                                    <span class="info-value" id="chatFinanciera"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Monto:</span>
                                    <span class="info-value" id="chatMonto"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Estatus:</span>
                                    <span class="info-value" id="chatStatus"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Fecha:</span>
                                    <span class="info-value" id="chatFecha"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Componente de chat -->
                    <div class="chat-section">                        
                        @include('components.chat', ['applicationId' => ''])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Pago -->
    <div class="modal fade" id="moneyModal" tabindex="-1" aria-labelledby="moneyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moneyModalLabel">
                        <i data-feather="dollar-sign" class="me-2"></i>
                        Gestión de Pago - Orden <span id="moneyOrderId"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Información del pago -->
                    <div class="info-section">
                        <h6 class="mb-3">Información del Pago</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Cliente:</span>
                                    <span class="info-value" id="moneyClientName"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Monto Aprobado:</span>
                                    <span class="info-value" id="moneyMontoAprobado"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Comisión:</span>
                                    <span class="info-value" id="moneyComision"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Total Gasto:</span>
                                    <span class="info-value" id="moneyTotalGasto"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Estatus Actual:</span>
                                    <span class="info-value" id="moneyStatusActual"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Fecha de Pago:</span>
                                    <span class="info-value" id="moneyFechaPago"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulario de actualización de pago -->
                    <form id="paymentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nuevoStatus" class="form-label">Actualizar Estatus</label>
                                    <select class="form-select" id="nuevoStatus" name="nuevoStatus">
                                        <option value="pendiente">Pendiente</option>
                                        <option value="aprobado">Aprobado</option>
                                        <option value="pagado">Pagado</option>
                                        <option value="rechazado">Rechazado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fechaPago" class="form-label">Fecha de Pago</label>
                                    <input type="date" class="form-control" id="fechaPago" name="fechaPago">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="montoPagado" class="form-label">Monto Pagado</label>
                                    <input type="number" class="form-control" id="montoPagado" name="montoPagado" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metodoPago" class="form-label">Método de Pago</label>
                                    <select class="form-select" id="metodoPago" name="metodoPago">
                                        <option value="transferencia">Transferencia</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3" placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="updatePayment()">Actualizar Pago</button>
                </div>
            </div>
        </div>
    </div>

{{-- modal Notification --}}
<div class="modal fade" id="notificationsModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i data-feather="info-circle"></i> Notificaciones de la Orden</h4>
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
<input type="hidden" id="chat-profile-url" value="{{ asset('img/profile/no.png') }}">
<input type="hidden" id="user-id" value="{{ Auth::id() }}"> {{-- Agregar esta línea --}}
@endsection

@push('scripts')
    <script src="{{ asset('vendor/data-tables-bootstrap5/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>    
    <script src="{{ asset('js/chatoi.js') }}"></script>
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
                
                // Validar campo fechaInicio
                const fechaInicioField = form.querySelector('#fechaInicio');
                if (fechaInicioField && fechaInicioField.value.trim() && !validarFormatoFecha(fechaInicioField.value.trim())) {
                    isValid = false;
                    fechaInicioField.classList.add('is-invalid');
                    
                    // Borrar el valor actual
                    fechaInicioField.value = '';
                    
                    // Agregar mensaje de error si no existe
                    let errorDiv = fechaInicioField.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Formato mm/dd/yyyy';
                        fechaInicioField.parentNode.insertBefore(errorDiv, fechaInicioField.nextSibling);
                    }
                    
                    if (!firstInvalidField) {
                        firstInvalidField = fechaInicioField;
                    }
                } else if (fechaInicioField) {
                    fechaInicioField.classList.remove('is-invalid');
                    const errorDiv = fechaInicioField.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                }
                
                // Validar campo fechaFin
                const fechaFinField = form.querySelector('#fechaFin');
                if (fechaFinField && fechaFinField.value.trim() && !validarFormatoFecha(fechaFinField.value.trim())) {
                    isValid = false;
                    fechaFinField.classList.add('is-invalid');
                    
                    // Borrar el valor actual
                    fechaFinField.value = '';
                    
                    // Agregar mensaje de error si no existe
                    let errorDiv = fechaFinField.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Formato mm/dd/yyyy';
                        fechaFinField.parentNode.insertBefore(errorDiv, fechaFinField.nextSibling);
                    }
                    
                    if (!firstInvalidField) {
                        firstInvalidField = fechaFinField;
                    }
                } else if (fechaFinField) {
                    fechaFinField.classList.remove('is-invalid');
                    const errorDiv = fechaFinField.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
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

            // Limpiar errores cuando el usuario modifica los campos
            const dateFields = form.querySelectorAll('#fechaInicio, #fechaFin');
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
    // Datos de las órdenes del backend
    const ordenesData = @json($paginatedData);

    // Variable global para la instancia del chat
    let chatInstance = null;

    // Inicializar componentes cuando el DOM esté listo
    $(document).ready(function() {
        // Inicializar datepickers con los mismos estilos que account.blade.php
        $('#fechaInicio').datepicker({
            uiLibrary: 'bootstrap5',
            maxDate: function() {
                return $('#fechaFin').val();
            }
        });
        
        $('#fechaFin').datepicker({
            uiLibrary: 'bootstrap5',
            minDate: function() {
                return $('#fechaInicio').val();
            }
        });
        
        // Inicializar Feather Icons usando la función global
        if (typeof safeFeatherReplace === 'function') {
            safeFeatherReplace();
        } else {
            // Fallback a feather.replace() si no está disponible la función segura
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
        
        // También inicializar cuando la ventana esté completamente cargada
        $(window).on('load', function() {
            setTimeout(function() {
                if (typeof safeFeatherReplace === 'function') {
                    safeFeatherReplace();
                } else if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 200);
        });

        const divTableLoading = $('#divTableLoading');
    });

    // Función para buscar órdenes
    function buscarOrdenes() {
        const fechaInicio = $('#fechaInicio').val();
        const fechaFin = $('#fechaFin').val();
        const ordenarPor = $('#ordenarPor').val();
        const estatusOrden = $('#estatusOrden').val();
        
        // Construir URL con parámetros
        const params = new URLSearchParams();
        
        if (fechaInicio) params.append('fechaInicio', fechaInicio);
        if (fechaFin) params.append('fechaFin', fechaFin);
        if (ordenarPor) params.append('order', ordenarPor);
        if (estatusOrden) params.append('co_estatus_orden', estatusOrden);
        
        // Redirigir con los parámetros
        window.location.href = `{{ route('installation') }}?${params.toString()}`;
    }

    // Función para limpiar filtros
    function limpiarFiltros() {
        $('#fechaInicio').val('');
        $('#fechaFin').val('');
        $('#ordenarPor').val('1');
        $('#estatusOrden').val('');
        
        // Limpiar también errores de validación
        $('#fechaInicio, #fechaFin').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Redirigir sin parámetros
        window.location.href = `{{ route('installation') }}`;
    }

    // Función para abrir modal de chat
    function openChatModal(ordenId) {
        // Destruir instancia anterior si existe
        if (chatInstance) {
            chatInstance.destroy().then(() => {
               
                chatInstance = null;
                initializeNewChat(ordenId);
            }).catch(error => {                
                chatInstance = null;
                initializeNewChat(ordenId);
            });
        } else {
            initializeNewChat(ordenId);
        }
    }

    // Función para inicializar nuevo chat
    function initializeNewChat(ordenId) {
        const orden = ordenesData.data.find(o => o.co_orden == ordenId);
        if (orden) {
                 
            // Actualizar datos del modal
            $('#chatOrderId').text(orden.co_orden);
            $('#chatClientName').text(orden.tx_primer_apellido_cliente + ', ' + orden.tx_primer_nombre_cliente);
            $('#chatClientPhone').text(orden.tx_telefono);
            $('#chatFinanciera').text('N/A');
            $('#chatMonto').text('$' + parseFloat(orden.total_gastos_orden).toLocaleString('es-ES', { minimumFractionDigits: 2 }));
            $('#chatStatus').text(orden.estatus_de_la_orden);
            $('#chatFecha').text(orden.fecha_instalacion);
            
            // Mostrar modal
            $('#chatModal').modal('show');
            
            // Inicializar chat después de que el modal esté visible
            $('#chatModal').off('shown.bs.modal').on('shown.bs.modal', function() {
                // Actualizar el ID del contenedor del chat
                const chatContainer = document.querySelector('.chat-section .chat-container');
                if (chatContainer) {
                    chatContainer.id = `chat-${ordenId}`;
                }
                
                if (window.ChatManagerOI) {
                    const token = '{{ csrf_token() }}';
                    // Crear nueva instancia
                    chatInstance = new ChatManagerOI(ordenId, token);
                }
            });
        }
    }

    // Función para abrir modal de dinero
    function openMoneyModal(ordenId) {
        const orden = ordenesData.data.find(o => o.co_orden == ordenId);
        if (orden) {
            $('#moneyOrderId').text(orden.co_orden);
            $('#moneyClientName').text(orden.tx_primer_apellido_cliente + ', ' + orden.tx_primer_nombre_cliente);
            $('#moneyMontoAprobado').text('N/A'); // Si no tienes monto aprobado
            $('#moneyComision').text('N/A'); // Si no tienes comisión
            $('#moneyTotalGasto').text('$' + parseFloat(orden.total_gastos_orden).toLocaleString('es-ES', { minimumFractionDigits: 2 }));
            $('#moneyStatusActual').text(orden.estatus_de_la_orden);
            $('#moneyFechaPago').text(orden.fecha_instalacion);
            
            // Preseleccionar el status actual
            $('#nuevoStatus').val(orden.estatus_de_la_orden.toLowerCase());
            
            $('#moneyModal').modal('show');
        }
    }

    // Función para editar orden
    function editOrden(ordenId) {
        // Aquí puedes redirigir a una vista de edición o mostrar un modal de edición
        Swal.fire({
            icon: 'info',
            title: 'Función de edición',
            text: 'Redirigiendo a editar orden ID: ' + ordenId,
            showConfirmButton: true,
            buttonsStyling: false,
            showCloseButton: true,
            customClass: {
                confirmButton: 'btn btn-primary mb-1',
            }
        });
    }

    // Función para actualizar pago
    function updatePayment() {
        const formData = {
            nuevoStatus: $('#nuevoStatus').val(),
            fechaPago: $('#fechaPago').val(),
            montoPagado: $('#montoPagado').val(),
            metodoPago: $('#metodoPago').val(),
            notas: $('#notas').val()
        };
        
        // Aquí iría la lógica para enviar los datos al servidor
        Swal.fire({
            icon: 'success',
            title: 'Pago actualizado',
            text: 'El pago se ha actualizado correctamente',
            showConfirmButton: true,
            buttonsStyling: false,
            showCloseButton: true,
            customClass: {
                confirmButton: 'btn btn-primary mb-1',
            }
        }).then(() => {
            $('#moneyModal').modal('hide');
        });
    }

    // Función para descargar reporte
    function descargarReporte() {
        const fechaInicio = $('#fechaInicio').val();
        const fechaFin = $('#fechaFin').val();
        const ordenarPor = $('#ordenarPor').val();
        const estatusOrden = $('#estatusOrden').val();
        
        // Construir URL con parámetros para descarga
        const params = new URLSearchParams();
        params.append('download', '1');
        
        if (fechaInicio) params.append('fechaInicio', fechaInicio);
        if (fechaFin) params.append('fechaFin', fechaFin);
        if (ordenarPor) params.append('order', ordenarPor);
        if (estatusOrden) params.append('co_estatus_orden', estatusOrden);
        
        // Abrir en nueva pestaña para descarga
        window.open(`{{ route('installation.export') }}?${params.toString()}`, '_blank');
    }

    // Actualizar iconos de Feather cuando se abren los modales
    $('#chatModal, #moneyModal').on('shown.bs.modal', function() {
        if (typeof safeFeatherReplace === 'function') {
            safeFeatherReplace();
        } else if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Mostrar/ocultar búsqueda avanzada al cargar la página si hay filtros aplicados
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        const estatusOrden = document.getElementById('estatusOrden').value;
        const order = document.getElementById('ordenarPor').value;
        
        // Verificar si algún filtro está activo
        if (fechaInicio || fechaFin || estatusOrden || (order && order !== '1')) {
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

            const clickedElement = $(this);
            const applicationId = clickedElement.attr('data_co_aplicacion');
            const ordenId = clickedElement.attr('data_co_orden');
            

            if (!applicationId) {
                console.error('No se encontró el ID de la orden');
                return;
            }

            let urlDestiny;

            // Determinar si se hizo clic en el badge o en el div del ícono
            if (clickedElement.hasClass('badge')) {
                // Si es el badge, cargar solo notificaciones no leídas                
                urlDestiny = `/installation/notifications/${applicationId}`; 
            } else {
                // Si es el div del ícono, cargar todas las notificaciones                
                urlDestiny = `/installation/notifications/${applicationId}/all`;
            }

            // Limpiar el contenido anterior del modal y mostrar spinner
            $('#notificationsModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

            // Mostrar el modal base inmediatamente
            const modal = new bootstrap.Modal(document.getElementById('notificationsModal'));
            modal.show();

            // Simular contenido de notificaciones
            setTimeout(function() {
                const mockNotifications = `
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Notificaciones de la Orden ${ordenId}</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Actualización de estatus</h6>
                                        <small>Hace 3 días</small>
                                    </div>
                                    <p class="mb-1">El estatus de pago ha sido actualizado a "Pendiente".</p>
                                    <small>Sistema automático</small>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Asignación de instalador</h6>
                                        <small>Hace 1 semana</small>
                                    </div>
                                    <p class="mb-1">Se ha asignado un instalador para esta orden.</p>
                                    <small>Manager del equipo</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#notificationsModalBody').html(mockNotifications);
                
                // Reinicializar iconos de Feather
                if (typeof safeFeatherReplace === 'function') {
                    safeFeatherReplace();
                } else if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 500);
        });

        $('#notificationsModal').on('hidden.bs.modal', function () {
            $('#notificationsModalBody').empty();
        });
        $('#chatModal').on('hidden.bs.modal', function () {
            // Limpiar datos anteriores
            $('#chatOrderId').text('');
            $('#chatClientName').text('');
            $('#chatClientPhone').text('');
            $('#chatFinanciera').text('');
            $('#chatMonto').text('');
            $('#chatStatus').text('');
            $('#chatFecha').text('');
            // Destruir instancia del chat
            if (chatInstance) {
                chatInstance.destroy().then(() => {
                    
                    chatInstance = null;
                }).catch(error => {                    
                    chatInstance = null;
                });
            }
            
            // Limpiar contenedor del chat
           // const chatContainer = document.querySelector('.chat-section .chat-container');
            //if (chatContainer) {
                //chatContainer.innerHTML = '';
                //chatContainer.id = '';
            //}
            
            // Remover event listeners específicos del modal
            $('#chatModal').off('shown.bs.modal');
        });
    });
</script>
@endpush 