@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                    Rediģēt sludinājumu: {{ $listing->marka }} {{ $listing->modelis }}
                </h2>
                <p class="text-sm text-gray-500">Atjaunini informāciju, rediģē bildes un to secību.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-5xl">
        <div class="space-y-10 rounded-3xl bg-white/80 p-8 shadow-xl ring-1 ring-gray-100 backdrop-blur">
            <form
                method="POST"
                action="{{ route('listings.update', $listing->id) }}"
                enctype="multipart/form-data"
                x-data="imageGalleryManager({{ Js::from(
                    $listing->galleryImages->map(fn($img) => [
                        'id' => $img->id,
                        'url' => route('listing-images.show', $img),
                        'name' => basename($img->filename),
                        'existing' => true,
                    ])
                ) }})"
                class="space-y-10"
            >
                @csrf
                @method('PUT')

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
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Marka</label>
                            <input
                                type="text"
                                value="{{ $listing->marka }}"
                                disabled
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-700 shadow-sm"
                            >
                            <input type="hidden" name="marka" value="{{ $listing->marka }}">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Modelis</label>
                            <input
                                type="text"
                                value="{{ $listing->modelis }}"
                                disabled
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-700 shadow-sm"
                            >
                            <input type="hidden" name="modelis" value="{{ $listing->modelis }}">
                        </div>

                        <div>
                            <label for="gads" class="text-sm font-semibold text-gray-700">Gads</label>
                            <input
                                id="gads"
                                type="number"
                                name="gads"
                                value="{{ old('gads', $listing->gads) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                required
                            >
                        </div>

                        <div>
                            <label for="nobraukums" class="text-sm font-semibold text-gray-700">Nobraukums (km)</label>
                            <input
                                id="nobraukums"
                                type="number"
                                name="nobraukums"
                                value="{{ old('nobraukums', $listing->nobraukums) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                required
                            >
                        </div>

                        <div>
                            <label for="cena" class="text-sm font-semibold text-gray-700">Cena (€)</label>
                            <input
                                id="cena"
                                type="number"
                                step="0.01"
                                name="cena"
                                value="{{ old('cena', $listing->cena) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                required
                            >
                        </div>

                        <div>
                            <label for="degviela" class="text-sm font-semibold text-gray-700">Degviela</label>
                            <select
                                id="degviela"
                                name="degviela"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                required
                            >
                                @foreach(['Benzīns','Dīzelis','Elektriska','Hibrīds'] as $opt)
                                    <option value="{{ $opt }}" @selected(old('degviela',$listing->degviela)===$opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="parnesumkarba" class="text-sm font-semibold text-gray-700">Pārnesumkārba</label>
                            <select
                                id="parnesumkarba"
                                name="parnesumkarba"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                                required
                            >
                                @foreach(['Manuālā','Automātiskā'] as $opt)
                                    <option value="{{ $opt }}" @selected(old('parnesumkarba',$listing->parnesumkarba)===$opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="motora_tilpums" class="text-sm font-semibold text-gray-700">Motora tilpums (L)</label>
                            <input
                                id="motora_tilpums"
                                type="number"
                                step="0.1"
                                name="motora_tilpums"
                                value="{{ old('motora_tilpums',$listing->motora_tilpums) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                            >
                        </div>

                        <div>
                            <label for="virsbuves_tips" class="text-sm font-semibold text-gray-700">Virsbūves tips</label>
                            @php
                                $bodies=['Apvidus','Hečbeks','Kabriolets','Kupeja','Mikroautobuss','Minivens','Pikaps','Sedans','Universāls'];
                            @endphp
                            <select
                                id="virsbuves_tips"
                                name="virsbuves_tips"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                            >
                                <option value="">—</option>
                                @foreach($bodies as $opt)
                                    <option @selected(old('virsbuves_tips',$listing->virsbuves_tips)===$opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="vin_numurs" class="text-sm font-semibold text-gray-700">VIN numurs</label>
                            <input
                                id="vin_numurs"
                                type="text"
                                name="vin_numurs"
                                value="{{ old('vin_numurs',$listing->vin_numurs) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                            >
                        </div>

                        <div>
                            <label for="valsts_numurzime" class="text-sm font-semibold text-gray-700">Valsts numurzīme</label>
                            <input
                                id="valsts_numurzime"
                                type="text"
                                name="valsts_numurzime"
                                value="{{ old('valsts_numurzime',$listing->valsts_numurzime) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                            >
                        </div>

                        <div>
                            <label for="tehniska_apskate" class="text-sm font-semibold text-gray-700">Tehniskā apskate</label>
                            <input
                                id="tehniska_apskate"
                                type="date"
                                name="tehniska_apskate"
                                value="{{ old('tehniska_apskate', optional($listing->tehniska_apskate)->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="apraksts" class="text-sm font-semibold text-gray-700">Apraksts</label>
                        <textarea
                            id="apraksts"
                            name="apraksts"
                            rows="4"
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                        >{{ old('apraksts',$listing->apraksts) }}</textarea>
                    </div>
                </section>

                <!-- ======================== Papildu informācija ======================== -->
                <section class="space-y-6">
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-700">Statuss</label>
                        <select
                            id="status"
                            name="status"
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                            required
                        >
                            <option value="{{ Listing::STATUS_AVAILABLE }}" @selected(old('status',$listing->status)===Listing::STATUS_AVAILABLE)>Pieejams</option>
                            <option value="{{ Listing::STATUS_RESERVED }}" @selected(old('status',$listing->status)===Listing::STATUS_RESERVED)>Rezervēts</option>
                            <option value="{{ Listing::STATUS_SOLD }}" @selected(old('status',$listing->status)===Listing::STATUS_SOLD)>Pārdots</option>
                        </select>
                    </div>

                    <div>
                        <label for="contact_info" class="text-sm font-semibold text-gray-700">Kontaktinformācija</label>
                        <input
                            id="contact_info"
                            type="text"
                            name="contact_info"
                            value="{{ old('contact_info',$listing->contact_info) }}"
                            placeholder="+371 20000000, e-pasts u.c."
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30"
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="show_contact"
                            name="show_contact"
                            value="1"
                            @checked(old('show_contact',$listing->show_contact))
                            class="h-4 w-4 rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]"
                        >
                        <label for="show_contact" class="text-sm text-gray-700">Rādīt kontaktinformāciju publiski</label>
                    </div>
                </section>

                <!-- ======================== Bilžu galerija ======================== -->
                <section class="space-y-4" x-init="registerInput($refs.input)">
                    <h3 class="text-lg font-semibold text-gray-900">Visas bildes (esošās + jaunās)</h3>

                    <!-- Reorder grid -->
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                        <template x-for="(img, index) in images" :key="img.id ?? img.name + index">
                            <div
                                class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm"
                                draggable="true"
                                @dragstart="startDrag(index)"
                                @dragover.prevent
                                @drop.prevent="handleReorderDrop(index)"
                                @dragend="endDrag()"
                            >
                                <img :src="img.url" alt="" class="h-32 w-full object-cover">

                                <!-- Controls top-right -->
                                <div class="pointer-events-none absolute right-2 top-2 flex justify-end opacity-100 md:opacity-0 md:transition md:group-hover:opacity-100">
                                    <div class="flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 shadow">
                                        <button
                                            type="button"
                                            class="pointer-events-auto inline-flex h-7 w-7 items-center justify-center rounded-full text-gray-700 hover:bg-gray-200"
                                            @click.stop="move(index, index - 1)"
                                            :disabled="index === 0"
                                            title="Pārvietot augstāk"
                                        >↑</button>

                                        <button
                                            type="button"
                                            class="pointer-events-auto inline-flex h-7 w-7 items-center justify-center rounded-full text-gray-700 hover:bg-gray-200"
                                            @click.stop="move(index, index + 1)"
                                            :disabled="index === images.length - 1"
                                            title="Pārvietot zemāk"
                                        >↓</button>

                                        <button
                                            type="button"
                                            class="pointer-events-auto inline-flex h-7 w-7 items-center justify-center rounded-full bg-rose-500 text-white hover:bg-rose-600"
                                            @click.stop="remove(index)"
                                            title="Dzēst attēlu"
                                        >✕</button>
                                    </div>
                                </div>

                                <!-- Order badge -->
                                <div class="pointer-events-none absolute left-2 top-2">
                                    <span class="rounded bg-black/60 px-2 py-1 text-xs text-white" x-text="index + 1"></span>
                                </div>

                                <p class="truncate px-2 pb-2 text-xs text-gray-600" x-text="img.name"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden file input -->
                    <input
                        x-ref="input"
                        type="file"
                        name="images[]"
                        multiple
                        accept="image/*"
                        class="hidden"
                        @change="addFiles($event.target.files)"
                    >

                    <!-- Add zone -->
                    <div
                        class="flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 p-6 text-center text-sm text-gray-600 transition hover:bg-gray-50"
                        @click="$refs.input.click()"
                        @dragover.prevent
                        @drop.prevent="addFiles($event.dataTransfer?.files)"
                    >
                        <span class="font-medium text-[#2B7A78]">Noklikšķini vai ievelc bildes šeit</span>
                        <span class="mt-1 block text-xs text-gray-400">JPG, PNG, WEBP • max 5 MB</span>
                    </div>
                </section>

                <!-- ======================== Submit ======================== -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">
                        Saglabājot izmaiņas, atzīmētās bildes tiks dzēstas, jaunās pievienotas, un pašreizējā secība tiks saglabāta.
                    </p>
                    <button type="submit" class="btn btn-primary text-base">Atjaunināt sludinājumu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================== Alpine loģika ======================== -->
    <script>
    function imageGalleryManager(existing = []) {
        const mapExisting = (e) => ({
            id: e.id,
            url: e.url,
            name: e.name,
            existing: true,
            file: null,
        });

        return {
            images: (existing || []).map(mapExisting),
            removedExisting: [],
            inputEl: null,
            dragIndex: null,

            registerInput(el) { this.inputEl = el; },

            // Drag helpers
            startDrag(i) { this.dragIndex = i; },
            endDrag() { this.dragIndex = null; },
            handleReorderDrop(targetIndex) {
                if (this.dragIndex === null || this.dragIndex === targetIndex) return;
                this.move(this.dragIndex, targetIndex);
                this.endDrag();
            },

            // Click reorder
            move(from, to) {
                if (from === to || to < 0 || to >= this.images.length) return;
                const arr = [...this.images];
                const [moved] = arr.splice(from, 1);
                arr.splice(to, 0, moved);
                this.images = arr;
                this.syncAllInputs();
            },

            // Add files
            addFiles(fileList) {
                Array.from(fileList || []).forEach(file => {
                    this.images.push({
                        id: null,
                        url: URL.createObjectURL(file),
                        name: file.name,
                        existing: false,
                        file
                    });
                });
                this.syncAllInputs();
            },

            // Remove image
            remove(index) {
                const img = this.images[index];
                if (img?.existing && img.id) this.removedExisting.push(img.id);
                this.images.splice(index, 1);
                this.syncAllInputs();
            },

            // Sync to form
            syncAllInputs() {
                // 1) Failu inputā ieliekam tikai JAUNOS failus pašreizējā kārtībā
                if (this.inputEl) {
                    const dt = new DataTransfer();
                    this.images.forEach(i => { if (!i.existing && i.file) dt.items.add(i.file); });
                    this.inputEl.files = dt.files;
                }

                const form = this.inputEl?.closest('form');
                if (!form) return;

                // 2) Notīrām mūsu hidden inputus
                form.querySelectorAll('input[name="existing_image_order[]"], input[name="remove_images[]"]').forEach(n => n.remove());

                // 3) Esošo bilžu secība (tikai tās, kas palikušas)
                this.images.forEach(i => {
                    if (i.existing && i.id) {
                        const h = document.createElement('input');
                        h.type = 'hidden';
                        h.name = 'existing_image_order[]';
                        h.value = i.id;
                        form.appendChild(h);
                    }
                });

                // 4) Dzēstās esošās bildes (unikalizētas)
                Array.from(new Set(this.removedExisting)).forEach(id => {
                    const h = document.createElement('input');
                    h.type = 'hidden';
                    h.name = 'remove_images[]';
                    h.value = id;
                    form.appendChild(h);
                });
            }
        };
    }
    </script>
</x-app-layout>
