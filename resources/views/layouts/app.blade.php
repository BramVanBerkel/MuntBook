<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GuldenBook @yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"></noscript>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <meta property="og:title" content="GuldenBook @yield('title')" />
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="GuldenBook.com"/>
    <meta property="og:description" content="GuldenBook - The Gulden blockchain explorer"/>
    <meta property="og:image" content="{{ asset('images/og_image.png') }}" />
    @production
        <script src="https://type-welcome.guldenbook.com/script.js" data-site="JBVDRBZA" defer></script>
    @endproduction
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <div class="bg-blue-500 pb-72">
        @include('layouts.navigation')
    </div>

    <main class="-mt-64 md:-mt-50">
        <header class="py-5">
            <div class="md:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex sm:items-center flex-col sm:flex-row">
                <h1 class="text-3xl font-bold text-white mr-auto mb-2 md:mb-0">
                    @yield('title')
                </h1>
                <x-search />
            </div>
        </header>
        <div class="max-w-7xl mx-auto pb-12 sm:px-4 sm:px-6 lg:px-8">
            <div class="bg-white sm:rounded-lg shadow px-5 py-6 sm:px-6">
                @yield('content')
            </div>
        </div>
    </main>
    @include('layouts.footer')
    @yield('scripts')
</div>
</body>
</html>
