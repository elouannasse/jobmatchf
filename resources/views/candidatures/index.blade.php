@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Mes candidatures') }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-dashboard shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Liste de mes candidatures') }}</h6>
            <div class="input-group w-50">
                <input type="text" id="searchInput" class="form-control" placeholder="{{ __('Rechercher...') }}">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(count($candidatures) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Offre') }}</th>
                                <th>{{ __('Entreprise') }}</th>
                                <th>{{ __('Date de candidature') }}</th>
                                <th>{{ __('Statut') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidatures as $candidature)
                                <tr>
                                    <td>{{ $candidature->offre->titre }}</td>
                                    <td>{{ $candidature->offre->user->name }}</td>
                                    <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($candidature->statut == 'en_attente')
                                            <span class="badge bg-warning">{{ __('En attente') }}</span>
                                        @elseif($candidature->statut == 'acceptee')
                                            <span class="badge bg-success">{{ __('Acceptée') }}</span>
                                        @elseif($candidature->statut == 'refusee')
                                            <span class="badge bg-danger">{{ __('Refusée') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm btn-info" title="{{ __('Voir') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $candidatures->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" alt="Aucune candidature" class="img-fluid mb-3" style="max-width: 200px; opacity: 0.5;">
                    <h4 class="text-muted">{{ __('Aucune candidature trouvée') }}</h4>
                    <p class="text-muted">{{ __('Vous n\'avez pas encore postulé à des offres d\'emploi.') }}</p>
                    <a href="{{ route('offres.disponibles') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-search me-1"></i> {{ __('Voir les offres disponibles') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script pour la recherche dans le tableau
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const table = document.querySelector('table');
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(function(row) {
                    let found = false;
                    row.querySelectorAll('td').forEach(function(cell) {
                        if (cell.textContent.toLowerCase().indexOf(searchValue) > -1) {
                            found = true;
                        }
                    });
                    
                    if (found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection