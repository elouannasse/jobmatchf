@extends('layouts.auth')

@section('js')
    <!-- Page JS Code -->
    <script src="{{asset('js/pages/op_auth_signin.min.js')}}"></script>
@endsection

@section('content')
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('{{asset('media/photos/photo19@2x.jpg')}}');">
        <div class="row g-0 justify-content-center bg-primary-dark-op">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                <!-- Sign In Block -->
                <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                    <div
                        class="block-content block-content-full px-lg-5 px-xl-5 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx fw-bold fs-1" href="{{ route('home')}}">
                                <span class="text-dark">Job</span><span class="text-primary">Match</span>
                            </a>
                            <p class="text-uppercase fw-bold fs-sm text-muted">Connexion</p>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <x-auth-session-status class="mb-4" :status="session('status')"/>

                        <form class="js-validation-signin" method="POST" action="{{route('login')}}">
                            @csrf
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="email"
                                           class="form-control"
                                           id="email" name="email" required
                                           placeholder="Email" value="{{ old('email') }}">
                                    <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                                </div>
                                @error('email')
                                <div class="text-danger mt-1 fs-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="password" id="password"
                                           name="password" placeholder="Password"
                                           class="form-control" required
                                    >
                                    <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div
                                class="d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-start mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="login-remember-me"
                                           name="remember" @checked(old('remember'))>
                                    <label class="form-check-label" for="login-remember-me">Se souvenir de moi</label>
                                </div>
                            </div>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn w-100 btn-lg btn-hero btn-primary mb-2">
                                    <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Se connecter
                                </button>
                                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between gap-lg-2">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1"
                                           href="{{ route('password.request') }} ">
                                            <i class="fa fa-exclamation-triangle opacity-50 me-1"></i>
                                            Mot de passe oublié ?
                                        </a>
                                    @endif
                                    <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1"
                                       href="{{ route('register') }}">
                                        <i class="fa fa-plus opacity-50 me-1"></i> {{__('New Account')}}
                                    </a>
                                </p>
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
