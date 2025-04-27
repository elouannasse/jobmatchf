@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Tableau de bord recruteur</h1>
        <a href="{{ route('offres.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Ajouter une offre
        </a>
    </div>

    <div class="dashboard-stats">
        <div class="dashboard-stat-card primary">
            <div class="dashboard-stat-number">{{ $totalOffres ?? 0 }}</div>
            <div class="dashboard-stat-label">Total offres</div>
            <div class="dashboard-stat-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('offres.index') }}" class="btn btn-primary mt-3">Détails</a>
        </div>

        <div class="dashboard-stat-card success">
            <div class="dashboard-stat-number">{{ $offresActives ?? 0 }}</div>
            <div class="dashboard-stat-label">Offres actives</div>
            <div class="dashboard-stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('offres.index') }}" class="btn btn-success mt-3">Détails</a>
        </div>

        <div class="dashboard-stat-card info">
            <div class="dashboard-stat-number">{{ $totalCandidatures ?? 0 }}</div>
            <div class="dashboard-stat-label">Candidatures</div>
            <div class="dashboard-stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('candidatures.recues') }}" class="btn btn-info mt-3">Détails</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">Navigation rapide</h5>
                </div>
                <div class="content-card-body">
                    <div class="home-list-section">
                        <h6 class="home-list-heading">RECRUTEUR</h6>
                        <ul class="home-list">
                            <li class="home-list-item">
                                <a href="{{ route('offres.index') }}" class="home-list-link">
                                    <i class="fas fa-briefcase"></i> Mes offres
                                </a>
                            </li>
                            <li class="home-list-item">
                                <a href="{{ route('candidatures.recues') }}" class="home-list-link">
                                    <i class="fas fa-file-alt"></i> Candidatures reçues
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="home-list-section">
                        <h6 class="home-list-heading">COMPTE</h6>
                        <ul class="home-list">
                            <li class="home-list-item">
                                <a href="{{ route('profile.show') }}" class="home-list-link">
                                    <i class="fas fa-user"></i> Mon profil
                                </a>
                            </li>
                            <li class="home-list-item">
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="home-list-link">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">Dernières offres</h5>
                    <a href="{{ route('offres.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                </div>
                <div class="content-card-body">
                    @if(isset($recentOffres) && count($recentOffres) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOffres as $offre)
                                        <tr>
                                            <td>{{ $offre->titre }}</td>
                                            <td>{{ $offre->type_contrat }}</td>
                                            <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($offre->etat)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune offre trouvée</p>
                            <a href="{{ route('offres.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Créer votre première offre
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">Candidatures récentes</h5>
                    <a href="{{ route('candidatures.recues') }}" class="btn btn-primary btn-sm">Voir tout</a>
                </div>
                <div class="content-card-body">
                    @if(isset($recentCandidatures) && count($recentCandidatures) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Candidat</th>
                                        <th>Offre</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCandidatures as $candidature)
                                        <tr>
                                            <td>{{ $candidature->user->name }}</td>
                                            <td>{{ $candidature->offre->titre }}</td>
                                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($candidature->statut == 'En attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($candidature->statut == 'Acceptée')
                                                    <span class="badge bg-success">Acceptée</span>
                                                @elseif($candidature->statut == 'Refusée')
                                                    <span class="badge bg-danger">Refusée</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune candidature reçue</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
