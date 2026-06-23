<!DOCTYPE html>
<html lang="hu" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Eötvös DÖ' }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="bg-white text-black dark:bg-gray-900 dark:text-white">
    <x-navbar />
    <main class="px-2">
        {{ $slot }}
    </main>
    <x-footer />
    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
