@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Détails de la candidature') }}</h1>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> {{ __('Retour') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Informations sur la candidature') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ __('Offre') }}</h5>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">{{ $candidature->offre->titre }}</h6>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-building me-2"></i>{{ $candidature->offre->user->name }}
                                </p>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $candidature->offre->lieu }}
                                </p>
                                <p class="card-text text-muted">
                                    <i class="fas fa-tag me-2"></i>{{ $candidature->offre->type_contrat }}
                                </p>
                                <a href="{{ route('offres.show', $candidature->offre->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>{{ __('Voir l\'offre complète') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>{{ __('Lettre de motivation') }}</h5>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($candidature->lettre_motivation)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>{{ __('Statut de la candidature') }}</h5>
                        <div class="mb-3">
                            @if($candidature->statut == 'en_attente')
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>{{ __('Votre candidature est en cours d\'examen.') }}
                                </div>
                            @elseif($candidature->statut == 'acceptee')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('Votre candidature a été acceptée!') }}
                                    @if($candidature->notes)
                                        <p class="mt-2 mb-0">{{ __('Message du recruteur') }}: {{ $candidature->notes }}</p>
                                    @endif
                                </div>
                            @elseif($candidature->statut == 'refusee')
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle me-2"></i>{{ __('Votre candidature n\'a pas été retenue.') }}
                                    @if($candidature->notes)
                                        <p class="mt-2 mb-0">{{ __('Motif') }}: {{ $candidature->notes }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->isRecruteur() && $candidature->offre->user_id == Auth::id() && $candidature->statut == 'en_attente')
                        <div class="mb-4">
                            <h5>{{ __('Répondre à cette candidature') }}</h5>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">
                                    <i class="fas fa-check me-1"></i> {{ __('Accepter') }}
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#refuseModal">
                                    <i class="fas fa-times me-1"></i> {{ __('Refuser') }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Candidat') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-primary text-white" style="width: 64px; height: 64px; font-size: 24px; line-height: 64px;">
                                {{ substr($candidature->user->prenom, 0, 1) }}{{ substr($candidature->user->name, 0, 1) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $candidature->user->prenom }} {{ $candidature->user->name }}</h5>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="fas fa-envelope me-2"></i>{{ $candidature->user->email }}
                        </p>
                        @if($candidature->user->telephone)
                            <p class="text-muted mb-1">
                                <i class="fas fa-phone me-2"></i>{{ $candidature->user->telephone }}
                            </p>
                        @endif
                    </div>

                    @if(Auth::user()->isRecruteur() && $candidature->offre->user_id == Auth::id())
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $candidature->user->email }}" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-1"></i> {{ __('Contacter par email') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Informations complémentaires') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <strong>{{ __('Date de candidature') }}:</strong><br>
                            {{ $candidature->created_at->format('d/m/Y à H:i') }}
                        </p>
                        @if($candidature->updated_at != $candidature->created_at)
                            <p class="text-muted mb-1">
                                <strong>{{ __('Dernière mise à jour') }}:</strong><br>
                                {{ $candidature->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        @endif
                    </div>

                    @if($candidature->cv)
                        <div class="d-grid gap-2">
                            <a href="{{ route('candidatures.telecharger-cv', $candidature->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-file-pdf me-1"></i> {{ __('Télécharger le CV') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'acceptation -->
@if(Auth::user()->isRecruteur() && $candidature->offre->user_id == Auth::id())
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acceptModalLabel">{{ __('Accepter la candidature') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('candidatures.updateStatus', $candidature->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>{{ __('Vous êtes sur le point d\'accepter la candidature de') }} {{ $candidature->user->prenom }} {{ $candidature->user->name }}.</p>
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('Message au candidat (optionnel)') }}</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="statut" value="acceptee">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Accepter') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de refus -->
    <div class="modal fade" id="refuseModal" tabindex="-1" aria-labelledby="refuseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refuseModalLabel">{{ __('Refuser la candidature') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('candidatures.updateStatus', $candidature->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>{{ __('Vous êtes sur le point de refuser la candidature de') }} {{ $candidature->user->prenom }} {{ $candidature->user->name }}.</p>
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('Motif du refus (optionnel)') }}</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="statut" value="refusee">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Refuser') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection