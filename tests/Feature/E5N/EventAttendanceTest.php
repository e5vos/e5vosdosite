<?php

namespace Tests\Feature\E5N;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Attendance;
use App\Models\Setting;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * Covers the signup / unsignup / attend / score flows on EventController and
 * the EventPolicy gates that guard them.
 */
class EventAttendanceTest extends TestCase
{
    use CreatesEntities;

    private function enableSignup(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);
    }

    private function enableAttendance(): void
    {
        Setting::updateOrCreate(['key' => 'e5n'], ['value' => '1']);
    }

    public function test_user_can_sign_up_to_an_open_event()
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);

        $response = $this->actingAs($user)
            ->postJson("/api/event/{$event->id}/signup", ['attender' => $code]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_team_leader_can_sign_up_their_team()
    {
        $this->enableSignup();
        $leader = $this->makeUser();
        $team = $this->makeTeam();
        $this->addMember($team, $leader, MembershipType::Leader);
        $event = $this->makeEvent(['signup_type' => 'team', 'signup_deadline' => now()->addDay()]);

        $response = $this->actingAs($leader)
            ->postJson("/api/event/{$event->id}/signup", ['attender' => $team->code]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'team_code' => $team->code,
        ]);
    }

    public function test_signup_is_blocked_when_signup_period_is_disabled()
    {
        // Setting intentionally left off.
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);

        $this->actingAs($user)
            ->postJson("/api/event/{$event->id}/signup", ['attender' => $code])
            ->assertStatus(403);
    }

    public function test_signup_rejects_wrong_attender_type()
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        // Team-only event, but a user (13-char code) tries to sign up.
        $event = $this->makeEvent(['signup_type' => 'team', 'signup_deadline' => now()->addDay()]);

        $this->actingAs($user)
            ->postJson("/api/event/{$event->id}/signup", ['attender' => $code])
            ->assertStatus(400);
    }

    public function test_signup_to_full_event_returns_conflict()
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $event = $this->makeEvent([
            'signup_type' => 'user',
            'signup_deadline' => now()->addDay(),
            'capacity' => 1,
        ]);
        // Fill the single spot with someone else.
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $this->actingAs($user)
            ->postJson("/api/event/{$event->id}/signup", ['attender' => $code])
            ->assertStatus(409);
    }

    public function test_signup_twice_returns_conflict()
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);

        $this->actingAs($user)
            ->postJson("/api/event/{$event->id}/signup", ['attender' => $code])
            ->assertStatus(409);
    }

    public function test_user_can_cancel_their_signup()
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);

        $this->actingAs($user)
            ->deleteJson("/api/event/{$event->id}/signup", ['attender' => $code])
            ->assertStatus(204);

        $this->assertDatabaseMissing('attendances', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_organiser_can_register_attendance_for_a_running_event()
    {
        $this->enableAttendance();
        $code = $this->uniqueE5code();
        $admin = $this->makeUser(['e5code' => $code]);
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent([
            'signup_type' => 'user',
            'signup_deadline' => now()->subHour(), // non-null => signup not required
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/api/event/{$event->id}/attend", ['attender' => $code]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'user_id' => $admin->id,
            'is_present' => true,
        ]);
    }

    public function test_attendance_is_blocked_when_e5n_setting_is_off()
    {
        $code = $this->uniqueE5code();
        $admin = $this->makeUser(['e5code' => $code]);
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent([
            'signup_type' => 'user',
            'signup_deadline' => now()->subHour(),
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        $this->actingAs($admin)
            ->postJson("/api/event/{$event->id}/attend", ['attender' => $code])
            ->assertStatus(403);
    }

    public function test_organiser_can_close_signup()
    {
        $organiser = $this->makeUser();
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDays(2)]);
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);

        $this->actingAs($organiser)
            ->putJson("/api/event/{$event->id}/close")
            ->assertStatus(200);

        // Deadline moved to "now", so signup is no longer open.
        $this->assertFalse($event->fresh()->isSignupOpen());
    }

    public function test_participants_listing_requires_privilege()
    {
        $event = $this->makeEvent(['signup_type' => 'user']);
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $this->actingAs($this->makeUser())
            ->getJson("/api/event/{$event->id}/participants")
            ->assertStatus(403);

        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);
        $this->actingAs($operator)
            ->getJson("/api/event/{$event->id}/participants")
            ->assertStatus(200);
    }

    public function test_organisers_listing()
    {
        $event = $this->makeEvent();
        $org = $this->makeUser();
        $this->grant($org, PermissionType::Organiser->value, $event->id);

        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $this->actingAs($operator)
            ->getJson("/api/event/{$event->id}/organisers")
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $org->id]);
    }

    public function test_presentations_endpoint_is_public()
    {
        $this->makeEvent([], $this->makeSlot(SlotType::presentation));

        $this->getJson('/api/presentations')->assertStatus(200);
    }

    public function test_my_presentations_requires_auth_and_returns_ok()
    {
        $this->getJson('/api/mypresentations')->assertStatus(401);

        $this->actingAs($this->makeUser())
            ->getJson('/api/mypresentations')
            ->assertStatus(200);
    }

    public function test_set_score_is_forbidden_for_unprivileged_user()
    {
        $event = $this->makeEvent();

        $this->actingAs($this->makeUser())
            ->postJson("/api/event/{$event->id}/score", ['attender' => '1', 'rank' => 1])
            ->assertStatus(403);
    }

    public function test_organiser_can_set_a_score()
    {
        $organiser = $this->makeUser();
        $event = $this->makeEvent(['signup_type' => 'user']);
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);
        $participant = $this->makeUser();
        Attendance::create(['event_id' => $event->id, 'user_id' => $participant->id]);

        $this->actingAs($organiser)
            ->postJson("/api/event/{$event->id}/score", ['attender' => $participant->id, 'rank' => 1])
            ->assertStatus(204);

        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'user_id' => $participant->id,
            'rank' => 1,
        ]);
    }

    public function test_setting_a_score_releases_the_previous_rank_holder()
    {
        $organiser = $this->makeUser();
        $event = $this->makeEvent(['signup_type' => 'user']);
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);
        $first = Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id, 'rank' => 1]);
        $second = Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $this->actingAs($organiser)
            ->postJson("/api/event/{$event->id}/score", ['attender' => $second->user_id, 'rank' => 1])
            ->assertStatus(204);

        $this->assertDatabaseHas('attendances', ['id' => $second->id, 'rank' => 1]);
        $this->assertDatabaseHas('attendances', ['id' => $first->id, 'rank' => null]);
    }

    public function test_orgas_endpoint_is_public()
    {
        $event = $this->makeEvent();
        $org = $this->makeUser();
        $this->grant($org, PermissionType::Organiser->value, $event->id);

        $this->getJson("/api/event/{$event->id}/orgas")
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $org->id]);
    }

    public function test_team_member_attendance_can_be_recorded()
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member, MembershipType::Leader);
        $event = $this->makeEvent(['signup_type' => 'team']);
        $attendance = $team->signUp($event);

        $payload = json_encode([
            ['user_id' => $member->id, 'is_present' => true],
        ]);

        $this->actingAs($member)
            ->postJson("/api/attendance/{$attendance->id}/teamMemberAttend", [
                'memberAttendances' => $payload,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('team_member_attendances', [
            'attendance_id' => $attendance->id,
            'user_id' => $member->id,
            'is_present' => true,
        ]);
    }

    public function test_event_index_search_by_name()
    {
        $event = $this->makeEvent(['name' => 'UniqueCoverageHappening']);

        $this->getJson('/api/events?q=UniqueCoverageHappening')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'UniqueCoverageHappening']);

        $this->getJson('/api/events/'.$event->slot_id.'?q=UniqueCoverageHappening')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'UniqueCoverageHappening']);
    }
}
