<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Help extends Model
{
    protected $table = 'helps' ;

    use HasFactory;


    public function getSos() {
        //
    }
}
