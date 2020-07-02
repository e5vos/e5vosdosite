<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    public function editMember($team,$member){
        Gate::authorize('edit-team',$team);
        if($team->member($member)) $team->remove($member);
        else $team->add($member);
    }


    public function createTeam($name){
        Gate::authorize();

        do{
            $teamcode = str_random(4);
        } while(!Team::where('code',$teamcode)->exists());

        Auth::user()->teams()->create([
            'name'=>$name,
            'code'=>$teamcode,
        ]);

        return $teamcode;
    }
    public function loadTeam($team){
        Gate::authorize('edit-team',$team);
        return Team::find($team)->toJson();
    }
}
