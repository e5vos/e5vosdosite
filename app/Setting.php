<?php

namespace App;

use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    public function toggleSetting($id) {
        Setting::firstWhere('id',$id)->toggle('value');
    }
}
