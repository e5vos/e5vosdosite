<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\User;

layout('components.layouts.app');

state(['shownUser' => null]);

mount(function (int $userId) {
    $viewer = auth()->user();
    $relations = ['teams'];

    if ($viewer && (
        $viewer->id === $userId ||
        $viewer->hasPermission('TCH') ||
        $viewer->hasPermission('ADM')
    )) {
        $relations[] = 'permissions';
        $relations[] = 'userActivity';
    }

    $this->shownUser = User::with($relations)->findOrFail($userId);
});
?>

<div class="container mx-auto mt-4 max-w-2xl">
    @if ($shownUser)
        <div class="flex items-center gap-4">
            @if ($shownUser['img_url'])
                <img src="{{ $shownUser['img_url'] }}" alt=""
                     class="h-16 w-16 rounded-full object-cover" />
            @endif
            <div>
                <h1 class="text-2xl font-bold">{{ $shownUser['name'] }}</h1>
                <p class="text-gray-500">{{ $shownUser['ejg_class'] }}</p>
            </div>
        </div>

        @if (!empty($shownUser['teams']))
            <h2 class="mt-6 mb-2 text-lg font-semibold">Csapatok</h2>
            <ul class="space-y-2">
                @foreach ($shownUser['teams'] as $team)
                    <li>
                        <a href="{{ route('csapat.show', $team['code']) }}"
                           class="text-blue-600 hover:underline dark:text-blue-400">
                            {{ $team['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Step 2: activity list, permissions display --}}
    @endif
</div>
