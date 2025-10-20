<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-white">Mani sludinājumi</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">
                    Pārvaldi savus auto piedāvājumus, seko apstiprinājuma statusam un atjauno informāciju jebkurā brīdī.
                </p>
            </div>
            <a href="{{ route('listings.create') }}" class="btn btn-primary">
                Pievienot jaunu sludinājumu
            </a>
        </div>
    </x-slot>

    @php
        $sortOptions = [
            'newest' => 'Jaunākie',
            'price_asc' => 'Cena: no zemākās',
            'price_desc' => 'Cena: no augstākās',
            'year_desc' => 'Gads: jaunākie',
            'year_asc' => 'Gads: vecākie',
        ];
        $activeFilters = collect($filters ?? [])->filter(fn ($value) => filled($value));
    @endphp

    <div class="space-y-8">
        <form
            method="GET"
            x-data="{ open:false }"
            @keydown.escape.window="open=false"
            class="relative rounded-3xl border border-gray-200 bg-white/90 p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/80"
        >
            <!-- Toolbar -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <!-- Mobile: poga kā index -->
                    <div class="sm:hidden w-full">
                        <button
                            type="button"
                            @click="open=!open"
                            :aria-expanded="open.toString()"
                            class="w-full rounded-2xl border border-slate-400/40 bg-slate-100/10 px-4 py-2 text-center text-sm font-semibold
                                   text-slate-800 hover:bg-slate-100/30 dark:border-slate-600/50 dark:bg-slate-800/40 dark:text-slate-100 dark:hover:bg-slate-800/60"
                        >
                            <span x-show="!open">Rādīt filtrus ▼</span>
                            <span x-show="open">Paslēpt filtrus ▲</span>
                        </button>
                    </div>

                    <!-- Desktop: aktīvo filtru čipsi -->
                    <div class="hidden flex-wrap items-center gap-2 text-xs text-gray-500 sm:flex dark:text-gray-300">
                        @if($activeFilters->isNotEmpty())
                            <span class="rounded-full bg-[#2B7A78]/10 px-3 py-1 text-[#2B7A78]">Aktīvie filtri:</span>
                            @foreach($activeFilters as $key => $value)
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600 dark:bg-gray-800 dark:text-gray-200">
                                    {{ ucfirst(str_replace('_',' ',$key)) }}: <strong>{{ $value }}</strong>
                                </span>
                            @endforeach
                        @else
                            <span>Filtri nav piemēroti.</span>
                        @endif
                    </div>
                </div>

                <!-- Desktop darbības -->
                <div class="hidden gap-3 sm:flex">
                    <a href="{{ route('listings.mine') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        Notīrīt
                    </a>
                    <button type="submit" class="btn btn-primary">Atlasīt</button>
                </div>
            </div>

            <!-- Desktop lauki -->
            <div class="mt-6 hidden grid-cols-3 gap-6 sm:grid">
                <div>
                    <label for="search" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Meklēt</label>
                    <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}"
                           :disabled="open"
                           placeholder="Meklēt pēc nosaukuma vai apraksta"
                           class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                </div>

                <div>
                    <label for="status" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Statuss</label>
                    <select id="status" name="status" :disabled="open"
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <option value="">Visi</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Kārtošana</label>
                    <select id="sort" name="sort" :disabled="open"
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Mobile dropdown -->
            <div x-cloak x-show="open" @click.outside="open=false" x-transition.origin.top class="sm:hidden">
                <div class="mt-4 rounded-2xl border border-slate-300 bg-white p-4 shadow-lg dark:border-slate-700 dark:bg-slate-900">
                    <div class="max-h-[70vh] space-y-4 overflow-y-auto pr-1">
                        <div>
                            <label for="m_search" class="text-xs font-semibold text-slate-700 dark:text-slate-200">Meklēt</label>
                            <input id="m_search" name="search" type="text" value="{{ $filters['search'] ?? '' }}"
                                   :disabled="!open"
                                   placeholder="Meklēt pēc nosaukuma vai apraksta"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        </div>

                        <div>
                            <label for="m_status" class="text-xs font-semibold text-slate-700 dark:text-slate-200">Statuss</label>
                            <select id="m_status" name="status" :disabled="!open"
                                    class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                <option value="">Visi</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="m_sort" class="text-xs font-semibold text-slate-700 dark:text-slate-200">Kārtošana</label>
                            <select id="m_sort" name="sort" :disabled="!open"
                                    class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @foreach($sortOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sticky action bar -->
                        <div class="sticky bottom-0 -mx-4 mt-2 border-t border-slate-200 bg-white/95 px-4 py-3 backdrop-blur dark:border-slate-700 dark:bg-slate-900/95">
                            <div class="flex gap-3">
                                <a href="{{ route('listings.mine') }}"
                                   class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                                    Notīrīt
                                </a>
                                <button type="submit" class="btn btn-primary flex-1">Atlasīt</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile: darbības zem toolbar, ja vajag arī šeit
                 <div class="mt-4 sm:hidden flex gap-3">
                   ...
                 </div>
            -->
        </form>

        @if($listings->count())
            <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($listings as $listing)
                    @include('listings.partials.card', [
                        'listing' => $listing,
                        'favoriteIds' => $favoriteIds ?? []
                    ])
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $listings->onEachSide(1)->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white/70 p-12 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900/60">
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-100">
                    Te vēl nav neviena sludinājuma.
                </p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                    Sāc ar jaunu sludinājumu – augšupielādētās bildes un informācija būs redzama šajā sarakstā.
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
