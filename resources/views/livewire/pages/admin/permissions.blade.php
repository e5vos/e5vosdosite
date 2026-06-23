<?php
use App\Helpers\PermissionType;
use App\Models\Event;
use App\Models\Permission;
use App\Models\User;
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state([
    'search'         => '',
    'searchResults'  => [],
    'selectedUser'   => null,
    'events'         => [],
    'newPermCode'    => '',
    'newPermEventId' => '',
    'statusMsg'      => '',
]);

mount(function () {
    // Route middleware already enforces ADM; a double-check here adds defence-in-depth.
    abort_unless(auth()->user()?->hasPermission('ADM'), 403);
    $this->events = Event::select('id', 'name')->get()->toArray();
});

$searchUsers = function () {
    $this->searchResults = User::where('name', 'like', "%{$this->search}%")
        ->orWhere('ejg_class', 'like', "%{$this->search}%")
        ->limit(10)
        ->get(['id', 'name', 'ejg_class'])
        ->toArray();
};

$selectUser = function (int $userId) {
    $this->selectedUser = User::with(['permissions' => fn ($q) => $q->with('event:id,name')])
        ->findOrFail($userId)
        ->toArray();
    $this->search        = '';
    $this->searchResults = [];
    $this->statusMsg     = '';
};

$addPermission = function () {
    abort_unless(auth()->user()?->hasPermission('ADM'), 403);
    if (! $this->newPermCode) return;

    Permission::firstOrCreate([
        'user_id'  => $this->selectedUser['id'],
        'event_id' => $this->newPermEventId ?: null,
        'code'     => $this->newPermCode,
    ]);

    $this->selectUser($this->selectedUser['id']);
    $this->newPermCode    = '';
    $this->newPermEventId = '';
    $this->statusMsg      = 'Jogosultság hozzáadva.';
};

$removePermission = function (int $userId, ?int $eventId, string $code) {
    abort_unless(auth()->user()?->hasPermission('ADM'), 403);
    Permission::where('user_id', $userId)
        ->where('event_id', $eventId)
        ->where('code', $code)
        ->delete();
    $this->selectUser($userId);
    $this->statusMsg = 'Jogosultság törölve.';
};
?>

<div class="container mx-auto mt-4">
    <h1 class="mb-6 text-2xl font-bold">Jogosultságok</h1>

    @if ($statusMsg)
        <div class="mb-4 rounded bg-green-100 px-4 py-2 text-green-700 dark:bg-green-900/30 dark:text-green-400">
            {{ $statusMsg }}
        </div>
    @endif

    {{-- User search --}}
    <div class="mb-6 max-w-sm">
        <label class="mb-1 block font-medium">Felhasználó keresése</label>
        <div class="relative">
            <input wire:model.debounce.300ms="search"
                   wire:change="searchUsers"
                   type="text"
                   placeholder="Név vagy osztály..."
                   class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
            @if (count($searchResults) > 0)
                <ul class="absolute z-10 mt-1 w-full rounded border bg-white shadow-lg dark:bg-gray-800 dark:border-gray-600">
                    @foreach ($searchResults as $result)
                        <li>
                            <button wire:click="selectUser({{ $result['id'] }})"
                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ $result['name'] }}
                                <span class="text-sm text-gray-400">({{ $result['ejg_class'] }})</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Selected user --}}
    @if ($selectedUser)
        <div class="rounded-lg border p-5 dark:border-gray-700">
            <h2 class="mb-4 text-lg font-semibold">
                {{ $selectedUser['name'] }}
                <span class="text-sm font-normal text-gray-400">({{ $selectedUser['ejg_class'] }})</span>
            </h2>

            {{-- Current permissions --}}
            <div class="mb-5 space-y-2">
                @forelse ($selectedUser['permissions'] ?? [] as $perm)
                    <div class="flex items-center justify-between rounded border p-3 dark:border-gray-600">
                        <div>
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $perm['code'] }}
                            </span>
                            @if ($perm['event'])
                                <span class="ml-2 text-sm text-gray-500">– {{ $perm['event']['name'] }}</span>
                            @else
                                <span class="ml-2 text-xs text-gray-400">globális</span>
                            @endif
                        </div>
                        <button wire:click="removePermission({{ $selectedUser['id'] }}, {{ $perm['event_id'] ?? 'null' }}, '{{ $perm['code'] }}')"
                                class="rounded bg-red-100 px-2 py-1 text-sm text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300">
                            Törlés
                        </button>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Nincs jogosultsága.</p>
                @endforelse
            </div>

            {{-- Add permission form --}}
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="mb-1 block text-sm font-medium">Jogosultság</label>
                    <select wire:model="newPermCode"
                            class="rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                        <option value="">Válassz...</option>
                        @foreach (PermissionType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->value }} ({{ $type->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Esemény (opcionális)</label>
                    <select wire:model="newPermEventId"
                            class="rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600">
                        <option value="">Globális</option>
                        @foreach ($events as $event)
                            <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="addPermission"
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Hozzáadás
                </button>
            </div>
        </div>
    @endif
</div>
