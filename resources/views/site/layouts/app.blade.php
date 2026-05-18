<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? $siteMetaDescription ?? 'Maritime logistics and port solutions.' }}">
    @include('partials.site-head-meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.site-theme-css')
    @stack('styles')
</head>

<body class="min-h-screen overflow-x-clip bg-background font-sans text-foreground antialiased">
    @include('site.partials.header')

    <main>
        @yield('content')
    </main>

    @include('site.partials.footer')
    @stack('scripts')
</body>

</html>
