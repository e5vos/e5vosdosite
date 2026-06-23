<?php
use function Livewire\Volt\{layout, state, computed};

layout('components.layouts.app');

state(['search' => '']);

$events = computed(function () {
    return \App\Models\Event::with('slot', 'location')
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
        ->get();
});
?>

<div class="container mx-auto mt-4">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Események</h1>
        @if (auth()->user()?->hasPermission('ADM'))
            <a href="{{ route('esemeny.create') }}"
               class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Új esemény
            </a>
        @endif
    </div>

    <input wire:model.debounce.300ms="search"
           type="text"
           placeholder="Keresés..."
           class="mb-4 w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->events as $event)
            <a href="{{ route('esemeny.show', $event->id) }}"
               class="block rounded-lg border p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                <h2 class="font-semibold">{{ $event->name }}</h2>
                <p class="text-sm text-gray-500">{{ $event->location?->name }}</p>
            </a>
        @endforeach
    </div>
</div>
