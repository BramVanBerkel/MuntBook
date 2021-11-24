@if(count($children))
    <div
        class="navigation-item cursor-pointer overflow-auto"
        aria-current="page">
        <a href="{{ $route }}"
           class="text-white rounded-md py-2 px-3 text-sm font-medium my-2 inline-block @if($isActive()) bg-black bg-opacity-10 @endif">
            {{ $slot }}
        </a>
        <div
            class="navigation-item-children absolute w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                @foreach($children as $child)
                    <x-nav-item-child
                        :route="$child->route">
                        {{ $child->label }}
                    </x-nav-item-child>
                @endforeach
            </div>
        </div>
    </div>
@else
    <a href="{{ $route }}"
       class="text-white rounded-md py-2 px-3 text-sm font-medium my-2 @if($isActive()) bg-black bg-opacity-10 @endif">
        {{ $slot }}
    </a>
@endif
