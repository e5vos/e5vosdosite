<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Attendance;
use App\Models\Event;

layout('components.layouts.app');

state(['event' => null, 'participants' => []]);

mount(function (int $eventid) {
    abort_unless(auth()->user()?->hasPermission('TCH') || auth()->user()?->hasPermission('ADM'), 403);
    $this->event = Event::findOrFail($eventid);
    $this->loadParticipants();
});

$loadParticipants = function () {
    $this->participants = Attendance::with('user')
        ->where('event_id', $this->event->id)
        ->get()
        ->toArray();
};

$togglePresence = function (int $attendanceId) {
    $attendance = Attendance::findOrFail($attendanceId);
    $this->authorize('attend', $attendance->event);
    $attendance->is_present = ! $attendance->is_present;
    $attendance->save();
    $this->loadParticipants();
};

$deleteAttendance = function (int $attendanceId) {
    $attendance = Attendance::findOrFail($attendanceId);
    $this->authorize('unsignup', $attendance->event);
    $attendance->delete();
    $this->loadParticipants();
};
?>

<div class="container mx-auto mt-4">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Jelenlét – {{ $event?->name }}</h1>
        <button wire:click="loadParticipants"
                class="rounded bg-gray-200 px-4 py-2 hover:bg-gray-300 dark:bg-gray-700">
            Frissítés
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2">Név</th>
                    <th class="px-4 py-2">Jelen van</th>
                    <th class="px-4 py-2">Törlés</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participants as $att)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $att['user']['name'] ?? '–' }}</td>
                        <td class="px-4 py-2">
                            <input type="checkbox"
                                   wire:click="togglePresence({{ $att['id'] }})"
                                   {{ $att['is_present'] ? 'checked' : '' }} />
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="deleteAttendance({{ $att['id'] }})"
                                    wire:confirm="Biztosan törlöd a jelentkezést?"
                                    class="rounded bg-red-500 px-2 py-1 text-white hover:bg-red-600">
                                Törlés
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
