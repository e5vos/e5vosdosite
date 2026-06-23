<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Permission;
use App\Models\User;
use App\Models\Event;
use App\Helpers\PermissionType;

layout('components.layouts.app');

state([
    'search'         => '',
    'searchResults'  => [],
    'selectedUser'   => null,
    'events'         => [],
    'newPermCode'    => '',
    'newPermEventId' => '',
]);

mount(function () {
    $this->events = Event::select('id', 'name')->get()->toArray();
});

$searchUsers = function () {
    $this->searchResults = User::where('name', 'like', "%{$this->search}%")
        ->limit(10)
        ->get(['id', 'name', 'ejg_class'])
        ->toArray();
};

$selectUser = function (int $userId) {
    $this->selectedUser = User::with('permissions.event:id,name')
        ->findOrFail($userId)
        ->toArray();
    $this->search = '';
    $this->searchResults = [];
};

$addPermission = function () {
    $this->authorize('create', Permission::class);
    Permission::firstOrCreate([
        'user_id'  => $this->selectedUser['id'],
        'event_id' => $this->newPermEventId ?: null,
        'code'     => $this->newPermCode,
    ]);
    $this->selectUser($this->selectedUser['id']);
    $this->newPermCode = '';
    $this->newPermEventId = '';
};

$removePermission = function (int $userId, ?int $eventId, string $code) {
    $this->authorize('destroy', Permission::class);
    Permission::where('user_id', $userId)
        ->where('event_id', $eventId)
        ->where('code', $code)
        ->delete();
    $this->selectUser($userId);
};
?>

<div class="container mx-auto mt-4">
    <h1 class="mb-6 text-2xl font-bold">Jogosultságok</h1>

    {{-- User search --}}
    <div class="mb-4 max-w-sm">
        <input wire:model.debounce.300ms="search" wire:change="searchUsers"
               type="text" placeholder="Felhasználó keresése..."
               class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
        @if (count($searchResults) > 0)
            <ul class="mt-1 rounded border bg-white shadow dark:bg-gray-800 dark:border-gray-600">
                @foreach ($searchResults as $result)
                    <li>
                        <button wire:click="selectUser({{ $result['id'] }})"
                                class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ $result['name'] }} ({{ $result['ejg_class'] }})
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    @if ($selectedUser)
        <div class="rounded-lg border p-4 dark:border-gray-700">
            <h2 class="mb-4 text-lg font-semibold">{{ $selectedUser['name'] }}</h2>

            {{-- Current permissions --}}
            <ul class="mb-4 space-y-2">
                @foreach ($selectedUser['permissions'] ?? [] as $perm)
                    <li class="flex items-center justify-between rounded border p-2 dark:border-gray-600">
                        <span>
                            <strong>{{ $perm['code'] }}</strong>
                            @if ($perm['event'])
                                – {{ $perm['event']['name'] }}
                            @endif
                        </span>
                        <button wire:click="removePermission({{ $selectedUser['id'] }}, {{ $perm['event_id'] ?? 'null' }}, '{{ $perm['code'] }}')"
                                class="rounded bg-red-500 px-2 py-1 text-sm text-white hover:bg-red-600">
                            Törlés
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Add permission --}}
            <div class="flex flex-wrap gap-2">
                <select wire:model="newPermCode"
                        class="rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                    <option value="">Jogosultság...</option>
                    @foreach (PermissionType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->value }}</option>
                    @endforeach
                </select>
                <select wire:model="newPermEventId"
                        class="rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                    <option value="">Globális (nincs esemény)</option>
                    @foreach ($events as $event)
                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                    @endforeach
                </select>
                <button wire:click="addPermission"
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Hozzáadás
                </button>
            </div>
        </div>
    @endif
</div>
