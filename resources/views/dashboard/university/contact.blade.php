@extends('layouts.master')

@section('title')
    @lang('translation.contacts_title') - @lang('translation.business-school')
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'%3E%3C/path%3E%3Ccircle cx='9' cy='7' r='4'%3E%3C/circle%3E%3Cpath d='M23 21v-2a4 4 0 0 0-3-3.87'%3E%3C/path%3E%3Cpath d='M16 3.13a4 4 0 0 1 0 7.75'%3E%3C/path%3E%3C/svg%3E");
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

.contact-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    padding: 20px;
}

.contact-card {
    background-color: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-input);
    transition: all 0.3s ease;
    position: relative;
    border: 1px solid var(--color-border);
    height: 450px;
    transform: translateY(0);
    transition: var(--transition-normal);
}

.contact-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-card);
    border-color: var(--color-primary);
}

.contact-front {
    padding: 30px;
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    backface-visibility: hidden;
    transition: all 0.3s ease;
}

.contact-info {
    padding: 30px;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    opacity: 0;
    transform: scale(0.9);
    transition: all 0.3s ease;
    backface-visibility: hidden;
    border-radius: var(--radius-lg);
}

.contact-card.flipped .contact-front {
    opacity: 0;
    transform: scale(0.9);
}

.contact-card.flipped .contact-info {
    opacity: 1;
    transform: scale(1);
}

.profile-img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 20px;
    border: 4px solid var(--color-primary);
    box-shadow: 0 5px 15px rgba(19, 192, 230, 0.3);
    transition: transform 0.3s ease;
}

.contact-card:hover .profile-img {
    transform: scale(1.05);
}

.contact-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--color-dark);
    margin-bottom: 10px;
}

.contact-role {
    font-size: 1.1rem;
    color: var(--color-secondary);
    margin-bottom: 25px;
}

.contact-info p {
    margin: 12px 0;
    font-size: 1rem;
    line-height: 1.6;
}

.contact-info strong {
    font-weight: 600;
    margin-right: 5px;
}

.btn-flip {
    background: rgba(70, 135, 230, 0.1);
    color: var(--color-secondary);
    border: none;
    border-radius: 30px;
    padding: 10px 20px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.contact-info .btn-flip {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
}

.btn-flip:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
}

.contact-info .btn-flip:hover {
    transform: translateX(-50%) translateY(-3px);
}

.btn-icon {
    width: 16px;
    height: 16px;
}

