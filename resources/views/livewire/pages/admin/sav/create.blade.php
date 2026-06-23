<?php
use function Livewire\Volt\{layout, state, rules};
use App\Models\Slot;
use App\Helpers\SlotType;

layout('components.layouts.app');

state(['name' => '', 'slot_type' => '', 'starts_at' => '', 'ends_at' => '']);

rules([
    'name'      => 'required|string|max:255',
    'slot_type' => 'required|string',
    'starts_at' => 'required|date',
    'ends_at'   => 'required|date|after:starts_at',
]);

$save = function () {
    $this->authorize('create', Slot::class);
    $this->validate();
    Slot::create([
        'name'      => $this->name,
        'slot_type' => $this->slot_type,
        'starts_at' => $this->starts_at,
        'ends_at'   => $this->ends_at,
    ]);
    return redirect()->route('admin.sav');
};
?>

<div class="container mx-auto mt-4 max-w-lg">
    <h1 class="mb-6 text-2xl font-bold">Új sáv</h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="block font-medium">Név</label>
            <input wire:model="name" type="text"
                   class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-medium">Típus</label>
            <select wire:model="slot_type"
                    class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                <option value="">Válassz...</option>
                @foreach (SlotType::cases() as $type)
                    <option value="{{ $type->value }}">{{ $type->value }}</option>
                @endforeach
            </select>
            @error('slot_type') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-medium">Kezdés</label>
            <input wire:model="starts_at" type="datetime-local"
                   class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            @error('starts_at') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-medium">Befejezés</label>
            <input wire:model="ends_at" type="datetime-local"
                   class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            @error('ends_at') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit"
                class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Mentés
        </button>
    </form>
</div>
