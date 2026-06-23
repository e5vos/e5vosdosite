<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property string $key
 * @property string $value
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

    /**
     * The primary key is the non-incrementing string "key" column. Without
     * these, Eloquent assumes an auto-incrementing integer key and casts the
     * key attribute to int(0) in memory (and on serialization).
     */
    public $incrementing = false;

    protected $keyType = 'string';
}
