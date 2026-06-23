<?php
use App\Exceptions\AlreadySignedUpException;
use App\Exceptions\EventFullException;
use App\Exceptions\StudentBusyException;
use App\Models\Attendance;
use App\Models\Event;
use App\Services\EventCacheService;
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state([
    'event'        => null,
    'participants' => [],
    'myAttendance' => null,
    'statusMsg'    => '',
    'isError'      => false,
]);

mount(function (int $eventid) {
    $this->event = Event::with('slot', 'location')->findOrFail($eventid);
    $this->loadParticipants();
    $this->loadMyAttendance();
});

$loadParticipants = function () {
    $user = auth()->user();
    if (! $user) return;
    if (
        $user->hasPermission('ADM') ||
        $user->hasPermission('TCH') ||
        $user->organisesEvent($this->event->id)
    ) {
        $this->participants = Attendance::with('user:id,name,ejg_class', 'team.members:id,name,ejg_class')
            ->where('event_id', $this->event->id)
            ->get()
            ->toArray();
    }
};

$loadMyAttendance = function () {
    if (! auth()->check()) return;
    $this->myAttendance = Attendance::where('event_id', $this->event->id)
        ->where('user_id', auth()->id())
        ->first()?->toArray();
};

$signup = function () {
    $this->authorize('signup', $this->event);
    $this->statusMsg = '';
    try {
        auth()->user()->signUp($this->event);
        EventCacheService::forgetSignup($this->event->id, auth()->user()->e5code ?? auth()->id());
        $this->event = $this->event->fresh('slot', 'location');
        $this->loadMyAttendance();
        $this->loadParticipants();
        $this->statusMsg = 'Sikeresen jelentkeztél!';
        $this->isError   = false;
    } catch (AlreadySignedUpException) {
        $this->statusMsg = 'Már jelentkeztél erre az eseményre.';
        $this->isError   = true;
    } catch (EventFullException) {
        $this->statusMsg = 'Az esemény betelt.';
        $this->isError   = true;
    } catch (StudentBusyException) {
        $this->statusMsg = 'Ebben a sávban már van elfoglaltságod.';
        $this->isError   = true;
    }
};

$cancelSignup = function () {
    $this->authorize('unsignup', $this->event);
    $attendance = Attendance::where('event_id', $this->event->id)
        ->where('user_id', auth()->id())
        ->first();
    if ($attendance) {
        $attendance->teamMemberAttendances()->delete();
        $attendance->delete();
        EventCacheService::forgetSignup($this->event->id, auth()->user()->e5code ?? auth()->id());
        $this->event = $this->event->fresh('slot', 'location');
        $this->loadMyAttendance();
        $this->loadParticipants();
        $this->statusMsg = 'Sikeresen lemondtad a jelentkezésedet.';
        $this->isError   = false;
    }
};

$deleteEvent = function () {
    $this->authorize('delete', $this->event);
    EventCacheService::forgetEvent($this->event->id, $this->event->slot_id);
    $this->event->delete();
    return redirect()->route('esemeny');
};
?>

<div class="container mx-auto mt-4">
    @if ($event)
        {{-- Header --}}
        <div class="mb-4 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $event->name }}</h1>
                @if ($event->organiser)
                    <p class="text-gray-500">Szervező: {{ $event->organiser }}</p>
                @endif
                <div class="mt-1 flex flex-wrap gap-3 text-sm text-gray-500">
                    @if ($event->location)
                        <span>📍 {{ $event->location['name'] }}</span>
                    @endif
                    @if ($event->slot)
                        <span>🕐 {{ $event->slot['name'] }}</span>
                    @endif
                    @if ($event->capacity)
                        <span>👥 {{ $event->occupancy }} / {{ $event->capacity }}</span>
                    @endif
                </div>
            </div>

            @auth
                <div class="flex flex-wrap gap-2">
                    @can('update', $event)
                        <a href="{{ route('esemeny.edit', $event->id) }}"
                           class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                            Szerkesztés
                        </a>
                        <a href="{{ route('esemeny.scanner', $event->id) }}"
                           class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                            Scanner
                        </a>
                    @endcan
                    @can('delete', $event)
                        <div x-data="{ open: false }">
                            <button @click="open = true"
                                    class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                Törlés
                            </button>
                            <div x-show="open" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                                    <p class="mb-4">Biztosan törlöd az eseményt?</p>
                                    <div class="flex gap-2">
                                        <button @click="open = false; $wire.deleteEvent()"
                                                class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                            Törlés
                                        </button>
                                        <button @click="open = false"
                                                class="rounded bg-gray-200 px-4 py-2 dark:bg-gray-700">
                                            Mégse
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            @endauth
        </div>

        {{-- Description --}}
        @if ($event->description)
            <div class="mb-4 rounded-lg border p-4 dark:border-gray-700">
                <p class="whitespace-pre-wrap">{{ $event->description }}</p>
            </div>
        @endif

        {{-- Status message --}}
        @if ($statusMsg)
            <div class="mb-4 rounded px-4 py-3 {{ $isError ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                {{ $statusMsg }}
            </div>
        @endif

        {{-- Signup section --}}
        @auth
            @if ($event->signup_type)
                <div class="mb-4">
                    @if ($myAttendance)
                        <p class="mb-2 text-green-600 dark:text-green-400">✓ Jelentkeztél erre az eseményre.</p>
                        @can('unsignup', $event)
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                        class="rounded border border-red-400 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    Lemondás
                                </button>
                                <div x-show="open" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                    <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                                        <p class="mb-4">Biztosan lemondod a jelentkezésedet?</p>
                                        <div class="flex gap-2">
                                            <button @click="open = false; $wire.cancelSignup()"
                                                    class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                                Lemondás
                                            </button>
                                            <button @click="open = false"
                                                    class="rounded bg-gray-200 px-4 py-2 dark:bg-gray-700">
                                                Mégse
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @elseif ($event->isSignupOpen())
                        @can('signup', $event)
                            <button wire:click="signup"
                                    wire:loading.attr="disabled"
                                    class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700 disabled:opacity-50">
                                <span wire:loading.remove>Jelentkezés</span>
                                <span wire:loading>...</span>
                            </button>
                        @endcan
                    @else
                        <p class="text-gray-500">A jelentkezés lezárult.</p>
                    @endif
                </div>
            @endif
        @endauth

        {{-- Participants list (for admins/organisers/teachers) --}}
        @if (count($participants) > 0)
            <div class="mt-6">
                <h2 class="mb-3 text-lg font-semibold">Résztvevők ({{ count($participants) }})</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2">Név / Csapat</th>
                                <th class="px-4 py-2">Jelen</th>
                                <th class="px-4 py-2">Helyezés</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($participants as $att)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">
                                        @if ($att['user'])
                                            <a href="{{ route('felhasznalo.show', $att['user']['id']) }}"
                                               class="text-blue-600 hover:underline dark:text-blue-400">
                                                {{ $att['user']['name'] }}
                                            </a>
                                            <span class="text-xs text-gray-400">({{ $att['user']['ejg_class'] }})</span>
                                        @elseif ($att['team'])
                                            <span class="font-medium">{{ $att['team']['name'] }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $att['is_present'] ? '✓' : '–' }}
                                    </td>
                                    <td class="px-4 py-2">{{ $att['rank'] ?? '–' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
