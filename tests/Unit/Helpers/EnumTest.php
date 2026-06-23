<?php

namespace Tests\Unit\Helpers;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit tests for the application's backing enums. These have no database
 * dependency, so they extend PHPUnit's TestCase directly (like the existing
 * example unit test) and run fast.
 */
class EnumTest extends TestCase
{
    public function test_permission_type_values_are_stable()
    {
        // These string values are persisted in the database, so they must not
        // drift. Pin them explicitly.
        $this->assertSame('ORG', PermissionType::Organiser->value);
        $this->assertSame('SCN', PermissionType::Scanner->value);
        $this->assertSame('ADM', PermissionType::Admin->value);
        $this->assertSame('TCH', PermissionType::Teacher->value);
        $this->assertSame('STD', PermissionType::Student->value);
        $this->assertSame('OPT', PermissionType::Operator->value);
        $this->assertSame('TAD', PermissionType::TeacherAdmin->value);
    }

    public function test_permission_type_can_be_built_from_value()
    {
        $this->assertSame(PermissionType::Admin, PermissionType::from('ADM'));
        $this->assertNull(PermissionType::tryFrom('NOPE'));
    }

    public function test_permission_type_has_seven_cases()
    {
        $this->assertCount(7, PermissionType::cases());
    }

    public function test_slot_type_values_are_stable()
    {
        // Comment in SlotType warns these must not change or the DB breaks.
        $this->assertSame('Előadássáv', SlotType::presentation->value);
        $this->assertSame('Programsáv', SlotType::program->value);
        $this->assertCount(2, SlotType::cases());
    }

    public function test_membership_type_values_are_stable()
    {
        $this->assertSame('meghívott', MembershipType::Invited->value);
        $this->assertSame('tag', MembershipType::Member->value);
        $this->assertSame('vezető', MembershipType::Leader->value);
        $this->assertCount(3, MembershipType::cases());
    }
}
