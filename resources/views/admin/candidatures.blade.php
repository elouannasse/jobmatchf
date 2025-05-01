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

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Candidatures</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCandidatures }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Candidatures Acceptées</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $acceptees }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Candidatures Refusées</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $refusees }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Liste des Candidatures</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nom du Candidat</th>
                                <th>Offre</th>
                                <th>Date de Candidature</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidatures as $candidature)
                                <tr>
                                    <td>{{ $candidature->user->name }}</td>
                                    <td>{{ $candidature->offre->titre }}</td>
                                    <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $candidature->statut == 'acceptee' ? 'success' : ($candidature->statut == 'refusee' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($candidature->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('candidatures.updateStatus', $candidature->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="status" value="acceptee"
                                                    class="btn btn-sm btn-success">Accepter</button>
                                                <button type="submit" name="status" value="refusee"
                                                    class="btn btn-sm btn-danger">Refuser</button>
                                            </form>
                                            <form action="{{ route('candidatures.destroy', $candidature->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune candidature trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $candidatures->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection