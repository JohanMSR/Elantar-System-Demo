@extends('layouts.master')

@section('title')
    @lang('translation.customers_title') - @lang('translation.business-center')
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .btnRango {
            background-color: #318ce7;
            margin-bottom: 5px;
        }

        .campo-date {
            margin-bottom: 5px;
        }

        .tabla-informe {
            display: block;
            overflow: auto;
            width: 300px;
            /* Ajusta el ancho según tus necesidades */
        }

        .chat-container {
            height: 500px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            /* Usa flexbox para alinear la imagen y el texto */
            align-items: center;
            /* Centra verticalmente la imagen y el texto */
        }

        .message-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message-content {
            /* No necesitas estilos específicos aquí ya que flexbox se encarga de la alineación */
        }

        .message-sender {
            font-weight: bold;
        }

        .my-message {
            justify-content: flex-end;
            /* Alinea el mensaje a la derecha */
        }

        .my-message .message-image {
            margin-right: 0;
            margin-left: 10px;
            order: 1;
            /* Coloca la imagen después del texto */
        }

        .my-message .message-sender {
            color: rgba(0, 0, 255, 0.363);
        }

        .other-message .message-sender {
            color: rgba(0, 128, 0, 0.322);
        }

        .input-group {
            margin-top: 10px;
        }

        .send-icon {
            margin-left: 5px;
        }

        .table-code {
            width: 80px; /* Ajusta el ancho según tus necesidades */
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
            height: 38px;
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
            height: 38px;
        }
        
        .search-header-container .input-group .btn-light:first-of-type {
            border-radius: 0;
        }
        
        .search-header-container .input-group .btn-light:last-of-type {
            border-radius: 0 30px 30px 0;
        }
        
        /* Ajuste responsive para el buscador */
        @media (max-width: 992px) {
            .search-header-container {
                width: 100%;
                margin-top: 15px;
                order: 2;
            }
        }
        
        /* Ajustes para pantallas pequeñas */
        @media (max-width: 576px) {
            .search-header-container .input-group {
                flex-wrap: nowrap;
            }
            
            .search-header-container .input-group .form-control,
            .search-header-container .input-group .btn-light {
                height: 38px;
                font-size: 0.875rem;
            }
            
            .search-header-container .input-group .btn-light {
                min-width: 44px;
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
            margin-bottom: 0.5rem;
            position: relative;
            overflow: visible;
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
        
        /* Estilos para los iconos feather */
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
    }else if(session()->exists('error_f')){
        $bandRregistro = '2';
        $registroMensaje = session('error_f');
        session()->forget('error_f');
    }
    else if($errors->any()){
        $bandRregistro = '2';
        $registroMensaje = $errors->first();
    }
    $teamsTitle = "Gestión de Negocio";
@endphp

<div class="container-fluid bg-light">
    <x-page-header :title="$teamsTitle" icon="users">
        <div class="search-header-container">
            <form action="{{ route('dashboard.team.tsearch') }}" method="GET" class="needs-validation mb-0" novalidate>
                @csrf
                <div class="input-group">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Buscar" onkeypress="if(event.keyCode == 13) { event.preventDefault(); this.form.submit(); }">
                    <button type="submit" class="btn btn-light d-flex align-items-center justify-content-center">
                        <i data-feather="search" class="icon-sm"></i>
                    </button>
                    <a href="{{ route('dashboard.team') }}" class="btn btn-light d-flex align-items-center justify-content-center">
                        <i data-feather="x" class="icon-sm"></i>
                    </a>
                </div>
            </form>
        </div>
    </x-page-header>
    <br>

    <div class="row mb-4">
        <div class="col-12 pt-2">
            <button class="advanced-search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearchCollapse" aria-expanded="false" aria-controls="advancedSearchCollapse">
                <i class="fas fa-search-plus"></i> Búsqueda avanzada
            </button>
        </div>
    </div>

    <div class="row mb-4 collapse" id="advancedSearchCollapse">
        <div class="col-12">
            <div class="card rounded-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('dashboard.team.tfilter') }}" method="GET" class="needs-validation" novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4 mb-3">
                                    <label for="select_filter" class="form-label">Filtrar Por:</label>
                                    <select id="select_filter" name="select_filter" class="form-select" aria-label="Select">
                                        <option value=""></option>
                                        @if(isset($status_app))
                                            @foreach($status_app as $estado)
                                                <option value="{{$estado->co_estatus_aplicacion}}"
                                                    {{ isset($select_filter) && $select_filter == $estado->co_estatus_aplicacion ? 'selected' : '' }}>
                                                    {{ $estado->tx_nombre }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="select_order" class="form-label">Ordenar Por:</label>
                                    <select id="select_order" class="form-select" aria-label="Select">
                                        <option value=""></option>
                                        <option value="1" {{ request('order') == '1' ? 'selected' : '' }}>Código de aplicación</option>
                                        <option value="2" {{ request('order') == '2' ? 'selected' : '' }}>Fecha de creación</option>
                                        <option value="3" {{ request('order') == '3' ? 'selected' : '' }}>Fecha de actualización</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                      {{--  <button class="btn btn-primary flex-grow-1" type="submit">Aplicar filtros</button>--}}
                                        <a href="{{ route('dashboard.team') }}" class="btn btn-secondary flex-grow-1" id="btn-reset">Reiniciar</a>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
    <div class="row mb-4">
        <div class="col-12">
            @php
                $headers = [
                    ['title' => 'Actions', 'class' => 'width-80'],
                    ['title' => 'Code Application', 'class' => 'table-code'],
                    ['title' => 'Status', 'class' => 'min-width-150'],
                    ['title' => 'Date Created', 'class' => 'min-width-80'],
                    ['title' => 'Primary Customer Full Name', 'class' => 'min-width-150'],
                    ['title' => 'Primary Customer Email', 'class' => 'min-width-150'],
                    ['title' => 'Address: Street 1', 'class' => 'min-width-150'],
                    ['title' => 'Address: State/Region', 'class' => 'min-width-150'],
                    ['title' => 'Primary Analyst', 'class' => 'min-width-150'],
                    ['title' => 'Secondary Analyst', 'class' => 'min-width-150'],
                    ['title' => 'Primary Customer First Name', 'class' => 'min-width-100'],
                    ['title' => 'Primary Customer Last Name', 'class' => 'min-width-100'],
                    ['title' => 'Secondary Customer First Name', 'class' => 'min-width-150'],
                    ['title' => 'Secondary Customer Last Name', 'class' => 'min-width-150'],
                    ['title' => 'Reference 1 Name', 'class' => 'min-width-150'],
                    ['title' => 'Reference 2 Name', 'class' => 'min-width-150'],
                    ['title' => 'Total System Price', 'class' => 'min-width-150'],
                    ['title' => 'How long here', 'class' => 'min-width-150'],
                    ['title' => 'Record ID', 'class' => 'min-width-80'],
                    ['title' => 'Date Updated', 'class' => 'min-width-80']
                ];
                
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
                tableId="team-table"
                emptyMessage="No hay Aplicaciones disponibles">
                
                @foreach($paginatedData as $data)
                    <tr>
                        <td class="width-80">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <div class="text-center">
                                    <a href="{{ route('dashboard.team-details') . '?co_aplicacion=' . $data->co_aplicacion }}" 
                                        class="btn btn-transparent">
                                        <i data-feather="eye"></i>                                       
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('forms.general-info.edit', ['co_aplicacion' => $data->co_aplicacion,'urldestination' => 'team']) }}" 
                                        class="btn btn-transparent">
                                        <i data-feather="edit"></i>
                                    </a>
                                </div>
                                <div class="text-center">
                                    @if($data->notifications_not_seen > 0)
                                        <span class="badge bg-danger" 
                                            id="badge_{{ $data->co_aplicacion }}" 
                                            style="cursor: pointer;" 
                                            title="Ver notificaciones"
                                            data_co_aplicacion="{{ $data->co_aplicacion }}">
                                            {{$data->notifications_not_seen}}
                                        </span>
                                    @elseif($data->notifications_seen > 0)
                                            <div class="text-center bell" style="cursor: pointer;" 
                                                data_co_aplicacion="{{$data->co_aplicacion}}"
                                                >
                                                <i data-feather="bell" class="menu-icon"></i>
                                            </div>                                        
                                    @endif
                                </div>
                            </div>                                    
                        </td>
                        <td class="table-code" style="text-align: center; vertical-align: middle;">{{ $data->co_aplicacion }}</td>                                                
                        <td class="min-width-150">{{ $data->{'Estado'} }}</td>
                        <td class="min-width-80" style="text-align: center; vertical-align: middle;">{{ $data->{'Date Created'} }}</td>
                        <td class="min-width-150">{{ $data->{'Primary Customer Full Name'} }}</td>
                        <td class="min-width-150">{{ $data->{'Primary Customer Email'} }}</td>
                        <td class="min-width-150">{{ $data->{'Address: Street 1'} }}</td>
                        <td class="min-width-150">{{ $data->{'Address: State/Region'} }}</td>
                        <td class="min-width-150">{{ $data->{'Primary Analyst'} }}</td>
                        <td class="min-width-150">{{ $data->{'Secondary Analyst'} }}</td>
                        <td class="min-width-150">{{ $data->{'Primary Customer First Name'} }}</td>
                        <td class="min-width-150">{{ $data->{'Primary Customer Last Name'} }}</td>
                        <td class="min-width-150">{{ $data->{'Secondary Customer First Name'} }}</td>
                        <td class="min-width-150">{{ $data->{'Secondary Customer Last Name'} }}</td>
                        <td class="min-width-150">{{ $data->{'Reference 1 Name'} }}</td>
                        <td class="min-width-150">{{ $data->{'Reference 2 Name'} }}</td>
                        <td class="min-width-150">${{ $data->{'Total System Price'} }}</td>
                        <td class="min-width-150">{{ $data->{'How Long Here'} }}</td>
                        <td class="min-width-80">{{ $data->{'Record ID'} }}</td>
                        <td class="min-width-80" style="text-align: center; vertical-align: middle;">{{ $data->{'Date Updated'} }}</td>
                    </tr>
                @endforeach
                <x-slot name="pagination">
                    {{ $paginatedData->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
                </x-slot>
            </x-data-table>
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

@endsection

@push('scripts')
    <script>
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
    </script>
    <script>
        //activateOption('opc6');
        //activateOption2('icon-customers');
    </script>
@endpush

@push('scripts')
<script>
        const bandRregistro = "{{ $bandRregistro }}";
        const registroMensaje = "{{ $registroMensaje }}";
        window.onload = function() {
               if (bandRregistro == "2") {
                    Swal.fire({
                        icon: "warning",
                        title: "Mensaje",
                        text: registroMensaje
                    });
                }else if(bandRregistro == "1"){
                    Swal.fire({
                        icon: "success",
                        title: "Mensaje",
                        text: registroMensaje
                    });
                }
                
                // Mostrar/ocultar búsqueda avanzada al cargar la página si hay filtros aplicados
                const selectFilter = document.getElementById('select_filter').value;
                const selectOrder = document.getElementById('select_order').value;
                
                // Si hay filtros aplicados, mostrar la búsqueda avanzada
                if (selectFilter || (selectOrder && selectOrder !== '')) {
                    const advancedSearchCollapse = new bootstrap.Collapse(document.getElementById('advancedSearchCollapse'), {
                        toggle: true
                    });
                }
            }        
        document.getElementById('select_order').addEventListener('change', function() {
            const order = this.value;
            const url = new URL(window.location.href);
            const page = url.searchParams.get('page') || 1;
            const select_filter = document.getElementById('select_filter').value;

            // Crea una nueva URL con la ruta relativa y conserva todos los demás parámetros
            const newUrl = new URL('/dashboard/team/order', url.origin); // Ruta relativa
            newUrl.searchParams.set('order', order);
            newUrl.searchParams.set('page', page);
            newUrl.searchParams.set('select_filter', select_filter);
            // Conserva otros parámetros (opcional)
            for (const [key, value] of url.searchParams.entries()) {
                if (key !== 'order' && key !== 'page') {
                    newUrl.searchParams.set(key, value);
                }
            }

            window.location.href = newUrl.href;
        }); 
        
          
</script>       
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script para asegurar que la búsqueda funcione con Enter
        const searchForm = document.querySelector('.search-header-container form');
        const searchInput = document.getElementById('search');
        
        if (searchForm && searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        }
        
        // Seleccionar el elemento select
        const selectFilter = document.getElementById('select_filter');
        
        // Agregar el evento change
        selectFilter.addEventListener('change', function() {
            
            if(selectFilter.value != ''){
                // Obtener el formulario padre del select
                this.form.submit();
            }
        });
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
    $('#notificationsModalBody').empty(); // Limpiar contenido al cerrars    
});
});
</script>
@endpush
