<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;
    protected $table = 'students';

    protected $hidden = ['created_at','updated_at','laravel_through_key'];

    public static function updatedatabase(){
        Student::query()->truncate();
        EJGClass::query()->truncate();


        $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); // needed to accept invalid ssl certificates

        $students = json_decode(file_get_contents("http://ejg.bimun.hu/ExecuteAPI.php?action=diakkodlista",false,stream_context_create($arrContextOptions)),true);

        foreach($students as $classname => &$class){
            $ejgclass = new EJGClass;
            $ejgclass->name=$classname;
            $ejgclass->save();
            foreach($class as &$studentcode){
                    $student = new Student;
                    $student->code=$studentcode;
                    $student->class_id=$ejgclass->id;
                    $student->save();
            }
        }
    }

    public function ejg_class(){
        return $this->belongsTo(EJGClass::class);
    }

    public function signups(){
        return $this->hasMany(PresentationSignup::class);
    }

    public function presentations(){
        return $this->hasManyThrough(Presentation::class,PresentationSignup::class);
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }

    public function omkodcheck($omkod){
        $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); // needed to accept invalid ssl certificates
        return file_get_contents("http://ejg.bimun.hu/ExecuteAPI.php?action=dcredcheck&dkod=".strtoupper($this->student_code)."&omkod=".$omkod ,false,stream_context_create($arrContextOptions))==1;
    }




}
