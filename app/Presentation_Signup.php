<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pressignup extends Model
{
    protected $table = 'presentation_signups';

    public function toggleJelen(){
        $this->jelen=!$this->jelen;
        $this->save();
    }
}
