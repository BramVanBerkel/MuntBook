<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    @if(isset($title) || isset($description))
        <div class="border-b px-4 py-5 sm:px-6">
            @if(isset($title))
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $title }}
                </h3>
            @endif

            @if(isset($description))
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {!! $description !!}
                </p>
            @endif
        </div>
    @endif
    <div class="border-gray-200 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200">
            {{ $slot }}
        </dl>
    </div>
</div>
