<?php

namespace App;

use Illuminate\Database\Eloquent\Team_Member;

class Team_Member extends Model
{
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function team(){
        return $this->belongsTo(Team::class);
    }
}
