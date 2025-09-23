<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Rediģēt sludinājumu: {{ $listing->marka }} {{ $listing->modelis }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('listings.update', $listing->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium">Marka</label>
                        <input type="text" name="marka" value="{{ old('marka', $listing->marka) }}" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Modelis</label>
                        <input type="text" name="modelis" value="{{ old('modelis', $listing->modelis) }}" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Gads</label>
                        <input type="number" name="gads" value="{{ old('gads', $listing->gads) }}" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nobraukums (km)</label>
                        <input type="number" name="nobraukums" value="{{ old('nobraukums', $listing->nobraukums) }}" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Cena (€)</label>
                        <input type="number" step="0.01" name="cena" value="{{ old('cena', $listing->cena) }}" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Degviela</label>
                        <select name="degviela" class="w-full rounded border-gray-300" required>
                            <option value="Benzīns" {{ $listing->degviela == 'Benzīns' ? 'selected' : '' }}>Benzīns</option>
                            <option value="Dīzelis" {{ $listing->degviela == 'Dīzelis' ? 'selected' : '' }}>Dīzelis</option>
                            <option value="Elektriska" {{ $listing->degviela == 'Elektriska' ? 'selected' : '' }}>Elektriska</option>
                            <option value="Hibrīds" {{ $listing->degviela == 'Hibrīds' ? 'selected' : '' }}>Hibrīds</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Pārnesumkārba</label>
                        <select name="parnesumkarba" class="w-full rounded border-gray-300" required>
                            <option value="Manuālā" {{ $listing->parnesumkarba == 'Manuālā' ? 'selected' : '' }}>Manuālā</option>
                            <option value="Automātiskā" {{ $listing->parnesumkarba == 'Automātiskā' ? 'selected' : '' }}>Automātiskā</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium">Apraksts</label>
                    <textarea name="apraksts" rows="4" class="w-full rounded border-gray-300">{{ old('apraksts', $listing->apraksts) }}</textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium">Jaunas bildes</label>
                    <input type="file" name="images[]" multiple class="w-full rounded border-gray-300" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Var pievienot vairākas bildes (maks. 2MB katra).</p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Atjaunināt
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
