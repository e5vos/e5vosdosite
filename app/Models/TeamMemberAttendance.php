<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\TeamMemberAttendance
 * @property int $user_id
 * @property string $team_code
 * @property int $attendance_id
 * @property bool $is_present
 */
class TeamMemberAttendance extends Model
{
    use HasFactory;

    protected $table = 'team_member_attendances';

    public $incrementing = false;

    protected $fillable = ['user_id', 'team_code', 'attendance_id', 'is_present'];

    protected $primaryKey = ['user_id', 'attendance_id'];

    /**
     * toggle the presence of the attendee at the even
     */
    public function togglePresent(): void
    {
        $this->is_present = !$this->is_present;
        $this->save();
    }

    /**
     * Get the user that owns the TeamMemberAttendance
     */
    public function user(): BelongsTo | null
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance that owns the TeamMemberAttendance
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}
