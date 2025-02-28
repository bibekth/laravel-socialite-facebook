<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login(){
        return Socialite::driver('facebook')->redirect();
    }

    public function redirect(){
        $user = Socialite::driver('facebook')->user();
        $newUser = User::firstOrCreate(
            ['email'=>$user->email],
            ['name'=>$user->name,'password'=>Hash::make(str_random(12))]
        );
        Auth::login($newUser, true);
        return redirect('/home');
    }

    public function deleteFbUser(){
        try {
            // Retrieve the Facebook user ID from the authenticated user
            $fbUser = Socialite::driver('facebook')->user();
            $user = User::where('facebook_id', $fbUser->id)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Delete the user record from the database
            $user->delete();

            return response()->json(['message' => 'User account deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        }
    }

    public function privacyPolicy(){
        return view('privacy-policy');
    }
}
