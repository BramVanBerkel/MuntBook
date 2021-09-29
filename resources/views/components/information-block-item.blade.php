@props([
    'copyable' => null,
    'name' => '',
])

<div {{ $attributes->merge(['class' => 'py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6']) }}>
    <dt class="text-sm font-medium text-gray-500">
        {{ $name }}
    </dt>

    <dd x-data='{ text: @json($copyable), showSuccessText: false, showErrorText: false }' class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 flex">
        <span @click="
                if(!text) return
                navigator.clipboard.writeText(text).then(function(){
                    showSuccessText = true;
                    setTimeout(() => showSuccessText = false, 2000)
                }, function(error) {
                    console.log(error);
                    showErrorText = true;
                    setTimeout(() => showErrorText = false, 2000)
                })"
            @class(["border-b border-dashed border-gray-400 cursor-pointer flex max-w-max items-center" => $copyable !== null])>
            {{ $slot }}
            @if($copyable !== null)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
            @endif
        </span>
        <span x-show="showSuccessText"
              x-cloak
              x-transition:enter="transition ease-out duration-100"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-100"
              x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
              class="ml-2 flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            copied to clipboard
        </span>

        <span x-show="showErrorText"
              x-cloak
              x-transition:enter="transition ease-out duration-100"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-100"
              x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
              class="ml-2 flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            error copying to clipboard
        </span>
    </dd>
</div>
