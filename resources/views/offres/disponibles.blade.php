@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Offres disponibles') }}</h1>
        <div class="input-group w-50">
            <input type="text" id="searchInput" class="form-control" placeholder="{{ __('Rechercher...') }}">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <div class="row">
        @if(count($offres) > 0)
            @foreach($offres as $offre)
                <div class="col-md-4 mb-4">
                    <div class="card card-dashboard h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-primary">{{ $offre->titre }}</h5>
                            <span class="badge bg-{{ $offre->type_contrat == 'CDI' ? 'success' : ($offre->type_contrat == 'CDD' ? 'warning' : 'info') }}">
                                {{ $offre->type_contrat }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $offre->lieu }}</p>
                                <p class="text-muted"><i class="fas fa-building me-2"></i>{{ $offre->user->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="card-text">{{ Str::limit($offre->description, 150) }}</p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($offre->salaire)
                                        <span class="text-success fw-bold">{{ number_format($offre->salaire, 0, ',', ' ') }} DH</span>
                                    @else
                                        <span class="text-muted">Salaire non précisé</span>
                                    @endif
                                </div>
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $offre->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                            <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>{{ __('Voir détails') }}
                            </a>
                            @auth
                                @if(auth()->user()->isCandidat())
                                    <a href="{{ route('offres.formulaire', $offre->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i>{{ __('Postuler') }}
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ __('Aucune offre disponible pour le moment.') }}
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $offres->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Script pour la recherche
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const cards = document.querySelectorAll('.card-dashboard');
                
                cards.forEach(function(card) {
                    const title = card.querySelector('.card-title').textContent.toLowerCase();
                    const description = card.querySelector('.card-text').textContent.toLowerCase();
                    const lieu = card.querySelector('.text-muted').textContent.toLowerCase();
                    
                    if (title.includes(searchValue) || description.includes(searchValue) || lieu.includes(searchValue)) {
                        card.closest('.col-md-4').style.display = '';
                    } else {
                        card.closest('.col-md-4').style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection