<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ManualAuthController;

// Routes d'authentification manuelle
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [ManualAuthController::class, 'showLoginForm'])->name('manual.login');
    Route::post('/login', [ManualAuthController::class, 'login']);
    
    // Register
    Route::get('/register', [ManualAuthController::class, 'showRegisterForm'])->name('manual.register');
    Route::post('/register', [ManualAuthController::class, 'register']);
    
    // Mot de passe oublié
    Route::get('/forgot-password', [ManualAuthController::class, 'showForgotPasswordForm'])->name('manual.password.request');
    Route::post('/forgot-password', [ManualAuthController::class, 'sendResetLinkEmail'])->name('manual.password.email');
});

// Route de déconnexion (accessible uniquement aux utilisateurs authentifiés)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [ManualAuthController::class, 'logout'])->name('manual.logout');
});




// Search route
Route::get('/home/search', [HomeController::class, 'search'])->name('home.search'); 

