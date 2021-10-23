<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';
    protected $hidden = ['created_at','updated_at'];

    /**
     * references the user who added the rating
     *
     * @return UserClass
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function createRating($rating){
        $newRating = new Rating;
        Rating::updateRating($newRating,$rating);
    }

    public function updateRating($from, $rating){
        $newRating = Rating::find($from);
        $newRating->user_id = $rating->user_id;
        $newRating->event_id = $rating->event_id;
        $newRating->rating = $rating->rating;
        $newRating->save();
    }

}
