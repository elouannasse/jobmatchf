@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de bord recruteur</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>

    <!-- Stats Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-primary text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-briefcase mb-2"></i>
                    <div class="stats-number">{{ $stats['total_offres'] }}</div>
                    <div class="stats-text">Total offres</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('offres.index') }}" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-success text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-check-circle mb-2"></i>
                    <div class="stats-number">{{ $stats['offres_actives'] }}</div>
                    <div class="stats-text">Offres actives</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('offres.index') }}?etat=active" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-info text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-file-alt mb-2"></i>
                    <div class="stats-number">{{ $stats['total_candidatures'] }}</div>
                    <div class="stats-text">Candidatures</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('candidatures.recues') }}" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-warning text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-bell mb-2"></i>
                    <div class="stats-number">{{ $stats['candidatures_nouvelles'] }}</div>
                    <div class="stats-text">Nouvelles candidatures</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('candidatures.recues') }}?statut=en_attente" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Applications Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Candidatures par offre</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filtrer par:</div>
                            <a class="dropdown-item" href="#">Dernière semaine</a>
                            <a class="dropdown-item" href="#">Dernier mois</a>
                            <a class="dropdown-item" href="#">Dernière année</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Exporter</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="applicationsChart" style="width: 100%; height: 20rem;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Status -->
        <div class="col-xl-4 col-lg-5">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Statut des candidatures</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Options:</div>
                            <a class="dropdown-item" href="#">Exporter</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="applicationsStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-warning"></i> En attente
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Acceptées
                        </span>
                        <span>
                            <i class="fas fa-circle text-danger"></i> Refusées
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Offers and Applications -->
    <div class="row">
        <!-- Recent Offers -->
        <div class="col-xl-7 col-lg-6">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Mes offres récentes</h6>
                    <a href="{{ route('offres.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nouvelle offre
                    </a>
                </div>
                <div class="card-body">
                    @if($offres_recentes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date de publication</th>
                                    <th>Status</th>
                                    <th>Candidatures</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offres_recentes as $offre)
                                <tr>
                                    <td>
                                        <p class="font-weight-bold mb-0">{{ $offre->titre }}</p>
                                        <small class="text-muted">{{ Str::limit($offre->description, 50) }}</small>
                                    </td>
                                    <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($offre->etat)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $offre->candidatures->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-briefcase fa-3x text-gray-300"></i>
                        </div>
                        <h5>Vous n'avez pas encore d'offres</h5>
                        <p class="text-muted">Commencez à publier des offres pour trouver des candidats.</p>
                        <a href="{{ route('offres.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Créer ma première offre
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="col-xl-5 col-lg-6">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières candidatures</h6>
                </div>
                <div class="card-body">
                    <div class="application-list">
                        @if($offres_recentes->flatMap->candidatures->count() > 0)
                        @php
                            $latest_applications = $offres_recentes->flatMap->candidatures->sortByDesc('created_at')->take(5);
                        @endphp
                        
                        @foreach($latest_applications as $candidature)
                        <div class="application-item p-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-title rounded-circle bg-primary">
                                            {{ substr($candidature->user->prenom, 0, 1) }}{{ substr($candidature->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $candidature->user->prenom }} {{ $candidature->user->name }}</h6>
                                        <p class="text-muted small mb-1">{{ $candidature->offre->titre }}</p>
                                        <div>
                                            @if($candidature->statut == 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($candidature->statut == 'acceptee')
                                                <span class="badge bg-success">Acceptée</span>
                                            @elseif($candidature->statut == 'refusee')
                                                <span class="badge bg-danger">Refusée</span>
                                            @endif
                                            <small class="text-muted">{{ $candidature->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('candidatures.show', $candidature->id) }}">Voir détails</a></li>
                                            <li><a class="dropdown-item" href="{{ route('candidatures.accepter', $candidature->id) }}">Accepter</a></li>
                                            <li><a class="dropdown-item" href="{{ route('candidatures.refuser', $candidature->id) }}">Refuser</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('candidatures.recues') }}" class="btn btn-sm btn-primary">Voir toutes les candidatures</a>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fa-3x text-gray-300"></i>
                            </div>
                            <h5>Aucune candidature pour le moment</h5>
                            <p class="text-muted">Les candidatures apparaîtront ici dès que vous en recevrez.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('offres.create') }}" class="card card-dashboard text-center p-4 h-100">
                                <div class="mb-3">
                                    <i class="fas fa-plus-circle fa-3x text-primary"></i>
                                </div>
                                <h6>Nouvelle offre</h6>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('candidatures.recues') }}" class="card card-dashboard text-center p-4 h-100">
                                <div class="mb-3">
                                    <i class="fas fa-users fa-3x text-info"></i>
                                </div>
                                <h6>Gérer candidatures</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Applications by Job Offer Chart
    var ctx = document.getElementById('applicationsChart').getContext('2d');
    var applicationsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [@foreach($offres_recentes as $offre) '{{ Str::limit($offre->titre, 20) }}', @endforeach],
            datasets: [{
                label: 'Nombre de candidatures',
                data: [@foreach($offres_recentes as $offre) {{ $offre->candidatures->count() }}, @endforeach],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Applications Status Chart
    var statusData = {
        en_attente: {{ $offres_recentes->flatMap->candidatures->where('statut', 'en_attente')->count() }},
        acceptee: {{ $offres_recentes->flatMap->candidatures->where('statut', 'acceptee')->count() }},
        refusee: {{ $offres_recentes->flatMap->candidatures->where('statut', 'refusee')->count() }}
    };
    
    var pieCtx = document.getElementById('applicationsStatusChart').getContext('2d');
    var applicationsStatusChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Acceptées', 'Refusées'],
            datasets: [{
                data: [statusData.en_attente, statusData.acceptee, statusData.refusee],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection