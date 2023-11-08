<?php

namespace App\Http\Controllers\Misc;

use App\Helpers\PermissionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\TeamResource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->q == '-1') {
            return response('[]', 200);
        }
        return response()->json(UserResource::collection(User::where('name', 'like', '%' . request()->q . '%')->select('id', 'name', 'ejg_class')->get()));
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
        $userId ??= request()->user()->id;
        $loadables = array('teams');
        if (request()->user()->id == $userId || request()->user()->hasPermission(PermissionType::Teacher->value) || request()->user()->hasPermission(PermissionType::Admin->value || request()->user()->hasPermission(PermissionType::Operator->value))) {
            $loadables[] = 'userActivity';
            $loadables[] = 'teamActivity';
            $loadables[] = 'permissions';
        }
        $user = User::with($loadables)->findOrFail($userId);
        $user->activity = $user->userActivity?->concat($user->teamActivity);
        unset($user->userActivity);
        unset($user->teamActivity);
        return response()->json($user, 200);
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
        foreach (request()->all() as $key => $value) {
            $user->$key = $value;
        }
        $user->save();
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
        $user = User::findOrFail($userId);
        $user->delete();
        return response()->noContent();
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
        return response((new UserResource($user))->jsonSerialize(), 200);
    }

    public function myTeams()
    {
        return Cache::remember("user." . request()->user()->id . '.teams', 60, function () {
            return (TeamResource::collection(request()->user()->teams()->with('members', 'activity')->get()))->jsonSerialize();
        });
    }
}
