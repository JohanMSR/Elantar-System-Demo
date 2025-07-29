@extends('layouts.master')

@section('title')
    Notificaciones - @lang('translation.business-center')
@endsection

@push('css')
    <style>
        .notification-container {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            max-height: 180vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--color-primary) #f0f7ff;
            margin-bottom: 2rem;
        }

        .notification-container::-webkit-scrollbar {
            width: 8px;
        }

        .notification-container::-webkit-scrollbar-track {
            background: #f0f7ff;
            border-radius: 4px;
        }

        .notification-container::-webkit-scrollbar-thumb {
            background-color: var(--color-primary);
            border-radius: 4px;
            border: 2px solid #f0f7ff;
        }

        .notification-item {
            position: relative;
            padding: 1.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition-normal);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .notification-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: transparent;
            transition: var(--transition-normal);
        }

        .notification-item:hover {
            background-color: #f8fafc;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .notification-unread {
            background-color: #f0f7ff;
        }

        .notification-unread::before {
            background: #3b82f6;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.3);
        }

        .notification-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e8f2ff;
            margin-right: 1.25rem;
            box-shadow: 0 2px 8px rgba(41, 76, 127, 0.1);
            flex-shrink: 0;
        }

        .notification-icon {
            width: 22px;
            height: 22px;
            color: #294C7F;
            stroke-width: 2;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            color: var(--color-dark);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: -0.01em;
        }

        .notification-message {
            color: var(--color-text);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed rgba(0, 0, 0, 0.1);
        }

        .notification-type {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            background: rgba(19, 192, 230, 0.1);
            border-radius: 6px;
            color: var(--color-dark);
            font-size: 0.813rem;
            font-weight: 500;
            gap: 0.75rem;
        }

        .notification-type i {
            width: 14px;
            height: 14px;
        }

        .notification-time {
            color: var(--color-light-text);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            margin: 0;
            font-weight: 400;
            line-height: 1;
            gap: 0.375rem;
        }

        .mark-read-btn {
            color: #3b82f6;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border: 1.5px solid #3b82f6;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: transparent;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);
        }

        .mark-read-btn:hover {
            color: #fff;
            background: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            transform: translateY(-1px);
        }

        .mark-read-btn i {
            margin-right: 0.5rem;
        }

        .empty-notifications {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-notifications i {
            width: 56px;
            height: 56px;
            color: var(--color-light-text);
            margin-bottom: 1.25rem;
            opacity: 0.8;
        }

        .empty-notifications p {
            color: var(--color-light-text);
            font-size: 1.125rem;
            font-weight: 500;
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
        
        .advanced-search-btn:hover {
            filter: brightness(1.05);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: #fff;
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

        /* Animación para nuevas notificaciones */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-item {
            animation: slideIn 0.3s ease-out forwards;
        }

        /* Estilos para la paginación */
        .pagination {
            margin: 2rem 0;
            padding: 1rem;
            justify-content: center;
            gap: 0.5rem;
        }

        .page-link {
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            border: none;
            color: #294C7F;
            background: #e8f2ff;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(41, 76, 127, 0.1);
        }

        .page-link:hover {
            background: #294C7F;
            color: #fff;
            box-shadow: 0 4px 12px rgba(41, 76, 127, 0.2);
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background: #294C7F;
            color: #fff;
            box-shadow: 0 4px 12px rgba(41, 76, 127, 0.2);
        }

        /* Ajustes de espaciado y alineación */
        .notification-list {
            padding: 0.5rem;
        }

        .d-flex.justify-content-between {
            gap: 1.5rem;
        }

        /* Ajuste para pantallas pequeñas */
        @media (max-width: 576px) {
            .notification-message {
                font-size: 0.95rem;
            }

            .mark-read-btn {
                padding: 0.5rem 1rem;
                font-size: 0.813rem;
            }

            .notification-icon-wrapper {
                width: 42px;
                height: 42px;
                margin-right: 1rem;
            }
        }
        
        /* Fade-in animation como en home.blade.php */
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Estilos para el dashboard card como en users/index.blade.php */
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
    </style>
@endpush

@section('content')
    @php
        $notificationTitle = __('Notificaciones');
    @endphp
    <x-page-header :title="$notificationTitle" icon="bell" />
    <br>
    <div class="row g-4">
        <div class="col-12 fade-in">
            <!-- Encabezado con acciones -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">{{ __('Todas las notificaciones') }}</h5>
                <a href="{{ route('notifications.preferences') }}" class="advanced-search-btn">
                    <i data-feather="settings" class="me-2" style="width: 16px; height: 16px;"></i>
                    {{ __('Preferencias') }}
                </a>
            </div>
            
            <!-- Contenedor de notificaciones -->
            <div class="notification-container" id="notification-container">
                <div class="notification-list" id="notification-list">
                    @include('dashboard.notifications.partials.notifications', ['notifications' => $notifications])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                const adjustedDate = new Date(date.getTime() + (60 * 60 * 1000));
                
                // Usar un formato más compatible entre navegadores
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true,
                    timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
                };
                
                const timeStr = adjustedDate.toLocaleTimeString('en-US', options);
                const dateStr = adjustedDate.toLocaleDateString('en-US', {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric',
                    timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
                });
                
                return `${timeStr} - ${dateStr}`;
            } catch (error) {
                console.error('Error formatting date:', error);
                return dateString; // Fallback al string original si hay error
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Content Loaded');
            
            // Formatear fechas iniciales
            const timeSpans = document.querySelectorAll('[data-time]');
            console.log(`Found ${timeSpans.length} time spans to format`);
            
            timeSpans.forEach(span => {
                try {
                    const dateString = span.dataset.time;
                    span.textContent = formatDate(dateString);
                } catch (error) {
                    console.error('Error formatting time span:', error);
                }
            });

            let page = 1;
            let isLoading = false;
            const container = document.getElementById('notification-container');
            const list = document.getElementById('notification-list');

            if (!container || !list) {
                console.error('Required elements not found:', { container: !!container, list: !!list });
                return;
            }

            function isNearBottom() {
                const threshold = 100;
                const scrollPosition = container.scrollHeight - container.scrollTop - container.clientHeight;
                const isNear = scrollPosition <= threshold;
                console.log('Scroll position:', scrollPosition, 'Is near bottom:', isNear);
                return isNear;
            }

            async function loadMoreNotifications(page) {
                if (isLoading) {
                    console.log('Already loading, skipping request');
                    return;
                }
                
                console.log('Loading page:', page);
                isLoading = true;

                try {
                    const url = `{{ url('/notifications?page=') }}${page}`;
                    console.log('Fetching from:', url);
                    
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.text();
                    console.log('Received data length:', data.length);
                    
                    // Crear un contenedor temporal para el HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;
                    
                    // Buscar el contenido de notificaciones
                    const newNotifications = tempDiv.querySelector('#notification-list');
                    
                    if (!newNotifications) {
                        console.error('Notification list not found in response');
                        return;
                    }
                    
                    const newContent = newNotifications.innerHTML.trim();
                    if (!newContent) {
                        console.log('No new notifications to add');
                        return;
                    }
                    
                    console.log('Found new notifications, updating timestamps');
                    
                    // Crear un contenedor temporal para las nuevas notificaciones
                    const tempNotifications = document.createElement('div');
                    tempNotifications.innerHTML = newContent;
                    
                    // Actualizar timestamps
                    const newTimeSpans = tempNotifications.querySelectorAll('[data-time]');
                    newTimeSpans.forEach(span => {
                        try {
                            const dateString = span.dataset.time;
                            span.textContent = formatDate(dateString);
                        } catch (error) {
                            console.error('Error formatting new time span:', error);
                        }
                    });
                    
                    // Insertar el contenido actualizado
                    list.insertAdjacentHTML('beforeend', tempNotifications.innerHTML);
                    
                    // Reinicializar Feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    
                    console.log('Successfully added new notifications');
                    
                } catch (error) {
                    console.error('Error loading notifications:', error);
                } finally {
                    isLoading = false;
                }
            }

            // Event listener para el scroll con debounce
            let scrollTimeout;
            container.addEventListener('scroll', function () {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    if (isNearBottom()) {
                        page++;
                        loadMoreNotifications(page);
                    }
                }, 150);
            });
        });

        // Asegurarse de que feather está disponible antes de usarlo
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        activateOption('notification');
    </script>
@endpush