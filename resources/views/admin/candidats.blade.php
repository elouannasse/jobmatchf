@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des Candidats</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($candidats as $candidat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $candidat->name }}</td>
                    <td>{{ $candidat->email }}</td>
                    <td>{{ $candidat->tel ?? 'N/A' }}</td>
                    <td>{{ $candidat->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $candidat->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                        <form action="{{ route('admin.users.destroy', $candidat->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun candidat trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $candidats->links() }}
</div>
@endsection