<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ManualAuthController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Gérer la demande de connexion
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/home'); // ✅ redirection vers la route /home
    }

    return back()->withErrors([
        'email' => 'Les informations d’identification ne sont pas correctes.',
    ])->onlyInput('email');
}


    /**
     * Afficher la page d'inscription
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Gérer la demande d'inscription
     */
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ], [
            'role_id.required' => "Le type d'utilisateur est requis.",
            'role_id.exists' => "Le type d'utilisateur sélectionné n'existe pas.",
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        Auth::login($user);

        return redirect(route('manual.login', absolute: false));
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
};

    