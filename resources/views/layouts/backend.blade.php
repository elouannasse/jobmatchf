<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--  <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">--}}
    {{--  <meta name="author" content="pixelcave">--}}
    {{--  <meta name="robots" content="index, follow">--}}

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Modules -->
    @yield('css-plugins')

    <!-- Stylesheets -->
    <!-- Dashmix framework -->
    <link rel="stylesheet" id="css-main" href="{{ asset('css/dashmix.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/style.css') }}">

    @yield('tailwindcss-cdn')
</head>

<body>
<!-- Page Container -->
<!--
  Available classes for #page-container:

  SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-dark'                              Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

  HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header


  FOOTER

    ''                                          Static Footer if no class is added
    'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

  HEADER STYLE

    ''                                          Classic Header style if no class is added
    'page-header-dark'                          Dark themed Header
    'page-header-glass'                         Light themed Header with transparency by default
                                                (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
    'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

  MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

  DARK MODE

    'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
-->
<div id="page-container" class="sidebar-o enable-page-overlay sidebar side-scroll page-header-dark
 page-header main-content-narrow
{{-- dark-mode sidebar-dark--}}
 ">
    <!-- Side Overlay-->
    @include('pages.overlay')
    <!-- END Side Overlay -->

    <!-- Sidebar -->
    @include('pages.sidebar')
    <!-- END Sidebar -->

    <!-- Header -->
    @include('pages.header')
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        @yield('content')
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    @include('pages.footer')
    <!-- END Footer -->
</div>
<!-- END Page Container -->
@stack('script')

<!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
<script src="{{ asset('js/dashmix.app.min.js') }}"></script>

<!-- jQuery (required for all plugin) -->
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>

<!-- Module -->
@yield('js')
</body>
</html>
