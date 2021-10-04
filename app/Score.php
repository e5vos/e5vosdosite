<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EJGClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Score extends Model
{
    use HasFactory;
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
