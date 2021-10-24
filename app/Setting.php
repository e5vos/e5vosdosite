<?php

namespace App;

use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $primaryKey = 'key';
    protected $keyType = 'string';

    use HasFactory;


    /**
     * Returns setting value based on $key
     * @param  string $key
     * @return string value
     */
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

    /**
     * Set setting to $value
     *
     * @param  string $value
     * @return void
     */
    public function set($value){
        $this->value = $value;
        $this->save();

    }
}
