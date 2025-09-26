<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div
        class="relative min-h-screen bg-slate-100 text-slate-900 transition-colors duration-500 dark:bg-slate-950 dark:text-slate-100"
    >
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-32 h-80 w-80 rounded-full bg-indigo-300/40 blur-3xl dark:bg-indigo-600/20"></div>
            <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-sky-200/40 blur-3xl dark:bg-sky-500/10"></div>
            <div class="absolute inset-y-0 left-1/2 w-px -translate-x-1/2 bg-gradient-to-b from-transparent via-indigo-200/60 to-transparent dark:via-indigo-500/20"></div>
        </div>

        <div class="relative z-10 flex min-h-screen flex-col">
            {{-- Navigation --}}
            @include('layouts.navigation')

            {{-- Page Heading --}}
            @isset($header)
                <header class="mx-auto w-full max-w-7xl px-4 pb-6 pt-10 sm:px-6 lg:px-8">
                    {{ $header }}
                </header>
            @endisset

            {{-- Page Content --}}
            <main class="relative flex-1 w-full max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 xl:pb-20 mx-auto">
                {{ $slot }}
            </main>

            <footer class="mt-auto border-t border-white/40 bg-white/70 px-4 py-6 text-center text-sm text-slate-500 backdrop-blur dark:border-slate-700/60 dark:bg-slate-900/70 dark:text-slate-400 sm:px-6 lg:px-8">
                © {{ now()->year }} {{ config('app.name', 'CarMarket') }}. Visi attēli tiek optimizēti Tailwind burvībā.
            </footer>
        </div>
    </div>
</body>
</html>
