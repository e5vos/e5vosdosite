<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;


class AuthController extends Controller{

    public function redirect($provider)
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback($provider)
    {
        $userData = Socialite::driver($provider)->user();
        $user = \App\User::where('google_id', Hash::make($userData->id))->exists();

        if(!$user){
            $user = \App\User::create([
                'name' => $userData->name,
                'email' => $userData->email,
                'google_id'=> Hash::make($userData->id)
            ]);
        }
        Auth::login($user);
        return redirect('./');
    }

    public function logout(){
        Auth::logout();
        return back();
    }

    public function login(){
        redirect('auth/google');
    }
}
