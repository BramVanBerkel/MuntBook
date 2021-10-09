<div class="relative mt-5">
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t border-gray-300"></div>
    </div>
    @if(isset($title))
        <div class="relative flex justify-center">
                <span class="px-3 bg-white text-lg font-medium text-gray-900">
                    {{ $title }}
                </span>
        </div>
    @endif
</div>
