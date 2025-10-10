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
                    <section class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Esošās bildes</h3>
                        <p class="text-sm text-gray-500">Atzīmē tās bildes, kuras gribi dzēst.</p>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($listing->galleryImages as $image)
                                <div class="relative overflow-hidden rounded-2xl bg-gray-100 shadow-sm">
                                    <img src="{{ asset('storage/'.$image->filename) }}"
                                         alt="Esoša auto bilde"
                                         class="h-32 w-full object-cover">
                                    <label class="absolute bottom-2 left-2 inline-flex items-center gap-1 rounded bg-black/70 px-2 py-1 text-xs text-white">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                                        Dzēst
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Jaunas bildes -->
                <section class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pievieno jaunas bildes</h3>
                    <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-600">
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
    </script>
</x-app-layout>
