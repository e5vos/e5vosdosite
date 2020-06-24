<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusPoints extends Model
{
    public function ejg_class(){
        return $this->belongsTo(EJGClass::class);
    }
}
