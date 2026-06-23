<?php
use function Livewire\Volt\{layout};

layout('components.layouts.app');
?>

<div class="container mx-auto mt-8 text-center">
    <h1 class="text-3xl font-bold">Eötvös DÖ</h1>
    <p class="mt-4 text-lg">Üdvözlünk az Eötvös DÖ oldalán!</p>
    @guest
        <a href="{{ route('login') }}"
           class="mt-6 inline-block rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
            Bejelentkezés
        </a>
    @endguest
    @auth
        <a href="{{ route('eloadas') }}"
           class="mt-6 inline-block rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
            Előadásjelentkezés
        </a>
    @endauth
</div>
