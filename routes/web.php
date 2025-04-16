<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification manuelle
// Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [App\Http\Controllers\Auth\ManualAuthController::class, 'showLoginForm'])
        ->name('manual.login');
    Route::post('/login', [App\Http\Controllers\Auth\ManualAuthController::class, 'login']);






    Route::get('/home', function () {
        return view('dashboard'); // Cette vue peut être modifiée selon vos besoins
    })->name('home');
    
    // Register
    Route::get('/register', [App\Http\Controllers\Auth\ManualAuthController::class, 'showRegisterForm'])
        ->name('manual.register');
    Route::post('/register', [App\Http\Controllers\Auth\ManualAuthController::class, 'register']);
    
    // Mot de passe oublié
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ManualAuthController::class, 'showForgotPasswordForm'])
        ->name('manual.password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ManualAuthController::class, 'sendResetLinkEmail'])
        ->name('manual.password.email');
// });

// Route de déconnexion (accessible uniquement aux utilisateurs authentifiés)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\ManualAuthController::class, 'logout'])
        ->name('manual.logout');
});