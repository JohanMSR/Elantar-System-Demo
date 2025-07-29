@extends('layouts.master')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    Editar Usuario - Centro de Negocios
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
        box-shadow: none !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem rgba(19, 192, 230, 0.25) !important;
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
    
    .section-card {
        background-color: white;
        border: none;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-input);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: var(--transition-normal);
    }
    
    .section-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-card);
    }
    
    .section-card .card-header {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary)) !important;
        border-bottom: none;
        padding: 1rem 1.5rem;
        color: white !important;
    }
    
    .section-card .card-header h5 {
        color: white !important;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .section-card .card-body {
        padding: 1.5rem;
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
        height: 100%;
        line-height: 1.5;
    }
    
    .btn-secondary:hover {
        background: #7f8c8d;
        color: #ffffff;
        transform: translateY(-2px);
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
        padding: 10px 0;
    }

    .photo-preview-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .photo-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .photo-preview-container:hover {
        box-shadow: 0 6px 12px rgba(19, 192, 230, 0.3);
        transform: translateY(-3px);
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
    
    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
        .col-md-6, .col-md-4 {
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
</style>
@endpush

@section('content')
<div class="container-fluid fade-in py-4">
    <x-page-header title="Editar Usuario" icon="edit">
    </x-page-header>
    <br>    
    <form id="userForm" action="{{ route('users.update', $user->co_usuario) }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <!-- Hidded fields documens -->
        <input type="hidden" name="tx_url_drive_old" value="{{ old('tx_url_drive', $user->tx_url_drive) }}">
        <input type="hidden" name="tx_url_paquete_old" value="{{ old('tx_url_paquete', $user->tx_url_paquete) }}">
        <input type="hidden" name="tx_url_formaw9_old" value="{{ old('tx_url_formaw9', $user->tx_url_formaw9) }}">
        <input type="hidden" name="tx_url_appempl_old" value="{{ old('tx_url_appempl', $user->tx_url_appempl) }}">
        <input type="hidden" name="tx_url_photo_old" value="{{ old('tx_url_photo', $user->image_path) }}">
        
        <!-- Personal Information Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="user" class="me-2"></i>Información Personal
                </h5>
            </div>
            <div class="card-body">
                <!-- Photo Upload Section -->                
                <div class="photo-upload-section d-flex justify-content-center align-items-center mb-4">
                    <label for="tx_url_photo" class="photo-preview-container">
                        @if (old('tx_url_photo', $user->image_path))
                            <img src="{{ url('storage/'. old('tx_url_photo', $user->image_path)) }}" alt="Preview" id="photoPreview" style="width: 150px; height: 150px;">                        
                        @else
                            <img src="{{ asset('img/profile/no.png') }}" alt="Preview" id="photoPreview" style="width: 150px; height: 150px;">
                        @endif
                    </label>
                    <input type="file" id="tx_url_photo" name="tx_url_photo" class="photo-input" accept="image/*" style="display: none !important;">
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6 form-group">
                        <label for="tx_primer_nombre" class="form-label fw-bold required-field">Primer Nombre</label>
                        <input type="text" class="form-control" id="tx_primer_nombre" name="tx_primer_nombre" 
                               value="{{ old('tx_primer_nombre', $user->tx_primer_nombre) }}" required placeholder="Ingrese el primer nombre">
                        <div class="invalid-feedback">Por favor ingrese el primer nombre</div>
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label for="tx_primer_apellido" class="form-label fw-bold required-field">Primer Apellido</label>
                        <input type="text" class="form-control" id="tx_primer_apellido" name="tx_primer_apellido" 
                               value="{{ old('tx_primer_apellido', $user->tx_primer_apellido) }}" required placeholder="Ingrese el primer apellido">
                        <div class="invalid-feedback">Por favor ingrese el primer apellido</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_email" class="form-label fw-bold required-field">Email</label>
                        <input type="email" class="form-control" id="tx_email" name="tx_email" 
                               value="{{ old('tx_email', $user->tx_email) }}" required placeholder="ejemplo@correo.com">
                        <div class="invalid-feedback">Por favor ingrese un email válido</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_telefono" class="form-label fw-bold required-field">Teléfono</label>
                        <input type="tel" class="form-control" id="tx_telefono" name="tx_telefono" 
                               value="{{ old('tx_telefono', $user->tx_telefono) }}" required placeholder="(123) 456-7890">
                        <div class="invalid-feedback">Por favor ingrese un número de teléfono</div>
                    </div>
                    
                    <div class="col-md-6 form-group">                        
                            <label for="fe_nac" class="form-label fw-bold required-field">Fecha Nacimiento</label>
                            <input type="text" 
                                class="form-control" 
                                id="fe_nac" 
                                name="fe_nac" 
                                value="{{ old('fe_nac',Carbon::parse($user->fe_nac)->format('m/d/Y')) }}"
                                required>
                            <div class="invalid-feedback">El formato de fecha debe ser MM/DD/YYYY</div>                        
                    </div>
                    <div class="col-md-6 form-group">
                            <label for="co_idioma" class="form-label fw-bold required-field">Lenguaje Principal</label>
                            <select name="co_idioma" id="co_idioma" class="form-select" required>
                                <option value="">Seleccionar Idioma</option>
                                @foreach($languages as $language)
                                <option value="{{ $language->co_idioma }}"  
                                    {{--old('co_idioma') ? (old('co_idioma') == $language->co_idioma ? 'selected' : '') : ($language->co_idioma == 'Español' ? 'selected' : '') --}}
                                    {{ old('co_idioma', $user->co_idioma) == $language->co_idioma ? 'selected' : '' }}
                                >{{ $language->tx_idioma }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Por favor seleccione un idioma</div>                       
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label for="co_tipo_usuario" class="form-label fw-bold required-field">Tipo de Usuario</label>
                        <select class="form-select" id="co_tipo_usuario" name="co_tipo_usuario" required>
                            <option value="">Seleccionar Tipo</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type->co_tipo_usuario }}" {{ old('co_tipo_usuario', $user->co_tipo_usuario) == $type->co_tipo_usuario ? 'selected' : '' }}>
                                    {{ $type->tx_tipo_usuario }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un tipo de usuario</div>
                    </div>                    
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="lock" class="me-2"></i>Cambiar Contraseña
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 form-group">
                        <label for="tx_password" class="form-label fw-bold">Nueva Contraseña</label>
                        {{--<input type="password" class="form-control" id="tx_password" name="tx_password" 
                               placeholder="Ingrese la nueva contraseña">--}}
                               <x-password-input 
                               name="tx_password" 
                               id="tx_password"
                               value="{{old('tx_password')}}"
                               :required="false"
                           />       
                        <div class="invalid-feedback">Por favor ingrese una contraseña válida</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="password_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                        {{--<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                               placeholder="Confirme la nueva contraseña">--}}
                               <x-password-input 
                                    name="password_confirmation" 
                                    id="password_confirmation"
                                    value="{{old('password_confirmation')}}"
                                    :required="false"
                                />     
                        <div class="invalid-feedback">Las contraseñas no coinciden</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Address Information Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="map-pin" class="me-2"></i>Información de Dirección
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 form-group">
                        <label for="tx_direccion1" class="form-label fw-bold required-field">Dirección 1</label>
                        <input type="text" class="form-control" id="tx_direccion1" name="tx_direccion1" 
                               value="{{ old('tx_direccion1', $user->tx_direccion1) }}" required placeholder="Ingrese la dirección principal">
                        <div class="invalid-feedback">Por favor ingrese la dirección principal</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_direccion2" class="form-label fw-bold">Dirección 2</label>
                        <input type="text" class="form-control" id="tx_direccion2" name="tx_direccion2" 
                               value="{{ old('tx_direccion2', $user->tx_direccion2) }}" placeholder="Apartamento, suite, unidad, etc. (opcional)">
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_zip" class="form-label fw-bold required-field">ZIP</label>
                        <input type="text" class="form-control" id="tx_zip" name="tx_zip" 
                               value="{{ old('tx_zip', $user->tx_zip) }}" required placeholder="Código postal">
                        <div class="invalid-feedback">Por favor ingrese el código postal</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="Office_City_ID" class="form-label fw-bold required-field">Ciudad de Oficina</label>
                        <select class="form-select" id="Office_City_ID" name="Office_City_ID" required>
                            <option value="">Seleccionar Ciudad</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->co_oficina }}" {{ old('Office_City_ID', $user->Office_City_ID) == $office->co_oficina ? 'selected' : '' }}>
                                    {{ $office->tx_nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione una ciudad de oficina</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role and Office Information Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="users" class="me-2"></i>Información de Rol y Oficina
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 form-group">
                        <label for="co_rol" class="form-label fw-bold required-field">Rol</label>
                        <select class="form-select" id="co_rol" name="co_rol" required>
                            <option value="">Seleccionar Rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->co_rol }}" {{ old('co_rol', $user->co_rol) == $role->co_rol ? 'selected' : '' }}>
                                    {{ $role->tx_nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un rol</div>
                    </div>



                    <div class="col-md-4 form-group">
                        <label for="co_estatus_usuario" class="form-label fw-bold required-field">Estatus</label>
                        <select class="form-select" id="co_estatus_usuario" name="co_estatus_usuario" required>
                            <option value="">Seleccionar Estatus</option>
                            @foreach($userStatuses as $status)
                                <option value="{{ $status->co_estatus_usuario }}" {{ old('co_estatus_usuario', $user->co_estatus_usuario) == $status->co_estatus_usuario ? 'selected' : '' }}>
                                    {{ $status->tx_estatus }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un estatus</div>
                    </div>

                    <div class="col-md-6 form-group non-plumber-field">
                        <label for="co_usuario_padre" class="form-label fw-bold required-field">Usuario Padre</label>
                        <select class="form-select" id="co_usuario_padre" name="co_usuario_padre" required>
                            <option value="">Seleccionar Usuario Padre</option>
                            @foreach($potentialParents as $parent)
                                <option value="{{ $parent->co_usuario }}" {{ old('co_usuario_padre', $user->co_usuario_padre) == $parent->co_usuario ? 'selected' : '' }}>
                                    {{ $parent->tx_primer_nombre }} {{ $parent->tx_primer_apellido }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un usuario padre</div>
                    </div>

                    <div class="col-md-6 form-group non-plumber-field">
                        <label for="co_office_manager" class="form-label fw-bold required-field">Gerente de Oficina</label>
                        <select class="form-select" id="co_office_manager" name="co_office_manager" required>
                            <option value="">Seleccionar Gerente</option>
                            @foreach($officeManagers as $manager)
                                <option value="{{ $manager->co_usuario }}" {{ old('co_office_manager', $user->co_office_manager) == $manager->co_usuario ? 'selected' : '' }}>
                                    {{ $manager->tx_primer_nombre }} {{ $manager->tx_primer_apellido }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un gerente de oficina</div>
                    </div>

                    <div class="col-md-6 form-group non-plumber-field">
                        <label for="co_usuario_reclutador" class="form-label fw-bold required-field">Reclutador</label>
                        <select class="form-select" id="co_usuario_reclutador" name="co_usuario_reclutador" required>
                            <option value="">Seleccionar Reclutador</option>
                            @foreach($potentialParents as $parent)
                                <option value="{{ $parent->co_usuario }}" {{ old('co_usuario_reclutador', $user->co_usuario_reclutador) == $parent->co_usuario ? 'selected' : '' }}>
                                    {{ $parent->tx_primer_nombre }} {{ $parent->tx_primer_apellido }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un reclutador</div>
                    </div>

                    <div class="col-md-6 form-group non-plumber-field">
                        <label for="co_sponsor" class="form-label fw-bold required-field">Patrocinador</label>
                        <select class="form-select" id="co_sponsor" name="co_sponsor" required>
                            <option value="">Seleccionar Patrocinador</option>
                            @foreach($potentialParents as $parent)
                                <option value="{{ $parent->co_usuario }}" {{ old('co_sponsor', $user->co_sponsor) == $parent->co_usuario ? 'selected' : '' }}>
                                    {{ $parent->tx_primer_nombre }} {{ $parent->tx_primer_apellido }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un patrocinador</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="file-text" class="me-2"></i>Documentos
                </h5>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="row g-3">
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_drive" class="form-label fw-bold">Licencia de Conducir o ID</label>
                                <input type="file" class="form-control" id="tx_url_drive" name="tx_url_drive" accept="image/*,application/pdf">
                                @if(old('tx_url_drive', $user->tx_url_drive))
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. old('tx_url_drive', $user->tx_url_drive)) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast(old('tx_url_drive', $user->tx_url_drive), '/') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_paquete" class="form-label fw-bold">Paquete de Incorporación</label>
                                <input type="file" class="form-control" id="tx_url_paquete" name="tx_url_paquete" accept="application/pdf">
                                @if(old('tx_url_paquete', $user->tx_url_paquete))
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. old('tx_url_paquete', $user->tx_url_paquete)) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast(old('tx_url_paquete', $user->tx_url_paquete), '/') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_formaw9" class="form-label fw-bold">Forma W9</label>
                                <input type="file" class="form-control" id="tx_url_formaw9" name="tx_url_formaw9" accept="application/pdf">
                                
                                @if(old('tx_url_formaw9', $user->tx_url_formaw9))
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. old('tx_url_formaw9', $user->tx_url_formaw9)) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast(old('tx_url_formaw9', $user->tx_url_formaw9), '/') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_appempl" class="form-label fw-bold">Aplicación del Empleado</label>
                                <input type="file" class="form-control" id="tx_url_appempl" name="tx_url_appempl" accept="application/pdf">
                                @if(old('tx_url_appempl', $user->tx_url_appempl))
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. old('tx_url_appempl', $user->tx_url_appempl)) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast(old('tx_url_appempl', $user->tx_url_appempl), '/') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="advanced-search-btn" id="saveBtn">
                <i data-feather="save" class="me-2"></i>Guardar Cambios
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary" style="min-width: 180px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <i data-feather="x" class="me-2"></i>Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>
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
        
        // Validación unificada del formulario
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Verificar si el usuario es plomero y aplicar la lógica correspondiente
            const tipoUsuarioSelect = document.getElementById('co_tipo_usuario');
            if (tipoUsuarioSelect && tipoUsuarioSelect.value) {
                const selectedOption = tipoUsuarioSelect.options[tipoUsuarioSelect.selectedIndex];
                const tipoUsuarioText = selectedOption ? selectedOption.text.toLowerCase() : '';
                if (tipoUsuarioText.includes('plomero')) {
                    handleUserTypeChange();
                }
            }
            
            let isValid = true;
            const requiredFields = form.querySelectorAll('.required-field');
            const firstInvalidField = { element: null, tabPane: null };

            // Validar campos requeridos
            requiredFields.forEach(field => {
                const inputElement = field.closest('.form-group').querySelector('input, select');
                if (inputElement) {
                    // Verificar si el campo está oculto para plomeros
                    const isHiddenForPlumber = field.closest('.form-group').classList.contains('hide-for-plumber');
                    
                    // Si el campo está oculto para plomeros, saltarlo
                    if (isHiddenForPlumber) {
                        return;
                    }
                    
                    const isEmpty = inputElement.type === 'select-one' 
                        ? !inputElement.value 
                        : !inputElement.value.trim();

                    if (isEmpty) {
                        inputElement.classList.add('is-invalid');
                        isValid = false;

                        // Guardar referencia al primer campo inválido
                        if (!firstInvalidField.element) {
                            firstInvalidField.element = inputElement;
                            //firstInvalidField.tabPane = inputElement.closest('.tab-pane');
                        }
                    } else {
                        inputElement.classList.remove('is-invalid');
                    }
                }
            });

            // Validación específica de contraseñas
            const password = form.querySelector('#tx_password');
            const passwordConfirm = form.querySelector('#password_confirmation');
                      
            if ((password && password.value.trim()) || (passwordConfirm && passwordConfirm.value.trim())) {
    
                if (!password.value.trim() || !passwordConfirm.value.trim()) {
                    if (!password.value.trim()) {
                        password.classList.add('is-invalid');
                        let feedback = password.closest('.form-group').querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = 'Por favor ingrese una contraseña';
                            feedback.classList.add('d-block'); // Agregar d-block
                        }
                    }
                    if (!passwordConfirm.value.trim()) {
                        passwordConfirm.classList.add('is-invalid');
                        let feedback = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = 'Por favor confirme la contraseña';
                            feedback.classList.add('d-block'); // Agregar d-block
                        }
                    }
                    isValid = false;

                    if (!firstInvalidField.element) {
                        firstInvalidField.element = !password.value.trim() ? password : passwordConfirm;                       
                    }
                }
                else if (password.value.length < 6) {
                    password.classList.add('is-invalid');
                    let feedback = password.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = 'La contraseña debe tener al menos 6 caracteres';
                        feedback.classList.add('d-block'); // Agregar d-block
                    }
                    isValid = false;

                    if (!firstInvalidField.element) {
                        firstInvalidField.element = password;                      
                    }
                }                
                else if (password.value !== passwordConfirm.value) {
                    passwordConfirm.classList.add('is-invalid');
                    let feedback = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = 'Las contraseñas no coinciden';
                        feedback.classList.add('d-block'); // Agregar d-block
                    }
                    isValid = false;

                    if (!firstInvalidField.element) {
                        firstInvalidField.element = passwordConfirm;                     
                    }
                }                
                else {
                    password.classList.remove('is-invalid');
                    passwordConfirm.classList.remove('is-invalid');
                    let feedbackPassword = password.closest('.form-group').querySelector('.invalid-feedback');
                    let feedbackConfirm = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                    if (feedbackPassword) {
                        feedbackPassword.textContent = '';
                        feedbackPassword.classList.remove('d-block'); // Quitar d-block
                    }
                    if (feedbackConfirm) {
                        feedbackConfirm.textContent = '';
                        feedbackConfirm.classList.remove('d-block'); // Quitar d-block
                    }
                }
            } else if (passwordConfirm) {
                passwordConfirm.classList.remove('is-invalid');
                let feedback = passwordConfirm.closest('.form-group').querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                    feedback.classList.remove('d-block'); // Quitar d-block
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
                    }
                } else {
                    emailInput.classList.remove('is-invalid');
                }
            }                
            
            const fechaNacimientoInput = form.querySelector('#fe_nac');
            if (fechaNacimientoInput) {
                // Expresión regular para validar formato mm/dd/yyyy
                const regexFecha = /^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/;
                
                if (fechaNacimientoInput.value.trim()) {                   
                    //console.log(regexFecha.test(fechaNacimientoInput.value));    
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
                           // firstInvalidField.tabPane = fechaNacimientoInput.closest('.tab-pane');
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
            
            if (!isValid && firstInvalidField.element) {
                
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
        const tipoUsuarioSelect = document.getElementById('co_tipo_usuario');
        const rolSelect = document.getElementById('co_rol');
        const nonPlumberFields = document.querySelectorAll('.non-plumber-field');

        function handleUserTypeChange() {
            const selectedOption = tipoUsuarioSelect.options[tipoUsuarioSelect.selectedIndex];
            const tipoUsuarioText = selectedOption ? selectedOption.text.toLowerCase() : '';
            
            if (tipoUsuarioText.includes('plomero')) {
                // Ocultar campos no necesarios para plomero
                nonPlumberFields.forEach(field => {
                    field.classList.add('hide-for-plumber');
                    // Remover el required de los campos ocultos
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                        input.classList.remove('is-invalid');
                        // Limpiar mensaje de feedback
                        const feedback = input.closest('.form-group')?.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = '';
                            feedback.classList.remove('d-block');
                        }
                    });
                });

                // Asignar automáticamente el rol de Plomero
                if (rolSelect) {
                    const plomeroOption = Array.from(rolSelect.options).find(option => 
                        option.text.toLowerCase().includes('plomero')
                    );
                    if (plomeroOption) {
                        rolSelect.value = plomeroOption.value;
                        rolSelect.classList.remove('is-invalid');
                    }
                }
            } else {
                // Mostrar campos para otros tipos de usuario
                nonPlumberFields.forEach(field => {
                    field.classList.remove('hide-for-plumber');
                    // Restaurar el required en los campos mostrados
                    const requiredInputs = field.querySelectorAll('input[data-required], select[data-required]');
                    requiredInputs.forEach(input => {
                        input.setAttribute('required', 'required');
                    });
                });

                // Limpiar la selección automática del rol
                if (rolSelect && rolSelect.value) {
                    const currentOption = rolSelect.options[rolSelect.selectedIndex];
                    if (currentOption && currentOption.text.toLowerCase().includes('plomero')) {
                        rolSelect.value = '';
                    }
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

        if (tipoUsuarioSelect) {
            tipoUsuarioSelect.addEventListener('change', handleUserTypeChange);
            // Ejecutar al cargar la página si ya hay un valor seleccionado
            if (tipoUsuarioSelect.value) {
                handleUserTypeChange();
            }
            // También ejecutar después de un pequeño delay para asegurar que los elementos estén completamente cargados
            setTimeout(() => {
                if (tipoUsuarioSelect.value) {
                    handleUserTypeChange();
                }
            }, 100);
        }
    });    
    

</script>
@endpush
@endsection 