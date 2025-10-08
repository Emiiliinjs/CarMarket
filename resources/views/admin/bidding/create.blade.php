@php use App\Models\Listing; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
                    Jauns admin izsoles sludinājums
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Šis sludinājums būs pieejams tikai administratoriem un netiks rādīts publiskajā sarakstā.
                </p>
            </div>
            <a href="{{ route('admin.bidding.index') }}" class="text-sm font-semibold text-[#2B7A78] transition hover:text-[#22615F] dark:text-[#2B7A78]/80 dark:hover:text-[#7FD1CC]">
                Atpakaļ uz sarakstu
            </a>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-4xl">
        <div class="rounded-3xl border border-gray-200/70 bg-white/80 p-8 shadow-sm backdrop-blur dark:border-gray-800/60 dark:bg-gray-900/40">
            <form method="POST" action="{{ route('admin.bidding.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="marka" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Marka</label>
                        <input type="text" id="marka" name="marka" value="{{ old('marka') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('marka')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="modelis" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Modelis</label>
                        <input type="text" id="modelis" name="modelis" value="{{ old('modelis') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('modelis')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gads" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Gads</label>
                        <input type="number" id="gads" name="gads" value="{{ old('gads') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('gads')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nobraukums" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nobraukums (km)</label>
                        <input type="number" id="nobraukums" name="nobraukums" value="{{ old('nobraukums') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('nobraukums')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cena" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Starta cena (€)</label>
                        <input type="number" step="0.01" id="cena" name="cena" value="{{ old('cena') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('cena')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="degviela" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Degviela</label>
                        <input type="text" id="degviela" name="degviela" value="{{ old('degviela') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('degviela')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="parnesumkarba" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pārnesumkārba</label>
                        <input type="text" id="parnesumkarba" name="parnesumkarba" value="{{ old('parnesumkarba') }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('parnesumkarba')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Statuss</label>
                        <select id="status" name="status" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', Listing::STATUS_AVAILABLE) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="apraksts" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Apraksts</label>
                    <textarea id="apraksts" name="apraksts" rows="5" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('apraksts') }}</textarea>
                    @error('apraksts')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_info" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Kontakta informācija</label>
                    <textarea id="contact_info" name="contact_info" rows="3" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100" placeholder="Telefons, e-pasts vai cita informācija">{{ old('contact_info') }}</textarea>
                    @error('contact_info')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" name="show_contact" value="1" @checked(old('show_contact', true)) class="h-4 w-4 rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                        Rādīt kontaktinformāciju izsoles skatā
                    </label>
                </div>

                <div class="space-y-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Auto bildes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Vari pievienot līdz 10 bildēm, tās tiks automātiski saspiestas.</p>
                    </div>
                    <input type="file" name="images[]" multiple accept="image/*" class="block w-full cursor-pointer rounded-xl border border-dashed border-gray-300 bg-white px-4 py-6 text-sm text-gray-600 transition hover:border-[#2B7A78]/50 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-300">
                    @error('images')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#2B7A78] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#22615F] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#2B7A78]">
                        Saglabāt izsoles auto
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
