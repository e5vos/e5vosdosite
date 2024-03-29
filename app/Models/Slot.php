<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Slot
 *
 * @property int $id
 * @property string $starts_at
 * @property string $ends_at
 * @property string $slot_type
 */
class Slot extends Model
{
    use HasFactory;

    protected $table = 'slots';

    protected $fillable = [
        'name',
        'starts_at',
        'ends_at',
        'slot_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get all of the events for the Slot
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get all of the signups for the Slot
     */
    public function signups(): HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, Event::class);
    }
}
