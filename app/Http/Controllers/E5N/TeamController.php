<?php

namespace App\Http\Controllers\E5N;

use App\Exceptions\AlreadyInTeamException;
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
        $team = Team::create($request->all());
        $team->members()->attach(Auth::user()->id, ['role' => MembershipType::Leader]);
        $team = new TeamResource($team);
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => $team->jsonSerialize());
    }



    /**
     * update a team
     */
    public function update(Request $request, $teamCode)
    {
        $team = Cache::pull('e5n.teams.' . $teamCode) ?? Team::findOrFail($teamCode);
        $team->update($request->all());
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
        return Cache::rememberForever('e5n.teams' . $team->code, fn () => (new TeamResource($team->load('members')))->jsonSerialize());
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
        $team = Team::findOrFail($teamCode);
        $updatableRole = $team->members->where('e5code', request()->userId)->firstOrFail()->pivot->role;
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
                }
            case MembershipType::Invited->value:
                if ($request->promote) {
                    $updatableRole = MembershipType::Member->value;
                    break;
                } else {
                    $kick = true;
                }
            default:
                if ($request->promote) {
                    $updatableRole = MembershipType::Invited->value;
                    break;
                } else {
                    abort(409, "User is not in the team");
                }
        }
        if ($kick) {
            TeamMembership::where('team_code', $teamCode)->where('user_id', request()->userId)->delete();
        } else {
            TeamMembership::updateOrCreate(['team_code' => $teamCode, 'user_id' => request()->userId], ['role' => $updatableRole]);
        }
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $team->code);
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team->load('members')))->jsonSerialize());
    }
}
