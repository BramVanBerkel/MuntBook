@props([
    'route',
])

<a @class([
        'px-4',
        'py-2',
        'mt-2',
        'text-sm',
        'font-semibold',
        'text-gray-100',
        'rounded-lg',
        'md:mt-0',
        'hover:bg-blue-600',
        'focus:bg-blue-700',
        'focus:outline-none',
        'focus:shadow-outline',
        'bg-blue-600' => request()->is($route),
]) href="{{ route($route) }}">
    {{ $slot }}
</a>
