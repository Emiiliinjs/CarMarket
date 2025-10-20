<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CarMarket') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 transition-colors duration-500 dark:bg-slate-950 dark:text-slate-100">
    <div class="flex min-h-screen flex-col">
        {{-- Navigation --}}
        @include('layouts.navigation')

        {{-- Page Heading --}}
        @isset($header)
            <header class="border-b border-slate-200/70 bg-white/80 backdrop-blur-sm dark:border-slate-800/80 dark:bg-slate-900/60">
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-4 py-8 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-10 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        @if(auth()->user()?->is_admin)
            @php
                $toastNotifications = ($adminNotifications ?? collect())->map(fn ($notification) => [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'action_url' => $notification->action_url,
                    'created_at_human' => optional($notification->created_at)->diffForHumans(),
                ]);
            @endphp

            <div
                x-data="adminNotificationToasts({{ $toastNotifications->toJson(JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})"
                x-init="bootstrap({{ $adminNotificationCount ?? 0 }})"
                class="pointer-events-none fixed bottom-4 right-4 z-50 flex w-full max-w-sm flex-col gap-3"
            >
                <template x-if="visible.length === 0 && $store.adminNotifications.count > 0">
                    <div class="pointer-events-auto rounded-2xl border border-amber-200 bg-amber-50/95 p-4 text-sm text-amber-800 shadow-lg backdrop-blur">
                        <p>Jauni paziņojumi pieejami administratora panelī.</p>
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('admin.index') }}" class="text-xs font-semibold uppercase tracking-wide text-amber-700 hover:text-amber-900">Atvērt paneli</a>
                        </div>
                    </div>
                </template>

                <template x-for="item in visible" :key="item.id">
                    <div
                        class="pointer-events-auto flex flex-col gap-2 rounded-2xl border border-slate-200/80 bg-white/95 p-4 text-sm text-slate-700 shadow-lg backdrop-blur dark:border-slate-700/60 dark:bg-slate-900/90 dark:text-slate-200"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white" x-text="item.title"></p>
                                <p class="mt-1 text-sm" x-text="item.message"></p>
                            </div>
                            <button
                                type="button"
                                class="rounded-full p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800"
                                @click="dismiss(item.id)"
                            >
                                <span class="sr-only">Aizvērt paziņojumu</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L10 9.293l4.646-4.647a.5.5 0 0 1 .708.708L10.707 10l4.647 4.646a.5.5 0 0 1-.708.708L10 10.707l-4.646 4.647a.5.5 0 0 1-.708-.708L9.293 10 4.646 5.354a.5.5 0 0 1 0-.708Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                            <span x-text="item.created_at_human"></span>
                            <div class="flex items-center gap-2">
                                <template x-if="item.action_url">
                                    <a :href="item.action_url" class="font-semibold text-[#2B7A78] hover:text-[#22615F]">Atvērt</a>
                                </template>
                                <button type="button" class="font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" @click="dismiss(item.id)">Atzīmēt kā izlasītu</button>
                            </div>
                        </div>
                    </div>
                </template>

                <button
                    type="button"
                    x-cloak
                    x-show="visible.length > 1"
                    class="pointer-events-auto self-end rounded-full border border-slate-200/70 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600 shadow backdrop-blur transition hover:border-slate-300 hover:text-slate-800 dark:border-slate-700/70 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:border-slate-600"
                    @click="dismissAll()"
                >
                    Aizvērt visus
                </button>
            </div>
        @endif

        <footer class="mt-auto border-t border-slate-200/70 bg-white/80 px-4 py-6 text-center text-sm text-slate-500 backdrop-blur-sm dark:border-slate-800/70 dark:bg-slate-900/60 dark:text-slate-400 sm:px-6 lg:px-8">
        © {{ now()->year }} {{ config('CarMarket', 'CarMarket') }} — Tavs ceļš uz īsto auto.
        </footer>
    </div>
</body>
</html>
