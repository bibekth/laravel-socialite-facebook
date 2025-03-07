<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function login()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function logout()
    {
        $user = Auth::user();
        Auth::logout($user);
        return redirect('/');
    }

    public function loginFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function redirect()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            dd([$user, $user->name, $user->email, $user->avatar, $user->token, $user->refresh_token, $user->expires_in]);
            $newUser = User::updateOrCreate(
                ['facebook_id' => $user->getId()],
                ['name' => $user->name, 'email' => $user->email, 'avatar_url' => $user->avatar, 'token' => $user->token, 'refresh_token' => $user->refresh_token, 'expires_in' => $user->expires_in]
            );
            dd($newUser);

            // Auth::login($newUser, true);
            return redirect('/home');
        } catch (Exception $e) {
            Log::error('Facebook Redirect: ' . $e->getMessage());
            return redirect(route('home'));
        }
    }

    public function deleteFbUser()
    {
        try {
            // Retrieve the Facebook user ID from the authenticated user
            $fbUser = Socialite::driver('facebook')->user();
            $user = User::where('facebook_id', $fbUser->id)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Delete the user record from the database
            $user->delete();
            Auth::logout($user);
            return response()->json(['message' => 'User account deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        }
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
}
