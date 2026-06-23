<?php
use App\Models\Event;
use function Livewire\Volt\{layout, state, mount};

layout('components.layouts.app');

state(['event' => null]);

mount(function (int $eventid) {
    $event = Event::with('slot')->findOrFail($eventid);
    $this->authorize('attend', $event);
    $this->event = $event->toArray();
});
?>

<div class="container mx-auto mt-4 max-w-xl" x-data="scanner({{ $event['id'] ?? 'null' }})">
    <h1 class="mb-2 text-2xl font-bold">Scanner</h1>
    <p class="mb-4 text-gray-500">{{ $event['name'] ?? '' }}</p>

    {{-- Status display --}}
    <div x-show="statusMessage"
         x-transition
         class="mb-4 rounded-lg px-4 py-3 text-sm font-medium"
         :class="isError ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'"
         x-text="statusMessage"></div>

    {{-- Camera viewport --}}
    <div class="relative overflow-hidden rounded-lg border dark:border-gray-700">
        <video id="scanner-video" class="w-full" autoplay playsinline muted></video>
        <canvas id="scanner-canvas" class="hidden"></canvas>
        <div x-show="!cameraReady"
             class="absolute inset-0 flex items-center justify-center bg-gray-900/80 text-white">
            <span x-text="cameraError || 'Kamera indítása...'"></span>
        </div>
        {{-- Cooldown overlay --}}
        <div x-show="cooldown"
             class="absolute inset-0 flex items-center justify-center bg-black/30">
            <div class="rounded-full bg-white/90 px-4 py-2 text-sm font-bold text-gray-800">
                ⏳
            </div>
        </div>
    </div>

    <p class="mt-3 text-sm text-gray-500">
        Tartsd a QR-kódot a kamera elé. A szkennelés automatikus.
    </p>

    {{-- Team member attendance dialog --}}
    <div x-show="teamDialog.open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-2xl dark:bg-gray-800">
            <h2 class="mb-4 text-lg font-bold">Csapat tagok jelenléte</h2>
            <p class="mb-4 text-sm text-gray-500" x-text="'Csapat: ' + teamDialog.teamName"></p>
            <div class="mb-4 space-y-2">
                <template x-for="(member, idx) in teamDialog.members" :key="member.user_id">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 dark:border-gray-600">
                        <input type="checkbox" x-model="teamDialog.members[idx].is_present"
                               class="h-4 w-4 rounded" />
                        <span x-text="member.name" class="font-medium"></span>
                        <span x-text="member.ejg_class" class="text-sm text-gray-400"></span>
                    </label>
                </template>
            </div>
            <div class="flex gap-2">
                <button @click="submitTeamAttendance()"
                        class="flex-1 rounded bg-green-600 py-2 text-white hover:bg-green-700">
                    Mentés
                </button>
                <button @click="teamDialog.open = false"
                        class="flex-1 rounded border py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Mégse
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('scanner', (eventId) => ({
        statusMessage: '',
        isError: false,
        cameraReady: false,
        cameraError: '',
        cooldown: false,
        animFrame: null,
        teamDialog: {
            open: false,
            teamName: '',
            attendanceId: null,
            members: [],
        },

        async init() {
            if (!eventId) return;
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' }
                });
                const video = document.getElementById('scanner-video');
                video.srcObject = stream;
                await video.play();
                this.cameraReady = true;
                this.startScan();
            } catch (e) {
                this.cameraError = 'Kamera hozzáférés megtagadva.';
            }
        },

        destroy() {
            if (this.animFrame) cancelAnimationFrame(this.animFrame);
            const video = document.getElementById('scanner-video');
            video?.srcObject?.getTracks().forEach(t => t.stop());
        },

        startScan() {
            const video  = document.getElementById('scanner-video');
            const canvas = document.getElementById('scanner-canvas');
            const ctx    = canvas.getContext('2d');

            const tick = () => {
                if (video.readyState >= video.HAVE_ENOUGH_DATA) {
                    canvas.width  = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const img  = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
                    if (code && code.data && !this.cooldown) {
                        this.handleScan(code.data);
                    }
                }
                this.animFrame = requestAnimationFrame(tick);
            };
            this.animFrame = requestAnimationFrame(tick);
        },

        getCsrf() {
            const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
            return match ? decodeURIComponent(match[1]) : '';
        },

        async handleScan(data) {
            this.cooldown      = true;
            this.statusMessage = '';

            try {
                const resp = await fetch(`/api/event/${eventId}/attend`, {
                    method: 'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'X-XSRF-TOKEN':  this.getCsrf(),
                        'Accept':        'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({ attender: data }),
                });

                if (!resp.ok) {
                    const err = await resp.json().catch(() => ({}));
                    this.statusMessage = err.message || `Hiba (${resp.status})`;
                    this.isError       = true;
                } else {
                    const result = await resp.json();

                    // Team attendance — show member dialog
                    if (result.teamMemberAttendances !== undefined) {
                        this.openTeamDialog(result);
                        return; // keep cooldown until dialog is closed
                    }

                    // Individual attendance
                    this.statusMessage = result.is_present
                        ? '✓ Jelenlét rögzítve'
                        : '✗ Hiányzás rögzítve';
                    this.isError = false;
                }
            } catch (e) {
                this.statusMessage = 'Hálózati hiba.';
                this.isError       = true;
            }

            setTimeout(() => { this.cooldown = false; }, 2500);
        },

        openTeamDialog(attendance) {
            // attendance.team.members contains all members
            const nonInvited = (attendance.team?.members || []).filter(
                m => m.pivot?.role !== 'meghívott'
            );
            // Pre-populate with existing teamMemberAttendances
            const existingMap = {};
            for (const tma of attendance.teamMemberAttendances || []) {
                existingMap[tma.user_id] = tma.is_present;
            }

            this.teamDialog = {
                open: true,
                teamName: attendance.team?.name || '',
                attendanceId: attendance.id,
                members: nonInvited.map(m => ({
                    user_id:   m.id,
                    name:      m.name,
                    ejg_class: m.ejg_class,
                    is_present: existingMap[m.id] ?? true,
                })),
            };
        },

        async submitTeamAttendance() {
            try {
                const resp = await fetch(`/api/attendance/${this.teamDialog.attendanceId}/teamMemberAttend`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': this.getCsrf(),
                        'Accept':       'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        memberAttendances: JSON.stringify(this.teamDialog.members)
                    }),
                });

                if (resp.ok) {
                    this.statusMessage = '✓ Csapat jelenlét rögzítve';
                    this.isError       = false;
                } else {
                    const err = await resp.json().catch(() => ({}));
                    this.statusMessage = err.message || 'Hiba a mentéskor.';
                    this.isError       = true;
                }
            } catch (e) {
                this.statusMessage = 'Hálózati hiba.';
                this.isError       = true;
            }

            this.teamDialog.open = false;
            setTimeout(() => { this.cooldown = false; }, 2500);
        },
    }));
});
</script>
