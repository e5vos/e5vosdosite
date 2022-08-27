<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * App\Models\Slot
 *
 * @property int $id
 * @property int $starts_at
 * @property int $ends_at
 * @property bool $is_presentation
 */
class Slot extends Model
{
    use HasFactory;

    /**
     * Get all of the events for the Slot
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
