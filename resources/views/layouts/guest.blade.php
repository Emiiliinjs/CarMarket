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
    <body class="font-sans antialiased">
        <div class="relative min-h-screen bg-slate-100 text-slate-900 transition-colors duration-500 dark:bg-slate-950 dark:text-slate-100">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -top-24 -left-32 h-80 w-80 rounded-full bg-indigo-300/40 blur-3xl dark:bg-indigo-600/20"></div>
                <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-sky-200/40 blur-3xl dark:bg-sky-500/10"></div>
                <div class="absolute inset-y-0 left-1/2 w-px -translate-x-1/2 bg-gradient-to-b from-transparent via-indigo-200/60 to-transparent dark:via-indigo-500/20"></div>
            </div>

            <div class="relative z-10 flex min-h-screen flex-col">
                @include('layouts.navigation')

                <main class="flex flex-1 items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
                    <div class="w-full max-w-md">
                        <div class="mb-8 flex justify-center">
                            <a href="/" class="flex items-center gap-3 rounded-full bg-white/70 px-4 py-2 shadow ring-1 ring-white/60 transition hover:-translate-y-0.5 hover:shadow-md dark:bg-slate-900/60 dark:ring-slate-700/60">
                                <x-application-logo class="h-12 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
                                <span class="hidden text-sm font-semibold tracking-wide text-slate-700 dark:text-slate-200 sm:inline">{{ config('app.name', 'CarMarket') }}</span>
                            </a>
                        </div>

                        <div class="rounded-3xl border border-white/60 bg-white/80 p-8 shadow-xl backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/70">
                            {{ $slot }}
                        </div>
                    </div>
                </main>

                <footer class="mt-auto border-t border-white/40 bg-white/70 px-4 py-6 text-center text-sm text-slate-500 backdrop-blur dark:border-slate-700/60 dark:bg-slate-900/70 dark:text-slate-400 sm:px-6 lg:px-8">
                    © {{ now()->year }} {{ config('app.name', 'CarMarket') }}. Visi attēli tiek optimizēti Tailwind burvībā.
                </footer>
            </div>
        </div>
    </body>
</html>
