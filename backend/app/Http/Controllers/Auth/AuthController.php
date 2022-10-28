<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\{
    Controller
};
use App\Http\Resources\UserResourceCollection;
use App\Models\{
    Permission,
    User
};

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\{
    Hash,
    Auth,
    Http,
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
        $user = User::firstWhere('email', $userData->email);
        if (!isset($user)) {
            $user = User::create([
                'name' => $userData->name,
                'email' => $userData->email,
                'img_url' => $userData->avatar,
            ]);
            Permission::create([
                'user_id' => $user->id,
                'code' => 'STD',
            ]);
        }
        if (!$user->google_id) {
            $user->google_id = Hash::make($userData->id);
        }

        if (!Hash::check($userData->id, $user->google_id)) {
            abort(400);
        }
        $user->save();
        Auth::login($user);
        $token = $user->createToken($request->header('User-Agent'), ['JOIN THE USSR'])->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => new UserResourceCollection($user)
        ]);
    }

    public function logout(Request $request){
        Auth::logout();

        return response();
    }

    /**
     * validate with pál ádám
     */
    public function setE5code(Request $request){
        $validated = Http::post('https://e5vos.dev/api/student/verify', [
            'email' => $request->user()->email,
            'studentId' => $request->user()->e5code,
            'api_token' => env('E5VOS_API_TOKEN')
        ]);
        if ($validated->body() === "true") {
            $request->user()->e5code = $request->e5code;
            $request->user()->save();
            return response()->json([
                'message' => 'E5 code set'
            ]);
        } else {
            return response()->json([
                'message' => 'invalid e5code'
            ], 400);
        }
    }
}
