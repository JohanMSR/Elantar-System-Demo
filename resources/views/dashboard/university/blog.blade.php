@extends('layouts.master')

@section('title')
    @lang('translation.blog_title') - @lang('translation.business-school')
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
    --transition-normal: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-fast: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
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
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
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
    transform: translateY(-2px);
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M4 19.5A2.5 2.5 0 0 1 6.5 17H20'%3E%3C/path%3E%3Cpath d='M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z'%3E%3C/path%3E%3C/svg%3E");
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
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

.featured {
    padding: 0;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-input);
    border: 1px solid var(--color-border);
    transition: var(--transition-normal);
    overflow: hidden;
    background: var(--color-input-bg);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.featured:hover {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-card);
    transform: translateY(-5px);
}

.featured::before {
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

.featured:hover::before {
    transform: scaleY(1);
}

.featured-thumbnail {
    width: 100%;
    overflow: hidden;
    border-radius: var(--radius-md);
    position: relative;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
}

.featured-thumbnail img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.featured:hover .featured-thumbnail img {
    transform: scale(1.05);
}

.featured-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.title {
    margin-bottom: 1rem;
    color: var(--color-dark) !important;
    font-family: "MontserratBold", sans-serif;
    font-size: 1.3rem;
    font-weight: 600;
    line-height: 1.4;
    transition: var(--transition-normal);
}

.featured:hover .title {
    color: var(--color-secondary) !important;
}

.featured p {
    font-size: 0.95rem;
    color: var(--color-text) !important;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex-grow: 1;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
}

.featured-footer {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.read-more {
    color: var(--color-primary);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: var(--transition-normal);
}

.read-more:hover {
    color: var(--color-secondary);
}

.read-more::after {
    content: '';
    display: inline-block;
    width: 1rem;
    height: 1rem;
    margin-left: 0.5rem;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2313c0e6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='5' y1='12' x2='19' y2='12'%3E%3C/line%3E%3Cpolyline points='12 5 19 12 12 19'%3E%3C/polyline%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    transition: transform 0.3s ease;
}

.read-more:hover::after {
    transform: translateX(3px);
}

.data {
    color: var(--color-light-text) !important;
    font-size: 0.85rem;
}

a {
    text-decoration: none;
    color: inherit;
}

.bg-imagen {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (max-width: 1200px) {
    .blog-grid {
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
    
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .title {
        font-size: 1.2rem;
    }
    
    .featured p {
        font-size: 0.9rem;
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
    
    .title {
        font-size: 1.1rem;
    }
    
    .featured p {
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
        font-size: 0.8rem;
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    @php
        $blogTitle = __('translation.blog_title');
    @endphp
    <x-page-header :title="$blogTitle" icon="book-open">
        <div class="breadcrumb-container">
            <ol class="breadcrumb-list">
                <li><a href="{{ route('university') }}">@lang('translation.university_title')</a></li>
                <li><a href="#">@lang('translation.blog_title')</a></li>
            </ol>
        </div>
    </x-page-header>
    
    <div class="row justify-content-center">
        <div class="col-12">
            <main class="fade-in">
                <div class="blog-grid">
                    <article class="featured stagger-fade-in" style="animation-delay: 0.1s;">
                        <a href="#">
                            <div class="featured-thumbnail">
                                <img src="/img/university/Blog/art1.png" decoding="async">
                            </div>
                            <div class="featured-content">
                                <h1 class="title">
                                    El Plan De La EPA Para Restringir Las Sustancias Químicas PFAS En El Agua Potable
                                </h1>
                                <p> 
                                    El agua es esencial para nuestra salud y bienestar. Por desgracia, muchas personas en todo el mundo están expuestas a agua que contiene niveles inseguros de sustancias químicas. Una de las sustancias químicas más controvertidas en el agua son los PFAS, compuestos sintéticos utilizados en diversos productos industriales y de consumo.
                                </p>
                                <div class="featured-footer">
                                    <span class="data">15 de Mayo, 2023</span>
                                    <span class="read-more">Leer más</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    
                    <article class="featured stagger-fade-in" style="animation-delay: 0.2s;">
                        <a href="#">
                            <div class="featured-thumbnail">
                                <img src="/img/university/Blog/art2.png" decoding="async">
                            </div>
                            <div class="featured-content">
                                <h1 class="title">
                                    La Verdad Sobre Las Sustancias Químicas PFAS Y Su Salud
                                </h1>
                                <p> 
                                    A medida que se realizan más investigaciones sobre el impacto de las sustancias químicas en la salud humana, un grupo de sustancias químicas conocidas como PFAS ha empezado a recibir más atención. Abreviatura de sustancias perfluoroalquílicas y polifluoroalquílicas, las sustancias químicas PFAS son compuestos artificiales.
                                </p>
                                <div class="featured-footer">
                                    <span class="data">10 de Mayo, 2023</span>
                                    <span class="read-more">Leer más</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    
                    <article class="featured stagger-fade-in" style="animation-delay: 0.3s;">
                        <a href="#">
                            <div class="featured-thumbnail">
                                <img src="/img/university/Blog/art3.png" decoding="async">
                            </div>
                            <div class="featured-content">
                                <h1 class="title">
                                    La Escandalosa Verdad Sobre El Agua Potable Contaminada...
                                </h1>
                                <p> 
                                    Todos reconocemos la importancia del agua potable para mantener un estilo de vida saludable. Sin embargo, muchos estadounidenses desconocen que millones de personas consumen agua contaminada, y la situación empeora cada año. La contaminación del agua potable es una preocupación creciente.
                                </p>
                                <div class="featured-footer">
                                    <span class="data">5 de Mayo, 2023</span>
                                    <span class="read-more">Leer más</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    
                    <article class="featured stagger-fade-in" style="animation-delay: 0.4s;">
                        <a href="#">
                            <div class="featured-thumbnail">
                                <img src="/img/university/Blog/art4.png" decoding="async">
                            </div>
                            <div class="featured-content">
                                <h1 class="title">
                                    La Silenciosa Crisis Del Agua En Estados Unidos
                                </h1>
                                <p> 
                                    No es ningún secreto que el agua es uno de los recursos más importantes para la supervivencia humana. El cuerpo humano no puede sostenerse sin agua; dependemos de ella para cocinar, para el saneamiento y para la agricultura. Pero a pesar de su importancia, muchas partes de Estados Unidos están sufriendo una crisis del agua.
                                </p>
                                <div class="featured-footer">
                                    <span class="data">1 de Mayo, 2023</span>
                                    <span class="read-more">Leer más</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    
                    <article class="featured stagger-fade-in" style="animation-delay: 0.5s;">
                        <a href="#">
                            <div class="featured-thumbnail">
                                <img src="/img/university/Blog/art5.png" decoding="async">
                            </div>
                            <div class="featured-content">
                                <h1 class="title">
                                    La Verdad Sobre El Agua Potable En Los Restaurantes
                                </h1>
                                <p> 
                                    Como entusiastas de la salud y el bienestar, somos conscientes de la importancia del agua potable para nuestro organismo y nuestro bienestar general. Sin embargo, ¿te has preguntado alguna vez por la calidad del agua de los restaurantes que visitas? ¿Priorizan el uso de filtros de agua?
                                </p>
                                <div class="featured-footer">
                                    <span class="data">25 de Abril, 2023</span>
                                    <span class="read-more">Leer más</span>
                                </div>
                            </div>
                        </a>
                    </article>
                </div>
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
        
        // Animar los artículos del blog al cargar
        const articles = document.querySelectorAll('.featured');
        articles.forEach((article, index) => {
            setTimeout(() => {
                article.classList.add('animate');
            }, 100 * index);
        });
    });
    </script>
@endpush
