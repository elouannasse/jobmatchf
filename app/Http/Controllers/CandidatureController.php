<?php

namespace App\Http\Controllers;

use App\Enums\StatutCandidature;
use App\Http\Requests\CandidatureRequest;
use App\Mail\CandidatureAcceptedMail;
use App\Mail\CandidatureRejectedMail;
use App\Mail\CandidatureSubmittedMail;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    public function index(?Offre $offre = null): View
    {
        $user = auth()->user();

        if ($user->isRecruteur()) {
            // Vérifier que l'utilisateur est bien le propriétaire de l'offre
            abort_if($user->id !== $offre->user_id, 403,
                "Vous n'êtes pas autorisé à voir les candidatures de cette offre.");

            // Récupérer toutes les candidatures liées à cette offre
            $candidatures = Candidature::whereHas('offre', function ($query) use ($user, $offre) {
                $query->where('id', $offre->id)
                    ->where('user_id', $user->id);
            })->get();
        } elseif ($user->isCandidat()) {
            // Récupérer les candidatures du candidat connecté
            $candidatures = $user->candidatures()->get();
        } else {
            abort(403); // Accès non autorisé
        }
        // Charger les relations
        $candidatures->load(['offre', 'user']);

        return view('candidature.index', compact('candidatures', 'offres'));
    }

    public
    function accepter(
        Candidature $candidature
    ): RedirectResponse {
        $this->authorize('update', $candidature);

        $candidature->update(['statut' => StatutCandidature::ACCEPTER]);

        Mail::to($candidature->user->email)->send(new CandidatureAcceptedMail($candidature));

        return to_route('offre.candidature.index', $candidature->offre)
            ->with('message', 'Candidature acceptée avec succès.');
    }

    public
    function rejeter(
        Candidature $candidature
    ): RedirectResponse {
        $this->authorize('update', $candidature);

        $candidature->update(['statut' => StatutCandidature::REJETER]);

        Mail::to($candidature->user->email)->send(new CandidatureRejectedMail($candidature));

        return to_route('offre.candidature.index', $candidature->offre)
            ->with('message', 'Candidature rejetée avec succès.');
    }

    public
    function store(
        CandidatureRequest $request,
        Offre $offre
    ): RedirectResponse {
        $this->authorize('create', Candidature::class);

        $data = $request->validated();

        // Gestion des fichiers (lettre de motivation et CV)
        $data['lettre_motivation'] = $this->handleUploadedFile($request, 'lettre_motivation', 'candidatures');
        $data['cv'] = $this->handleUploadedFile($request, 'cv', 'candidatures');
        $data['offre_id'] = $offre->id;

        $candidature = $request->user()->candidatures()->create($data);

        // Envoyer un email au recruteur lié à l'offre
        Mail::to($offre->user->email)
            ->send(new CandidatureSubmittedMail($candidature, $offre));

        return to_route('candidature.index')->with('message', 'Candidature créée avec succès');
    }

    public
    function create(
        Offre $offre
    ): View {
        $this->authorize('create', Candidature::class);

        return view('candidature.add', compact('offre'));
    }

    /**
     * Afficher les détails d'une candidature et les informations du candidat.
     */
    public
    function show(
        Offre $offre,
        Candidature $candidature
    ): View {
        // Autoriser l'accès à la candidature en fonction des policies
        $this->authorize('view', $candidature);

        // Récupérer toutes les informations du candidat lié à cette candidature
        $candidat = User::with(['langues', 'competences', 'experiences', 'formations'])
            ->find($candidature->user_id, ['id', 'name', 'prenom', 'email', 'tel', 'adresse']);

        // Afficher la vue avec les détails de la candidature et du candidat
        return view('candidature.show', compact('candidat', 'candidature', 'offre'));
    }
}
