<?php

namespace App\Helpers;

enum PermissionType: string {
    case Organiser = "ORG";
    case Aadmin = "ADM";
    case Teacher = "TCH";
}
