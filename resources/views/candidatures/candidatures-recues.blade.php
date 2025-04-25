@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Candidatures reçues') }}</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(count($candidatures) > 0)
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
                                    @foreach($candidatures as $candidature)
                                        <tr>
                                            <td>{{ $candidature->user->prenom }} {{ $candidature->user->name }}</td>
                                            <td>{{ $candidature->offre->titre }}</td>
                                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($candidature->statut == 'en_attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($candidature->statut == 'acceptee')
                                                    <span class="badge bg-success">Acceptée</span>
                                                @elseif($candidature->statut == 'refusee')
                                                    <span class="badge bg-danger">Refusée</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('candidatures.show', $candidature) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Détails
                                                </a>
                                                
                                                @if($candidature->statut == 'en_attente')
                                                    <form action="{{ route('candidatures.accepter', $candidature) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check"></i> Accepter
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('candidatures.refuser', $candidature) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times"></i> Refuser
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $candidatures->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Vous n'avez pas encore reçu de candidatures pour vos offres d'emploi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection