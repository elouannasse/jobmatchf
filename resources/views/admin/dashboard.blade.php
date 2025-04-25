@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de bord administrateur</h1>
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
                    <i class="fas fa-users mb-2"></i>
                    <div class="stats-number">{{ $stats['total_utilisateurs'] }}</div>
                    <div class="stats-text">Utilisateurs</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.users') }}" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-success text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-user-tie mb-2"></i>
                    <div class="stats-number">{{ $stats['total_recruteurs'] }}</div>
                    <div class="stats-text">Recruteurs</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.recruteurs') }}" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-info text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-user mb-2"></i>
                    <div class="stats-number">{{ $stats['total_candidats'] }}</div>
                    <div class="stats-text">Candidats</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.candidats') }}" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard bg-warning text-white mb-4">
                <div class="card-body stats-card">
                    <i class="fas fa-briefcase mb-2"></i>
                    <div class="stats-number">{{ $stats['total_offres'] }}</div>
                    <div class="stats-text">Offres d'emploi</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.offres') }}" class="small text-white stretched-link">Détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="row">
        <div class="col-12">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Notifications</h6>
                    @if(isset($notifications) && count($notifications) > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">Marquer tout comme lu</button>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($notifications) && count($notifications) > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item">
                                    @if(isset($notification->data['type']) && $notification->data['type'] == 'pending_offer')
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Nouvelle offre à approuver</h5>
                                            <small>{{ \Carbon\Carbon::parse($notification->data['data']['created_at'])->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $notification->data['data']['offer_title'] }}</p>
                                        <small>Publiée par {{ $notification->data['data']['recruteur_name'] }}</small>
                                    @else
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $notification->data['titre'] ?? 'Notification' }}</h5>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary">Marquer comme lu</button>
                                        </form>
                                        
                                        @if(isset($notification->data['offre_id']))
                                            <a href="{{ route('offres.show', $notification->data['offre_id']) }}" class="btn btn-sm btn-primary">Voir l'offre</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($pendingOffers) && count($pendingOffers) > 0)
                        <div class="alert alert-info">
                            <h6>Offres en attente d'approbation</h6>
                            <ul class="mb-0">
                                @foreach($pendingOffers as $offer)
                                    <li>{{ $offer->titre }} ({{ $offer->user->name }}) - {{ $offer->created_at->diffForHumans() }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-2">
                                <a href="{{ route('admin.offres') }}" class="btn btn-sm btn-primary">Voir les offres</a>
                            </div>
                        </div>
                    @else
                        <p class="text-center">Aucune notification non lue</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- User Activity Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Activité des utilisateurs</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Options:</div>
                            <a class="dropdown-item" href="#">Dernière semaine</a>
                            <a class="dropdown-item" href="#">Dernier mois</a>
                            <a class="dropdown-item" href="#">Dernière année</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Exporter</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="userActivityChart" style="width: 100%; height: 20rem;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition des utilisateurs</h6>
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
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-primary"></i> Recruteurs
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Candidats
                        </span>
                        <span>
                            <i class="fas fa-circle text-info"></i> Admins
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Latest Offers -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-xl-8 col-lg-7">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activité récente</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-primary">JD</div>
                                            </div>
                                            <div>
                                                <p class="font-weight-bold mb-0">John Doe</p>
                                                <p class="text-muted small mb-0">Recruteur</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>A publié une nouvelle offre</td>
                                    <td>Il y a 2 heures</td>
                                    <td><span class="badge bg-success">Nouveau</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-info">AS</div>
                                            </div>
                                            <div>
                                                <p class="font-weight-bold mb-0">Alice Smith</p>
                                                <p class="text-muted small mb-0">Candidat</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>A postulé à une offre</td>
                                    <td>Il y a 3 heures</td>
                                    <td><span class="badge bg-info">En cours</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-warning">RB</div>
                                            </div>
                                            <div>
                                                <p class="font-weight-bold mb-0">Robert Brown</p>
                                                <p class="text-muted small mb-0">Recruteur</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>A mis à jour son profil</td>
                                    <td>Il y a 5 heures</td>
                                    <td><span class="badge bg-secondary">Terminé</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-danger">MJ</div>
                                            </div>
                                            <div>
                                                <p class="font-weight-bold mb-0">Maria Johnson</p>
                                                <p class="text-muted small mb-0">Candidat</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>A créé un compte</td>
                                    <td>Il y a 1 jour</td>
                                    <td><span class="badge bg-success">Nouveau</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-success">TP</div>
                                            </div>
                                            <div>
                                                <p class="font-weight-bold mb-0">Thomas Parker</p>
                                                <p class="text-muted small mb-0">Recruteur</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>A accepté une candidature</td>
                                    <td>Il y a 2 jours</td>
                                    <td><span class="badge bg-primary">Important</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">Voir tous les utilisateurs</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Job Offers -->
        <div class="col-xl-4 col-lg-5">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières offres publiées</h6>
                </div>
                <div class="card-body">
                    <div class="job-list">
                        @forelse($latestOffers as $offre)
                            <div class="job-item p-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $offre->titre }}</h6>
                                        <p class="text-muted small mb-1">{{ $offre->user->name }} - {{ $offre->lieu }}</p>
                                        <div>
                                            <span class="badge bg-{{ $offre->type_contrat == 'CDI' ? 'success' : ($offre->type_contrat == 'CDD' ? 'warning' : 'danger') }}">{{ $offre->type_contrat }}</span>
                                            <span class="badge bg-{{ $offre->etat ? 'info' : 'secondary' }}">{{ $offre->etat ? 'Active' : 'Inactive' }}</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $offre->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center">
                                <p class="text-muted">Aucune offre récente</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.offres') }}" class="btn btn-sm btn-primary">Voir toutes les offres</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // User Activity Chart
    var ctx = document.getElementById('userActivityChart').getContext('2d');
    var userActivityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Inscriptions',
                data: {!! json_encode($monthlyRegistrations) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    // User Distribution Chart
    var pieCtx = document.getElementById('userDistributionChart').getContext('2d');
    var userDistributionChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Recruteurs', 'Candidats', 'Admins'],
            datasets: [{
                data: [{{ $stats['total_recruteurs'] }}, {{ $stats['total_candidats'] }}, {{ $stats['total_utilisateurs'] - $stats['total_recruteurs'] - $stats['total_candidats'] }}],
                backgroundColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(54, 185, 204, 1)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection