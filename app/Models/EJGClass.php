<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Setting;


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
    public function calculatePoints(){

        $this->points = $this->bonuspoints()->where('event','E5N')->sum();
        $e5nBasePoint = Setting::lookup('e5nBasePoint');
        foreach($this->students() as $student){
            foreach($this->scores() as $score){
                $this->points += $score->place*$score->event()->weight*$e5nBasePoint;
            }
            foreach($student->teams() as $team){
                foreach($team->signups() as $signUp){
                    $this->points += $signUp->place*$signUp->event()->weight*$team->sizeModifier()*$e5nBasePoint;
                }
            }
        }
        $this->save();
    }
}
