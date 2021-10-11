<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EJGClass extends Model
{
    use HasFactory;
    protected $table = 'ejg_classes';


    public function bonuspoints(){
        return $this->hasMany(BonusPoints::class);
    }

    public function students(){
        return $this->hasMany(Student::class);
    }

    /**
     * Calculate current point status
     *
     * @return void
     */
    public static function calculatePoints(){
        $ejgclasses = EJGClass::all();

        foreach($ejgclasses as $ejgclassid => &$ejgclass){
            $ejgclass->points = $ejgclass->bonuspoints()->where('event','E5N')->sum();
            foreach($ejgclass->students() as $studentid => &$student){
                foreach($student->scores() as $scoreid => &$score){
                    $ejgclass->points += $score->place*$score->event()->weight*Setting::firstWhere('key','e5nBasePoint')->get()->value();
                }
                foreach($student->teams() as $teamid => &$team){
                    foreach($team->scores() as $scoreid =>&$score){
                        $ejgclass->points += $score->place*$score->event()->weight*$team->sizeModifier()*Setting::firstWhere('key','e5nBasePoint')->get()->value();
                    }
                }
            }
            $ejgclass->save();
        }
    }
}
