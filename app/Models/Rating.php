<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Rating
 * @property int $user_id
 * @property int $event_id
 * @property int $rating
 */
class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['rating'];

    /**
     * Get the user that owns the rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that has the rating.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
