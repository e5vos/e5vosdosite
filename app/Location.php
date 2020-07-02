<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function events(){
        return $this->hasMany(Event::class);
    }
}
