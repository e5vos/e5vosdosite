<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\{
    Controller
};
use App\Models\{
    User
};

use Illuminate\Support\Facades\{
    Auth,
    Redirect
};


class UserController extends Controller
{

    public function index(){
        return view('user.index');
    }

    public function edit(){
        /**
         * @var User
         */
        $user = Auth::login();

        $user->save();
    }

    public function destroy(){
        $user = User::find(Auth::user()->id);
        Auth::logout();
        if ($user->delete()) {
            return Redirect::route('home');
       }

    }
}
