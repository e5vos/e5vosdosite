<?php
use function Livewire\Volt\{layout, mount};

layout('components.layouts.app');

mount(function () {
    abort_unless(auth()->user()?->hasPermission('ADM'), 403);
});

$clearCache = function () {
    \Illuminate\Support\Facades\Cache::flush();
};
?>

<div class="container mx-auto mt-4">
    <h1 class="mb-6 text-2xl font-bold">Admin</h1>

    <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.sav') }}"
           class="rounded-lg border p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
            Sávok kezelése
        </a>
        <a href="{{ route('admin.permissions') }}"
           class="rounded-lg border p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
            Jogosultságok
        </a>
    </div>

    <div class="mt-8" x-data="{ open: false }">
        <button @click="open = true"
                class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
            Cache ürítése
        </button>
        <div x-show="open" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
             @click.self="open = false">
            <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                <p class="mb-4">Biztosan törlöd a cache-t?</p>
                <div class="flex gap-2">
                    <button @click="open = false; $wire.clearCache()"
                            class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        Igen
                    </button>
                    <button @click="open = false"
                            class="rounded bg-gray-200 px-4 py-2 hover:bg-gray-300 dark:bg-gray-700">
                        Mégse
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
