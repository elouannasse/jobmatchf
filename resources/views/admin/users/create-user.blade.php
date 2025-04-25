@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg" style="width: 100%; max-width: 600px;">
            <div class="card-header text-center bg-primary text-white">
                <h4>Ajouter un Utilisateur</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">Nom</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="role_id">Rôle</label>
                        <select name="role_id" id="role_id" class="form-control" required>
                            <option value="">-- Sélectionnez un rôle --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection