<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Teams;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{

    use HasFactory;

    /**
     * Members of this team
     */
    public function members(){
        return $this->hasMany(Team_Member::class);
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
     * Returns Member object from id
     *
     * @param  int $member Member's id
     * @return Member
     */
    public function member($member){
        return $this->members()->where('id',$member);
    }


    /**
     * Add member to the team
     *
     * @param  int $member New member's id
     * @return void
     */
    public function add($member){
        if(!$this->member($member)){
            $this->members()->create(['student_id'=>$member])->save();
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
}
