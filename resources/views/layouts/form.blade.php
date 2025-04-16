@extends('layouts.backend')

@section('css-plugins')
    <!-- Stylesheets -->
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css')}} ">
@endsection

@section('js')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page JS Helpers (Select2 plugin) -->
    <script>Dashmix.helpersOnLoad(['jq-select2']);</script>
@endsection
