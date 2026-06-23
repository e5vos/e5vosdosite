<?php

namespace Tests\Unit\Models;

use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Attendance;
use App\Models\Event;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class EventModelTest extends TestCase
{
    use CreatesEntities;

    public function test_belongs_to_slot_and_location()
    {
        $slot = $this->makeSlot();
        $event = $this->makeEvent([], $slot);

        $this->assertEquals($slot->id, $event->slot->id);
        $this->assertNotNull($event->location);
        $this->assertEquals($event->location_id, $event->location->id);
    }

    public function test_attendance_count_and_occupancy()
    {
        $event = $this->makeEvent();
        $this->assertSame(0, $event->attendanceCount());
        $this->assertSame(0, $event->occupancy);

        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        // occupancy is a cached attribute; it must be invalidated to refresh.
        $this->assertSame(2, $event->attendanceCount());
        $event->forget('occupancy');
        $this->assertSame(2, $event->occupancy);
    }

    public function test_current_events_scope_matches_running_events()
    {
        $running = $this->makeEvent([
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);
        $past = $this->makeEvent([
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subDay(),
        ]);

        $ids = Event::currentEvents()->pluck('id')->all();
        $this->assertContains($running->id, $ids);
        $this->assertNotContains($past->id, $ids);
    }

    public function test_is_running()
    {
        $running = $this->makeEvent([
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);
        $notStarted = $this->makeEvent([
            'starts_at' => now()->addHour(),
            'ends_at' => now()->addHours(2),
        ]);

        $this->assertTrue($running->isRunning());
        $this->assertFalse($notStarted->isRunning());
    }

    public function test_is_signup_open()
    {
        $noSignup = $this->makeEvent(['signup_type' => null, 'signup_deadline' => null]);
        $this->assertFalse($noSignup->isSignupOpen());

        $openNoDeadline = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => null]);
        $this->assertTrue($openNoDeadline->isSignupOpen());

        $openFuture = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);
        $this->assertTrue($openFuture->isSignupOpen());

        $closedPast = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->subDay()]);
        $this->assertFalse($closedPast->isSignupOpen());
    }

    public function test_users_and_teams_relationships()
    {
        $event = $this->makeEvent(['signup_type' => 'team_user']);
        $user = $this->makeUser();
        $team = $this->makeTeam();

        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);
        Attendance::create(['event_id' => $event->id, 'team_code' => $team->code]);

        $this->assertTrue($event->users()->get()->contains('id', $user->id));
        $this->assertTrue($event->teams()->get()->contains('code', $team->code));
    }

    public function test_organisers_relationship_is_scoped_to_the_event()
    {
        $event = $this->makeEvent();
        $org = $this->makeUser();
        $scn = $this->makeUser();
        $this->grant($org, PermissionType::Organiser->value, $event->id);
        $this->grant($scn, PermissionType::Scanner->value, $event->id);

        // A scanner of a *different* event must not leak into this event's list.
        $otherEvent = $this->makeEvent();
        $otherScanner = $this->makeUser();
        $this->grant($otherScanner, PermissionType::Scanner->value, $otherEvent->id);

        $organiserIds = $event->organisers()->pluck('users.id')->all();
        $this->assertContains($org->id, $organiserIds);
        $this->assertContains($scn->id, $organiserIds);
        $this->assertNotContains($otherScanner->id, $organiserIds);
    }

    public function test_signuppers_merges_users_and_teams()
    {
        $event = $this->makeEvent(['signup_type' => 'team_user']);
        $user = $this->makeUser();
        $team = $this->makeTeam();
        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);
        Attendance::create(['event_id' => $event->id, 'team_code' => $team->code]);

        $signuppers = $event->signuppers();
        $this->assertTrue($signuppers->contains(fn ($s) => $s->getKey() === $user->id));
        $this->assertTrue($signuppers->contains(fn ($s) => $s->getKey() === $team->code));
    }

    public function test_soft_deletes()
    {
        $event = $this->makeEvent();
        $id = $event->id;
        $event->delete();

        $this->assertNull(Event::find($id));
        $this->assertNotNull(Event::withTrashed()->find($id));

        Event::withTrashed()->find($id)->restore();
        $this->assertNotNull(Event::find($id));
    }

    public function test_presentation_slot_event_links_back()
    {
        $slot = $this->makeSlot(SlotType::presentation);
        $event = $this->makeEvent([], $slot);

        $this->assertTrue($slot->events->contains('id', $event->id));
        $this->assertSame(SlotType::presentation->value, $event->slot->slot_type);
    }
}
