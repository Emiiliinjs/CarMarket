@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900'
            : 'block w-full rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-white hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:text-slate-300 dark:hover:bg-slate-900/60 dark:hover:text-white dark:focus-visible:ring-offset-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
