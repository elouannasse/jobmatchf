@extends('layouts.form')  

@section('tailwindcss-cdn')
    <script src="https://cdn.tailwindcss.com"></script>
@endsection

@section('js')
    @parent
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script>
        setTimeout(() => {
            $('.js-select2').select2({width: '100%'})
        }, 100)
    </script>
@endsection

@section('content')
    <div class="py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 col-md-8 mx-auto">

                    <div class="p-4 bg-white shadow rounded">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="col-12 col-md-8 mx-auto">
                    <div class="p-4 bg-white shadow rounded">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="col-12 col-md-8 mx-auto">
                    <div class="p-4 bg-white shadow rounded">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
