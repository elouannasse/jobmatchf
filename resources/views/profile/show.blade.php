@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Mon profil') }}</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%;">{{ __('Nom') }}</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Prénom') }}</th>
                                        <td>{{ $user->prenom }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Email') }}</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    @if($user->tel)
                                    <tr>
                                        <th>{{ __('Téléphone') }}</th>
                                        <td>{{ $user->tel }}</td>
                                    </tr>
                                    @endif
                                    @if($user->adresse)
                                    <tr>
                                        <th>{{ __('Adresse') }}</th>
                                        <td>{{ $user->adresse }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            @if($user->isRecruteur())
                                <h5 class="mt-4 mb-3 border-bottom pb-2">{{ __('Informations de l\'entreprise') }}</h5>
                                
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th style="width: 40%;">{{ __('Nom de l\'entreprise') }}</th>
                                            <td>{{ $user->nom_entreprise }}</td>
                                        </tr>
                                        @if($user->description_entreprise)
                                        <tr>
                                            <th>{{ __('Description') }}</th>
                                            <td>{{ $user->description_entreprise }}</td>
                                        </tr>
                                        @endif
                                        @if($user->typeEntreprise)
                                        <tr>
                                            <th>{{ __('Type d\'entreprise') }}</th>
                                            <td>{{ $user->typeEntreprise->nom }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            @endif

                            <div class="text-center mt-4">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>{{ __('Modifier mon profil') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection