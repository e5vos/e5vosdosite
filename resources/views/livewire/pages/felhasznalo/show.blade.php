<?php
use App\Models\Attendance;
use App\Models\User;
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state(['shownUser' => null, 'activity' => [], 'permissions' => []]);

mount(function (int $userId) {
    $viewer   = auth()->user();
    $canSeeDetails = $viewer && (
        $viewer->id === $userId ||
        $viewer->hasPermission('TCH') ||
        $viewer->hasPermission('ADM')
    );

    $relations = ['teams'];
    if ($canSeeDetails) {
        $relations[] = 'permissions';
    }

    $this->shownUser = User::with($relations)->findOrFail($userId)->toArray();

    if ($canSeeDetails) {
        $this->activity = Attendance::with('event:id,name,slot_id', 'team:code,name')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->toArray();
        $this->permissions = $this->shownUser['permissions'] ?? [];
    }
});
?>

<div class="container mx-auto mt-4 max-w-2xl">
    @if ($shownUser)
        {{-- Profile header --}}
        <div class="mb-6 flex items-center gap-4">
            @if ($shownUser['img_url'])
                <img src="{{ $shownUser['img_url'] }}" alt=""
                     class="h-16 w-16 rounded-full object-cover" />
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-200 text-2xl dark:bg-gray-700">
                    {{ mb_substr($shownUser['name'], 0, 1) }}
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold">{{ $shownUser['name'] }}</h1>
                <p class="text-gray-500">{{ $shownUser['ejg_class'] }}</p>
                @if ($shownUser['e5code'])
                    <p class="font-mono text-xs text-gray-400">{{ $shownUser['e5code'] }}</p>
                @endif
            </div>
        </div>

        {{-- Teams --}}
        @if (!empty($shownUser['teams']))
            <div class="mb-6">
                <h2 class="mb-2 text-lg font-semibold">Csapatok</h2>
                <div class="space-y-2">
                    @foreach ($shownUser['teams'] as $team)
                        <a href="{{ route('csapat.show', $team['code']) }}"
                           class="flex items-center justify-between rounded-lg border p-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                            <span class="font-medium">{{ $team['name'] }}</span>
                            <span class="font-mono text-sm text-gray-400">{{ $team['code'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Permissions (visible to self, teachers, admins) --}}
        @if (!empty($permissions))
            <div class="mb-6">
                <h2 class="mb-2 text-lg font-semibold">Jogosultságok</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($permissions as $perm)
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            {{ $perm['code'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Activity --}}
        @if (!empty($activity))
            <div>
                <h2 class="mb-2 text-lg font-semibold">Tevékenység</h2>
                <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2">Esemény</th>
                                <th class="px-4 py-2">Csapat</th>
                                <th class="px-4 py-2">Jelen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activity as $att)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="px-4 py-2">
                                        @if ($att['event'])
                                            <a href="{{ route('esemeny.show', $att['event']['id']) }}"
                                               class="hover:underline">
                                                {{ $att['event']['name'] }}
                                            </a>
                                        @else
                                            –
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($att['team'])
                                            <a href="{{ route('csapat.show', $att['team']['code']) }}"
                                               class="hover:underline">
                                                {{ $att['team']['name'] }}
                                            </a>
                                        @else
                                            –
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="{{ $att['is_present'] ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ $att['is_present'] ? '✓' : '–' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
