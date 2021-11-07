<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPoint extends Model
{
    use HasFactory;
    public function ejg_class(){
        return $this->belongsTo(EJGClass::class);
    }
}
