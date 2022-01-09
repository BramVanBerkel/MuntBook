@props([
    'styled' => true,
    'href' => '',
])

<a href="{{ $href }}" {{ $attributes->class(['text-blue-600 hover:text-blue-800' => $styled]) }}>
    {{ $slot }}
</a>
