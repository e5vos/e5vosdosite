<?php
use App\Exceptions\InvalidCodeException;
use App\Services\E5CodeService;
use function Livewire\Volt\{layout, state, mount, rules};

layout('components.layouts.app');

state(['studentCode' => '', 'error' => '', 'next' => '']);

mount(function () {
    $this->next = request()->query('next', '/eloadas');
    if (auth()->user()?->e5code) {
        return redirect($this->next);
    }
});

rules(['studentCode' => ['required', 'regex:/^20(\d{2})([A-FN])(\d{2})EJG(\d{3})$/']]);

$submit = function () {
    $this->validate();
    $this->error = '';
    try {
        E5CodeService::setCode(auth()->user(), $this->studentCode);
        return redirect($this->next);
    } catch (InvalidCodeException) {
        $this->error = 'Érvénytelen EJG diákkód.';
    }
};
?>

<div class="container mx-auto mt-8 max-w-sm">
    <h1 class="mb-6 text-2xl font-bold">E5 kód megadása</h1>
    <p class="mb-4 text-gray-600 dark:text-gray-400">
        Add meg az EJG-s diákkódodat a programokra való jelentkezéshez.
    </p>

    @if ($error)
        <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-700 dark:bg-red-900/30 dark:text-red-400">
            {{ $error }}
        </div>
    @endif

    <form wire:submit="submit">
        <label class="mb-1 block font-medium">E5 kód</label>
        <input wire:model="studentCode"
               type="text"
               placeholder="pl. 2022A01EJG001"
               class="w-full rounded border px-3 py-2 font-mono dark:bg-gray-800 dark:border-gray-600" />
        @error('studentCode')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">Érvénytelen kódformátum.</p>
        @enderror
        <button type="submit"
                wire:loading.attr="disabled"
                class="mt-4 w-full rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
            <span wire:loading.remove>Mentés</span>
            <span wire:loading>Ellenőrzés...</span>
        </button>
    </form>
</div>
