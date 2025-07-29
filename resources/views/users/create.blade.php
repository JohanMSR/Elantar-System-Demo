@extends('layouts.master')

@section('title')
Creacion de usuario - Centro de Negocios
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
    
    .form-group {
        margin-bottom: 1.5rem;
        width: 100%;
    }
    
    .form-label {
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: var(--radius-md);
        border: 1px solid var(--color-border);
        padding: 0.75rem;
        transition: var(--transition-normal);
        width: 100%;
        max-width: 100%;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem rgba(19, 192, 230, 0.25);
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
    
    .section-title {
        color: var(--color-text);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 1.5rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--color-primary);
    }
    
    .advanced-search-btn, .btn-primary {
        display: inline-block;
        width: auto;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(to right, #13c0e6, #4687e6);
        color: #fff;
        border: none;
        border-radius: var(--radius-md);
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
    
    .advanced-search-btn:hover, .btn-primary:hover {
        filter: brightness(1.05);
        box-shadow: 0 7px 20px rgba(70, 135, 230, 0.4);
        color: #fff;
        text-decoration: none;
    }
    
    .btn-secondary {
        background: #e2e6ea;
        color: #495057;
        border: none;
        border-radius: var(--radius-md);
        transition: var(--transition-normal);
        padding: 0.75rem 1.5rem;
        display: inline-block;
        width: auto;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background: #c8d0d6;
        color: #495057;
        transform: translateY(-2px);
        text-decoration: none;
    }
    
    .required-field::after {
        content: "*";
        color: #e74c3c;
        margin-left: 4px;
    }
    
    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.875rem;
    }
    
    .photo-upload-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin: 0 auto;
        padding: 20px 0;
    }

    .photo-preview-container {
        width: 150px;
        height: 150px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .photo-input {
        position: absolute;
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        z-index: -1;
        visibility: hidden;
    }

    /* Tab styles */
    .nav-tabs {
        border-bottom: 2px solid var(--color-border);
        margin-bottom: 1.5rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: var(--color-light-text);
        padding: 1rem 1.5rem;
        font-weight: 500;
        transition: var(--transition-normal);
    }
    
    .nav-tabs .nav-link:hover {
        border: none;
        color: var(--color-primary);
    }
    
    .nav-tabs .nav-link.active {
        border: none;
        color: var(--color-primary);
        border-bottom: 2px solid var(--color-primary);
    }
    
    .tab-content {
        padding: 1rem 0;
    }
    
    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
        .col-md-6 {
            padding: 0.5rem;
        }

        .form-control, .form-select {
            padding: 0.5rem;
        }

        .btn {
            width: 100%;
            margin: 0.5rem 0;
        }
    }

    /* Adjust tab navigation for better mobile display */
    @media (max-width: 576px) {
        .nav-tabs .nav-link {
            padding: 0.5rem;
            font-size: 0.9rem;
        }

        .nav-tabs .nav-link i {
            margin-right: 0.25rem;
        }
    }    

    /* Estilos específicos para inputs regulares (excluyendo x-password-input) */
    .form-group > input.form-control:not(.x-password-input input) {
        display: block;
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--color-text);
        background-color: var(--color-input-bg);
        background-clip: padding-box;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        transition: var(--transition-normal);
    }

    .form-group > input.form-control:not(.x-password-input input):focus {
        color: var(--color-text);
        background-color: var(--color-input-bg);
        border-color: var(--color-primary);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(19, 192, 230, 0.25);
    }

    /* Ocultar campos para usuarios tipo Plomero */
    .hide-for-plumber {
        display: none !important;
    }

    /* Estilos para el switch de tipo de usuario */
    .user-type-switch {
        margin-bottom: 1rem;
    }

    .user-type-switch .form-check {
        margin: 0;
        position: relative;
    }

    .user-type-switch .form-check-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .user-type-switch .user-type-label {
        display: inline-block;
        padding: 12px 24px;
        margin: 0 5px;
        background: #f8f9fa;
        border: 2px solid var(--color-border);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: var(--transition-normal);
        font-weight: 500;
        text-align: center;
        color: var(--color-text);
        min-width: 120px;
        box-shadow: var(--shadow-input);
        position: relative;
        overflow: hidden;
    }

    .user-type-switch .user-type-label:hover {
        background: var(--color-input-bg-hover);
        border-color: var(--color-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(19, 192, 230, 0.2);
    }

    .user-type-switch .form-check-input:checked + .user-type-label {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        border-color: var(--color-primary);
        color: white;
        transform: translateY(-3px);
        box-shadow: var(--shadow-btn);
    }

    .user-type-switch .form-check-input:checked + .user-type-label::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
        left: 100%;
    }

    .user-type-switch .form-check-input:focus + .user-type-label {
        box-shadow: 0 0 0 0.2rem rgba(19, 192, 230, 0.25);
    }

    /* Animación adicional */
    .user-type-switch .user-type-label::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .user-type-switch .form-check-input:checked + .user-type-label::after {
        width: 300px;
        height: 300px;
    }

    /* Responsive para móviles */
    @media (max-width: 576px) {
        .user-type-switch {
            flex-direction: column;
            align-items: center;
        }
        
        .user-type-switch .user-type-label {
            margin: 5px 0;
            width: 200px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in py-4">
    <x-page-header title="Crear Nuevo Usuario" icon="user-plus">
    </x-page-header>
    <br>
    <div class="dashboard-card">    
        <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data" id="userForm">
            @csrf
            
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-fill mb-4" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                        <i data-feather="user" class="me-2"></i>Información Personal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="access-tab" data-bs-toggle="tab" data-bs-target="#access" type="button" role="tab">
                        <i data-feather="lock" class="me-2"></i>Información de Acceso
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
                        <i data-feather="map-pin" class="me-2"></i>Información de Ubicación
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                        <i data-feather="file-text" class="me-2"></i>Documentos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="organization-tab" data-bs-toggle="tab" data-bs-target="#organization" type="button" role="tab">
                        <i data-feather="git-branch" class="me-2"></i>Información Organizacional
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="userTabsContent">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="form-section">
                        <!-- Photo Upload Section -->
                        <div class="photo-upload-section d-flex justify-content-center align-items-center">
                            <label for="tx_url_photo" class="photo-preview-container">
                                <img src="{{ asset('img/profile/no.png') }}" alt="Preview" id="photoPreview" style="width: 250px; height: 250px;">
                            </label>
                            <input type="file" id="tx_url_photo" name="tx_url_photo" class="photo-input" accept="image/*" style="display: none !important;">
                        </div>
                        <br>
                        <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold required-field text-center d-block mb-3">Tipo de Usuario</label>
                                    <div class="user-type-switch d-flex justify-content-center flex-wrap gap-3">
                                        @foreach($userTypes as $index => $type)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input user-type-radio" 
                                                       type="radio" 
                                                       name="co_tipo_usuario" 
                                                       id="user_type_{{ $type->co_tipo_usuario }}" 
                                                       value="{{ $type->co_tipo_usuario }}"
                                                       {{ old('co_tipo_usuario') ? (old('co_tipo_usuario') == $type->co_tipo_usuario ? 'checked' : '') : (stripos($type->tx_tipo_usuario, 'Usuario de Sistema') !== false ? 'checked' : '') }}
                                                       required>
                                                <label class="form-check-label user-type-label" for="user_type_{{ $type->co_tipo_usuario }}">
                                                    {{ $type->tx_tipo_usuario }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                     <div class="invalid-feedback text-center">Por favor seleccione un tipo de usuario</div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_primer_nombre" class="form-label fw-bold required-field">Primer Nombre</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_primer_nombre" 
                                        name="tx_primer_nombre" 
                                        value="{{ old('tx_primer_nombre') }}"
                                        required>
                                    <div class="invalid-feedback">Por favor ingrese el primer nombre</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_segundo_nombre" class="form-label fw-bold">Inicial del Segundo Nombre</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_segundo_nombre" 
                                        name="tx_segundo_nombre" 
                                        value="{{ old('tx_segundo_nombre') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_primer_apellido" class="form-label fw-bold required-field">Primer Apellido</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_primer_apellido" 
                                        name="tx_primer_apellido" 
                                        value="{{ old('tx_primer_apellido') }}"
                                        required>
                                    <div class="invalid-feedback">Por favor ingrese el primer apellido</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_segundo_apellido" class="form-label fw-bold">Segundo Apellido</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_segundo_apellido" 
                                        name="tx_segundo_apellido" 
                                        value="{{ old('tx_segundo_apellido') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_telefono" class="form-label fw-bold required-field">Teléfono</label>
                                    <input type="tel" 
                                        class="form-control" 
                                        id="tx_telefono" 
                                        name="tx_telefono" 
                                        value="{{ old('tx_telefono') }}"
                                        required>
                                    <div class="invalid-feedback">Por favor ingrese un número de teléfono</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_email" class="form-label fw-bold required-field">Correo Electrónico</label>
                                    <input type="email" 
                                        class="form-control" 
                                        id="tx_email" 
                                        name="tx_email" 
                                        value="{{ old('tx_email') }}"
                                        required>
                                    <div class="invalid-feedback">Por favor ingrese un correo electrónico válido</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fe_nac" class="form-label fw-bold required-field">Fecha Nacimiento</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="fe_nac" 
                                        name="fe_nac" 
                                        value="{{ old('fe_nac') }}"
                                        required>
                                    <div class="invalid-feedback">El formato de fecha debe ser MM/DD/YYYY</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="co_idioma" class="form-label fw-bold required-field">Lenguaje Principal</label>
                                    <select name="co_idioma" id="co_idioma" class="form-select" required>
                                        <option value="">Seleccionar Idioma</option>
                                        @foreach($languages as $language)
                                        <option value="{{ $language->co_idioma }}" {{ 
                                            old('co_idioma') ? (old('co_idioma') == $language->co_idioma ? 'selected' : '') : ($language->co_idioma == 'Español' ? 'selected' : '') 
                                        }}>{{ $language->tx_idioma }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione un lenguaje</div>
                                </div>

                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="advanced-search-btn next-tab-btn" data-target="#access">
                            <i data-feather="arrow-right" class="me-2"></i>
                            Siguiente Paso
                        </button>
                    </div>
                </div>

                <!-- Access Information Tab -->
                <div class="tab-pane fade" id="access" role="tabpanel">
                    <div class="form-section">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_password" class="form-label fw-bold required-field">Contraseña</label>
                                    {{--<input type="password" class="form-control" id="tx_password" name="tx_password" 
                                    value="{{old('tx_password')}}"
                                    required>
                                    <div class="invalid-feedback"></div>--}}
                                    <x-password-input 
                                        name="tx_password" 
                                        id="tx_password"
                                        value="{{old('tx_password')}}"
                                    />
                                    <div class="invalid-feedback">Por favor ingrese la contraseña</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label fw-bold required-field">Confirmar Contraseña</label>
                                    {{--    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                    value="{{old('password_confirmation')}}"
                                    required>
                                    <div class="invalid-feedback">Por favor confirme la contraseña</div>--}}
                                    <x-password-input 
                                        name="password_confirmation" 
                                        id="password_confirmation"
                                        value="{{old('password_confirmation')}}"
                                    />
                                    <div class="invalid-feedback">Por favor confirme la contraseña</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="advanced-search-btn next-tab-btn" data-target="#location">
                            <i data-feather="arrow-right" class="me-2"></i>
                            Siguiente Paso
                        </button>
                    </div>
                </div>

                <!-- Location Information Tab -->
                <div class="tab-pane fade" id="location" role="tabpanel">
                    <div class="form-section">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_direccion1" class="form-label fw-bold required-field">Dirección 1</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_direccion1" 
                                        name="tx_direccion1" 
                                        value="{{ old('tx_direccion1') }}"
                                        required>
                                    <div class="invalid-feedback">Por favor ingrese la dirección principal</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_direccion2" class="form-label fw-bold">Dirección 2</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_direccion2" 
                                        name="tx_direccion2" 
                                        value="{{ old('tx_direccion2') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_zip" class="form-label fw-bold required-field">Código ZIP</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="tx_zip" 
                                        name="tx_zip" 
                                        value="{{ old('tx_zip') }}"
                                        required>
                                    <div class="invalid-feedback">Por favor ingrese el código ZIP</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Office_City_ID" class="form-label fw-bold required-field">Ciudad de Oficina</label>
                                    <select class="form-select" id="Office_City_ID" name="Office_City_ID" required>
                                        <option value="">Seleccionar Ciudad</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->co_oficina }}" {{ old('Office_City_ID') == $office->co_oficina ? 'selected' : '' }}>{{ $office->tx_nombre }}</option>
                                        @endforeach
                                    </select>
                                     <div class="invalid-feedback">Por favor seleccione una ciudad</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="advanced-search-btn next-tab-btn" data-target="#documents">
                            <i data-feather="arrow-right" class="me-2"></i>
                            Siguiente Paso
                        </button>
                    </div>
                </div>

                <!-- Documentos Tab -->
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="form-section">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_url_drive" class="form-label fw-bold">Licencia de Conducir o ID</label>
                                    <input type="file" class="form-control" id="tx_url_drive" name="tx_url_drive" accept="image/*,application/pdf">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_url_paquete" class="form-label fw-bold">Paquete de Incorporación</label>
                                    <input type="file" class="form-control" id="tx_url_paquete" name="tx_url_paquete" accept="application/pdf">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_url_formaw9" class="form-label fw-bold">Forma W9</label>
                                    <input type="file" class="form-control" id="tx_url_formaw9" name="tx_url_formaw9" accept="application/pdf">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tx_url_appempl" class="form-label fw-bold">Aplicación del Empleado</label>
                                    <input type="file" class="form-control" id="tx_url_appempl" name="tx_url_appempl" accept="application/pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="advanced-search-btn next-tab-btn" data-target="#organization">
                            <i data-feather="arrow-right" class="me-2"></i>
                            Siguiente Paso
                        </button>
                    </div>
                </div>

                <!-- Organizational Information Tab -->
                <div class="tab-pane fade" id="organization" role="tabpanel">
                    <div class="form-section">
                        <div class="row g-4">
                            <div class="col-md-6 non-plumber-field">
                                <div class="form-group">
                                    <label for="co_usuario_padre" class="form-label fw-bold required-field">Usuario Padre</label>
                                    <select class="form-select" id="co_usuario_padre" name="co_usuario_padre" required>
                                        <option value="">Seleccionar Usuario Padre</option>
                                        @foreach($activeUsers as $user)
                                            <option value="{{ $user->co_usuario }}" {{ old('co_usuario_padre') == $user->co_usuario ? 'selected' : '' }}>{{ $user->tx_primer_nombre }} {{ $user->tx_primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione un usuario padre</div>
                                </div>
                            </div>

                            <div class="col-md-6 non-plumber-field">
                                <div class="form-group">
                                    <label for="co_usuario_reclutador" class="form-label fw-bold required-field">Usuario Reclutador</label>
                                    <select class="form-select" id="co_usuario_reclutador" name="co_usuario_reclutador" required>
                                        <option value="">Seleccionar Reclutador</option>
                                        @foreach($activeUsers as $user)
                                            <option value="{{ $user->co_usuario }}" {{ old('co_usuario_reclutador') == $user->co_usuario ? 'selected' : '' }}>{{ $user->tx_primer_nombre }} {{ $user->tx_primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                     <div class="invalid-feedback">Por favor seleccione un reclutador</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="co_estatus_usuario" class="form-label fw-bold required-field">Status del Usuario</label>
                                    <select class="form-select" id="co_estatus_usuario" name="co_estatus_usuario" required>
                                        <option value="">Seleccionar el status del usuario</option>
                                        @foreach($userStatuses as $status)
                                            <option value="{{ $status->co_estatus_usuario }}" {{ old('co_estatus_usuario') == $status->co_estatus_usuario ? 'selected' : '' }}>{{ $status->tx_estatus }}</option>
                                        @endforeach
                                    </select>
                                     <div class="invalid-feedback">Por favor seleccione el status del usuario</div>
                                </div>
                            </div>

                            <div class="col-md-6 non-plumber-field">
                                <div class="form-group">
                                    <label for="co_office_manager" class="form-label fw-bold required-field">Gerente de Oficina</label>
                                    <select class="form-select" id="co_office_manager" name="co_office_manager" required>
                                        <option value="">Seleccionar Gerente</option>
                                        @foreach($officeManagers as $manager)
                                            <option value="{{ $manager->co_usuario }}" {{ old('co_office_manager') == $manager->co_usuario ? 'selected' : '' }}>{{ $manager->tx_primer_nombre }} {{ $manager->tx_primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                     <div class="invalid-feedback">Por favor seleccione un gerente</div>
                                </div>
                            </div>

                            <div class="col-md-6 non-plumber-field">
                                <div class="form-group">
                                    <label for="co_sponsor" class="form-label fw-bold required-field">Patrocinador</label>
                                    <select class="form-select" id="co_sponsor" name="co_sponsor" required>
                                        <option value="">Seleccionar Patrocinador</option>
                                        @foreach($activeUsers as $user)
                                            <option value="{{ $user->co_usuario }}" {{ old('co_sponsor') == $user->co_usuario ? 'selected' : '' }}>{{ $user->tx_primer_nombre }} {{ $user->tx_primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione un patrocinador</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="co_rol" class="form-label fw-bold required-field">Rol</label>
                                    <select class="form-select" id="co_rol" name="co_rol" required>
                                        <option value="">Seleccionar Rol</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->co_rol }}" {{ old('co_rol') == $role->co_rol ? 'selected' : '' }}>{{ $role->tx_nombre }}</option>
                                        @endforeach
                                    </select>
                                     <div class="invalid-feedback">Por favor seleccione un rol</div>
                                </div>
                            </div>
                            {{--
                            <div class="col-md-6 non-plumber-field">
                                <div class="form-group">
                                    <label for="departamento" class="form-label fw-bold">Departamento</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="departamento"
                                        name="departamento"
                                        value="{{ old('departamento') }}">
                                </div>
                            </div>
                            --}}
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="advanced-search-btn me-3 mb-2">
                            <i data-feather="user-plus" class="me-2"></i>
                            Crear Usuario
                        </button>
                        <button type="reset" class="btn btn-secondary mb-2">
                            <i data-feather="refresh-cw" class="me-2"></i>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>
</div>

@push('scripts')
<script>
    // Mensajes de éxito y error
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
</script>
<script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#fe_nac').datepicker({
            dateFormat: 'mm/dd/yyyy' // Formato de fecha
        });
       
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        const form = document.getElementById('userForm');
        const tabButtons = document.querySelectorAll('.next-tab-btn');
        const tabs = document.querySelectorAll('.tab-pane');

        // Manejo de navegación entre tabs
        tabButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const targetTabId = this.getAttribute('data-target');
                const nextTabButton = document.querySelector(`[data-bs-target="${targetTabId}"]`);      
                if (nextTabButton) {
                    nextTabButton.click();
                }
                
                // Reinicializar Feather Icons después de cambiar de tab
                if (typeof feather !== 'undefined') {
                    setTimeout(function() {
                        feather.replace();
                    }, 100);
                }
            });
        });

        // Validación unificada del formulario
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            let isValid = true;
            const requiredFields = form.querySelectorAll('.required-field');
            const firstInvalidField = { element: null, tabPane: null };

            // Validar campos requeridos (solo los que no están ocultos)
            requiredFields.forEach(field => {
                // Verificar si el campo está en un contenedor oculto para plomeros
                const isHiddenForPlumber = field.closest('.hide-for-plumber') !== null;
                
                // Solo validar si el campo no está oculto
                if (!isHiddenForPlumber) {
                    const inputElement = field.closest('.form-group').querySelector('input, select');
                    if (inputElement) {
                        const isEmpty = inputElement.type === 'select-one' 
                            ? !inputElement.value 
                            : !inputElement.value.trim();

                        if (isEmpty) {
                            inputElement.classList.add('is-invalid');
                            isValid = false;

                            // Guardar referencia al primer campo inválido
                            if (!firstInvalidField.element) {
                                firstInvalidField.element = inputElement;
                                firstInvalidField.tabPane = inputElement.closest('.tab-pane');
                            }
                        } else {
                            inputElement.classList.remove('is-invalid');
                        }
                    }
                }
            });

            // Validación específica de contraseñas
            const password = form.querySelector('#tx_password');
            const passwordConfirm = form.querySelector('#password_confirmation');
            
            
            if (password || passwordConfirm ) {
                // Validar que ambos campos tengan valor
                if (!password.value.trim() || !passwordConfirm.value.trim()) {
                    if (!password.value.trim()) {
                        password.classList.add('is-invalid');
                        let feedback = password.closest('.form-group').querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = 'Por favor ingrese una contraseña';
                            feedback.classList.add('d-block');
                        }
                    }
                    if (!passwordConfirm.value.trim()) {
                        passwordConfirm.classList.add('is-invalid');
                        let feedback = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = 'Por favor confirme la contraseña';
                            feedback.classList.add('d-block');
                        }
                    }
                    isValid = false;

                    if (!firstInvalidField.element) {
                        firstInvalidField.element = !password.value.trim() ? password : passwordConfirm;                       
                        firstInvalidField.tabPane = password.closest('.tab-pane');
                    }
                }
                else if (password.value.length < 6) {
                    password.classList.add('is-invalid');
                    let feedback = password.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = 'La contraseña debe tener al menos 6 caracteres';
                        feedback.classList.add('d-block');
                    }
                    isValid = false;

                    if (!firstInvalidField.element) {
                        firstInvalidField.element = password;  
                        firstInvalidField.tabPane = password.closest('.tab-pane');                    
                    }
                }                
                else if (password.value !== passwordConfirm.value) {
                    passwordConfirm.classList.add('is-invalid');
                    let feedback = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = 'Las contraseñas no coinciden';
                        feedback.classList.add('d-block');
                    }
                    isValid = false;

                    if (!firstInvalidField.element) {
                        firstInvalidField.element = passwordConfirm;                     
                        firstInvalidField.tabPane = passwordConfirm.closest('.tab-pane');
                    }
                }                
                else {
                    password.classList.remove('is-invalid');
                    passwordConfirm.classList.remove('is-invalid');
                    let feedbackPassword = password.closest('.form-group').querySelector('.invalid-feedback');
                    let feedbackConfirm = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedbackPassword) {
                        feedbackPassword.textContent = '';
                        feedbackPassword.classList.remove('d-block');
                    }
                    if (feedbackConfirm) {
                        feedbackConfirm.textContent = '';
                        feedbackConfirm.classList.remove('d-block');
                    }
                }
            } else if (passwordConfirm) {
                passwordConfirm.classList.remove('is-invalid');
                let feedback = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                    feedback.classList.remove('d-block');
                }
            }

            const emailInput = form.querySelector('#tx_email');
            if (emailInput) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    isValid = false;
                    let feedback = emailInput.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = 'Por favor ingrese un correo electrónico válido';
                    }
                            
                    if (!firstInvalidField.element) {
                        firstInvalidField.element = emailInput;
                        firstInvalidField.tabPane = emailInput.closest('.tab-pane');
                    }
                } else {
                    emailInput.classList.remove('is-invalid');
                }
            }

            // Validación de fecha de nacimiento
            const fechaNacimientoInput = form.querySelector('#fe_nac');
            if (fechaNacimientoInput) {
                // Expresión regular para validar formato mm/dd/yyyy
                const regexFecha = /^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/;
                
                if (fechaNacimientoInput.value.trim()) {                   
                    console.log(regexFecha.test(fechaNacimientoInput.value));    
                    if (!regexFecha.test(fechaNacimientoInput.value)) {
                        fechaNacimientoInput.classList.add('is-invalid');
                        let feedback = fechaNacimientoInput.closest('.form-group').querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = 'El formato de fecha debe ser mm/dd/yyyy';
                            feedback.classList.add('d-block');
                        }
                        fechaNacimientoInput.value = ''; // Limpiar el campo
                        fechaNacimientoInput.focus(); // Enfocar el campo
                        isValid = false;

                        if (!firstInvalidField.element) {
                            firstInvalidField.element = fechaNacimientoInput;
                            firstInvalidField.tabPane = fechaNacimientoInput.closest('.tab-pane');
                        }
                    } else {
                        fechaNacimientoInput.classList.remove('is-invalid');
                        let feedback = fechaNacimientoInput.closest('.form-group').querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = '';
                            feedback.classList.remove('d-block');
                        }
                    }
                }
            }
            
            if (!isValid && firstInvalidField.tabPane) {
                const tabId = firstInvalidField.tabPane.id;
                const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
                if (tabButton) {
                    tabButton.click();
                }
                firstInvalidField.element.focus();
                return false;
            }

            // Si todo es válido, enviar el formulario
            if (isValid) {
                form.submit();
            }
        });
        
        // Limpiar validaciones al escribir en inputs o cambiar selects
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('input', function() {
                // Remover clase is-invalid del input/select
                this.classList.remove('is-invalid');
                
                // Remover clase is-invalid del contenedor form-group
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.classList.remove('is-invalid');
                }
                
                // Limpiar mensaje de feedback
                const feedback = this.closest('.form-group')?.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                    feedback.classList.remove('d-block');
                }
            });
        });

        // Para los selects, también necesitamos el evento change
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                // Remover clase is-invalid del select
                this.classList.remove('is-invalid');
                
                // Remover clase is-invalid del contenedor form-group
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.classList.remove('is-invalid');
                }
                
                // Limpiar mensaje de feedback
                const feedback = this.closest('.form-group')?.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                    feedback.classList.remove('d-block');
                }
            });
        });

        // Limpiar validación personalizada al escribir en el campo de confirmación de contraseña
        const passwordConfirm = form.querySelector('#password_confirmation');
        if (passwordConfirm) {
            passwordConfirm.addEventListener('input', function() {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            });
        }

        // Formateo de número de teléfono
        const phoneInput = document.getElementById('tx_telefono');
        if (phoneInput) {
            phoneInput.addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });
        }

        // Vista previa de foto
        const photoInput = document.getElementById('tx_url_photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const preview = document.getElementById('photoPreview');
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.src = "{{ asset('img/profile/no.png') }}";
                }
            });
        }

        // Manejo del tipo de usuario para mostrar/ocultar campos de Plomero
        const tipoUsuarioRadios = document.querySelectorAll('input[name="co_tipo_usuario"]');
        const rolSelect = document.getElementById('co_rol');
        const nonPlumberFields = document.querySelectorAll('.non-plumber-field');

        function handleUserTypeChange() {
            const selectedRadio = document.querySelector('input[name="co_tipo_usuario"]:checked');
            if (!selectedRadio) return;
            
            const selectedLabel = document.querySelector(`label[for="${selectedRadio.id}"]`);
            const tipoUsuarioText = selectedLabel ? selectedLabel.textContent.toLowerCase() : '';
            
            if (tipoUsuarioText.includes('plomero')) {
                // Ocultar campos no necesarios para plomero
                nonPlumberFields.forEach(field => {
                    field.classList.add('hide-for-plumber');
                    
                    // Remover el required de los campos ocultos y limpiar validaciones
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                        input.classList.remove('is-invalid');
                        input.value = ''; // Limpiar el valor del campo
                        
                        // Limpiar mensaje de feedback
                        const feedback = input.closest('.form-group')?.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = '';
                            feedback.classList.remove('d-block');
                        }
                    });
                });

                // Asignar automáticamente el rol de Plomero y deshabilitar el select
                if (rolSelect) {
                    const plomeroOption = Array.from(rolSelect.options).find(option => 
                        option.text.toLowerCase().includes('plomero')
                    );
                    if (plomeroOption) {
                        rolSelect.value = plomeroOption.value;
                        rolSelect.classList.remove('is-invalid');
                    }
                    // Deshabilitar el select de rol para plomeros
                    rolSelect.disabled = true;
                    rolSelect.style.backgroundColor = '#e9ecef';
                    rolSelect.style.opacity = '0.65';
                }
            } else {
                // Mostrar campos para otros tipos de usuario
                nonPlumberFields.forEach(field => {
                    field.classList.remove('hide-for-plumber');
                    
                    // Restaurar el required en los campos mostrados
                    const requiredInputs = field.querySelectorAll('input[data-required], select[data-required]');
                    requiredInputs.forEach(input => {
                        input.setAttribute('required', 'required');
                        // Limpiar cualquier estado de validación previo
                        input.classList.remove('is-invalid');
                        
                        // Limpiar mensaje de feedback
                        const feedback = input.closest('.form-group')?.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = '';
                            feedback.classList.remove('d-block');
                        }
                    });
                });

                // Habilitar el select de rol y limpiar la selección automática
                if (rolSelect) {
                    rolSelect.disabled = false;
                    rolSelect.style.backgroundColor = '';
                    rolSelect.style.opacity = '';
                    
                    // Limpiar la selección automática del rol de plomero
                    if (rolSelect.value) {
                        const currentOption = rolSelect.options[rolSelect.selectedIndex];
                        if (currentOption && currentOption.text.toLowerCase().includes('plomero')) {
                            rolSelect.value = '';
                        }
                    }
                    rolSelect.classList.remove('is-invalid');
                }
            }
        }

        // Marcar campos requeridos originalmente
        nonPlumberFields.forEach(field => {
            const requiredInputs = field.querySelectorAll('input[required], select[required]');
            requiredInputs.forEach(input => {
                input.setAttribute('data-required', 'true');
            });
        });

        // Agregar event listeners a los radio buttons
        tipoUsuarioRadios.forEach(radio => {
            radio.addEventListener('change', handleUserTypeChange);
        });

        // Ejecutar al cargar la página si ya hay un valor seleccionado
        const selectedRadio = document.querySelector('input[name="co_tipo_usuario"]:checked');
        if (selectedRadio) {
            handleUserTypeChange();
        }

        // Inicialización de tabs de Bootstrap
        var triggerTabList = [].slice.call(document.querySelectorAll('#userTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
                
                if (typeof feather !== 'undefined') {
                    setTimeout(function() {
                        feather.replace();
                    }, 100);
                }
            })
        });
    });
</script>
@endpush
@endsection