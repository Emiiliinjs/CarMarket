<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kopējais sludinājumu skaits</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($listingCount) }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reģistrētie lietotāji</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($userCount) }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sludinājumi šomēnes</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($listingsThisMonth) }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jauni lietotāji šomēnes</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($newUsersThisMonth) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cenu statistika</h3>
                        <dl class="mt-4 space-y-2 text-gray-700 dark:text-gray-200">
                            <div class="flex items-center justify-between">
                                <dt>Vidējā cena</dt>
                                <dd class="font-semibold">
                                    @if (! is_null($averagePrice))
                                        €{{ number_format($averagePrice, 0, '.', ' ') }}
                                    @else
                                        Nav datu
                                    @endif
                                </dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>Galerijas bilžu kopskaits</dt>
                                <dd class="font-semibold">{{ number_format($totalImageCount) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pēdējie sludinājumi</h3>
                        <ul class="mt-4 space-y-3">
                            @forelse ($latestListings as $listing)
                                <li class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 p-3 text-sm dark:border-gray-700 dark:bg-gray-900">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $listing->created_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">€{{ number_format($listing->cena, 0, '.', ' ') }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-600 dark:text-gray-400">Vēl nav pievienots neviens sludinājums.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
