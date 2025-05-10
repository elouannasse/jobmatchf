<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Auth\ManualAuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [ManualAuthController::class, 'showLoginForm'])->name('manual.login');
    Route::post('/login', [ManualAuthController::class, 'login']);
    
    Route::get('/register', [ManualAuthController::class, 'showRegisterForm'])->name('manual.register');
    Route::post('/register', [ManualAuthController::class, 'register']);
    
   
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [ManualAuthController::class, 'logout'])->name('manual.logout');
});




Route::get('/home/search', [HomeController::class, 'search'])->name('home.search'); 

