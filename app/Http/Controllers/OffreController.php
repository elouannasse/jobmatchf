<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\User;
use App\Models\TypeEntreprise;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    /**
     * Constructeur: vérifie l'authentification et le rôle de l'utilisateur
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Méthodes accessibles uniquement aux recruteurs
            $recruteurMethods = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'mesOffres', 'toggleStatus'];
            
            // Vérification du rôle pour les méthodes restreintes
            if (in_array($request->route()->getActionMethod(), $recruteurMethods) 
                && !$request->user()->isRecruteur() 
                && !$request->user()->isAdmin()) { // Permettre aux administrateurs d'accéder
                abort(403, ' pas autorisé à accéder à cette page .');
            }
            
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offres = Auth::user()->offres()->latest()->paginate(10);
        return view('offres.index', compact('offres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('offres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'required|string|max:255',
            'type_contrat' => 'required|string|max:50',
            'salaire' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date|after:today',
        ]);
    
        $offre = new Offre($request->all());
        $offre->user_id = Auth::id();
        $offre->etat = false; // Initialement inactive
        $offre->approved = false; // En attente d'approbation (false au lieu de null)
        $offre->title = $request->titre;
        $offre->salaire_proposer = null;
        $offre->type_offre = null;
        $offre->date_debut = now();
        $offre->date_fin = $request->date_expiration ?? now()->addMonths(3);
        $offre->save();
        
        // Envoyer des notifications aux administrateurs
        $admins = User::whereHas('role', function($query) {
            $query->where('name', User::ROLE_ADMINISTRATEUR);
        })->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification('pending_offer', [
                'offer_id' => $offre->id,
                'offer_title' => $offre->titre,
                'recruteur_name' => Auth::user()->name,
                'created_at' => now()->toDateTimeString()
            ]));
        }
    
        return redirect()->route('offres.index')
            ->with('success', 'Offre créée avec succès. Elle sera visible après approbation par un administrateur.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        // Check if the current user is the owner or the offer is active and approved
        if (Auth::id() !== $offre->user_id && (!$offre->etat || !$offre->approved) && !Auth::user()->isAdmin()) {
            abort(403, 'Cette offre n\'est pas disponible.');
        }

        return view('offres.show', compact('offre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offre $offre)
    {
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        return view('offres.edit', compact('offre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offre $offre)
    {
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'required|string|max:255',
            'type_contrat' => 'required|string|max:50',
            'salaire' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date|after:today',
            'etat' => 'boolean',
        ]);

        // Si l'offre est modifiée, elle doit être à nouveau approuvée
        $offre->approved = false; // Changé de null à false
        $offre->etat = false;

        $offre->update($request->all());
        $offre->title = $request->titre; // Mise à jour du champ title
        $offre->date_fin = $request->date_expiration ?? $offre->date_fin; // Mise à jour de date_fin si date_expiration est présente
        $offre->save();
        
        // Envoyer des notifications aux administrateurs pour l'offre modifiée
        $admins = User::whereHas('role', function($query) {
            $query->where('name', User::ROLE_ADMINISTRATEUR);
        })->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification('pending_offer', [
                'offer_id' => $offre->id,
                'offer_title' => $offre->titre,
                'recruteur_name' => Auth::user()->name,
                'created_at' => now()->toDateTimeString(),
                'is_update' => true
            ]));
        }

        return redirect()->route('offres.index')
            ->with('success', 'Offre mise à jour avec succès. Elle devra être à nouveau approuvée par un administrateur.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id) {
            // Allow admins to delete any offer
            if (Auth::user()->isAdmin()) {
                $offre->delete();
                return redirect()->route('admin.offres')
                    ->with('success', 'Offre supprimée avec succès.');
            }
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette offre.');
        }

        $offre->delete();

        return redirect()->route('offres.index')
            ->with('success', 'Offre supprimée avec succès.');
    }

    /**
     * Display a listing of offers posted by the current user.
     */
    public function mesOffres()
    {
        $offres = Auth::user()->offres()->latest()->paginate(10);
        return view('offres.mes-offres', compact('offres'));
    }

    /**
     * Display a listing of available offers for candidates.
     * Affiche uniquement les offres actives et approuvées par un administrateur
     */
    public function offresDisponibles()
    {
        $offres = Offre::where('etat', true)
            ->where('approved', true)  // Seulement les offres approuvées
            ->with('user')
            ->latest()
            ->paginate(12);

        return view('offres.disponibles', compact('offres'));
    }
    
    /**
     * Display a listing of available offers for candidates.
     * Alias for compatibility with route name
     */
    public function offreDisponibles()
    {
        return $this->offresDisponibles();
    }

    /**
     * Toggle the status of an offer.
     * Cette fonction reste disponible mais n'affectera que les offres déjà approuvées
     */
    public function toggleStatus(Offre $offre)
    {
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        // Vérifier si l'offre est approuvée
        if ($offre->approved !== true && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier le statut d\'une offre non approuvée.');
        }

        $offre->etat = !$offre->etat;
        $offre->save();

        return redirect()->back()
            ->with('success', 'Statut de l\'offre modifié avec succès.');
    }
}