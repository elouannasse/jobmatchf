@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Postuler à l\'offre') }}: {{ $offre->titre }}</h4>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ __('Détails de l\'offre') }}</h5>
                        <p><strong>{{ __('Entreprise') }}:</strong> {{ $offre->user->nom_entreprise ?? $offre->user->name }}</p>
                        <p><strong>{{ __('Lieu') }}:</strong> {{ $offre->lieu }}</p>
                        <p><strong>{{ __('Type de contrat') }}:</strong> {{ $offre->type_contrat }}</p>
                    </div>

                    <form action="{{ route('offres.postuler', $offre->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">{{ __('Lettre de motivation') }} <span class="text-danger">*</span></label>
                            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="6" required>{{ old('message') }}</textarea>
                            <div class="form-text">{{ __('Expliquez pourquoi vous êtes intéressé par ce poste et quelles sont vos qualifications.') }}</div>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="cv" class="form-label">{{ __('CV (PDF)') }} <span class="text-danger">*</span></label>
                            <input type="file" id="cv" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf" required>
                            <div class="form-text">{{ __('Format accepté: PDF, taille maximum: 2 Mo') }}</div>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('offres.disponibles') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> {{ __('Retour') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('Envoyer ma candidature') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection