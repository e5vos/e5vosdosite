<?php
use App\Exceptions\AlreadySignedUpException;
use App\Exceptions\EventFullException;
use App\Exceptions\StudentBusyException;
use App\Helpers\SlotType;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Slot;
use App\Services\EventCacheService;
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state([
    'slotList'         => [],
    'presentations'    => [],
    'myPresentations'  => [],
    'currentSlotIndex' => 0,
    'statusMsg'        => '',
    'isError'          => false,
]);

mount(function () {
    $this->slotList = Slot::where('slot_type', SlotType::presentation->value)->get()->toArray();
    $this->loadPresentations();
    $this->loadMyPresentations();
});

$loadPresentations = function () {
    $slotId = $this->slotList[$this->currentSlotIndex]['id'] ?? null;
    $this->presentations = $slotId
        ? Event::with('location')
            ->where('slot_id', $slotId)
            ->get()
            ->toArray()
        : [];
};

$loadMyPresentations = function () {
    $this->myPresentations = Attendance::where('user_id', auth()->id())
        ->whereHas('event.slot', fn ($q) => $q->where('slot_type', SlotType::presentation->value))
        ->pluck('event_id')
        ->toArray();
};

$selectSlot = function (int $index) {
    $this->currentSlotIndex = $index;
    $this->loadPresentations();
    $this->statusMsg = '';
};

$signup = function (int $eventId) {
    $event = Event::findOrFail($eventId);
    $this->authorize('signup', $event);
    $this->statusMsg = '';
    try {
        auth()->user()->signUp($event);
        EventCacheService::forgetSignup($event->id, auth()->user()->e5code ?? auth()->id());
        $this->loadMyPresentations();
        $this->loadPresentations();
        $this->statusMsg = 'Sikeres jelentkezés!';
        $this->isError   = false;
    } catch (AlreadySignedUpException) {
        $this->statusMsg = 'Már jelentkeztél erre az előadásra.';
        $this->isError   = true;
    } catch (StudentBusyException) {
        $this->statusMsg = 'Ebben a sávban már van elfoglaltságod.';
        $this->isError   = true;
    } catch (EventFullException) {
        $this->statusMsg = 'Az előadás betelt.';
        $this->isError   = true;
    }
};

$cancelSignup = function (int $eventId) {
    $event = Event::findOrFail($eventId);
    $this->authorize('unsignup', $event);
    $this->statusMsg = '';
    $attendance = Attendance::where('event_id', $eventId)
        ->where('user_id', auth()->id())
        ->first();
    if ($attendance) {
        $attendance->teamMemberAttendances()->delete();
        $attendance->delete();
        EventCacheService::forgetSignup($eventId, auth()->user()->e5code ?? auth()->id());
        $this->loadMyPresentations();
        $this->loadPresentations();
        $this->statusMsg = 'Sikeresen lemondtad a jelentkezésedet.';
        $this->isError   = false;
    }
};
?>

<div class="container mx-auto mt-4" wire:poll.10000ms="loadPresentations">
    <h1 class="mb-4 text-2xl font-bold">Előadásjelentkezés</h1>

    {{-- Status message --}}
    @if ($statusMsg)
        <div class="mb-4 rounded px-4 py-3 {{ $isError ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
            {{ $statusMsg }}
        </div>
    @endif

    {{-- Slot tabs --}}
    <div class="mb-4 flex flex-wrap gap-2">
        @foreach ($slotList as $i => $slot)
            <button wire:click="selectSlot({{ $i }})"
                    class="rounded px-4 py-2 {{ $currentSlotIndex === $i ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                {{ $slot['name'] }}
            </button>
        @endforeach
    </div>

    {{-- Presentations table --}}
    <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3">Előadás</th>
                    <th class="px-4 py-3">Helyszín</th>
                    <th class="px-4 py-3 text-right">Férőhely</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($presentations as $p)
                    <tr class="border-t dark:border-gray-700 {{ in_array($p['id'], $myPresentations) ? 'bg-green-50 dark:bg-green-900/10' : '' }}">
                        <td class="px-4 py-3">
                            <a href="{{ route('esemeny.show', $p['id']) }}"
                               class="font-medium hover:underline">
                                {{ $p['name'] }}
                            </a>
                            @if ($p['organiser'])
                                <span class="block text-xs text-gray-400">{{ $p['organiser'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $p['location']['name'] ?? '–' }}</td>
                        <td class="px-4 py-3 text-right text-gray-500">
                            {{ $p['occupancy'] ?? 0 }}{{ $p['capacity'] ? ' / '.$p['capacity'] : '' }}
                        </td>
                        <td class="px-4 py-3">
                            @if (in_array($p['id'], $myPresentations))
                                <div x-data="{ open: false }" class="flex justify-end">
                                    <button @click="open = true"
                                            class="rounded border border-red-400 px-3 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        Lemondás
                                    </button>
                                    <div x-show="open" x-cloak
                                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                        <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                                            <p class="mb-4">Biztosan lemondod a jelentkezésedet?</p>
                                            <div class="flex gap-2">
                                                <button @click="open = false; $wire.cancelSignup({{ $p['id'] }})"
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
                            @elseif ($p['signup_type'] && ($p['signup_deadline'] === null || strtotime($p['signup_deadline']) > time()))
                                <div class="flex justify-end">
                                    <button wire:click="signup({{ $p['id'] }})"
                                            wire:loading.attr="disabled"
                                            class="rounded bg-green-600 px-3 py-1 text-sm text-white hover:bg-green-700 disabled:opacity-50">
                                        Jelentkezés
                                    </button>
                                </div>
                            @else
                                <span class="block text-right text-xs text-gray-400">Lezárult</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                            Nincs előadás ebben a sávban.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
