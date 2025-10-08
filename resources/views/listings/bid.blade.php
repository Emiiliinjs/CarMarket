<x-app-layout>
    @php
        $primaryImage = $listing->galleryImages->first();
        $primaryImageUrl = $primaryImage
            ? route('listing-images.show', $primaryImage)
            : asset('images/car.png');

        $componentConfig = [
            'listingId' => $listing->id,
            'pollUrl' => route('listings.bids.index', $listing),
            'storeUrl' => route('listings.bids.store', $listing),
            'currentBid' => (float) $currentBid,
            'nextBidAmount' => (float) $nextBidAmount,
            'minIncrement' => (int) $minIncrement,
            'bids' => $recentBids,
            'locale' => str_replace('_', '-', app()->getLocale()) ?: 'lv-LV',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-[#2B7A78] dark:text-[#2B7A78]/80">Tiešsaistes izsole</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-gray-900 dark:text-white">
                    {{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                    Minimālais solis tiek palielināts par {{ number_format($minIncrement, 0, '.', ' ') }} € katrā solī.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('listings.show', $listing) }}" class="btn btn-secondary">
                    Apskatīt sludinājumu
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-light">Atpakaļ</a>
            </div>
        </div>
    </x-slot>

    <div
        x-data="liveBid(@json($componentConfig))"
        x-init="init(); $nextTick(() => {
            if ($refs.serverHistory) {
                $refs.serverHistory.remove();
            }
        })"
        x-cloak
        class="grid gap-8 lg:grid-cols-5"
    >
        <div class="space-y-6 lg:col-span-3">
            <div class="overflow-hidden rounded-3xl bg-white/80 shadow-xl ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                <div class="relative aspect-[16/9]">
                    <img
                        src="{{ $primaryImageUrl }}"
                        alt="{{ $listing->marka }} {{ $listing->modelis }}"
                        class="h-full w-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 text-white">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-white/80">Pašreizējais augstākais solis</p>
                                <p class="text-3xl font-semibold" x-text="format(currentBid)">
                                    {{ number_format($currentBid, 2, '.', ' ') }} €
                                </p>
                            </div>
                            <div class="rounded-2xl bg-white/20 px-4 py-2 text-sm font-medium">
                                Nākamais solis: <span x-text="format(nextBidAmount)">{{ number_format($nextBidAmount, 2, '.', ' ') }} €</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Auto specifikācija</h3>
                <dl class="mt-4 grid gap-4 text-sm text-gray-600 dark:text-gray-300 sm:grid-cols-2">
                    <div class="rounded-2xl bg-gray-50/80 px-4 py-3 dark:bg-gray-800/60">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Marka / modelis</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50/80 px-4 py-3 dark:bg-gray-800/60">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Izlaiduma gads</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $listing->gads }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50/80 px-4 py-3 dark:bg-gray-800/60">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Nobraukums</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ number_format($listing->nobraukums, 0, '.', ' ') }} km</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50/80 px-4 py-3 dark:bg-gray-800/60">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Degviela / kārba</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $listing->degviela }} • {{ $listing->parnesumkarba }}</dd>
                    </div>
                </dl>

                @if($listing->apraksts)
                    <div class="mt-6 space-y-2">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Apraksts</h3>
                        <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $listing->apraksts }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6 lg:col-span-2">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-4 rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pievieno savu soli</h3>
                    <span class="inline-flex items-center rounded-full bg-[#2B7A78]/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#2B7A78] dark:bg-[#2B7A78]/10 dark:text-[#2B7A78]/70">
                        Solis {{ number_format($minIncrement, 0, '.', ' ') }} €
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Katrs solis tiek apstiprināts nekavējoties un tiek translēts visiem dalībniekiem.</p>

                @auth
                    <form
                        method="POST"
                        action="{{ route('listings.bids.store', $listing) }}"
                        x-on:submit.prevent="placeBid($event)"
                        class="space-y-4"
                    >
                        @csrf
                        <div class="space-y-3">
                            <label for="bid-amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tavs solis (€)</label>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="btn btn-secondary shrink-0 px-3 py-2 text-sm"
                                    x-on:click="decrease()"
                                    :disabled="loading || amount <= nextBidAmount"
                                >
                                    −{{ number_format($minIncrement, 0, '.', ' ') }}
                                </button>

                                <input
                                    id="bid-amount"
                                    name="amount"
                                    type="number"
                                    inputmode="decimal"
                                    min="{{ number_format($nextBidAmount, 2, '.', '') }}"
                                    step="{{ number_format($minIncrement, 0, '.', '') }}"
                                    value="{{ old('amount', $nextBidAmount) }}"
                                    x-model.number="amount"
                                    x-bind:min="nextBidAmount"
                                    x-bind:step="minIncrement"
                                    x-bind:disabled="loading"
                                    x-on:blur="handleManualInput($event)"
                                    x-on:change="handleManualInput($event)"
                                    class="flex-1 rounded-2xl border border-gray-200 bg-white px-4 py-2 text-center text-lg font-semibold text-gray-900 shadow-sm focus:border-[#2B7A78] focus:outline-none focus:ring-2 focus:ring-[#2B7A78]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-secondary shrink-0 px-3 py-2 text-sm"
                                    x-on:click="increase()"
                                    :disabled="loading"
                                >
                                    +{{ number_format($minIncrement, 0, '.', ' ') }}
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-full"
                            :disabled="loading"
                        >
                            <span x-show="!loading">Pielikt soli</span>
                            <span x-show="loading" class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M12 3v3m0 12v3m9-9h-3M6 12H3m15.364-6.364-2.121 2.121M8.757 15.243l-2.121 2.121m0-12.728 2.121 2.121m8.486 8.486 2.121 2.121" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Apstrādā...
                            </span>
                        </button>
                    </form>
                @else
                    <p class="rounded-2xl bg-gray-50/80 px-4 py-3 text-sm text-gray-600 dark:bg-gray-800/60 dark:text-gray-300">
                        Lai piedalītos izsolē, lūdzu, <a href="{{ route('login') }}" class="font-semibold text-[#2B7A78] hover:underline">ielogojies savā kontā</a> vai <a href="{{ route('register') }}" class="font-semibold text-[#2B7A78] hover:underline">izveido jaunu profilu</a>.
                    </p>
                @endauth

                <div
                    class="rounded-2xl border border-rose-200/70 bg-rose-50/70 px-4 py-3 text-sm text-rose-700 shadow-sm dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200"
                    x-show="error"
                    x-text="error"
                ></div>
                <div
                    class="rounded-2xl border border-emerald-200/70 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200"
                    x-show="success"
                    x-text="success"
                ></div>

                @error('amount')
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="rounded-3xl bg-white/80 p-6 shadow-xl ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Solījumu vēsture</h3>
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-400" x-text="bids.length + ' ieraksti'"></span>
                </div>

                <div class="mt-4 space-y-3">
                    <div x-ref="serverHistory">
                        @forelse($recentBids as $bid)
                            <div class="rounded-2xl border border-gray-200/60 bg-white/80 px-4 py-3 shadow-sm dark:border-gray-700/60 dark:bg-gray-800/70">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $bid['user'] }}</p>
                                    <p class="text-base font-semibold text-[#2B7A78] dark:text-[#2B7A78]/80">{{ number_format($bid['amount'], 2, '.', ' ') }} €</p>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $bid['created_at_human'] }}</p>
                            </div>
                        @empty
                            <p class="rounded-2xl bg-gray-50/80 px-4 py-3 text-sm text-gray-500 dark:bg-gray-800/60 dark:text-gray-300">
                                Vēl nav veikts neviens solis. Esi pirmais!
                            </p>
                        @endforelse
                    </div>

                    <template x-if="!bids.length">
                        <p class="rounded-2xl bg-gray-50/80 px-4 py-3 text-sm text-gray-500 dark:bg-gray-800/60 dark:text-gray-300">
                            Vēl nav veikts neviens solis. Esi pirmais!
                        </p>
                    </template>

                    <template x-for="bid in bids" :key="bid.id">
                        <div class="rounded-2xl border border-gray-200/60 bg-white/80 px-4 py-3 shadow-sm dark:border-gray-700/60 dark:bg-gray-800/70">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="bid.user"></p>
                                <p class="text-base font-semibold text-[#2B7A78] dark:text-[#2B7A78]/80" x-text="format(bid.amount)"></p>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="bid.created_at_human"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
