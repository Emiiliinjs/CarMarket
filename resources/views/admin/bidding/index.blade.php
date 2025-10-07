<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
                    Admin izsoļu automašīnas
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Šeit vari pārvaldīt īpašos sludinājumus, kas pieejami tikai administratoriem.
                </p>
            </div>
            <a
                href="{{ route('admin.bidding.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Jauns izsoles auto
            </a>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-900/60 dark:bg-green-900/40 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($listings as $listing)
                <div class="flex flex-col gap-6 rounded-3xl border border-gray-200/70 bg-white/80 p-6 shadow-sm backdrop-blur dark:border-gray-800/60 dark:bg-gray-900/40">
                    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $listing->marka }} {{ $listing->modelis }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Gads: {{ $listing->gads }} · Nobraukums: {{ number_format($listing->nobraukums, 0, '.', ' ') }} km
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Starta cena: {{ number_format($listing->cena, 2, '.', ' ') }} €
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a
                                href="{{ route('listings.live-bid', $listing) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-indigo-200/70 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-400 hover:text-indigo-500 dark:border-indigo-800/50 dark:text-indigo-300 dark:hover:border-indigo-600"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                                Atvērt izsoli
                            </a>
                            <a
                                href="{{ route('listings.show', $listing) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-300 hover:text-gray-900 dark:border-gray-700 dark:text-gray-200 dark:hover:border-gray-500"
                            >
                                Detaļas
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.bidding.destroy', $listing) }}"
                                onsubmit="return confirm('Vai tiešām dzēst šo izsoles sludinājumu?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:text-red-700 dark:border-red-900/60 dark:text-red-300 dark:hover:border-red-700"
                                >
                                    Dzēst
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($listing->galleryImages->isNotEmpty())
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach($listing->galleryImages->take(3) as $image)
                                <img src="{{ route('listing-images.show', $image) }}" alt="Foto" class="h-40 w-full rounded-2xl object-cover">
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-gray-300/80 bg-white/70 p-16 text-center text-sm text-gray-500 dark:border-gray-700/60 dark:bg-gray-900/30 dark:text-gray-300">
                    Vēl nav izveidots neviens izsoles sludinājums. Izmanto pogu augšā, lai pievienotu pirmo.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
