@extends('layouts.liste-datatable')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Liste des utilisateurs</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">utilisateur</li>
                        <li class="breadcrumb-item active" aria-current="page">Liste</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <div class="content">

        <x-alert type="success" session-name="message"/>
        <x-alert type="danger" session-name="error"/>

        <!-- Dynamic Table Responsive -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Liste des utilisateurs
                </h3>
                <div>
                    <a href="{{ route('user.create') }}" class="btn btn-md btn-primary">Ajouter un utilisateur</a>
                </div>
            </div>
            <div class="block-content block-content-full overflow-x-auto">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Statut</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">
                                <a href="{{ route('user.show', $user) }}" class="text-primary link-fx">
                                    {{ $user->name . ' ' . $user->prenom }}
                                </a>
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>
                                {{ $user->role->name }}
                            </td>
                            <td>
                                <span class="badge {{ $user->etat ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->etat ? 'Actif' : 'Bloqué' }}
                                </span>
                            </td>


                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('user.edit', $user) }}"
                                       type="button" class="btn btn-md btn-alt-info js-bs-tooltip-enabled"
                                       data-bs-toggle="tooltip" aria-label="Modifier"
                                       data-bs-original-title="Modifier" data-bs-placement="top">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    @if ($user->etat)
                                        <form action="{{ route('user.deactivate', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <a href="" type="button" id="delete-button" onclick="event.preventDefault();
                                            if(confirm('Êtes-vous sûr de vouloir bloquer cet utilisateur ?')){
                                            this.closest('form').submit();}"
                                               class="btn btn-md btn-alt-danger js-bs-tooltip-enabled"
                                               data-bs-toggle="tooltip" aria-label="Bloquer"
                                               data-bs-original-title="Bloquer" data-bs-placement="top">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                        </form>
                                    @else
                                        <form action="{{ route('user.activate', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <a href="#" type="button" id="delete-button" onclick="event.preventDefault();
                                            if(confirm('Êtes-vous sûr de vouloir activer cet utilisateur ?')){
                                            this.closest('form').submit();}"
                                               class="btn btn-md btn-alt-warning js-bs-tooltip-enabled"
                                               data-bs-toggle="tooltip" aria-label="Activer"
                                               data-bs-original-title="Activer" data-bs-placement="top">
                                                <i class="fa fa-thumbs-down"></i>
                                            </a>
                                        </form>
                                    @endif

                                    {{--@unless($user->etat)
                                        <form action="{{ route('user.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" type="button" onclick="event.preventDefault();
                                            if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')){
                                            this.closest('form').submit();}"
                                               class="btn btn-md btn-alt-danger js-bs-tooltip-enabled"
                                               data-bs-toggle="tooltip" aria-label="Supprimer"
                                               data-bs-original-title="Supprimer" data-bs-placement="top">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </form>
                                    @endunless--}}
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
@endsection
