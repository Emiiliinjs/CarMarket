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

        <footer class="mt-auto border-t border-slate-200/70 bg-white/80 px-4 py-6 text-center text-sm text-slate-500 backdrop-blur-sm dark:border-slate-800/70 dark:bg-slate-900/60 dark:text-slate-400 sm:px-6 lg:px-8">
            © {{ now()->year }} {{ config('app.name', 'CarMarket') }} auto pirkšanai un pārdošanai — ērti, droši, saprotami.
        </footer>
    </div>
</body>
</html>
