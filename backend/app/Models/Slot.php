<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


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

    /**
     * Get all of the events for the Slot
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
