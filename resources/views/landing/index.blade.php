<x-guest-layout>
    <section class="mx-auto max-w-6xl space-y-16 px-6 py-16 sm:px-8 lg:px-12">
        <div class="grid gap-10 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:grid-cols-[1.2fr,1fr] lg:items-center dark:border-slate-800 dark:bg-slate-900/70">
            <div class="space-y-6">
                <span class="inline-flex items-center rounded-full bg-[#2B7A78]/10 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-[#2B7A78]">
                    CarMarket Latvija
                </span>

                <h1 class="text-3xl font-bold leading-tight text-slate-900 sm:text-4xl dark:text-white">
                    Pārdod un atrodi auto vienā uzticamā platformā.
                </h1>

                <p class="text-base text-slate-600 dark:text-slate-300">
                    Šī mājaslapa ir auto sludinājumu platforma, kur lietotāji var publicēt savus transportlīdzekļus,
                    pircēji var ērti filtrēt un salīdzināt piedāvājumus, bet administratori uztur drošu un kvalitatīvu vidi.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center rounded-xl bg-[#2B7A78] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#22615F]">
                        Skatīt auto sludinājumus
                    </a>
                    @auth
                        <a href="{{ route('listings.create') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                            Ievietot sludinājumu
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                            Izveidot kontu
                        </a>
                    @endauth
                </div>
            </div>

            <div class="grid gap-4 text-sm">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/70">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Kas ir CarMarket?</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-300">Mūsdienīga platforma automašīnu pirkšanai, pārdošanai un pārvaldībai ar pārskatāmu dizainu.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/70">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Kam tā paredzēta?</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-300">Privātpersonām un auto tirgotājiem, kuri vēlas ātri sasniegt pircējus un pārvaldīt savus piedāvājumus.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/70">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Kāpēc izmantot šo lapu?</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-300">Droša autorizācija, favorīti, tiešsaistes solīšana un ērta administrēšana vienā sistēmā.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Pārskatāmi sludinājumi</h3>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Katram auto redzama svarīgākā informācija: marka, modelis, cena, nobraukums un foto.</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Gudra meklēšana</h3>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Filtrē pēc cenas, markas un citiem kritērijiem, lai ātrāk atrastu sev piemērotu auto.</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Uzticama vide</h3>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Moderācija un lietotāju pārvaldība palīdz uzturēt kvalitatīvu un drošu sludinājumu vidi.</p>
            </article>
        </div>
    </section>
</x-guest-layout>
