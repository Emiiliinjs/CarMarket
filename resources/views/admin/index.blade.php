<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900 dark:text-white">Administratora panelis</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">Apstiprini jaunus sludinājumus, pārvaldi ziņojumus un lietotāju statusu.</p>
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

    @if(($adminNotifications ?? collect())->isNotEmpty())
        <section class="mb-8 space-y-3 rounded-3xl border border-[#2B7A78]/30 bg-white/90 p-6 shadow-sm backdrop-blur dark:border-[#2B7A78]/50 dark:bg-gray-900/80">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-[#2B7A78] dark:text-[#88d9d2]">Jaunie administratora paziņojumi</h3>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.notifications.read') }}">
                        @csrf
                        <input type="hidden" name="mark_all" value="1">
                        <button type="submit" class="btn btn-secondary px-3 py-2 text-xs">Atzīmēt visus kā izlasītus</button>
                    </form>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($adminNotifications as $notification)
                    <article class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm transition hover:border-[#2B7A78]/40 dark:border-slate-700 dark:bg-gray-900/80">
                        <div class="flex flex-col gap-2">
                            <h4 class="text-base font-semibold text-slate-900 dark:text-white">{{ $notification->title }}</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $notification->message }}</p>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                            <span>{{ $notification->created_at?->diffForHumans() }}</span>
                            <div class="flex flex-wrap items-center gap-2">
                                @if($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="btn btn-secondary px-3 py-1 text-xs">Atvērt</a>
                                @endif
                                <form method="POST" action="{{ route('admin.notifications.read') }}">
                                    @csrf
                                    <input type="hidden" name="ids[]" value="{{ $notification->id }}">
                                    <button type="submit" class="btn btn-success px-3 py-1 text-xs">Atzīmēt kā izlasītu</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <div class="grid gap-8 lg:grid-cols-2">
        <section class="space-y-4 rounded-3xl border border-gray-200 bg-white/80 p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/70">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gaidošie sludinājumi</h3>
                <span class="rounded-full bg-[#2B7A78]/10 px-3 py-1 text-xs font-semibold text-[#2B7A78] dark:bg-[#2B7A78]/10 dark:text-[#2B7A78]/70">{{ $pendingListings->count() }}</span>
            </div>

            @forelse($pendingListings as $listing)
                <article class="space-y-3 rounded-2xl border border-gray-200 bg-white/80 p-4 shadow-sm transition hover:border-[#2B7A78]/30 dark:border-gray-700 dark:bg-gray-900/80">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $listing->marka }} {{ $listing->modelis }} ({{ $listing->gads }})</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Ievietots {{ $listing->created_at->diffForHumans() }} • Cena {{ number_format($listing->cena, 2, '.', ' ') }} €</p>
                            <p class="text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ $listing->user->email }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-secondary px-3 py-2 text-xs">Skatīt</a>
                            <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                                @csrf
                                <button type="submit" class="btn btn-success px-3 py-2 text-xs">Apstiprināt</button>
                            </form>
                            <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}" onsubmit="return confirm('Dzēst šo sludinājumu?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger px-3 py-2 text-xs">Dzēst</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl border border-dashed border-gray-200 bg-white/70 p-6 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">Nav sludinājumu, kas gaida apstiprinājumu.</p>
            @endforelse
        </section>

        <section class="space-y-4 rounded-3xl border border-gray-200 bg-white/80 p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/70">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ziņotie sludinājumi</h3>
                <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600 dark:bg-rose-500/10 dark:text-rose-200">{{ $reports->count() }}</span>
            </div>

            @forelse($reports as $report)
                <article class="space-y-3 rounded-2xl border border-gray-200 bg-white/80 p-4 shadow-sm transition hover:border-rose-200 dark:border-gray-700 dark:bg-gray-900/80">
                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-gray-700 dark:text-gray-200">{{ $report->reason }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ziņoja: {{ $report->user?->email ?? 'Anonīmi' }} • {{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('listings.show', $report->listing) }}" class="btn btn-secondary px-3 py-2 text-xs">Skatīt sludinājumu</a>
                        <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                            @csrf
                            <button type="submit" class="btn btn-success px-3 py-2 text-xs">Atzīmēt kā atrisinātu</button>
                        </form>
                        <form method="POST" action="{{ route('admin.listings.destroy', $report->listing) }}" onsubmit="return confirm('Dzēst ziņoto sludinājumu?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-3 py-2 text-xs">Dzēst sludinājumu</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl border border-dashed border-gray-200 bg-white/70 p-6 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">Nav jaunu ziņojumu.</p>
            @endforelse
        </section>
    </div>

    <section class="mt-8 space-y-4 rounded-3xl border border-gray-200 bg-white/80 p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/70">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bloķētie lietotāji</h3>
            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-200">{{ $blockedUsers->count() }}</span>
        </div>

        @forelse($blockedUsers as $user)
            <div class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white/80 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/80 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.users.toggle-block', $user) }}">
                    @csrf
                    <button type="submit" class="btn btn-success px-4 py-2 text-xs">Atbloķēt lietotāju</button>
                </form>
            </div>
        @empty
            <p class="rounded-2xl border border-dashed border-gray-200 bg-white/70 p-6 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">Pašlaik nav bloķētu lietotāju.</p>
        @endforelse
    </section>
</x-app-layout>
