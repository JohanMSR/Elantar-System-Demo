@props(['size' => ''])

<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'btn btn-primary ' . ($size ? 'btn-' . $size : '')
]) }}>
    {{ $slot }}
</button>
