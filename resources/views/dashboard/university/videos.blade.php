@extends('layouts.master')

@section('title')
    @lang('translation.videos_title') - @lang('translation.business-school')
@endsection

@push('css')
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

.container-fluid {
    padding: 0rem;
    max-width: 1500px;
    margin: 0 auto;
    position: relative;
}

.container-1 {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
    box-shadow: var(--shadow-card);
    padding: 2rem;
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg);
    transition: var(--transition-normal);
    z-index: 1;
    width: 100% !important;
    margin: 0 auto 0.2rem auto;
}

.container-1:hover {
    box-shadow: 0 15px 35px rgba(19, 192, 230, 0.35);
}

.container-1::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-primary), transparent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s ease;
}

.container-1:hover::after {
    transform: scaleX(1);
}

.flex-menu {
    display: flex;
    align-items: center;
    width: 100%;
}

#principal-head {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
    color: white;
    text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    letter-spacing: 0.5px;
    position: relative;
    display: flex;
    align-items: center;
}

#principal-head::before {
    content: '';
    display: inline-block;
    width: 2rem;
    height: 2rem;
    margin-right: 15px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='23 7 16 12 23 17 23 7'%3E%3C/polygon%3E%3Cpath d='M14 5H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z'%3E%3C/path%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
}

#principal-head::after {
    content: '';
    position: absolute;
    bottom: -7px;
    left: 0;
    width: 50px;
    height: 3px;
    background: white;
    border-radius: 3px;
}

.breadcrumb-container {
    margin-left: auto;
    width: 40%;
    min-width: 300px;
    background-color: white;
    border-radius: 30px;
    padding: 0.4rem 1.2rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: center;
    align-items: center;
}

.breadcrumb-list {
    display: flex;
    list-style-type: none;
    padding: 0;
    margin: 0;
    align-items: center;
}

.breadcrumb-list li {
    display: flex;
    align-items: center;
    color: var(--color-light-text);
}

.breadcrumb-list li:not(:last-child)::after {
    content: '';
    display: inline-block;
    width: 0.8rem;
    height: 0.8rem;
    margin: 0 0.4rem;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
}

.breadcrumb-list a {
    color: var(--color-light-text);
    text-decoration: none;
    transition: var(--transition-normal);
    font-size: 0.9rem;
}

.breadcrumb-list a:hover {
    color: var(--color-primary);
}

.breadcrumb-list li:last-child a {
    color: var(--color-dark);
    font-weight: 600;
}

/* Ajuste responsive para el breadcrumb */
@media (max-width: 992px) {
    .breadcrumb-container {
        width: 80%;
        margin: 0.8rem auto 0;
    }
}

@media (max-width: 576px) {
    .breadcrumb-container {
        width: 100%;
        min-width: auto;
    }
    
    .breadcrumb-list a {
        font-size: 0.8rem;
    }
}

main {
    padding: 1rem 0;
}

.featured-video {
    margin-bottom: 2.5rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    background: var(--color-input-bg);
    position: relative;
    transition: var(--transition-normal);
    max-width: 1300px;
    margin-left: auto;
    margin-right: auto;
}

.featured-video:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(19, 192, 230, 0.35);
}

.featured-video::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.featured-video:hover::before {
    transform: scaleY(1);
}

.video-container {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* Proporción 16:9 */
    background-color: #000;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-info {
    padding: 2rem;
}

.video-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--color-dark);
    margin-bottom: 1rem;
    transition: var(--transition-normal);
}

.featured-video:hover .video-title {
    color: var(--color-secondary);
}

.video-author {
    color: var(--color-light-text);
    font-size: 1.2rem;
    margin-bottom: 1.2rem;
}

.video-description {
    color: var(--color-text);
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 1.2rem;
    padding: 1.5rem;
    background-color: var(--color-input-bg-hover);
    border-radius: var(--radius-md);
    border-left: 3px solid var(--color-primary);
}

.video-category {
    margin-top: 3rem;
    margin-bottom: 1.5rem;
    position: relative;
    padding-left: 1rem;
}

.video-category::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
    border-radius: 2px;
}

.video-category h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-dark);
    margin: 0;
}

.video-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.video-card {
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-input);
    border: 1px solid var(--color-border);
    overflow: hidden;
    background: var(--color-input-bg);
    position: relative;
    transition: var(--transition-normal);
    height: 100%;
}

.video-card:hover {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-card);
    transform: translateY(-5px);
}

.video-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.video-card:hover::before {
    transform: scaleY(1);
}

.video-card-container {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
    background-color: #000;
    overflow: hidden;
}

.video-card-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-card-info {
    padding: 1rem;
}

.video-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-dark);
    margin-bottom: 0.5rem;
    transition: var(--transition-normal);
}

.video-card:hover .video-card-title {
    color: var(--color-secondary);
}

