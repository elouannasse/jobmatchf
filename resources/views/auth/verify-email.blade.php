@extends('layouts.auth')

@section('content')
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('{{asset('media/photos/photo19@2x.jpg')}}');">
        <div class="row g-0 justify-content-center bg-primary-dark-op">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                <!-- Verify Email Block -->
                <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                    <div
                        class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx fw-bold fs-1" href="{{ route('home')}}">
                                <span class="text-dark">Job</span><span class="text-primary">Match</span>
                            </a>
                            <p class="fs-sm text-muted">
                                Merci pour votre inscription! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer par e-mail? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons volontiers un autre.
                            </p>
                        </div>
                        <!-- END Header -->

                        @if (session('status') == 'verification-link-sent')
                            <div class="mb-4 text-success text-center">
                                Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de l'inscription.
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <form method="POST" action="{{ route('manual.verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-hero btn-primary">
                                    Renvoyer l'e-mail de vérification
                                </button>
                            </form>
                        </div>

                        <div class="text-center">
                            <form method="POST" action="{{ route('manual.logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-alt-secondary">
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END Verify Email Block -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection