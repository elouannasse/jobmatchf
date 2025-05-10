<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    
    public function index()
    {
        $candidatures = Auth::user()->candidatures()->with('offre.user')->latest()->paginate(10);
        return view('candidatures.index', compact('candidatures'));
    }

    
    public function create()
    {
        $offres = Offre::where('etat', true)->get();
        return view('candidatures.create', compact('offres'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'lettre_motivation' => 'required|string|min:50',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $candidature = new Candidature();
        $candidature->user_id = Auth::id();
        $candidature->offre_id = $request->offre_id;
        $candidature->lettre_motivation = $request->lettre_motivation;
        $candidature->statut = 'en_attente';

        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
        }

        $candidature->save();

        return redirect()->route('candidatures.mes-candidatures')
            ->with('success', 'Votre candidature a été envoyée avec succès.');
    }

   
    public function show(Candidature $candidature)
    {
        if (Auth::id() !== $candidature->user_id && Auth::id() !== $candidature->offre->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir cette candidature.');
        }
        
        return view('candidatures.show', compact('candidature'));
    }

    
    public function postuler(Request $request, Offre $offre)
    {
        if (!Auth::user()->isCandidat()) {
            return redirect()->route('offres.disponibles')
                ->with('error', 'Seuls les candidats peuvent postuler aux offres. Si vous êtes administrateur ou recruteur, vous devez créer un compte candidat pour postuler.');
        }
        
        if (!$offre->etat || $offre->approved !== true) {
            abort(403, 'Cette offre n\'est pas disponible.');
        }
        
        if (Candidature::where('user_id', Auth::id())->where('offre_id', $offre->id)->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }
        
        $validated = $request->validate([
            'message' => 'required|string|min:50',
            'cv' => 'required|file|mimes:pdf|max:2048', 
        ]);
        
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }
        
        $candidature = new Candidature([
            'user_id' => Auth::id(),
            'offre_id' => $offre->id,
            'lettre_motivation' => $validated['message'], 
            'cv' => $cvPath, 
            'statut' => 'en_attente',
        ]);
        
        $candidature->save();
        
        return redirect()->route('candidatures.mes-candidatures')
            ->with('success', 'Votre candidature a été envoyée avec succès.');
    }

    
    public function mesCandidatures()
    {
        $candidatures = Auth::user()->candidatures()->with('offre.user')->latest()->paginate(10);
        return view('candidatures.mes-candidatures', compact('candidatures'));
    }

    
    public function candidaturesRecues()
    {
        if (!Auth::user()->isRecruteur()) {
            abort(403, 'Accès non autorisé.');
        }

        $candidatures = Candidature::whereHas('offre', function($query) {
            $query->where('user_id', Auth::id());
        })->with(['user', 'offre'])->latest()->paginate(10);

        return view('candidatures.candidatures-recues', compact('candidatures'));
    }

    
    public function updateStatus(Request $request, Candidature $candidature)
    {
        $this->authorize('update', $candidature);
        
        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        $candidature->statut = $request->statut;
        $candidature->save();

        return redirect()->back()
            ->with('success', 'Le statut de la candidature a été mis à jour.');
    }

   
    public function formulaire(Offre $offre)
    {
        if (!auth()->user()->isCandidat()) {
            return redirect()->route('offres.disponibles')
                ->with('error', 'Seuls les candidats peuvent postuler aux offres. Si vous êtes administrateur ou recruteur, vous devez créer un compte candidat pour postuler.');
        }
        
        if (!$offre->etat || $offre->approved !== true) {
            abort(404, 'Cette offre n\'est pas disponible.');
        }
        
        if (Candidature::where('user_id', auth()->id())->where('offre_id', $offre->id)->exists()) {
            return redirect()->route('candidatures.mes-candidatures')
                ->with('info', 'Vous avez déjà postulé à cette offre.');
        }
        
        return view('candidatures.formulaire', compact('offre'));
    }
    
   
    public function accepter(Candidature $candidature)
    {
        if (Auth::id() !== $candidature->offre->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }
        
        $candidature->statut = 'acceptee';
        $candidature->save();
        
        return redirect()->back()
            ->with('success', 'La candidature a été acceptée avec succès.');
    }

   
    public function refuser(Candidature $candidature)
    {
        if (Auth::id() !== $candidature->offre->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }
        
        $candidature->statut = 'refusee';
        $candidature->save();
        
        return redirect()->back()
            ->with('success', 'La candidature a été refusée.');
    }

   
    public function telechargerCV(Candidature $candidature)
    {
        if (Auth::id() !== $candidature->user_id && Auth::id() !== $candidature->offre->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à télécharger ce CV.');
        }
        
        if (!$candidature->cv) {
            return redirect()->back()->with('error', 'Aucun CV disponible pour cette candidature.');
        }
        
        $path = storage_path('app/public/' . $candidature->cv);
        
        if (!file_exists($path)) {
            $path = storage_path('app/' . $candidature->cv);
            
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'Le fichier CV n\'a pas été trouvé.');
            }
        }
        
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        $filename = 'CV_' . $candidature->user->name . '_' . date('Ymd') . '.' . $extension;
        
        $headers = [
            'Content-Type' => $extension == 'pdf' ? 'application/pdf' : 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->download($path, $filename, $headers);
    }
}