<?php

namespace App\Http\Controllers\E5N;

use App\Exceptions\NotAllowedException;
use App\Helpers\MembershipType;
use App\Http\Controllers\{
    Controller
};
use App\Http\Resources\TeamResource;
use App\Models\{
    Team,
    TeamMembership,
    User
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Auth,
    Cache
};

class TeamController extends Controller
{

    /**
     * Display a listing of teams.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::rememberForever('e5n.teams.all', fn () => TeamResource::collection(Team::all())->jsonSerialize());
    }

    /**
     * create a team
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //check for existing team
        if (Team::where('code', $request->code)->exists()) {
            abort(409, "Team already exists");
        }
        $team = Team::create($request->all());
        $team->members()->attach(Auth::user()->id, ['role' => MembershipType::Leader]);
        $team = new TeamResource($team);
        Cache::forget('e5n.teams.all');
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => new TeamResource($team->load('members', 'activity')));
    }



    /**
     * update a team
     */
    public function update(Request $request, $teamCode)
    {
        $team = Cache::pull('e5n.teams.' . $teamCode) ?? Team::findOrFail($teamCode);
        if (Cache::get('e5n.teams.' . $request->code)?->exists ?? Team::find($request->code)->exists) {
            abort(409, "Team already exists");
        }
        foreach ($request->all() as $key => $value) {
            $team->$key = $value;
        }
        $team->save();
        $team = new TeamResource($team);
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team))->jsonSerialize());
    }

    /**
     * Display the specified team.
     */
    public function show($teamCode)
    {
        return Cache::rememberForever('e5n.teams.' . $teamCode, fn () => (new TeamResource(Team::findOrFail($teamCode)->load('members', 'activity')))->jsonSerialize());
    }

    /**
     * Delete a team from the database
     */
    public function delete($teamCode)
    {
        $team = Team::where('code', $teamCode)->firstOrFail();
        $team->delete();
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $teamCode);
        return response()->noContent();
    }

    /**
     * restore a team from the database
     */
    public function restore($teamCode)
    {
        $team = Team::withTrashed()->where('code', $teamCode)->firstOrFail();
        $team->restore();
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $team->code);
        return Cache::rememberForever('e5n.teams' . $team->code, fn () => (new TeamResource($team->load('members', 'activity')))->jsonSerialize());
    }


    /**
     * Promote, demote, kick or invite a user to a team
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamCode
     * @return \Illuminate\Http\Response
     */
    public function promote(Request $request, $teamCode)
    {
        $team = Team::findOrFail($teamCode)->load('members');
        $updatableRole = $team->members->where('id', request()->userId)->first()?->pivot->role;
        $kick = false;
        switch ($updatableRole) {
            case MembershipType::Leader->value:
                if ($request->promote) {
                    throw new NotAllowedException();
                } else {
                    $updatableRole = MembershipType::Member->value;
                    break;
                }
            case MembershipType::Member->value:
                if ($request->promote) {
                    $updatableRole = MembershipType::Leader->value;
                    break;
                } else {
                    $kick = true;
                    break;
                }
            case MembershipType::Invited->value:
                if ($request->promote) {
                    $updatableRole = MembershipType::Member->value;
                    break;
                } else {
                    $kick = true;
                    break;
                }
            default:
                if ($request->promote) {
                    $updatableRole = MembershipType::Invited->value;
                    break;
                } else {
                    abort(400, "User is not in the team");
                    break;
                }
        }
        if ($kick) {
            TeamMembership::where('team_code', $teamCode)->where('user_id', request()->userId)->delete();
        } else {
            $membership = TeamMembership::where('team_code', $teamCode)->where('user_id', request()->userId)->first();
            if ($membership) {
                $membership->role = $updatableRole;
                $membership->save();
            } else {
                $membership = new TeamMembership();
                $membership->team_code = $teamCode;
                $membership->user_id = request()->userId;
                $membership->role = $updatableRole;
                $membership->save();

                // TeamMembership::create(['team_code' => $teamCode, 'user_id' => request()->userId, 'role' => $updatableRole]);
            }
        }
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $team->code);
        foreach ($team->members as $member) {
            Cache::forget($member->id . 'teams');
        }
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team->load('members', 'activity')))->jsonSerialize());
    }
}
