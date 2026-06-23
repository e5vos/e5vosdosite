<?php
use function Livewire\Volt\{layout, state, mount};
use Laravel\Socialite\Facades\Socialite;

layout('components.layouts.app');

state(['next' => '']);

mount(function () {
    $this->next = request()->query('next', '/eloadas');
    if (auth()->check()) {
        return redirect($this->next);
    }
});

$login = function () {
    session(['url.intended' => $this->next]);
    return redirect(Socialite::driver('google_web')->redirect()->getTargetUrl());
};
?>

<div class="container mx-auto mt-16 max-w-sm text-center">
    <h1 class="mb-8 text-2xl font-bold">Bejelentkezés</h1>
    <button wire:click="login"
            class="w-full rounded bg-blue-600 px-6 py-3 text-white hover:bg-blue-700">
        Bejelentkezés Google-fiókkal
    </button>
</div>
