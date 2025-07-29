@props([
    'name' => 'remember',
    'id' => 'remember',
    'label' => 'translation.remember_me',
    'checked' => false,
    'class' => '',
    'labelClass' => 'form-check-label'
])

<div class="form-check text-start my-3 {{ $class }}">
    <input 
        class="form-check-input" 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => '']) }}
    >
    <label class="{{ $labelClass }}" for="{{ $id }}">
        @lang($label)
    </label>
</div>

<style>
    /* Estilos específicos para el checkbox */
    .form-check-input {
        width: 18px !important;
        height: 18px !important;
        margin-top: 0 !important;
        margin-right: 0 !important;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition-normal);
        vertical-align: middle;
        border: 2px solid #d1d9e6;
    }

    .form-check {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: var(--color-light-text);
        margin-left: 0;
        padding-top: 1px;
        cursor: pointer;
        transition: var(--transition-normal);
        line-height: 1.2;
        user-select: none;
    }
    
    .form-check-label:hover {
        color: var(--color-dark);
    }
    
    .form-check-input:checked {
        background-color: var(--color-accent);
        border-color: var(--color-accent);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
    }

    .form-check-input:not(:checked):hover {
        border-color: var(--color-accent-dark);
    }
    
    /* Estilos mejorados para el checkbox cuando los datos son incorrectos/correctos */
    .was-validated .form-check-input:valid:checked {
        background-color: #198754;
        border-color: #198754;
    }
    
    .was-validated .form-check-input:valid:not(:checked) {
        border-color: #d1d9e6;
        background-color: transparent;
    }
    
    .was-validated .form-check-input:invalid {
        border-color: #dc3545;
    }
    
    .was-validated .form-check-input:invalid:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .was-validated .form-check-input:valid:checked ~ .form-check-label {
        color: #198754;
    }
    
    .was-validated .form-check-input:invalid ~ .form-check-label {
        color: #dc3545;
    }
</style> 