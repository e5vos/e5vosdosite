<?php

namespace App;

use Illuminate\Database\Eloquent\Teams;

class Team extends Model
{
    public function members(){
        return $this->hasMany(Team_Member::class);
    }
    public function admin(){
        return $this->belongsTo(Student::class);
    }

    public function sizeModifier(){
        return 1/$this->members()->count();
    }
}
