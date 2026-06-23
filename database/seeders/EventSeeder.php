<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Location;
use App\Models\Slot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function __construct(private int $count = 100) {}

    public function run(): void
    {
        $locationIds = Location::pluck('id')->all();
        $slots = Slot::all();

        // Create all events in one batch, then update times/links in bulk.
        $events = Event::factory()->count($this->count)->create();

        $updates = [];
        foreach ($events as $event) {
            $slot = $slots->random();
            $slotStart = Carbon::parse($slot->starts_at);
            $slotEnd = Carbon::parse($slot->ends_at);
            $slotLength = max(1, $slotEnd->diffInHours($slotStart));

            $startsAt = $slotStart->copy()->addHours(rand(0, $slotLength));
            $endsAt = $slotEnd->copy()->subHours(rand(0, $slotLength));

            $updates[] = [
                'id' => $event->id,
                'location_id' => $locationIds[array_rand($locationIds)],
                'slot_id' => $slot->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt->lt($startsAt) ? $startsAt->copy()->addHour() : $endsAt,
                'signup_deadline' => $startsAt->copy()->subDays(rand(0, 7)),
            ];
        }

        // Bulk-update via CASE WHEN to avoid N+1 UPDATEs.
        foreach (array_chunk($updates, 50) as $chunk) {
            $ids = array_column($chunk, 'id');
            $cases = ['location_id' => '', 'slot_id' => '', 'starts_at' => '', 'ends_at' => '', 'signup_deadline' => ''];
            foreach ($chunk as $row) {
                $id = (int) $row['id'];
                $cases['location_id'] .= "WHEN {$id} THEN {$row['location_id']} ";
                $cases['slot_id'] .= "WHEN {$id} THEN {$row['slot_id']} ";
                $cases['starts_at'] .= "WHEN {$id} THEN '{$row['starts_at']}' ";
                $cases['ends_at'] .= "WHEN {$id} THEN '{$row['ends_at']}' ";
                $cases['signup_deadline'] .= "WHEN {$id} THEN '{$row['signup_deadline']}' ";
            }
            $idList = implode(',', $ids);
            DB::statement("
                UPDATE events SET
                  location_id    = CASE id {$cases['location_id']} END,
                  slot_id        = CASE id {$cases['slot_id']} END,
                  starts_at      = CASE id {$cases['starts_at']} END,
                  ends_at        = CASE id {$cases['ends_at']} END,
                  signup_deadline= CASE id {$cases['signup_deadline']} END
                WHERE id IN ({$idList})
            ");
        }

        // Wire up a handful of parent/child chains for variety.
        $chainCount = min(5, intdiv($this->count, 3));
        $ids = $events->pluck('id')->shuffle()->take($chainCount * 3)->values()->all();
        for ($i = 0; $i < $chainCount * 3; $i += 3) {
            Event::whereIn('id', [$ids[$i], $ids[$i + 1], $ids[$i + 2]])->update(['root_parent' => null, 'direct_child' => null]);
            Event::find($ids[$i])->update(['direct_child' => $ids[$i + 1]]);
            Event::find($ids[$i + 1])->update(['direct_child' => $ids[$i + 2], 'root_parent' => $ids[$i]]);
            Event::find($ids[$i + 2])->update(['root_parent' => $ids[$i]]);
        }
    }
}
