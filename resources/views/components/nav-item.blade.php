@props([
    'route',
])

<a @class([
        "px-4",
        "py-2",
        "mt-2",
        "text-white",
        "text-bold",
        "font-semibold",
        "rounded-lg",
        "dark-mode:bg-transparent",
        "dark-mode:hover:bg-blue-600",
        "dark-mode:focus:bg-gray-600",
        "dark-mode:text-gray-200",
        "md:mt-0",
        "md:ml-2",
        "hover:bg-blue-600",
        "focus:bg-blue-700",
        "focus:outline-none",
        "focus:shadow-outline",
        "bg-blue-600" => request()->is($route)
   ]) href="{{ route($route) }}">
    {{ $slot }}
</a>
