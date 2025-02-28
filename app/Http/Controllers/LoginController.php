<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login(){
        return Socialite::driver('facebook')->redirect();
    }

    public function redirect(){
        $user = Socialite::driver('facebook')->user();
        dd($user);
    }

    public function deleteFbUser(){
        $user = Socialite::driver('facebook')->user();
        User::delete($user);
    }

    public function privacyPolicy(){
        return view('privacy-policy');
    }
}
