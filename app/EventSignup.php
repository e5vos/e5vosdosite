<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSignup extends Model
{
    use HasFactory;
    protected $table = 'event_signups';

    protected $hidden = ['created_at','updated_at'];

    public function toggle(){
        $this->present=!$this->present;
        $this->save();
    }
    public function event(){
        return $this->belongsTo(Presentation::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
