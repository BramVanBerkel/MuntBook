@props([
    'first' => false,
    'last' => false,
])

<button {{ $attributes->class([
    '-ml-px',
    'relative',
    'inline-flex',
    'items-center',
    'px-4',
    'py-2',
    'border',
    'border-gray-300',
    'bg-white',
    'text-sm',
    'font-medium',
    'text-gray-700',
    'focus:z-10',
    'focus:outline-none',
    'focus:ring-1',
    'focus:ring-blue-500',
    'focus:border-blue-500',
]) }} type="button">
    {{ $slot }}
</button>
