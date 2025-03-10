<?php

use App\Http\Controllers\HomeController;
use App\Livewire\DisplayPost;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/auth/facebook', [App\Http\Controllers\HomeController::class, 'loginFacebook'])->name('facebook.login');
    Route::get('/auth/facebook/callback', [App\Http\Controllers\HomeController::class, 'redirect'])->name('facebook.redirect');
    Route::get('/privacy-policy', [App\Http\Controllers\HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::post('/delete/facebook/callback', [App\Http\Controllers\HomeController::class, 'deleteFbUser'])->name('delete.fb.user');
    Volt::route('register', 'auth.register')
        ->name('register');
});
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::middleware(['auth'])->group(function () {
    Route::get('/{slug}', DisplayPost::class);
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// require __DIR__ . '/auth.php';
