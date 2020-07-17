<?php

namespace App;


use Illuminate\Database\Eloquent\Teams;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function members(){
        return $this->hasMany(Team_Member::class);
    }
    public function scores(){
        return $this->hasMany(Score::class);
    }
    public function admin(){
        return $this->belongsTo(Student::class);
    }
    public function size(){
        return $this->members()->count();
    }
    public function sizeModifier(){
        return 1/$this->size();
    }
    public function member($member){
        return $this->members()->where('id',$member);
    }
    public function add($member){
        if(!$this->member($member)) $this->members()->create(['student_id'=>$member])->save();
    }
    public function remove($member){
        $this->member($member)->delete();
    }
}
