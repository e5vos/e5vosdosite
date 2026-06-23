<?php
use function Livewire\Volt\{layout, mount};
use Illuminate\Support\Facades\Auth;

layout('components.layouts.app');

mount(function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
});
?>

<div></div>
