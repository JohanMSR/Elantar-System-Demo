@extends('layouts.master')

@section('title')
    Administración de usuarios - Centro de Negocios
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #13c0e6;
            --color-primary-dark: #10a5c6;
            --color-secondary: #4687e6;
            --color-secondary-dark: #3472c9;
            --color-accent: #8ce04f;
            --color-accent-dark: #7ac843;
            --color-dark: #162d92;
            --color-text: #495057;
            --color-light-text: #6c757d;
            --color-border: #eaeaea;
            --color-input-bg: #ffffff;
            --color-input-bg-hover: #f8f9fa;
            --shadow-card: 0 12px 30px rgba(19, 192, 230, 0.25);
            --shadow-btn: 0 5px 15px rgba(70, 135, 230, 0.3);
            --shadow-input: 0 2px 4px rgba(0, 0, 0, 0.05);
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
            --transition-fast: all 0.2s ease;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 15px;
        }
        
        .dashboard-card {
            background-color: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            padding: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition-normal);
            margin-bottom: 2rem;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(19, 192, 230, 0.35);
        }

        .dashboard-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--color-primary), transparent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s ease;
        }

        .dashboard-card:hover::after {
            transform: scaleX(1);
        }
        
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.6s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-5px); }
            100% { opacity: 1; transform: translateY(0); }
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
        
        .search-header-container .input-group .btn-light {
            background-color: white;
            color: var(--color-primary);
            border-color: #fff;
            border-radius: 0 30px 30px 0;
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
            box-shadow: 0 7px 20px rgba(70, 135, 230, 0.4);
            color: #fff;
            text-decoration: none;
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
                margin-bottom: 0.5rem;
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
        
        .action-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(19, 192, 230, 0.1);
            color: var(--color-primary);
            border-radius: 50%;
            transition: var(--transition-normal);
            cursor: pointer;
        }
        
        .action-icon:hover {
            background-color: var(--color-primary);
            color: white;
            transform: translateY(-3px);
        }
        
        .action-icon.edit-icon {
            background-color: rgba(70, 135, 230, 0.1);
            color: var(--color-secondary);
        }
        
        .action-icon.edit-icon:hover {
            background-color: var(--color-secondary);
            color: white;
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
        
        .list-group-item {
            border-color: var(--color-border);
            transition: var(--transition-fast);
        }
        
        .list-group-item:hover {
            background-color: rgba(19, 192, 230, 0.05);
        }
    </style>
@endpush

@section('content')
@php
    $pageTitle = 'Administración de Usuarios';
@endphp
<div class="container-fluid fade-in">
    <x-page-header :title="$pageTitle" icon="users">
        <div class="search-header-container">
            <form class="needs-validation mb-0" method="GET" action="{{ route('users.index') }}" novalidate>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar usuario..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-light">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </x-page-header>
    <br>
    <div class="dashboard-card">
        <div class="mb-4 pt-2 main-buttons-container">
            <a href="{{ route('users.create') }}" class="advanced-search-btn">
                <i class="fas fa-user-plus me-2"></i> Crear Nuevo Usuario
            </a>
            
            <a href="{{ route('users.index') }}" class="advanced-search-btn">
                <i class="fas fa-sync-alt me-2"></i> Reiniciar Búsqueda
            </a>
            </div>

        @php
            // Definimos los encabezados para la tabla
            $headers = [
                ['title' => 'Acciones', 'class' => 'col-1'],
                ['title' => 'ID', 'class' => 'col-1'],
                ['title' => 'Nombre', 'class' => 'col-1'],
                ['title' => 'Apellido', 'class' => 'col-1'],
                ['title' => 'Teléfono', 'class' => 'col-1'],
                ['title' => 'Email', 'class' => 'col-2'],
                ['title' => 'Rol', 'class' => 'col-1'],
                ['title' => 'Fecha Creación', 'class' => 'col-1'],
                ['title' => 'Estado', 'class' => 'col-1']
            ];
        @endphp
        
        <x-data-table 
            :headers="$headers" 
            :data="$paginatedData" 
            :perPage="$paginatedData->perPage()"
            :currentPage="$paginatedData->currentPage()" 
            :totalItems="$paginatedData->total()"
            tableId="users-table"
            :showPerPageSelector="false"
            emptyMessage="No hay usuarios disponibles">
            
            @foreach($paginatedData as $data)
                <tr>
                    <td>
                        <div class="action-icons-container">
                            <a href="{{ route('users.detail', ['co_usuario' =>$data->Record_ID]) }}" class="action-icon edit-icon" title="Ver detalle">
                                <i data-feather="eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $data->Record_ID) }}" class="action-icon edit-icon" title="Editar usuario">
                                <i data-feather="edit"></i>
                            </a>                            
                        </div>
                    </td>
                    <td>{{ $data->Record_ID }}</td>
                    <td>{{ $data->First_Name }}</td>
                    <td>{{ $data->Last_Name }}</td>
                    <td>{{ $data->Mobile_Phone }}</td>
                    <td>{{ $data->Email }}</td>
                    <td>{{ $data->Rol_User }}</td>
                    <td>{{ date('d/m/Y', strtotime($data->Date_Created)) }}</td>
                    <td>
                        <span class="badge {{ $data->User_Status == 'Activo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $data->User_Status }}
                        </span>
                    </td>
                </tr>
            @endforeach
            
            <x-slot name="pagination">
                {{ $paginatedData->appends(['sort' => $sortField, 'order' => $sortOrder])->links('pagination::bootstrap-5') }}
            </x-slot>
        </x-data-table>
        </div>
    </div>

    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
            feather.replace();
            }
            
            @if(session('success'))
                Swal.fire({
                    title: 'Éxito!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#13c0e6'
                });
            @endif
            
            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#13c0e6'
                });
            @endif
        });
        </script>
    @endpush
@endsection