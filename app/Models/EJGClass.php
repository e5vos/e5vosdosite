<?php

namespace App\Models;

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

        foreach($ejgclasses as $ejgclass){
            $ejgclass->points = $ejgclass->bonuspoints()->where('event','E5N')->sum();
            foreach($ejgclass->students() as $student){
                foreach($student->scores() as $score){
                    $ejgclass->points += $score->place*$score->event()->weight*Setting::firstWhere('key','e5nBasePoint')->get()->value();
                }
                foreach($student->teams() as $team){
                    foreach($team->scores() as $score){
                        $ejgclass->points += $score->place*$score->event()->weight*$team->sizeModifier()*Setting::firstWhere('key','e5nBasePoint')->get()->value();
                    }
                }
            }
            $ejgclass->save();
        }
    }
}