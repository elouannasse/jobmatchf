@extends('layouts.auth')

@section('js')
    <!-- jQuery (required for jQuery Validation plugin) -->
    <script src="{{asset('js/lib/jquery.min.js')}}"></script>

    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('js/pages/op_auth_reminder.min.js')}}"></script>
@endsection

@section('content')
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('{{asset('media/photos/photo16@2x.jpg')}}');">
        <div class="row g-0 justify-content-center bg-black-75">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                <!-- Reminder Block -->
                <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                    <div
                        class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx fw-bold fs-1" href="{{ route('home')}}">
                                <span class="text-dark">Job</span><span class="text-primary">Match</span>
                            </a>
                            <p class="fs-sm text-muted">
                                Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre adresse
                                électronique et nous vous enverrons un lien de réinitialisation du mot de passe qui vous
                                permettra d'en choisir un nouveau.
                            </p>
                        </div>
                        <!-- END Header -->

                        <!-- Reminder Form -->

                        <x-auth-session-status class="mb-4" :status="session('status')"/>

                        <form class="js-validation-reminder" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="email" class="form-control" value="{{ old('email') }}"
                                           name="email" placeholder="Email" required autofocus>
                                    <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                                    @error('email')
                                    <div class="text-danger mt-1 fs-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-hero btn-primary">
                                    <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Reset Link Password
                                </button>
                            </div>
                        </form>
                        <!-- END Reminder Form -->
                    </div>
                </div>
                <!-- END Reminder Block -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
