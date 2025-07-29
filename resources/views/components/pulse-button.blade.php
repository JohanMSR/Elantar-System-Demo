@props([
    'type' => 'submit',
    'class' => '',
    'fullWidth' => false
])

<button 
    {{ $attributes->merge([
        'type' => $type,
        'class' => 'btn btn-primary py-2 pulse-animation ' . ($fullWidth ? 'w-100' : '') . ' ' . $class
    ]) }}>
    <span>{{ $slot }}</span>
</button>

<style>
.btn-primary {
    background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
    border: none;
    transition: all 0.3s ease;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    overflow: hidden;
    position: relative;
    z-index: 1;
    padding: 0.75rem 1.5rem;
    height: 54px;
    box-shadow: 0 5px 15px rgba(70, 135, 230, 0.3);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: all 0.5s ease;
    z-index: -1;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--color-secondary-dark), var(--color-primary-dark));
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(70, 135, 230, 0.4);
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:active {
    transform: translateY(1px);
    box-shadow: 0 3px 10px rgba(70, 135, 230, 0.3);
}

.btn-primary:focus {
    box-shadow: 0 0 0 3px rgba(70, 135, 230, 0.3);
}

.btn-primary span {
    display: inline-block;
    transition: transform 0.3s ease;
}

.btn-primary:hover span {
    transform: scale(1.05);
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(70, 135, 230, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(70, 135, 230, 0); }
    100% { box-shadow: 0 0 0 0 rgba(70, 135, 230, 0); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@media (hover: none) {
    .btn-primary:hover {
        transform: none;
        box-shadow: none;
    }
}
</style> 