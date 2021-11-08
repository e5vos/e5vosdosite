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
        Gate::authorize('viewAny',User::class);
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

        if($request->user()->isAdmin()){

            foreach($user->permissions()->get() as $permission){
                if(!in_array($permission->permission,json_decode($request->input("permissions"),false))){
                    $permission->delete();
                }
            }

            foreach(json_decode($request->input("permissions"),false) as $permissionString){
                if(!$user->permissions()->where('permission',$permissionString)->exists()){
                    $user->permissions()->create(['permission' => $permissionString]);
                }
            }
        }


        return back();
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
