<?php
namespace App\Helpers;

enum MembershipType {
    case Invited;
    case Member;
    case Leader;
    case Owner;
}
