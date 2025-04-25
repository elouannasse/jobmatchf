@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Détails de l\'offre') }}</h1>
        <div>
            <a href="{{ route('offres.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> {{ __('Retour') }}
            </a>
            <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> {{ __('Modifier') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $offre->titre }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text-dark">{{ __('Description') }}</h5>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($offre->description)) !!}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-dark">{{ __('Informations générales') }}</h5>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th width="40%">{{ __('Type de contrat') }}</th>
                                        <td>{{ $offre->type_contrat }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Lieu') }}</th>
                                        <td>{{ $offre->lieu }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Salaire') }}</th>
                                        <td>{{ $offre->salaire ? number_format($offre->salaire, 0, ',', ' ') . ' DH' : 'Non précisé' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Date d\'expiration') }}</th>
                                        <td>{{ $offre->date_expiration ? date('d/m/Y', strtotime($offre->date_expiration)) : 'Non précisée' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-dark">{{ __('Informations de publication') }}</h5>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th width="40%">{{ __('Publiée le') }}</th>
                                        <td>{{ $offre->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Dernière modification') }}</th>
                                        <td>{{ $offre->updated_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Statut') }}</th>
                                        <td>
                                            <span class="badge {{ $offre->etat ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $offre->etat ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Candidatures') }}</th>
                                        <td>
                                            <span class="badge bg-primary">{{ $offre->candidatures->count() }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <form action="{{ route('offres.toggle-status', $offre->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn {{ $offre->etat ? 'btn-secondary' : 'btn-success' }}">
                                <i class="fas {{ $offre->etat ? 'fa-times-circle' : 'fa-check-circle' }} me-1"></i>
                                {{ $offre->etat ? 'Désactiver l\'offre' : 'Activer l\'offre' }}
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> {{ __('Supprimer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Candidatures reçues') }}</h6>
                    <span class="badge bg-primary">{{ $offre->candidatures->count() }}</span>
                </div>
                <div class="card-body">
                    @if($offre->candidatures->count() > 0)
                        <div class="list-group candidatures-list">
                            @foreach($offre->candidatures as $candidature)
                                <a href="{{ route('candidatures.show', $candidature->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $candidature->user->prenom }} {{ $candidature->user->name }}</h6>
                                        <small>{{ $candidature->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-muted small">{{ Str::limit($candidature->message, 50) }}</p>
                                    <div>
                                        @if($candidature->statut == 'en_attente')
                                            <span class="badge bg-warning">{{ __('En attente') }}</span>
                                        @elseif($candidature->statut == 'acceptee')
                                            <span class="badge bg-success">{{ __('Acceptée') }}</span>
                                        @elseif($candidature->statut == 'refusee')
                                            <span class="badge bg-danger">{{ __('Refusée') }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @if($offre->candidatures->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('candidatures.recues', ['offre_id' => $offre->id]) }}" class="btn btn-sm btn-primary">{{ __('Voir toutes les candidatures') }}</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fa-3x text-gray-300"></i>
                            </div>
                            <p class="text-muted">{{ __('Aucune candidature reçue pour le moment.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Partager cette offre') }}</h6>
                </div>
                <div class="card-body">
                    <p>{{ __('Partagez cette offre d\'emploi pour attirer plus de candidats:') }}</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="offreUrl" value="{{ route('offres.show', $offre->id) }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyOffreUrl()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-around">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('offres.show', $offre->id)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('offres.show', $offre->id)) }}&text={{ urlencode($offre->titre) }}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('offres.show', $offre->id)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="mailto:?subject={{ urlencode('Offre d\'emploi: '.$offre->titre) }}&body={{ urlencode('Découvrez cette offre d\'emploi: '.$offre->titre."\n".route('offres.show', $offre->id)) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-envelope"></i>
                        </a>
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
                {{ __('Êtes-vous sûr de vouloir supprimer cette offre ?') }}
                <p class="text-danger mb-0 mt-2">{{ __('Cette action est irréversible.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                <form action="{{ route('offres.destroy', $offre->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Supprimer') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyOffreUrl() {
        var copyText = document.getElementById("offreUrl");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        
        // Notification de copie
        alert("URL copiée dans le presse-papiers!");
    }
</script>
@endpush
@endsection