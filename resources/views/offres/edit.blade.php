@extends('layouts.dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/offres-style.css') }}">
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Modifier l\'offre') }}</h1>
        <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> {{ __('Retour') }}
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Formulaire de modification') }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('offres.update', $offre->id) }}" class="offre-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="titre" class="form-label">{{ __('Titre') }} <span class="text-danger">*</span></label>
                            <input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre', $offre->titre) }}" required autofocus>
                            @error('titre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="8" required>{{ old('description', $offre->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lieu" class="form-label">{{ __('Lieu') }} <span class="text-danger">*</span></label>
                                <input id="lieu" type="text" class="form-control @error('lieu') is-invalid @enderror" name="lieu" value="{{ old('lieu', $offre->lieu) }}" required>
                                @error('lieu')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="type_contrat" class="form-label">{{ __('Type de contrat') }} <span class="text-danger">*</span></label>
                                <select id="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror" name="type_contrat" required>
                                    <option value="">{{ __('Sélectionner') }}</option>
                                    <option value="CDI" {{ old('type_contrat', $offre->type_contrat) == 'CDI' ? 'selected' : '' }}>CDI</option>
                                    <option value="CDD" {{ old('type_contrat', $offre->type_contrat) == 'CDD' ? 'selected' : '' }}>CDD</option>
                                    <option value="Stage" {{ old('type_contrat', $offre->type_contrat) == 'Stage' ? 'selected' : '' }}>Stage</option>
                                    <option value="Freelance" {{ old('type_contrat', $offre->type_contrat) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="Alternance" {{ old('type_contrat', $offre->type_contrat) == 'Alternance' ? 'selected' : '' }}>Alternance</option>
                                    <option value="Temps partiel" {{ old('type_contrat', $offre->type_contrat) == 'Temps partiel' ? 'selected' : '' }}>Temps partiel</option>
                                </select>
                                @error('type_contrat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="salaire" class="form-label">{{ __('Salaire (DH)') }}</label>
                                <input id="salaire" type="number" class="form-control @error('salaire') is-invalid @enderror" name="salaire" value="{{ old('salaire', $offre->salaire) }}">
                                <div class="form-text">{{ __('Laissez vide si vous ne souhaitez pas préciser') }}</div>
                                @error('salaire')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_expiration" class="form-label">{{ __('Date d\'expiration') }}</label>
                                <input id="date_expiration" type="date" class="form-control @error('date_expiration') is-invalid @enderror" name="date_expiration" value="{{ old('date_expiration', $offre->date_expiration ? date('Y-m-d', strtotime($offre->date_expiration)) : '') }}">
                                <div class="form-text">{{ __('Laissez vide pour une offre sans date d\'expiration') }}</div>
                                @error('date_expiration')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('etat') is-invalid @enderror" type="checkbox" id="etat" name="etat" value="1" {{ old('etat', $offre->etat) ? 'checked' : '' }}>
                                <label class="form-check-label" for="etat">{{ __('Offre active') }}</label>
                            </div>
                            <div class="form-text">{{ __('Une offre inactive ne sera pas visible par les candidats') }}</div>
                            @error('etat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-secondary me-md-2">{{ __('Annuler') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Enregistrer les modifications') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">{{ __('Zone dangereuse') }}</h6>
                </div>
                <div class="card-body">
                    <p>{{ __('La suppression d\'une offre est définitive et entraînera la suppression de toutes les candidatures associées.') }}</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> {{ __('Supprimer cette offre') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Informations') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>{{ __('Date de création:') }}</strong></p>
                        <p>{{ $offre->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1"><strong>{{ __('Dernière modification:') }}</strong></p>
                        <p>{{ $offre->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1"><strong>{{ __('Candidatures reçues:') }}</strong></p>
                        <p><span class="badge bg-primary">{{ $offre->candidatures->count() }}</span></p>
                    </div>
                </div>
            </div>

            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Conseils') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>{{ __('Titre attractif') }}</h6>
                        <p class="small">{{ __('Utilisez un titre clair et précis qui décrit exactement le poste recherché.') }}</p>
                    </div>
                    <div class="mb-2">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>{{ __('Description détaillée') }}</h6>
                        <p class="small">{{ __('Décrivez les responsabilités, les exigences et les avantages du poste.') }}</p>
                    </div>
                    <div class="mb-2">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>{{ __('Soyez transparent') }}</h6>
                        <p class="small">{{ __('Précisez le salaire et le type de contrat pour attirer les bons candidats.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirmer la suppression') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Êtes-vous sûr de vouloir supprimer cette offre?') }}</p>
                <p class="text-danger">{{ __('Cette action est irréversible et entraînera la suppression de toutes les candidatures associées.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                <form action="{{ route('offres.destroy', $offre->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Supprimer définitivement') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection