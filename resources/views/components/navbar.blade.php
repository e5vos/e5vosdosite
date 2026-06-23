@php
    $user = auth()->user();
    $isTeacher = $user?->hasPermission('TCH');
@endphp

<nav class="mb-2 rounded border-gray-200 bg-slate-50 px-2 pt-2 text-black dark:bg-gray-700 dark:text-white"
     x-data="{ mobileOpen: false, qrOpen: false }">
    <div class="container mx-auto flex flex-wrap items-center justify-between">
        {{-- Brand --}}
        <span
            class="flex cursor-pointer items-center"
            @click="qrOpen = true"
        >
            <img src="/donci.svg" alt="" class="mr-5 h-10 w-10 fill-black dark:fill-white" />
            <span class="self-center text-xl font-semibold whitespace-nowrap">
                {{ $user?->name ?? 'Eötvös DÖ' }}
            </span>
        </span>

        <div class="flex items-center">
            {{-- Desktop nav links --}}
            <ul class="hidden flex-row space-x-8 text-sm font-medium md:flex">
                @if ($isTeacher)
                    <li>
                        <a href="{{ route('eloadas.manage') }}"
                           class="block rounded py-2 pr-4 pl-3 text-black hover:bg-gray-100 md:p-0 md:hover:bg-inherit md:hover:text-gray-500 dark:text-white">
                            Előadások – Tanári
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('eloadas') }}"
                       class="block rounded py-2 pr-4 pl-3 text-black hover:bg-gray-100 md:p-0 md:hover:bg-inherit md:hover:text-gray-500 dark:text-white">
                        Előadásjelentkezés
                    </a>
                </li>
                @if (!$user)
                    <li>
                        <a href="{{ route('login', ['next' => request()->path()]) }}"
                           class="block rounded py-2 pr-4 pl-3 text-black hover:bg-gray-100 md:p-0 md:hover:bg-inherit md:hover:text-gray-500 dark:text-white">
                            Bejelentkezés
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('logout') }}"
                           class="block rounded py-2 pr-4 pl-3 text-black hover:bg-gray-100 md:p-0 md:hover:bg-inherit md:hover:text-gray-500 dark:text-white">
                            Kijelentkezés
                        </a>
                    </li>
                @endif
            </ul>

            {{-- Mobile hamburger --}}
            <button class="ml-3 inline-flex items-center justify-center rounded-lg hover:text-gray-900 focus:ring-2 focus:ring-blue-300 focus:outline-none md:hidden"
                    @click="mobileOpen = !mobileOpen">
                <svg class="h-8 w-8 fill-black dark:fill-white" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen" class="mt-2 w-full md:hidden">
        <ul class="flex flex-col rounded-lg border p-4 font-medium">
            @if ($isTeacher)
                <li>
                    <a href="{{ route('eloadas.manage') }}" class="block rounded py-2 px-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                        Előadások – Tanári
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('eloadas') }}" class="block rounded py-2 px-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                    Előadásjelentkezés
                </a>
            </li>
            @if (!$user)
                <li>
                    <a href="{{ route('login') }}" class="block rounded py-2 px-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                        Bejelentkezés
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('logout') }}" class="block rounded py-2 px-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                        Kijelentkezés
                    </a>
                </li>
            @endif
        </ul>
    </div>

    {{-- QR code modal (shows e5code) --}}
    @if ($user)
        <div x-show="qrOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
             @click.self="qrOpen = false">
            <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                <h2 class="mb-4 text-center text-lg font-semibold">
                    {{ $user->name }} ({{ $user->ejg_class }})
                </h2>
                @if ($user->e5code)
                    <p class="mt-2 rounded-full bg-slate-200 px-3 py-1 text-center font-mono dark:bg-gray-700">
                        {{ $user->e5code }}
                    </p>
                @else
                    <p class="text-red-500">Nincs E5 kódod :(</p>
                @endif
                <button @click="qrOpen = false"
                        class="mt-4 w-full rounded bg-slate-200 px-4 py-2 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                    Bezárás
                </button>
            </div>
        </div>
    @endif
</nav>
