<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Event;
use App\Models\Slot;
use App\Helpers\SlotType;

layout('components.layouts.app');

state(['slots' => [], 'presentations' => [], 'myPresentations' => [], 'currentSlotIndex' => 0]);

mount(function () {
    $this->slots = Slot::where('slot_type', SlotType::presentation->value)->get()->toArray();
    $this->loadPresentations();
    $this->loadMyPresentations();
});

$loadPresentations = function () {
    $slotId = $this->slots[$this->currentSlotIndex]['id'] ?? null;
    $this->presentations = $slotId
        ? Event::with('location')->where('slot_id', $slotId)->get()->toArray()
        : [];
};

$loadMyPresentations = function () {
    $this->myPresentations = auth()->user()
        ->presentations()
        ->get()
        ->pluck('id')
        ->toArray();
};

$selectSlot = function (int $index) {
    $this->currentSlotIndex = $index;
    $this->loadPresentations();
};

$signup = function (int $eventId) {
    $event = Event::findOrFail($eventId);
    $this->authorize('signup', $event);
    auth()->user()->signUp($event);
    $this->loadMyPresentations();
    $this->loadPresentations();
};

$cancelSignup = function (int $eventId) {
    $event = Event::findOrFail($eventId);
    $this->authorize('unsignup', $event);
    // Step 2: cancel signup
    $this->loadMyPresentations();
    $this->loadPresentations();
};
?>

<div class="container mx-auto mt-4" wire:poll.10000ms="loadPresentations">
    <h1 class="mb-4 text-2xl font-bold">Előadásjelentkezés</h1>

    {{-- Slot tabs --}}
    <div class="mb-4 flex flex-wrap gap-2">
        @foreach ($slots as $i => $slot)
            <button wire:click="selectSlot({{ $i }})"
                    class="rounded px-4 py-2 {{ $currentSlotIndex === $i ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700' }}">
                {{ $slot['name'] }}
            </button>
        @endforeach
    </div>

    {{-- Presentations table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2">Előadás</th>
                    <th class="px-4 py-2">Helyszín</th>
                    <th class="px-4 py-2">Férőhely</th>
                    <th class="px-4 py-2">Jelentkezés</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presentations as $presentation)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $presentation['name'] }}</td>
                        <td class="px-4 py-2">{{ $presentation['location']['name'] ?? '–' }}</td>
                        <td class="px-4 py-2">{{ $presentation['occupancy'] ?? 0 }} / {{ $presentation['capacity'] ?? '∞' }}</td>
                        <td class="px-4 py-2">
                            @if (in_array($presentation['id'], $myPresentations))
                                <button wire:click="cancelSignup({{ $presentation['id'] }})"
                                        class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600">
                                    Lemondás
                                </button>
                            @else
                                <button wire:click="signup({{ $presentation['id'] }})"
                                        class="rounded bg-green-600 px-3 py-1 text-white hover:bg-green-700">
                                    Jelentkezés
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
