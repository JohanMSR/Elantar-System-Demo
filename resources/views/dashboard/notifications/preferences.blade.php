@extends('layouts.master')

@section('title')
    Preferencias de Notificaciones - @lang('translation.business-center')
@endsection

@push('css')
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
        
        /* Estilos para la tarjeta de preferencias */
        .preferences-card {
            border: none;
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: var(--transition-normal);
        }
        
        .preferences-card .card-header {
            background: linear-gradient(to right, #13c0e6, #4687e6);
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .preferences-card .card-body {
            padding: 1.75rem;
        }
        
        /* Estilo para los switches */
        .form-check-input {
            transform: scale(1.5);
            margin-top: 0.2rem;
        }
        
        .form-switch {
            padding-left: 0 !important;
            margin-bottom: 1.25rem !important;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            transition: var(--transition-fast);
        }
        
        .form-switch:hover {
            background-color: rgba(19, 192, 230, 0.05);
        }

        .form-check-label {
            font-size: 1.1rem;
            margin-left: 0.5rem;
            font-weight: 500;
            color: var(--color-text);
        }
        
        /* Centro el botón de guardar */
        .save-button-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    @php
        $preferencesTitle = 'Preferencias de Notificaciones';
    @endphp
    <x-page-header :title="$preferencesTitle" icon="bell-off" />
    <br>
    
    <div class="container-fluid fade-in">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Fila para el botón de volver -->
        <div class="row">
            <div class="col-12">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('notifications.index') }}" class="advanced-search-btn">
                        <i data-feather="arrow-left" class="me-2" style="width: 16px; height: 16px;"></i>
                        Volver a notificaciones
                    </a>
                </div>
            </div>
        </div><!-- /Fin fila botón volver -->
        
        <!-- Fila para la tarjeta de preferencias -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="preferences-card card">
                    <div class="card-header d-flex align-items-center">
                        <i data-feather="settings" class="me-2"></i>
                        <span>Configuración de Notificaciones</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">Selecciona los tipos de notificaciones que deseas recibir:</p>
                        
                        <form action="{{ route('notifications.updatePreferences') }}" method="POST" id="preferencesForm">
                            @csrf
                            @method('PUT')

                            @foreach ($notificationTypes as $type)
                            <div class="form-check form-switch mb-3 d-flex align-items-center justify-content-between">
                                <label class="form-check-label text-capitalize"
                                    for="notificationType{{ $type->co_tiponoti }}">
                                    {{ $type->tx_descripcion }}
                                </label>
                                <input class="form-check-input" type="checkbox"
                                    id="notificationType{{ $type->co_tiponoti }}"
                                    name="notification_types[]" value="{{ $type->co_tiponoti }}"
                                    {{ isset($userPreferences[$type->co_tiponoti]) && $userPreferences[$type->co_tiponoti]->bo_visualizar ? 'checked' : '' }}>
                            </div>
                            @endforeach

                            <div class="save-button-container">
                                <button type="submit" class="advanced-search-btn" id="saveButton">
                                    <i data-feather="save" class="me-2" style="width: 16px; height: 16px;"></i>
                                    Guardar Preferencias
                                </button>
                                <div id="loadingSpinner" class="spinner-border text-primary ms-3" role="status" style="display: none;">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </div>
                        </form>
                    </div><!-- /Fin card-body -->
                </div><!-- /Fin preferences-card -->
            </div><!-- /Fin col -->
        </div><!-- /Fin row preferencias -->
    </div><!-- /Fin container-fluid -->
@endsection

@push('scripts')
    <script>
        document.getElementById('preferencesForm').addEventListener('submit', function() {
            document.getElementById('saveButton').style.display = 'none';
            document.getElementById('loadingSpinner').style.display = 'block';
        });
        
        // Asegurarse de que feather está disponible antes de usarlo
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        activateOption('notification');
    </script>
@endpush