<?php

namespace App\Http\Controllers;

use App\Enums\StatutCandidature;
use App\Enums\StatutOffre;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()

    {
        $user = auth()->user();
        $fullName = $user->name.' '.$user->prenom;

        // Admin dashboard data
        if ($user->isAdmin()) {
            $adminData = [
                'totalUsers' => User::count(),
                'totalOffres' => Offre::count(),
                'totalCandidatures' => Candidature::count(),

                'totalRecruteurs' => User::whereRelation('role', 'name', 'recruteur')->count(),
                'totalCandidats' => User::whereRelation('role', 'name', 'candidat')->count(),

                'offresEnAttentes' => Offre::whereStatut(StatutOffre::EN_ATTENTE)->count(),
                'offresValidees' => Offre::whereStatut(StatutOffre::VALIDER)->count(),
                'offresRejetees' => Offre::whereStatut(StatutOffre::REJETER)->count(),

                'candidaturesEnAttentes' => Candidature::whereStatut(StatutCandidature::EN_COURS)->count(),
                'candidaturesAcceptees' => Candidature::whereStatut(StatutCandidature::ACCEPTER)->count(),
                'candidaturesRejetees' => Candidature::whereStatut(StatutCandidature::REJETER)->count(),

                'userFullName' => $fullName,
            ];

            return view('dashboard', ['data' => $adminData, 'role' => 'admin']);
        }

        // Recruteur dashboard data
        if ($user->isRecruteur()) {
            $recruteurData = [
                'offresRecruteur' => $user->offres()->count(),

                'offresEnAttente' => $user->offres()->whereStatut(StatutOffre::EN_ATTENTE)->count(),

                'offresExpirees' => $user->offres()->whereDate('date_fin', '<', now())->count(),

                'candidaturesReçues' => Candidature::whereHas('offre', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),

                'candidaturesAcceptees' => Candidature::whereHas('offre', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->whereStatut(StatutCandidature::ACCEPTER)->count(),

                'candidaturesRejetees' => Candidature::whereHas('offre', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->whereStatut(StatutCandidature::REJETER)->count(),

                'candidaturesEnAttente' => Candidature::whereHas('offre', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->whereStatut(StatutCandidature::EN_COURS)->count(),

                'userFullName' => $fullName,
            ];

            return view('dashboard', ['data' => $recruteurData, 'role' => 'recruteur']);
        }

        // Candidat dashboard data
        if ($user->isCandidat()) {
            $candidatData = [
                'candidaturesSoumises' => $user->candidatures()->count(),

                'candidaturesAcceptees' => $user->candidatures()->whereStatut(
                    StatutCandidature::ACCEPTER)->count(),

                'candidaturesRejetees' => $user->candidatures()->whereStatut(
                    StatutCandidature::REJETER)->count(),

                'candidaturesEnAttente' => $user->candidatures()->whereStatut(
                    StatutCandidature::EN_COURS)->count(),

                'userFullName' => $fullName,
            ];

            return view('dashboard', ['data' => $candidatData, 'role' => 'candidat']);
        }

        abort(403, 'Accès non autorisé.');
    }
}