@props([
    'name' => 'password',
    'id' => 'password',
    'placeholder' => 'Clave',
    'required' => true,
    'autocomplete' => 'current-password',
    'class' => '',
    'label' => 'Clave',
    'labelClass' => 'visually-hidden'
])

<div class="input-icon-wrapper password-wrapper {{ $class }}">
    <div class="input-field-container">
        <i data-feather="lock" class="input-icon" aria-hidden="true"></i>
        <label for="{{ $id }}" class="{{ $labelClass }}">{{ $label }}</label>
        <input 
            id="{{ $id }}" 
            type="password" 
            name="{{ $name }}" 
            class="form-control @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder }}" 
            {{ $required ? 'required' : '' }} 
            {{ $autocomplete ? 'autocomplete="'.$autocomplete.'"' : '' }}
            autocomplete="new-password"
            {{ $attributes->merge(['class' => '']) }}
        >
        <i data-feather="eye" class="password-toggle-icon" id="toggle{{ $id }}" aria-hidden="true" style="display: none;" tabindex="0" role="button" aria-label="Mostrar/ocultar contraseña"></i>
    </div>
    
    @error($name)
    <div class="d-block invalid-feedback" role="alert">
        <div class="d-flex flex-column">
            @foreach ((array) $errors->get($name) as $message)
            <div>{{ $message }}</div>
            @endforeach
        </div>
    </div>
    @enderror
</div>

<style>
    .input-icon-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .input-field-container {
        position: relative;
        width: 100%;
    }
    
    .input-icon-wrapper:hover .input-icon {
        color: var(--color-primary);
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-primary);
        z-index: 1;
        width: 18px;
        height: 18px;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    
    .form-control {
        border: 2px solid var(--color-border);
        transition: all 0.3s ease;
        height: 54px;
        padding: 0.75rem 0.75rem 0.75rem 3rem;
        border-radius: var(--radius-md);
        font-size: 1rem;
        letter-spacing: 0.025em;
        box-shadow: var(--shadow-input);
        background-color: var(--color-input-bg);
    }
    
    .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(19, 192, 230, 0.15);
        outline: none;
        background-color: var(--color-input-bg);
    }
    
    .form-control:hover {
        border-color: rgb(75, 193, 247);
        background-color: var(--color-input-bg-hover);
    }
    
    .form-control::placeholder {
        color: #a9b1ba;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus::placeholder {
        opacity: 0.7;
        transform: translateX(5px);
    }
    
    .form-control.is-invalid {
        background-image: none;
        border-color: #dc3545;
        padding-right: 0.75rem;
    }
    
    .password-toggle-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--color-light-text);
        transition: all 0.3s ease;
        z-index: 2;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        user-select: none;
    }
    
    .password-toggle-icon:hover {
        color: var(--color-primary);
        background-color: rgba(19, 192, 230, 0.1);
        transform: translateY(-50%) scale(1.1);
    }
    
    .password-toggle-icon:focus {
        outline: 2px solid var(--color-primary);
        outline-offset: 2px;
        color: var(--color-primary);
        background-color: rgba(19, 192, 230, 0.15);
    }
    
    .password-toggle-icon:active {
        transform: translateY(-50%) scale(0.95);
    }
    
    .password-wrapper input[type="password"],
    .password-wrapper input[type="text"] {
        padding-right: 3rem;
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-5px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    /* Desactivar los controles nativos del navegador para campos de contraseña */
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear,
    input[type="password"]::-webkit-contacts-auto-fill-button {
        display: none !important;
        visibility: hidden;
        pointer-events: none;
        position: absolute;
        right: 0;
    }
    
    /* Específicamente para Chrome */
    input[type="password"]::-webkit-credentials-auto-fill-button {
        display: none !important;
        visibility: hidden;
        pointer-events: none;
        position: absolute;
        right: 0;
    }
    
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 30px var(--color-input-bg) inset !important;
    }
</style>

<script>
// Script que se ejecuta inmediatamente para casos donde el DOM ya esté cargado
(function() {
    const togglePassword = document.querySelector('#toggle{{ $id }}');
    const password = document.querySelector('#{{ $id }}');
    
    if (togglePassword && password && document.readyState === 'complete') {
        initializePasswordToggle(togglePassword, password);
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('#toggle{{ $id }}');
    const password = document.querySelector('#{{ $id }}');
    
    if (togglePassword && password) {
        initializePasswordToggle(togglePassword, password);
    }
});

function initializePasswordToggle(togglePassword, password) {
    // Mostrar/ocultar el ícono de ojo basado en si el campo tiene valor
    password.addEventListener('input', function() {
        togglePassword.style.display = this.value.length > 0 ? 'block' : 'none';
    });
    
    // Inicializar estado - oculto por defecto a menos que ya tenga un valor
    togglePassword.style.display = password.value.length > 0 ? 'block' : 'none';
    
    // Funcionalidad del toggle
    togglePassword.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Cambiar el ícono
        if (type === 'password') {
            this.innerHTML = '<i data-feather="eye"></i>';
        } else {
            this.innerHTML = '<i data-feather="eye-off"></i>';
        }
        
        // Reinicializar Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
    
    // También manejar el evento keydown para accesibilidad
    togglePassword.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });
    
    // Asegurar que el ícono tenga el atributo tabindex para accesibilidad
    togglePassword.setAttribute('tabindex', '0');
    togglePassword.setAttribute('role', 'button');
    togglePassword.setAttribute('aria-label', 'Mostrar/ocultar contraseña');
}
</script> 