<?php

namespace Tests\Unit\Policies;

use App\Exceptions\SignupClosedException;
use App\Exceptions\SignupDisabledException;
use App\Exceptions\WrongSignupTypeException;
use App\Helpers\PermissionType;
use App\Models\Setting;
use App\Policies\EventPolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class EventPolicyTest extends TestCase
{
    use CreatesEntities;

    private EventPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new EventPolicy;
    }

    public function test_before_grants_operators_true(): void
    {
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $this->assertTrue($this->policy->before($operator));
    }

    public function test_before_returns_null_for_non_operators(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);

        $this->assertNull($this->policy->before($admin));
    }

    public function test_restore_allows_admin(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);

        $this->assertTrue($this->policy->restore($admin));
    }

    public function test_restore_denies_plain_user(): void
    {
        $this->assertFalse($this->policy->restore($this->makeUser()));
    }

    public function test_force_delete_always_returns_false(): void
    {
        $this->assertFalse($this->policy->forceDelete());
    }

    public function test_view_always_returns_true(): void
    {
        $this->assertTrue($this->policy->view());
    }

    public function test_create_always_returns_true(): void
    {
        $this->assertTrue($this->policy->create());
    }

    public function test_signup_throws_when_signup_is_disabled(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '']);

        $this->expectException(SignupDisabledException::class);

        $user = $this->makeUser();
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => null]);
        request()->merge(['eventId' => $event->id, 'attender' => $user->e5code]);

        $this->policy->signup($user, $event);
    }

    public function test_signup_throws_when_event_signup_is_closed(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $this->expectException(SignupClosedException::class);

        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->subDay()]);
        request()->merge(['eventId' => $event->id, 'attender' => $user->e5code]);

        $this->policy->signup($user, $event);
    }

    public function test_signup_throws_for_wrong_attender_type(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $this->expectException(WrongSignupTypeException::class);

        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);
        $event = $this->makeEvent(['signup_type' => 'team', 'signup_deadline' => null]);
        // e5code is 13 chars → attenderType = 'user', event wants 'team'
        request()->merge(['eventId' => $event->id, 'attender' => $user->e5code]);

        $this->policy->signup($user, $event);
    }

    public function test_signup_allows_user_signing_up_for_themselves(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => null]);
        request()->merge(['eventId' => $event->id, 'attender' => $user->e5code]);

        $this->assertTrue($this->policy->signup($user, $event));
    }

    public function test_unsignup_throws_when_signup_is_disabled(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '']);

        $this->expectException(SignupDisabledException::class);

        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => null]);
        request()->merge([
            'eventId' => $event->id,
            'attender' => $user->e5code,
        ]);

        $this->policy->unsignup($user, $event);
    }

    public function test_unsignup_throws_when_signup_closed(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $this->expectException(SignupClosedException::class);

        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => now()->subDay()]);
        request()->merge([
            'eventId' => $event->id,
            'attender' => $user->e5code,
        ]);

        $this->policy->unsignup($user, $event);
    }

    public function test_unsignup_allows_own_attender(): void
    {
        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);
        $event = $this->makeEvent(['signup_type' => 'user', 'signup_deadline' => null]);
        request()->merge([
            'eventId' => $event->id,
            'attender' => $user->e5code,
        ]);

        $this->assertTrue($this->policy->unsignup($user, $event));
    }

    public function test_unsignup_denies_missing_attender_request_param(): void
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();

        // Without 'attender' in the request the policy returns false (no abort)
        // so that @can('unsignup', $event) in Blade templates works safely.
        request()->replace([]);

        $this->assertFalse($this->policy->unsignup($user, $event));
    }
}
