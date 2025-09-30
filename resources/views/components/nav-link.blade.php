@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 border-b-2 border-indigo-500 px-3 py-2 text-sm font-semibold text-indigo-600 dark:text-indigo-300'
            : 'inline-flex items-center gap-2 border-b-2 border-transparent px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950']) }}>
    {{ $slot }}
</a>
