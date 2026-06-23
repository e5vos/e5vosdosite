<?php

namespace Tests\Feature\E5N;

use App\Helpers\PermissionType;
use App\Models\Attendance;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * Covers SlotController's free/attending/missing student listings and the
 * SlotPolicy::freeStudents gate.
 */
class SlotStudentsTest extends TestCase
{
    use CreatesEntities;

    public function test_free_students_requires_teacher_role()
    {
        $slot = $this->makeSlot();

        $this->actingAs($this->makeUser())
            ->getJson("/api/slot/{$slot->id}/free_students")
            ->assertStatus(403);

        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);
        $this->actingAs($teacher)
            ->getJson("/api/slot/{$slot->id}/free_students")
            ->assertStatus(200);
    }

    public function test_attending_students_listing()
    {
        $slot = $this->makeSlot();
        $event = $this->makeEvent([], $slot);
        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $this->makeUser()->id,
            'is_present' => true,
        ]);

        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);

        $this->actingAs($teacher)
            ->getJson("/api/slot/{$slot->id}/attending_students")
            ->assertStatus(200);
    }

    public function test_missing_students_listing()
    {
        $slot = $this->makeSlot();
        $event = $this->makeEvent([], $slot);
        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $this->makeUser()->id,
            'is_present' => false,
        ]);

        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);

        $this->actingAs($teacher)
            ->getJson("/api/slot/{$slot->id}/missing_students")
            ->assertStatus(200);
    }

    public function test_student_listings_require_authentication()
    {
        $slot = $this->makeSlot();

        $this->getJson("/api/slot/{$slot->id}/free_students")->assertStatus(401);
    }
}
