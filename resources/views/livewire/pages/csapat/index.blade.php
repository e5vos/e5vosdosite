<?php
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state(['myTeams' => [], 'allTeams' => []]);

mount(function () {
    $this->allTeams = \App\Models\Team::all()->toArray();
    if (auth()->check()) {
        $this->myTeams = auth()->user()->teams->toArray();
    }
});
?>

<div class="container mx-auto mt-4">
    <h1 class="mb-4 text-2xl font-bold">Csapatok</h1>

    @if (count($myTeams) > 0)
        <h2 class="mb-2 text-lg font-semibold">Saját csapataim</h2>
        <div class="mb-6 grid gap-4 md:grid-cols-2">
            @foreach ($myTeams as $team)
                <a href="{{ route('csapat.show', $team['code']) }}"
                   class="block rounded-lg border p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                    <h3 class="font-semibold">{{ $team['name'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $team['code'] }}</p>
                </a>
            @endforeach
        </div>
    @endif

    <h2 class="mb-2 text-lg font-semibold">Összes csapat</h2>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($allTeams as $team)
            <a href="{{ route('csapat.show', $team['code']) }}"
               class="block rounded-lg border p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                <h3 class="font-semibold">{{ $team['name'] }}</h3>
                <p class="text-sm text-gray-500">{{ $team['code'] }}</p>
            </a>
        @endforeach
    </div>
</div>
