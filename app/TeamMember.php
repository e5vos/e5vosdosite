<?php

namespace App;

use Illuminate\Database\Eloquent\TeamMember;

class TeamMember extends Model
{
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function team(){
        return $this->belongsTo(Team::class);
    }
}
