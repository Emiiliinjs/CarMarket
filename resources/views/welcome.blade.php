<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">Visi auto sludinājumi</h2>
                <p class="text-sm text-gray-500">Atrodi sev piemērotāko auto – katra bilde tiek apstrādāta vienādi, lai galerijas izskatītos moderni jebkurā ekrānā.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        @if($listings->count())
            <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($listings as $listing)
                    @include('listings.partials.card', ['listing' => $listing])
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $listings->onEachSide(1)->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white/70 p-12 text-center shadow-sm">
                <p class="text-lg font-semibold text-gray-700">Nav neviena sludinājuma.</p>
                <p class="mt-2 text-sm text-gray-500">Pievieno savu pirmo auto un kopīgo bildes ar pircējiem jau šodien.</p>
            </div>
        @endif
    </div>
</x-app-layout>
