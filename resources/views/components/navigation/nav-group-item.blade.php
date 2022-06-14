@props([
    'route',
    'showTestnet' => false,
])

@if(empty(config('gulden.testnet')) || $showTestnet)
    <a @class([
    'block',
    'px-4',
    'py-2',
    'mt-2',
    'text-sm',
    'font-semibold',
    'bg-transparent',
    'rounded-lg',
    'md:mt-0',
    'hover:text-gray-900',
    'focus:text-gray-900',
    'hover:bg-gray-200',
    'focus:bg-gray-200',
    'focus:outline-none',
    'focus:shadow-outline',
    'bg-gray-300' => request()->is($route)
])
       href="{{ route($route) }}">
        {{ $slot }}
    </a>
@endif
