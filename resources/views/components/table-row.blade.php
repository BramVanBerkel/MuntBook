@props([
    'color' => ''
])

<tr {{ $attributes->merge(['class' => $color]) }}>
    {{ $slot }}
</tr>
