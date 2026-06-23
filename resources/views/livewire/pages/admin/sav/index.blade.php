<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Slot;

layout('components.layouts.app');

state(['slots' => []]);

mount(function () {
    $this->slots = Slot::all()->toArray();
});

$delete = function (int $slotId) {
    $slot = Slot::findOrFail($slotId);
    $this->authorize('delete', $slot);
    $slot->delete();
    $this->slots = Slot::all()->toArray();
};
?>

<div class="container mx-auto mt-4">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Sávok</h1>
        <a href="{{ route('admin.sav.create') }}"
           class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Új sáv
        </a>
    </div>

    <div class="space-y-2">
        @foreach ($slots as $slot)
            <div class="flex items-center justify-between rounded-lg border p-4 dark:border-gray-700">
                <div>
                    <p class="font-semibold">{{ $slot['name'] }}</p>
                    <p class="text-sm text-gray-500">{{ $slot['slot_type'] }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.sav.edit', $slot['id']) }}"
                       class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                        Szerkesztés
                    </a>
                    <button wire:click="delete({{ $slot['id'] }})"
                            wire:confirm="Biztosan törlöd a sávot?"
                            class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600">
                        Törlés
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
