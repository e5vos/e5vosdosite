<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\{
    Controller
};
use App\Models\{
    User,
    EJGClass
};

use Illuminate\Http\Request;


use Illuminate\Support\Facades\{
    Auth,
    Redirect,
    Gate
};


class UserController extends Controller
{

    public function index(){
        return redirect()->route('index');
        return view('user.index');
    }

    public function edit(Request $request, $userID){
        $user = User::findOrFail($userID);
        Gate::authorize('update',$user);

        return view('user.edit',["user"=>$user,"classes"=>EJGClass::all()]);
    }

    public function update(Request $request, $userID){
        $user = User::findOrFail($userID);
        Gate::authorize('update',$user);

        if($user->ejgClass==null){
            $class = EJGClass::findOrFail($request->input('ejgClass'));
            $user->ejgClass()->associate($class);
            $user->save();
        }

        return redirect()->route('index');
    }

    public function destroy(Request $request, $userID){
        $user = User::findOrFail($userID);
        Gate::authorize('delete',$user);
        if($user->is($request->user())){
            Auth::logout();
            if ($user->delete()) {
                return redirect()->route('index');
           }
        }
        else{
            $user->delete();
            return back();
        }

    }
}
