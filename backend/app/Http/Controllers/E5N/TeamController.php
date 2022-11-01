<?php

namespace App\Http\Controllers\E5N;

use App\Exceptions\NotAllowedException;
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
use MembershipType;
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
        return Team::all();
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
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        return Cache::rememberForever('e5n.teams.'.$team->code, fn () => $team);
    }



    /**
     * update a team
     */
    public function update(Request $request, $teamCode)
    {
        $team = Team::findOrFail($teamCode);
        $team->update($request->all());
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
        Cache::put('e5n.teams.'.$team->code, $team);
        return Cache::get('e5n.teams.'.$team->code);
    }

    /**
     * Display the specified team.
     */
    public function show($teamCode){
        return Team::findOrFail($teamCode);
    }

    /**
     * Delete a team from the database
     */
    public function delete(Request $request, $teamCode){
        $team = Team::where('code', $teamCode)->firstOrFail();
        $team->delete();
        Cache::forget('e5n.teams.all');
        Cache::forget('e5n.teams.presentations');
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
        Cache::forget('e5n.teams.presentations');
        Cache::forget('e5n.teams.'.$team->code);
        return Cache::rememberForever('e5n.teams'.$team->code, fn () => $team);
    }

    /**
     * Display a listing of team members.
     * @return \Illuminate\Http\Response
     */
    public function members($teamCode = null)
    {
        $teamCode = $teamCode ?? $this->code ?? abort(400, 'No team code provided');
        return Cache::rememberForever('e5n.teams.'.$teamCode, fn() => Team::with('members')->where('teams.code', $teamCode)->firstOrFail());
    }


}
