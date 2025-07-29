@extends('layouts.master')

@section('title')
Editar Verificación - @lang('translation.business-center')
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
        --color-warning: #FFA500;
        --color-danger: #FF4D4F;
        --color-success: #52c41a;
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

    .main-content {
        background-color: #f1f8ff;
        padding: 0;
    }

    .section-title {
        font-family: "MontserratBold";
        font-size: 1.25rem;
        color: var(--color-text);
        margin-bottom: 1.5rem;
    }

    .dashboard-card {
        background-color: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        padding: 1.5rem;
        height: 100%;
        transition: var(--transition-normal);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(19, 192, 230, 0.15);
    }

    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-family: "MontserratBold";
        font-size: 1.1rem;
        color: var(--color-text);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: var(--color-primary);
    }

    .card-subtitle {
        font-family: "Montserrat";
        font-size: 0.85rem;
        color: var(--color-light-text);
    }

    .btn-primary {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        filter: brightness(1.05);
        box-shadow: var(--shadow-btn);
        text-decoration: none;
        color: white;
    }

    .btn-secondary {
        background-color: var(--color-light-text);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        text-decoration: none;
        color: white;
    }

    .form-control {
        background-color: white;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: var(--color-text);
        transition: var(--transition-fast);
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(19, 192, 230, 0.1);
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--color-text);
    }

    .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .water-type-section {
        background-color: #f8f9fa;
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-top: 1rem;
    }

    .water-type-title {
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: 1rem;
    }

    .fade-in {
        opacity: 0;
        transform: translateY(20px);
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

    /* Responsive Design Improvements */
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .btn-primary,
        .btn-secondary {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            gap: 0.3rem;
        }

        .btn-primary i,
        .btn-secondary i {
            width: 16px;
            height: 16px;
        }

        .card-title {
            font-size: 1rem;
        }

        .card-subtitle {
            font-size: 0.8rem;
        }

        .form-label {
            font-size: 0.9rem;
        }

        .form-control {
            font-size: 0.9rem;
            padding: 0.6rem 0.8rem;
        }

        .water-type-section {
            padding: 0.75rem;
        }

        .water-type-title {
            font-size: 0.95rem;
        }

        .water-type-title i {
            width: 16px;
            height: 16px;
        }

        .form-check-label {
            font-size: 0.85rem;
        }

        .form-check-label small {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .dashboard-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .col-md-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .water-type-section .row {
            margin-left: 0;
            margin-right: 0;
        }

        .water-type-section .col-md-6 {
            padding-left: 0;
            padding-right: 0;
        }

        .form-check {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .form-check-input {
            margin-left: -1.5rem;
        }

        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.75rem;
        }

        .d-flex.justify-content-end .btn-primary,
        .d-flex.justify-content-end .btn-secondary {
            margin-top: 0;
        }
    }

    /* Touch-friendly improvements */
    @media (hover: none) and (pointer: coarse) {
        .btn-primary,
        .btn-secondary {
            min-height: 44px;
        }

        .form-check-input {
            min-width: 20px;
            min-height: 20px;
        }

        .form-control {
            min-height: 44px;
        }
    }
</style>
@endpush

@section('content')
    <div class="main-content">
    @php
        $verificationsTitle = 'Editar Verificación';
    @endphp
    <x-page-header :title="$verificationsTitle" icon="edit" :has_team="false" />
    <br>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="section-title">Editar Verificación</h5>
                        <p class="text-muted">Modificar la información de la verificación</p>
                    </div>
                    <a href="{{ route('verifications.index') }}" class="btn-secondary">
                        <i data-feather="arrow-left"></i>
                        Volver a Verificaciones
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="dashboard-card fade-in">
                    <div class="card-header-custom">
                        <div>
                            <h5 class="card-title">
                                <i data-feather="edit"></i>
                                Información de la Verificación
                            </h5>
                            <p class="card-subtitle">Modifique los datos de la verificación</p>
                        </div>
                    </div>

                    <form action="{{ route('verifications.update', $verification) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre de la Verificación *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $verification->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $verification->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="water-type-section">
                            <h6 class="water-type-title">
                                <i data-feather="droplet"></i>
                                Tipos de Agua Aplicables *
                            </h6>
                            <div class="row">
                                @foreach($waterTypes as $waterType)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input @error('water_types') is-invalid @enderror" 
                                                   type="checkbox" 
                                                   name="water_types[]" 
                                                   value="{{ $waterType->co_tipo_agua }}" 
                                                   id="waterType{{ $waterType->co_tipo_agua }}"
                                                   {{ in_array($waterType->co_tipo_agua, old('water_types', $selectedWaterTypes)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="waterType{{ $waterType->co_tipo_agua }}">
                                                <strong>{{ $waterType->tx_tipo_agua }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $waterType->description }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('water_types')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('verifications.index') }}" class="btn-secondary">
                                        <i data-feather="x"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn-primary">
                                        <i data-feather="save"></i>
                                        Actualizar Verificación
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Validación del formulario
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                // Validar que al menos un tipo de agua esté seleccionado
                var waterTypeCheckboxes = form.querySelectorAll('input[name="water_types[]"]:checked');
                if (waterTypeCheckboxes.length === 0) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Mostrar error
                    var errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.textContent = 'Debe seleccionar al menos un tipo de agua.';
                    
                    var waterTypeSection = form.querySelector('.water-type-section');
                    var existingError = waterTypeSection.querySelector('.invalid-feedback.d-block');
                    if (existingError) {
                        existingError.remove();
                    }
                    waterTypeSection.appendChild(errorDiv);
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Inicializar Feather icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined' && feather.replace) {
        feather.replace();
    }
});
</script>
@endpush 