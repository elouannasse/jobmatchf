<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    // Afficher tous les offres
    public function index()
    {
        $offres = Offre::latest()->paginate(10);
        return view('offres.index', compact('offres'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('offres.create');
    }

    // Enregistrer une nouvelle offre
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'salaire_proposer' => 'required|numeric',
            'type_offre' => 'required|in:CDI,CDD,STAGE',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'nullable|string',
        ]);

        Offre::create($request->all());

        return redirect()->route('offres.index')->with('success', 'Offre créée avec succès.');
    }

    // Afficher une seule offre
    public function show(Offre $offre)
    {
        return view('offres.show', compact('offre'));
    }

    // Formulaire de modification
    public function edit(Offre $offre)
    {
        return view('offres.edit', compact('offre'));
    }

    // Mettre à jour une offre
    public function update(Request $request, Offre $offre)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'salaire_proposer' => 'required|numeric',
            'type_offre' => 'required|in:CDI,CDD,STAGE',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'nullable|string',
        ]);

        $offre->update($request->all());

        return redirect()->route('offres.index')->with('success', 'Offre mise à jour avec succès.');
    }

    // Supprimer une offre
    public function destroy(Offre $offre)
    {
        $offre->delete();
        return redirect()->route('offres.index')->with('success', 'Offre supprimée avec succès.');
    }
}
