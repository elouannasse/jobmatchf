<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ManualAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ManualAuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');

    Route::resource('offres', OffreController::class);
    Route::get('/mes-offres', [OffreController::class, 'mesOffres'])->name('offres.mes-offres');
    Route::get('/candidatures-recues', [CandidatureController::class, 'candidaturesRecues'])->name('candidatures.recues');
    Route::put('/candidatures/{candidature}/update-status', [CandidatureController::class, 'updateStatus'])
        ->name('candidatures.updateStatus');
    Route::put('/offres/{offre}/toggle-status', [OffreController::class, 'toggleStatus'])
        ->name('offres.toggle-status');

    Route::get('/offres-disponibles', [OffreController::class, 'offreDisponibles'])->name('offres.disponibles');
    Route::post('/offres/{offre}/postuler', [CandidatureController::class, 'postuler'])->name('offres.postuler');
    Route::get('/mes-candidatures', [CandidatureController::class, 'mesCandidatures'])->name('candidatures.mes-candidatures');
    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/offres/{offre}/formulaire', [CandidatureController::class, 'formulaire'])->name('offres.formulaire');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/recruteurs', [AdminController::class, 'recruteurs'])->name('recruteurs');
        Route::get('/candidats', [AdminController::class, 'candidats'])->name('candidats');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        
        // Correction: utiliser un seul nom de route pour la suppression d'utilisateur
        Route::delete('/users/{userId}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
        Route::get('/offres', [AdminController::class, 'offres'])->name('offres');
        Route::get('/candidatures', [AdminController::class, 'candidatures'])->name('candidatures');
        Route::get('/users', [AdminController::class, 'users'])->name('users');

        Route::put('/offres/{offreId}/approve', [AdminController::class, 'approveOffre'])->name('offres.approve');
        Route::put('/offres/{offreId}/reject', [AdminController::class, 'rejectOffre'])->name('offres.reject');
        Route::put('/offres/{offreId}/toggle-status', [AdminController::class, 'toggleOffreStatus'])->name('offres.toggle-status');
        
        // Modifié pour utiliser la méthode existante deleteCandidature au lieu de destroyCandidature
        Route::delete('/candidatures/{candidatureId}', [AdminController::class, 'deleteCandidature'])->name('candidatures.destroy');
        
        Route::get('/candidats/create', [AdminController::class, 'createCandidate'])->name('candidats.create');
        Route::post('/candidats', [AdminController::class, 'storeCandidate'])->name('candidats.store');
    });

    Route::post('/offres/{offre}/postuler', [CandidatureController::class, 'postuler'])->name('offres.postuler');
    Route::get('/offres/{offre}/formulaire', [CandidatureController::class, 'formulaire'])->name('offres.formulaire');

    Route::put('candidatures/{candidature}/accepter', [CandidatureController::class, 'accepter'])
        ->name('candidatures.accepter');

    Route::put('candidatures/{candidature}/refuser', [CandidatureController::class, 'refuser'])
        ->name('candidatures.refuser');

    Route::get('candidatures/{candidature}/telecharger-cv', [CandidatureController::class, 'telechargerCV'])
        ->name('candidatures.telecharger-cv');

    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    });

    Route::get('/notifications/count', [NotificationController::class, 'getCount'])->name('notifications.count');
    Route::get('/notifications/dropdown', [NotificationController::class, 'getDropdownContent'])->name('notifications.dropdown');
});