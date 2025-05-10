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
    
    private $offreRepository;

        private $offreService; 

   
    public function __construct(OffreRepositoryInterface $offreRepository, OffreService $offreService)
    {
        $this->offreRepository = $offreRepository;
        $this->offreService = $offreService;
        
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $recruteurMethods = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'mesOffres', 'toggleStatus'];
            
            if (in_array($request->route()->getActionMethod(), $recruteurMethods) 
                && !$request->user()->isRecruteur() 
                && !$request->user()->isAdmin()) { 
                abort(403, ' pas autorisé à accéder à cette page .');
            }
            
            return $next($request);
        });
    }

   
    public function index()
    {
        $offres = $this->offreRepository->getByUserPaginated(Auth::id(), 10);
        return view('offres.index', compact('offres'));
    }

   
    public function create()
    {
        return view('offres.create');
    }

    
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

    
    public function show(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        if (Auth::id() !== $offre->user_id && (!$offre->etat || !$offre->approved) && !Auth::user()->isAdmin()) {
            abort(403, 'Cette offre n\'est pas disponible.');
        }

        return view('offres.show', compact('offre'));
    }

    
    public function edit(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        return view('offres.edit', compact('offre'));
    }

    
    public function update(Request $request, int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
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

    
    public function destroy(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        if (Auth::id() !== $offre->user_id) {
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

    
    public function mesOffres()
    {
        $offres = $this->offreRepository->getByUserPaginated(Auth::id(), 10);
        return view('offres.mes-offres', compact('offres'));
    }

    
    public function offresDisponibles()
    {
        $offres = $this->offreRepository->getAllActivePaginated(12);
        return view('offres.disponibles', compact('offres'));
    }
    
    
    public function offreDisponibles()
    {
        return $this->offresDisponibles();
    }

    
    public function toggleStatus(int $id)
    {
        $offre = $this->offreRepository->findById($id);
        
        if (Auth::id() !== $offre->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }

        if ($offre->approved !== true && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier le statut d\'une offre non approuvée.');
        }

        $this->offreService->toggleOffreStatus($id);

        return redirect()->back()
            ->with('success', 'Statut de l\'offre modifié avec succès.');
    }
}