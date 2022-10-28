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
    Auth
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
     *
     */
    public function create(){
        return redirect()->route('team.index');
    }



    /**
     *
     */
    public function edit(Request $request, $teamCode){
        $team = Team::where('code',$teamCode)->firstOrFail();
        Gate::authorize('view',$team);
        if($request->user()->cannot('update',$team)){
            return view('e5n.teams.view',["team" => $team]);
        }else{
            return view('e5n.teams.edit',["team"=>$team]);
        }
    }

    /**
     * Display the specified team.
     */
    public function show($teamCode){
        return Team::findOrFail($teamCode);
    }

    /**
     *
     */
    public function destroy(Request $request, $teamCode){
        $team = Team::where('code',$teamCode)->firstOrFail();

        Gate::authorize('delete',$team);

        $team->removeMember($request->user());

        return redirect()->route('team.index');
    }


    /**
     *
     */
    public function store(Request $request){
        Gate::authorize('create',Team::class);
        $teamcodes = Team::pluck('code');
        do{
            $teamcode = Str::random(4);
        } while($teamcodes->contains($teamcode));


        $team = new Team();
        $team->code = $teamcode;
        $team->name = $request->input('name');
        $team->save();

        $team->addMember($request->user(),'manager');



        return redirect()->route('team.edit',$team->code);
    }

    /**
     *
     */
    public function invite(){
        return view('e5n.teams.invite');
    }

    /**
     *
     */
    public function delete(Request $request){
        return $this->destroy($request,$request->input("team"));
    }

    /**
     *
     */
    public function manageMember(Request $request, $teamCode){
        /** @var User $currentUser */
        $currentUser = Auth::user();
        $team = Team::where('code',$teamCode)->firstOrFail();

        if($request->input('new')) {
            $user = User::where('email',$request->input('email'))->firstOrFail();
            // Send email to user


            $team->addMember($user);
            return redirect()->route('team.edit',$teamCode);
        }

        /** @var TeamMembership $currentUsersMembership */
        $currentUsersMembership = $currentUser->teamMemberships()->whereBelongsTo($team)->firstOrFail(); // Authenticated user's membership

        /** @var TeamMembership $memberShip */
        $memberShip= User::findOrFail($request->input('target'))->teamMemberships()->whereBelongsTo($team)->firstOrFail(); // Membership to be promoted or demoted


        try{
            if($request->input('promote')) {
                $currentUsersMembership->promote($memberShip);
            }else if($request->input('demote') ) {
                    $currentUsersMembership->demote($memberShip);
            }
        }catch(NotAllowedException $e){
            return abort(403,$e->getMessage());
        }


        return redirect()->route('team.edit',$teamCode);
    }

}
