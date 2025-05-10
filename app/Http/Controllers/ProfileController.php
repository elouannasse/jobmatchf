<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeEntreprise;

class ProfileController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

   
    public function edit()
    {
        $user = Auth::user();
        $typesEntreprise = TypeEntreprise::all();
        
        return view('profile.edit', compact('user', 'typesEntreprise'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ];
        
        if ($user->isRecruteur()) {
            $rules['nom_entreprise'] = 'required|string|max:255';
            $rules['description_entreprise'] = 'nullable|string';
            $rules['type_entreprise_id'] = 'required|exists:type_entreprises,id';
        }
        
        $request->validate($rules);
        
        $user->name = $request->name;
        $user->prenom = $request->prenom;
        $user->tel = $request->tel;
        $user->adresse = $request->adresse;
        
        if ($user->isRecruteur()) {
            $user->nom_entreprise = $request->nom_entreprise;
            $user->description_entreprise = $request->description_entreprise;
            $user->type_entreprise_id = $request->type_entreprise_id;
        }
        
        $user->save();
        
        return redirect()->route('profile.show')
            ->with('success', 'succe');
    }
}