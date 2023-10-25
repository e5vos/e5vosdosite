<?php

namespace App\Helpers;

/**
 * App\Helpers\PermissionType
 * DO NOT CHANGE, WILL FUCK UP DB
 */
enum FloorType: string
{
    case basement = 'alagsor';
    case ground = 'földszint';
    case half = 'félemelet';
    case first = 'első emelet';
    case second = 'második emelet';
    case third = 'harmadik emelet';
}
