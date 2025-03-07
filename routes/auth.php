<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Volt::route('login', 'auth.login')
    //     ->name('login');
    Route::get('/login', [HomeController::class, 'login'])->name('login');
    Route::get('/auth/facebook', [App\Http\Controllers\HomeController::class, 'loginFacebook'])->name('facebook.login');
    Route::get('/auth/facebook/callback', [App\Http\Controllers\HomeController::class, 'redirect'])->name('facebook.redirect');
    Route::get('/privacy-policy', [App\Http\Controllers\HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::post('/delete/facebook/callback', [App\Http\Controllers\HomeController::class, 'deleteFbUser'])->name('delete.fb.user');


    // Volt::route('register', 'auth.register')
    //     ->name('register');

    // Volt::route('forgot-password', 'auth.forgot-password')
    //     ->name('password.request');

    // Volt::route('reset-password/{token}', 'auth.reset-password')
    //     ->name('password.reset');
});

// Route::middleware('auth')->group(function () {
//     Volt::route('verify-email', 'auth.verify-email')
//         ->name('verification.notice');

//     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');

//     Volt::route('confirm-password', 'auth.confirm-password')
//         ->name('password.confirm');
// });

// Route::post('logout', App\Livewire\Actions\Logout::class)
//     ->name('logout');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
