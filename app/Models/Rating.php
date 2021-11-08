<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Rating of an event by a user.
 *
 * @version 2021.10.24
 * @author Roland Domjan, Zoltan Meszaros
 */
class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';
    protected $hidden = ['created_at','updated_at'];
    protected $guarded = [];

    /**
     * Returns the user who added the rating.
     *
     * @return \App\User
     */
    public function user(){
        return $this->belongsTo(User::class);
    }


    /**
     * Returns the event this rating refers to.
     *
     * @return \App\Event
     */
    public function event(){
        return $this->belongsTo(Event::class);
    }

}
