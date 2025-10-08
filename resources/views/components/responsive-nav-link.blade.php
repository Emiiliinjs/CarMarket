@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-lg border border-[#2B7A78]/30 bg-[#2B7A78]/10 px-4 py-2 text-sm font-semibold text-[#2B7A78] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2B7A78]/50 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:border-[#2B7A78]/40 dark:bg-[#2B7A78]/20 dark:text-[#2B7A78] dark:focus-visible:ring-offset-slate-950'
            : 'block w-full rounded-lg border border-transparent px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#2B7A78]/40 hover:bg-white hover:text-[#2B7A78] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2B7A78]/50 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:text-slate-300 dark:hover:border-[#2B7A78]/50 dark:hover:bg-slate-900/60 dark:hover:text-white dark:focus-visible:ring-offset-slate-950';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
