<?php
use App\Models\Event;
use App\Models\Location;
use App\Models\Slot;
use App\Services\EventCacheService;
use function Livewire\Volt\{layout, state, mount, rules};

layout('components.layouts.app');

state([
    'event' => null,
    'name' => '', 'description' => '', 'organiser' => '',
    'starts_at' => '', 'ends_at' => '', 'signup_deadline' => '',
    'signup_type' => '', 'location_id' => '', 'capacity' => '',
    'slot_id' => '',
    'locations' => [], 'slots' => [],
]);

mount(function (int $eventid) {
    $event = Event::with('slot', 'location')->findOrFail($eventid);
    $this->authorize('update', $event);

    $this->event          = $event;
    $this->name           = $event->name;
    $this->description    = $event->description ?? '';
    $this->organiser      = $event->organiser   ?? '';
    $this->slot_id        = $event->slot_id;
    $this->location_id    = $event->location_id ?? '';
    $this->capacity       = $event->capacity    ?? '';
    $this->signup_type    = $event->signup_type ?? '';
    $this->starts_at      = $event->starts_at?->format('Y-m-d\TH:i') ?? '';
    $this->ends_at        = $event->ends_at?->format('Y-m-d\TH:i')   ?? '';
    $this->signup_deadline = $event->signup_deadline?->format('Y-m-d\TH:i') ?? '';

    $this->locations = Location::all(['id', 'name'])->toArray();
    $this->slots     = Slot::all(['id', 'name', 'starts_at', 'ends_at'])->toArray();
});

rules(['name' => 'required|string|max:255']);

$save = function () {
    $this->authorize('update', $this->event);
    $this->validate();

    $slot = Slot::find($this->slot_id) ?? $this->event->slot;

    $startsAt = $this->starts_at ?: null;
    $endsAt   = $this->ends_at   ?: null;

    if ($slot && $startsAt && $startsAt < $slot->starts_at) {
        $startsAt = $slot->starts_at;
    }
    if ($slot && $endsAt && $endsAt > $slot->ends_at) {
        $endsAt = $slot->ends_at;
    }

    $signupDeadline = null;
    if ($this->signup_type) {
        $signupDeadline = $this->signup_deadline ?: $slot?->starts_at;
    }

    $this->event->name            = $this->name;
    $this->event->description     = $this->description;
    $this->event->organiser       = $this->organiser;
    $this->event->slot_id         = $this->slot_id;
    $this->event->location_id     = $this->location_id ?: null;
    $this->event->signup_type     = $this->signup_type ?: null;
    $this->event->capacity        = $this->capacity    ?: null;
    $this->event->signup_deadline = $signupDeadline;
    if ($startsAt) $this->event->starts_at = $startsAt;
    if ($endsAt)   $this->event->ends_at   = $endsAt;
    $this->event->save();

    EventCacheService::forgetEvent($this->event->id, $this->event->slot_id);

    return redirect()->route('esemeny.show', $this->event->id);
};

$closeSignup = function () {
    $this->authorize('update', $this->event);
    $this->event->signup_deadline = now();
    $this->event->save();
    EventCacheService::forgetEvent($this->event->id, $this->event->slot_id);
    $this->signup_deadline = now()->format('Y-m-d\TH:i');
};
?>

<div class="container mx-auto mt-4 max-w-2xl">
    <h1 class="mb-6 text-2xl font-bold">Esemény szerkesztése</h1>

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
            <label class="block font-medium">Sáv</label>
            <select wire:model="slot_id"
                    class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                @foreach ($slots as $slot)
                    <option value="{{ $slot['id'] }}"
                            {{ (string) $slot_id === (string) $slot['id'] ? 'selected' : '' }}>
                        {{ $slot['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Helyszín</label>
            <select wire:model="location_id"
                    class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                <option value="">Nincs megadva</option>
                @foreach ($locations as $location)
                    <option value="{{ $location['id'] }}"
                            {{ (string) $location_id === (string) $location['id'] ? 'selected' : '' }}>
                        {{ $location['name'] }}
                    </option>
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
                <option value="user"      {{ $signup_type === 'user'      ? 'selected' : '' }}>Egyéni</option>
                <option value="team"      {{ $signup_type === 'team'      ? 'selected' : '' }}>Csapat</option>
                <option value="team_user" {{ $signup_type === 'team_user' ? 'selected' : '' }}>Egyéni vagy csapat</option>
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

            <div>
                <button type="button" wire:click="closeSignup"
                        class="rounded border border-orange-400 px-4 py-2 text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20">
                    Jelentkezés azonnali lezárása
                </button>
            </div>
        @endif

        <div class="flex gap-3">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove>Mentés</span>
                <span wire:loading>Mentés...</span>
            </button>
            <a href="{{ route('esemeny.show', $event?->id) }}"
               class="rounded border px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800">
                Mégse
            </a>
        </div>
    </form>
</div>
