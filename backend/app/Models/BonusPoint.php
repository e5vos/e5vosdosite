<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPoint extends Model
/**
 * App\Models\BonusPoint
 * @property int $id
 * @property int $quantity
 * @property string $ejg_class
 * @property Illuminate\Support\Carbon|null $created_at
 * @property Illuminate\Support\Carbon|null $updated_at
 */
{
    use HasFactory;

    protected $table = 'bonus_points';
}
