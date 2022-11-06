<?php
namespace App\Helpers;

enum MembershipType: string {
    case Invited = 'meghívott';
    case Member = 'tag';
    case Leader = 'vezető';
}
