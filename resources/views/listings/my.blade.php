<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-white">Mani sludinājumi</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">Pārvaldi savus auto piedāvājumus, seko apstiprinājuma statusam un atjauno informāciju jebkurā brīdī.</p>
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
            class="rounded-3xl border border-gray-200 bg-white/80 p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/70"
            x-data="carSelection(
                @json($carData),
                @json($filters['marka'] ?? ''),
                @json($filters['modelis'] ?? ''),
                @json($filters['search'] ?? '')
            )"
            x-init="init()"
        >
            <div class="grid gap-6 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label for="search" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Meklēt</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        x-model="searchQuery"
                        list="my-listings-search-options"
                        placeholder="Meklēt pēc markas, modeļa vai apraksta"
                        class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                    >
                </div>

                <datalist id="my-listings-search-options">
                    <template x-for="option in searchOptions" :key="option">
                        <option :value="option"></option>
                    </template>
                </datalist>

                <div>
                    <label for="marka" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Marka</label>
                    <select
                        id="marka"
                        name="marka"
                        x-model="selectedBrand"
                        class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                    >
                        <option value="">Visas markas</option>
                        @foreach(array_keys($carData) as $brand)
                            <option
                                value="{{ $brand }}"
                                @selected(($filters['marka'] ?? '') === $brand)
                                x-bind:hidden="availableBrands.length"
                                x-bind:disabled="availableBrands.length"
                            >
                                {{ $brand }}
                            </option>
                        @endforeach
                        <template x-for="brand in availableBrands" :key="brand">
                            <option :value="brand" x-text="brand"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="modelis" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Modelis</label>
                    <select
                        id="modelis"
                        name="modelis"
                        x-model="selectedModel"
                        :disabled="! selectedBrand"
                        class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 disabled:cursor-not-allowed disabled:bg-gray-100 dark:disabled:bg-gray-800/60"
                    >
                        <option value="">Visi modeļi</option>
                        <template x-for="model in availableModels" :key="model">
                            <option :value="model" x-text="model"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="status" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Statuss</label>
                    <select id="status" name="status" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <option value="">Visi</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Kārtošana</label>
                    <select id="sort" name="sort" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-300">
                    @if($activeFilters->isNotEmpty())
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-200">Aktīvie filtri:</span>
                        @foreach($activeFilters as $key => $value)
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600 dark:bg-gray-800 dark:text-gray-200">{{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong></span>
                        @endforeach
                    @else
                        <span>Filtri nav piemēroti.</span>
                    @endif
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('listings.mine') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">Notīrīt</a>
                    <button type="submit" class="btn btn-primary">Atlasīt</button>
                </div>
            </div>
        </form>

        @if($listings->count())
            <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($listings as $listing)
                    @include('listings.partials.card', ['listing' => $listing, 'favoriteIds' => $favoriteIds ?? []])
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $listings->onEachSide(1)->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white/70 p-12 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900/60">
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-100">Te vēl nav neviena sludinājuma.</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Sāc ar jaunu sludinājumu – augšupielādētās bildes un informācija būs redzama šajā sarakstā.</p>
            </div>
        @endif
    </div>
    @include('listings.partials.car-scripts')
</x-app-layout>
