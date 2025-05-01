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
                                    
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" data-user-name="{{ $user->name }}" data-user-id="{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Include the shared delete confirmation modal -->
@include('components.modals.delete-confirmation')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Direct implementation for user deletion
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            
            // Get the form
            const form = $(this).closest('form');
            const userName = form.data('user-name');
            
            // Update modal content
            $('#confirmationMessage').text('Êtes-vous sûr de vouloir supprimer l\'utilisateur ' + userName + ' ?');
            
            // Show modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            
            // When confirm button is clicked
            $('#confirmDelete').off('click').on('click', function() {
                form.submit();
                deleteModal.hide();
            });
        });
    });
</script>
@endpush
@endsection