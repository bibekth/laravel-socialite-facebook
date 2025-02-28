<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/auth/facebook',[App\Http\Controllers\LoginController::class, 'login'])->name('facebook.login');
Route::get('/auth/facebook/callback',[App\Http\Controllers\LoginController::class, 'redirect'])->name('facebook.redirect');
Route::get('/privacy-policy',[App\Http\Controllers\LoginController::class, 'privacyPolicy'])->name('privacy.policy');
Route::post('/delete/facebook/callback',[App\Http\Controllers\LoginController::class, 'deleteFbUser'])->name('delete.fb.user');
Route::get('/users', function(){
    $users = User::all();
    return response()->json($users);
});
