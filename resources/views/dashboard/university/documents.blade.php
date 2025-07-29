@extends('layouts.master')

@section('title')
    @lang('translation.documents_title') - @lang('translation.business-school')
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z'%3E%3C/path%3E%3Cpath d='M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z'%3E%3C/path%3E%3C/svg%3E");
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

main {
    padding: 1rem 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

main section {
    display: flex;
    align-items: center;
    margin: 1.5rem 0;
    padding: 1.6rem;
    border-radius: var(--radius-lg);
    background: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

main section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
    z-index: 0;
    opacity: 0.1;
}

main section:hover::before {
    width: 300%;
    height: 300%;
}

main section:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(19, 192, 230, 0.15);
}

main section:hover .icon {
    color: var(--color-secondary);
}

main section:hover h2 {
    color: var(--color-secondary);
}

main section:hover .description-text {
    color: var(--color-primary-dark);
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

h2 {
    margin-top: 0;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    font-family: "MontserratBold", sans-serif;
    color: var(--color-dark);
    text-transform: uppercase;
    transition: var(--transition-normal);
    letter-spacing: 0.5px;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    position: relative;
    display: inline-block;
    z-index: 2;
}

.description-text {
    font-size: 1rem;
    font-family: "Montserrat", sans-serif;
    color: var(--color-text);
    margin: 0;
    line-height: 1.6;
    transition: var(--transition-normal);
    position: relative;
    text-shadow: 0 0.5px 0 rgba(255, 255, 255, 0.8);
    max-width: 90%;
    z-index: 2;
}

.file-section {
    margin-bottom: 0;
}

.file-section section {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 1.6rem;
    border-radius: var(--radius-lg);
    background: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    transition: var(--transition-normal);
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.file-section section:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(19, 192, 230, 0.15);
    border-color: var(--color-primary);
}

.file-section .icon {
    width: 60px;
    height: 60px;
    margin-bottom: 1rem;
    background: rgba(70, 135, 230, 0.15);
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(70, 135, 230, 0.2);
}

.file-section .icon i {
    width: 35px !important;
    height: 35px !important;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
}

.file-section section:hover .icon {
    color: white;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(19, 192, 230, 0.3);
}

.file-section section:hover .icon i {
    transform: scale(1.2);
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.file-section h2 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: var(--color-dark);
    transition: var(--transition-normal);
}

.file-section section:hover h2 {
    color: var(--color-secondary);
}

.file-section p {
    margin-bottom: 1rem;
    color: var(--color-text);
    transition: var(--transition-normal);
}

.file-section section:hover p {
    color: var(--color-primary-dark);
}

.download-button {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1.5rem;
    background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
    color: white;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-btn);
    border: none;
    margin-top: 1rem;
}

.download-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(19, 192, 230, 0.4);
    color: white;
}

.download-button i {
    margin-right: 8px;
    width: 16px !important;
    height: 16px !important;
}

@media (max-width: 1200px) {
    main {
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
    
    main {
        grid-template-columns: 1fr;
    }
    
    main section {
        padding: 1.2rem;
        flex-direction: column;
        text-align: center;
    }
    
    .icon {
        margin-right: 0;
        margin-bottom: 0.8rem;
        width: 50px;
        height: 50px;
    }
    
    h2 {
        font-size: 1.3rem;
        letter-spacing: 0.3px;
    }
    
    .description-text {
        font-size: 0.9rem;
        max-width: 100%;
    }
    
    #principal-head::after {
        width: 40px;
        bottom: -5px;
    }
    
    main section::before {
        width: 100%;
        height: 5px;
        opacity: 0.1;
    }
    
    main section:hover::before {
        height: 100%;
    }
    
    .file-section section {
        padding: 1.2rem;
    }
    
    .file-section .icon {
        width: 50px;
        height: 50px;
        margin-bottom: 0.8rem;
    }
    
    .file-section .icon i {
        width: 30px !important;
        height: 30px !important;
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
    
    main section {
        padding: 1.2rem;
    }
    
    .icon {
        width: 50px;
        height: 50px;
    }
    
    h2 {
        font-size: 1.1rem;
    }
    
    .description-text {
        font-size: 0.85rem;
    }
    
    #principal-head::after {
        width: 30px;
    }
}

@media (max-width: 992px) {
    .breadcrumb-container {
        width: 80%;
        margin: 0.8rem auto 0;
    }
    
    #principal-head {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .breadcrumb-container {
        width: 100%;
        min-width: auto;
    }
    
    .breadcrumb-list a {
@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}

.fade-in {
    opacity: 0;
    animation: fadeIn 0.6s ease-in-out forwards;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    @php
        $documentsTitle = __('translation.documents_title');
    @endphp
    <x-page-header :title="$documentsTitle" icon="file-text">
        <div class="breadcrumb-container">
            <ol class="breadcrumb-list">
                <li><a href="{{ route('university') }}">@lang('translation.university_title')</a></li>
                <li><a href="#">@lang('translation.documents_title')</a></li>
            </ol>
        </div>
    </x-page-header>
    
    <div class="row justify-content-center">
        <div class="col-12">
            <main class="fade-in">
                @if(!empty($documentos))
                    @foreach ($documentos as $item)
                        <div class="file-section">
                            <section>
                                <div class="icon">
                                    <i data-feather="file" width="40" height="40"></i>
                                </div>
                                <div>
                                    <h2>{{$item->tx_titulo}}</h2>
                                    @php $decripcion_breve = substr($item->tx_descripcion, 0, 500); @endphp
                                    <p class="description-text">{{$decripcion_breve}} ...</p>
                                    <a href="{{$item->tx_url}}" target="blank_" class="download-button">
                                        <i data-feather="download" width="16" height="16" style="margin-right: 5px;"></i>
                                        Descargar
                                    </a>
                                </div>
                            </section>
                        </div>
                    @endforeach
                @else
                    <section>
                        <div class="icon">
                            <i data-feather="alert-circle" width="40" height="40"></i>
                        </div>
                        <div>
                            <h2>No hay documentos disponibles</h2>
                            <p class="description-text">En este momento no hay documentos que mostrar. Por favor, vuelve más tarde.</p>
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        activateOption('opc3');
        activateOption2('icon-university');
        
        // Añadir clase para animación de entrada
        const mainElement = document.querySelector('main');
        if (mainElement) {
            mainElement.classList.add('fade-in');
        }
        
        // Efecto hover en secciones para dispositivos táctiles
        const sections = document.querySelectorAll('main section');
        if ('ontouchstart' in window) {
            sections.forEach(section => {
                section.addEventListener('touchstart', function() {
                    this.classList.add('hover');
                });
                
                section.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.classList.remove('hover');
                    }, 300);
                });
            });
        }
    });
    </script>
@endpush