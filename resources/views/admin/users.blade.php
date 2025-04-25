@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Gestion des utilisateurs') }}</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> {{ __('Ajouter un utilisateur') }}
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card card-dashboard shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Liste des utilisateurs') }}</h6>
        </div>
       
        <div class="card-body">
            <div class="table-responsive test">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Nom') }}</th>
                            <th>{{ __('Prénom') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Rôle') }}</th>
                            <th>{{ __('Date d\'inscription') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->prenom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge bg-{{ $user->role->name == 'Administrateur' ? 'danger' : ($user->role->name == 'Recruteur' ? 'primary' : 'success') }}">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Non défini') }}</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-user-id="{{ $user->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de confirmation de suppression -->
                                <div class="modal fade delete-modal" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">{{ __('Confirmer la suppression') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('Êtes-vous sûr de vouloir supprimer cet utilisateur?') }}</p>
                                                <p class="text-danger">{{ __('Cette action est irréversible et entraînera la suppression de toutes les données associées.') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-confirm-btn">{{ __('Supprimer définitivement') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('Aucun utilisateur trouvé') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélectionner tous les boutons de suppression
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const modal = new bootstrap.Modal(document.getElementById('deleteModal' + userId));
                modal.show();
            });
        });
        
        // Corriger le problème de vibration des modals
        const deleteModals = document.querySelectorAll('.delete-modal');
        
        deleteModals.forEach(modal => {
            // Empêcher la propagation des clics dans le contenu du modal
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // S'assurer que les boutons fonctionnent correctement
            const cancelButton = modal.querySelector('.btn-secondary');
            if (cancelButton) {
                cancelButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    bootstrap.Modal.getInstance(modal).hide();
                });
            }
            
            // S'assurer que le bouton de suppression fonctionne correctement
            const deleteButton = modal.querySelector('.delete-confirm-btn');
            if (deleteButton) {
                deleteButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    });
</script>
@endpush
@endsection