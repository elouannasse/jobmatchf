<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));

    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tel' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }
}
