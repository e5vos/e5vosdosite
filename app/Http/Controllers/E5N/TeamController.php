<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};

use App\Models\{
    Team,
    TeamMember
};

use Illuminate\Support\Facades\{
    Gate,
    Request
};

use Illuminate\Support\Str;


class TeamController extends Controller
{
    public function home(){
        return view('e5n.teams.home');
    }

    public function invite(){
        return view('e5n.teams.invite');
    }

    /**
     * Add or remove TeamMember
     */
    public function editMember($teamCode,$member){

        $team = Team::firstWhere('code',$teamCode);
        Gate::authorize('update',$team);

        if($team->member($member)){
            $team->remove($member);
        }
        else{
            $team->add($member);
        }
    }

    public function update($teamCode){
        $team = Team::firstWhere('code',$teamCode);
        Gate::authorize('update',$team);
        return view('e5n.teams.update');
    }

    public function createTeam(Request $request){
        Gate::authorize('create',Team::class);
        do{
            $teamcode = Str::random(4);
        } while(!Team::where('code',$teamcode)->exists());


        $team = new Team();

        $teamMember = new TeamMember();
        $teamMember->team()->associate($team);
        $teamMember->user()->associate($request->user());
        $teamMember->isManager=true;
        $teamMember->save();

        return $teamcode;
    }
    public function loadTeam($teamCode){
        $team= Team::firstWhere('code',$teamCode);
        Gate::authorize('show',$team);
        return Team::find($team)->toJson();
    }
}
