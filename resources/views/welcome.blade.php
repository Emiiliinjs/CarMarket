<x-app-layout>
    <x-slot name="header">
        <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-[#2B7A78]/12 via-white to-white px-6 py-8 shadow-sm sm:px-10 sm:py-12 dark:border-slate-800 dark:from-slate-900/70 dark:via-slate-950">
            <div class="grid gap-8 lg:grid-cols-[1.1fr,1fr]">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-[#2B7A78] shadow-sm shadow-[#2B7A78]/20 dark:bg-slate-900/60 dark:text-[#2B7A78]/80">
                        Jaunākie piedāvājumi
                    </span>

                    <h1 class="text-3xl font-semibold leading-snug text-slate-900 sm:text-4xl dark:text-white">
                        Atrodi savu nākamo auto vienkāršā, bet izteiksmīgā galerijā.
                    </h1>

                    <p class="max-w-xl text-sm text-slate-600 sm:text-base dark:text-slate-300">
                        CarMarket apvieno skaidru struktūru ar gaumīgu Tailwind stilu – pievieno, filtrē un saglabā auto sludinājumus vienuviet.
                    </p>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center rounded-xl bg-[#2B7A78] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#22615F]">
                            Pārlūkot auto
                        </a>
                        <div class="flex items-center gap-6 text-sm text-slate-500 dark:text-slate-300">
                            <div>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ number_format($listings->count()) }}</p>
                                <p>Aktīvie piedāvājumi</p>
                            </div>
                            <div class="hidden h-10 w-px bg-slate-200 dark:bg-slate-700 sm:block"></div>
                            <div class="hidden sm:block">
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">100%</p>
                                <p>Responsīvas galerijas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4">
                    <div class="rounded-2xl border border-slate-200 bg-white/90 p-5 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Vieglā pārvaldība</h3>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">Administrē sludinājumus un attēlus bez liekas sarežģītības – viss sakārtots vienā panelī.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white/90 p-5 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Favorīti vienā vietā</h3>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">Saglabā savus iecienītos auto un salīdzini tos pēc svarīgākajiem parametriem.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white/90 p-5 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Mūsdienīgs Tailwind dizains</h3>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">Izvēlies mierīgu, bet joprojām stilīgu interfeisu, kas pielāgojas jebkurai ierīcei.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-12">
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Intuitīvi filtri</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Atrast vajadzīgo modeli ir viegli ar pārskatāmu filtru un kārtošanas sistēmu.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Stabila galerija</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Bildes tiek apstrādātas, lai katrs sludinājums izskatītos sakopts un vienots.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Tumšais režīms</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Pielāgo interfeisu savai videi – tikai viens klikšķis, lai pārslēgtos.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Salīdzināšanas rīks</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Atzīmē līdz trim auto un salīdzini tos vienuviet, izmantojot tīru tabulu.</p>
            </div>
        </section>

        @if($listings->count())
            <section class="space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Izcelti auto piedāvājumi</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Vienkāršas, bet modernas kartītes ar visiem svarīgākajiem datiem.</p>
                    </div>
                    <a href="{{ route('favorites.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#2B7A78] transition hover:text-[#22615F] dark:text-[#2B7A78]/80 dark:hover:text-[#2B7A78]">
                        Apskatīt favorītus
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M12.293 4.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 1 1-1.414-1.414L14.586 10l-2.293-2.293a1 1 0 0 1 0-1.414Z" />
                        </svg>
                    </a>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($listings as $listing)
                        @include('listings.partials.card', ['listing' => $listing])
                    @endforeach
                </div>

                <div class="flex justify-center">
                    {{ $listings->onEachSide(1)->links() }}
                </div>
            </section>
        @else
            <section class="rounded-3xl border border-dashed border-slate-300 bg-white/80 p-12 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900/60">
                <h2 class="text-2xl font-semibold text-slate-700 dark:text-slate-100">Nav pieejamu sludinājumu.</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Esi pirmais un pievieno savu auto galeriju ar dažiem klikšķiem.</p>
                <a href="{{ route('listings.create') }}" class="btn btn-primary mt-6">Izveidot sludinājumu</a>
            </section>
        @endif
    </div>
</x-app-layout>
