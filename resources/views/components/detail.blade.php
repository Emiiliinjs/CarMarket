@props(['label', 'value' => '—'])

<div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3 dark:bg-gray-800/60">
    <dt class="font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
    <dd class="font-semibold text-gray-900 dark:text-white">{{ $value }}</dd>
</div>
