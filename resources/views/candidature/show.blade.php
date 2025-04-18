@extends('layouts.backend')

@use("App\Enums\StatutCandidature")

@php
    $isRecruteur = auth()->user()->isRecruteur() ?? false;
@endphp

@section('content')
    <!-- Hero Section -->
    <!-- Affichage des messages de succès ou d'erreur -->
    <x-alert type="success" session-name="message"/>
    <x-alert type="danger" session-name="error"/>

    <div class="bg-image" style="background-image: url('{{ asset('media/photos/photo21@2x.jpg') }}');">
        <div class="bg-black-75">
            <div class="content content-boxed content-full py-5">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center py-3">
                        <div class="w-100 text-center text-md-start">
                            <h1 class="h2 text-white mb-2">
                                Candidature de {{ $candidat->name }} {{ $candidat->prenom }}
                            </h1>
                            <h2 class="h4 fs-sm text-uppercase fw-semibold text-white-75 mb-2">
                                Pour l'offre :
                                @if($isRecruteur)
                                    {{ $offre->title }}
                                @else
                                    <a class="fw-semibold text-primary-light"
                                       href="{{ route('offre.show', $offre) }}">
                                        {{ $offre->title }}
                                    </a>
                                @endif
                            </h2>
                            <h2 class="h4 fs-sm text-uppercase fw-semibold text-white-75">
                                Reçue {{ $candidature->created_at->diffForHumans() }}
                            </h2>
                        </div>
                    </div>
                    @if($isRecruteur && $candidature->statut === StatutCandidature::EN_COURS)
                        <!-- Actions pour recruteur (Accepter/Refuser) -->
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <div class="w-100 text-center text-md-start">
                                <form action="{{ route('candidature.accepter', $candidature) }}" method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-check opacity-50 me-1"></i> Accepter
                                    </button>
                                </form>

                                <form action="{{ route('candidature.rejeter', $candidature) }}" method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-times opacity-50 me-1"></i> Rejeter
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Affichage du statut pour le candidat -->{{--
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <div class="w-100 text-center text-md-start p-3 rounded bg-light shadow">
                                <h2 class="h4 fs-sm text-uppercase fw-semibold text-dark mb-3">
                                    Statut du Candidat
                                </h2>
                                <div class="badge
                                    @if($candidature->statut === StatutCandidature::ACCEPTER) bg-success
                                    @elseif($candidature->statut === StatutCandidature::ENTRETIEN_PLANIFIE) bg-primary
                                    @elseif($candidature->statut === StatutCandidature::EN_COURS) bg-warning
                                    @elseif($candidature->statut === StatutCandidature::REJETER) bg-danger
                                    @else bg-warning @endif
                                    p-3 fs-5">
                                    {{ Str::replace('_',' ',$candidature->statut->value) }}
                                </div>
                            </div>
                        </div>--}}
                        <div class="col-md-4 d-flex align-items-center">
                            <a class="block block-rounded block-link-shadow block-transparent bg-black-50
                        text-center mb-0 mx-auto">
                                <div class="block-content block-content-full p-3 text-center">
                                    <div class="fs-3 fw-semibold text-white mb-2">
                                        Statut du candidat
                                    </div>
                                    <button class="btn btn-hero text-white {{ $candidature->statut->badgeClass() }}">
                                        {{ Str::of($candidature->statut->value)->replace('_', ' ')->title() }}
                                    </button>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero Section -->


    <!-- Page Content -->
    <div class="content content-boxed">
        <!-- Block Tabs Animated Fade -->
        <div class="block block-rounded">
            <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                @if($isRecruteur)
                    <li class="nav-item">
                        <button class="nav-link active" id="btabs-animated-fade-info-tab" data-bs-toggle="tab"
                                data-bs-target="#btabs-animated-fade-info" role="tab"
                                aria-controls="btabs-animated-fade-info" aria-selected="true">Info du candidat
                        </button>
                    </li>
                @endif
                <li class="nav-item ">
                    <button class="nav-link {{ $isRecruteur ? '' : 'active' }}" id="btabs-animated-fade-cv-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#btabs-animated-fade-cv" role="tab"
                            aria-controls="btabs-animated-fade-cv" aria-selected="false">CV
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-animated-fade-lettre-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-animated-fade-lettre" role="tab"
                            aria-controls="btabs-animated-fade-lettre" aria-selected="false">Lettre de motivation
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-animated-fade-formation-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-animated-fade-formation" role="tab"
                            aria-controls="btabs-animated-fade-formation" aria-selected="false">Formations
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-animated-fade-competence-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-animated-fade-competence" role="tab"
                            aria-controls="btabs-animated-fade-competence" aria-selected="false">Compétences
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-animated-fade-experience-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-animated-fade-experience" role="tab"
                            aria-controls="btabs-animated-fade-experience" aria-selected="false">Expériences
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-animated-fade-langue-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-animated-fade-langue" role="tab"
                            aria-controls="btabs-animated-fade-langue" aria-selected="false">Langues
                    </button>
                </li>
            </ul>

            <div class="block-content tab-content overflow-hidden">
                <!-- Informations personnelles -->
                @if($isRecruteur)
                    <div class="tab-pane fade show active" id="btabs-animated-fade-info" role="tabpanel"
                         aria-labelledby="btabs-animated-fade-info-tab" tabindex="0">
                        <ul class="fa-ul list-icons">
                            <li>
                            <span class="fa-li text-primary">
                                <i class="fa fa-user"></i>
                            </span>
                                <div class="fw-semibold">Nom complet</div>
                                <div class="text-muted">{{ $candidat->name }} {{ $candidat->prenom }}</div>
                            </li>
                            <li>
                            <span class="fa-li text-primary">
                                <i class="fa fa-envelope"></i>
                            </span>
                                <div class="fw-semibold">Email</div>
                                <div class="text-muted">{{ $candidat->email }}</div>
                            </li>
                            <li>
                            <span class="fa-li text-primary">
                                <i class="fa fa-phone"></i>
                            </span>
                                <div class="fw-semibold">Téléphone</div>
                                <div class="text-muted">{{ $candidat->tel }}</div>
                            </li>
                            <li>
                            <span class="fa-li text-primary">
                                <i class="fa fa-map-marker-alt"></i>
                            </span>
                                <div class="fw-semibold">Adresse</div>
                                <div class="text-muted">{{ $candidat->adresse }}</div>
                            </li>
                        </ul>
                    </div>
                @endif

                <!-- CV -->
                <div class="tab-pane fade {{ $isRecruteur ? '' : 'show active' }}" id="btabs-animated-fade-cv"
                     role="tabpanel"
                     aria-labelledby="btabs-animated-fade-cv-tab" tabindex="0">
                    <embed src="{{ Storage::url($candidature->cv) }}" type="application/pdf" width="100%"
                           height="500px"/>
                </div>

                <!-- Lettre de motivation -->
                <div class="tab-pane fade" id="btabs-animated-fade-lettre" role="tabpanel"
                     aria-labelledby="btabs-animated-fade-lettre-tab" tabindex="0">
                    <embed src="{{ Storage::url($candidature->lettre_motivation) }}" type="application/pdf" width="100%"
                           height="500px"/>
                </div>

                <!-- Formations -->
                <div class="tab-pane fade" id="btabs-animated-fade-formation" role="tabpanel"
                     aria-labelledby="btabs-animated-fade-formation-tab" tabindex="0">
                    @if ($candidat->formations->isEmpty())
                        <p>Aucune formation enregistrée.</p>
                    @else
                        <ul class="fa-ul list-icons">
                            @foreach ($candidat->formations as $formation)
                                <li>
                                    <span class="fa-li text-primary">
                                        <i class="fa fa-graduation-cap"></i>
                                    </span>
                                    <div class="fw-semibold">{{ $formation->name }}</div>
                                    <div class="text-muted">{{ $formation->institution }}
                                        ({{ $formation->date_debut->format('M Y') }}
                                        - {{ $formation->date_fin->format('M Y') }})
                                    </div>
                                    @if ($formation->diplome)
                                        <a href="{{ Storage::url($formation->diplome) }}" target="_blank"
                                           class="d-inline-block mb-2">Voir le diplôme
                                        </a>
                                    @else
                                        <div class="text-muted">Aucun diplôme.</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Compétences -->
                <div class="tab-pane fade" id="btabs-animated-fade-competence" role="tabpanel"
                     aria-labelledby="btabs-animated-fade-competence-tab" tabindex="0">
                    @if ($candidat->competences->isEmpty())
                        <p>Aucune compétence enregistrée.</p>
                    @else
                        <div class="row justify-content-around py-4">
                            @foreach ($candidat->competences as $competence)
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-brain text-primary me-2"></i>
                                        <div class="fw-semibold">{{ $competence->name }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Expériences -->
                <div class="tab-pane fade" id="btabs-animated-fade-experience" role="tabpanel"
                     aria-labelledby="btabs-animated-fade-experience-tab" tabindex="0">
                    @if ($candidat->experiences->isEmpty())
                        <p>Aucune expérience enregistrée.</p>
                    @else
                        <ul class="fa-ul list-icons">
                            @foreach ($candidat->experiences as $experience)
                                <li>
                                    <span class="fa-li text-primary">
                                        <i class="fa fa-briefcase"></i>
                                    </span>
                                    <div class="fw-semibold">{{ $experience->titre_post }}</div>
                                    <div class="text-muted">{{ $experience->entreprise }}
                                        ({{ $experience->date_debut->format('M Y') }}
                                        - {{ $experience->date_fin->format('M Y') }})
                                    </div>
                                    <div class="text-muted">{{ $experience->description }}</div>
                                </li>
                                @unless($loop->last)
                                    <br>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- langues -->
                <div class="tab-pane fade" id="btabs-animated-fade-langue" role="tabpanel"
                     aria-labelledby="btabs-animated-fade-langue-tab" tabindex="0">
                    @if ($candidat->langues->isEmpty())
                        <p>Aucune langue enregistrée.</p>
                    @else
                        <ul class="fa-ul list-icons">
                            @foreach ($candidat->langues as $langue)
                                <li>
                                    <span class="fa-li text-primary">
                                        <i class="fa fa-language"></i>
                                    </span>
                                    <div class="fw-semibold">{{ $langue->name }}
                                        <span class="text-muted"> ( {{ $langue->pivot->niveau }} )</span></div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <!-- END Block Tabs Animated Fade -->

        <!-- Actions: Accepter ou Refuser la Candidature -->
        {{--    @if(auth()->user()->can('update', $candidature))
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Actions</h3>
                    </div>
                    <div class="block-content">
                        <form action="{{ route('candidature.update', $candidature) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">Changer le statut</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="accepté" {{ $candidature->status == 'accepté' ? 'selected' : '' }}>Accepter</option>
                                    <option value="refusé" {{ $candidature->status == 'refusé' ? 'selected' : '' }}>Refuser</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-hero btn-primary">
                                <i class="fa fa-check opacity-50 me-1"></i> Mettre à jour
                            </button>
                        </form>
                    </div>
                </div>
            @endif--}}
    </div>
    <!-- END Page Content -->
@endsection
