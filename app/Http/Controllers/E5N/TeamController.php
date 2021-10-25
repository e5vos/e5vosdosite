<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class TeamController extends Controller
{
    public function home(){
        return view('e5n.teams.home');
    }

    /**
     * Add or remove TeamMember
     */
    public function editMember($team,$member){
        Gate::authorize('edit-team',$team);
        if($team->member($member)){
            $team->remove($member);
        }
        else{
            $team->add($member);
        }
    }


    public function createTeam($name){
        Gate::authorize();
        do{
            $teamcode = Str::random(4);
        } while(!\App\Team::where('code',$teamcode)->exists());

        Auth::user()->teams()->create([
            'name'=>$name,
            'code'=>$teamcode,
        ]);

        return $teamcode;
    }
    public function loadTeam($team){
        Gate::authorize('edit-team',$team);
        return \App\Team::find($team)->toJson();
    }
}
