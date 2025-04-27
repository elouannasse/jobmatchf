@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Gestion des candidatures') }}</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card card-dashboard shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Liste des Candidatures') }}</h6>
        </div>
       
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Nom du Candidat') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Offre') }}</th>
                            <th>{{ __('Statut') }}</th>
                            <th>{{ __('Date de Candidature') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($candidatures as $candidature)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $candidature->user->name }}</td>
                                <td>{{ $candidature->user->email }}</td>
                                <td>{{ $candidature->offre->titre ?? $candidature->offre->title ?? 'N/A' }}</td>
                                <td>
                                    @if ($candidature->statut == 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif ($candidature->statut == 'acceptee')
                                        <span class="badge bg-success">Acceptée</span>
                                    @elseif ($candidature->statut == 'refusee')
                                        <span class="badge bg-danger">Refusée</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($candidature->statut) }}</span>
                                    @endif
                                </td>
                                <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info view-candidature-btn" data-candidature-id="{{ $candidature->id }}">
                                            <i class="fas fa-eye"></i> {{ __('Voir') }}
                                        </button>
                                        
                                        <form action="{{ route('admin.candidatures.destroy', $candidature->id) }}" method="POST" class="d-inline" data-candidature-name="{{ $candidature->user->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                <i class="fas fa-trash"></i> {{ __('Supprimer') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('Aucune candidature trouvée') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $candidatures->links('components.pagination') }}
            </div>
        </div>
    </div>
</div>

<!-- Include the shared delete confirmation modal -->
@include('components.modals.delete-confirmation')

<!-- Include the candidature details modal -->
@include('components.modals.candidature-details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/delete-modal.js') }}"></script>
<script src="{{ asset('js/candidature-modal.js') }}"></script>
@endpush
@endsection