@media (max-width: 1200px) {
    .contact-grid {
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
    
    .contact-grid {
        grid-template-columns: 1fr;
        padding: 15px;
        gap: 20px;
    }
    
    .contact-card {
        height: 420px;
    }
    
    .profile-img {
        width: 130px;
        height: 130px;
    }
    
    .contact-name {
        font-size: 1.3rem;
    }
    
    .contact-role {
        font-size: 1rem;
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
    
    .contact-card {
        height: 380px;
    }
    
    .profile-img {
        width: 110px;
        height: 110px;
    }
    
    .contact-name {
        font-size: 1.2rem;
    }
    
    .contact-role {
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
        $contactsTitle = __('translation.contacts_title');
    @endphp
    <x-page-header :title="$contactsTitle" icon="users">
        <div class="breadcrumb-container">
            <ol class="breadcrumb-list">
                <li><a href="{{ route('university') }}">@lang('translation.university_title')</a></li>
                <li><a href="#">@lang('translation.contacts_title')</a></li>
            </ol>
        </div>
    </x-page-header>
    
    <div class="row justify-content-center">
        <div class="col-12">
            <main class="fade-in">
                <div class="contact-grid">
                    <!-- Tarjeta 1 - Soporte Técnico -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Logo.png" class="profile-img" alt="Logo AquaFeel">
                            <h3 class="contact-name">Soporte Técnico</h3>
                            <p class="contact-role">Equipo de Soporte</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Soporte Técnico</p>
                            <p><strong>Correo Electrónico:</strong> soporte@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 610-601-2782</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tarjeta 2 - Crédito Visión Global -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Logo.png" class="profile-img" alt="Logo AquaFeel">
                            <h3 class="contact-name">Crédito Visión Global</h3>
                            <p class="contact-role">Departamento de Crédito</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Crédito Visión Global</p>
                            <p><strong>Correo Electrónico:</strong> -</p>
                            <p><strong>Teléfono:</strong> +1 (610) 819-7366</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tarjeta 3 - Administración Visión Global -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Logo.png" class="profile-img" alt="Logo AquaFeel">
                            <h3 class="contact-name">Administración Visión Global</h3>
                            <p class="contact-role">Departamento de Administración</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Administración Visión Global</p>
                            <p><strong>Correo Electrónico:</strong> -</p>
                            <p><strong>Teléfono:</strong> +1 (610) 632-7821</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tarjeta 4 - Verificación Visión Global -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Logo.png" class="profile-img" alt="Logo AquaFeel">
                            <h3 class="contact-name">Verificación Visión Global</h3>
                            <p class="contact-role">Departamento de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Verificación Visión Global</p>
                            <p><strong>Correo Electrónico:</strong> -</p>
                            <p><strong>Teléfono:</strong> +1 (610) 601-6503</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 5 - Comisiones y Pagos -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Logo.png" class="profile-img" alt="Logo AquaFeel">
                            <h3 class="contact-name">Comisiones y Pagos</h3>
                            <p class="contact-role">Departamento de Comisiones</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Comisiones y Pagos Visión Global</p>
                            <p><strong>Correo Electrónico:</strong> -</p>
                            <p><strong>Teléfono:</strong> +1 (610) 601-3530</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 6 - Instalación y Servicio -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Logo.png" class="profile-img" alt="Logo AquaFeel">
                            <h3 class="contact-name">Instalación y Servicio</h3>
                            <p class="contact-role">Departamento de Instalación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Instalación y Servicio Visión Global</p>
                            <p><strong>Correo Electrónico:</strong> -</p>
                            <p><strong>Teléfono:</strong> +1 (610) 601-6252</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 7 - Melvin Mora -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Melvin M.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Melvin Mora</h3>
                            <p class="contact-role">Embajador</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Melvin Mora (Embajador)</p>
                            <p><strong>Correo Electrónico:</strong> MelvinMora@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (516) 401-2242</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 8 - Ale Ruiz -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Ale Ruiz Contacto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Ale Ruiz</h3>
                            <p class="contact-role">Embajadora</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Ale Ruiz (Embajadora)</p>
                            <p><strong>Correo Electrónico:</strong> AleRuiz@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (914) 513-7272</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 9 - Osiel Valdivie -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Osiel Valdivie.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Osiel Valdivie</h3>
                            <p class="contact-role">Manager y Director Operaciones</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Osiel Valdivie (Manager y Director Operaciones)</p>
                            <p><strong>Correo Electrónico:</strong> Osiel@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (702) 769-2500</p>
                            <p><strong>Dirección:</strong> Visión global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tarjeta 10 - Julio Arvelo -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Julio Arvelo.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Julio Arvelo</h3>
                            <p class="contact-role">Director</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Julio Arvelo De La Cruz</p>
                            <p><strong>Correo Electrónico:</strong> julioarvelo@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (702) 353-8602</p>
                            <p><strong>Dirección:</strong> Vision Global</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 11 - Wilson Santos -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Wilson Santos.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Wilson Santos</h3>
                            <p class="contact-role">Director Regional</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Wilson Santos (Director Regional)</p>
                            <p><strong>Correo Electrónico:</strong> WilsonSantos@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (917) 542-1049</p>
                            <p><strong>Dirección:</strong> New Jersey 1, Miami, República Dominicana</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 12 - Alexander Henriquez -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Alexander Henriquez.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Alexander Henriquez</h3>
                            <p class="contact-role">Manager</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Alexander Henriquez (Manager)</p>
                            <p><strong>Correo Electrónico:</strong> AlexanderHenriquez@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (973) 842-1019</p>
                            <p><strong>Dirección:</strong> New Jersey 2</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 13 - Juan Sanchez -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Juan Sánchez.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Juan Sanchez</h3>
                            <p class="contact-role">Director</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Juan Sanchez (Director)</p>
                            <p><strong>Correo Electrónico:</strong> JuanSanchez@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (702) 409-7881</p>
                            <p><strong>Dirección:</strong> Las Vegas, Nevada</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 14 - Steven Ricon y Melba Negrin -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Steven Ricon y Melba Negrin.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Steven Ricon y Melba Negrin</h3>
                            <p class="contact-role">Manager</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Steven Ricon y Melba negrin (Manager)</p>
                            <p><strong>Correo Electrónico:</strong> RiconNegrin@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (860) 502-4934 / +1 (774) 535-4510</p>
                            <p><strong>Dirección:</strong> Connecticut y Puerto Rico</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 15 - Glafreisy Pichardo -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Glafreisy Pichardo</h3>
                            <p class="contact-role">Reading, PA</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Glafreisy Pichardo</p>
                            <p><strong>Correo Electrónico:</strong> GlafreisyPichardo@aquafeelglobal.net</p>
                            <p><strong>Teléfono:</strong> +1 (610) 507-8022</p>
                            <p><strong>Dirección:</strong> Reading. PA.</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 16 - Carlos Rosario -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Carlos Rosario</h3>
                            <p class="contact-role">Allegtown, PA</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Carlos Rosario</p>
                            <p><strong>Correo Electrónico:</strong> eduard_rosario@hotmail.com</p>
                            <p><strong>Teléfono:</strong> +1 (347) 641-0578</p>
                            <p><strong>Dirección:</strong> Allegtown. PA.</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 17 - Jose German -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Jose German</h3>
                            <p class="contact-role">Florida, FL</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Jose German</p>
                            <p><strong>Correo Electrónico:</strong> jjoseagerman@gmail.com</p>
                            <p><strong>Teléfono:</strong> +1 (954) 859-9580</p>
                            <p><strong>Dirección:</strong> Florida. FL.</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 18 - AQUAFEEL SOLUTIONS -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">AQUAFEEL SOLUTIONS</h3>
                            <p class="contact-role">Pre-Verificaciones</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> AQUAFEEL SOLUTIONS PRE VERIFICACIONES (ESPAÑOL /INGLÉS)</p>
                            <p><strong>Teléfono:</strong> 919-790-5475 opción 3; Ext. 104 Con Rocío Paredes</p>
                            <p><strong>Horario:</strong> L-V 10:00am - 6:00pm</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 19 - AFI VERIFICACIONES -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">AFI VERIFICACIONES</h3>
                            <p class="contact-role">Servicio de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> AFI VERIFICACIONES</p>
                            <p><strong>Teléfono:</strong> 1800-234-3663 opción 4, Ext. 6090 / 6097</p>
                            <p><strong>Horario:</strong> Lunes - Viernes 8:00 am-9:00 pm; Sábado 8:00 am-4:00 pm</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 20 - PCI VERIFICACIONES -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">PCI VERIFICACIONES</h3>
                            <p class="contact-role">Servicio de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> PCI VERIFICACIONES</p>
                            <p><strong>Teléfono:</strong> +1 (888) 850-3359</p>
                            <p><strong>Horario:</strong> Lunes - Viernes 7:00 a.m. - 11:00 p.m. (Hora Centro); Sábado 8:00 a.m. - 5:00 p.m. (Hora Centro); Domingo 2:00 p.m. - 6:00 p.m. (Hora Centro)</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 21 - Sunlight VERIFICACIONES -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">Sunlight VERIFICACIONES</h3>
                            <p class="contact-role">Servicio de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> Sunlight VERIFICACIONES</p>
                            <p><strong>Teléfono:</strong> +1 (800) 972-0825; Inglés +1 (877) 878-1079; Espanol +1 (877) 433-0693</p>
                            <p><strong>Horario:</strong> Lunes - Viernes 9am - 9pm; Sábado 10am - 5pm</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 22 - UNITED VERIFICACIONES -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">UNITED VERIFICACIONES</h3>
                            <p class="contact-role">Servicio de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> UNITED VERIFICACIONES</p>
                            <p><strong>Teléfono:</strong> +1 (800) 344-5000</p>
                            <p><strong>Horario:</strong> Lunes - Viernes 8:00 am 10:00 pm; Sábado 8:00 am a 1:00 pm; Domingo 9:00 am a 2:00 pm</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 23 - FIRST CREDIT VERIFICACIONES -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">FIRST CREDIT</h3>
                            <p class="contact-role">Servicio de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> FIRST CREDIT VERIFICACIONES</p>
                            <p><strong>Teléfono:</strong> +1 (800) 525-9512; (303) 565-3622; (303) 499-9500</p>
                            <p><strong>Horario:</strong> Lunes - Viernes 8:00 am a 8:00 pm; Sábado 11:00 am a 4:00 pm</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta 24 - CASTLE CREDIT -->
                    <div class="contact-card" onclick="toggleCard(this)">
                        <div class="contact-front">
                            <img src="/img/contacts/Perfil por defecto.png" class="profile-img" alt="Perfil">
                            <h3 class="contact-name">CASTLE CREDIT</h3>
                            <p class="contact-role">Servicio de Verificación</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14L21 3"></path>
                                    <path d="M18 13v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h7"></path>
                                </svg>
                                Ver información
                            </button>
                        </div>
                        <div class="contact-info">
                            <p><strong>Nombre:</strong> CASTLE CREDIT</p>
                            <p><strong>Teléfono:</strong> +1 (844) 940-1002 ext. 3242 con Rose Español</p>
                            <p><strong>Horario:</strong> Lunes - Viernes 8:00 am a 5:00 pm</p>
                            <button class="btn-flip" onclick="toggleCard(this.parentNode.parentNode, event)">
                                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>
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
});

// Función para alternar la clase 'flipped' en la tarjeta
function toggleCard(card, event) {
    if (event) {
        event.stopPropagation(); // Evitar que el evento se propague
    }
    card.classList.toggle('flipped');
}
</script>
@endpush