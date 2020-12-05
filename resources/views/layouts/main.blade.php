<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')
<body>
    @include('layouts.partials.nav')

    @include('layouts.partials.search')

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
