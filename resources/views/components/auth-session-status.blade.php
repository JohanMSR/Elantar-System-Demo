@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'fs-6 text-danger']) }}>
        {{ $status }}
    </div>
@endif
