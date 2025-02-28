<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/facebook-login',[App\Http\Controllers\LoginController::class, 'login'])->name('facebook.login');
Route::post('/facebook-login',[App\Http\Controllers\LoginController::class, 'redirect'])->name('facebook.redirect');
Route::get('/privacy-policy',[App\Http\Controllers\LoginController::class, 'privacyPolicy'])->name('privacy.policy');
