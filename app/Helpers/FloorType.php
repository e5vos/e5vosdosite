<?php

namespace App\Helpers;
/**
 * App\Helpers\PermissionType
 * DO NOT CHANGE, WILL FUCK UP DB
 */
enum FloorType: int {
    case basement = 0;
    case ground = 1;
    case half = 2;
    case first = 3;
    case second = 4;
    case third = 5;
}
