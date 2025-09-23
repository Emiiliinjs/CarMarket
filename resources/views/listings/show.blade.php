<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-lg shadow p-6">

            <!-- Sludinājuma info -->
            <div class="text-gray-700 space-y-2">
                <p><strong>Nobraukums:</strong> {{ number_format($listing->nobraukums, 0, '.', ' ') }} km</p>
                <p><strong>Cena:</strong> <span class="text-green-600 font-semibold">{{ number_format($listing->cena, 2, '.', ' ') }} €</span></p>
                <p><strong>Degviela:</strong> {{ ucfirst($listing->degviela) }}</p>
                <p><strong>Pārnesumkārba:</strong> {{ ucfirst($listing->parnesumkarba) }}</p>

                @if($listing->apraksts)
                    <div class="mt-2 text-gray-600">
                        <h4 class="font-semibold mb-1">Apraksts</h4>
                        <p>{{ $listing->apraksts }}</p>
                    </div>
                @endif
            </div>

            <!-- Galvenā bilde -->
            <div class="flex justify-center my-4">
                @php
                    $firstImage = $listing->images->first();
                @endphp
                <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ $firstImage ? asset('storage/'.$firstImage->filename) : asset('images/car.png') }}"
                         alt="{{ $listing->marka }} {{ $listing->modelis }}"
                         class="w-full h-full object-cover object-center">
                </div>
            </div>

            <!-- Galerija (skip first image) -->
            @if($listing->images->count() > 1)
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($listing->images->skip(1) as $image)
                        <div class="overflow-hidden rounded-lg shadow hover:shadow-lg transition transform hover:scale-105">
                            <img src="{{ asset('storage/'.$image->filename) }}"
                                 alt="Auto bilde"
                                 class="w-full h-32 sm:h-40 object-cover object-center">
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Rediģēt un dzēst pogas -->
            @if(auth()->check() && (auth()->user()->id === $listing->user_id || auth()->user()->is_admin))
                <div class="mt-6 flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                    <a href="{{ route('listings.edit', $listing->id) }}"
                       class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        Rediģēt sludinājumu
                    </a>

                    <form action="{{ route('listings.destroy', $listing->id) }}" method="POST"
                          onsubmit="return confirm('Vai tiešām dzēst šo sludinājumu?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow hover:bg-red-700 transition">
                            Dzēst sludinājumu
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
