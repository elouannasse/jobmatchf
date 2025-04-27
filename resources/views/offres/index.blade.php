@extends('layouts.dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-offers.css') }}">

<div class="content-wrapper " >
    <div class="page-header">
        <h1 class="page-title">{{ __('Mes offres d\'emploi') }}</h1>
        <a href="{{ route('offres.create') }}" class="add-job-btn">
            <i class="fas fa-plus"></i> {{ __('Ajouter une offre') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="job-offers-container">
        <div class="offers-header">
            <h6 class="offers-title">{{ __('Liste des offres') }}</h6>
            <div class="offers-controls">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="{{ __('Rechercher...') }}">
                </div>
            </div>
        </div>

        <div class="table-container">
            @if(count($offres) > 0)
                <table class="offers-table">
                    <thead>
                        <tr>
                            <th>{{ __('TITRE') }}</th>
                            <th>{{ __('LIEU') }}</th>
                            <th>{{ __('TYPE') }}</th>
                            <th>{{ __('DATE DE PUBLICATION') }}</th>
                            <th>{{ __('STATUT') }}</th>
                            <th>{{ __('CANDIDATURES') }}</th>
                            <th>{{ __('ACTIONS') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offres as $offre)
                            <tr>
                                <td>
                                    <div class="job-title">{{ $offre->titre }}</div>
                                </td>
                                <td>
                                    <div class="job-location">
                                        <i class="fas fa-map-marker-alt"></i> {{ $offre->lieu }}
                                    </div>
                                </td>
                                <td>
                                    <div class="job-type">
                                        <i class="fas fa-briefcase"></i> {{ $offre->type_contrat }}
                                    </div>
                                </td>
                                <td>
                                    <div class="job-date">
                                        <i class="fas fa-calendar-alt"></i> {{ $offre->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('offres.toggle-status', $offre->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="badge {{ $offre->etat ? 'badge-active' : 'badge-inactive' }}" title="{{ $offre->etat ? 'Active' : 'Inactive' }}">
                                            <i class="fas {{ $offre->etat ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $offre->etat ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="job-applications">
                                        {{ $offre->candidatures->count() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('offres.show', $offre->id) }}" class="action-btn action-btn-view" title="{{ __('Voir') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('offres.edit', $offre->id) }}" class="action-btn action-btn-edit" title="{{ __('Modifier') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="action-btn action-btn-delete" data-offre-id="{{ $offre->id }}" title="{{ __('Supprimer') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $offre->id }}" action="{{ route('offres.destroy', $offre->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Empty search results message -->
                <div class="empty-search-results" style="display: none;">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4 class="empty-state-title">{{ __('Aucun résultat trouvé') }}</h4>
                        <p class="empty-state-text">{{ __('Essayez avec d\'autres termes de recherche.') }}</p>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container">
                    @if ($offres->hasPages())
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($offres->onFirstPage())
                                <li>
                                    <span class="pagination-disabled">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $offres->previousPageUrl() }}" class="pagination-link" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($offres->getUrlRange(1, $offres->lastPage()) as $page => $url)
                                @if ($page == $offres->currentPage())
                                    <li>
                                        <span class="pagination-current">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="pagination-link">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($offres->hasMorePages())
                                <li>
                                    <a href="{{ $offres->nextPageUrl() }}" class="pagination-link" rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <span class="pagination-disabled">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    @endif
                    
                    <div class="pagination-info">
                        Affichage de {{ $offres->firstItem() ?? 0 }} à {{ $offres->lastItem() ?? 0 }} sur {{ $offres->total() }} offres
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="empty-state-title">{{ __('Aucune offre trouvée') }}</h4>
                    <p class="empty-state-text">{{ __('Vous n\'avez pas encore publié d\'offres d\'emploi.') }}</p>
                    <a href="{{ route('offres.create') }}" class="add-job-btn">
                        <i class="fas fa-plus"></i> {{ __('Créer ma première offre') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Toast notification for success message -->
@if(session('success'))
<div id="successToast" class="toast toast-success">
    <div class="toast-body">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Succès!</div>
            <div class="toast-message">{{ session('success') }}</div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="{{ asset('js/modern-offers.js') }}"></script>
<script src="{{ asset('js/direct-delete.js') }}"></script>
<script src="{{ asset('js/modal-fix.js') }}"></script>
@endpush