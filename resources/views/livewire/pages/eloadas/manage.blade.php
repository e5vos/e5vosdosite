<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Event;
use App\Models\Slot;
use App\Helpers\SlotType;

layout('components.layouts.app');

state(['slots' => [], 'presentations' => [], 'search' => '', 'currentSlotIndex' => 0]);

mount(function () {
    abort_unless(auth()->user()?->hasPermission('TCH'), 403);
    $this->slots = Slot::where('slot_type', SlotType::presentation->value)->get()->toArray();
    $this->loadPresentations();
});

$loadPresentations = function () {
    $slotId = $this->slots[$this->currentSlotIndex]['id'] ?? null;
    $this->presentations = $slotId
        ? Event::with('location')
            ->where('slot_id', $slotId)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->get()
            ->toArray()
        : [];
};

$selectSlot = function (int $index) {
    $this->currentSlotIndex = $index;
    $this->loadPresentations();
};
?>

<div class="container mx-auto mt-4">
    <h1 class="mb-4 text-2xl font-bold">Előadások – Tanári nézet</h1>

    <div class="mb-4 flex flex-wrap gap-2">
        @foreach ($slots as $i => $slot)
            <button wire:click="selectSlot({{ $i }})"
                    class="rounded px-4 py-2 {{ $currentSlotIndex === $i ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700' }}">
                {{ $slot['name'] }}
            </button>
        @endforeach
    </div>

    <input wire:model.debounce.300ms="search" wire:change="loadPresentations"
           type="text" placeholder="Keresés..."
           class="mb-4 w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2">Előadás</th>
                    <th class="px-4 py-2">Helyszín</th>
                    <th class="px-4 py-2">Résztvevők</th>
                    <th class="px-4 py-2">Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presentations as $presentation)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $presentation['name'] }}</td>
                        <td class="px-4 py-2">{{ $presentation['location']['name'] ?? '–' }}</td>
                        <td class="px-4 py-2">{{ $presentation['occupancy'] ?? 0 }}</td>
                        <td class="flex gap-2 px-4 py-2">
                            <a href="{{ route('eloadas.attendance', $presentation['id']) }}"
                               class="rounded bg-blue-600 px-3 py-1 text-white hover:bg-blue-700">
                                Jelenlét
                            </a>
                            <a href="{{ route('eloadas.scanner', $presentation['id']) }}"
                               class="rounded bg-green-600 px-3 py-1 text-white hover:bg-green-700">
                                Scanner
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
