@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                Pievienot jaunu sludinājumu
            </h2>
            <p class="text-sm text-gray-500">Ievadi automašīnas informāciju un pievieno bildes, lai tās tiktu kompresētas un parādītas galerijā.</p>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-5xl">
        <div class="space-y-10 rounded-3xl bg-white/80 p-8 shadow-xl ring-1 ring-gray-100 backdrop-blur">
            <form
                method="POST"
                action="{{ route('listings.store') }}"
                enctype="multipart/form-data"
                class="space-y-10"
                x-data="listingForm(@json($carData), @json(old('marka')), @json(old('modelis')))"
            >
                @csrf

                <section class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="marka">Marka</label>
                            <select
                                id="marka"
                                name="marka"
                                x-model="selectedBrand"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                required
                            >
                                <option value="">Izvēlies marku</option>
                                <template x-for="brand in availableBrands" :key="brand">
                                    <option :value="brand" x-text="brand"></option>
                                </template>
                            </select>
                            @error('marka')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="modelis">Modelis</label>
                            <select
                                id="modelis"
                                name="modelis"
                                x-model="selectedModel"
                                :disabled="! selectedBrand"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:bg-gray-100"
                                required
                            >
                                <option value="">Izvēlies modeli</option>
                                <template x-for="model in availableModels" :key="model">
                                    <option :value="model" x-text="model"></option>
                                </template>
                            </select>
                            @error('modelis')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="gads">Gads</label>
                            <input id="gads" type="number" name="gads" value="{{ old('gads') }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('gads')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="nobraukums">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums" value="{{ old('nobraukums') }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('nobraukums')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="cena">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena" value="{{ old('cena') }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('cena')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="degviela">Degviela</label>
                            <select id="degviela" name="degviela" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                                <option {{ old('degviela') === 'Benzīns' ? 'selected' : '' }}>Benzīns</option>
                                <option {{ old('degviela') === 'Dīzelis' ? 'selected' : '' }}>Dīzelis</option>
                                <option {{ old('degviela') === 'Elektriska' ? 'selected' : '' }}>Elektriska</option>
                                <option {{ old('degviela') === 'Hibrīds' ? 'selected' : '' }}>Hibrīds</option>
                            </select>
                            @error('degviela')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="parnesumkarba">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                                <option {{ old('parnesumkarba') === 'Manuālā' ? 'selected' : '' }}>Manuālā</option>
                                <option {{ old('parnesumkarba') === 'Automātiskā' ? 'selected' : '' }}>Automātiskā</option>
                            </select>
                            @error('parnesumkarba')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700" for="apraksts">Apraksts</label>
                            <textarea id="apraksts" name="apraksts" rows="4" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">{{ old('apraksts') }}</textarea>
                            @error('apraksts')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="status">Statuss</label>
                            <select id="status" name="status" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                                <option value="{{ Listing::STATUS_AVAILABLE }}" @selected(old('status', Listing::STATUS_AVAILABLE) === Listing::STATUS_AVAILABLE)>Pieejams</option>
                                <option value="{{ Listing::STATUS_RESERVED }}" @selected(old('status') === Listing::STATUS_RESERVED)>Rezervēts</option>
                                <option value="{{ Listing::STATUS_SOLD }}" @selected(old('status') === Listing::STATUS_SOLD)>Pārdots</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700" for="contact_info">Kontakta informācija</label>
                            <textarea id="contact_info" name="contact_info" rows="3" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" placeholder="Telefona numurs, e-pasts vai cita informācija">{{ old('contact_info') }}</textarea>
                            @error('contact_info')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="show_contact" value="1" @checked(old('show_contact')) class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Rādīt kontaktinformāciju sludinājumā
                            </label>
                            @error('show_contact')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Auto bildes</h3>
                            <p class="text-sm text-gray-500">Velc un nomet bildes vai izvēlies tās no ierīces. Tās automātiski tiks samazinātas līdz optimālam izmēram.</p>
                        </div>
                        <button type="button" @click="$refs.fileInput.click()" class="inline-flex items-center rounded-xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-100">
                            Pievienot bildes
                        </button>
                    </div>

                    <div
                        @dragover.prevent="dragover = true"
                        @dragleave.prevent="dragover = false"
                        @drop.prevent="handleDrop($event)"
                        :class="{'border-indigo-400 bg-indigo-50/80': dragover}"
                        class="flex min-h-[180px] flex-col items-center justify-center gap-4 rounded-2xl border-2 border-dashed border-indigo-200 bg-white/70 p-8 text-center transition"
                    >
                        <template x-if="files.length === 0">
                            <div class="space-y-2">
                                <span class="inline-flex items-center justify-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-600">Vairākas bildes</span>
                                <p class="text-sm text-gray-500">Atbalstīti formāti: JPG, PNG, WEBP. Maks. izmērs 2MB katrai bildei.</p>
                            </div>
                        </template>

                        <template x-if="files.length > 0">
                            <div class="grid w-full grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                        <img :src="file.url" class="h-28 w-full object-cover transition duration-200 group-hover:scale-105" alt="Augšupielādētā bilde">
                                        <button type="button" @click="remove(index)" class="absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-xs font-semibold text-white transition hover:bg-black/80">×</button>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <input type="file" name="images[]" multiple class="hidden" x-ref="fileInput" @change="handleFiles($event)" accept="image/*">
                    </div>

                    @error('images')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </section>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">Ar augšupielādi tu apliecini, ka bilde nepārkāpj autortiesības.</p>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-3 text-base font-semibold text-white shadow-lg transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Ievietot sludinājumu
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('listings.partials.car-scripts')
</x-app-layout>
