@extends('layouts.master')

@section('title')
    @lang('translation.leads_title') - @lang('translation.business-center')
@endsection

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btnRango {
            background-color: #318ce7;
            margin-bottom: 15px;
        }

        .campo-date {
            margin-bottom: 2px;
        }

        .tabla-informe {
            display: block;
            overflow: auto;
            width: 300px;
            /* Ajusta el ancho según tus necesidades */
        }

        .margin-select{
            margin-bottom: 15px !important;
        }
        #validationCustom10::placeholder{
            color: darkgray;
        }

        /* Override datepicker wrapper margin */
        .gj-datepicker-bootstrap.input-group {
            margin-bottom: 0 !important;
        }

        .btn-disabled {
        background-color: #d3d3d3 !important; /* Gris claro */
        border-color: #d3d3d3 !important;      
        cursor: not-allowed !important;
        opacity: 0.7 !important;
        pointer-events: none !important;
    }
        
        /* Estilos para el buscador en page-header */
        .search-header-container {
            width: 100%;
            max-width: 450px;
        }
        
        .search-header-container .input-group .form-control {
            background-color: white;
            color: #333;
            border: 1px solid #fff;
            border-radius: 30px 0 0 30px;
        }
        
        .search-header-container .input-group .btn-outline-light {
            background-color: white;
            color: var(--color-primary);
            border-color: #fff;
        }
        
        .search-header-container .input-group .btn-light {
            background-color: white;
            color: var(--color-primary);
            border-color: #fff;
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
        
        /* Estilos para el botón de búsqueda avanzada */
        .advanced-search-btn {
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
            position: relative;
            overflow: visible;
        }
        
        /* Nuevo layout responsivo para los botones principales */
        .main-buttons-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .main-buttons-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .advanced-search-btn {
                width: 100%;
            }
        }
        
        /* Ajustes para las acciones en la tabla */
        .action-icons-container {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        
        @media (max-width: 768px) {
            .action-icons-container {
                flex-direction: column;
            }
            
            .action-icons-container .action-icon {
                margin-bottom: 5px;
            }
        }

        /* Estilos para los botones de acciones */
        .action-btn {
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: #fff;
            border: none;
            border-radius: var(--radius-md, 8px);
            padding: 0.5rem 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        .action-btn:hover {
            filter: brightness(1.05);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: #fff;
        }
        
        .action-btn.secondary {
            background: white;
            color: var(--color-primary, #13c0e6);
            border: 1px solid var(--color-primary, #13c0e6);
        }
        
        .action-btn i {
            font-size: 0.875rem;
        }

        /* Estilos para los modales */
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

        .modal-header .modal-title span {
            color: white;
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

        .modal-body .form-control,
        .modal-body .form-select {
            border-radius: var(--radius-sm, 4px);
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            background-color: #fff;
            height: 38px; /* Altura consistente para todos los campos */
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: #13c0e6;
            box-shadow: 0 0 0 0.2rem rgba(19, 192, 230, 0.25);
        }

        .modal-body .form-control.border-success,
        .modal-body .form-control.border-primary,
        .modal-body .form-control.border-secondary,
        .modal-body .form-control.border-warning,
        .modal-body .form-control.border-info {
            border-width: 1px;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 1rem 1.5rem;
        }

        .modal-btn {
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: #fff;
            border: none;
            border-radius: var(--radius-md, 8px);
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .modal-btn:hover {
            filter: brightness(1.05);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }

        .progress {
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            background-color: #e9ecef;
        }

        .progress .progress-bar {
            background: linear-gradient(to right, #13c0e6, #4687e6);
        }
        
        /* Mejoras para el responsive de los modales */
        .modal-xl {
            max-width: 95%;
        }
        
        @media (min-width: 1200px) {
            .modal-xl {
                max-width: 1140px;
            }
        }
        
        /* Ajustes específicos para alineación de campos en modales */
        .modal-body .row {
            margin-bottom: 0.5rem;
        }
        
        /* Alineación consistente de etiquetas */
        .modal-body .form-label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
            line-height: 1.2;
            height: 2.2em; /* Altura fija para todas las etiquetas */
            overflow: hidden;
        }
        
        /* Corrección para el campo de la inicial del segundo nombre */
        .modal-body label[for="validationCustom1"] {
            white-space: normal;
            height: auto; /* Permite que crezca según necesite */
            min-height: 2.2em;
        }
        
        .modal-body h5 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        
        /* Espaciado consistente entre elementos del formulario */
        .modal-body .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        /* Ajustes responsivos para los campos en dispositivos pequeños */
        @media (max-width: 767.98px) {
            .modal-body .col-md-1,
            .modal-body .col-md-2,
            .modal-body .col-md-3,
            .modal-body .col-md-4,
            .modal-body .col-md-5,
            .modal-body .col-md-6,
            .modal-body .col-md-8,
            .modal-body .col-md-12 {
                margin-bottom: 0.75rem;
            }
            
            .modal-body br {
                display: none;
            }
            
            .modal-dialog-centered {
                align-items: flex-start;
                padding-top: 1rem;
            }
            
            .modal-xl {
                margin: 0.5rem;
            }
            
            /* En móvil, permitir que las etiquetas ocupen el espacio que necesiten */
            .modal-body .form-label {
                height: auto;
                min-height: 1.2em;
            }
        }
        
        /* Estilo específico para el modal de preclasificación */
        #myModal1 .modal-body .row:not(:last-child) {
            margin-bottom: 0.5rem;
        }
        
        /* Corrección específica para campos del formulario en dispositivos medianos */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .modal-body .form-label {
                height: auto;
                min-height: 2.2em;
                display: flex;
                align-items: flex-start;
            }
        }

        /* Estilos específicos para el modal de nuevo/editar */
        #myModal .form-label {
            display: flex;
            align-items: flex-start;
            height: auto;
            min-height: 2.5em;
            margin-bottom: 0.25rem;
        }
        
        /* Ajuste específico para los tres primeros campos del formulario */
        #myModal .row:first-of-type .form-label {
            height: 2.5em;
            overflow: visible;
            word-break: normal;
        }
        
        /* Ajuste para la etiqueta de Primera Letra del Segundo Nombre */
        #myModal label[for="validationCustom1"] {
            line-height: 1.2;
            display: block;
        }
        
        /* Ajuste consistente para los campos de formulario */
        #myModal .row .col-sm-12 {
            margin-bottom: 1rem;
        }
        
        @media (min-width: 768px) {
            #myModal .row .col-md-4 {
                margin-bottom: 1.25rem;
            }
            
            #myModal .row:first-of-type .col-md-4 {
                display: flex;
                flex-direction: column;
            }
            
            #myModal .row:first-of-type .form-control {
                flex: 1;
                margin-top: auto;
            }
        }

        /* Otros estilos que puedan existir */
        
        .fecha-actualizacion {
            display: inline-block;
            padding: 3px 8px;
            /*background-color: rgba(239, 68, 68, 0.1);*/
            /*color: #ef4444;*/
            border-radius: 5px;
            font-weight: 500;
        }
    </style>
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
        $leadsTitle = __('translation.leads_title');
    @endphp
    <div class="container-fluid">
        <x-page-header :title="$leadsTitle" icon="shopping-bag">
            <div class="search-header-container">
                <form class="needs-validation mb-0" method="GET" action="{{route('searchTextLeads')}}" novalidate>
                    @csrf
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Buscar">
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </x-page-header>
        <div class="container-fluid p-0">
            <div class="mb-3 pt-2 main-buttons-container">
                <button class="advanced-search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearchCollapse" aria-expanded="false" aria-controls="advancedSearchCollapse">
                    <i class="fas fa-search-plus"></i> Búsqueda avanzada
                </button>
                <button id="newLeadBtn" type="button" class="advanced-search-btn" data-bs-toggle="modal" data-bs-target="#myModal">
                    <i class="fas fa-plus"></i> Nuevo Prospecto
                </button>
            </div>   
            
            <div class="collapse" id="advancedSearchCollapse">
                <div class="card card-body mb-4">
                <form class="needs-validation" method="POST" action="{{route('leadsSearch')}}" novalidate>
                    @csrf
                        <div class="row mb-3">
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="startDate" class="form-label">@lang('translation.start_date_form')</label>
                                <input id="startDate" name="startDate" type="text" class="form-control"
                                        placeholder="mm/dd/aa" aria-label="startDate" value="{{ $date1 }}" required>
                            </div>
                            
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="endDate" class="form-label">@lang('translation.end_date_form')</label>
                                <input id="endDate" type="text" name="endDate" class="form-control"
                                    placeholder="mm/dd/aa" aria-label="endDate" value="{{ $date2 }}" required>
                            </div>

                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="select_order" class="form-label">@lang('translation.order_by_date')</label>
                                <select id="select_order" name="select_order" class="form-select"
                                    aria-label="Select">
                                    <option value="1" @if($select_order==1) selected @endif>Fecha ultima actualización</option>
                                    <option value="2" @if($select_order==2) selected @endif>Fecha de la cita</option>
                                    <option value="3" @if($select_order==3) selected @endif>Ciudad</option>
                                    <option value="4" @if($select_order==4) selected @endif>Estado</option>
                                </select>
                            </div>
                            </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button class="action-btn" type="submit">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            
                            <a href="{{ route('shop') }}" class="action-btn secondary">
                                <i class="fas fa-sync-alt"></i> Reiniciar
                            </a>
                        </div>
                </form>
            </div>
            </div>
            
                <div class="table-responsive">
                    <div id="divTableLoading">
                    @php
                        // Definimos los encabezados para la tabla
                        $headers = [
                            ['title' => 'Acciones', 'class' => 'col-1'],
                            ['title' => 'ID', 'class' => 'col-1'],
                            ['title' => 'Nombre', 'class' => 'col-1'],
                            ['title' => 'Apellido', 'class' => 'col-1'],
                            ['title' => 'Número de teléfono', 'class' => 'col-2'],
                            ['title' => 'Estado', 'class' => 'col-1'],
                            ['title' => 'Ciudad', 'class' => 'col-1'],
                            ['title' => 'Analista', 'class' => 'col-1'],
                            ['title' => 'Agendador', 'class' => 'col-1'],
                            ['title' => 'Código Postal', 'class' => 'col-1'],
                            ['title' => 'Cita', 'class' => 'col-1'],
                            ['title' => 'Actualización', 'class' => 'col-1']
                        ];
                    @endphp
                    
                    <x-data-table 
                        :headers="$headers" 
                        :data="$leads" 
                        tableId="leads-table" 
                        :isLoading="false"
                        :showPerPageSelector="false"
                        :showPagination="false"
                    >
                        @foreach ($leads as $item)
                                @php
                                $fecha_rf2 = "";
                                $fecha_ultima_actualizacion = "";
                                if($item->fe_fecha_cita != null || $item->fe_fecha_cita != ""){
                                    $fecha_r2 = Carbon::parse($item->fe_fecha_cita);
                                    $fecha_rf2 = $fecha_r2->isoFormat('MM/DD/YYYY');
                                }
                                if($item->fe_ultima_act != null || $item->fe_ultima_act != ""){
                                    $fecha_ultima_actualizacion = Carbon::parse($item->fe_ultima_act);
                                    $fecha_ultima_actualizacion = $fecha_ultima_actualizacion->isoFormat('MM/DD/YYYY');
                                }
                                @endphp
                                <tr class="text-center">
                                <td>
                                    <div class="action-icons-container">
                                        <div class="action-icon"
                                        data_proposals="{{$item->tx_url_proposals}}" 
                                        data_id_item="{{$item->co_cliente}}"
                                        data_item="{{$item->co_cliente}}&{{$item->tx_primer_nombre}}&{{$item->tx_segundo_nombre}}&{{$item->tx_primer_apellido}}&{{$item->estado}}&{{$item->tx_ciudad}}&{{$item->tx_direccion1}}&{{$item->zip}}&{{$item->tx_telefono}}&{{$item->tx_email}}&{{$item->co_ryve_owner}}&{{$fecha_rf2}}&{{$item->ho_cita}}&{{$item->tx_comentario}}&{{$item->co_fuente}}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#myModal1" 
                                        style="cursor:pointer;"><i data-feather="send"></i></div>
                                        <div class="action-icon"
                                        data_id_item="{{$item->co_cliente}}"
                                        data_item="{{$item->tx_primer_nombre}}&{{$item->tx_segundo_nombre}}&{{$item->tx_primer_apellido}}&{{$item->tx_email}}&{{$item->tx_ciudad}}&{{$item->co_estado}}&{{$item->zip}}&{{$fecha_rf2}}&{{$item->tx_direccion1}}&{{$item->tx_direccion2}}&{{$item->tx_telefono}}&{{$item->co_ryve_owner}}&{{$item->ho_cita}}&{{$item->tx_comentario}}&{{$item->co_fuente}}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#myModal" 
                                        style="cursor:pointer;"
                                        id="editLeadBtn"
                                        ><i data-feather="edit"></i>
                                        </div>                                        
                                    </div>
                                </td>
                                <td>{{$item->co_cliente}}</td>
                                <td>{{$item->tx_primer_nombre}}</td>
                                <td>{{$item->tx_primer_apellido}}</td>
                                <td>{{$item->tx_telefono}}</td>
                                <td>{{$item->estado}}</td>
                                <td>{{$item->tx_ciudad}}</td>
                                <td>{{$item->analista}}</td>
                                <td>{{$item->agendador}}</td>                            
                                <td>{{$item->zip}}</td>                                                            
                                <td>{{$fecha_rf2}}</td>
                                <td>{{$fecha_ultima_actualizacion}}</td>     
                                </tr>
                            @endforeach                               
                    </x-data-table>
                </div>
            </div>
        </div>
        </br>
    </div>
    {{-- modal nuevo y editar --}}
    <div class="modal fade " id="myModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i data-feather="user-plus"></i> <span id="titleModalNewEdit"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="form_new_edit" class="needs-validation" method="POST" action="{{ route('shopNewLead') }}" novalidate>
                            @csrf
                            <div class="row g-3 mb-2">
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom0" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="validationCustom0" name="nombre" value="{{ old('nombre') }}" placeholder="" required>
                                    @if ($errors->get('nombre'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror" >
                                            @foreach ((array) $errors->get('nombre') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif                                    
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom1" class="form-label text-truncate">Primera Letra del Segundo Nombre</label>
                                    <input type="text" class="form-control" id="validationCustom1" name="primera_letra_segundo_nombre" value="{{ old('primera_letra_segundo_nombre') }}" placeholder="" maxlength="1">
                                    @if ($errors->get('primera_letra_segundo_nombre'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror" >
                                            @foreach ((array) $errors->get('primera_letra_segundo_nombre') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif                                    
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom2" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="validationCustom2" name="apellido" value="{{ old('apellido') }}" placeholder="">
                                    @if ($errors->get('apellido'))
                                        <div class="d-block invalid-feedback">
                                            <div class="d-flex flex-column msg_formerror">
                                                @foreach ((array) $errors->get('apellido') as $message)
                                                    <div>{{ $message }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-2">
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom10" class="form-label">Número Telefónico</label>
                                    <input type="text" class="form-control" id="validationCustom10" name="telefono" placeholder="(555) 666-7777" value="{{ old('telefono') }}">
                                    @if ($errors->get('telefono'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('telefono') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <label for="validationCustom3" class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" id="validationCustom3" value="{{ old('email') }}" placeholder="" aria-describedby="inputGroupPrepend">
                                    @if ($errors->get('email'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('email') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-2">
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom4" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="city" id="validationCustom4" placeholder="" value="{{ old('city') }}">
                                    @if ($errors->get('city'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('city') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom5" class="form-label">Estado</label>
                                    <select id="validationCustom5" name="state" class="form-select" value="{{ old('state') }}">
                                        <option value="" selected>Seleccione...</option>
                                        @foreach ($listEstados as $item)
                                        <option value="{{$item->co_estado}}">{{$item->tx_nombre}}</option>    
                                        @endforeach                                        
                                    </select>
                                    @if ($errors->get('state'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('state') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="validationCustom6" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="validationCustom6" name="zip" value="{{ old('zip') }}" placeholder="" maxlength="10">
                                    @if ($errors->get('zip'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('zip') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-2">
                                <div class="col-sm-6 col-md-3">
                                    <label for="validationCustom7" class="form-label">Fecha Cita</label>
                                    <input id="validationCustom7" name="fecha_cita" type="text" class="form-control campo-date" placeholder="" aria-label="validationCustom7" value="{{ old('fecha_cita') }}">
                                    @if ($errors->get('fecha_cita'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('fecha_cita') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="validationCustom12" class="form-label">Hora Cita</label>
                                    <input id="validationCustom12" name="hora_cita" type="text" class="form-control campo-date" placeholder="" aria-label="validationCustom12" value="{{ old('hora_cita') }}">
                                    @if ($errors->get('hora_cita'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('hora_cita') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="validationCustom11" class="form-label">Dueño del Proyecto</label>
                                    <select id="validationCustom11" name="select_qbowner" class="form-select" value="{{ old('select_qbowner') }}" required>
                                        {{--<option value="" selected>Seleccione...</option>--}}
                                        @foreach ($select_qbowner as $item)
                                        <option value="{{$item->co_ryve_usuario}}" @if($item->co_usuario == $usuario_logueado) selected @endif>{{$item->tx_primer_nombre}} {{$item->tx_primer_apellido}}</option>    
                                        @endforeach                                        
                                    </select>
                                    @if ($errors->get('select_qbowner'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('select_qbowner') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="validationCustom14" class="form-label">Fuente</label>
                                    <select id="validationCustom14" name="select_fuente" class="form-select" value="{{ old('select_fuente') }}">
                                        <option value="" selected>Seleccione...</option>
                                        @foreach ($select_fuente as $item)
                                        <option value="{{$item->co_fuente}}">{{$item->tx_nombre}}</option>    
                                        @endforeach                                        
                                    </select>
                                    @if ($errors->get('select_fuente'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('select_fuente') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-2">
                                <div class="col-sm-12 col-md-6">
                                    <label for="validationCustom8" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="validationCustom8" name="direccion" placeholder="" value="{{ old('direccion') }}">
                                    @if ($errors->get('direccion'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('direccion') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="validationCustom9" class="form-label">Dirección 2</label>
                                    <input type="text" class="form-control" id="validationCustom9" name="direccion2" placeholder="" value="{{ old('direccion2') }}">
                                    @if ($errors->get('direccion2'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('direccion2') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-2">
                                <div class="col-12">
                                    <label for="validationCustom13" class="form-label">Nota</label>   
                                    <input id="validationCustom13" name="nota" type="text" class="form-control" placeholder="" aria-label="validationCustom13" value="{{ old('nota') }}">
                                    @if ($errors->get('nota'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column msg_formerror">
                                            @foreach ((array) $errors->get('nota') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                        <input type="hidden" id="code_lead_edit" name="code_lead_edit" value="{{old('code_lead_edit')}}">
                                        <label class="form-check-label" for="invalidCheck">
                                            Estoy segur@ que he ingresado los datos correctamente
                                        </label>
                                        <div class="invalid-feedback msg_formerror">
                                            Debes confirmar el recuadro anterior
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button id="btnModalNewEdit" class="modal-btn" style="margin-bottom: 15px;" type="submit" form="form_new_edit"></button>
                                </div>
                            </div>
                            <div class="row">
                                <div>
                                    <input type="hidden" class="d-none" id="input_action" name="input_action" value="{{old('input_action','new')}}">
                                    <button id="editValidateLeadBtn" type="button" class="d-none" data-bs-toggle="modal" data-bs-target="#myModal"></button>                    
                                </div>   
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal preclafificacion --}}
    <div class="modal fade" id="myModal1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i data-feather="bookmark"></i> Información del Prospecto</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <label for="forma_0" class="form-label">ID</label>
                                <input type="text" class="form-control border-success" id="forma_0" readonly>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <label for="forma_1" class="form-label">Nombres</label>
                                <input type="text" class="form-control border-primary" id="forma_1" readonly>
                            </div>
                            <div class="col-sm-6 col-md-2 col-lg-2">
                                <label for="forma_2" class="form-label">Inicial Seg. Nombre</label>
                                <input type="text" class="form-control border-primary" id="forma_2" readonly>
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <label for="forma_3" class="form-label">Apellido</label>
                                <input type="text" class="form-control border-primary" id="forma_3" readonly>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <label for="forma_4" class="form-label">Estado</label>
                                <input type="text" class="form-control border-secondary" id="forma_4" readonly>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <label for="forma_5" class="form-label">Ciudad</label>
                                <input type="text" class="form-control border-secondary" id="forma_5" readonly>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <label for="forma_6" class="form-label">Dirección</label>
                                <input type="text" class="form-control border-secondary" id="forma_6" readonly>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <label for="forma_7" class="form-label">Código Postal</label>
                                <input type="text" class="form-control border-warning" id="forma_7" readonly>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <label for="forma_8" class="form-label">Número Telefónico</label>
                                <input type="text" class="form-control border-info" id="forma_8" readonly>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <label for="forma_9" class="form-label">Email</label>
                                <input type="text" class="form-control border-info" id="forma_9" readonly>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="forma_10" class="form-label">Dueño del Proyecto</label>
                                <select id="forma_10" class="form-select" disabled="disabled">
                                    <option value="" selected>Seleccione...</option>
                                    @foreach ($select_qbowner as $item)
                                        <option value="{{$item->co_ryve_usuario}}">{{$item->tx_primer_nombre}} {{$item->tx_primer_apellido}}</option>    
                                    @endforeach                                        
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-3">
                                <label for="forma_11" class="form-label">Fecha Cita</label>
                                <input id="forma_11" name="fecha_cita" type="text" class="form-control campo-date" readonly>                                
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-3">
                                <label for="forma_12" class="form-label">Hora Cita</label>
                                <input id="forma_12" name="hora_cita" type="text" class="form-control campo-date" readonly>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="forma_14" class="form-label">Fuente</label>
                                <select id="forma_14" name="select_fuente" class="form-select" disabled>
                                    @foreach ($select_fuente as $item)
                                    <option value="{{$item->co_fuente}}">{{$item->tx_nombre}}</option>    
                                    @endforeach                                        
                                </select>                                
                            </div>
                            <div class="col-12">
                                <label for="forma_13" class="form-label">Nota</label>   
                                <input id="forma_13" name="nota" type="text" class="form-control campo-date" readonly>
                            </div>
                        </div>

                        <div class="progress my-4">
                            <div name="partbar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"
                                aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 text-center">
                                <button id="btn_precalificacion_1" type="button" class="modal-btn">Pre-Calificación</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Actualizada la funcionalidad de limpieza (ahora debe ser a través de la acción de borrar el campo manual)
        document.getElementById('search').addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
            window.location.href = '{{ route('shop') }}';
            }
        });
        
        @if((count($errors)>0) || (session()->exists('error')))        
            const input_action = document.getElementById('input_action').value;
            if(input_action=='new'){
                const newLeadBtn = document.getElementById('newLeadBtn').click();
                
            }else if(input_action=='edit'){
                document.getElementById('editValidateLeadBtn').click();
                //console.log('pruebas'+ $("#code_lead_edit").val());
             }
        @endif

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
                        icon: "warning",
                        title: "Información",
                        text: registroMensaje
                    });
                }
            }
        };

        const partbar = document.getElementsByName('partbar');
        const btnprogressbar1 = document.getElementById('btn1');
        const btnprogressbar2 = document.getElementById('btn2');
        const btnprogressbar3 = document.getElementById('btn3');
        const btnprogressbar4 = document.getElementById('btn4');
        const btnprogressbar5 = document.getElementById('btn5');

        function changeColor() {
            btnprogressbar1.className = "btn btn-primary";
        }

        function changeColor2() {
            if (btnprogressbar1.className === "btn btn-primary") {
                btnprogressbar2.className = "btn btn-primary";
            }
        }

        function changeColor3() {
            if (btnprogressbar1.className === "btn btn-primary" && btnprogressbar2.className === "btn btn-primary") {
                btnprogressbar3.className = "btn btn-primary";
            }
        }

        function changeColor4() {
            if (btnprogressbar1.className === "btn btn-primary" && btnprogressbar2.className === "btn btn-primary" &&
                btnprogressbar3.className === "btn btn-primary") {
                btnprogressbar4.className = "btn btn-primary";
            }
        }

        function changeColor5() {
            if (btnprogressbar1.className === "btn btn-primary" && btnprogressbar2.className === "btn btn-primary" &&
                btnprogressbar3.className === "btn btn-primary" && btnprogressbar4.className === "btn btn-primary") {
                btnprogressbar5.className = "btn btn-primary";
            }
        }

        function check1() {
            for (const boton of partbar) {
                boton.style.width = "20%"

            }
        }

        function check2() {
            if (btnprogressbar1.className === "btn btn-primary") {
                for (const boton of partbar) {
                    boton.style.width = "40%"

                }
            }
        }

        function check3() {
            for (const boton of partbar) {
                if (btnprogressbar1.className === "btn btn-primary" && btnprogressbar2.className === "btn btn-primary") {
                    for (const boton of partbar) {
                        boton.style.width = "60%"

                    }
                }
            }
        }

        function check4() {
            if (btnprogressbar1.className === "btn btn-primary" && btnprogressbar2.className === "btn btn-primary" &&
                btnprogressbar3.className === "btn btn-primary") {
                for (const boton of partbar) {
                    boton.style.width = "80%"
                }
            }
        }

        function check5() {
            if (btnprogressbar1.className === "btn btn-primary" && btnprogressbar2.className === "btn btn-primary" &&
                btnprogressbar3.className === "btn btn-primary" && btnprogressbar4.className === "btn btn-primary") {
                for (const boton of partbar) {
                    boton.style.width = "100%"

                }
            }
        }
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        
            
        //activateOption('opc5');
        //activateOption2('icon-store');        
        
        $('#myModal1').on('shown.bs.modal', function(event){
            const datos = $(event.relatedTarget).attr('data_item');
            const aux_item = datos.split('&');
            
            aux_item.forEach((item, i)=>{
                
                if(i == 14)
                    item = item.trim();
                $("#forma_"+i).val(item);
            });

            //-----------------------------
            const data_proposals = $(event.relatedTarget).attr('data_proposals');

            console.log(data_proposals);
            let band_preca = 0;          
            if (data_proposals == "null" || data_proposals === null || data_proposals === undefined || data_proposals.trim() === ""){
                band_preca = 1;
            }else{
                $("#btn_precla").attr('href', data_proposals);
                $("#btn_precla").removeAttr('disabled');
                $("#btn_precla").html('Editar Pre-Calificación');
            }

            //-------
            const id_cliente = $(event.relatedTarget).attr('data_id_item');
                jQuery.ajax({                        
                    headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    type: 'GET',
                    url: '/store/lead/precualificacion?co_cliente=' + id_cliente,
                    dataType: 'JSON',
                    beforeSend: function() {
                        
                        if(band_preca == 1){
                            $("#btn_precla").prop('disabled', 'disabled');
                            $("#btn_precla").html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="visually-hidden" role="status">Loading...</span>');
                        }
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
                        if(band_preca == 1){
                            $("#btn_precla").attr('href', data.datos[0]);
                        }
                        //------------------------
                        $("#btn_autor").attr('href', data.datos[1]);
                        $("#btn_photo").attr('href', data.datos[2]);
                        $("#btn_verifica").attr('href', data.datos[3]);
                        $("#btn_referred").attr('href', data.datos[4]);                    
                    },
                    complete: function() {
                        if(band_preca == 1){
                            $("#btn_precla").removeAttr('disabled');
                            $("#btn_precla").html('Pre-Calificación');
                        }
                        //---------------------------
                        $("#btn_autor").removeAttr('disabled');
                        $("#btn_autor").html('Formularios de autorización de pago');
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
            //-------        
            
        })

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
            for(let i=0; i<=10; i++){
               $("#forma_"+i).val("");
            }
            myModal1.querySelectorAll(':focus').forEach(element => element.blur());
        })


        
        const divTableLoading = $('#divTableLoading');
        const selectOrder = document.getElementById('select_order');
        const tbodyTabla = document.getElementById('table_leads'); 

        selectOrder.addEventListener('change', () => {
            const order = $('#select_order').val();
            const date1 = $('#startDate').val();
            const date2 = $('#endDate').val();
            if(order>0){                
                jQuery.ajax({                        
                    headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    type: 'GET',
                    url: '/store/lead/ordertable?select_order=' + order + '&startDate=' + date1 + '&endDate=' + date2,
                    dataType: 'JSON',
                    beforeSend: function() {
                        blockTable('leads-table', 'Cargando...');
                    },
                    success: function(data) {
                        // Crear los encabezados para la nueva tabla
                        const headers = [
                            {'title': 'Acciones', 'class': 'col-1'},
                            {'title': 'ID', 'class': 'col-1'},
                            {'title': 'Nombre', 'class': 'col-1'},
                            {'title': 'Apellido', 'class': 'col-1'},
                            {'title': 'Número de teléfono', 'class': 'col-2'},
                            {'title': 'Estado', 'class': 'col-1'},
                            {'title': 'Ciudad', 'class': 'col-1'},
                            {'title': 'Analista', 'class': 'col-1'},
                            {'title': 'Agendador', 'class': 'col-1'},
                            {'title': 'Código Postal', 'class': 'col-1'},
                            {'title': 'Cita', 'class': 'col-1'},
                            {'title': 'Actualización', 'class': 'col-1'}
                        ];
                        
                        // Generar el HTML de la tabla
                        let tableHtml = `
                            <table class="table rounded-3 table-hover">
                                <thead>
                                    <tr class="text-center">
                                        ${headers.map(header => `<th class="text-nowrap ${header.class || ''}">${header.title}</th>`).join('')}
                                    </tr>
                                </thead>
                                <tbody id="table_leads">
                        `;
                        
                        // Generar las filas
                        data.data.forEach(item => {
                            let fechaf2 = '';
                            let fe_ultima_actualizacion = '';
                            if(item.fe_fecha_cita != null && item.fe_fecha_cita != ''){
                                 fechaf2 = moment(item.fe_fecha_cita).format("MM/DD/YYYY");
                            }
                            if(item.fe_ultima_act != null && item.fe_ultima_act != ''){
                                fe_ultima_actualizacion = `<span class="fecha-actualizacion">${moment(item.fe_ultima_act).format("MM/DD/YYYY")}</span>`;
                            }
                            
                            if(item.tx_primer_apellido == null || item.tx_primer_apellido == '')
                                item.tx_primer_apellido = '';
                            if(item.tx_telefono == null || item.tx_telefono == "")
                                item.tx_telefono = "";
                            if(item.estado == null || item.estado == "")
                                item.estado = "";
                            if(item.tx_ciudad == null || item.tx_ciudad == '')
                                item.tx_ciudad = '';
                            if(item.zip == null || item.zip == "")
                                item.zip = "";
                            if(item.tx_email == null || item.tx_email == '')
                                    item.tx_email = '';    
                            if(item.tx_comentario == null || item.tx_comentario == '')
                                    item.tx_comentario = '';            
                            if(item.tx_segundo_nombre == null || item.tx_segundo_nombre == '')
                                    item.tx_segundo_nombre = '';    
                            
                            tableHtml += `
                                <tr class="text-center">
                                    <td>
                                        <div class="action-icons-container">
                                            <div class="action-icon"
                                        data_proposals="${item.tx_url_proposals}" 
                                        data_id_item="${item.co_cliente}"  
                                                data_item="${item.co_cliente}&${item.tx_primer_nombre}&${item.tx_segundo_nombre}&${item.tx_primer_apellido}&${item.estado}&${item.tx_ciudad}&${item.tx_direccion1}&${item.zip}&${item.tx_telefono}&${item.tx_email}&${item.co_ryve_owner}&${fechaf2}&${item.ho_cita}&${item.tx_comentario}&${item.co_fuente}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#myModal1" 
                                                style="cursor:pointer;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                            </div>
                                            <div class="action-icon"
                                        data_id_item="${item.co_cliente}"  
                                        data_item="${item.tx_primer_nombre}&${item.tx_segundo_nombre}&${item.tx_primer_apellido}&${item.tx_email}&${item.tx_ciudad}&${item.co_estado}&${item.zip}&${fechaf2}&${item.tx_direccion1}&${item.tx_direccion2}&${item.tx_telefono}&${item.co_ryve_owner}&${item.ho_cita}&${item.tx_comentario}&${item.co_fuente}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#myModal" 
                                                style="cursor:pointer;"
                                                id="editLeadBtn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${item.co_cliente}</td>
                                    <td>${item.tx_primer_nombre}</td>
                                    <td>${item.tx_primer_apellido}</td>
                                    <td>${item.tx_telefono}</td>
                                    <td>${item.estado}</td>
                                    <td>${item.tx_ciudad}</td>
                                    <td>${item.analista}</td>
                                    <td>${item.agendador}</td>
                                    <td>${item.zip}</td>
                                    <td>${fechaf2}</td>
                                    <td>${fe_ultima_actualizacion}</td>                                    
                                </tr>
                            `;
                        });
                        
                        tableHtml += `
                                </tbody>
                            </table>
                        `;
                        
                        // Reemplazar el contenido de la tabla
                        const tableContainer = document.getElementById('leads-table' + 'Loading');
                        if (tableContainer) {
                            tableContainer.innerHTML = tableHtml;
                            
                            // Reinicializar Feather icons
                            if (typeof feather !== 'undefined') {
                                feather.replace();
                            }
                        }
                    },
                    complete: function() {
                        unblockTable('leads-table');
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
                        })                        
                    }
                });
            }

        });

        $('#myModal').on('shown.bs.modal', function(event){

            const idBtn = $(event.relatedTarget).attr('id');
            const form = $("#form_new_edit");
            const title = $("#titleModalNewEdit");
            $("#validationCustom0").focus();
             
            switch (idBtn) {
                case 'newLeadBtn':
                    
                    title.html('Registrar nuevo Prospecto');
                    form.attr('action', '{{ route('shopNewLead') }}');
                    $("#btnModalNewEdit").html('<i class="fas fa-save me-2"></i>Crear Prospecto');
                    $("#code_lead_edit").val('');                    

                    break;
                case 'editLeadBtn':

                    title.html('Editar Prospecto');
                    form.attr('action', '{{ route('shopEditLead') }}');
                    $("#btnModalNewEdit").html('<i class="fas fa-save me-2"></i>Salvar');
                    $("#input_action").val('edit');

                    //--------------------------------
                    const data_edit = $(event.relatedTarget).attr('data_item');
                    const data_id_item = $(event.relatedTarget).attr('data_id_item');
                    $("#code_lead_edit").val(data_id_item);
                    const aux_item = data_edit.split('&');
                    
                    aux_item.forEach((item, i)=>{

                        if(i==5 || i==11 ){//state o duenio
                            
                            $("#validationCustom"+i).val(item).change();
                           
                                                                    
                        }else{
                            if(item == 'null'){
                                $("#validationCustom"+i).val("");                                
                            }else{
                                $("#validationCustom"+i).val(item);
                            }                            
                        }
                        
                    });
                    //--------------------------------

                    break;
                case 'editValidateLeadBtn':
                    title.html('Editar Prospecto');
                    form.attr('action', '{{ route('shopEditLead') }}');
                    $("#btnModalNewEdit").html('<i class="fas fa-save me-2"></i>Salvar');
                    break;  
                default:
                console.log(`no cincide con la opcion`);
            }
            
        });

        $('#myModal').on('hidden.bs.modal', function(event){
            const form = $("#form_new_edit");
            const title = $("#titleModalNewEdit");
            const button = document.getElementById('btnModalNewEdit');
            
            form.attr('action', '');
            button.classList.remove('btn-disabled');
            $("form select").each(function() { this.selectedIndex = 0 });
            $("form input[type=text], form input[type=date], form input[type=email], form input[type=number]").each(function() { this.value = '' });
            $("#invalidCheck").prop('checked',false);
            title.html('');
            $("#code_lead_edit").val('');
            $(".msg_formerror").html('');
            form.removeClass('was-validated');
        });

        // Implementación simple para el botón de envío del formulario
        document.getElementById('btnModalNewEdit').addEventListener('click', function(event) {
    // Obtener el formulario que contiene el botón
    const form = document.getElementById('form_new_edit');
    
    // Verificar si todos los campos requeridos son válidos
    const requiredInputs = Array.from(form.querySelectorAll('[required]'));
    const allInputsValid = requiredInputs.every(input => input.checkValidity());
    
    // Si hay campos inválidos, no bloquear el botón y detener el envío
    if (!allInputsValid) {
        // Primero quitamos la clase was-validated si ya existe
        // para reiniciar cualquier validación visual previa
        form.classList.remove('was-validated');
        
        // Luego aplicamos la clase para mostrar los mensajes de error actuales
        form.classList.add('was-validated');
        
        // Enfocamos el primer campo inválido para guiar al usuario
        const firstInvalidInput = requiredInputs.find(input => !input.checkValidity());
        if (firstInvalidInput) {
            firstInvalidInput.focus();
        }
        
        // Prevenir el envío del formulario
        event.preventDefault();
        return false;
    }
    
    // Si todos los campos son válidos, bloquear el botón y permitir el envío
    this.classList.add('btn-disabled');
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando...';
    
    // No necesitamos event.preventDefault() aquí porque queremos que el formulario se envíe
    return true;
});
    </script>
    <script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>
    <script>
        const today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            uiLibrary: 'bootstrap5',
            maxDate: today
        });
        $('#endDate').datepicker({
            uiLibrary: 'bootstrap5',
            minDate: $('#startDate').val() 
        });
        //const today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#validationCustom7').datepicker({
            uiLibrary: 'bootstrap5',
            minDate: today
        });

        //validationCustom12
        $('#validationCustom12').timepicker({ 
            uiLibrary: 'bootstrap5',
            footer: true,
            format: 'hh:MM TT',
            mode: 'ampm',
            size:'default'
        });
        
        // Mostrar/ocultar búsqueda avanzada al cargar la página si hay datos de búsqueda
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            // Si hay fechas seleccionadas, mostrar la búsqueda avanzada
            if (startDate || endDate) {
                const advancedSearchCollapse = new bootstrap.Collapse(document.getElementById('advancedSearchCollapse'), {
                    toggle: true
                });
            }
        });
    </script>    
    <script>
        $(document).ready(function() {
            $('#btn_precalificacion_1').click(function(e) {
                e.preventDefault();
                var codigoCliente = $('#forma_0').val();
                
                // Redirigir a la ruta con el parámetro
                window.location.href = `/forms/general-info/create?co_cliente=${codigoCliente}`;
            });
        });    
    </script>
@endpush
