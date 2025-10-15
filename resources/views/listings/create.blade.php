@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                Pievienot jaunu sludinājumu
            </h2>
            <p class="text-sm text-gray-500">
                Ievadi automašīnas informāciju un pievieno bildes – tās tiks saglabātas galerijā.
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

                {{-- Kļūdu bloks --}}
                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ======================== Pamatinformācija ======================== -->
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
                                class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30 appearance-none"
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
                                class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30 appearance-none"
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
                            <label for="gads" class="text-sm font-semibold text-gray-700">Izlaiduma gads</label>
                            <input id="gads" type="number" name="gads"
                                   value="{{ old('gads') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                   required>
                            @error('gads') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nobraukums -->
                        <div>
                            <label for="nobraukums" class="text-sm font-semibold text-gray-700">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums"
                                   value="{{ old('nobraukums') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                   required>
                            @error('nobraukums') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Cena -->
                        <div>
                            <label for="cena" class="text-sm font-semibold text-gray-700">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena"
                                   value="{{ old('cena') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                   required>
                            @error('cena') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Degviela -->
                        <div>
                            <label for="degviela" class="text-sm font-semibold text-gray-700">Degviela</label>
                            <select id="degviela" name="degviela"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30"
                                    required>
                                <option value="Benzīns" @selected(old('degviela') === 'Benzīns')>Benzīns</option>
                                <option value="Dīzelis" @selected(old('degviela') === 'Dīzelis')>Dīzelis</option>
                                <option value="Elektriska" @selected(old('degviela') === 'Elektriska')>Elektriska</option>
                                <option value="Hibrīds" @selected(old('degviela') === 'Hibrīds')>Hibrīds</option>
                            </select>
                            @error('degviela') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Pārnesumkārba -->
                        <div>
                            <label for="parnesumkarba" class="text-sm font-semibold text-gray-700">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30"
                                    required>
                                <option value="Manuālā" @selected(old('parnesumkarba') === 'Manuālā')>Manuālā</option>
                                <option value="Automātiskā" @selected(old('parnesumkarba') === 'Automātiskā')>Automātiskā</option>
                            </select>
                            @error('parnesumkarba') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Motora tilpums -->
                        <div>
                            <label for="motora_tilpums" class="text-sm font-semibold text-gray-700">Motora tilpums (L)</label>
                            <input id="motora_tilpums" type="number" step="0.1" name="motora_tilpums"
                                   value="{{ old('motora_tilpums') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                            @error('motora_tilpums') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Virsbūves tips -->
                        <div>
                            <label for="virsbuves_tips" class="text-sm font-semibold text-gray-700">Virsbūves tips</label>
                            <select id="virsbuves_tips" name="virsbuves_tips"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                                <option value="">Izvēlies</option>
                                <option @selected(old('virsbuves_tips')==='Apvidus')>Apvidus</option>
                                <option @selected(old('virsbuves_tips')==='Hečbeks')>Hečbeks</option>
                                <option @selected(old('virsbuves_tips')==='Kabriolets')>Kabriolets</option>
                                <option @selected(old('virsbuves_tips')==='Kupeja')>Kupeja</option>
                                <option @selected(old('virsbuves_tips')==='Mikroautobuss')>Mikroautobuss</option>
                                <option @selected(old('virsbuves_tips')==='Minivens')>Minivens</option>
                                <option @selected(old('virsbuves_tips')==='Pikaps')>Pikaps</option>
                                <option @selected(old('virsbuves_tips')==='Sedans')>Sedans</option>
                                <option @selected(old('virsbuves_tips')==='Universāls')>Universāls</option>
                            </select>
                            @error('virsbuves_tips') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- VIN -->
                        <div>
                            <label for="vin_numurs" class="text-sm font-semibold text-gray-700">VIN numurs</label>
                            <input id="vin_numurs" type="text" name="vin_numurs"
                                   value="{{ old('vin_numurs') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                            @error('vin_numurs') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Valsts numurzīme -->
                        <div>
                            <label for="valsts_numurzime" class="text-sm font-semibold text-gray-700">Valsts numurzīme</label>
                            <input id="valsts_numurzime" type="text" name="valsts_numurzime"
                                   value="{{ old('valsts_numurzime') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                            @error('valsts_numurzime') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tehniskā apskate -->
                        <div>
                            <label for="tehniska_apskate" class="text-sm font-semibold text-gray-700">Tehniskā apskate</label>
                            <input id="tehniska_apskate" type="date" name="tehniska_apskate"
                                   value="{{ old('tehniska_apskate') }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                            @error('tehniska_apskate') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Apraksts -->
                    <div>
                        <label for="apraksts" class="text-sm font-semibold text-gray-700">Apraksts</label>
                        <textarea id="apraksts" name="apraksts" rows="4"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('apraksts') }}</textarea>
                        @error('apraksts') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Statuss (noklusējums vai izvēle) -->
                    {{-- Ja gribi slēpt: atkomentē nākamo rindu un izdzēs redzamo select --}}
                    {{-- <input type="hidden" name="status" value="{{ Listing::STATUS_AVAILABLE }}"> --}}
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-700">Statuss</label>
                        <select id="status" name="status"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                            <option value="{{ Listing::STATUS_AVAILABLE }}" @selected(old('status') === Listing::STATUS_AVAILABLE)>Pieejams</option>
                            <option value="{{ Listing::STATUS_RESERVED }}" @selected(old('status') === Listing::STATUS_RESERVED)>Rezervēts</option>
                            <option value="{{ Listing::STATUS_SOLD }}" @selected(old('status') === Listing::STATUS_SOLD)>Pārdots</option>
                        </select>
                        @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kontaktinformācija -->
                    <div>
                        <label for="contact_info" class="text-sm font-semibold text-gray-700">Kontakta informācija</label>
                        <textarea id="contact_info" name="contact_info" rows="3"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('contact_info') }}</textarea>
                        @error('contact_info') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                        <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="show_contact" value="1" @checked(old('show_contact'))
                                   class="h-4 w-4 rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                            Rādīt kontaktinformāciju sludinājumā
                        </label>
                        @error('show_contact') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <!-- ======================== Jaunas bildes ======================== -->
                <section class="space-y-4" x-data="imageUploadManager()" x-init="registerInput($refs.input)">
                    <h3 class="text-lg font-semibold text-gray-900">Pievieno auto bildes</h3>

                    <!-- Drag & Drop zona -->
                    <div
                        class="flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 p-8 text-center transition hover:bg-gray-50"
                        :class="{ 'ring-2 ring-[#2B7A78]/50': isDragOver }"
                        @click="$refs.input.click()"
                        @dragover.prevent="onZoneDrag(true)"
                        @dragleave.prevent="onZoneDrag(false)"
                        @drop.prevent="handleZoneDrop($event)"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <p class="text-sm text-gray-600">Ievelc bildes šeit vai <span class="font-medium text-[#2B7A78] underline">izvēlies no ierīces</span></p>
                        <p class="mt-1 text-xs text-gray-400">Atbalstītie formāti: JPG, PNG, WEBP. Max 5 MB gabalā.</p>
                    </div>

                    <!-- Slēptais input -->
                    <input
                        x-ref="input"
                        type="file"
                        name="images[]"
                        multiple
                        accept="image/*"
                        class="hidden"
                        @change="setFiles($event.target.files)"
                    >

                    <!-- Priekšskatījums ar pārkārtošanu -->
                    <template x-if="files.length">
                        <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            <template x-for="(file, index) in files" :key="file.name + index">
                                <div
                                    class="group relative rounded-xl shadow"
                                    draggable="true"
                                    @dragstart="startDrag(index)"
                                    @dragover.prevent
                                    @drop.prevent="handleReorderDrop(index)"
                                    @dragend="endDrag()"
                                >
                                    <img :src="URL.createObjectURL(file)" class="h-32 w-full rounded-xl object-cover">
                                    <div class="pointer-events-none absolute right-2 top-2 flex gap-1 opacity-0 transition group-hover:opacity-100">
                                        <button
                                            type="button"
                                            class="pointer-events-auto inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/80 text-gray-700 shadow"
                                            @click.stop="move(index, index - 1)"
                                            :disabled="index === 0"
                                            title="Pārvietot augstāk"
                                        >↑</button>
                                        <button
                                            type="button"
                                            class="pointer-events-auto inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/80 text-gray-700 shadow"
                                            @click.stop="move(index, index + 1)"
                                            :disabled="index === files.length - 1"
                                            title="Pārvietot zemāk"
                                        >↓</button>
                                    </div>
                                    <p class="mt-1 truncate text-xs text-gray-600" x-text="file.name"></p>
                                </div>
                            </template>
                        </div>
                    </template>

                    @error('images') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('images.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </section>

                <!-- ======================== Submit ======================== -->
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

    {{-- Alpine helperi --}}
    <script>
        function listingForm(carData, initialBrand = '', initialModel = '') {
            return {
                carData,
                availableBrands: Object.keys(carData),
                availableModels: [],
                selectedBrand: initialBrand || '',
                selectedModel: initialModel || '',
                init() { if (this.selectedBrand) this.updateModels(); },
                updateModels() {
                    this.availableModels = this.carData[this.selectedBrand] || [];
                    if (!this.availableModels.includes(this.selectedModel)) this.selectedModel = '';
                }
            }
        }

        function imageUploadManager() {
            return {
                files: [],
                inputEl: null,
                dragIndex: null,
                isDragOver: false,

                registerInput(el) { this.inputEl = el; },

                setFiles(fileList) {
                    this.files = Array.from(fileList ?? []);
                    this.syncInputFiles();
                },

                onZoneDrag(state) { this.isDragOver = state; },

                handleZoneDrop(event) {
                    this.onZoneDrag(false);
                    if (event.dataTransfer?.files?.length) this.setFiles(event.dataTransfer.files);
                },

                startDrag(index) { this.dragIndex = index; },
                endDrag() { this.dragIndex = null; },

                handleReorderDrop(targetIndex) {
                    if (this.dragIndex === null) return;
                    this.move(this.dragIndex, targetIndex);
                    this.endDrag();
                },

                move(from, to) {
                    if (from === to || to < 0 || to >= this.files.length) return;
                    const updated = [...this.files];
                    const [moved] = updated.splice(from, 1);
                    updated.splice(to, 0, moved);
                    this.files = updated;
                    this.syncInputFiles();
                },

                syncInputFiles() {
                    if (!this.inputEl) return;
                    const dt = new DataTransfer();
                    this.files.forEach(f => dt.items.add(f));
                    this.inputEl.files = dt.files;
                },
            };
        }
    </script>
</x-app-layout>
