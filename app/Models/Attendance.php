<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Attendance
 * @property int $id
 * @property int $user_id
 * @property int $team_code
 * @property int $event_id
 * @property bool $is_present
 * @property int $rank
 * @property int $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'team_code',
        'event_id',
        'is_present',
        'rank',
        'rating',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Attendance.php Boot the model.
     *
     * Assing global scopes, etc. Order by updated_at

     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', fn ($builder) => $builder->orderBy('attendances.updated_at', 'desc'));
    }

    /**
     * toggle the presence of the attendee at the event
     */
    public function togglePresent(): void
    {
        $this->is_present = !$this->is_present;
        $this->save();
    }

    /**
     * Get user that owns the attendance if attendance is not team attendance
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get team that owns the attendance if attendance is team attendance
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get team members that owns the attendance if attendance is team attendance
     */
    public function usersInTeam(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_member_attendances');
    }

    /**
     * Get event that the attendance belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the team member attendances for the attendance.
     */
    public function teamMemberAttendances(): HasMany
    {
        return $this->hasMany(TeamMemberAttendance::class);
    }
}
