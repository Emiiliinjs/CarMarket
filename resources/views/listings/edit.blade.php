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
                x-data="listingForm(@json($carModels), @js($listing->marka), @js($listing->modelis))"
                x-init="init()"
            >
                @csrf
                @method('PUT')

                <!-- ======================== Pamatinformācija ======================== -->
                <section class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Marka (read-only) -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Marka</label>
                            <input type="text"
                                   value="{{ $listing->marka }}"
                                   disabled
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-700 shadow-sm" />
                            <input type="hidden" name="marka" value="{{ $listing->marka }}">
                        </div>

                        <!-- Modelis (read-only) -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Modelis</label>
                            <input type="text"
                                   value="{{ $listing->modelis }}"
                                   disabled
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-700 shadow-sm" />
                            <input type="hidden" name="modelis" value="{{ $listing->modelis }}">
                        </div>

                        <!-- Gads -->
                        <div>
                            <label for="gads" class="text-sm font-semibold text-gray-700">Gads</label>
                            <input id="gads" type="number" name="gads" value="{{ old('gads', $listing->gads) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                          focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                            @error('gads') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nobraukums -->
                        <div>
                            <label for="nobraukums" class="text-sm font-semibold text-gray-700">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums" value="{{ old('nobraukums', $listing->nobraukums) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                          focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                            @error('nobraukums') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Cena -->
                        <div>
                            <label for="cena" class="text-sm font-semibold text-gray-700">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena" value="{{ old('cena', $listing->cena) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                          focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                            @error('cena') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Degviela -->
                        <div>
                            <label for="degviela" class="text-sm font-semibold text-gray-700">Degviela</label>
                            <select id="degviela" name="degviela"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                           focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                                <option value="Benzīns" @selected(old('degviela', $listing->degviela) === 'Benzīns')>Benzīns</option>
                                <option value="Dīzelis" @selected(old('degviela', $listing->degviela) === 'Dīzelis')>Dīzelis</option>
                                <option value="Elektriska" @selected(old('degviela', $listing->degviela) === 'Elektriska')>Elektriska</option>
                                <option value="Hibrīds" @selected(old('degviela', $listing->degviela) === 'Hibrīds')>Hibrīds</option>
                            </select>
                        </div>

                        <!-- Pārnesumkārba -->
                        <div>
                            <label for="parnesumkarba" class="text-sm font-semibold text-gray-700">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                           focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                                <option value="Manuālā" @selected(old('parnesumkarba', $listing->parnesumkarba) === 'Manuālā')>Manuālā</option>
                                <option value="Automātiskā" @selected(old('parnesumkarba', $listing->parnesumkarba) === 'Automātiskā')>Automātiskā</option>
                            </select>
                        </div>
                    </div>

                    <!-- Apraksts -->
                    <div>
                        <label for="apraksts" class="text-sm font-semibold text-gray-700">Apraksts</label>
                        <textarea id="apraksts" name="apraksts" rows="4"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm
                                         focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('apraksts', $listing->apraksts) }}</textarea>
                    </div>

                    <!-- Statuss -->
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-700">Statuss</label>
                        <select id="status" name="status"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm
                                       focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30" required>
                            <option value="{{ Listing::STATUS_AVAILABLE }}" @selected(old('status', $listing->status) === Listing::STATUS_AVAILABLE)>Pieejams</option>
                            <option value="{{ Listing::STATUS_RESERVED }}" @selected(old('status', $listing->status) === Listing::STATUS_RESERVED)>Rezervēts</option>
                            <option value="{{ Listing::STATUS_SOLD }}" @selected(old('status', $listing->status) === Listing::STATUS_SOLD)>Pārdots</option>
                        </select>
                    </div>

                    <!-- Kontaktinformācija -->
                    <div>
                        <label for="contact_info" class="text-sm font-semibold text-gray-700">Kontakta informācija</label>
                        <textarea id="contact_info" name="contact_info" rows="3"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm
                                         focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('contact_info', $listing->contact_info) }}</textarea>
                    </div>
                </section>

                <!-- Esošās bildes -->
                @if($listing->galleryImages->count())
                    <section
                        class="space-y-4"
                        x-data="existingImageManager(@json(
                            $listing->galleryImages
                                ->map(function ($image) {
                                    return [
                                        'id' => $image->id,
                                        'url' => asset('storage/'.$image->filename),
                                        'name' => basename($image->filename),
                                    ];
                                })
                                ->values()
                                ->all()
                        ))"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">Esošās bildes</h3>
                        <p class="text-sm text-gray-500">Atzīmē tās bildes, kuras gribi dzēst, un pārvelc vai izmanto bultiņas, lai mainītu secību.</p>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            <template x-for="(image, index) in images" :key="image.id">
                                <div
                                    class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm"
                                    draggable="true"
                                    @dragstart="startDrag(index)"
                                    @dragover.prevent
                                    @drop.prevent="handleReorderDrop(index)"
                                    @dragend="endDrag()"
                                >
                                    <input type="hidden" name="existing_image_order[]" :value="image.id">
                                    <img :src="image.url"
                                         alt="Esoša auto bilde"
                                         class="h-32 w-full object-cover">
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
                                            :disabled="index === images.length - 1"
                                            title="Pārvietot zemāk"
                                        >↓</button>
                                    </div>
                                    <label class="absolute bottom-2 left-2 inline-flex items-center gap-1 rounded bg-black/70 px-2 py-1 text-xs text-white">
                                        <input type="checkbox" name="remove_images[]" :value="image.id">
                                        Dzēst
                                    </label>
                                    <p class="px-2 pb-2 text-xs text-gray-600" x-text="image.name"></p>
                                </div>
                            </template>
                        </div>
                    </section>
                @endif

                <!-- Jaunas bildes -->
                <section class="space-y-4" x-data="imageUploadManager()" x-init="registerInput($refs.input)">
                    <h3 class="text-lg font-semibold text-gray-900">Pievieno jaunas bildes</h3>
                    <div
                        class="flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 p-6 text-center text-sm text-gray-600 transition hover:bg-gray-50"
                        :class="{ 'ring-2 ring-[#2B7A78]/50': isDragOver }"
                        @click="$refs.input.click()"
                        @dragover.prevent="onZoneDrag(true)"
                        @dragleave.prevent="onZoneDrag(false)"
                        @drop.prevent="handleZoneDrop($event)"
                    >
                        <span class="font-medium text-[#2B7A78]">Noklikšķini vai ievelc bildes šeit</span>
                        <span class="mt-1 block text-xs text-gray-400">Atbalstītie formāti: JPG, PNG, WEBP</span>
                    </div>
                    <input
                        x-ref="input"
                        type="file"
                        name="images[]"
                        multiple
                        accept="image/*"
                        class="hidden"
                        @change="setFiles($event.target.files)"
                    >

                    <template x-if="files.length">
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
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

                <!-- Submit -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">Saglabājot izmaiņas, atzīmētās bildes tiks dzēstas, bet jaunās – pievienotas.</p>
                    <button type="submit" class="btn btn-primary text-base">Atjaunināt sludinājumu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine logika (šeit vairs netiek izmantota) -->
    <script>
    function listingForm(carData, initialBrand, initialModel) {
        return {
            carData,
            selectedBrand: initialBrand || '',
            selectedModel: initialModel || '',
            init() {},
            updateModels() {}
        }
    }

    function imageUploadManager() {
        return {
            files: [],
            inputEl: null,
            dragIndex: null,
            isDragOver: false,

            registerInput(el) {
                this.inputEl = el;
            },

            setFiles(fileList) {
                this.files = Array.from(fileList ?? []);
                this.syncInputFiles();
            },

            onZoneDrag(state) {
                this.isDragOver = state;
            },

            handleZoneDrop(event) {
                this.onZoneDrag(false);

                if (event.dataTransfer?.files?.length) {
                    this.setFiles(event.dataTransfer.files);
                }
            },

            startDrag(index) {
                this.dragIndex = index;
            },

            endDrag() {
                this.dragIndex = null;
            },

            handleReorderDrop(targetIndex) {
                if (this.dragIndex === null) {
                    return;
                }

                this.move(this.dragIndex, targetIndex);
                this.endDrag();
            },

            move(from, to) {
                if (from === to || to < 0 || to >= this.files.length) {
                    return;
                }

                const updated = [...this.files];
                const [moved] = updated.splice(from, 1);
                updated.splice(to, 0, moved);
                this.files = updated;
                this.syncInputFiles();
            },

            syncInputFiles() {
                if (! this.inputEl) {
                    return;
                }

                const dataTransfer = new DataTransfer();

                this.files.forEach(file => {
                    dataTransfer.items.add(file);
                });

                this.inputEl.files = dataTransfer.files;
            },
        };
    }

    function existingImageManager(initialImages = []) {
        return {
            images: initialImages,
            dragIndex: null,

            startDrag(index) {
                this.dragIndex = index;
            },

            endDrag() {
                this.dragIndex = null;
            },

            handleReorderDrop(targetIndex) {
                if (this.dragIndex === null) {
                    return;
                }

                this.move(this.dragIndex, targetIndex);
                this.endDrag();
            },

            move(from, to) {
                if (from === to || to < 0 || to >= this.images.length) {
                    return;
                }

                const updated = [...this.images];
                const [moved] = updated.splice(from, 1);
                updated.splice(to, 0, moved);
                this.images = updated;
            },
        };
    }
    </script>
</x-app-layout>
