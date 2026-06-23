<?php
use App\Models\Event;
use App\Models\Location;
use App\Models\Slot;
use App\Services\EventCacheService;
use function Livewire\Volt\{layout, state, mount, rules};

layout('components.layouts.app');

state([
    'name' => '', 'description' => '', 'organiser' => '',
    'starts_at' => '', 'ends_at' => '', 'signup_deadline' => '',
    'signup_type' => '', 'location_id' => '', 'capacity' => '',
    'slot_id' => '',
    'locations' => [], 'slots' => [],
]);

mount(function () {
    $this->authorize('create', Event::class);
    $this->locations = Location::all(['id', 'name'])->toArray();
    $this->slots     = Slot::all(['id', 'name', 'starts_at', 'ends_at'])->toArray();
});

rules([
    'name'    => 'required|string|max:255',
    'slot_id' => 'required|integer|exists:slots,id',
]);

$save = function () {
    $this->authorize('create', Event::class);
    $this->validate();

    $slot = Slot::find($this->slot_id);

    $startsAt = $this->starts_at ?: $slot->starts_at;
    $endsAt   = $this->ends_at   ?: $slot->ends_at;

    if ($startsAt < $slot->starts_at) {
        $startsAt = $slot->starts_at;
    }
    if ($endsAt > $slot->ends_at) {
        $endsAt = $slot->ends_at;
    }

    $signupDeadline = null;
    if ($this->signup_type) {
        $signupDeadline = $this->signup_deadline ?: $slot->starts_at;
    }

    $event = Event::create([
        'slot_id'          => $this->slot_id,
        'name'             => $this->name,
        'description'      => $this->description,
        'organiser'        => $this->organiser,
        'signup_type'      => $this->signup_type ?: null,
        'capacity'         => $this->capacity ?: null,
        'starts_at'        => $startsAt,
        'ends_at'          => $endsAt,
        'signup_deadline'  => $signupDeadline,
    ]);

    if ($this->location_id) {
        $event->location_id = $this->location_id;
        $event->save();
    }

    EventCacheService::forgetEvent($event->id, $event->slot_id);

    return redirect()->route('esemeny.show', $event->id);
};
?>

<div class="container mx-auto mt-4 max-w-2xl">
    <h1 class="mb-6 text-2xl font-bold">Új esemény</h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="block font-medium">Név *</label>
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
            <label class="block font-medium">Szervező neve</label>
            <input wire:model="organiser" type="text"
                   class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
        </div>

        <div>
            <label class="block font-medium">Sáv *</label>
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
                <option value="">Nincs megadva</option>
                @foreach ($locations as $location)
                    <option value="{{ $location['id'] }}">{{ $location['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Kezdés</label>
                <input wire:model="starts_at" type="datetime-local"
                       class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            </div>
            <div>
                <label class="block font-medium">Befejezés</label>
                <input wire:model="ends_at" type="datetime-local"
                       class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            </div>
        </div>

        <div>
            <label class="block font-medium">Jelentkezés típusa</label>
            <select wire:model="signup_type"
                    class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                <option value="">Nincs (szabad részvétel)</option>
                <option value="user">Egyéni</option>
                <option value="team">Csapat</option>
                <option value="team_user">Egyéni vagy csapat</option>
            </select>
        </div>

        @if ($signup_type)
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Jelentkezési határidő</label>
                    <input wire:model="signup_deadline" type="datetime-local"
                           class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
                </div>
                <div>
                    <label class="block font-medium">Férőhelyek száma</label>
                    <input wire:model="capacity" type="number" min="1"
                           class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
                </div>
            </div>
        @endif

        <div class="flex gap-3">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove>Mentés</span>
                <span wire:loading>Mentés...</span>
            </button>
            <a href="{{ route('esemeny') }}"
               class="rounded border px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800">
                Mégse
            </a>
        </div>
    </form>
</div>
