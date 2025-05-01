@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modifier mon profil') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nom') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="prenom" class="col-md-4 col-form-label text-md-end">{{ __('Prénom') }}</label>
                            <div class="col-md-6">
                                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom', $user->prenom) }}" required autocomplete="given-name">
                                @error('prenom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tel" class="col-md-4 col-form-label text-md-end">{{ __('Téléphone') }}</label>
                            <div class="col-md-6">
                                <input id="tel" type="text" class="form-control @error('tel') is-invalid @enderror" name="tel" value="{{ old('tel', $user->tel) }}" autocomplete="tel">
                                @error('tel')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="adresse" class="col-md-4 col-form-label text-md-end">{{ __('Adresse') }}</label>
                            <div class="col-md-6">
                                <textarea id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="adresse" autocomplete="street-address">{{ old('adresse', $user->adresse) }}</textarea>
                                @error('adresse')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($user->isRecruteur())
                            <hr>
                            <h5 class="text-center mb-3">{{ __('Informations de l\'entreprise') }}</h5>
                            
                            <div class="row mb-3">
                                <label for="nom_entreprise" class="col-md-4 col-form-label text-md-end">{{ __('Nom de l\'entreprise') }}</label>
                                <div class="col-md-6">
                                    <input id="nom_entreprise" type="text" class="form-control @error('nom_entreprise') is-invalid @enderror" name="nom_entreprise" value="{{ old('nom_entreprise', $user->nom_entreprise) }}" required>
                                    @error('nom_entreprise')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description_entreprise" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                                <div class="col-md-6">
                                    <textarea id="description_entreprise" class="form-control @error('description_entreprise') is-invalid @enderror" name="description_entreprise" rows="4">{{ old('description_entreprise', $user->description_entreprise) }}</textarea>
                                    @error('description_entreprise')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="type_entreprise_id" class="col-md-4 col-form-label text-md-end">{{ __('Type d\'entreprise') }}</label>
                                <div class="col-md-6">
                                    <select id="type_entreprise_id" class="form-select @error('type_entreprise_id') is-invalid @enderror" name="type_entreprise_id" required>
                                        <option value="">{{ __('Sélectionner un type d\'entreprise') }}</option>
                                        @foreach($typesEntreprise as $type)
                                            <option value="{{ $type->id }}" {{ (old('type_entreprise_id', $user->type_entreprise_id) == $type->id) ? 'selected' : '' }}>
                                                {{ $type->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_entreprise_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enregistrer les modifications') }}
                                </button>
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary ms-2">
                                    {{ __('Annuler') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection