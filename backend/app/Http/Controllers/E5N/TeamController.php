<?php

namespace App\Http\Controllers\E5N;

use App\Exceptions\AlreadyInTeamException;
use App\Exceptions\NotAllowedException;
use App\Helpers\MembershipType;
use App\Http\Controllers\{
    Controller
};

use App\Models\{
    Team,
    TeamMember,
    TeamMembership,
    User
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate,
    Auth,
    Cache
};
use Illuminate\Support\Str;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

use function React\Promise\reduce;

class TeamController extends Controller
{

    /**
     * Display a listing of teams.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::rememberForever('e5n.teams.all', fn () => Team::all()->load('members'));
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
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        Cache::forever('e5n.teams'.$team->code, $team);
        return Cache::rememberForever('e5n.teams.'.$team->code, fn () => $team->load('members'));
    }



    /**
     * update a team
     */
    public function update(Request $request, $teamCode)
    {
        $team = Cache::pull('e5n.teams.'.$teamCode) ?? Team::findOrFail($teamCode);
        $team->update($request->all());
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        return Cache::rememberForever('e5n.teams.'.$team->code, $team->load('members'));
    }

    /**
     * Display the specified team.
     */
    public function show($teamCode)
    {
        return Cache::rememberForever('e5n.teams.'.$teamCode, fn () => Team::findOrFail($teamCode)->load('members'));
    }

    /**
     * Delete a team from the database
     */
    public function delete(Request $request, $teamCode){
        $team = Team::where('code', $teamCode)->firstOrFail();
        $team->delete();
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.'.$team->code);
        return response()->json(null, 204);
    }

    /**
     * restore a team from the database
     */
    public function restore(Request $request, $teamCode)
    {
        $team = Team::withTrashed()->where('code', $teamCode)->firstOrFail();
        $team->restore();
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.'.$team->code);
        return Cache::rememberForever('e5n.teams'.$team->code, fn () => $team->load('members'));
    }


    /**
     * invite team members
     */
    public function invite(Request $request, $teamCode)
    {
        $team = Cache::pull('e5n.teams.'.$teamCode, Team::findOrFail($teamCode)->load('members'));
        if ($team->members->pluck('e5code')->contains($request->userCode)) {
            throw new AlreadyInTeamException();
        }
        $team->members()->attach(User::where('e5code', $request->userCode)->firstOrFail(), ['role' => MembershipType::Invited]);
        Cache::forget('e5n.teams.all');
        return Cache::rememberForever('e5n.teams.'.$team->code, fn () => $team->load('members'));
    }

    /**
     * kick team members
     */
    public function kick(Request $request, $teamCode)
    {
        $team = Cache::pull('e5n.teams.'.$teamCode, Team::findOrFail($teamCode)->load('members'));
        $team->members()->detach(User::where('e5code', $request->userCode)->firstOrFail());
        Cache::forget('e5n.teams.all');
        return Cache::rememberForever('e5n.teams.'.$team->code, fn () => $team->load('members'));
    }

    /**
     * promote or demote team members
     */
    public function promote(Request $request, $teamCode)
    {
        $team = Cache::pull('e5n.teams.'.$teamCode, Team::findOrFail($teamCode)->load('members'));
        $user = User::where("e5code", $request->userCode)->firstOrFail();
        $role = TeamMembership::where('team_code', $team->code)->where('user_id', $user->id)->first()->role;
        if ($request->promote === 'promote') {
            $role = $role === MembershipType::Invited->value ? MembershipType::Member->value : MembershipType::Leader->value;
        } elseif ($request->promote === 'demote') {
            $role = MembershipType::Member->value;
        }
        $team->members()->updateExistingPivot($user, ['role' => $role]);
        Cache::forget('e5n.teams.all');
        return Cache::rememberForever('e5n.teams.'.$team->code, fn () => $team->load('members'));
    }
}
