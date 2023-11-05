<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Location
 * @property int $id
 * @property string $name
 * @property string $floor
 */
class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function currentEvents($time = null)
    {
        $time = $time ?? now();
        return $this->events()->where('starts_at', '<=', $time)->where('ends_at', '>=', $time);
    }
}
