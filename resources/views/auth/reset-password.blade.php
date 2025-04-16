@extends('layouts.auth')

@section('content')
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('{{asset('media/photos/photo19@2x.jpg')}}');">
        <div class="row g-0 justify-content-center bg-primary-dark-op">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                <!-- Reset Password Block -->
                <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                    <div class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx fw-bold fs-1" href="{{ route('home')}}">
                                <span class="text-dark">Job</span><span class="text-primary">Match</span>
                            </a>
                            <p class="fs-sm text-muted">
                                Réinitialisez votre mot de passe
                            </p>
                        </div>
                        <!-- END Header -->

                        <!-- Reset Password Form -->
                        <form method="POST" action="{{ route('manual.password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="Email" value="{{ old('email', $request->email) }}" required>
                                    <span class="input-group-text"><i class="fa fa-envelope-open"></i></span>
                                </div>
                                @error('email')
                                <div class="text-danger mt-1 fs-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="password" class="form-control" id="password"
                                           name="password" placeholder="Nouveau mot de passe" required>
                                    <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1 fs-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                                    <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                                </div>
                            </div>

                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-hero btn-primary">
                                    Réinitialiser le mot de passe
                                </button>
                            </div>
                        </form>
                        <!-- END Reset Password Form -->
                    </div>
                </div>
                <!-- END Reset Password Block -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection