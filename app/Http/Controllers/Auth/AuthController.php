<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\{
    Controller
};

use App\Models\{
    User
};

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\{
    Hash,
    Auth,
    URL,
};


class AuthController extends Controller{

    public function redirect(Request $request, $provider='google')
    {
        $request->session()->put('intended_url', url()->previous());

        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider='google')
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
        return redirect()->to($request->session()->get('intended_url',''));
    }

    public function logout(Request $request){
        Auth::logout();

        return redirect()->route("index");
    }

    public function login(Request $request){
        return $this->redirect($request,"google");
    }



}
