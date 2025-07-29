@extends('layouts.master')

@section('title')
    @lang('translation.university_title') - @lang('translation.business-school')
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
    width: 100%;
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

.logo {
    width: 180px;
    transition: var(--transition-normal);
    filter: drop-shadow(0 4px 6px rgba(19, 192, 230, 0.2));
    animation: float 6s ease-in-out infinite;
}

.logo:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 6px 8px rgba(19, 192, 230, 0.3));
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}

main {
    padding: 1rem 0;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

main section {
    display: flex;
    align-items: center;
    margin: 0;
    padding: 1.6rem;
    border-radius: var(--radius-lg);
    background: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    transition: var(--transition-normal);
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
    width: 100%;
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
    opacity: 0.3;
}

main section:hover::before {
    width: 300%;
    height: 300%;
}

main section:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(19, 192, 230, 0.15);
    border-color: var(--color-primary);
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

main section:hover h2 {
    transform: translateX(5px);
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

.button {
    display: block;
    text-decoration: none;
    color: var(--color-text);
    background: none;
    border: none;
    padding: 0;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: var(--transition-normal);
    height: 100%;
}

.button:focus {
    outline: none;
}

.button:focus-visible section {
    box-shadow: 0 0 0 3px var(--color-primary);
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
    
    .logo {
        width: 140px;
    }
    
    main {
        gap: 1rem;
    }
    
    main section {
        padding: 1.2rem;
        flex-direction: column;
        text-align: center;
    }
    
    .icon {
        width: 70px;
        height: 70px;
        margin-right: 0;
        margin-bottom: 1rem;
        padding: 1.2rem;
    }
    
    .icon i {
        width: 40px !important;
        height: 40px !important;
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
        opacity: 0.3;
        width: 0;
        height: 0;
    }
    
    main section:hover::before {
        width: 300%;
        height: 300%;
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
    
    .logo {
        width: 120px;
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

/* Animaciones de entrada */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
}

.fade-in section {
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
}

.fade-in section:nth-child(1) { animation-delay: 0.1s; }
.fade-in section:nth-child(2) { animation-delay: 0.2s; }
.fade-in section:nth-child(3) { animation-delay: 0.3s; }
.fade-in section:nth-child(4) { animation-delay: 0.4s; }
.fade-in section:nth-child(5) { animation-delay: 0.5s; }
</style>
@endpush

@section('content')
        @php
            $universityTitle = __('translation.business-school');
        @endphp
        <x-page-header :title="$universityTitle" icon="book" />
    
    <div class="row justify-content-center">
        <div class="col-12">
            <main class="fade-in">
                    <a class="button" href="{{route('u_documents')}}" aria-label="@lang('translation.documents_title')">
                    <section>
                        <div class="icon">
                            <i data-feather="file-text" width="40" height="40"></i>
                        </div>
                        <div>
                            <h2>@lang('translation.documents_title')</h2>
                            <p class="description-text">Obtén las herramientas y la información que necesitas para tus objetivos.</p>
                        </div>
                    </section>
                    </a>
                
                    <a class="button" href="{{route('u_videos')}}" aria-label="@lang('translation.videos_title')">
                        <section>
                            <div class="icon">
                                <i data-feather="video" width="40" height="40"></i>
                            </div>
                            <div>
                                <h2>@lang('translation.videos_title')</h2>
                            <p class="description-text">Explora nuestra videoteca, videos educativos y recursos audivisuales</p>
                            </div>
                        </section>
                    </a>
                
                    <a class="button" href="{{route('u_faq')}}" aria-label="@lang('translation.faq_title')">
                        <section>
                            <div class="icon">
                                <i data-feather="help-circle" width="40" height="40"></i>
                            </div>
                            <div>
                                <h2>@lang('translation.faq_title')</h2>
                            <p class="description-text">Aquí encontrarás las preguntas más frecuentes sobre la plataforma.</p>
                            </div>
                        </section>
                    </a>
                
                    <a class="button" href="{{route('u_contact')}}" aria-label="@lang('translation.contacts_title')">
                        <section>
                            <div class="icon">
                                <i data-feather="users" width="40" height="40"></i>
                            </div>
                            <div>
                                <h2>@lang('translation.contacts_title')</h2>
                            <p class="description-text">Conoce a las personas que han explicado el contenido de nuestra plataforma.</p>
                            </div>
                        </section>
                    </a>
                
                    <a class="button" href="{{route('u_blog')}}" aria-label="@lang('translation.blog_title')">
                        <section>
                            <div class="icon">
                                <i data-feather="book-open" width="40" height="40"></i>
                            </div>
                            <div>
                                <h2>@lang('translation.blog_title')</h2>
                            <p class="description-text">Sumérgete en nuestro blog y descubre contenido relacionado con técnicas para tu trabajo y desafíos profesionales.</p>
                            </div>
                        </section>
                    </a>
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
