@extends('layouts.liste-datatable')

@use("App\Enums\StatutOffre")

@section('content')

    <div class="content">
        <!-- Affichage des messages de succès ou d'erreur -->
        <x-alert type="success" session-name="message"/>
        <x-alert type="danger" session-name="error"/>

        <!-- Dynamic Table Responsive -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title fs-lg">
                    <i class="fa fa-briefcase text-muted me-1"></i>
                    @if($isAdmin)
                        {{ __('Jobs to be validated')}}
                    @else
                        {{ __('Your current jobs')}}
                    @endif
                </h3>
                @unless($isAdmin)
                    @if(is_null(auth()->user()->type_entreprise_id))
                        <div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-md btn-primary">
                                Complétez votre profil
                            </a>
                        </div>
                    @else
                        <div>
                            <a href="{{ route('offre.create') }}" class="btn btn-md btn-primary">
                                Ajouter une nouvelle offre
                            </a>
                        </div>
                    @endif
                @endunless
            </div>
            <div class="block-content block-content-full overflow-x-auto">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                    <thead>
                    <tr>
                        <th>Titre du poste</th>
                        <th>Type de l'offre</th>
                        @if($isAdmin)
                            <th>Entreprise</th>
                        @else
                            <th>Salaire proposé</th>
                        @endif
                        <th>Date de fin</th>
                        <th>Statut</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($offres as $offre)
                        <tr>
                            <td class="fw-semibold">
                                <a href="{{ route('offre.show', $offre) }}" class="text-primary link-fx">
                                    {{ $offre->title }}
                                </a>
                            </td>
                            <td style="width: 15%;">{{ Str::title($offre->type_offre) }}</td>
                            @if($isAdmin)
                                <td>{{ $offre->user->nom_entreprise }}</td>
                            @else
                                <td>
                                    {{ number_format($offre->salaire_proposer, thousands_separator: ' ') . " FCFA" }}
                                </td>
                            @endif
                            <td>{{ $offre->date_fin->format('d M Y') }}</td>
                            <td class="text-center">
                                <span
                                    class="badge px-2 {{ $offre->statut->badgeClass() }}">
                                    {{ $offre->statut->value }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @if($isAdmin)
                                        <div class="d-flex justify-content-between gap-2">
                                            <form action="{{ route('offre.validate', $offre) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-md btn-alt-primary">
                                                    Valider
                                                </button>
                                            </form>
                                            <form action="{{ route('offre.rejeter', $offre) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-md btn-alt-danger">
                                                    Rejeter
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        @if($offre->candidatures_count <= 0 && $offre->statut !== StatutOffre::VALIDER)
                                            <a href="{{ route('offre.edit', $offre) }}"
                                               class="btn btn-md btn-alt-secondary js-bs-tooltip-enabled"
                                               data-bs-toggle="tooltip" aria-label="Modifier"
                                               data-bs-original-title="Modifier">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('offre.destroy', $offre) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        id="{{ $offre->candidatures_count ?? 'delete-button'}}"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')"
                                                        class="btn btn-md btn-alt-danger js-bs-tooltip-enabled"
                                                        data-bs-toggle="tooltip" aria-label="Supprimer"
                                                        data-bs-original-title="Supprimer">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @elseif($offre->candidatures_count > 0)
                                            <a href="{{ route('offre.candidature.index', $offre) }}"
                                               class="btn btn-md btn-alt-info js-bs-tooltip-enabled"
                                               data-bs-toggle="tooltip" aria-label="Voir les candidatures"
                                               data-bs-original-title="Voir les candidatures">
                                                <i class="fa fa-users"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
@endsection
