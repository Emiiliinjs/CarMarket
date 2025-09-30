<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-10">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Kopējais sludinājumu skaits</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($listingCount) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Reģistrētie lietotāji</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($userCount) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Sludinājumi šomēnes</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($listingsThisMonth) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Jauni lietotāji šomēnes</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($newUsersThisMonth) }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Cenu statistika</h3>
                <dl class="mt-4 space-y-3 text-slate-600 dark:text-slate-300">
                    <div class="flex items-center justify-between">
                        <dt>Vidējā cena</dt>
                        <dd class="font-semibold text-slate-900 dark:text-white">
                            @if (! is_null($averagePrice))
                                €{{ number_format($averagePrice, 0, '.', ' ') }}
                            @else
                                Nav datu
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Galerijas bilžu kopskaits</dt>
                        <dd class="font-semibold text-slate-900 dark:text-white">{{ number_format($totalImageCount) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Pēdējie sludinājumi</h3>
                <ul class="mt-4 space-y-3">
                    @forelse ($latestListings as $listing)
                        <li class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $listing->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">€{{ number_format($listing->cena, 0, '.', ' ') }}</span>
                        </li>
                    @empty
                        <li class="text-sm text-slate-600 dark:text-slate-400">Vēl nav pievienots neviens sludinājums.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
