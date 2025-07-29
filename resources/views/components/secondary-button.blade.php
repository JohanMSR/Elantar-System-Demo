@props(['size' => ''])

<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'btn btn-secondary ' . ($size ? 'btn-' . $size : '')
]) }}>
    {{ $slot }}
</button>
