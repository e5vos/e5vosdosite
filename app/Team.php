<?php

namespace App;

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
     * @param \App\User $member
     * @return bool
     */
    public function isMember(\App\User $member){
        return $this->members()->contains($member);
    }


    /**
     * Add member to the team
     *
     * @param  int $member New member's id
     * @return void
     */
    public function add(\App\User $member){
        if(!$this->isMember($member)){
            $this->members()->create(['user'=>$member->id])->save();
        }
    }

    /**
     * Remove member from team, based on id
     *
     * @param  int $member
     */
    public function remove($member){
        $this->member($member)->delete();
    }

     /**
     * Members of this team
     */
    public function memberships(){
        return $this->hasMany(Team_Member::class);
    }

    public function members(){
        return $this->hasManyThrough(User::class, Team_Member::class, 'team_id','id','id','user_id');
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
    public function admin(){
        return $this->belongsTo(Student::class);
    }
}
