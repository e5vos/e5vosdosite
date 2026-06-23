<?php

namespace Tests\Feature\Livewire;

use App\Helpers\PermissionType;
use App\Models\Attendance;
use App\Models\Setting;
use Livewire\Volt\Volt;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * Tests for the pages.esemeny.show Volt component.
 *
 * Covers signup, cancellation, deletion, participant visibility, and
 * authorization failures.
 */
class EsemenyShowTest extends TestCase
{
    use CreatesEntities;

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function enableSignup(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);
    }

    // -----------------------------------------------------------------------
    // Happy paths
    // -----------------------------------------------------------------------

    public function test_user_with_std_permission_can_sign_up_to_open_event(): void
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Student->value);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('signup');

        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_signup_sets_success_status_message(): void
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Student->value);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('signup')
            ->assertSet('statusMsg', 'Sikeresen jelentkeztél!')
            ->assertSet('isError', false);
    }

    public function test_operator_can_cancel_their_own_signup(): void
    {
        // The unsignup policy requires request()->has('attender'), which the
        // Livewire component does not send.  Operator (OPT) users bypass the
        // policy via the before() hook, so we use one here.
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Operator->value);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('cancelSignup');

        $this->assertDatabaseMissing('attendances', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cancel_signup_sets_success_status_message(): void
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Operator->value);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('cancelSignup')
            ->assertSet('statusMsg', 'Sikeresen lemondtad a jelentkezésedet.')
            ->assertSet('isError', false);
    }

    public function test_adm_user_can_delete_event(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent();

        Volt::actingAs($admin)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('deleteEvent')
            ->assertRedirect(route('esemeny'));

        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }

    // -----------------------------------------------------------------------
    // Failure paths
    // -----------------------------------------------------------------------

    public function test_signup_to_full_event_sets_error_status_message(): void
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Student->value);
        $event = $this->makeEvent([
            'signup_type' => 'user',
            'signup_deadline' => now()->addDay(),
            'capacity' => 1,
        ]);
        // Fill the only spot with someone else.
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('signup')
            ->assertSet('statusMsg', 'Az esemény betelt.')
            ->assertSet('isError', true);
    }

    public function test_signup_to_full_event_does_not_create_attendance(): void
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Student->value);
        $event = $this->makeEvent([
            'signup_type' => 'user',
            'signup_deadline' => now()->addDay(),
            'capacity' => 1,
        ]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('signup');

        $this->assertDatabaseMissing('attendances', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_duplicate_signup_sets_already_signed_up_message(): void
    {
        $this->enableSignup();
        $code = $this->uniqueE5code();
        $user = $this->makeUser(['e5code' => $code]);
        $this->grant($user, PermissionType::Student->value);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);
        Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);

        Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('signup')
            ->assertSet('statusMsg', 'Már jelentkeztél erre az eseményre.')
            ->assertSet('isError', true);
    }

    public function test_guest_cannot_sign_up(): void
    {
        $this->enableSignup();
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->addDay()]);

        // Livewire converts AuthorizationException to a 403 response internally;
        // assert by side-effect: no attendance should be created.
        Volt::test('pages.esemeny.show', ['eventid' => $event->id])
            ->call('signup');

        $this->assertDatabaseMissing('attendances', ['event_id' => $event->id]);
    }

    // -----------------------------------------------------------------------
    // Participant visibility
    // -----------------------------------------------------------------------

    public function test_adm_user_sees_participants_list(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent(['signup_type' => 'user']);
        $participant = $this->makeUser();
        Attendance::create(['event_id' => $event->id, 'user_id' => $participant->id]);

        $component = Volt::actingAs($admin)->test('pages.esemeny.show', ['eventid' => $event->id]);

        $this->assertNotEmpty($component->get('participants'));
    }

    public function test_non_privileged_user_cannot_see_participants_list(): void
    {
        $user = $this->makeUser();
        $event = $this->makeEvent(['signup_type' => 'user']);
        $participant = $this->makeUser();
        Attendance::create(['event_id' => $event->id, 'user_id' => $participant->id]);

        $component = Volt::actingAs($user)->test('pages.esemeny.show', ['eventid' => $event->id]);

        $this->assertEmpty($component->get('participants'));
    }
}
