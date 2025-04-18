@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Utilisateur</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Utilisateur</li>
                        <li class="breadcrumb-item active" aria-current="page">show</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content content-boxed">
        <div class="row">
            <div class="col-md-12">
                <!-- Job Description -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Utilisateur détail</h3>
                        <div>
                            <a href="{{ route('user.edit', $user) }}"
                               class="btn btn-md btn-alt-primary">Modifier cette utilisateur</a>
                        </div>
                    </div>
                    <div class="block-content py-3 space-y-2 fs-5">
                        <div><span class="fw-bold"> Id: </span> N°{{ $user->id }}</div>
                        <div><span class="fw-bold"> Nom: </span> {{ $user->name . ' ' . ($user->prenom) ?? '' }}</div>
                        <div><span class="fw-bold"> Email: </span> {{ $user->email }}</div>
                        <div><span class="fw-bold"> Téléphone: </span> {{ $user->tel }}</div>
                        <div><span class="fw-bold"> Adresse: </span> {{ $user->adresse }}</div>

                        @if($user->role->name === 'Recruteur')
                            <div><span class="fw-bold"> Nom Entreprise: </span> {{ $user->nom_entreprise }}</div>
                            <div><span
                                    class="fw-bold"> Description Entreprise: </span> {{ $user->description_entreprise }}
                            </div>
                        @endif

                        <div><span class="fw-bold"> Statut: </span>
                            <span class="badge fs-6 mb-2 {{ $user->etat ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->etat ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        <div class="fw-bold flex-shrink-0"> Rôle assigné:
                            <span>
                            <a class="badge bg-primary p-2 fs-6 mb-2">
                                {{ (Str::ucfirst($user->role->name)) }}
                            </a>
                        </span>
                        </div>
                    </div>
                    <!-- END Job Description -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->
@endsection
