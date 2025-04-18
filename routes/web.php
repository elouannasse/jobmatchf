<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ManualAuthController;
use App\Http\Controllers\CandidatureController;

// -------- Auth routes --------

// Login
Route::get('/login', [ManualAuthController::class, 'showLoginForm'])->name('manual.login');
Route::post('/login', [ManualAuthController::class, 'login']);

// Register
Route::get('/register', [ManualAuthController::class, 'showRegisterForm'])->name('manual.register');
Route::post('/register', [ManualAuthController::class, 'register']);

// -------- Authenticated routes --------
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [ManualAuthController::class, 'logout'])->name('logout');

    // Dashboard (main page after login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// -------- Public routes --------

// Home page
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Search offres
Route::get('/home/search', [HomeController::class, 'search'])->name('home.search');

// List offres
Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');

// Show one offre
Route::get('/offre/{offre}', [OffreController::class, 'show'])->name('offre.show');
Route::middleware('auth')->group(function () {

    // Afficher formulaire de candidature
    Route::get('/offre/{offre}/candidature/create', [CandidatureController::class, 'create'])->name('candidature.create');

    // Enregistrer une candidature
    Route::post('/offre/{offre}/candidature', [CandidatureController::class, 'store'])->name('candidature.store');

    // Liste des candidatures (selon rôle)
    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidature.index');

    // Voir détails d'une candidature
    Route::get('/offre/{offre}/candidature/{candidature}', [CandidatureController::class, 'show'])->name('candidature.show');

    // Accepter une candidature
    Route::post('/candidature/{candidature}/accepter', [CandidatureController::class, 'accepter'])->name('candidature.accepter');

    // Rejeter une candidature
    Route::post('/candidature/{candidature}/rejeter', [CandidatureController::class, 'rejeter'])->name('candidature.rejeter');
});
