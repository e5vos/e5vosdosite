<?php

namespace Tests\Unit\Models;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Attendance;
use App\Models\BonusPoint;
use App\Models\Permission;
use App\Models\Setting;
use App\Models\TeamMembership;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class SupportingModelsTest extends TestCase
{
    use CreatesEntities;

    public function test_slot_events_and_signups_relationships()
    {
        $slot = $this->makeSlot();
        $event = $this->makeEvent([], $slot);
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $this->assertTrue($slot->events->contains('id', $event->id));
        // signups() is a hasManyThrough Attendance -> Event.
        $this->assertSame(1, $slot->signups()->count());
    }

    public function test_location_events_and_current_events()
    {
        $event = $this->makeEvent([
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);
        $location = $event->location;

        $this->assertTrue($location->events->contains('id', $event->id));
        $this->assertTrue($location->currentEvents()->get()->contains('id', $event->id));

        // An event scheduled entirely in the past is not "current".
        $past = $this->makeEvent([
            'location_id' => $location->id,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subDay(),
        ]);
        $this->assertFalse($location->currentEvents()->get()->contains('id', $past->id));
    }

    public function test_setting_is_keyed_by_key_column()
    {
        Setting::updateOrCreate(['key' => 'coverage.flag'], ['value' => 'on']);
        $this->assertDatabaseHas('settings', ['key' => 'coverage.flag', 'value' => 'on']);

        // Look-ups resolve by the string 'key' primary key, which is now kept
        // intact in memory (the model declares $incrementing = false).
        $found = Setting::find('coverage.flag');
        $this->assertNotNull($found);
        $this->assertSame('key', $found->getKeyName());
        $this->assertSame('coverage.flag', $found->getKey());
        $this->assertSame('coverage.flag', $found->key);
        $this->assertEquals('on', $found->value);

        // updateOrCreate updates the existing row rather than inserting a new one.
        Setting::updateOrCreate(['key' => 'coverage.flag'], ['value' => 'off']);
        $this->assertEquals('off', Setting::find('coverage.flag')->value);
        $this->assertSame(1, Setting::where('key', 'coverage.flag')->count());
    }

    public function test_bonus_point_factory_creates_row()
    {
        $bonus = BonusPoint::factory()->create(['ejg_class' => '9.A', 'quantity' => 42]);

        $this->assertDatabaseHas('bonus_points', ['id' => $bonus->id, 'quantity' => 42]);
        $this->assertSame('9.A', $bonus->ejg_class);
    }

    public function test_permission_user_and_event_relationships()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        $permission = Permission::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Organiser->value,
        ]);

        $this->assertEquals($user->id, $permission->user->id);
        $this->assertEquals($event->id, $permission->event->id);
    }

    public function test_permission_composite_find_returns_matching_model_or_null()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        Permission::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Scanner->value,
        ]);

        // find() honours the actual code (no longer forced to Organiser).
        $found = Permission::find([$user->id, $event->id, PermissionType::Scanner->value]);
        $this->assertInstanceOf(Permission::class, $found);
        $this->assertEquals($user->id, $found->user_id);
        $this->assertEquals(PermissionType::Scanner->value, $found->code);

        // A non-existent composite key returns null.
        $this->assertNull(Permission::find([$user->id, $event->id, PermissionType::Organiser->value]));

        // event_id null (non-event-scoped permission) resolves via IS NULL.
        Permission::create(['user_id' => $user->id, 'event_id' => null, 'code' => PermissionType::Admin->value]);
        $this->assertNotNull(Permission::find([$user->id, null, PermissionType::Admin->value]));
    }

    public function test_team_membership_composite_key_update()
    {
        $team = $this->makeTeam();
        $user = $this->makeUser();
        $membership = $this->addMember($team, $user, MembershipType::Member);

        // Exercises HasCompositeKey::setKeysForSaveQuery on update.
        $membership->role = MembershipType::Leader->value;
        $membership->save();

        $this->assertDatabaseHas('team_memberships', [
            'team_code' => $team->code,
            'user_id' => $user->id,
            'role' => MembershipType::Leader->value,
        ]);
    }

    public function test_team_membership_relationships()
    {
        $team = $this->makeTeam();
        $user = $this->makeUser();
        $this->addMember($team, $user, MembershipType::Member);

        $membership = TeamMembership::where('team_code', $team->code)
            ->where('user_id', $user->id)
            ->first();

        $this->assertEquals($user->id, $membership->user->id);
        $this->assertEquals($team->code, $membership->team->code);
    }

    public function test_presentation_slot_type_helper()
    {
        $slot = $this->makeSlot(SlotType::presentation);
        $this->assertSame(SlotType::presentation->value, $slot->slot_type);
    }
}
