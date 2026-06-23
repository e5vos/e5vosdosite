<?php

namespace Database\Seeders;

use App\Helpers\PermissionType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function __construct(private int $count = 1000) {}

    public function run(): void
    {
        $users = User::factory()->count($this->count)->create();
        $eventIds = Event::pluck('id')->all();
        $eventCount = count($eventIds);
        $now = Carbon::now();

        // Build all permission rows in-memory, then bulk-insert in one query per chunk.
        $rows = [];
        $orgIndex = 0;

        foreach ($users as $i => $user) {
            // Every user gets a Student permission.
            $rows[] = [
                'user_id' => $user->id,
                'code' => PermissionType::Student->value,
                'event_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Distribute Organiser permissions across events so each event gets
            // roughly the same share of organisers (mirrors the old complex closure).
            if ($eventCount > 0) {
                $rows[] = [
                    'user_id' => $user->id,
                    'code' => PermissionType::Organiser->value,
                    'event_id' => $eventIds[$orgIndex % $eventCount],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $orgIndex++;
            }

            // Sprinkle a few extra role permissions for variety.
            if ($i % 20 === 0) {
                $rows[] = ['user_id' => $user->id, 'code' => PermissionType::Teacher->value,      'event_id' => null, 'created_at' => $now, 'updated_at' => $now];
            }
            if ($i % 50 === 0) {
                $rows[] = ['user_id' => $user->id, 'code' => PermissionType::Admin->value,        'event_id' => null, 'created_at' => $now, 'updated_at' => $now];
            }
            if ($i % 100 === 0) {
                $rows[] = ['user_id' => $user->id, 'code' => PermissionType::TeacherAdmin->value, 'event_id' => null, 'created_at' => $now, 'updated_at' => $now];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('permissions')->insert($chunk);
        }
    }
}
