<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property string $key
 * @property boolean $value
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'key';
}