.video-card-author {
    color: var(--color-light-text);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.video-card-description {
    color: var(--color-text);
    font-size: 0.9rem;
    line-height: 1.5;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

@media (max-width: 1200px) {
    .video-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .container-1 {
        padding: 1rem;
    }
    
    #principal-head {
        font-size: 1.5rem;
    }
    
    .video-grid {
        grid-template-columns: 1fr;
    }
    
    .featured-video {
        margin-bottom: 2rem;
    }
    
    .video-container {
        padding-top: 56.25%; /* Mantener 16:9 en móviles */
    }
    
    .video-title {
        font-size: 1.5rem;
    }
    
    .video-description {
        font-size: 1.1rem;
        padding: 1.2rem;
    }
    
    #principal-head::after {
        width: 40px;
        bottom: -5px;
    }
}

@media (max-width: 480px) {
    .container-1 {
        flex-direction: column;
    }
    
    .flex-menu {
        flex-direction: column;
        gap: 1rem;
    }
    
    #principal-head {
        font-size: 1.3rem;
        text-align: center;
    }
    
    .video-title {
        font-size: 1.2rem;
    }
    
    .video-description {
        font-size: 0.9rem;
    }
    
    #principal-head::after {
        width: 30px;
    }
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}

.fade-in {
    opacity: 0;
    animation: fadeIn 0.6s ease-in-out forwards;
}

@keyframes staggerFadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.stagger-fade-in {
    opacity: 0;
    animation: staggerFadeIn 0.6s ease-in-out forwards;
}

.icon {
    width: 60px;
    height: 60px;
    margin-right: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-secondary);
    transition: var(--transition-normal);
    position: relative;
    z-index: 2;
    border-radius: 50%;
    background: rgba(70, 135, 230, 0.15);
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(70, 135, 230, 0.2);
}

.icon i {
    transition: var(--transition-normal);
    width: 35px !important;
    height: 35px !important;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
}

main section:hover .icon {
    color: white;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(19, 192, 230, 0.3);
}

main section:hover .icon i {
    transform: scale(1.2);
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    @php
        $videosTitle = __('translation.videos_title');
    @endphp
    <x-page-header :title="$videosTitle" icon="video">
        <div class="breadcrumb-container">
            <ol class="breadcrumb-list">
                <li><a href="{{ route('university') }}">@lang('translation.university_title')</a></li>
                <li><a href="#">@lang('translation.videos_title')</a></li>
            </ol>
        </div>
    </x-page-header>
    
    <div class="row justify-content-center">
        <div class="col-12">
            <main class="fade-in">
                @if(!empty($video_destacado))
                    <div class="featured-video stagger-fade-in" style="animation-delay: 0.1s;">
                        @foreach($video_destacado as $item)
                            <div class="video-container">
                                <video preload="metadata" controls controlsList="nodownload">
                                    <source src="{{$item->tx_url}}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                            <div class="video-info">
                                <h1 class="video-title">{{$item->tx_titulo}}</h1>
                                <p class="video-author">Por {{$item->tx_autor}}</p>
                                <p class="video-description">{{$item->tx_descripcion}}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        No hay video destacado...
                    </div>
                @endif

                @php
                $categorias = [];

                foreach ($videos as $video) {
                    $categoria = $video->Categoria;

                    // Si la categoría no existe en $categorias, crearla
                    if (!array_key_exists($categoria, $categorias)) {
                        $categorias[$categoria] = [];
                    }

                    // Add the video URL directly if it's not the featured video
                    if ($video->tx_url != ($video_destacado[0]->tx_url ?? '')) { // Handle potential undefined $video_destacado
                        $categorias[$categoria][] = $video;
                    }
                }
                @endphp

                @foreach ($categorias as $categoria => $videos_categoria)
                    @if (!empty($categoria))
                        <div class="video-category stagger-fade-in" style="animation-delay: 0.2s;">
                            <h2>Videos Sobre {{ htmlspecialchars($categoria) }}</h2>
                        </div>
                        <div class="video-grid">
                            @foreach ($videos_categoria as $video)
                                <div class="video-card stagger-fade-in" style="animation-delay: {{ 0.3 + ($loop->index * 0.1) }}s;">
                                    <div class="video-card-container">
                                        <video preload="metadata" controls controlsList="nodownload">
                                            <source src="{{ $video->tx_url }}" type="video/mp4">
                                            Tu navegador no soporta el elemento de video.
                                        </video>
                                    </div>
                                    <div class="video-card-info">
                                        <h3 class="video-card-title">{{ $video->tx_titulo }}</h3>
                                        <p class="video-card-author">Por {{ $video->tx_autor }}</p>
                                        <p class="video-card-description">{{ $video->tx_descripcion }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activar opciones del menú
        if (typeof activateOption === 'function') {
            activateOption('opc3');
        }
        if (typeof activateOption2 === 'function') {
            activateOption2('icon-university');
        }
        
        // Añadir clase para animación de entrada
        const mainElement = document.querySelector('main');
        if (mainElement) {
            mainElement.classList.add('fade-in');
        }
        
        // Animar los videos al cargar
        const videos = document.querySelectorAll('.video-card, .featured-video, .video-category');
        videos.forEach((video, index) => {
            setTimeout(() => {
                video.classList.add('animate');
            }, 100 * index);
        });
    });
    </script>
@endpush
