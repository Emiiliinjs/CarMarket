<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                    Rediģēt sludinājumu: {{ $listing->marka }} {{ $listing->modelis }}
                </h2>
                <p class="text-sm text-gray-500">Atjaunini informāciju un pievieno jaunas bildes – esošās saglabāsies, ja tās nedzēs.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-5xl">
        <div class="space-y-10 rounded-3xl bg-white/80 p-8 shadow-xl ring-1 ring-gray-100 backdrop-blur">
            <form method="POST" action="{{ route('listings.update', $listing->id) }}" enctype="multipart/form-data" class="space-y-10" x-data="imageUpload()">
                @csrf
                @method('PUT')

                <section class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="marka">Marka</label>
                            <input id="marka" type="text" name="marka" value="{{ old('marka', $listing->marka) }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('marka')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="modelis">Modelis</label>
                            <input id="modelis" type="text" name="modelis" value="{{ old('modelis', $listing->modelis) }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('modelis')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="gads">Gads</label>
                            <input id="gads" type="number" name="gads" value="{{ old('gads', $listing->gads) }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('gads')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="nobraukums">Nobraukums (km)</label>
                            <input id="nobraukums" type="number" name="nobraukums" value="{{ old('nobraukums', $listing->nobraukums) }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('nobraukums')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="cena">Cena (€)</label>
                            <input id="cena" type="number" step="0.01" name="cena" value="{{ old('cena', $listing->cena) }}" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                            @error('cena')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="degviela">Degviela</label>
                            <select id="degviela" name="degviela" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                                <option value="Benzīns" {{ old('degviela', $listing->degviela) === 'Benzīns' ? 'selected' : '' }}>Benzīns</option>
                                <option value="Dīzelis" {{ old('degviela', $listing->degviela) === 'Dīzelis' ? 'selected' : '' }}>Dīzelis</option>
                                <option value="Elektriska" {{ old('degviela', $listing->degviela) === 'Elektriska' ? 'selected' : '' }}>Elektriska</option>
                                <option value="Hibrīds" {{ old('degviela', $listing->degviela) === 'Hibrīds' ? 'selected' : '' }}>Hibrīds</option>
                            </select>
                            @error('degviela')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="parnesumkarba">Pārnesumkārba</label>
                            <select id="parnesumkarba" name="parnesumkarba" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" required>
                                <option value="Manuālā" {{ old('parnesumkarba', $listing->parnesumkarba) === 'Manuālā' ? 'selected' : '' }}>Manuālā</option>
                                <option value="Automātiskā" {{ old('parnesumkarba', $listing->parnesumkarba) === 'Automātiskā' ? 'selected' : '' }}>Automātiskā</option>
                            </select>
                            @error('parnesumkarba')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="apraksts">Apraksts</label>
                        <textarea id="apraksts" name="apraksts" rows="4" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">{{ old('apraksts', $listing->apraksts) }}</textarea>
                        @error('apraksts')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                @if($listing->galleryImages->count())
                    <section class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Esošās bildes</h3>
                            <span class="text-sm text-gray-500">Kopā {{ $listing->galleryImages->count() }} {{ $listing->galleryImages->count() === 1 ? 'bilde' : 'bildes' }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($listing->galleryImages as $image)
                                <div class="overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                    <img src="{{ asset('storage/'.$image->filename) }}" alt="Esoša auto bilde" class="h-32 w-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pievieno jaunas bildes</h3>
                            <p class="text-sm text-gray-500">Esošās bildes netiek dzēstas – jaunās tiks kompresētas un pievienotas galerijai.</p>
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
                    <p class="text-sm text-gray-500">Saglabājot izmaiņas, jaunās bildes tiks optimizētas un pievienotas galerijai.</p>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-3 text-base font-semibold text-white shadow-lg transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Atjaunināt sludinājumu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function imageUpload() {
            return {
                files: [],
                dragover: false,
                handleDrop(event) {
                    const droppedFiles = Array.from(event.dataTransfer.files);
                    this.addFiles(droppedFiles);
                    this.dragover = false;
                },
                handleFiles(event) {
                    const selectedFiles = Array.from(event.target.files);
                    this.addFiles(selectedFiles);
                },
                addFiles(newFiles) {
                    newFiles.forEach(file => {
                        file.url = URL.createObjectURL(file);
                        this.files.push(file);
                    });
                },
                remove(index) {
                    this.files.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>
