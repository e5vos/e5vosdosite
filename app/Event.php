<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    public function permissions(){
        return $this->hasMany(Permission::class);
    }
    public function ongoing(){
        return $this->start < now() && now() < $this->end;
    }
}
