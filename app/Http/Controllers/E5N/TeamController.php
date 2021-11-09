<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};

use App\Models\{
    Team,
    TeamMember,
    User
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate,
};

use Illuminate\Support\Str;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

use function React\Promise\reduce;

class TeamController extends Controller
{
    public function index(){
        Gate::authorize('create',Team::class);
        return view('e5n.teams.index');
    }


    public function create(){
        return redirect()->route('team.index');
    }




    public function edit(Request $request, $teamCode){
        $team = Team::where('code',$teamCode)->firstOrFail();
        Gate::authorize('view',$team);
        if($request->user()->cannot('update',$team)){
            return view('e5n.teams.view',["team" => $team]);
        }else{
            return view('e5n.teams.edit',["team"=>$team]);
        }
    }


    public function show(Request $request, $teamCode){
        return $this->edit($request,$teamCode);
    }


    public function destroy(Request $request, $teamCode){
        $team = Team::where('code',$teamCode)->firstOrFail();

        Gate::authorize('delete',$team);

        $team->removeMember($request->user());

        return redirect()->route('team.index');
    }



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


    public function invite(){
        return view('e5n.teams.invite');
    }

    public function delete(Request $request){
        return $this->destroy($request,$request->input("team"));
    }

    public function manageMember(Request $request, $teamCode){
        $team = Team::where('code',$teamCode)->firstOrFail();
        if($request->input('leave')){
            $team->removeMember($request->user());
            return redirect()->route('team.index');
        }
        else if($request->input('kick')) {
            if(!$team->removeMember(User::findOrFail($request->input('kick')))) return redirect()->route('team.index');
        }
        else if($request->input('promote')) {
            $memberShip= $team->memberShips()->where('user_id',$request->input("promote"))->firstOrFail();

            switch($memberShip->role){
                case 'applicant':
                    $memberShip->role = "member";
                    break;
                case 'member':
                    $memberShip->role = "manager";
                    break;
                case 'manager':
                    break;
                default:
                    $memberShip->role = "applicant";
            }
            $memberShip->save();
        }
        else if($request->input('new')) {
            $user = User::where('email',$request->input('email'))->firstOrFail();
            $team->addMember($user);
        }
        return redirect()->route('team.edit',$teamCode);
    }

    public function leave(Request $request, $teamCode){
        $team = Team::where('code',$teamCode)->firstOrFail();
        $team->memberShips()->where('user_id',$request->user()->id)->delete();
        return redirect()->route('team.index');
    }

}
