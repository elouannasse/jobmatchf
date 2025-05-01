@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ __('Tableau de bord') }}</h1>
    <p class="lead">{{ __('Bienvenue') }}, {{ Auth::user()->prenom }} {{ Auth::user()->name }}</p>

    <!-- Cartes de statistiques -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100 shadow">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ __('Candidatures en total') }}</h5>
                    <p class="card-text display-4">{{ $stats['total_candidatures'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100 shadow">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ __('Candidatures en cours') }}</h5>
                    <p class="card-text display-4">{{ $stats['candidatures_en_cours'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100 shadow">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ __('Candidatures acceptées') }}</h5>
                    <p class="card-text display-4">{{ $stats['candidatures_acceptees'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Deux sections en colonnes -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Nouvelles offres disponibles') }}</h5>
                    <a href="{{ route('offres.disponibles') }}" class="btn btn-sm btn-primary">
                        {{ __('Voir toutes les offres') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($offres_recentes) && count($offres_recentes) > 0)
                        <div class="list-group">
                            @foreach($offres_recentes as $offre)
                                <a href="{{ route('offres.show', $offre) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $offre->titre }}</h5>
                                        <small>{{ $offre->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($offre->description, 100) }}</p>
                                    <small>{{ $offre->lieu }} - {{ $offre->type_contrat }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <p class="mb-0">{{ __('Aucune offre d\'emploi disponible pour le moment.') }}</p>
                            <p class="text-muted">{{ __('Revenez plus tard pour voir les nouvelles opportunités.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Mes candidatures récentes') }}</h5>
                    <a href="{{ route('candidatures.mes-candidatures') }}" class="btn btn-sm btn-primary">
                        {{ __('Voir toutes mes candidatures') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($candidatures_recentes) && count($candidatures_recentes) > 0)
                        <div class="list-group">
                            @foreach($candidatures_recentes as $candidature)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $candidature->offre->titre }}</h5>
                                        <small>{{ $candidature->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $candidature->offre->lieu }} - {{ $candidature->offre->type_contrat }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small>{{ $candidature->offre->user->name }}</small>
                                        @if($candidature->statut == 'en_attente')
                                            <span class="badge bg-warning">{{ __('En attente') }}</span>
                                        @elseif($candidature->statut == 'acceptee')
                                            <span class="badge bg-success">{{ __('Acceptée') }}</span>
                                        @elseif($candidature->statut == 'refusee')
                                            <span class="badge bg-danger">{{ __('Refusée') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="mb-0">{{ __('Vous n\'avez pas encore postulé à une offre.') }}</p>
                            <p class="text-muted">{{ __('Consultez les offres disponibles pour commencer.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table des dernières offres -->
    <div class="row">
        <div class="col-12">
            <div class="card card-dashboard shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Dernières offres d\'emploi') }}</h5>
                </div>
                <div class="card-body">
                    @if(isset($offres_recentes) && count($offres_recentes) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Titre') }}</th>
                                        <th>{{ __('Entreprise') }}</th>
                                        <th>{{ __('Lieu') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Publié le') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offres_recentes as $offre)
                                    <tr>
                                        <td>{{ $offre->titre }}</td>
                                        <td>{{ $offre->user->nom_entreprise ?? $offre->user->name }}</td>
                                        <td>{{ $offre->lieu }}</td>
                                        <td>{{ $offre->type_contrat }}</td>
                                        <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> {{ __('Voir') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <p class="mb-0">{{ __('Aucune offre d\'emploi disponible pour le moment.') }}</p>
                            <p class="text-muted">{{ __('Revenez plus tard pour voir les nouvelles opportunités.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection