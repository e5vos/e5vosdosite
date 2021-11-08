<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\{
    Controller
};

use App\Models\{
    User
};

use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\{
    Hash,
    Auth,
    URL,
};


class AuthController extends Controller{

    public function redirect($provider='google')
    {
        session()->put('intended_url', url()->previous());

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider='google')
    {
        $userData = Socialite::driver($provider)->user();
        $user = User::firstWhere('email',$userData->email);

        if(!$user){
            $user = User::create([
                'name' => $userData->name,
                'email' => $userData->email,
                'google_id'=> Hash::make($userData->id)
            ]);
        }
        Auth::login($user);
        return redirect()->intended(session('intended_url'));
    }

    public function logout(){
        Auth::logout();
        return redirect()->route("index");
    }

    public function login(){
        return $this->redirect("google");
    }



}
