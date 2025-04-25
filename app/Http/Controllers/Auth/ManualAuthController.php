<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\TypeEntreprise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManualAuthController extends Controller
{
    /**
     *    
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Chercher l'utilisateur manuellement
        $user = User::where('email', $request->email)->first();
        
        // Si l'utilisateur n'existe pas
        if (!$user) {
            return back()->withErrors([
                'email' => 'Utilisateur introuvable avec cet email.',
            ]);
        }
        
        // Vérifier le mot de passe manuellement
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Mot de passe incorrect.',
            ]);
        }
        
        // Si tout est bon, connecter l'utilisateur manuellement
        Auth::login($user);
        $request->session()->regenerate();
        
        return redirect()->route('home');
    }
    /**
     */
    public function showRegisterForm()
    {
        $roles = Role::whereIn('name', [
            'Candidat', 
            'Recruteur'
        ])->get();
        
        $typesEntreprise = TypeEntreprise::all();
        
        return view('auth.register', compact('roles', 'typesEntreprise'));
    }

    /**
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'role_id.required' => "Le type d'utilisateur est requis",
            'role_id.exists' => "Le type d'utilisateur n'existe pas",
        ]);

        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    /**
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}