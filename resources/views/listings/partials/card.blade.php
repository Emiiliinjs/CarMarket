@php
    $coverImage = $listing->galleryImages->first();
    $imageUrl = $coverImage ? route('listing-images.show', $coverImage) : asset('images/car.png');
    $galleryCount = $listing->galleryImages->count();
    $favoriteIds = $favoriteIds ?? [];
    $isFavorite = in_array($listing->id, $favoriteIds, true);
    $statusStyles = [
        'available' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200',
        'reserved' => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-200',
        'sold' => 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-200',
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
    class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70"
>
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $imageUrl }}" alt="{{ $listing->marka }} {{ $listing->modelis }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">

        <div class="absolute left-4 top-4 flex items-center gap-2">
            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold shadow-sm {{ $statusStyles[$listing->status] ?? 'border-slate-200 bg-white/90 text-slate-700 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-200' }}">
                {{ $listing->status_label }}
            </span>

            @if(! $listing->is_approved)
                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">Gaida apstiprinājumu</span>
            @endif

            @auth
                <form method="POST" action="{{ route($isFavorite ? 'favorites.destroy' : 'favorites.store', $listing) }}">
                    @csrf
                    @if($isFavorite)
                        @method('DELETE')
                    @endif
                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-white/70 bg-white/90 p-2 text-rose-500 shadow-sm transition hover:border-rose-200 hover:text-rose-600 dark:border-slate-700 dark:bg-slate-900/70 dark:text-rose-300 dark:hover:border-rose-400">
                        <span class="sr-only">{{ $isFavorite ? 'Noņemt no favorītiem' : 'Pievienot favorītiem' }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M3.172 5.172a4 4 0 0 1 5.656 0L10 6.343l1.172-1.171a4 4 0 0 1 5.656 5.656L10 17.657l-6.828-6.829a4 4 0 0 1 0-5.656Z" @if(! $isFavorite) fill="none" stroke="currentColor" stroke-width="1.5" @endif />
                        </svg>
                    </button>
                </form>
            @endauth
        </div>

        @if($galleryCount > 1)
            <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-slate-900/70 px-3 py-1 text-xs font-semibold text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M4.5 4A1.5 1.5 0 0 0 3 5.5v7A1.5 1.5 0 0 0 4.5 14h7A1.5 1.5 0 0 0 13 12.5v-7A1.5 1.5 0 0 0 11.5 4h-7Z" />
                    <path d="M6.5 6h8A1.5 1.5 0 0 1 16 7.5v6A1.5 1.5 0 0 1 14.5 15h-8A1.5 1.5 0 0 1 5 13.5v-6A1.5 1.5 0 0 1 6.5 6Z" opacity=".6" />
                </svg>
                <span>{{ $galleryCount }} bild{{ $galleryCount === 1 ? 'e' : 'es' }}</span>
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col justify-between gap-6 p-6">
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-300">Izlaiduma gads {{ $listing->gads }}</p>

            <dl class="grid grid-cols-2 gap-4 text-sm text-slate-600 dark:text-slate-300">
                <div>
                    <dt class="font-medium text-slate-500 dark:text-slate-400">Cena</dt>
                    <dd class="text-base font-semibold text-indigo-600 dark:text-indigo-300">{{ number_format($listing->cena, 2, '.', ' ') }} €</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-500 dark:text-slate-400">Nobraukums</dt>
                    <dd>{{ number_format($listing->nobraukums, 0, '.', ' ') }} km</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-500 dark:text-slate-400">Degviela</dt>
                    <dd>{{ $listing->degviela }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-500 dark:text-slate-400">Pārnesumkārba</dt>
                    <dd>{{ $listing->parnesumkarba }}</dd>
                </div>
            </dl>
        </div>

        <div class="flex items-center justify-between gap-4">
            <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-300">
                <input type="checkbox" class="js-compare-checkbox h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800">
                Salīdzināt
            </label>

            <a href="{{ route('listings.show', $listing->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100 dark:border-indigo-500/40 dark:bg-indigo-500/10 dark:text-indigo-200 dark:hover:border-indigo-500">
                Skatīt detaļas
            </a>
        </div>
    </div>
</article>
