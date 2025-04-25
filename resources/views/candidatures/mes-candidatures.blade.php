@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Mes candidatures') }}</h4>
                </div>
                <div class="card-body">
                    @if(count($candidatures) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidatures as $candidature)
                                        <tr>
                                            <td>{{ $candidature->offre->titre }}</td>
                                            <td>{{ $candidature->offre->user->nom_entreprise }}</td>
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
                                                <a href="{{ route('offres.show', $candidature->offre) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir l'offre
                                                </a>
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
                            Vous n'avez pas encore postulé à une offre d'emploi.
                            <a href="{{ route('offres.disponibles') }}" class="alert-link">Découvrir les offres disponibles</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection