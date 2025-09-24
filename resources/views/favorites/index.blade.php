<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-white">Mani favorīti</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">Seko līdzi automašīnām, kas tev iepatikušās – saglabātie sludinājumi paliks vienuviet.</p>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 px-6 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
            {{ session('error') }}
        </div>
    @endif

    @if($listings->count())
        <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($listings as $listing)
                @include('listings.partials.card', ['listing' => $listing, 'favoriteIds' => $favoriteIds])
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $listings->onEachSide(1)->links() }}
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-gray-300 bg-white/70 p-12 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900/60">
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-100">Favorītu saraksts ir tukšs.</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Atrodi interesantus piedāvājumus un piespied sirsniņu, lai ātri pie tiem atgrieztos.</p>
            <a href="{{ route('listings.index') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Atpakaļ uz sludinājumiem</a>
        </div>
    @endif
</x-app-layout>
