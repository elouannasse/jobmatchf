@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Candidatures</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du Candidat</th>
                    <th>Email</th>
                    <th>Offre</th>
                    <th>Statut</th>
                    <th>Date de Candidature</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($candidatures as $candidature)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $candidature->user->name }}</td>
                        <td>{{ $candidature->user->email }}</td>
                        <td>{{ $candidature->offre->titre ?? 'N/A' }}</td>
                        <td>{{ ucfirst($candidature->statut) }}</td>
                        <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <form action="{{ route('admin.candidatures.destroy', $candidature->id) }}" method="POST"
                                id="delete-form-{{ $candidature->id }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette candidature ?')) document.getElementById('delete-form-{{ $candidature->id }}').submit();">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucune candidature trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $candidatures->links() }}
    </div>
@endsection