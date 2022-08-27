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

    public function events()
    {
        return $this->hasMany(Event::class);
    }

}
