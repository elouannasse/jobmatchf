@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Créer une nouvelle offre') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('offres.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="titre" class="form-label">{{ __('Titre') }}</label>
                            <input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') }}" required autofocus>
                            @error('titre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lieu" class="form-label">{{ __('Lieu') }}</label>
                            <input id="lieu" type="text" class="form-control @error('lieu') is-invalid @enderror" name="lieu" value="{{ old('lieu') }}" required>
                            @error('lieu')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type_contrat" class="form-label">{{ __('Type de contrat') }}</label>
                            <select id="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror" name="type_contrat" required>
                                <option value="">{{ __('Sélectionner') }}</option>
                                <option value="CDI" {{ old('type_contrat') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option value="CDD" {{ old('type_contrat') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option value="Stage" {{ old('type_contrat') == 'Stage' ? 'selected' : '' }}>Stage</option>
                                <option value="Freelance" {{ old('type_contrat') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="Alternance" {{ old('type_contrat') == 'Alternance' ? 'selected' : '' }}>Alternance</option>
                            </select>
                            @error('type_contrat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="salaire" class="form-label">{{ __('Salaire (DH)') }}</label>
                            <input id="salaire" type="number" class="form-control @error('salaire') is-invalid @enderror" name="salaire" value="{{ old('salaire') }}">
                            @error('salaire')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_expiration" class="form-label">{{ __('Date d\'expiration') }}</label>
                            <input id="date_expiration" type="date" class="form-control @error('date_expiration') is-invalid @enderror" name="date_expiration" value="{{ old('date_expiration') }}">
                            @error('date_expiration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('offres.index') }}" class="btn btn-secondary me-md-2">{{ __('Annuler') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection