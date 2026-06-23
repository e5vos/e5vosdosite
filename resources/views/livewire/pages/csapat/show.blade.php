<?php
use App\Exceptions\NotAllowedException;
use App\Helpers\MembershipType;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state([
    'team'          => null,
    'members'       => [],
    'activity'      => [],
    'inviteSearch'  => '',
    'inviteResults' => [],
    'statusMsg'     => '',
    'isError'       => false,
]);

mount(function (string $teamcode) {
    $this->loadTeam($teamcode);
});

$loadTeam = function (string $teamcode = null) {
    $code = $teamcode ?? $this->team['code'];
    $team = Team::with('members', 'activity.event:id,name')->where('code', $code)->firstOrFail();
    $this->team     = $team->toArray();
    $this->members  = $team->members->map(fn ($m) => array_merge($m->toArray(), ['role' => $m->pivot->role]))->toArray();
    $this->activity = $team->activity->toArray();
};

$isCurrentUserLeader = function (): bool {
    return collect($this->members)->contains(fn ($m) =>
        $m['id'] === auth()->id() && $m['role'] === MembershipType::Leader->value
    );
};

$canManage = function (): bool {
    $user = auth()->user();
    return $user && ($user->hasPermission('ADM') || $this->isCurrentUserLeader());
};

$promote = function (int $userId, bool $doPromote) {
    if (! $this->canManage()) abort(403);

    $teamCode    = $this->team['code'];
    $currentRole = collect($this->members)->firstWhere('id', $userId)['role'] ?? null;

    $kick = false;
    $newRole = null;

    match ($currentRole) {
        MembershipType::Leader->value  => $newRole = $doPromote ? null   : MembershipType::Member->value,
        MembershipType::Member->value  => $doPromote ? $newRole = MembershipType::Leader->value  : ($kick = true),
        MembershipType::Invited->value => $doPromote ? $newRole = MembershipType::Member->value  : ($kick = true),
        null                           => $doPromote ? $newRole = MembershipType::Invited->value : abort(400),
        default                        => abort(400),
    };

    if ($currentRole === MembershipType::Leader->value && $doPromote) {
        $this->statusMsg = 'Egy vezető nem léptethető elő.';
        $this->isError   = true;
        return;
    }

    if ($kick) {
        TeamMembership::where('team_code', $teamCode)->where('user_id', $userId)->delete();
        Cache::forget("user.{$userId}.teams");
    } else {
        $membership = TeamMembership::firstOrNew(['team_code' => $teamCode, 'user_id' => $userId]);
        $membership->role = $newRole;
        $membership->save();
    }

    Cache::forget("e5n.teams.all");
    Cache::forget("e5n.teams.{$teamCode}");
    foreach (collect($this->members)->pluck('id') as $mid) {
        Cache::forget("user.{$mid}.teams");
    }

    $this->loadTeam($teamCode);
    $this->statusMsg = 'Tagság frissítve.';
    $this->isError   = false;
};

$searchInvite = function () {
    $this->inviteResults = User::where('name', 'like', "%{$this->inviteSearch}%")
        ->whereDoesntHave('teams', fn ($q) => $q->where('team_memberships.team_code', $this->team['code']))
        ->limit(8)
        ->get(['id', 'name', 'ejg_class'])
        ->toArray();
};

$inviteUser = function (int $userId) {
    if (! $this->canManage()) abort(403);
    $teamCode   = $this->team['code'];
    $membership = TeamMembership::firstOrNew(['team_code' => $teamCode, 'user_id' => $userId]);
    $membership->role = MembershipType::Invited->value;
    $membership->save();
    Cache::forget("e5n.teams.{$teamCode}");
    Cache::forget("user.{$userId}.teams");
    $this->inviteSearch  = '';
    $this->inviteResults = [];
    $this->loadTeam($teamCode);
    $this->statusMsg = 'Meghívó elküldve.';
    $this->isError   = false;
};

$acceptInvite = function () {
    $teamCode = $this->team['code'];
    $membership = TeamMembership::where('team_code', $teamCode)
        ->where('user_id', auth()->id())
        ->where('role', MembershipType::Invited->value)
        ->first();
    if ($membership) {
        $membership->role = MembershipType::Member->value;
        $membership->save();
        Cache::forget("e5n.teams.{$teamCode}");
        Cache::forget('user.'.auth()->id().'.teams');
        $this->loadTeam($teamCode);
        $this->statusMsg = 'Csatlakoznál a csapathoz.';
        $this->isError   = false;
    }
};
?>

