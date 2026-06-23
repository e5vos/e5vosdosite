<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Event;

layout('components.layouts.app');

state(['event' => null, 'participants' => []]);

mount(function (int $eventid) {
    $this->event = Event::with('slot', 'location')->findOrFail($eventid);
    // Step 2: load participants if authorized
});

$signup = function () {
    $this->authorize('signup', $this->event);
    // Step 2: sign up logic
};

$cancelSignup = function () {
    $this->authorize('unsignup', $this->event);
    // Step 2: cancel signup logic
};

$deleteEvent = function () {
    $this->authorize('delete', $this->event);
    // Step 2: delete event
    return redirect()->route('esemeny');
};
?>

<div class="container mx-auto mt-4">
    @if ($event)
        <h1 class="text-2xl font-bold">{{ $event->name }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $event->description }}</p>
        <p class="mt-1 text-sm text-gray-500">
            Helyszín: {{ $event->location?->name }} | Sáv: {{ $event->slot?->name }}
        </p>

        <div class="mt-4 flex gap-2">
            @auth
                @can('signup', $event)
                    <button wire:click="signup"
                            class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        Jelentkezés
                    </button>
                @endcan
                @can('update', $event)
                    <a href="{{ route('esemeny.edit', $event->id) }}"
                       class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                        Szerkesztés
                    </a>
                @endcan
                @can('delete', $event)
                    <button wire:click="deleteEvent"
                            wire:confirm="Biztosan törlöd az eseményt?"
                            class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        Törlés
                    </button>
                @endcan
            @endauth
        </div>

        {{-- Step 2: participants list, attendance sheet link --}}
    @endif
</div>
