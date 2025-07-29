@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'fs-6 text-danger mt-3 mb-2']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
