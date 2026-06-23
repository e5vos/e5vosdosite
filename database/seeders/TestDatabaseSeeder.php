<?php

namespace Database\Seeders;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Event;
use App\Models\Location;
use App\Models\Slot;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Lightweight seeder for the test suite.
 *
 * Creates just enough rows for tests that call Model::first() or rely on the
 * database not being empty. Individual tests are responsible for their own
 * fixtures; this seeder intentionally avoids attendance data and keeps
 * row counts low so migrate:fresh + seed completes in ~1-2 seconds.
 */
class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Locations — foreign-key target for events.
        Location::factory()->count(5)->create();
        $locationIds = Location::pluck('id')->all();

        // Slots — one presentation, two program slots.
        $presentationSlot = Slot::factory()->create([
            'name' => 'Előadás sáv',
            'slot_type' => SlotType::presentation->value,
            'starts_at' => Carbon::today()->setTime(8, 0),
            'ends_at' => Carbon::today()->setTime(10, 0),
        ]);
        $programSlots = collect([
            Slot::factory()->create([
                'name' => 'Program sáv 1',
                'slot_type' => SlotType::program->value,
                'starts_at' => Carbon::today()->setTime(10, 0),
                'ends_at' => Carbon::today()->setTime(12, 0),
            ]),
            Slot::factory()->create([
                'name' => 'Program sáv 2',
                'slot_type' => SlotType::program->value,
                'starts_at' => Carbon::today()->setTime(13, 0),
                'ends_at' => Carbon::today()->setTime(15, 0),
            ]),
        ]);
        $allSlots = $programSlots->prepend($presentationSlot);

        // Events — a small set spread across slots.
        $now = Carbon::now();
        $events = Event::factory()->count(10)->make()->each(function (Event $event) use ($locationIds, $allSlots, $now) {
            $slot = $allSlots->random();
            $event->location_id = $locationIds[array_rand($locationIds)];
            $event->slot_id = $slot->id;
            $event->starts_at = $slot->starts_at;
            $event->ends_at = $slot->ends_at;
            $event->signup_deadline = $now->copy()->addDay();
            $event->save();
        });

        // Users — 30 rows, bulk-insert permissions afterwards.
        $users = User::factory()->count(30)->create();
        $eventIds = Event::pluck('id')->all();

        $permRows = [];
        $ts = $now->toDateTimeString();
        foreach ($users as $i => $user) {
            $permRows[] = ['user_id' => $user->id, 'code' => PermissionType::Student->value,  'event_id' => null, 'created_at' => $ts, 'updated_at' => $ts];

            if ($i === 0) {
                $permRows[] = ['user_id' => $user->id, 'code' => PermissionType::Operator->value, 'event_id' => null, 'created_at' => $ts, 'updated_at' => $ts];
            }
            if ($i < 3) {
                $permRows[] = ['user_id' => $user->id, 'code' => PermissionType::Admin->value, 'event_id' => null, 'created_at' => $ts, 'updated_at' => $ts];
            }
            if ($i < 5) {
                $permRows[] = ['user_id' => $user->id, 'code' => PermissionType::Teacher->value, 'event_id' => null, 'created_at' => $ts, 'updated_at' => $ts];
            }
            if ($i < 5 && count($eventIds) > 0) {
                $permRows[] = ['user_id' => $user->id, 'code' => PermissionType::Organiser->value, 'event_id' => $eventIds[$i % count($eventIds)], 'created_at' => $ts, 'updated_at' => $ts];
            }
        }
        DB::table('permissions')->insert($permRows);

        // Teams — 2 teams, 3 members each.
        $userIds = $users->pluck('id')->shuffle();
        Team::factory()->count(2)->create()->each(function (Team $team) use (&$userIds) {
            $members = $userIds->splice(0, 3);
            foreach ($members as $j => $userId) {
                TeamMembership::factory()->create([
                    'team_code' => $team->code,
                    'user_id' => $userId,
                    'role' => $j === 0 ? MembershipType::Leader->value : MembershipType::Member->value,
                ]);
            }
        });
    }
}
