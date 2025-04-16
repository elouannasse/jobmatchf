<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

{{--    <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">--}}
{{--    <meta name="author" content="pixelcave">--}}
{{--    <meta name="robots" content="index, follow">--}}

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Modules -->
    @yield('css')

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    <script src="{{ asset('js/dashmix.app.min.js') }}"></script>

</head>

<body>
<!-- Page Container -->
<div id="page-container">
    <!-- Main Container -->
    <main id="main-container">
        @yield('content')
    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
<script src="{{ asset('js/dashmix.app.min.js') }}"></script>

<!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
{{-- @vite(['resources/sass/main.scss', 'resources/sass/dashmix/themes/xwork.scss', 'resources/js/dashmix/app.js']) --}}
@yield('js')
</body>
</html>
