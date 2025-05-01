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
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                            data-user-name="{{ $user->name }}" 
                                            data-user-id="{{ $user->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
                {{ $users->links('components.pagination') }}
            </div>
        </div>
    </div>
</div>

<!-- Custom Delete Confirmation Modal -->
<div class="modal" id="customDeleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="modal-dialog" style="margin: 10% auto; max-width: 500px;">
        <div class="modal-content" style="background-color: white; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
            <div class="modal-header bg-danger text-white" style="padding: 15px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" id="closeModalBtn" style="background-color: transparent; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h4 id="customConfirmationMessage" style="margin-bottom: 15px;">Êtes-vous sûr de vouloir supprimer cet utilisateur?</h4>
                    <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 4px;">
                        <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> Cette action est irréversible et entraînera la suppression de toutes les données associées.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 15px; display: flex; justify-content: center; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-secondary" id="customCancelDelete" style="background-color: #6c757d; color: white; padding: 8px 16px; margin-right: 10px; border: none; border-radius: 4px; cursor: pointer;">Annuler</button>
                <button type="button" class="btn btn-danger" id="customConfirmDelete" style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Supprimer définitivement</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const modal = document.getElementById('customDeleteModal');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('customCancelDelete');
        const confirmBtn = document.getElementById('customConfirmDelete');
        const confirmMessage = document.getElementById('customConfirmationMessage');
        
        let currentUserId = null;
        
        // Add click event to all delete buttons
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Get user info
                const userName = this.getAttribute('data-user-name');
                currentUserId = this.getAttribute('data-user-id');
                
                // Update confirmation message
                confirmMessage.textContent = `Êtes-vous sûr de vouloir supprimer l'utilisateur ${userName} ?`;
                
                // Show modal
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        });
        
        // Close modal function
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = ''; // Restore scrolling
        }
        
        // Close modal when clicking close button
        closeBtn.addEventListener('click', closeModal);
        
        // Close modal when clicking cancel button
        cancelBtn.addEventListener('click', closeModal);
        
        // Submit form when clicking confirm button
        confirmBtn.addEventListener('click', function() {
            if (currentUserId) {
                const form = document.getElementById(`delete-form-${currentUserId}`);
                if (form) {
                    form.submit();
                }
            }
            closeModal();
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
</script>
@endpush
@endsection