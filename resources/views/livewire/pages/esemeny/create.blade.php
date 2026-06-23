<?php
use function Livewire\Volt\{layout, state, mount, rules};
use App\Models\Location;
use App\Models\Slot;

layout('components.layouts.app');

state([
    'name' => '', 'description' => '', 'starts_at' => '',
    'ends_at' => '', 'signup_deadline' => '', 'signup_type' => 'team_user',
    'location_id' => '', 'capacity' => '', 'slot_id' => '',
    'is_competition' => false,
    'locations' => [], 'slots' => [],
]);

mount(function () {
    $this->authorize('create', \App\Models\Event::class);
    $this->locations = Location::all()->toArray();
    $this->slots = Slot::all()->toArray();
});

rules([
    'name'     => 'required|string|max:255',
    'slot_id'  => 'required|integer|exists:slots,id',
]);

$save = function () {
    $this->authorize('create', \App\Models\Event::class);
    $this->validate();
    // Step 2: create event
};
?>

<div class="container mx-auto mt-4 max-w-2xl">
    <h1 class="mb-6 text-2xl font-bold">Új esemény</h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="block font-medium">Név</label>
            <input wire:model="name" type="text"
                   class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-medium">Leírás</label>
            <textarea wire:model="description" rows="4"
                      class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600"></textarea>
        </div>
        <div>
            <label class="block font-medium">Sáv</label>
            <select wire:model="slot_id"
                    class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                <option value="">Válassz sávot...</option>
                @foreach ($slots as $slot)
                    <option value="{{ $slot['id'] }}">{{ $slot['name'] }}</option>
                @endforeach
            </select>
            @error('slot_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-medium">Helyszín</label>
            <select wire:model="location_id"
                    class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                <option value="">Válassz helyszínt...</option>
                @foreach ($locations as $location)
                    <option value="{{ $location['id'] }}">{{ $location['name'] }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Mentés
        </button>
    </form>
</div>
