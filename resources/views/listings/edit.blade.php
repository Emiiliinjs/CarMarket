@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                    Rediģēt sludinājumu: {{ $listing->marka }} {{ $listing->modelis }}
                </h2>
                <p class="text-sm text-gray-500">
                    Atjaunini informāciju un pievieno jaunas bildes – esošās saglabāsies, ja tās nedzēs.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-5xl">
        <div class="space-y-10 rounded-3xl bg-white/80 p-8 shadow-xl ring-1 ring-gray-100 backdrop-blur">
            <form
                method="POST"
                action="{{ route('listings.update', $listing->id) }}"
                enctype="multipart/form-data"
                class="space-y-10"
                x-data="listingForm(
                    '{{ old('marka', $listing->marka) }}',
                    '{{ old('modelis', $listing->modelis) }}'
                )"
                x-init="init()"
            >
                @csrf
                @method('PUT')

                <section class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Marka -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="marka">Marka</label>
                            <select
                                id="marka"
                                name="marka"
                                x-model="selectedBrand"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                       focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
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

                            <!-- Debug -->
                            <p class="mt-1 text-xs text-gray-500">selectedBrand: <span x-text="selectedBrand"></span></p>
                            <p class="mt-1 text-xs text-gray-500">availableBrands: <span x-text="availableBrands.length"></span></p>
                        </div>

                        <!-- Modelis -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="modelis">Modelis</label>
                            <select
                                id="modelis"
                                name="modelis"
                                x-model="selectedModel"
                                :disabled="! selectedBrand"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                       focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30
                                       disabled:cursor-not-allowed disabled:bg-gray-100"
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

                            <!-- Debug -->
                            <p class="mt-1 text-xs text-gray-500">selectedModel: <span x-text="selectedModel"></span></p>
                            <p class="mt-1 text-xs text-gray-500">availableModels: <span x-text="availableModels.length"></span></p>
                        </div>

                        <!-- Pārējie lauki -->
                        <div>
                            <label for="gads" class="text-sm font-semibold text-gray-700">Gads</label>
                            <input id="gads" type="number" name="gads"
                                   value="{{ old('gads', $listing->gads) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                          focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                   required>
                            @error('gads') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="nobraukums" class="text-sm font-semibold text-gray-700">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums"
                                   value="{{ old('nobraukums', $listing->nobraukums) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                          focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                   required>
                            @error('nobraukums') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="cena" class="text-sm font-semibold text-gray-700">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena"
                                   value="{{ old('cena', $listing->cena) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                          focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                   required>
                            @error('cena') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="degviela" class="text-sm font-semibold text-gray-700">Degviela</label>
                            <select id="degviela" name="degviela"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                           focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                    required>
                                <option value="Benzīns" @selected(old('degviela', $listing->degviela) === 'Benzīns')>Benzīns</option>
                                <option value="Dīzelis" @selected(old('degviela', $listing->degviela) === 'Dīzelis')>Dīzelis</option>
                                <option value="Elektriska" @selected(old('degviela', $listing->degviela) === 'Elektriska')>Elektriska</option>
                                <option value="Hibrīds" @selected(old('degviela', $listing->degviela) === 'Hibrīds')>Hibrīds</option>
                            </select>
                            @error('degviela') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="parnesumkarba" class="text-sm font-semibold text-gray-700">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                           focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                    required>
                                <option value="Manuālā" @selected(old('parnesumkarba', $listing->parnesumkarba) === 'Manuālā')>Manuālā</option>
                                <option value="Automātiskā" @selected(old('parnesumkarba', $listing->parnesumkarba) === 'Automātiskā')>Automātiskā</option>
                            </select>
                            @error('parnesumkarba') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Apraksts + Status + Kontaktinfo -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="apraksts" class="text-sm font-semibold text-gray-700">Apraksts</label>
                            <textarea id="apraksts" name="apraksts" rows="4"
                                      class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm
                                             focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">{{ old('apraksts', $listing->apraksts) }}</textarea>
                            @error('apraksts') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="text-sm font-semibold text-gray-700">Statuss</label>
                            <select id="status" name="status"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                           focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                    required>
                                <option value="{{ Listing::STATUS_AVAILABLE }}" @selected(old('status', $listing->status) === Listing::STATUS_AVAILABLE)>Pieejams</option>
                                <option value="{{ Listing::STATUS_RESERVED }}" @selected(old('status', $listing->status) === Listing::STATUS_RESERVED)>Rezervēts</option>
                                <option value="{{ Listing::STATUS_SOLD }}" @selected(old('status', $listing->status) === Listing::STATUS_SOLD)>Pārdots</option>
                            </select>
                            @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="contact_info" class="text-sm font-semibold text-gray-700">Kontakta informācija</label>
                            <textarea id="contact_info" name="contact_info" rows="3"
                                      class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm
                                             focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">{{ old('contact_info', $listing->contact_info) }}</textarea>
                            @error('contact_info') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="show_contact" value="1"
                                       @checked(old('show_contact', $listing->show_contact))
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Rādīt kontaktinformāciju sludinājumā
                            </label>
                        </div>
                    </div>
                </section>

                <!-- Esošās bildes -->
                @if($listing->galleryImages->count())
                    <section class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Esošās bildes</h3>
                                <p class="text-sm text-gray-500">Vari dzēst nevajadzīgās bildes – tās tiks noņemtas gan no galerijas, gan glabātuves.</p>
                            </div>
                            <span class="text-sm text-gray-500">
                                Kopā {{ $listing->galleryImages->count() }}
                                {{ $listing->galleryImages->count() === 1 ? 'bilde' : 'bildes' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($listing->galleryImages as $image)
                                <div class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                    <img src="{{ route('listing-images.show', $image) }}" alt="Esoša auto bilde"
                                         class="h-32 w-full object-cover transition duration-200 group-hover:scale-105">
                                    <button type="button"
                                            class="absolute inset-x-2 bottom-2 flex justify-end bg-transparent focus:outline-none"
                                            onclick="if(confirm('Vai tiešām dzēst šo attēlu?')) document.getElementById('delete-image-form-{{ $image->id }}').submit();">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-black/65 px-3 py-1 text-xs font-semibold text-white">Dzēst</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Jaunas bildes -->
                <section class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pievieno jaunas bildes</h3>
                            <p class="text-sm text-gray-500">Esošās bildes netiek dzēstas – jaunās tiks kompresētas un pievienotas galerijai.</p>
                        </div>
                        <button type="button" @click="$refs.fileInput.click()" class="btn btn-light">Pievienot bildes</button>
                    </div>

                    <div
                        @dragover.prevent="dragover = true"
                        @dragleave.prevent="dragover = false"
                        @drop.prevent="handleDrop($event)"
                        :class="{'border-indigo-400 bg-indigo-50/80': dragover}"
                        class="flex min-h-[160px] flex-col items-center justify-center gap-4 rounded-2xl border-2 border-dashed border-indigo-200 bg-white/70 p-8 text-center transition"
                    >
                        <template x-if="files.length === 0">
                            <div class="space-y-2">
                                <span class="inline-flex items-center justify-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-600">Velc &amp; nomet</span>
                                <p class="text-sm text-gray-500">Atbalstīti formāti: JPG, PNG, WEBP. Maks. izmērs 2MB katrai bildei.</p>
                            </div>
                        </template>

                        <template x-if="files.length > 0">
                            <div class="grid w-full grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                        <img :src="file.url"
                                             class="h-28 w-full object-cover transition duration-200 group-hover:scale-105"
                                             alt="Augšupielādētā bilde">
                                        <button type="button" @click="remove(index)"
                                                class="absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center
                                                       rounded-full bg-black/60 text-xs font-semibold text-white">×</button>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <input type="file" name="images[]" multiple class="hidden" x-ref="fileInput"
                               @change="handleFiles($event)" accept="image/*">
                    </div>

                    @error('images') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('images.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </section>

                <!-- Submit -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">Saglabājot izmaiņas, jaunās bildes tiks optimizētas un pievienotas galerijai.</p>
                    <button type="submit" class="btn btn-primary text-base">Atjaunināt sludinājumu</button>
                </div>
            </form>

            @if($listing->galleryImages->count())
                @foreach($listing->galleryImages as $image)
                    <form id="delete-image-form-{{ $image->id }}"
                          method="POST"
                          action="{{ route('listing-images.destroy', $image) }}"
                          class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif
        </div>
    </div>

    @include('listings.partials.car-scripts')
</x-app-layout>
