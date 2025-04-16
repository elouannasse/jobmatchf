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

    <!-- Stylesheets -->
    <!-- Dashmix framework -->
    <link rel="stylesheet" id="css-main" href="{{ asset('css/dashmix.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/style.css') }}">

    <!-- Modules -->
    @yield('css')

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

<!-- jQuery (required for jQuery Validation plugin) -->
<script src="{{asset('js/lib/jquery.min.js')}}"></script>

<!-- Page JS Plugins -->
<script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

<!-- Modules -->
@yield('js')
</body>
</html>
