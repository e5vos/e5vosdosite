<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Teams;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{

    use HasFactory;


    /**
     * The size of the team
     *
     * @return int the teams size
     */
    public function size(){
        return $this->members()->count();
    }


    /**
     * Size Score modifier
     *
     * @return int 1/size
     */
    public function sizeModifier(){
        return 1/$this->size();
    }

    /**
     * Returns whether $member is in team
     *
     * @param User $member
     * @return bool
     */
    public function isMember(User $member){
        return $this->members()->contains($member);
    }


    /**
     * Add member to the team
     *
     * @param  int $member New member's id
     * @return void
     */
    public function add(User $member){
        if(!$this->isMember($member)){
            $this->members()->create(['user'=>$member->id])->save();
        }
    }

    /**
     * Remove member from team, based on id
     *
     * @param  TeamMember $member
     * @return bool whether the team continues to exist
     */
    public function removeMemberShip(TeamMember $member){
        $member->delete();
        if($this->size()==0){
            $this->delete();
            return false;
        }
        else if($this->admins()->count()==0){
            $this->memberships()->update(['role'=>'manager']);
            return true;
        }
    }

    public function removeMember(User $user){
        return $this->removeMemberShip($user->teamMemberships()->firstWhere('team_id',$this->id));
    }

     /**
     * Members of this team
     */
    public function memberships(){
        return $this->hasMany(TeamMember::class);
    }

    public function members(){
        return $this->hasManyThrough(User::class, TeamMember::class, 'team_id','id','id','user_id');
    }
    /**
     * Scores of this team
     */
    public function scores(){
        return $this->hasMany(Score::class);
    }
    /**
     * Administator user for the team
     */
    public function admins(){
        return $this->members()->where('role',"manager");
    }

    /**
     *
     * @return TeamMember newly created membership
     */
    public function addMember(User $user, $role="member"){
        $teamMember = new TeamMember();
        $teamMember->team()->associate($this);
        $teamMember->user()->associate($user);
        $teamMember->role=$role;
        $teamMember->save();
        return $teamMember;
    }

}
