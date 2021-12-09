@props([
    'type' => 'primary'
])

<span {{ $attributes->class([
    'inline-flex',
    'items-center',
    'px-2',
    'rounded-full',
    'text-xs',
    'font-medium',
    'bg-blue-100 text-blue-800' => $type === 'primary',
    'bg-red-100 text-red-800' => $type === 'danger',
    'bg-green-100 text-green-800' => $type === 'success',
    'bg-yellow-100 text-yellow-800' => $type === 'warning',
    'bg-gray-100 text-gray-800' => $type === 'secondary',
]) }} >
    @switch($type)
        @case('danger')
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            @break
        @case('success')
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            @break
        @case('warning')
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @break
        @default
            <svg class="-ml-0.5 mr-1.5 h-2" fill="currentColor" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3" />
            </svg>
            @break
    @endswitch
  {{ $slot }}
</span>
