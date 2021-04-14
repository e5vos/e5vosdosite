<?php

namespace App;

use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';

    public static function lookUp($key){
        $setting = Setting::find($key);
        if($setting == null){
            return null;
        }else{
            return $setting->value;
        }
    }

    public static function check($key,$expected="1"){
        return Setting::lookUp($key) === $expected;
    }
}
