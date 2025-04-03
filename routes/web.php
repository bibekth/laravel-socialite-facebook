<?php

use App\Http\Controllers\HomeController;
use App\Livewire\DisplayPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (Auth::user()) {
        return redirect(route('dashboard'));
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/auth/facebook', [App\Http\Controllers\HomeController::class, 'loginFacebook'])->name('facebook.login');
    Route::get('/auth/facebook/callback', [App\Http\Controllers\HomeController::class, 'redirect'])->name('facebook.redirect');
    Route::get('/privacy-policy', [App\Http\Controllers\HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::post('/delete/facebook/callback', [App\Http\Controllers\HomeController::class, 'deleteFbUser'])->name('delete.fb.user');
    // Volt::route('register', 'auth.register')
    //     ->name('register');
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

Route::post('/github/webhooks', function () {
    try {
        $secret = "monkey@21";
        $payload = file_get_contents("php://input");
        // file_put_contents("webhook_request.log", $payload, FILE_APPEND);
        $signature = $_SERVER["HTTP_X_HUB_SIGNATURE_256"] ?? "";
        $hash = "sha256=" . hash_hmac("sha256", $payload, $secret);
        if (!hash_equals($hash, $signature)) {
            http_response_code(403);
            exit("Invalid Signature");
        }

        $data = json_decode($payload, true);
        if ($data["ref"] === "refs/heads/new") {
            exec("cd ~/public_html/lsf && git pull origin new 2>&1", $output, $returnCode);
            file_put_contents("webhook.log", implode('\n', $output), FILE_APPEND);
        }
        return response()->json('success', 200);
    } catch (Exception $e) {
        return response()->json($e->getMessage(), 500);
    }
});
// require __DIR__ . '/auth.php';
