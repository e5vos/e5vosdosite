<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $dates = ['start','end'];

    protected $appends = ['type','badgeClass','badgeMessage'];

    protected $casts = ['start' => 'datetime:G:i', 'end' => 'datetime:G:i'];



    public function getBadgeClassAttribute(){
        return 'success';
    }
    public function getBadgeMessageAttribute(){
        return $this->badgeClass;
    }
    public function getTypeAttribute(){
        return ['Small','Medium','Large'][$this->weight-1];
    }

    public function getOrgaCountAttribute(){
        return $this->permissions()->count();
    }

    public static function currentEvents(){
        return Event::where('start','<' ,now())->where('end','>',now())->get();
    }

    public function image(){
        return DBImage::find($this->image_id);
    }

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function ongoing(){
        return $this->start < now() && now() < $this->end;
    }


    /**
     * Returns visitorcount of an event.
     *
     * @return int visitor count of an event
     */
    public function visitorcount(){
        return $this->scores()->where('place','0')->whereNotNull('student_id')->count();
    }

}
