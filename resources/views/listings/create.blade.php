<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Pievienot jaunu sludinājumu
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium">Marka</label>
                        <input type="text" name="marka" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Modelis</label>
                        <input type="text" name="modelis" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Gads</label>
                        <input type="number" name="gads" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nobraukums (km)</label>
                        <input type="number" name="nobraukums" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Cena (€)</label>
                        <input type="number" step="0.01" name="cena" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Degviela</label>
                        <select name="degviela" class="w-full rounded border-gray-300" required>
                            <option>Benzīns</option>
                            <option>Dīzelis</option>
                            <option>Elektriska</option>
                            <option>Hibrīds</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Pārnesumkārba</label>
                        <select name="parnesumkarba" class="w-full rounded border-gray-300" required>
                            <option>Manuālā</option>
                            <option>Automātiskā</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium">Apraksts</label>
                    <textarea name="apraksts" rows="4" class="w-full rounded border-gray-300"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium">Auto bildes</label>
                    <input type="file" name="images[]" multiple
                           class="w-full rounded border-gray-300" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Varat izvēlēties vairākas bildes (maks. 2MB katra).</p>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 text-gray-800 text-base font-semibold bg-gray-200 rounded-lg shadow hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition">
                        Ievietot
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
