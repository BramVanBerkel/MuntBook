<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')
<body>
    @include('layouts.partials.nav')

    @if(Route::is('home'))
        @include('layouts.partials.header')
    @else
        @include('layouts.partials.header_small')
    @endif


<section class="block-explorer-features section bg-bottom">
    <div class="container">
        @yield('content')
    </div>
</section>

@include('layouts.partials.footer')

@include('scripts')

@yield('script')

</body>
</html>
