@extends('layouts.auth')

@section('content')
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('{{asset('media/photos/photo19@2x.jpg')}}');">
        <div class="row g-0 justify-content-center bg-primary-dark-op">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                <!-- Sign In Block -->
                <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                    <div
                        class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx fw-bold fs-1" href="{{ route('home')}}">
                                <span class="text-dark">Job</span><span class="text-primary">Match</span>
                            </a>
                            <p class="fs-sm text-muted">
                                Il s'agit d'une zone sécurisée de l'application. Veuillez confirmer votre mot de passe
                                avant de continuer.
                            </p>
                        </div>
                        <!-- END Header -->

                        <!-- Confirm password Form -->
                        <x-auth-session-status class="mb-4" :status="session('status')"/>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="password" id="password"
                                           name="password" placeholder="Password"
                                           class="form-control" required autofocus
                                    >
                                    <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1 fs-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-hero btn-primary">
                                    Confirm
                                </button>
                            </div>
                        </form>
                        <!-- END Sign In Form -->
                    </div>
                </div>
                <!-- END Sign In Block -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->

@endsection
