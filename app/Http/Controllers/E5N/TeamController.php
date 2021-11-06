<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
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

        $team = \App\Team::firstWhere('code',$teamCode);
        Gate::authorize('update',$team);

        if($team->member($member)){
            $team->remove($member);
        }
        else{
            $team->add($member);
        }
    }

    public function update($teamCode){
        $team = \App\Team::firstWhere('code',$teamCode);
        Gate::authorize('update',$team);
        return view('e5n.teams.update');
    }

    public function createTeam(Request $request){
        Gate::authorize('create',Team::class);
        do{
            $teamcode = Str::random(4);
        } while(!\App\Team::where('code',$teamcode)->exists());

        $request->user->teams()->create([
            'name'=>$name,
            'code'=>$teamcode,
        ]);

        return $teamcode;
    }
    public function loadTeam($teamCode){
        $team= \App\Team::firstWhere('code',$teamCode);
        Gate::authorize('show',$team);
        return \App\Team::find($team)->toJson();
    }
}
