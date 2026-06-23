<?php
use function Livewire\Volt\{layout, state, mount};
use App\Models\Event;

layout('components.layouts.app');

state(['event' => null, 'statusMessage' => '', 'isError' => false]);

mount(function (int $eventid) {
    $event = Event::findOrFail($eventid);
    $this->authorize('attend', $event);
    $this->event = $event;
});
?>

<div class="container mx-auto mt-4" x-data="scanner(@js($event?->id))">
    <h1 class="mb-4 text-2xl font-bold">Scanner – {{ $event?->name }}</h1>

    <div id="status-msg" x-text="statusMessage"
         class="mb-4 min-h-8 rounded px-4 py-2"
         :class="isError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
         x-show="statusMessage"></div>

    <div id="scanner-viewport" class="relative max-w-md overflow-hidden rounded border">
        <video id="scanner-video" class="w-full" autoplay playsinline></video>
        <canvas id="scanner-canvas" class="hidden"></canvas>
    </div>

    <p class="mt-2 text-sm text-gray-500">
        Tartsd a QR-kódot a kamera elé a jelenlét rögzítéséhez.
    </p>
</div>

{{-- Step 2: inline Alpine.js scanner component using jsQR + fetch to /api/event/{id}/attend --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('scanner', (eventId) => ({
        statusMessage: '',
        isError: false,
        // Step 2: camera init + jsQR scan loop + attend API call
    }));
});
</script>
