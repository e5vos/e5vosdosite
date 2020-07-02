<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function teamMembers(){
        return $this->hasManyThrough(App\Student::class,App\Team::class);
    }
}
