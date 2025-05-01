@extends('layouts.dashboard')

@section('content')
<div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Statistiques et rapports</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Statistiques</li>
        </ol>
    </div>

    <!-- Contenu de statistiques -->
    <div class="row justify-content-center">
        <!-- Graphique d'inscriptions par mois -->
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Inscriptions d'utilisateurs par mois</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="userRegistrationsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique d'offres par mois -->
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Offres publiées par mois</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="offerStatisticsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Graphique d'applications par mois -->
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Candidatures par mois</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="applicationStatisticsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Types de contrats populaires -->
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Types de contrats populaires</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="popularJobTypesChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lieux populaires -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lieux populaires</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lieu</th>
                                    <th>Nombre d'offres</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularLocations as $location)
                                    <tr>
                                        <td>{{ $location->lieu }}</td>
                                        <td>{{ $location->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add custom CSS to override dashboard layout -->
<style>
@media (min-width: 992px) {
    #content.main-content {
        padding-left: 30px;
        padding-right: 30px;
    }
    
    .card {
        margin-bottom: 30px;
    }
    
    .chart-area, .chart-pie {
        height: 100%;
        min-height: 350px;
    }
    
    .card-body {
        padding: 1.5rem;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les inscriptions d'utilisateurs
    const userRegistrationsData = @json($userRegistrations);
    const userLabels = userRegistrationsData.map(item => `${item.year}-${item.month}`);
    const userData = userRegistrationsData.map(item => item.total);
    
    // Données pour les offres
    const offerStatisticsData = @json($offerStatistics);
    const offerLabels = offerStatisticsData.map(item => `${item.year}-${item.month}`);
    const offerData = offerStatisticsData.map(item => item.total);
    
    // Données pour les candidatures
    const applicationStatisticsData = @json($applicationStatistics);
    const applicationLabels = applicationStatisticsData.map(item => `${item.year}-${item.month}`);
    const applicationData = applicationStatisticsData.map(item => item.total);
    
    // Données pour types de contrats
    const popularJobTypesData = @json($popularJobTypes);
    const jobTypeLabels = popularJobTypesData.map(item => item.type_contrat);
    const jobTypeData = popularJobTypesData.map(item => item.total);
    
    // Créer graphique d'inscriptions
    new Chart(document.getElementById('userRegistrationsChart'), {
        type: 'line',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'Inscriptions',
                data: userData,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
    
    // Créer graphique d'offres
    new Chart(document.getElementById('offerStatisticsChart'), {
        type: 'line',
        data: {
            labels: offerLabels,
            datasets: [{
                label: 'Offres publiées',
                data: offerData,
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderColor: 'rgba(28, 200, 138, 1)',
                pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                pointBorderColor: '#fff',
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
    
    // Créer graphique de candidatures
    new Chart(document.getElementById('applicationStatisticsChart'), {
        type: 'line',
        data: {
            labels: applicationLabels,
            datasets: [{
                label: 'Candidatures',
                data: applicationData,
                backgroundColor: 'rgba(54, 185, 204, 0.05)',
                borderColor: 'rgba(54, 185, 204, 1)',
                pointBackgroundColor: 'rgba(54, 185, 204, 1)',
                pointBorderColor: '#fff',
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
    
    // Créer graphique de types de contrats
    new Chart(document.getElementById('popularJobTypesChart'), {
        type: 'pie',
        data: {
            labels: jobTypeLabels,
            datasets: [{
                data: jobTypeData,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection