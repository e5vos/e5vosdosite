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
        return Cache::rememberForever('e5n.teams.all', fn () => TeamResource::collection(Team::all()->load('members'))->jsonSerialize());
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
        $team = new TeamResource($team->load('members'));
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
        $team = new TeamResource($team->load('members'));
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team->load('members')))->jsonSerialize());
    }

    /**
     * Display the specified team.
     */
    public function show($teamCode)
    {
        return Cache::rememberForever('e5n.teams.' . $teamCode, fn () => (new TeamResource(Team::findOrFail($teamCode)->load('members')))->jsonSerialize());
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
        return response()->json(null, 204);
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
     * invite team members
     */
    public function invite(Request $request, $teamCode)
    {
        $team = Team::findOrFail($teamCode)->load('members');
        if ($team->members->pluck('e5code')->contains($request->userCode)) {
            throw new AlreadyInTeamException();
        }
        $team->members()->attach(User::where('e5code', $request->userCode)->firstOrFail(), ['role' => MembershipType::Invited]);
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $team->code);
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team->load('members')))->jsonSerialize());
    }

    /**
     * kick team members
     */
    public function kick(Request $request, $teamCode)
    {
        $team = Team::findOrFail($teamCode)->load('members');
        $team->members()->detach(User::where('e5code', $request->userCode)->firstOrFail());
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $team->code);
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team->load('members')))->jsonSerialize());
    }

    /**
     * promote or demote team members
     */
    public function promote(Request $request, $teamCode)
    {
        $team = Team::findOrFail($teamCode)->load('members');
        $user = User::where("e5code", $request->userCode)->firstOrFail();
        $role = TeamMembership::where('team_code', $team->code)->where('user_id', $user->id)->first()->role;
        if ($request->promote === 'promote') {
            $role = $role === MembershipType::Invited->value ? MembershipType::Member->value : MembershipType::Leader->value;
        } elseif ($request->promote === 'demote') {
            $role = MembershipType::Member->value;
        }
        $team->members()->updateExistingPivot($user, ['role' => $role]);
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.' . $team->code);
        return Cache::rememberForever('e5n.teams.' . $team->code, fn () => (new TeamResource($team->load('members')))->jsonSerialize());
    }
}
