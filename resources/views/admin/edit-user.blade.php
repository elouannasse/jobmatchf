@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Modifier un utilisateur') }}</h1>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> {{ __('Retour') }}
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Informations de l\'utilisateur') }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">{{ __('Prénom') }}</label>
                                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom', $user->prenom) }}" required autocomplete="prenom" autofocus>
                                @error('prenom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('Nom') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">{{ __('Numéro de téléphone') }}</label>
                            <input id="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" name="telephone" value="{{ old('telephone', $user->telephone) }}" autocomplete="telephone">
                            @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">{{ __('Le numéro de téléphone est optionnel.') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">{{ __('Rôle') }}</label>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">{{ __('Sélectionner un rôle') }}</option>
                                <option value="Candidat" {{ (old('role', $user->role ? $user->role->name : '') == 'Candidat') ? 'selected' : '' }}>{{ __('Candidat') }}</option>
                                <option value="Recruteur" {{ (old('role', $user->role ? $user->role->name : '') == 'Recruteur') ? 'selected' : '' }}>{{ __('Recruteur') }}</option>
                                <option value="Admin" {{ (old('role', $user->role ? $user->role->name : '') == 'Administrateur') ? 'selected' : '' }}>{{ __('Administrateur') }}</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Date d\'inscription') }}</label>
                            <input type="text" class="form-control" value="{{ $user->created_at->format('d/m/Y H:i') }}" disabled>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-md-2">{{ __('Annuler') }}</a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Enregistrer les modifications') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">{{ __('Changer le mot de passe') }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_password" value="1">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-danger">
                                {{ __('Changer le mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-dashboard shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">{{ __('Zone dangereuse') }}</h6>
                </div>
                <div class="card-body">
                    <p>{{ __('La suppression d\'un utilisateur est définitive et entraînera la suppression de toutes les données associées.') }}</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> {{ __('Supprimer cet utilisateur') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirmer la suppression') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Êtes-vous sûr de vouloir supprimer cet utilisateur?') }}</p>
                <p class="text-danger">{{ __('Cette action est irréversible et entraînera la suppression de toutes les données associées.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                <form id="delete-user-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Supprimer définitivement') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Correction du problème de vibration du modal et boutons non cliquables
        const deleteModal = document.getElementById('deleteModal');
        
        if (deleteModal) {
            // Assurer que le modal est correctement initialisé
            const modal = new bootstrap.Modal(deleteModal);
            
            // Empêcher la propagation des événements de clic à l'intérieur du modal
            const modalContent = deleteModal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // S'assurer que le bouton d'annulation fonctionne correctement
            const cancelButton = deleteModal.querySelector('.btn-secondary');
            if (cancelButton) {
                cancelButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    modal.hide();
                });
            }
            
            // S'assurer que le formulaire de suppression fonctionne correctement
            const deleteForm = document.getElementById('delete-user-form');
            const deleteButton = deleteForm ? deleteForm.querySelector('button[type="submit"]') : null;
            
            if (deleteButton) {
                deleteButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        }
    });
</script>
@endpush
@endsection