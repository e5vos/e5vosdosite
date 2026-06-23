<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Event;
use App\Models\Slot;

layout('components.layouts.app');

state(['slots' => [], 'events' => [], 'currentSlotIndex' => 0]);

mount(function () {
    $this->slots = Slot::all()->toArray();
    $this->loadEvents();
});

$loadEvents = function () {
    $slotId = $this->slots[$this->currentSlotIndex]['id'] ?? null;
    $this->events = $slotId
        ? Event::with('location')->where('slot_id', $slotId)->get()->toArray()
        : [];
};

$selectSlot = function (int $index) {
    $this->currentSlotIndex = $index;
    $this->loadEvents();
};
?>

<div class="container mx-auto mt-4">
    <h1 class="mb-4 text-2xl font-bold">Sávok</h1>

    <div class="mb-4 flex flex-wrap gap-2">
        @foreach ($slots as $i => $slot)
            <button wire:click="selectSlot({{ $i }})"
                    class="rounded px-4 py-2 {{ $currentSlotIndex === $i ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700' }}">
                {{ $slot['name'] }}
            </button>
        @endforeach
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($events as $event)
            <a href="{{ route('esemeny.show', $event['id']) }}"
               class="block rounded-lg border p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                <h2 class="font-semibold">{{ $event['name'] }}</h2>
                <p class="text-sm text-gray-500">{{ $event['location']['name'] ?? '–' }}</p>
            </a>
        @endforeach
    </div>
</div>
