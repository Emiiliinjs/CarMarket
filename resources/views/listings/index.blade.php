<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-white">Visi auto sludinājumi</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">Apskati svaigākos piedāvājumus – bildes tiek optimizētas, lai ielāde būtu ātra jebkurā ierīcē.</p>
            </div>
        </div>
    </x-slot>

    @php
        $activeFilters = collect($filters ?? [])->filter(fn ($value, $key) => filled($value) && $key !== 'sort');
    @endphp

    <div
        class="space-y-8"
        x-data="listingsPage(@json($carData), @json($filters['marka'] ?? ''), @json($filters['modelis'] ?? ''))"
    >
        <form
            method="GET"
            id="filters-panel"
            class="rounded-3xl border border-gray-200 bg-white/80 p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/70"
        >
            <div class="grid gap-6 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <label for="search" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Meklēt pēc atslēgvārda</label>
                    <div class="mt-2 flex rounded-xl border border-gray-200 bg-white shadow-sm focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800">
                        <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Meklēt pēc markas, modeļa vai apraksta" class="w-full rounded-xl border-0 bg-transparent px-4 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none dark:text-gray-200" />
                        <button type="submit" class="me-2 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Meklēt</button>
                    </div>
                </div>

                <div class="grid gap-6 lg:col-span-8 lg:grid-cols-6">
                    <div class="lg:col-span-2">
                        <label for="marka" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Marka</label>
                        <select
                            id="marka"
                            name="marka"
                            x-model="selectedBrand"
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        >
                            <option value="">Visas markas</option>
                            <template x-for="brand in availableBrands" :key="brand">
                                <option :value="brand" x-text="brand"></option>
                            </template>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
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

                    <div class="lg:col-span-1">
                        <label for="price_min" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Cena no (€)</label>
                        <input id="price_min" name="price_min" type="number" min="0" value="{{ $filters['price_min'] ?? '' }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" />
                    </div>

                    <div class="lg:col-span-1">
                        <label for="price_max" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Cena līdz (€)</label>
                        <input id="price_max" name="price_max" type="number" min="0" value="{{ $filters['price_max'] ?? '' }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" />
                    </div>

                    <div class="lg:col-span-1">
                        <label for="year_from" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Gads no</label>
                        <input id="year_from" name="year_from" type="number" min="1900" max="{{ date('Y') + 1 }}" value="{{ $filters['year_from'] ?? '' }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" />
                    </div>

                    <div class="lg:col-span-1">
                        <label for="year_to" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Gads līdz</label>
                        <input id="year_to" name="year_to" type="number" min="1900" max="{{ date('Y') + 1 }}" value="{{ $filters['year_to'] ?? '' }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" />
                    </div>

                    <div class="lg:col-span-2">
                        <label for="degviela" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Degvielas tips</label>
                        <select id="degviela" name="degviela" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            <option value="">Visi</option>
                            @foreach($fuelOptions as $fuel)
                                <option value="{{ $fuel }}" @selected(($filters['degviela'] ?? '') === $fuel)>{{ $fuel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Statuss</label>
                        <select id="status" name="status" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            <option value="">Visi</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="sort" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Kārtošana</label>
                        <select id="sort" name="sort" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            @foreach($sortOptions as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-300">
                    @if($activeFilters->isNotEmpty())
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">Aktīvie filtri:</span>
                        @foreach($activeFilters as $key => $value)
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600 dark:bg-gray-800 dark:text-gray-200">{{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong></span>
                        @endforeach
                    @else
                        <span>Netiek izmantoti papildu filtri.</span>
                    @endif
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">Notīrīt</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Atlasīt</button>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

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
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-100">Nav neviena sludinājuma.</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Sāc ar pirmo auto – pievieno sludinājumu un augšupielādētās bildes tiks saglabātas galerijā.</p>
            </div>
        @endif

        <div id="compare-panel" class="sticky bottom-6 z-20 hidden rounded-3xl border border-indigo-200 bg-white/95 p-6 shadow-2xl backdrop-blur dark:border-indigo-500/40 dark:bg-gray-900/90">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Salīdzināšanas rīks</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Atķeksē līdz 3 sludinājumiem, lai salīdzinātu cenu un specifikāciju.</p>
                </div>
                <button type="button" id="compare-clear" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">Notīrīt izvēli</button>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Auto</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Gads</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Cena</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Nobraukums</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Degviela</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Pārnesumkārba</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Statuss</th>
                        </tr>
                    </thead>
                    <tbody id="compare-table" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                </table>
            </div>

            <p id="compare-warning" class="mt-3 hidden rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-xs text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200"></p>
        </div>
    </div>

    @include('listings.partials.car-scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const comparePanel = document.getElementById('compare-panel');
            const compareTable = document.getElementById('compare-table');
            const clearButton = document.getElementById('compare-clear');
            const warningBox = document.getElementById('compare-warning');
            const maxItems = 3;
            const formatCurrency = new Intl.NumberFormat('lv-LV', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });
            const formatNumber = new Intl.NumberFormat('lv-LV');

            const state = {
                items: [],
                add(item) {
                    if (this.items.length >= maxItems) {
                        return false;
                    }

                    this.items.push(item);
                    this.render();

                    return true;
                },
                remove(id) {
                    this.items = this.items.filter((entry) => entry.id !== id);
                    this.render();
                },
                clear() {
                    this.items = [];
                    this.render();
                },
                render() {
                    compareTable.innerHTML = '';
                    warningBox.classList.add('hidden');

                    if (this.items.length === 0) {
                        comparePanel.classList.add('hidden');
                        return;
                    }

                    comparePanel.classList.remove('hidden');

                    this.items.forEach((item) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">${item.marka} ${item.modelis}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${item.gads}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${formatCurrency.format(item.cena)}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${formatNumber.format(item.nobraukums)} km</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${item.degviela}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${item.parnesumkarba}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${item.status}</td>
                        `;

                        compareTable.appendChild(row);
                    });
                },
            };

            document.querySelectorAll('.js-compare-checkbox').forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    const card = checkbox.closest('[data-listing-card]');

                    if (! card) {
                        return;
                    }

                    const item = {
                        id: card.dataset.id,
                        marka: card.dataset.marka,
                        modelis: card.dataset.modelis,
                        gads: Number(card.dataset.gads),
                        cena: Number(card.dataset.cena),
                        nobraukums: Number(card.dataset.nobraukums),
                        degviela: card.dataset.degviela,
                        parnesumkarba: card.dataset.parnesumkarba,
                        status: card.dataset.status,
                    };

                    if (checkbox.checked) {
                        const added = state.add(item);

                        if (! added) {
                            checkbox.checked = false;
                            warningBox.textContent = 'Salīdzināšanai iespējams izvēlēties ne vairāk kā trīs sludinājumus vienlaikus.';
                            warningBox.classList.remove('hidden');
                        }
                    } else {
                        state.remove(item.id);
                    }
                });
            });

            clearButton?.addEventListener('click', () => {
                state.clear();
                document.querySelectorAll('.js-compare-checkbox').forEach((checkbox) => {
                    checkbox.checked = false;
                });
            });
        });
    </script>
</x-app-layout>
