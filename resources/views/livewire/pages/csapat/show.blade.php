<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Team;

layout('components.layouts.app');

state(['team' => null]);

mount(function (string $teamcode) {
    $this->team = Team::with('members')->where('code', $teamcode)->firstOrFail();
});
?>

<div class="container mx-auto mt-4">
    @if ($team)
        <h1 class="mb-2 text-2xl font-bold">{{ $team['name'] }}</h1>
        <p class="mb-4 text-sm text-gray-500">Kód: {{ $team['code'] }}</p>

        <h2 class="mb-2 text-lg font-semibold">Tagok</h2>
        <ul class="space-y-2">
            @foreach ($team['members'] ?? [] as $member)
                <li class="rounded-lg border p-3 dark:border-gray-700">
                    {{ $member['name'] }}
                    <span class="text-sm text-gray-500">({{ $member['ejg_class'] }})</span>
                </li>
            @endforeach
        </ul>

        {{-- Step 2: promote member, team admin actions --}}
    @endif
</div>
