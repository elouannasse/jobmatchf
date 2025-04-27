<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\User;
use App\Models\TypeEntreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use App\Services\OffreService;

class OffreController extends Controller
{
    /**
     * @var OffreRepositoryInterface
     */
    private $offreRepository;

    /**
     * @var OffreService
     */
    private $offreService; 

    /**
     * Constructeur: vérifie l'authentification et le rôle de l'utilisateur et injecte les dépendances
     * 
     * @param OffreRepositoryInterface $offreRepository
     * @param OffreService $offreService
     */
    public function __construct(OffreRepositoryInterface $offreRepository, OffreService $offreService)
    {
        $this->offreRepository = $offreRepository;
        $this->offreService = $offreService;
        
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
        $offres = $this->offreRepository->getByUserPaginated(Auth::id(), 10);
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
    
        $this->offreService->createOffre($request->all());
    
        return redirect()->route('offres.index')
            ->with('success', 'Offre créée avec succès. Elle sera visible après approbation par un administrateur.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        // Check if the current user is the owner or the offer is active and approved
        if (Auth::id() !== $offre->user_id && (!$offre->etat || !$offre->approved) && !Auth::user()->isAdmin()) {
            abort(403, 'Cette offre n\'est pas disponible.');
        }

        return view('offres.show', compact('offre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        return view('offres.edit', compact('offre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
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

        $this->offreService->updateOffre($id, $request->all());

        return redirect()->route('offres.index')
            ->with('success', 'Offre mise à jour avec succès. Elle devra être à nouveau approuvée par un administrateur.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id) {
            // Allow admins to delete any offer
            if (Auth::user()->isAdmin()) {
                $this->offreService->deleteOffre($id);
                return redirect()->route('admin.offres')
                    ->with('success', 'Offre supprimée avec succès.');
            }
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette offre.');
        }

        $this->offreService->deleteOffre($id);

        return redirect()->route('offres.index')
            ->with('success', 'Offre supprimée avec succès.');
    }

    /**
     * Display a listing of offers posted by the current user.
     */
    public function mesOffres()
    {
        $offres = $this->offreRepository->getByUserPaginated(Auth::id(), 10);
        return view('offres.mes-offres', compact('offres'));
    }

    /**
     * Display a listing of available offers for candidates.
     * Affiche uniquement les offres actives et approuvées par un administrateur
     */
    public function offresDisponibles()
    {
        $offres = $this->offreRepository->getAllActivePaginated(12);
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
    public function toggleStatus(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        // Check if the current user is the owner
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        // Vérifier si l'offre est approuvée
        if ($offre->approved !== true && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier le statut d\'une offre non approuvée.');
        }

        $this->offreService->toggleOffreStatus($id);

        return redirect()->back()
            ->with('success', 'Statut de l\'offre modifié avec succès.');
    }
}