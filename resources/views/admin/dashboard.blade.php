@extends('layouts.dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">Tableau de bord administrateur</h1>
        <div class="admin-actions">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="admin-dashboard-stats">
        <div class="stat-box stat-box-primary">
            <div class="stat-box-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-box-content">
                <span class="stat-box-number">{{ $stats['total_utilisateurs'] }}</span>
                <span class="stat-box-label">Utilisateurs</span>
                <div class="stat-box-action">
                    <a href="{{ route('admin.users') }}" class="stat-box-link">
                        Détails <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="stat-box stat-box-success">
            <div class="stat-box-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-box-content">
                <span class="stat-box-number">{{ $stats['total_recruteurs'] }}</span>
                <span class="stat-box-label">Recruteurs</span>
                <div class="stat-box-action">
                    <a href="{{ route('admin.recruteurs') }}" class="stat-box-link">
                        Détails <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="stat-box stat-box-info">
            <div class="stat-box-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-box-content">
                <span class="stat-box-number">{{ $stats['total_candidats'] }}</span>
                <span class="stat-box-label">Candidats</span>
                <div class="stat-box-action">
                    <a href="{{ route('admin.candidats') }}" class="stat-box-link">
                        Détails <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="stat-box stat-box-warning">
            <div class="stat-box-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-box-content">
                <span class="stat-box-number">{{ $stats['total_offres'] }}</span>
                <span class="stat-box-label">Offres d'emploi</span>
                <div class="stat-box-action">
                    <a href="{{ route('admin.offres') }}" class="stat-box-link">
                        Détails <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column (Charts & Activity) -->
        <div class="col-xl-8 col-lg-7">
            <!-- User Activity Chart -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-area me-2"></i>
                        Activité des utilisateurs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-history me-2"></i>
                        Activité récente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table table-striped table-hover">
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
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(78, 115, 223, 0.1); color: #4e73df; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <span>JD</span>
                                            </div>
                                            <div>
                                                <p style="font-weight: 600; margin-bottom: 0;">John Doe</p>
                                                <small style="color: #858796;">Recruteur</small>
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
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(54, 185, 204, 0.1); color: #36b9cc; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <span>AS</span>
                                            </div>
                                            <div>
                                                <p style="font-weight: 600; margin-bottom: 0;">Alice Smith</p>
                                                <small style="color: #858796;">Candidat</small>
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
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(246, 194, 62, 0.1); color: #f6c23e; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <span>RB</span>
                                            </div>
                                            <div>
                                                <p style="font-weight: 600; margin-bottom: 0;">Robert Brown</p>
                                                <small style="color: #858796;">Recruteur</small>
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
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(231, 74, 59, 0.1); color: #e74a3b; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <span>MJ</span>
                                            </div>
                                            <div>
                                                <p style="font-weight: 600; margin-bottom: 0;">Maria Johnson</p>
                                                <small style="color: #858796;">Candidat</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>A créé un compte</td>
                                    <td>Il y a 1 jour</td>
                                    <td><span class="badge bg-success">Nouveau</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary">
                            <i class="fas fa-users me-1"></i> Voir tous les utilisateurs
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column (Distribution & Latest Offers) -->
        <div class="col-xl-4 col-lg-5">
            <!-- User Distribution Chart -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition des utilisateurs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 260px;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <div class="d-flex justify-content-center">
                            <div class="me-3">
                                <span style="display: inline-block; width: 12px; height: 12px; margin-right: 5px; background-color: #4e73df; border-radius: 50%;"></span>
                                <span style="font-weight: 600;">Recruteurs</span>
                            </div>
                            <div class="me-3">
                                <span style="display: inline-block; width: 12px; height: 12px; margin-right: 5px; background-color: #1cc88a; border-radius: 50%;"></span>
                                <span style="font-weight: 600;">Candidats</span>
                            </div>
                            <div>
                                <span style="display: inline-block; width: 12px; height: 12px; margin-right: 5px; background-color: #36b9cc; border-radius: 50%;"></span>
                                <span style="font-weight: 600;">Admins</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </h5>
                    @if(isset($notifications) && count($notifications) > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-check-double me-1"></i> Tout marquer lu
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($notifications) && count($notifications) > 0)
                        <div style="max-height: 360px; overflow-y: auto;">
                            @foreach($notifications as $notification)
                                <div style="padding: 1rem; border-radius: 0.5rem; background-color: #f8f9fc; margin-bottom: 0.75rem; border-left: 4px solid #4e73df;">
                                    @if(isset($notification->data['type']) && $notification->data['type'] == 'pending_offer')
                                        <div class="d-flex justify-content-between">
                                            <h6 style="margin-bottom: 0.5rem; font-weight: 700; color: #4e73df;">Nouvelle offre à approuver</h6>
                                            <small style="color: #858796;">{{ \Carbon\Carbon::parse($notification->data['data']['created_at'])->diffForHumans() }}</small>
                                        </div>
                                        <p style="margin-bottom: 0.5rem;">{{ $notification->data['data']['offer_title'] }}</p>
                                        <small style="color: #858796;">Publiée par {{ $notification->data['data']['recruteur_name'] }}</small>
                                    @else
                                        <div class="d-flex justify-content-between">
                                            <h6 style="margin-bottom: 0.5rem; font-weight: 700; color: #4e73df;">{{ $notification->data['titre'] ?? 'Notification' }}</h6>
                                            <small style="color: #858796;">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p style="margin-bottom: 0.5rem;">{{ $notification->data['message'] ?? '' }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-check me-1"></i> Marquer lu
                                            </button>
                                        </form>
                                        
                                        @if(isset($notification->data['offre_id']))
                                            <a href="{{ route('offres.show', $notification->data['offre_id']) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> Voir l'offre
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($pendingOffers) && count($pendingOffers) > 0)
                        <div style="padding: 1rem; border-radius: 0.5rem; background-color: rgba(54, 185, 204, 0.1); border-left: 4px solid #36b9cc; margin-bottom: 1rem;">
                            <h6 style="margin-bottom: 0.75rem; font-weight: 700; color: #36b9cc;">
                                <i class="fas fa-info-circle me-1"></i> Offres en attente d'approbation
                            </h6>
                            <ul style="margin-bottom: 0.75rem; padding-left: 1.5rem;">
                                @foreach($pendingOffers as $offer)
                                    <li style="margin-bottom: 0.5rem;">{{ $offer->titre }} <small style="color: #858796;">({{ $offer->user->name }}) - {{ $offer->created_at->diffForHumans() }}</small></li>
                                @endforeach
                            </ul>
                            <div>
                                <a href="{{ route('admin.offres') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt me-1"></i> Voir les offres
                                </a>
                            </div>
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem 1rem;">
                            <div style="font-size: 2.5rem; color: #d1d3e2; margin-bottom: 1rem;">
                                <i class="fas fa-bell-slash"></i>
                            </div>
                            <p style="color: #858796; margin-bottom: 0;">Aucune notification non lue</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Latest Job Offers -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Dernières offres publiées
                    </h5>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div style="max-height: 400px; overflow-y: auto;">
                        @forelse($latestOffers as $offre)
                            <div style="padding: 1rem; border-bottom: 1px solid #e3e6f0;">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 style="margin-bottom: 0.25rem; font-weight: 700;">{{ $offre->titre }}</h6>
                                        <p style="margin-bottom: 0.5rem; color: #858796; font-size: 0.875rem;">
                                            <i class="fas fa-building me-1"></i> {{ $offre->user->name }} 
                                            <span class="mx-1">·</span> 
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $offre->lieu }}
                                        </p>
                                        <div>
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.25rem; background-color: {{ $offre->type_contrat == 'CDI' ? '#1cc88a' : ($offre->type_contrat == 'CDD' ? '#f6c23e' : '#e74a3b') }}; color: white; margin-right: 0.5rem;">
                                                {{ $offre->type_contrat }}
                                            </span>
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.25rem; background-color: {{ $offre->etat ? '#36b9cc' : '#858796' }}; color: white;">
                                                {{ $offre->etat ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    <small style="color: #858796; white-space: nowrap; margin-left: 1rem;">{{ $offre->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 2rem 1rem;">
                                <div style="font-size: 2.5rem; color: #d1d3e2; margin-bottom: 1rem;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <p style="color: #858796; margin-bottom: 0;">Aucune offre récente</p>
                            </div>
                        @endforelse
                    </div>
                    <div style="text-align: center; padding: 1rem; border-top: 1px solid #e3e6f0;">
                        <a href="{{ route('admin.offres') }}" class="btn btn-primary">
                            <i class="fas fa-briefcase me-1"></i> Voir toutes les offres
                        </a>
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
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#5a5c69',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyColor: '#858796',
                    bodyFont: {
                        size: 13
                    },
                    borderWidth: 1,
                    borderColor: '#e3e6f0',
                    displayColors: false,
                    caretPadding: 10,
                    cornerRadius: 4,
                    padding: 10
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#858796'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        borderDash: [2]
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#858796',
                        padding: 10
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
                hoverBackgroundColor: [
                    'rgba(78, 115, 223, 0.9)',
                    'rgba(28, 200, 138, 0.9)',
                    'rgba(54, 185, 204, 0.9)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#5a5c69',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyColor: '#858796',
                    bodyFont: {
                        size: 13
                    },
                    borderWidth: 1,
                    borderColor: '#e3e6f0',
                    displayColors: false,
                    caretPadding: 10,
                    cornerRadius: 4,
                    padding: 10
                }
            },
            cutout: '70%'
        }
    });

    // Add hover effects to stat boxes
    document.querySelectorAll('.stat-box').forEach(box => {
        box.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 0.5rem 2rem rgba(58, 59, 69, 0.2)';
            this.style.transition = 'all 0.3s ease';
        });
        
        box.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
</script>
@endpush
@endsection