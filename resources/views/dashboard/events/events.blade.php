@extends('layouts.master')

@section('title')
    @lang('translation.event_title') - @lang('translation.business-center')
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <style>
        :root {
            --color-primary: #13c0e6;
            --color-primary-dark: #10a5c6;
            --color-secondary: #4687e6;
            --color-secondary-dark: #3472c9;
            --color-accent: #8ce04f;
            --color-accent-dark: #7ac843;
            --color-warning: #FFA500;
            --color-danger: #FF4D4F;
            --color-dark: #162d92;
            --color-text: #495057;
            --color-light-text: #6c757d;
            --color-border: #eaeaea;
            --color-input-bg: #ffffff;
            --color-input-bg-hover: #f8f9fa;
            --shadow-card: 0 8px 20px rgba(19, 192, 230, 0.1);
            --shadow-btn: 0 5px 15px rgba(70, 135, 230, 0.2);
            --transition-normal: all 0.3s ease;
            --transition-fast: all 0.2s ease;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-circle: 50%;
        }
        
        .container-fluid {
            padding: 0rem;
            max-width: 1500px;
            margin: 0 auto;
            position: relative;
        }
        
        .main-content {
            background-color: #f1f8ff;
            padding: 1.5rem;
            min-height: 85vh;
        }
        
        /* Eventos */
        .event-card {
            background-color: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            margin-bottom: 0;
            overflow: hidden;
            transition: var(--transition-fast);
            height: 100%;
        }

        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(19, 192, 230, 0.2);
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
            color: white;
        }

        .event-title {
            font-family: "MontserratBold";
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: white;
        }

        .event-date {
            font-size: 0.8rem;
            opacity: 0.9;
            color: white;
        }

        .event-date i {
            color: rgba(255, 255, 255, 0.85);
        }

        .event-image-container {
            position: relative;
        }

        .event-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .event-image-fallback {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f8ff;
        }

        .event-image-fallback img {
            max-width: 50%;
            max-height: 50%;
        }

        .event-content {
            padding: 1rem;
            font-size: 0.9rem;
            color: var(--color-text);
        }
        
        .event-content p {
            max-height: 100px;
            overflow-y: auto;
            margin-bottom: 0.75rem;
            scrollbar-width: thin;
            scrollbar-color: var(--color-primary) #f1f8ff;
        }
        
        .event-content p::-webkit-scrollbar {
            width: 6px;
        }
        
        .event-content p::-webkit-scrollbar-track {
            background: #f1f8ff;
        }
        
        .event-content p::-webkit-scrollbar-thumb {
            background-color: var(--color-primary);
            border-radius: 20px;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #f8f9fa;
            border-top: 1px solid var(--color-border);
        }

        .event-button {
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition-fast);
            display: inline-flex;
            align-items: center;
        }

        .event-button:hover {
            background-color: var(--color-primary-dark);
        }
        
        .event-button i {
            margin-right: 5px;
        }

        .event-time {
            font-size: 0.8rem;
            color: var(--color-light-text);
        }
        
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
        
        /* Estilos para el modal y slider */
        .event-modal .modal-content {
            border-radius: var(--radius-md);
            overflow: hidden;
            border: none;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            min-height: 80vh;
        }
        
        .event-modal .modal-header {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
            color: white;
            border-bottom: none;
            padding: 1.2rem 1.5rem;
        }
        
        .event-modal .modal-title {
            font-family: "MontserratBold";
            font-size: 1.25rem;
        }
        
        .event-modal .modal-body {
            padding: 1.5rem;
        }
        
        .event-modal .modal-body-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .event-modal .modal-dialog {
            max-width: 80%;
            margin: 1.75rem auto;
            height: auto;
        }
        
        .event-modal .modal-footer {
            border-top: 1px solid var(--color-border);
            padding: 1rem 1.5rem;
        }
        
        .swiper {
            width: 100%;
            height: 500px;
            margin-bottom: 1.5rem;
            border-radius: var(--radius-md);
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        
        .swiper-slide img {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
        }
        
        .swiper-slide video {
            max-width: 100%;
            max-height: 500px;
        }
        
        .swiper-pagination-bullet-active {
            background-color: var(--color-primary);
        }
        
        .swiper-button-next, 
        .swiper-button-prev {
            color: rgba(19, 192, 230, 0.5);
            transition: var(--transition-fast);
        }
        
        .swiper-button-next:hover, 
        .swiper-button-prev:hover {
            color: var(--color-primary);
        }
        
        .event-description {
            margin-top: 1rem;
            max-height: 300px;
            overflow-y: auto;
            padding-right: 0.5rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--color-text);
            flex: 1;
        }
        
        .event-meta {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--color-border);
            display: flex;
            justify-content: flex-start;
            color: var(--color-light-text);
            font-size: 0.85rem;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            
            .swiper {
                height: 300px;
            }
            
            .swiper-slide img,
            .swiper-slide video {
                max-height: 300px;
            }
            
            .event-modal .modal-dialog {
                max-width: 100%;
                margin: 0;
                height: 100%;
            }
            
            .event-modal .modal-content {
                height: 100vh;
                border-radius: 0;
                display: flex;
                flex-direction: column;
            }
            
            .event-modal .modal-body {
                flex: 1;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }
            
            .event-modal.modal {
                padding: 0 !important;
            }
            
            .event-description {
                max-height: none;
                flex: 1;
                overflow-y: auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @php
            $eventTitle = __('translation.event_title');
        @endphp
        <x-page-header :title="$eventTitle" icon="calendar" />
        
        <div class="main-content">
            <div class="row g-4 fade-in">
                @php
                    use Carbon\Carbon;
                @endphp
                
                @if(isset($eventos2) && count($eventos2) > 0)
                    @foreach($eventos2 as $evento)
                        <div class="col-md-6 col-lg-4">
                            <div class="event-card">
                                <div class="event-header">
                                    <div>
                                        <h5 class="event-title">{{ $evento->tx_titulo }}</h5>
                                        <span class="event-date">{{ Carbon::parse($evento->fe_registro)->format('d F - Y | H:i') }}</span>
                                    </div>
                                </div>
                                
                                @if(isset($evento->adjuntos) && count($evento->adjuntos) > 0)
                                    <img src="{{ asset($evento->adjuntos[0]->tx_url_adj) }}" alt="{{ $evento->tx_titulo }}" class="event-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="event-image-fallback" style="display: none;">
                                        <img src="{{ asset('favicon10.png') }}" alt="Logo">
                                    </div>
                                @else
                                    <div class="event-image-fallback">
                                        <img src="{{ asset('favicon10.png') }}" alt="Logo">
                                    </div>
                                @endif
                                
                                <div class="event-content">
                                    <p>{{ $evento->tx_descripcion }}</p>
                                </div>
                                
                                <div class="event-footer">
                                    <button class="event-button ver-mas-evento" 
                                        data-id="{{ $evento->codigo_general }}" 
                                        data-titulo="{{ $evento->tx_titulo }}" 
                                        data-descripcion="{{ $evento->tx_descripcion }}" 
                                        data-fecha="{{ Carbon::parse($evento->fe_registro)->format('d F - Y | H:i') }}">
                                        <i class="ri-eye-line"></i> Ver más
                                    </button>
                                    <span class="event-time">{{ Carbon::parse($evento->fe_registro)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="ri-information-line me-2"></i> No hay eventos disponibles en este momento.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Modal para mostrar detalles del evento -->
    <div class="modal fade event-modal" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        <!-- Swiper para mostrar imágenes/videos -->
                        <div class="swiper eventSwiper">
                            <div class="swiper-wrapper" id="eventSwiperWrapper">
                                <!-- Aquí se cargarán dinámicamente las diapositivas -->
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        
                        <div class="event-description" id="eventDescription"></div>
                        
                        <div class="event-meta">
                            <div class="event-meta-date">
                                <i class="ri-calendar-line me-1"></i>
                                <span id="eventDate"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Variables globales
        let eventSwiper = null;
        
        // Función principal al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Añadir clase para animación de entrada en carga
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach(element => {
                element.classList.add('fade-in');
            });
            
            // Configurar los eventos para los botones "Ver más"
            setupEventButtons();
            
            // Configurar los botones de cierre modal
            setupModalCloseButtons();
        });
        
        // Configurar los botones "Ver más"
        function setupEventButtons() {
            const buttons = document.querySelectorAll('.ver-mas-evento');
            
            buttons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const id = this.getAttribute('data-id');
                    const titulo = this.getAttribute('data-titulo');
                    const descripcion = this.getAttribute('data-descripcion');
                    const fecha = this.getAttribute('data-fecha');
                    
                    verDetallesEvento(id, titulo, descripcion, fecha);
                });
            });
        }
        
        // Configurar botones de cierre modal
        function setupModalCloseButtons() {
            const closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    closeEventModal();
                });
            });
        }
        
        // Función para ver detalles del evento
        function verDetallesEvento(id, titulo, descripcion, fecha) {
            // Configurar el título y descripción del modal
            document.getElementById('eventDetailModalLabel').textContent = titulo;
            document.getElementById('eventDescription').textContent = descripcion;
            document.getElementById('eventDate').textContent = fecha;
            
            // Limpiar el contenedor del swiper
            const swiperWrapper = document.getElementById('eventSwiperWrapper');
            swiperWrapper.innerHTML = '';
            
            // Mostrar el modal usando JavaScript plano
            showEventModal();
            
            // Agregar un slide de carga mientras se obtienen los adjuntos
            const loadingSlide = document.createElement('div');
            loadingSlide.className = 'swiper-slide';
            loadingSlide.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
            swiperWrapper.appendChild(loadingSlide);
            
            // Obtener adjuntos del evento mediante Ajax
            fetch(`/obtener-adjuntos-evento/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    // Limpiar el contenedor del swiper nuevamente
                    swiperWrapper.innerHTML = '';
                    
                    if (data.adjuntos && data.adjuntos.length > 0) {
                        // Crear slides para cada adjunto
                        data.adjuntos.forEach((adjunto, index) => {
                            const slide = document.createElement('div');
                            slide.className = 'swiper-slide';
                            
                            // Determinar si es imagen o video según el tipo o extensión
                            const isVideo = (adjunto.tipo_adjunto && 
                                            (adjunto.tipo_adjunto.includes('video') || 
                                             adjunto.tipo_adjunto.includes('mp4') || 
                                             adjunto.tipo_adjunto.includes('webm') || 
                                             adjunto.tipo_adjunto.includes('ogg'))) || 
                                           (adjunto.tx_url_adj && 
                                            (adjunto.tx_url_adj.toLowerCase().endsWith('.mp4') || 
                                             adjunto.tx_url_adj.toLowerCase().endsWith('.webm') || 
                                             adjunto.tx_url_adj.toLowerCase().endsWith('.ogg') || 
                                             adjunto.tx_url_adj.toLowerCase().endsWith('.mov')));
                            
                            if (isVideo) {
                                // Es un video
                                const video = document.createElement('video');
                                video.src = adjunto.tx_url_adj;
                                video.controls = true;
                                video.preload = "metadata";
                                video.className = "w-100 h-auto";
                                
                                // Manejar errores de carga del video
                                video.onerror = function() {
                                    const fallbackDiv = document.createElement('div');
                                    fallbackDiv.className = 'd-flex flex-column justify-content-center align-items-center h-100';
                                    fallbackDiv.innerHTML = `
                                        <img src="/favicon10.png" alt="No se pudo cargar el video" style="max-width: 40%; margin-bottom: 10px;">
                                        <div class="text-danger">No se pudo cargar el video</div>
                                    `;
                                    slide.innerHTML = '';
                                    slide.appendChild(fallbackDiv);
                                };
                                
                                slide.appendChild(video);
                            } else {
                                // Es una imagen u otro tipo de archivo
                                const img = document.createElement('img');
                                img.src = adjunto.tx_url_adj;
                                img.alt = 'Adjunto de evento';
                                img.onerror = function() {
                                    // Si la imagen no carga, mostrar una imagen de fallback
                                    this.src = '/favicon10.png';
                                };
                                slide.appendChild(img);
                            }
                            
                            swiperWrapper.appendChild(slide);
                        });
                    } else {
                        // Si no hay adjuntos, mostrar un mensaje o imagen por defecto
                        const slide = document.createElement('div');
                        slide.className = 'swiper-slide';
                        const img = document.createElement('img');
                        img.src = '/favicon10.png';
                        img.alt = 'No hay adjuntos disponibles';
                        slide.appendChild(img);
                        swiperWrapper.appendChild(slide);
                    }
                    
                    // Inicializar Swiper después de un pequeño retraso
                    setTimeout(initializeSwiper, 800, data.adjuntos);
                })
                .catch(error => {
                    // Mostrar mensaje de error en el modal
                    swiperWrapper.innerHTML = '';
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide';
                    slide.innerHTML = '<div class="alert alert-danger">Error al cargar los adjuntos del evento</div>';
                    swiperWrapper.appendChild(slide);
                });
        }
        
        // Función para inicializar Swiper
        function initializeSwiper(adjuntos) {
            try {
                // Destruir swiper anterior si existe
                if (eventSwiper !== null) {
                    eventSwiper.destroy(true, true);
                    eventSwiper = null;
                }
                
                // Verificar si Swiper está disponible
                if (typeof Swiper === 'function') {
                    // Inicializar nuevo swiper
                    eventSwiper = new Swiper(".eventSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 30,
                        loop: adjuntos && adjuntos.length > 1,
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        on: {
                            slideChange: function() {
                                // Pausar todos los videos cuando cambia el slide
                                const videos = document.querySelectorAll('.swiper-slide video');
                                videos.forEach(video => {
                                    video.pause();
                                });
                            }
                        }
                    });
                    
                    // Ajustar el tamaño del Swiper después de que todos los contenidos estén cargados
                    setTimeout(function() {
                        if (eventSwiper) {
                            eventSwiper.update();
                        }
                    }, 500);
                }
            } catch (error) {
                // Error silencioso
            }
        }
        
        // Función para mostrar el modal
        function showEventModal() {
            const modalElement = document.getElementById('eventDetailModal');
            
            // Detectar si es un dispositivo móvil
            const isMobile = window.innerWidth < 768;
            
            // Intenta usar Bootstrap si está disponible
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                try {
                    const modal = new bootstrap.Modal(modalElement);
                    
                    // Si es móvil, asegurar que sea fullscreen
                    if (isMobile) {
                        modalElement.classList.add('modal-fullscreen-md-down');
                    } else {
                        modalElement.classList.remove('modal-fullscreen-md-down');
                    }
                    
                    modal.show();
                } catch (error) {
                    showModalFallback(modalElement, isMobile);
                }
            } else {
                showModalFallback(modalElement, isMobile);
            }
        }
        
        // Función fallback para mostrar modal sin Bootstrap
        function showModalFallback(modalElement, isMobile) {
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Si es móvil, asegurar que sea fullscreen
            if (isMobile) {
                modalElement.classList.add('modal-fullscreen-md-down');
            } else {
                modalElement.classList.remove('modal-fullscreen-md-down');
            }
            
            // Crear backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
        
        // Función para cerrar el modal
        function closeEventModal() {
            const modalElement = document.getElementById('eventDetailModal');
            
            // Intenta usar Bootstrap si está disponible
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                try {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        closeModalFallback(modalElement);
                    }
                } catch (error) {
                    closeModalFallback(modalElement);
                }
            } else {
                closeModalFallback(modalElement);
            }
            
            // Eliminar clase de fullscreen si existe
            modalElement.classList.remove('modal-fullscreen-md-down');
        }
        
        // Función fallback para cerrar modal sin Bootstrap
        function closeModalFallback(modalElement) {
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Eliminar backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    </script>
@endpush
