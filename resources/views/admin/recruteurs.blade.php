@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Recruteurs</h1>

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
                    <th>Entreprise</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recruteurs as $recruteur)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $recruteur->name }}</td>
                        <td>{{ $recruteur->email }}</td>
                        <td>{{ $recruteur->typeEntreprise->nom ?? 'N/A' }}</td>
                        <td>{{ $recruteur->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $recruteur->id) }}"
                                class="btn btn-primary btn-sm">Modifier</a>
                            <form action="{{ route('admin.users.destroy', $recruteur->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce recruteur ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun recruteur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $recruteurs->links() }}
    </div>
@endsection