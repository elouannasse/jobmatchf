@extends('layouts.auth')

@section('content')
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('{{asset('media/photos/photo21@2x.jpg')}}');">
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
                                Merci de votre inscription ! Veuillez vérifier votre adresse e-mail en cliquant sur le
                                lien envoyé. Si vous n'avez pas reçu l'e-mail, nous vous enverrons un autre avec
                                plaisir.
                            </p>
                        </div>
                        <!-- END Header -->


                        @if (session('status') == 'verification-link-sent')
                            <div class="mb-4 font-weight-bold small text-success">
                                Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie
                                lors de votre inscription.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-hero btn-primary">
                                    <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Resend Verification Email
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-hero btn-light">
                                    <i class="fa fa-fw fa-arrow-right-from-bracket opacity-50 me-1"></i> Log Out
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- END Sign In Block -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->

@endsection
