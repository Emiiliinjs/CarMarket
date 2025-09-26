@php
    $coverImage = $listing->galleryImages->first();
    $imageUrl = $coverImage ? route('listing-images.show', $coverImage) : asset('images/car.png');
    $galleryCount = $listing->galleryImages->count();
    $favoriteIds = $favoriteIds ?? [];
    $isFavorite = in_array($listing->id, $favoriteIds, true);
    $statusStyles = [
        'available' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200',
        'reserved' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200',
        'sold' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-200',
    ];
@endphp

<article
    data-listing-card
    data-id="{{ $listing->id }}"
    data-marka="{{ $listing->marka }}"
    data-modelis="{{ $listing->modelis }}"
    data-gads="{{ $listing->gads }}"
    data-cena="{{ $listing->cena }}"
    data-nobraukums="{{ $listing->nobraukums }}"
    data-degviela="{{ $listing->degviela }}"
    data-parnesumkarba="{{ $listing->parnesumkarba }}"
    data-status="{{ $listing->status_label }}"
    class="group flex flex-col overflow-hidden rounded-3xl bg-white/80 backdrop-blur shadow-lg ring-1 ring-white/60 transition duration-300 hover:-translate-y-1 hover:shadow-2xl dark:bg-slate-900/70 dark:ring-slate-800"
>
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $imageUrl }}" alt="{{ $listing->marka }} {{ $listing->modelis }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">

        <div class="absolute left-4 top-4 flex items-center gap-2">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$listing->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-200' }}">
                {{ $listing->status_label }}
            </span>

            @if(! $listing->is_approved)
                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-200">Gaida apstiprinājumu</span>
            @endif

            @auth
                <form method="POST" action="{{ route($isFavorite ? 'favorites.destroy' : 'favorites.store', $listing) }}">
                    @csrf
                    @if($isFavorite)
                        @method('DELETE')
                    @endif
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-white/90 p-2 text-rose-500 shadow transition hover:bg-white dark:bg-gray-800/90 dark:text-rose-300">
                        <span class="sr-only">{{ $isFavorite ? 'Noņemt no favorītiem' : 'Pievienot favorītiem' }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M3.172 5.172a4 4 0 0 1 5.656 0L10 6.343l1.172-1.171a4 4 0 0 1 5.656 5.656L10 17.657l-6.828-6.829a4 4 0 0 1 0-5.656Z" @if(! $isFavorite) fill="none" stroke="currentColor" stroke-width="1.5" @endif />
                        </svg>
                    </button>
                </form>
            @endauth
        </div>

        @if($galleryCount > 1)
            <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M4.5 4A1.5 1.5 0 0 0 3 5.5v7A1.5 1.5 0 0 0 4.5 14h7A1.5 1.5 0 0 0 13 12.5v-7A1.5 1.5 0 0 0 11.5 4h-7Z" />
                    <path d="M6.5 6h8A1.5 1.5 0 0 1 16 7.5v6A1.5 1.5 0 0 1 14.5 15h-8A1.5 1.5 0 0 1 5 13.5v-6A1.5 1.5 0 0 1 6.5 6Z" opacity=".6" />
                </svg>
                <span>{{ $galleryCount }} bild{{ $galleryCount === 1 ? 'e' : 'es' }}</span>
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col justify-between bg-gradient-to-b from-white/70 via-white/60 to-white/70 p-6 dark:from-slate-900/60 dark:via-slate-900/40 dark:to-slate-900/60">
        <div class="space-y-4">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Izlaiduma gads {{ $listing->gads }}</p>
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-300">
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Cena</dt>
                    <dd class="text-base font-semibold text-indigo-600 dark:text-indigo-300">{{ number_format($listing->cena, 2, '.', ' ') }} €</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nobraukums</dt>
                    <dd>{{ number_format($listing->nobraukums, 0, '.', ' ') }} km</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Degviela</dt>
                    <dd>{{ $listing->degviela }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Pārnesumkārba</dt>
                    <dd>{{ $listing->parnesumkarba }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 space-y-3">
            <label class="inline-flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-300">
                <input type="checkbox" class="js-compare-checkbox h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                Salīdzināt
            </label>

            <a href="{{ route('listings.show', $listing->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Skatīt detaļas
            </a>
        </div>
    </div>
</article>
