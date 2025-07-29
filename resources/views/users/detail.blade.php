@extends('layouts.master')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    Detalle de Usuario - Centro de Negocios
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

    .card-header-1 {
            background-color: var(--color-input-bg, #ffffff);
            font-weight: bold;
            width: 100%;
            border-bottom: 2px solid var(--color-border, #eaeaea);
            color: var(--color-dark, #162d92);
        }

    .card-title {
            color: var(--color-primary, #13c0e6);
            font-weight: 600;
            margin-bottom: 1.25rem;
            position: relative;
            padding: 0.5rem;
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
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!--<div class="col-lg-10">-->
        <x-page-header title="Detalle de Usuario" icon="users">
        <a href="{{ route('users.index') }}" class="btn btn-light d-flex align-items-center justify-content-center gap-1">
            <i data-feather="arrow-left" class="icon-sm"></i> Volver
        </a>
        </x-page-header>   

        <div class="row g-3">
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
                    <label for="photoPreview" class="photo-preview-container">
                        @if (isset($user->image_path) && $user->image_path))
                            <img src="{{ url('storage/'. $user->image_path) }}" alt="Preview" id="photoPreview" style="width: 200px; height: 200px;">
                        @else
                            <img src="{{ asset('img/profile/no.png') }}" alt="Preview" id="photoPreview" style="width: 150px; height: 150px;">
                        @endif
                    </label>                    
                </div>
                <div class="row g-3">
                    <div class="col-md-6 form-group">
                        <label for="tx_co_usuario" class="form-label fw-bold required-field">ID</label>
                        <input type="text" class="form-control" id="tx_co_usuario" name="tx_co_usuario" 
                               value="{{ $user->co_usuario }}" placeholder=""
                               disabled>                        
                    </div>
                <div class="row g-3">
                    <div class="col-md-6 form-group">
                        <label for="tx_primer_nombre" class="form-label fw-bold required-field">Primer Nombre</label>
                        <input type="text" class="form-control" id="tx_primer_nombre" name="tx_primer_nombre" 
                               value="{{ $user->tx_primer_nombre }}" placeholder=""
                               disabled>                        
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label for="tx_primer_apellido" class="form-label fw-bold required-field">Primer Apellido</label>
                        <input type="text" class="form-control" id="tx_primer_apellido" name="tx_primer_apellido" 
                               value="{{ $user->tx_primer_apellido }}" required placeholder=""
                               disabled>
                        <div class="invalid-feedback">Por favor ingrese el primer apellido</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_email" class="form-label fw-bold required-field">Email</label>
                        <input type="email" class="form-control" id="tx_email" name="tx_email" 
                               value="{{ $user->tx_email }}" required placeholder=""
                               disabled>
                        <div class="invalid-feedback">Por favor ingrese un email válido</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_telefono" class="form-label fw-bold required-field">Teléfono</label>
                        <input type="tel" class="form-control" id="tx_telefono" name="tx_telefono" 
                               value="{{ $user->tx_telefono }}" required placeholder=""
                               disabled>
                        <div class="invalid-feedback">Por favor ingrese un número de teléfono</div>
                    </div>
                    <div class="col-md-6 form-group">                        
                        <label for="fe_nac" class="form-label fw-bold required-field">Fecha Nacimiento</label>
                        <input type="text" 
                            class="form-control" 
                            id="fe_nac" 
                            name="fe_nac" 
                            value="{{ Carbon::parse($user->fe_nac)->format('m/d/Y') }}"
                            disabled>                        
                </div>
                <div class="col-md-6 form-group">
                        <label for="co_idioma" class="form-label fw-bold required-field">Lenguaje Principal</label>
                        <input type="text" class="form-control" id="tx_idioma" name="tx_idioma" value="{{ $user->tx_idioma ?? '' }}"
                        disabled>                                                                       
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
                               value="{{ $user->tx_direccion1 }}" required placeholder=""
                               disabled>
                        <div class="invalid-feedback">Por favor ingrese la dirección principal</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_direccion2" class="form-label fw-bold">Dirección 2</label>
                        <input type="text" class="form-control" id="tx_direccion2" name="tx_direccion2" 
                               value="{{ $user->tx_direccion2 }}" placeholder=""
                               disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="tx_zip" class="form-label fw-bold required-field">ZIP</label>
                        <input type="text" class="form-control" id="tx_zip" name="tx_zip" 
                               value="{{ $user->tx_zip }}" required placeholder=""
                               disabled>
                        <div class="invalid-feedback">Por favor ingrese el código postal</div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="office_city" class="form-label fw-bold required-field">Ciudad de Oficina</label>
                        <input type="text" class="form-control" id="office_city" name="office_city" 
                               value="{{ $user->tx_oficina ?? '' }}"
                               disabled>
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
                        <input type="text" class="form-control" id="co_rol" name="co_rol" 
                               value="{{ $user->rol_user ?? '' }}"
                               disabled>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="co_tipo_usuario" class="form-label fw-bold required-field">Tipo de Usuario</label>                        
                        <input type="text" class="form-control" id="co_tipo_usuario" name="co_tipo_usuario"
                               value="{{ $user->tipo_usuario ?? '' }}"
                               disabled>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="co_estatus_usuario" class="form-label fw-bold required-field">Estatus</label>
                        <input type="text" class="form-control" id="co_estatus_usuario" name="co_estatus_usuario" value="{{ $user->user_status ?? '' }}"
                        disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="co_usuario_padre" class="form-label fw-bold required-field">Padre</label>
                        <input type="text" class="form-control" id="co_usuario_padre" name="co_usuario_padre" value="{{ $user->father_name ?? '' }}"
                        disabled>
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label for="co_usuario_reclutador" class="form-label fw-bold required-field">Reclutador</label>
                        <input type="text" class="form-control" id="co_usuario_reclutador" name="co_usuario_reclutador" value="{{ $user->recruiter_name ?? '' }}"
                        disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="co_sponsor" class="form-label fw-bold required-field">Patrocinador</label>
                        <input type="text" class="form-control" id="co_sponsor" name="co_sponsor" value="{{ $user->sponsor_name ?? '' }}"
                        disabled>                       
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="co_office_manager" class="form-label fw-bold required-field">Gerente de Oficina</label>
                        <input type="text" class="form-control" id="co_office_manager" name="co_office_manager" value="{{ $user->office_manager_name ?? '' }}"
                        disabled>
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
                                @if($user->tx_url_drive)
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. $user->tx_url_drive) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast($user->tx_url_drive, '/') }}
                                        </a>
                                    </div>
                                @else                             
                                    <div class="mt-2">
                                        <label class="btn btn-sm btn-outline-primary disabled" style="cursor: default;">No tiene documento</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_paquete" class="form-label fw-bold">Paquete de Incorporación</label>
                                @if($user->tx_url_paquete)
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. $user->tx_url_paquete) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast($user->tx_url_paquete, '/') }}
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <label class="btn btn-sm btn-outline-primary disabled" style="cursor: default;">No tiene documento</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_formaw9" class="form-label fw-bold">Forma W9</label>                               
                                
                                @if($user->tx_url_formaw9)
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. $user->tx_url_formaw9) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast($user->tx_url_formaw9, '/') }}
                                        </a>
                                    </div>
                                @else
                                <div class="mt-2">
                                    <label class="btn btn-sm btn-outline-primary disabled" style="cursor: default;">No tiene documento</label>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="form-group">
                                <label for="tx_url_appempl" class="form-label fw-bold">Aplicación del Empleado</label>                                
                                @if($user->tx_url_appempl)
                                    <div class="mt-2">
                                        <a href="{{ url('storage/'. $user->tx_url_appempl) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i data-feather="file-text" class="me-1"></i>
                                            {{ Str::afterLast($user->tx_url_appempl, '/') }}
                                        </a>
                                    </div>
                                @else
                                <div class="mt-2">
                                    <label class="btn btn-sm btn-outline-primary disabled" style="cursor: default;">No tiene documento</label>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>

        <!-- Log Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="activity" class="me-2"></i>Logs
                </h5>
            </div>
            <div class="container mt-4 animate-fade-in">
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="card">
                            <div class="card-header-1">
                                <h5 class="card-title mb-0">Historial de Actividades</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-striped table-hover">
                                        
                                        <thead>
                                            <tr class="text-center">
                                                <th class="col-5">Información</th>
                                                <th class="col-4">Usuario</th>
                                                <th class="col-3">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($logs ?? [] as $log)
                                                <tr>
                                                    <td>{{ $log->tx_accion }}</td>
                                                    <td class="text-center">{{ $log->usuario }}</td>
                                                    <td class="text-center">{{ $log->fe_registro }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center py-4">No hay registros disponibles</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                                
            </div>
        </div>
         
       <!-- </div> parece que esta demas-->
  <!--</div>-->
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Form validation
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        // Password confirmation validation
                        const password = form.querySelector('#tx_password');
                        const passwordConfirm = form.querySelector('#password_confirmation');

                        if (password && passwordConfirm && password.value && password.value !== passwordConfirm.value) {
                            event.preventDefault();
                            event.stopPropagation();
                            passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
                        } else if (passwordConfirm) {
                            passwordConfirm.setCustomValidity('');
                        }

                        form.classList.add('was-validated');
                    });
                });
        })();
        
        // Phone number formatting
        const phoneInput = document.getElementById('tx_telefono');
        if (phoneInput) {
            phoneInput.addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });
        }
        
        // Photo preview functionality
        const photoInput = document.getElementById('photoInput');
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
                }
            });
        }
        
        // Password input validation
        const passwordInput = document.getElementById('tx_password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        
        if (passwordInput && confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
            
            passwordInput.addEventListener('input', function() {
                if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
        }
        
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
        
        // Igualar altura de botones
        setTimeout(function() {
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.querySelector('.btn-secondary');
            if (saveBtn && cancelBtn) {
                const saveHeight = saveBtn.offsetHeight;
                cancelBtn.style.height = saveHeight + 'px';
            }
        }, 100);
    });
</script>
@endpush
@endsection 