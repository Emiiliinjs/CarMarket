@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                Pievienot jaunu sludinājumu
            </h2>
            <p class="text-sm text-gray-500">
                Ievadi automašīnas informāciju un pievieno bildes, lai tās tiktu kompresētas un parādītas galerijā.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-5xl">
        <div class="space-y-10 rounded-3xl bg-white/80 p-8 shadow-xl ring-1 ring-gray-100 backdrop-blur">
            <form
                method="POST"
                action="{{ route('listings.store') }}"
                enctype="multipart/form-data"
                class="space-y-10"
                x-data="listingForm(
                    JSON.parse(document.getElementById('car-models-data').textContent),
                    '{{ old('marka') }}',
                    '{{ old('modelis') }}'
                )"
                x-init="init()"
            >
                @csrf

                <!-- ================== Auto pamatinformācija ================== -->
                <section class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Marka -->
                        <div>
                            <label for="marka" class="text-sm font-semibold text-gray-700">Marka</label>
                            <select
                                id="marka"
                                name="marka"
                                x-model="selectedBrand"
                                @change="updateModels()"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                       focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                required
                            >
                                <option value="">Izvēlies marku</option>
                                <template x-for="brand in availableBrands" :key="brand">
                                    <option :value="brand" x-text="brand"></option>
                                </template>
                            </select>
                            @error('marka') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Modelis -->
                        <div>
                            <label for="modelis" class="text-sm font-semibold text-gray-700">Modelis</label>
                            <select
                                id="modelis"
                                name="modelis"
                                x-model="selectedModel"
                                :disabled="!selectedBrand"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                       focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30
                                       disabled:cursor-not-allowed disabled:bg-gray-100"
                                required
                            >
                                <option value="">Izvēlies modeli</option>
                                <template x-for="model in availableModels" :key="model">
                                    <option :value="model" x-text="model"></option>
                                </template>
                            </select>
                            @error('modelis') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Gads -->
                        <div>
                            <label for="gads" class="text-sm font-semibold text-gray-700">Gads</label>
                            <input id="gads" type="number" name="gads"
                                   value="{{ old('gads') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                          focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                   required>
                            @error('gads') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nobraukums -->
                        <div>
                            <label for="nobraukums" class="text-sm font-semibold text-gray-700">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums"
                                   value="{{ old('nobraukums') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                          focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                   required>
                            @error('nobraukums') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Cena -->
                        <div>
                            <label for="cena" class="text-sm font-semibold text-gray-700">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena"
                                   value="{{ old('cena') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                          focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                   required>
                            @error('cena') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Degviela -->
                        <div>
                            <label for="degviela" class="text-sm font-semibold text-gray-700">Degviela</label>
                            <select id="degviela" name="degviela"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                           focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                    required>
                                <option value="Benzīns" @selected(old('degviela') === 'Benzīns')>Benzīns</option>
                                <option value="Dīzelis" @selected(old('degviela') === 'Dīzelis')>Dīzelis</option>
                                <option value="Elektriska" @selected(old('degviela') === 'Elektriska')>Elektriska</option>
                                <option value="Hibrīds" @selected(old('degviela') === 'Hibrīds')>Hibrīds</option>
                            </select>
                        </div>

                        <!-- Pārnesumkārba -->
                        <div>
                            <label for="parnesumkarba" class="text-sm font-semibold text-gray-700">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                           focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                    required>
                                <option value="Manuālā" @selected(old('parnesumkarba') === 'Manuālā')>Manuālā</option>
                                <option value="Automātiskā" @selected(old('parnesumkarba') === 'Automātiskā')>Automātiskā</option>
                            </select>
                        </div>
                    </div>

                    <!-- Apraksts -->
                    <div>
                        <label for="apraksts" class="text-sm font-semibold text-gray-700">Apraksts</label>
                        <textarea id="apraksts" name="apraksts" rows="4"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                         focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('apraksts') }}</textarea>
                    </div>

                    <!-- Statuss -->
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-700">Statuss</label>
                        <select id="status" name="status"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                       focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30" required>
                            <option value="{{ Listing::STATUS_AVAILABLE }}" @selected(old('status') === Listing::STATUS_AVAILABLE)>Pieejams</option>
                            <option value="{{ Listing::STATUS_RESERVED }}" @selected(old('status') === Listing::STATUS_RESERVED)>Rezervēts</option>
                            <option value="{{ Listing::STATUS_SOLD }}" @selected(old('status') === Listing::STATUS_SOLD)>Pārdots</option>
                        </select>
                    </div>

                    <!-- Kontaktinformācija -->
                    <div>
                        <label for="contact_info" class="text-sm font-semibold text-gray-700">Kontakta informācija</label>
                        <textarea id="contact_info" name="contact_info" rows="3"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 shadow-sm
                                         focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('contact_info') }}</textarea>
                        <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="show_contact" value="1" @checked(old('show_contact'))
                                   class="h-4 w-4 rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                            Rādīt kontaktinformāciju sludinājumā
                        </label>
                    </div>
                </section>

                <!-- ================== Bildes (Drag & Drop) ================== -->
                <section class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Auto bildes</h3>
                            <p class="text-sm text-gray-500">Velc un nomet bildes vai izvēlies tās no ierīces. Tās automātiski tiks samazinātas līdz optimālam izmēram.</p>
                        </div>
                        <button type="button" @click="$refs.fileInput.click()" class="btn btn-light">Pievienot bildes</button>
                    </div>

                    <div
                        @dragover.prevent="dragover = true"
                        @dragleave.prevent="dragover = false"
                        @drop.prevent="handleDrop($event)"
                        :class="{'border-[#2B7A78]/50 bg-[#2B7A78]/15': dragover}"
                        class="flex min-h-[180px] flex-col items-center justify-center gap-4 rounded-2xl border-2 border-dashed border-[#2B7A78]/30 bg-white/70 p-8 text-center transition"
                    >
                        <template x-if="files.length === 0">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">Atbalstīti formāti: JPG, PNG, WEBP. Maks. izmērs 2MB katrai bildei.</p>
                            </div>
                        </template>

                        <template x-if="files.length > 0">
                            <div class="grid w-full grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                        <img :src="file.url" class="h-28 w-full object-cover transition duration-200 group-hover:scale-105">
                                        <button type="button" @click="remove(index)" class="absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-xs font-semibold text-white">×</button>
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

                <!-- ================== Submit ================== -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">Ar augšupielādi tu apliecini, ka bilde nepārkāpj autortiesības.</p>
                    <button type="submit" class="btn btn-primary text-base">Ievietot sludinājumu</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JSON car data priekš Alpine --}}
    <script id="car-models-data" type="application/json">
        {!! json_encode($carModels, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- Alpine helper funkcija --}}
    <script>
        function listingForm(carData, initialBrand = '', initialModel = '') {
            return {
                carData,
                availableBrands: Object.keys(carData),
                availableModels: [],
                selectedBrand: initialBrand || '',
                selectedModel: initialModel || '',
                files: [],
                dragover: false,

                init() {
                    if (this.selectedBrand) this.updateModels();
                },

                updateModels() {
                    this.availableModels = this.carData[this.selectedBrand] || [];
                    if (!this.availableModels.includes(this.selectedModel)) {
                        this.selectedModel = '';
                    }
                },

                handleFiles(event) {
                    const selected = Array.from(event.target.files);
                    selected.forEach(file => {
                        this.files.push({ file, url: URL.createObjectURL(file) });
                    });
                    this.syncInput();
                },

                handleDrop(e) {
                    const dropped = Array.from(e.dataTransfer.files);
                    dropped.forEach(file => {
                        this.files.push({ file, url: URL.createObjectURL(file) });
                    });
                    this.syncInput();
                    this.dragover = false;
                },

                remove(index) {
                    this.files.splice(index, 1);
                    this.syncInput();
                },

                syncInput() {
                    const dt = new DataTransfer();
                    this.files.forEach(f => dt.items.add(f.file));
                    this.$refs.fileInput.files = dt.files;
                }
            }
        }
    </script>
</x-app-layout>
