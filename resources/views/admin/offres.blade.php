@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestion des Offres</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Recruteur</th>
                    <th>Lieu</th>
                    <th>État</th>
                    <th>Date de Création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($offres as $offre)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $offre->titre }}</td>
                        <td>{{ $offre->user->name ?? 'N/A' }}</td>
                        <td>{{ $offre->lieu ?? 'N/A' }}</td>
                        <td>{{ $offre->etat ? 'Actif' : 'Inactif' }}</td>
                        <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <form action="{{ route('offres.destroy', $offre->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucune offre trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $offres->links('components.pagination') }}
    </div>
@endsection