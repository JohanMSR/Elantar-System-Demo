@props(['size' => ''])

<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'btn btn-danger ' . ($size ? 'btn-' . $size : '')
]) }}>
    {{ $slot }}
</button>
