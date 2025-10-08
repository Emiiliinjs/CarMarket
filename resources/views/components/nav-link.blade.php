@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 border-b-2 border-[#2B7A78] px-3 py-2 text-sm font-semibold text-[#2B7A78]'
            : 'inline-flex items-center gap-2 border-b-2 border-transparent px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#2B7A78]/40 hover:text-[#2B7A78] dark:text-slate-300 dark:hover:border-[#2B7A78]/50 dark:hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2B7A78]/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950']) }}>
    {{ $slot }}
</a>
