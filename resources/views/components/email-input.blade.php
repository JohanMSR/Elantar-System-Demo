@props([
    'name' => 'email',
    'id' => 'email',
    'value' => '',
    'placeholder' => 'Email',
    'required' => true,
    'autocomplete' => 'email',
    'autofocus' => false,
    'class' => '',
    'label' => 'Email',
    'labelClass' => 'visually-hidden'
])

<div class="input-icon-wrapper {{ $class }}">
    <div class="input-field-container">
        <i data-feather="mail" class="input-icon" aria-hidden="true"></i>
        <label for="{{ $id }}" class="{{ $labelClass }}">{{ $label }}</label>
        <input 
            id="{{ $id }}" 
            type="email" 
            name="{{ $name }}" 
            class="form-control @error($name) is-invalid @enderror" 
            value="{{ old($name, $value) }}" 
            placeholder="{{ $placeholder }}" 
            {{ $required ? 'required' : '' }} 
            {{ $autocomplete ? 'autocomplete="'.$autocomplete.'"' : '' }} 
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $attributes->merge(['class' => '']) }}
        >
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
</style> 