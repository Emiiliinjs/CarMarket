@props(['active'])

@php
$classes = ($active ?? false)
            ? 'relative inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-indigo-600 via-purple-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900'
            : 'relative inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:text-slate-900 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:text-slate-300 dark:hover:text-white dark:focus-visible:ring-offset-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
