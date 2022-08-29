<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMemberAttendance extends Model
{
    use HasFactory;

    protected $table = 'team_member_attendances';

    public $incrementing = false;

    protected $fillable = ['user_id','team_id'];
}
