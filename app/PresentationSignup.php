<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresentationSignup extends Model
{
    use HasFactory;
    protected $table = 'presentation_signups';

    protected $hidden = ['created_at','updated_at'];

    use HasFactory;

    public function toggle(){
        $this->present=!$this->present;
        $this->save();
    }
    public function presentation(){
        return $this->belongsTo(Presentation::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
