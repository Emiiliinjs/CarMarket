@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#2B7A78] dark:focus:border-[#2B7A78] focus:ring-[#2B7A78] dark:focus:ring-[#2B7A78] rounded-md shadow-sm']) }}>
