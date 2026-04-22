<nav
    x-data="{
        open: false,
        adminCount: Number({{ $adminNotificationCount ?? 0 }} || 0),
        scrolled: false,
        init() {
            const f = () => this.scrolled = window.scrollY > 4;
            f();
            window.addEventListener('scroll', f, { passive: true });
        }
    }"
    x-init="init()"
    x-effect="document.documentElement.style.overflowY = open ? 'hidden' : ''"
    @keydown.escape.window="open = false"
    class="sticky top-0 z-40 transition-all duration-300"
    :class="scrolled
        ? 'border-b border-slate-200/80 bg-white/90 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.08)] backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-950/90'
        : 'border-b border-transparent bg-white/80 backdrop-blur-sm dark:bg-slate-950/80'"
>
    <div aria-hidden="true"
         class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-full bg-[radial-gradient(circle_at_10%_0%,rgba(45,212,191,0.16),transparent_42%),radial-gradient(circle_at_85%_0%,rgba(56,189,248,0.16),transparent_38%)] dark:bg-[radial-gradient(circle_at_10%_0%,rgba(20,184,166,0.2),transparent_42%),radial-gradient(circle_at_85%_0%,rgba(14,116,144,0.2),transparent_38%)]"></div>

    <!-- ─── Top bar ─────────────────────────────────────────────────────────── -->
    <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">

        <!-- Left: Logo + nav links -->
        <div class="flex items-center gap-5">

            <!-- Logo -->
            <a href="{{ route('landing.index') }}"
               class="group flex items-center gap-2.5 rounded-xl px-2 py-1.5 transition-all duration-200 hover:bg-teal-50 dark:hover:bg-teal-950/40">
                <x-application-logo class="block h-8 w-auto fill-current text-[#2B7A78] transition-transform duration-200 group-hover:scale-105" />
                <span class="hidden text-sm font-bold tracking-tight text-slate-800 transition-colors group-hover:text-[#2B7A78] sm:inline dark:text-slate-100 dark:group-hover:text-teal-400">
                    {{ config('CarMarket', 'CarMarket') }}
                </span>
            </a>


            <!-- Desktop links -->
            <div class="hidden items-center gap-2 md:flex lg:gap-3">

            <!-- Divider -->
            <div class="hidden h-5 w-px bg-slate-200 md:block dark:bg-slate-700"></div>

            <!-- Desktop nav links -->
            <nav class="hidden items-center gap-0.5 md:flex" aria-label="Galvenā navigācija">


                <x-nav-link :href="route('landing.index')" :active="request()->routeIs('landing.index')">
                    <svg class="h-4 w-4 opacity-80" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5 12 4l9 7.5M6.5 10.5V20h11V10.5"/>
                    </svg>
                    {{ __('Sākumlapa') }}
                </x-nav-link>

                <x-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.index')">
                    <svg class="h-4 w-4 opacity-80" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" d="M7 10h10M7 14h6"/>
                    </svg>
                    {{ __('Sludinājumi') }}
                </x-nav-link>

                @auth
                    <x-nav-link :href="route('listings.mine')" :active="request()->routeIs('listings.mine')">
                        <svg class="h-4 w-4 opacity-80" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M6 7V5h12v2m-1 0v12H7V7m3 4h4"/>
                        </svg>
                        {{ __('Mani sludinājumi') }}
                    </x-nav-link>

                    <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
                        <svg class="h-4 w-4 opacity-80" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12 20-7-6.3A4.4 4.4 0 0 1 11.2 7L12 7.8l.8-.8a4.4 4.4 0 0 1 6.2 6.2L12 20Z"/>
                        </svg>
                        {{ __('Favorīti') }}
                    </x-nav-link>

                    <a href="{{ route('listings.create') }}"
                       class="ml-1 inline-flex items-center gap-1.5 rounded-xl bg-[#2B7A78] px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#22615F] hover:shadow-md active:scale-95 {{ request()->routeIs('listings.create') ? 'ring-2 ring-[#2B7A78] ring-offset-2' : '' }}">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        {{ __('Pievienot') }}
                    </a>
                @endauth

                @if(auth()->user()?->is_admin)


                    <!-- Admin dropdown -->
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="group ml-1 inline-flex items-center gap-2 rounded-xl border border-amber-200/80 bg-gradient-to-br from-amber-50 to-orange-50/80 px-3 py-2 text-sm font-semibold text-amber-900 shadow-sm transition-all duration-200 hover:border-amber-300 hover:shadow-md dark:border-amber-600/30 dark:from-amber-900/25 dark:to-orange-900/20 dark:text-amber-100">
                                <span class="flex h-5 w-5 items-center justify-center rounded-md bg-amber-400/20 text-xs text-amber-600 dark:text-amber-400">★</span>
                                <span>{{ __('Admin') }}</span>
                                <span x-cloak x-show="adminCount > 0" x-text="adminCount"
                                      class="inline-flex h-4.5 min-w-[1.1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[0.6rem] font-bold leading-none text-white shadow-sm"></span>
                                <svg class="h-3.5 w-3.5 text-amber-500 transition-transform duration-200 group-hover:translate-y-0.5 dark:text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-3 pb-1.5 pt-2.5 text-[0.65rem] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                {{ __('Admin iespējas') }}
                            </div>
                            <x-dropdown-link :href="route('admin.index')">
                                <span class="flex items-center gap-2">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                    {{ __('Admin panelis') }}
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.bidding.index')">
                                <span class="flex items-center gap-2">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 0 0 6.54 17H17M7 13l1-4h9"/><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg>
                                    {{ __('Izsoļu pārvaldība') }}
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.bidding.create')">
                                <span class="flex items-center gap-2">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    {{ __('Pievienot izsoles auto') }}
                                </span>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                @endif

            </nav>
        </div>

        <!-- Right: Actions -->
        <div class="ml-auto hidden items-center gap-2 md:flex">

            <!-- Dark mode toggle -->
            <button type="button"
                    @click="$store.theme.toggle()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition-all duration-200 hover:border-[#2B7A78] hover:bg-teal-50 hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-400 dark:hover:border-teal-600 dark:hover:bg-teal-950/30 dark:hover:text-teal-400"
                    aria-label="Tumšais režīms">
                <svg x-show="!$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                </svg>
                <svg x-show="$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            @auth
                <span class="inline-flex items-center gap-1 rounded-full border border-teal-200/80 bg-teal-50/80 px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wider text-teal-700 dark:border-teal-700/40 dark:bg-teal-900/20 dark:text-teal-300">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                    {{ __('Live izsoles') }}
                </span>

                <!-- User dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="group inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3.5 py-2 text-sm font-medium text-slate-700 transition-all duration-200 hover:border-[#2B7A78] hover:bg-teal-50 hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200 dark:hover:border-teal-700 dark:hover:bg-teal-950/30">
                            <!-- Avatar initials -->
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-100 text-[0.65rem] font-bold text-teal-700 dark:bg-teal-900/60 dark:text-teal-300">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-[7rem] truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200 group-hover:text-[#2B7A78]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="border-b border-slate-100 px-3 py-2.5 dark:border-slate-800">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</p>
                            <p class="mt-0.5 truncate text-[0.7rem] text-slate-400">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profils') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Iziet') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-300">
                    {{ __('Ieiet') }}
                </a>
                <a href="{{ route('register') }}"
                   class="rounded-xl bg-[#2B7A78] px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#22615F] hover:shadow-md active:scale-95">
                    {{ __('Reģistrēties') }}
                </a>
            @endauth
        </div>

        <!-- ─── Mobile: hamburger ────────────────────────────────────────────── -->
        <div class="flex items-center gap-2 md:hidden">
            <button type="button"
                    @click="open = !open"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-all duration-200 hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-300"
                    :aria-expanded="open.toString()"
                    aria-label="Izvēlne">
                <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-45': open }" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- ─── Mobile panel ─────────────────────────────────────────────────────── -->
    <div x-cloak x-show="open" class="md:hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm"
             @click="open = false"
             aria-hidden="true"></div>

        <!-- Slide-down panel -->
        <div class="fixed inset-x-0 top-16 z-40 h-[calc(100dvh-4rem)] overflow-y-auto bg-white dark:bg-slate-950"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-3">

            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">

                <!-- Nav links section -->
                <div class="px-4 py-3 space-y-0.5">
                    <p class="mb-2 px-3 text-[0.65rem] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-600">
                        {{ __('Navigācija') }}
                    </p>

                    <x-responsive-nav-link :href="route('landing.index')" :active="request()->routeIs('landing.index')" @click="open=false">
                        {{ __('Sākumlapa') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.index')" @click="open=false">
                        {{ __('Sludinājumi') }}
                    </x-responsive-nav-link>

                    @auth
                        <x-responsive-nav-link :href="route('listings.mine')" :active="request()->routeIs('listings.mine')" @click="open=false">
                            {{ __('Mani sludinājumi') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')" @click="open=false">
                            {{ __('Favorīti') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('listings.create')" :active="request()->routeIs('listings.create')" @click="open=false">
                            {{ __('Pievienot sludinājumu') }}
                        </x-responsive-nav-link>
                    @endauth

                    @if(auth()->user()?->is_admin)
                        <div class="pt-2 pb-1">
                            <p class="mb-1 px-3 text-[0.65rem] font-bold uppercase tracking-widest text-amber-500/70">
                                {{ __('Admin zona') }}
                            </p>
                        </div>


                        <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" @click="open=false">
                            <span class="flex items-center gap-2">
                                {{ __('Admin panelis') }}
                                <span x-cloak x-show="adminCount > 0" x-text="adminCount"
                                      class="inline-flex h-4.5 min-w-[1.1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[0.6rem] font-bold leading-none text-white"></span>
                            </span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.bidding.index')" :active="request()->routeIs('admin.bidding.*')" @click="open=false">
                            {{ __('Izsoles auto') }}
                        </x-responsive-nav-link>
                    @endif
                </div>

                <!-- Account section -->
                <div class="px-4 py-4">
                    @guest
                        <p class="mb-3 text-xs text-slate-500 dark:text-slate-400">{{ __('Piesakies vai reģistrējies, lai piekļūtu visām funkcijām.') }}</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}"
                               class="rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                                {{ __('Ieiet') }}
                            </a>
                            <a href="{{ route('register') }}"
                               class="rounded-xl bg-[#2B7A78] px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-[#22615F]">
                                {{ __('Reģistrēties') }}
                            </a>
                        </div>
                    @endguest

                    @auth
                        <!-- User info -->
                        <div class="mb-3 flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-100 text-sm font-bold text-teal-700 dark:bg-teal-900/50 dark:text-teal-300">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')" @click="open=false">
                                {{ __('Profils') }}
                            </x-responsive-nav-link>

                            <!-- Dark mode toggle -->
                            <button type="button"
                                    @click="$store.theme.toggle()"
                                    class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900/50">
                                <span>{{ __('Tumšais režīms') }}</span>
                                <span class="flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                                      :class="$store.theme.isDark ? 'bg-[#2B7A78]' : 'bg-slate-200'">
                                    <span class="ml-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200"
                                          :class="$store.theme.isDark ? 'translate-x-5' : 'translate-x-0'"></span>
                                </span>
                            </button>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-rose-600 hover:text-rose-700 dark:text-rose-400">
                                    {{ __('Iziet') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</nav>
