<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\User;
use App\Models\Candidature;

class HomeController extends Controller
{
   
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isRecruteur()) {
            return $this->recruteurDashboard();
        } else {
            return $this->candidatDashboard();
        }
    }

   
    public function adminDashboard()
    {
        $stats = [
            'total_utilisateurs' => User::count(),
            'total_recruteurs' => User::whereHas('role', function($query) {
                $query->where('name', User::ROLE_RECRUTEUR);
            })->count(),
            'total_candidats' => User::whereHas('role', function($query) {
                $query->where('name', User::ROLE_CANDIDAT);
            })->count(),
            'total_offres' => Offre::count(),
            'total_candidatures' => Candidature::count(),
        ];

        $latestOffers = Offre::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $monthlyRegistrations = [];
        $monthLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M');
            
            $monthlyRegistrations[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return view('admin.dashboard', compact('stats', 'latestOffers', 'monthlyRegistrations', 'monthLabels'));
    }

    
    private function recruteurDashboard()
    {
        $user = auth()->user();
        
        $totalOffres = $user->offres()->count();
        $offresActives = $user->offres()->where('etat', true)->count();
        $totalCandidatures = Candidature::whereHas('offre', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        $recentOffres = $user->offres()->latest()->take(5)->get();
        
        $recentCandidatures = Candidature::whereHas('offre', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['user', 'offre'])->latest()->take(5)->get();
        
        return view('home', compact('totalOffres', 'offresActives', 'totalCandidatures', 'recentOffres', 'recentCandidatures'));
    }

    
    private function candidatDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'total_candidatures' => $user->candidatures()->count(),
            'candidatures_en_cours' => $user->candidatures()->where('statut', 'en_attente')->count(),
            'candidatures_acceptees' => $user->candidatures()->where('statut', 'acceptee')->count(),
            'candidatures_refusees' => $user->candidatures()->where('statut', 'refusee')->count(),
        ];
        
        $offres_recentes = Offre::where('etat', true)
            ->where('approved', true)  
            ->with('user')             
            ->latest()
            ->take(5)
            ->get();
            
        $candidatures_recentes = $user->candidatures()
            ->with('offre')
            ->latest()
            ->take(5)
            ->get();
        
        return view('candidat.dashboard', compact('stats', 'offres_recentes', 'candidatures_recentes'));
    }
}