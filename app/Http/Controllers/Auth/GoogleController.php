<?php

namespace App\Http\Controllers\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {

            $userData = Socialite::driver('google')->user();

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
        } catch (\Exception $e) {
            abort(400);
        }
    }

    public function tokenLogin(){
        return;
    }
}
