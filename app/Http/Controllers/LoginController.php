<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login(){
        return Socialite::driver('facebook')->redirect();
    }

    public function redirect(){
        $user = Socialite::driver('facebook')->user();
    }
}
