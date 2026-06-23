<?php

namespace Tests\Concerns;

use App\Helpers\MembershipType;
use App\Helpers\SlotType;
use App\Models\Event;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Slot;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;

/**
 * Small builders for the most common fixtures. The seeded database already
 * contains a lot of (random) data, so tests that need determinism create their
 * own rows here instead of relying on whatever the seeder produced.
 */
trait CreatesEntities
{
    /** Monotonic counter for collision-proof unique fields. */
    protected static int $entitySeq = 0;

    protected function makeUser(array $attributes = []): User
    {
        $n = ++static::$entitySeq;

        // The seeded database holds 1000 users, which exhausts Faker's small
        // safeEmail pool and risks unique-index collisions. Pin the unique
        // columns to deterministic values; tests that need a real e5code pass
        // one explicitly.
        return User::factory()->create(array_merge([
            'email' => "coverage-{$n}@example.test",
            'google_id' => null,
            'e5code' => null,
        ], $attributes));
    }

    protected function makeSlot(SlotType $type = SlotType::program, array $attributes = []): Slot
    {
        return Slot::factory()->create(array_merge([
            'name' => 'Test '.$type->value,
            'slot_type' => $type->value,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ], $attributes));
    }

    protected function makeEvent(array $attributes = [], ?Slot $slot = null): Event
    {
        $slot ??= $this->makeSlot();
        $location = Location::factory()->create();

        return Event::factory()->create(array_merge([
            'slot_id' => $slot->id,
            'location_id' => $location->id,
            'starts_at' => $slot->starts_at,
            'ends_at' => $slot->ends_at,
            'capacity' => null,
            'signup_type' => 'user',
            // Keep signup closed by default so @can('signup', $event) is never
            // evaluated in Livewire templates unless the test explicitly needs it.
            'signup_deadline' => now()->subDay(),
            'root_parent' => null,
            'direct_child' => null,
        ], $attributes));
    }

    protected function makeTeam(array $attributes = []): Team
    {
        return Team::factory()->create($attributes);
    }

    /**
     * A unique, schema-valid e5code (matches the DB check constraint
     * 20[0-9]{2}[A-FN][0-9]{2}EJG[0-9]{3}). Used where signup/attendance logic
     * branches on a 13-character attender code.
     *
     * Supports up to 100 000 unique codes across a test run by varying both the
     * middle two digits and the final three digits, avoiding the wrap-around
     * collision that occurs when only the final three digits are used.
     */
    protected function uniqueE5code(): string
    {
        $n = ++static::$entitySeq;
        $suffix = str_pad((string) ($n % 1000), 3, '0', STR_PAD_LEFT);
        $mid = str_pad((string) (intdiv($n, 1000) % 100), 2, '0', STR_PAD_LEFT);

        return "2099N{$mid}EJG{$suffix}";
    }

    /**
     * Attach $user to $team with the given role.
     */
    protected function addMember(Team $team, User $user, MembershipType $role = MembershipType::Member): TeamMembership
    {
        return TeamMembership::factory()->create([
            'team_code' => $team->code,
            'user_id' => $user->id,
            'role' => $role->value,
        ]);
    }

    /**
     * Grant a permission code to a user (optionally scoped to an event).
     */
    protected function grant(User $user, string $code, ?int $eventId = null): Permission
    {
        return Permission::create([
            'user_id' => $user->id,
            'event_id' => $eventId,
            'code' => $code,
        ]);
    }
}
