<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Visi auto sludinājumi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if($listings->count())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($listings as $listing)
                        <div class="overflow-hidden bg-white rounded-lg shadow">
                            <div class="p-4">
                                <h3 class="text-lg font-bold">{{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Nobraukums: {{ number_format($listing->nobraukums, 0, '.', ' ') }} km
                                </p>
                                <p class="text-sm text-gray-600">
                                    Cena: <span class="text-green-600 font-semibold">{{ number_format($listing->cena, 2, '.', ' ') }} €</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Degviela: {{ ucfirst($listing->degviela) }}, {{ ucfirst($listing->parnesumkarba) }}
                                </p>

                                <div class="mt-4">
    <a href="{{ route('listings.show', $listing->id) }}"
       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 text-gray-800 text-base font-semibold bg-blue-300 rounded-lg shadow-lg hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
        Skatīt detaļas
    </a>
</div>


                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $listings->links() }}
                </div>
            @else
                <p class="text-center text-gray-500">Nav neviena sludinājuma.</p>
            @endif
        </div>
    </div>
</x-app-layout>