<div class="container mx-auto mt-4 max-w-2xl">
    @if ($team)
        {{-- Header --}}
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $team['name'] }}</h1>
                <p class="font-mono text-sm text-gray-500">{{ $team['code'] }}</p>
            </div>
            {{-- QR code modal --}}
            <div x-data="{ open: false }">
                <button @click="open = true"
                        class="rounded border px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800">
                    QR-kód
                </button>
                <div x-show="open" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                     @click.self="open = false">
                    <div class="rounded-lg bg-white p-6 text-center shadow-xl dark:bg-gray-800">
                        <h2 class="mb-4 font-semibold">{{ $team['name'] }}</h2>
                        <p class="rounded-full bg-slate-200 px-4 py-2 font-mono text-lg dark:bg-gray-700">
                            {{ $team['code'] }}
                        </p>
                        <button @click="open = false" class="mt-4 rounded bg-gray-200 px-4 py-2 dark:bg-gray-700">
                            Bezárás
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status message --}}
        @if ($statusMsg)
            <div class="mb-4 rounded px-4 py-3 {{ $isError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $statusMsg }}
            </div>
        @endif

        {{-- My invitation --}}
        @auth
            @php
                $myMembership = collect($members)->firstWhere('id', auth()->id());
            @endphp
            @if ($myMembership && $myMembership['role'] === \App\Helpers\MembershipType::Invited->value)
                <div class="mb-4 rounded-lg border border-blue-300 bg-blue-50 p-4 dark:border-blue-700 dark:bg-blue-900/20">
                    <p class="mb-2">Meghívót kaptál ebbe a csapatba.</p>
                    <button wire:click="acceptInvite"
                            class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Elfogadás
                    </button>
                </div>
            @endif
        @endauth

        {{-- Members --}}
        <h2 class="mb-3 text-lg font-semibold">Tagok</h2>
        <div class="mb-6 space-y-2">
            @foreach ($members as $member)
                <div class="flex items-center justify-between rounded-lg border p-3 dark:border-gray-700">
                    <div>
                        <a href="{{ route('felhasznalo.show', $member['id']) }}"
                           class="font-medium hover:underline">{{ $member['name'] }}</a>
                        <span class="ml-2 text-sm text-gray-500">{{ $member['ejg_class'] }}</span>
                        <span class="ml-2 rounded bg-gray-100 px-2 py-0.5 text-xs dark:bg-gray-700">
                            {{ $member['role'] }}
                        </span>
                    </div>
                    @if ($this->canManage() && $member['id'] !== auth()->id())
                        <div class="flex gap-1">
                            @if ($member['role'] !== \App\Helpers\MembershipType::Leader->value)
                                <button wire:click="promote({{ $member['id'] }}, true)"
                                        class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300">
                                    ↑ Előlép
                                </button>
                            @endif
                            <button wire:click="promote({{ $member['id'] }}, false)"
                                    class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300">
                                {{ $member['role'] === \App\Helpers\MembershipType::Invited->value ? 'Visszavon' : '↓ Visszafokoz / Kirúg' }}
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Invite --}}
        @if ($this->canManage())
            <div class="mb-6">
                <h3 class="mb-2 font-semibold">Meghívás</h3>
                <div class="relative">
                    <input wire:model.debounce.300ms="inviteSearch"
                           wire:change="searchInvite"
                           type="text"
                           placeholder="Felhasználó neve..."
                           class="w-full rounded border px-3 py-2 dark:bg-gray-800 dark:border-gray-600" />
                    @if (count($inviteResults) > 0)
                        <ul class="absolute z-10 mt-1 w-full rounded border bg-white shadow-lg dark:bg-gray-800 dark:border-gray-600">
                            @foreach ($inviteResults as $result)
                                <li>
                                    <button wire:click="inviteUser({{ $result['id'] }})"
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
        @endif

        {{-- Activity --}}
        @if (count($activity) > 0)
            <h2 class="mb-3 text-lg font-semibold">Tevékenység</h2>
            <ul class="space-y-2">
                @foreach ($activity as $att)
                    <li class="flex items-center justify-between rounded border p-3 dark:border-gray-700">
                        <span>{{ $att['event']['name'] ?? '–' }}</span>
                        <span class="{{ $att['is_present'] ? 'text-green-600' : 'text-gray-400' }} text-sm">
                            {{ $att['is_present'] ? 'Jelen volt' : 'Nem volt jelen' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>
