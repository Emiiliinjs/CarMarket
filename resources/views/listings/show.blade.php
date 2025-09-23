<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-lg shadow p-6">
            <p><strong>Nobraukums:</strong> {{ number_format($listing->nobraukums, 0, '.', ' ') }} km</p>
            <p><strong>Cena:</strong> {{ number_format($listing->cena, 2, '.', ' ') }} €</p>
            <p><strong>Degviela:</strong> {{ ucfirst($listing->degviela) }}</p>
            <p><strong>Pārnesumkārba:</strong> {{ ucfirst($listing->parnesumkarba) }}</p>

            @if($listing->apraksts)
                <div class="mt-4">
                    <h3 class="font-semibold">Apraksts</h3>
                    <p>{{ $listing->apraksts }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
