<x-app-layout>
    @php
        $images = $listing->images;
        $primaryImage = $images->first();
        $primaryUrl = $primaryImage ? asset('storage/'.$primaryImage->filename) : asset('images/car.png');
        $additionalImages = $images->skip(1);
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">{{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})</h2>
                <p class="text-sm text-gray-500">Galerija ar {{ $images->count() }} bild{{ $images->count() === 1 ? 'i' : 'ēm' }} – optimizētas ātrai ielādei un izskatam uz jebkuras ierīces.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        <div class="grid gap-8 lg:grid-cols-5">
            <div class="space-y-6 lg:col-span-3">
                <div class="overflow-hidden rounded-3xl bg-white/80 shadow-xl ring-1 ring-gray-100">
                    <div class="relative aspect-[4/3]">
                        <img src="{{ $primaryUrl }}" alt="{{ $listing->marka }} {{ $listing->modelis }}" class="h-full w-full object-cover" loading="lazy">
                        @if($images->count() > 1)
                            <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M4.5 4A1.5 1.5 0 0 0 3 5.5v7A1.5 1.5 0 0 0 4.5 14h7A1.5 1.5 0 0 0 13 12.5v-7A1.5 1.5 0 0 0 11.5 4h-7Z" />
                                    <path d="M6.5 6h8A1.5 1.5 0 0 1 16 7.5v6A1.5 1.5 0 0 1 14.5 15h-8A1.5 1.5 0 0 1 5 13.5v-6A1.5 1.5 0 0 1 6.5 6Z" opacity=".6" />
                                </svg>
                                <span>{{ $images->count() }} bild{{ $images->count() === 1 ? 'e' : 'es' }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                @if($additionalImages->count())
                    <div class="rounded-3xl bg-white/70 p-4 shadow ring-1 ring-gray-100">
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Papildu bildes</h3>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach($additionalImages as $image)
                                <div class="overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                    <img src="{{ asset('storage/'.$image->filename) }}" alt="Papildu auto bilde" class="h-32 w-full object-cover" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="space-y-6 rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-gray-100">
                    <div class="flex items-baseline justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Cena</p>
                            <p class="text-3xl font-semibold text-indigo-600">{{ number_format($listing->cena, 2, '.', ' ') }} €</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">{{ ucfirst($listing->degviela) }} • {{ ucfirst($listing->parnesumkarba) }}</span>
                    </div>

                    <dl class="grid gap-4 text-sm text-gray-600">
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-medium text-gray-500">Marka / modelis</dt>
                            <dd class="font-semibold text-gray-900">{{ $listing->marka }} {{ $listing->modelis }}</dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-medium text-gray-500">Izlaiduma gads</dt>
                            <dd class="font-semibold text-gray-900">{{ $listing->gads }}</dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-medium text-gray-500">Nobraukums</dt>
                            <dd class="font-semibold text-gray-900">{{ number_format($listing->nobraukums, 0, '.', ' ') }} km</dd>
                        </div>
                    </dl>

                    @if($listing->apraksts)
                        <div class="space-y-2">
                            <h3 class="text-base font-semibold text-gray-900">Apraksts</h3>
                            <p class="text-sm leading-relaxed text-gray-600">{{ $listing->apraksts }}</p>
                        </div>
                    @endif
                </div>

                @if(auth()->check() && (auth()->user()->id === $listing->user_id || auth()->user()->is_admin))
                    <div class="flex flex-col gap-3 rounded-3xl bg-white/80 p-6 shadow ring-1 ring-gray-100 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('listings.edit', $listing->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                            Rediģēt sludinājumu
                        </a>

                        <form action="{{ route('listings.destroy', $listing->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Vai tiešām dzēst šo sludinājumu?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                Dzēst sludinājumu
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
