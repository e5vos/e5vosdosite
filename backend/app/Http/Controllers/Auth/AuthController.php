<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\{
    Controller
};
use App\Http\Resources\UserResourceCollection;
use App\Models\{
    User
};

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\{
    Hash,
    Auth,
};


class AuthController extends Controller{

    public function redirect($provider='google')
    {
        if (auth()->check()) {
            return response('Already logged in', 400);
        }
        //return Socialite::driver($provider)->stateless()->redirect();
        return ['url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl()];
    }

    public function callback(Request $request, $provider='google')
    {
        $userData = Socialite::driver($provider)->stateless()->user();
        $user = User::firstOrCreate(['email' => $userData->email]);
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->img_url = $userData->avatar;

        if(!$user->google_id){
            $user->google_id = Hash::make($userData->id);
        }

        if(!Hash::check($userData->id,$user->google_id)){
            abort(400);
        }
        $token = $user->createToken($request->header('User-Agent'), ['PERMISSIONs'])->plainTextToken;
        $user->save();
        Auth::login($user); //only for testing
        return view('oauth/callback', [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
        ]);
    }

    public function logout(Request $request){
        Auth::logout();

        return response();
    }
}
