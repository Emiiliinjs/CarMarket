@php
    $coverImage = $listing->galleryImages->first();
    $imageUrl = $coverImage ? asset('storage/'.$coverImage->filename) : asset('images/car.png');
    $galleryCount = $listing->galleryImages->count();
@endphp

<article class="group flex flex-col overflow-hidden rounded-3xl bg-white/80 shadow-lg ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $imageUrl }}" alt="{{ $listing->marka }} {{ $listing->modelis }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">

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

    <div class="flex flex-1 flex-col justify-between p-5">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $listing->marka }} {{ $listing->modelis }}</h3>
                <p class="text-sm text-gray-500">Izlaiduma gads {{ $listing->gads }}</p>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <dt class="font-medium text-gray-500">Cena</dt>
                    <dd class="text-base font-semibold text-indigo-600">{{ number_format($listing->cena, 2, '.', ' ') }} €</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Nobraukums</dt>
                    <dd>{{ number_format($listing->nobraukums, 0, '.', ' ') }} km</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Degviela</dt>
                    <dd>{{ ucfirst($listing->degviela) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Pārnesumkārba</dt>
                    <dd>{{ ucfirst($listing->parnesumkarba) }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6">
            <a href="{{ route('listings.show', $listing->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Skatīt detaļas
            </a>
        </div>
    </div>
</article>
