<?php

namespace App\Http\Controllers\Misc;

use App\Helpers\EjgClassType;
use App\Helpers\PermissionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::where('name', 'like', '%' . request()->q . '%')->select('id', 'name', 'ejg_class')->get());
        // return User::with(['userActivity', 'teamActivity'])->get()->map(function ($user) {
        //     $user->activity = $user->userActivity->concat($user->teamActivity);
        //     unset($user->userActivity);
        //     unset($user->teamActivity);
        //     return $user;
        // })->jsonSerialize();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function show(int $userId = null)
    {
        $userId ??= request()->userId;
        $loadables = array('teams');
        if (request()->user()->id == $userId || request()->user()->hasPermission(PermissionType::Teacher->value) || request()->user()->hasPermission(PermissionType::Admin->value)) {
            $loadables[] = 'userActivity';
            $loadables[] = 'teamActivity';
        }
        return User::find($userId)->with($loadables)->get()->map(function ($user) {
            $user->activity = $user->userActivity->concat($user->teamActivity);
            unset($user->userActivity);
            unset($user->teamActivity);
            return $user;
        })->jsonSerialize();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User  $user
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function update(int $userId = null)
    {
        $userId ??= request()->user->id;
        $user = User::find($userId);
        $user->update(request()->all());
        return (new UserResource($user))->jsonSerialize();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $userId = null)
    {
        $userId ??= request()->user->id;
        $user = User::find($userId);
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function restore(int $userId = null)
    {
        $userId ??= request()->user->id;
        $user = User::withTrashed()->find($userId);
        $user->restore();
        return (new UserResource($user->load('')))->jsonSerialize();
    }
}
