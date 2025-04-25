@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Inscription') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('Nom') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="prenom" class="form-label">{{ __('Prénom') }}</label>
                                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom') }}" required autocomplete="given-name">
                                @error('prenom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">{{ __('Type d\'utilisateur') }}</label>
                            <select id="role_id" class="form-select @error('role_id') is-invalid @enderror" name="role_id" required>
                                <option value="">{{ __('Sélectionnez votre profil') }}</option>
                                @foreach($roles as $role)
                                    @if($role->name != 'Administrateur')
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="recruteur_fields" class="d-none">
                            <div class="mb-3">
                                <label for="nom_entreprise" class="form-label">{{ __('Nom de l\'entreprise') }}</label>
                                <input id="nom_entreprise" type="text" class="form-control @error('nom_entreprise') is-invalid @enderror" name="nom_entreprise" value="{{ old('nom_entreprise') }}">
                                @error('nom_entreprise')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type_entreprise_id" class="form-label">{{ __('Type d\'entreprise') }}</label>
                                <select id="type_entreprise_id" class="form-select @error('type_entreprise_id') is-invalid @enderror" name="type_entreprise_id">
                                    <option value="">{{ __('Sélectionnez le type d\'entreprise') }}</option>
                                    @foreach($typesEntreprise as $type)
                                        <option value="{{ $type->id }}" {{ old('type_entreprise_id') == $type->id ? 'selected' : '' }}>
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('S\'inscrire') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p>{{ __('Vous avez déjà un compte?') }} <a href="{{ route('login') }}">{{ __('Connectez-vous') }}</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const recruteurFields = document.getElementById('recruteur_fields');
        
        // Vérifier l'état initial
        toggleRecruteurFields();
        
        // Ajouter l'écouteur d'événements
        roleSelect.addEventListener('change', toggleRecruteurFields);
        
        function toggleRecruteurFields() {
            // Obtenir la valeur du texte de l'option sélectionnée
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const isRecruteur = selectedOption.text === 'Recruteur';
            
            if (isRecruteur) {
                recruteurFields.classList.remove('d-none');
            } else {
                recruteurFields.classList.add('d-none');
            }
        }
    });
</script>
@endpush
@endsection