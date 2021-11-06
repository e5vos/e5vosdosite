<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TeamMember extends Model
{

    use HasFactory;

    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function team(){
        return $this->belongsTo(Team::class);
    }
}
