<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Permission
 * @property int $user_id
 * @property int|null $event_id
 * @property int $code
 */



class Permission extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    protected $primaryKey = ['user_id','event_id', 'code'];

    public $incrementing = false;

    /**
     * Get the user that owns the permission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the event that has the permission.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
