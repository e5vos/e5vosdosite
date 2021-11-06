<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class UserController extends Controller
{

    public function index(){
        return view('user.index');
    }

    public function edit(){
        $user = Auth::login();

        $user->save();
    }

    public function destroy(){
        $user = \App\User::find(Auth::user()->id);
        Auth::logout();
        if ($user->delete()) {
            return Redirect::route('home');
       }

    }
}
