@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                    Rediģēt sludinājumu: {{ $listing->marka }} {{ $listing->modelis }}
                </h2>
                <p class="text-sm text-gray-500">
                    Atjaunini informāciju, rediģē bildes un to secību.
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
                x-data="imageGalleryManager({{ Js::from(
                    $listing->galleryImages->map(fn($img) => [
                        'id' => $img->id,
                        'url' => asset('storage/' . ltrim($img->filename, '/')),
                        'name' => basename($img->filename),
                        'existing' => true,
                    ])
                ) }})"
                class="space-y-10"
            >
                @csrf
                @method('PUT')

                <!-- ======================== Pamatinformācija ======================== -->
                <section class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Marka</label>
                            <input type="text" value="{{ $listing->marka }}" disabled
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-700 shadow-sm" />
                            <input type="hidden" name="marka" value="{{ $listing->marka }}">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Modelis</label>
                            <input type="text" value="{{ $listing->modelis }}" disabled
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-700 shadow-sm" />
                            <input type="hidden" name="modelis" value="{{ $listing->modelis }}">
                        </div>

                        <div>
                            <label for="gads" class="text-sm font-semibold text-gray-700">Gads</label>
                            <input id="gads" type="number" name="gads" value="{{ old('gads', $listing->gads) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30" required>
                        </div>

                        <div>
                            <label for="nobraukums" class="text-sm font-semibold text-gray-700">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums" value="{{ old('nobraukums', $listing->nobraukums) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30" required>
                        </div>

                        <div>
                            <label for="cena" class="text-sm font-semibold text-gray-700">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena" value="{{ old('cena', $listing->cena) }}"
                                   class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30" required>
                        </div>

                        <div>
                            <label for="degviela" class="text-sm font-semibold text-gray-700">Degviela</label>
                            <select id="degviela" name="degviela"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                                <option value="Benzīns" @selected($listing->degviela === 'Benzīns')>Benzīns</option>
                                <option value="Dīzelis" @selected($listing->degviela === 'Dīzelis')>Dīzelis</option>
                                <option value="Elektriska" @selected($listing->degviela === 'Elektriska')>Elektriska</option>
                                <option value="Hibrīds" @selected($listing->degviela === 'Hibrīds')>Hibrīds</option>
                            </select>
                        </div>

                        <div>
                            <label for="parnesumkarba" class="text-sm font-semibold text-gray-700">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                                <option value="Manuālā" @selected($listing->parnesumkarba === 'Manuālā')>Manuālā</option>
                                <option value="Automātiskā" @selected($listing->parnesumkarba === 'Automātiskā')>Automātiskā</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="apraksts" class="text-sm font-semibold text-gray-700">Apraksts</label>
                        <textarea id="apraksts" name="apraksts" rows="4"
                                  class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">{{ old('apraksts', $listing->apraksts) }}</textarea>
                    </div>
                </section>

                <!-- ======================== Papildu informācija ======================== -->
                <section class="space-y-6">
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-700">Statuss</label>
                        <select id="status" name="status"
                                class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                            <option value="available" @selected($listing->status === 'available')>Pieejams</option>
                            <option value="reserved" @selected($listing->status === 'reserved')>Rezervēts</option>
                            <option value="sold" @selected($listing->status === 'sold')>Pārdots</option>
                        </select>
                    </div>

                    <div>
                        <label for="contact_info" class="text-sm font-semibold text-gray-700">Kontaktinformācija</label>
                        <input id="contact_info" type="text" name="contact_info"
                               value="{{ old('contact_info', $listing->contact_info) }}"
                               placeholder="Piemēram: +371 20000000, e-pasts u.c."
                               class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:ring-2 focus:ring-[#2B7A78]/30">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="show_contact" name="show_contact" value="1"
                               @checked($listing->show_contact)
                               class="h-4 w-4 rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                        <label for="show_contact" class="text-sm text-gray-700">Rādīt kontaktinformāciju publiski</label>
                    </div>
                </section>

                <!-- ======================== Bilžu galerija ======================== -->
                <section class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Visas bildes (esošās + jaunās)</h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4"
                         @dragover.prevent @drop.prevent="handleDrop($event)">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="group relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm"
                                 draggable="true"
                                 @dragstart="dragStart(index)"
                                 @dragend="dragEnd()"
                                 @drop="swapImages(index)">
                                <img :src="image.url" alt="Bilde" class="h-32 w-full object-cover">
                                <label class="absolute bottom-2 left-2 inline-flex items-center gap-1 rounded bg-black/70 px-2 py-1 text-xs text-white">
                                    <template x-if="image.existing">
                                        <input type="checkbox" name="remove_images[]" :value="image.id"> Dzēst
                                    </template>
                                </label>
                                <p class="px-2 pb-2 text-xs text-gray-600 truncate" x-text="image.name"></p>
                            </div>
                        </template>
                    </div>

                    <input x-ref="input" type="file" name="images[]" multiple accept="image/*" class="hidden" @change="addFiles($event.target.files)">
                    <div class="flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 p-6 text-center text-sm text-gray-600 transition hover:bg-gray-50"
                         @click="$refs.input.click()">
                        <span class="font-medium text-[#2B7A78]">Noklikšķini vai ievelc bildes šeit</span>
                        <span class="mt-1 block text-xs text-gray-400">Atbalstītie formāti: JPG, PNG, WEBP</span>
                    </div>
                </section>

                <!-- ======================== Submit ======================== -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">
                        Saglabājot izmaiņas, atzīmētās bildes tiks dzēstas, jaunās – pievienotas, un secība saglabāsies.
                    </p>
                    <button type="submit" class="btn btn-primary text-base">Atjaunināt sludinājumu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================== Alpine loģika ======================== -->
    <script>
function imageGalleryManager(existing = []) {
    return {
        images: existing,
        dragIndex: null,

        dragStart(index) {
            this.dragIndex = index;
        },
        dragEnd() {
            this.dragIndex = null;
        },
        swapImages(targetIndex) {
            if (this.dragIndex === null || this.dragIndex === targetIndex) return;

            const temp = this.images[this.dragIndex];
            this.images[this.dragIndex] = this.images[targetIndex];
            this.images[targetIndex] = temp;
            this.dragIndex = null;

            // ✅ saglabā jauno secību slēptajos inputos
            this.syncOrderInputs();
        },
        addFiles(fileList) {
            Array.from(fileList).forEach(file => {
                this.images.push({
                    id: null,
                    url: URL.createObjectURL(file),
                    name: file.name,
                    existing: false,
                    file
                });
            });
            this.syncInput();
        },
        syncInput() {
            const input = document.querySelector('input[name="images[]"]');
            const dt = new DataTransfer();
            this.images.forEach(img => {
                if (!img.existing && img.file) dt.items.add(img.file);
            });
            input.files = dt.files;
        },
        // ✅ jauns: ģenerē hidden inputus esošo bilžu secībai
        syncOrderInputs() {
            document.querySelectorAll('input[name="existing_image_order[]"]').forEach(e => e.remove());
            const form = document.querySelector('form');
            this.images.forEach(img => {
                if (img.existing && img.id) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'existing_image_order[]';
                    input.value = img.id;
                    form.appendChild(input);
                }
            });
        }
    };
}
</script>

</x-app-layout>
