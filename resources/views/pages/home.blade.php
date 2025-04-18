@extends('layouts.backend')

@section('content')
    <!-- Hero Section -->
    <div class="bg-body-extra-light text-center">
        <div class="content content-boxed content-full py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-6">
                    <h1 class="h2 mb-2">
                        {{__('Find your dream job')}} <span class="text-primary">{{__('today')}}</span>.
                    </h1>
                    <p class="fs-lg fw-normal text-muted balance">
                        {{__('We offer the most complete job platform to publish your job offers and apply for your dream job.')}}
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-11 col-lg-9 col-xl-7">
                    <div class="p-2 rounded bg-body-light shadow-sm">
                        <form class="d-flex align-items-start" action="{{ route('home.search') }}" method="GET">
                            <div class="flex-grow-1">
                                <label class="visually-hidden" for="example-job-search"
                                >{{ __('Search Job by title or type or category')}}</label>
                                <input type="text" id="example-job-search" name="search-term"
                                       class="form-control form-control-lg form-control-alt
                                       @error('search-term') is-invalid @enderror"
                                       placeholder="{{ __('Search Job by title or type or category')}}.."
                                       value="{{ request('search-term') ?? '' }}">
                                @error('search-term')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex-grow-0 ms-2">
                                <button type="submit" class="btn btn-lg btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            {{--<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="px-2 px-sm-5">
                    <p class="fs-3 text-dark mb-0">6,584</p>
                    <p class="text-muted mb-0">
                        Active Jobs
                    </p>
                </div>
                <div class="px-2 px-sm-5 border-start">
                    <p class="fs-3 text-dark mb-0">2,960</p>
                    <p class="text-muted mb-0">
                        Freelancers
                    </p>
                </div>
                <div class="px-2 px-sm-5 border-start">
                    <p class="fs-3 text-dark mb-0">980</p>
                    <p class="text-muted mb-0">
                        Companies
                    </p>
                </div>
            </div>--}}
        </div>
    </div>
    <!-- END Hero Section -->

    <!-- Page Content -->
    <div class="content content-boxed content-full">
        <!-- Jobs -->

        @forelse ($offres as $offre)
            <div class="card shadow-sm rounded-2 mb-4">
                <div class="card-body px-5">
                    <div class="d-sm-flex align-items-start">
                        <div class="flex-grow-1">
                            <a class="link-fx h4 mb-1 d-inline-block text-primary"
                               href="{{ route('offre.show', $offre) }}">
                                {{ $offre->title }}
                            </a>
                            <div class="fs-sm fw-semibold text-muted mb-2">
                                {{ $offre->category->name }} - {{ $offre->created_at->diffForHumans() }}
                                <br>
                                Offre expire {{ $offre->date_fin->diffForHumans() }}
                            </div>
                            <p class="text-muted mb-2 truncated-text balance">
                                {{ $offre->description ?? 'Pas de description pour cette offre' }}
                            </p>
                            <div class="d-inline-block">
                                <span class="badge bg-secondary rounded">{{ $offre->type_offre }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="d-flex justify-content-center align-items-center fs-lg fw-semibold text-muted">
                Aucune offre disponible.
            </div>
            {{--            Aucunue offre disponible pour le moment.--}}
        @endforelse

        @if ($offres->hasPages())
            <div class="d-flex justify-content-center align-items-center mt-4 py-3 bg-body-extra-light">
                <div class="shadow-sm rounded">
                    {{ $offres->links() }}
                </div>
                <!-- END Jobs -->
            </div>
        @endif
    </div>
    <!-- END Page Content -->
@endsection
