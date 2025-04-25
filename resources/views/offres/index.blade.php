@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ __('Mes offres d\'emploi') }}</h1>
                <a href="{{ route('offres.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ __('Ajouter une offre') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Liste des offres') }}</h6>
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="{{ __('Rechercher...') }}">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 py-3">
                    @if(count($offres) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 18%">{{ __('Titre') }}</th>
                                        <th style="width: 15%">{{ __('Lieu') }}</th>
                                        <th style="width: 12%">{{ __('Type') }}</th>
                                        <th style="width: 15%">{{ __('Date de publication') }}</th>
                                        <th style="width: 13%">{{ __('Statut') }}</th>
                                        <th style="width: 10%">{{ __('Candidatures') }}</th>
                                        <th style="width: 17%">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offres as $offre)
                                        <tr>
                                            <td>{{ $offre->titre }}</td>
                                            <td>{{ $offre->lieu }}</td>
                                            <td>{{ $offre->type_contrat }}</td>
                                            <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <form action="{{ route('offres.toggle-status', $offre->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm {{ $offre->etat ? 'btn-success' : 'btn-secondary' }} mb-1" title="{{ $offre->etat ? 'Active' : 'Inactive' }}">
                                                        <i class="fas {{ $offre->etat ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                        {{ $offre->etat ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $offre->candidatures->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-info" title="{{ __('Voir') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning" title="{{ __('Modifier') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $offre->id }}" title="{{ __('Supprimer') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteModal{{ $offre->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $offre->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $offre->id }}">{{ __('Confirmer la suppression') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ __('Êtes-vous sûr de vouloir supprimer cette offre ?') }}
                                                                <p class="text-danger mb-0 mt-2">{{ __('Cette action est irréversible.') }}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                                                <form action="{{ route('offres.destroy', $offre->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">{{ __('Supprimer') }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Larger Centered Pagination -->
                        <div class="pagination-container py-4">
                            <div class="d-flex justify-content-center">
                                @if ($offres->hasPages())
                                    <ul class="pagination pagination-lg">
                                        {{-- Previous Page Link --}}
                                        @if ($offres->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $offres->previousPageUrl() }}" rel="prev">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($offres->getUrlRange(1, $offres->lastPage()) as $page => $url)
                                            @if ($page == $offres->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($offres->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $offres->nextPageUrl() }}" rel="next">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                            
                            <div class="text-muted text-center mt-3">
                                <span class="fw-bold">
                                    Affichage de {{ $offres->firstItem() ?? 0 }} à {{ $offres->lastItem() ?? 0 }} sur {{ $offres->total() }} offres
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('images/empty.svg') }}" alt="Aucune offre" class="img-fluid mb-3" style="max-width: 200px; opacity: 0.5;">
                            <h4 class="text-muted">{{ __('Aucune offre trouvée') }}</h4>
                            <p class="text-muted">{{ __('Vous n\'avez pas encore publié d\'offres d\'emploi.') }}</p>
                            <a href="{{ route('offres.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-1"></i> {{ __('Créer ma première offre') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Centering fixes */
    .container-fluid {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    
    .card {
        border: 0;
    }
    
    .card-body {
        padding-left: 1px;
        padding-right: 1px;
    }
    
    /* Enhanced table styling */
    .table {
        width: 100%;
        margin-bottom: 0;
        table-layout: fixed;
    }
    
    .table th, .table td {
        vertical-align: middle;
    }
    
    /* Enhanced pagination styling */
    .pagination-container {
        width: 100%;
        margin-top: 10px;
    }
    
    .pagination-lg .page-link {
        padding: 0.75rem 1.2rem;
        font-size: 1.1rem;
        line-height: 1.5;
    }
    
    .pagination-lg .page-item:first-child .page-link {
        border-top-left-radius: 0.3rem;
        border-bottom-left-radius: 0.3rem;
    }
    
    .pagination-lg .page-item:last-child .page-link {
        border-top-right-radius: 0.3rem;
        border-bottom-right-radius: 0.3rem;
    }
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        font-weight: bold;
    }
    
    .page-link {
        color: #0d6efd;
        background-color: #fff;
        border: 1px solid #dee2e6;
        position: relative;
        display: block;
        text-decoration: none;
    }
    
    .page-link:hover {
        color: #0a58ca;
        background-color: #e9ecef;
    }
    
    /* Fix for dashboard layout */
    #content.main-content {
        padding: 20px 0;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 991.98px) {
        .table {
            table-layout: auto;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
@endsection

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