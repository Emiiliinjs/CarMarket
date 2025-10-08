<x-app-layout>
    @php
        $images = $listing->galleryImages;
        $imageUrls = $images
            ->map(fn($image) => route('listing-images.show', $image))
            ->values();
        $primaryUrl = $imageUrls->first() ?? asset('images/car.png');
        $additionalImages = $imageUrls->slice(1);
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">Galerija ar {{ $images->count() }} bild{{ $images->count() === 1 ? 'i' : 'ēm' }} – optimizētas ātrai ielādei jebkurā ierīcē.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center rounded-full px-4 py-1 text-sm font-semibold {{ match($listing->status) {
                    \App\Models\Listing::STATUS_RESERVED => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200',
                    \App\Models\Listing::STATUS_SOLD => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-200',
                    default => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200',
                } }}">{{ $listing->status_label }}</span>

                @auth
                    <form method="POST" action="{{ route($isFavorite ? 'favorites.destroy' : 'favorites.store', $listing) }}">
                        @csrf
                        @if($isFavorite)
                            @method('DELETE')
                        @endif

                        <button type="submit" class="btn btn-secondary rounded-full border-rose-200 px-4 text-rose-600 hover:bg-rose-50 dark:border-rose-500/40 dark:bg-gray-900/60 dark:text-rose-200">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M3.172 5.172a4 4 0 0 1 5.656 0L10 6.343l1.172-1.171a4 4 0 0 1 5.656 5.656L10 17.657l-6.828-6.829a4 4 0 0 1 0-5.656Z" @if(! $isFavorite) fill="none" stroke="currentColor" stroke-width="1.5" @endif />
                            </svg>
                            {{ $isFavorite ? 'Favorīts' : 'Pievienot favorītiem' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary rounded-full border-[#2B7A78]/30 px-4 text-[#2B7A78] hover:bg-[#2B7A78]/10 dark:border-[#2B7A78]/30 dark:bg-gray-900/60 dark:text-[#2B7A78]/70">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M3.172 5.172a4 4 0 0 1 5.656 0L10 6.343l1.172-1.171a4 4 0 0 1 5.656 5.656L10 17.657l-6.828-6.829a4 4 0 0 1 0-5.656Z" fill="none" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        Saglabāt
                    </a>
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        @if(!$listing->is_approved)
            <div class="rounded-3xl border border-amber-200 bg-amber-50 px-6 py-4 text-sm text-amber-700 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                Sludinājums vēl gaida administratora apstiprinājumu. Tas nav redzams publiskajā sarakstā.
            </div>
        @endif

        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-6 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-5">
            <div class="space-y-6 lg:col-span-3">
                <div class="overflow-hidden rounded-3xl bg-white/80 shadow-xl ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                    <div class="relative aspect-[4/3]">
                        <button
                            type="button"
                            @if($imageUrls->isNotEmpty()) data-gallery-index="0" @endif
                            class="group relative h-full w-full"
                        >
                            <img src="{{ $primaryUrl }}" alt="{{ $listing->marka }} {{ $listing->modelis }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]" loading="lazy">
                            @if($imageUrls->isNotEmpty())
                                <span class="pointer-events-none absolute inset-0 hidden items-center justify-center bg-black/40 text-sm font-semibold uppercase tracking-wide text-white backdrop-blur group-hover:flex">Skatīt galeriju</span>
                            @endif
                        </button>
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
                    <div class="rounded-3xl bg-white/70 p-4 shadow ring-1 ring-gray-100 dark:bg-gray-900/60 dark:ring-gray-800">
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Papildu bildes</h3>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach($additionalImages as $index => $imageUrl)
                                <button
                                    type="button"
                                    data-gallery-index="{{ $index + 1 }}"
                                    class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm"
                                >
                                    <img src="{{ $imageUrl }}" alt="Papildu auto bilde" class="h-32 w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                                    <span class="pointer-events-none absolute inset-0 hidden items-center justify-center bg-black/40 text-xs font-semibold uppercase tracking-wide text-white group-hover:flex">Atvērt</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="space-y-6 rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                    <div class="flex items-baseline justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cena</p>
                            <p class="text-3xl font-semibold text-[#2B7A78] dark:text-[#2B7A78]/80">{{ number_format($listing->cena, 2, '.', ' ') }} €</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-[#2B7A78]/10 px-3 py-1 text-xs font-semibold text-[#2B7A78] dark:bg-[#2B7A78]/10 dark:text-[#2B7A78]/70">{{ $listing->degviela }} • {{ $listing->parnesumkarba }}</span>
                    </div>

                    <dl class="grid gap-4 text-sm text-gray-600 dark:text-gray-300">
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3 dark:bg-gray-800/60">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Marka / modelis</dt>
                            <dd class="font-semibold text-gray-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }}</dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3 dark:bg-gray-800/60">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Izlaiduma gads</dt>
                            <dd class="font-semibold text-gray-900 dark:text-white">{{ $listing->gads }}</dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50/70 px-4 py-3 dark:bg-gray-800/60">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Nobraukums</dt>
                            <dd class="font-semibold text-gray-900 dark:text-white">{{ number_format($listing->nobraukums, 0, '.', ' ') }} km</dd>
                        </div>
                    </dl>

                    @if($listing->apraksts)
                        <div class="space-y-2">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Apraksts</h3>
                            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $listing->apraksts }}</p>
                        </div>
                    @endif
                </div>

                @if($listing->show_contact && $listing->contact_info)
                    <div class="rounded-3xl border border-[#2B7A78]/30 bg-[#2B7A78]/10 p-6 shadow-sm dark:border-[#2B7A78]/30 dark:bg-[#2B7A78]/10">
                        <h3 class="text-base font-semibold text-[#22615F] dark:text-[#2B7A78]/70">Kontakta informācija</h3>
                        <p class="mt-2 whitespace-pre-line text-sm text-[#184844] dark:text-[#7FD1CC]">{{ $listing->contact_info }}</p>
                    </div>
                @endif

                <div x-data="{ open: false }" class="rounded-3xl bg-white/80 p-6 shadow ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Ziņot par pārkāpumu</h3>
                        <button type="button" @click="open = ! open" class="btn btn-secondary rounded-lg px-3 py-1.5 text-xs">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9 3a1 1 0 0 1 2 0v7a1 1 0 0 1-2 0V3Zm1 12.75a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z" clip-rule="evenodd" />
                            </svg>
                            Ziņot
                        </button>
                    </div>

                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-300">Informē administratoru, ja sludinājums pārkāpj noteikumus. Tavs ziņojums paliek konfidenciāls.</p>

                    <form x-show="open" x-cloak method="POST" action="{{ route('listings.report', $listing) }}" class="mt-4 space-y-3">
                        @csrf
                        <textarea name="reason" rows="3" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" placeholder="Apraksti pārkāpumu vai maldinošu informāciju"></textarea>
                        <button type="submit" class="btn btn-danger">Nosūtīt ziņojumu</button>
                    </form>
                </div>

                @if(auth()->check() && (auth()->user()->id === $listing->user_id || auth()->user()->is_admin))
                    <div class="flex flex-col gap-3 rounded-3xl bg-white/80 p-6 shadow ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('listings.edit', $listing->id) }}" class="btn btn-primary w-full sm:w-auto">
                            Rediģēt sludinājumu
                        </a>

                        <form action="{{ route('listings.destroy', $listing->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Vai tiešām dzēst šo sludinājumu?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">
                                Dzēst sludinājumu
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($imageUrls->isNotEmpty())
        <div id="gallery-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-4">
            <div class="relative flex w-full max-w-5xl flex-col gap-4">
                <button id="gallery-close" type="button" class="absolute right-0 top-0 -translate-y-16 rounded-full bg-white/90 p-2 text-gray-900 shadow transition hover:bg-white">
                    <span class="sr-only">Aizvērt galeriju</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.22 4.22a.75.75 0 0 1 1.06 0L10 8.94l4.72-4.72a.75.75 0 1 1 1.06 1.06L11.06 10l4.72 4.72a.75.75 0 1 1-1.06 1.06L10 11.06l-4.72 4.72a.75.75 0 0 1-1.06-1.06L8.94 10 4.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="relative overflow-hidden rounded-3xl bg-white shadow-2xl">
                    <img id="gallery-image" src="" alt="Galerijas attēls" class="h-full w-full max-h-[70vh] object-contain bg-black">
                    <div class="pointer-events-none absolute inset-x-0 top-0 flex justify-between p-4 text-xs font-semibold uppercase tracking-wide text-white">
                        <span id="gallery-counter" class="rounded-full bg-black/60 px-3 py-1 backdrop-blur"></span>
                    </div>

                    <button id="gallery-prev" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-black/60 p-3 text-white backdrop-blur transition hover:bg-black/80">
                        <span class="sr-only">Iepriekšējais attēls</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.78 4.22a.75.75 0 0 1 0 1.06L8.06 10l4.72 4.72a.75.75 0 1 1-1.06 1.06l-5.25-5.25a.75.75 0 0 1 0-1.06l5.25-5.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <button id="gallery-next" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-black/60 p-3 text-white backdrop-blur transition hover:bg-black/80">
                        <span class="sr-only">Nākamais attēls</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.22 15.78a.75.75 0 0 1 0-1.06L11.94 10 7.22 5.28a.75.75 0 1 1 1.06-1.06l5.25 5.25a.75.75 0 0 1 0 1.06l-5.25 5.25a.75.75 0 0 1-1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const galleryImages = @json($imageUrls);
                const openers = document.querySelectorAll('[data-gallery-index]');
                const modal = document.getElementById('gallery-modal');
                const modalImage = document.getElementById('gallery-image');
                const modalCounter = document.getElementById('gallery-counter');
                const closeButton = document.getElementById('gallery-close');
                const nextButton = document.getElementById('gallery-next');
                const prevButton = document.getElementById('gallery-prev');

                let currentIndex = 0;

                const showImage = (index) => {
                    currentIndex = (index + galleryImages.length) % galleryImages.length;
                    modalImage.src = galleryImages[currentIndex];
                    modalCounter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
                };

                const openModal = (index) => {
                    showImage(index);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                };

                openers.forEach((opener) => {
                    opener.addEventListener('click', () => {
                        const index = parseInt(opener.getAttribute('data-gallery-index'), 10);
                        if (!Number.isNaN(index)) {
                            openModal(index);
                        }
                    });
                });

                nextButton.addEventListener('click', () => showImage(currentIndex + 1));
                prevButton.addEventListener('click', () => showImage(currentIndex - 1));
                closeButton.addEventListener('click', closeModal);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (modal.classList.contains('hidden')) {
                        return;
                    }

                    if (event.key === 'Escape') {
                        closeModal();
                    } else if (event.key === 'ArrowRight') {
                        showImage(currentIndex + 1);
                    } else if (event.key === 'ArrowLeft') {
                        showImage(currentIndex - 1);
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
