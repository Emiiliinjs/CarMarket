<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-indigo-500 via-purple-500 to-sky-500 p-10 text-white shadow-2xl">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/20 blur-2xl"></div>
            <div class="absolute -bottom-20 -left-6 h-64 w-64 rounded-full bg-sky-400/30 blur-3xl"></div>

            <div class="relative z-10 grid gap-10 lg:grid-cols-[1.15fr,1fr]">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1 text-sm font-semibold uppercase tracking-wide text-white/90">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        Jaunākās kolekcijas
                    </span>

                    <h1 class="text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">
                        Atrodi savu nākamo auto ar galeriju, kas vienmēr izskatās <span class="text-white/90">perfekti</span>.
                    </h1>

                    <p class="text-base text-white/80 sm:text-lg">
                        CarMarket piedāvā vienmērīgi apstrādātas galerijas un Tailwind modernu dizainu katram sludinājumam.
                        Iepazīsti svaigākos piedāvājumus un saglabā favorītus vienā klikšķī.
                    </p>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-indigo-600 shadow-lg shadow-indigo-500/30 transition hover:-translate-y-0.5">
                            Pārlūkot visus auto
                        </a>
                        <div class="flex items-center gap-4 text-sm text-white/80">
                            <div class="flex flex-col">
                                <span class="text-xl font-semibold text-white">{{ number_format($listings->count()) }}</span>
                                <span>Aktīvie piedāvājumi</span>
                            </div>
                            <div class="hidden h-10 w-px bg-white/30 sm:block"></div>
                            <div class="hidden flex-col sm:flex">
                                <span class="text-xl font-semibold text-white">100%</span>
                                <span>Responsīva galerija</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 text-sm">
                    <div class="rounded-3xl bg-white/15 p-6 shadow-lg backdrop-blur">
                        <h3 class="text-lg font-semibold text-white">Pilnībā responsīvs dizains</h3>
                        <p class="mt-2 text-white/80">Katra galerija pielāgojas ierīcei – no mobilā līdz 4K monitoram.</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-6 shadow-lg backdrop-blur">
                        <h3 class="text-lg font-semibold text-white">Viedie favorīti</h3>
                        <p class="mt-2 text-white/80">Pievieno favorītiem savus top auto un saglabā tos personalizētā sarakstā.</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-6 shadow-lg backdrop-blur">
                        <h3 class="text-lg font-semibold text-white">Droša pārvaldība</h3>
                        <p class="mt-2 text-white/80">Admin panelis ar statusu kontroles iespējām un moderēšanas rīkiem.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-12">
        <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-white/60 transition hover:-translate-y-1 hover:shadow-2xl dark:bg-slate-900/70 dark:ring-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-300">Instant filtrēšana</h3>
                    <span class="text-xs font-semibold uppercase text-indigo-500">Live</span>
                </div>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Izmanto detalizētus filtrus un datu sarakstus, lai ātri sameklētu vēlamo modeli.</p>
            </div>
            <div class="rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-white/60 transition hover:-translate-y-1 hover:shadow-2xl dark:bg-slate-900/70 dark:ring-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-300">Galerijas optimizācija</h3>
                    <span class="text-xs font-semibold uppercase text-emerald-500">HDR</span>
                </div>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Visas bildes tiek standartizētas, lai galerija saglabātu vienotu stilu.</p>
            </div>
            <div class="rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-white/60 transition hover:-translate-y-1 hover:shadow-2xl dark:bg-slate-900/70 dark:ring-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-300">Tumšais režīms</h3>
                    <span class="text-xs font-semibold uppercase text-purple-500">Pro</span>
                </div>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Glancēta pieredze naktī un dienā, pateicoties gudram Tailwind režīmu pārslēdzējam.</p>
            </div>
            <div class="rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-white/60 transition hover:-translate-y-1 hover:shadow-2xl dark:bg-slate-900/70 dark:ring-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-300">Salīdzināšanas panelis</h3>
                    <span class="text-xs font-semibold uppercase text-rose-500">Beta</span>
                </div>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Atķeksē līdz trim auto, lai salīdzinātu cenu un specifikācijas vienuviet.</p>
            </div>
        </section>

        @if($listings->count())
            <section class="space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Izcelti auto piedāvājumi</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-300">Pievilcīgas galerijas ar dinamisku hover efektu katrā kartītē.</p>
                    </div>
                    <a href="{{ route('favorites.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-200">
                        Apskatīt favorītus
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M12.293 4.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 1 1-1.414-1.414L14.586 10l-2.293-2.293a1 1 0 0 1 0-1.414Z" />
                        </svg>
                    </a>
                </div>

                <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($listings as $listing)
                        @include('listings.partials.card', ['listing' => $listing])
                    @endforeach
                </div>

                <div class="flex justify-center">
                    {{ $listings->onEachSide(1)->links() }}
                </div>
            </section>
        @else
            <section class="rounded-[2.5rem] border border-dashed border-white/60 bg-white/80 p-16 text-center shadow-xl backdrop-blur dark:border-slate-700 dark:bg-slate-900/70">
                <h2 class="text-2xl font-semibold text-slate-700 dark:text-slate-100">Nav neviena sludinājuma... pagaidām.</h2>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Esi pirmais un pievieno auto galeriju ar dažiem klikšķiem – Tailwind parūpēsies par stilu.</p>
                <a href="{{ route('listings.create') }}" class="btn btn-primary mt-6 rounded-full shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5">
                    Izveidot sludinājumu
                </a>
            </section>
        @endif
    </div>
</x-app-layout>
