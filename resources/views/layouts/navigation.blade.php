<nav
    x-data="{
        open:false,
        adminCount: Number({{ $adminNotificationCount ?? 0 }}||0),
        scrolled:false,
        init(){ const f=()=>this.scrolled=window.scrollY>4; f(); window.addEventListener('scroll',f,{passive:true}) }
    }"
    x-init="init()"
    x-effect="document.documentElement.style.overflowY = open ? 'hidden' : ''"
    @keydown.escape.window="open=false"
    class="sticky top-0 z-40 border-b bg-white/95 backdrop-blur dark:bg-slate-950/95 dark:border-slate-800"
    :class="{ 'shadow-sm': scrolled }"
>
    <!-- Top bar -->
    <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex flex-1 items-center gap-6">
            <!-- Logo -->
            <a href="{{ route('listings.index') }}"
               class="flex items-center gap-2 rounded-xl px-2 py-1 text-slate-800 transition hover:bg-slate-100 dark:text-slate-100 dark:hover:bg-slate-900">
                <x-application-logo class="block h-8 w-auto fill-current text-[#2B7A78]" />
                <span class="hidden text-sm font-semibold tracking-tight sm:inline">{{ config('CarMarket','CarMarket') }}</span>
            </a>

            <!-- Desktop links -->
            <div class="hidden items-center gap-1 md:flex">
                <x-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.index')">
                    {{ __('Sludinājumi') }}
                </x-nav-link>

                @auth
                    <x-nav-link :href="route('listings.mine')" :active="request()->routeIs('listings.mine')">
                        {{ __('Mani sludinājumi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
                        {{ __('Favorīti') }}
                    </x-nav-link>
                    <x-nav-link :href="route('listings.create')" :active="request()->routeIs('listings.create')">
                        {{ __('Pievienot sludinājumu') }}
                    </x-nav-link>
                @endauth

                @if(auth()->user()?->is_admin)
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                        <span>{{ __('Admin panelis') }}</span>
                        <span x-cloak x-show="adminCount>0" x-text="adminCount"
                              class="ml-2 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-600 px-1 text-[0.65rem] font-bold leading-none text-white"></span>
                    </x-nav-link>
                @endif
            </div>
        </div>

        <!-- Desktop actions -->
        <div class="hidden items-center gap-3 md:flex">
            <button type="button" @click="$store.theme.toggle()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-[#2B7A78] transition hover:border-[#2B7A78] dark:border-slate-700"
                    aria-label="Tumšais režīms">
                <svg x-show="!$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3.5a.75.75 0 0 1 .75.75V6a.75.75 0 0 1-1.5 0V4.25A.75.75 0 0 1 10 3.5Z"/></svg>
                <svg x-show="$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.284 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd"/></svg>
            </button>

            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-800 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-[#2B7A78]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profils') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Iziet') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                       class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                        {{ __('Ieiet') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="rounded-xl bg-[#2B7A78] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#22615F]">
                        {{ __('Reģistrēties') }}
                    </a>
                </div>
            @endauth
        </div>

        <!-- Mobile hamburger -->
        <div class="flex items-center md:hidden">
            <button @click="open=!open"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200"
                    :aria-expanded="open.toString()" aria-label="Izvēlne">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile panel -->
    <div x-cloak x-show="open" class="md:hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 z-30 bg-slate-900/60" @click="open=false" aria-hidden="true"></div>

        <!-- Panel -->
        <div
            class="fixed inset-x-0 top-16 z-40 h-[calc(100vh-4rem)] overflow-y-auto border-t border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
        >
            <div class="mb-2 flex justify-end">
                <button @click="open=false" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900" aria-label="Aizvērt">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sekcija: Navigācija -->
            <div class="space-y-1">
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
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" @click="open=false">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" @click="open=false">
                        <span>{{ __('Admin panelis') }}</span>
                        <span x-cloak x-show="adminCount>0" x-text="adminCount"
                              class="ml-2 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-600 px-1 text-[0.65rem] font-bold leading-none text-white"></span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.bidding.index')" :active="request()->routeIs('admin.bidding.*')" @click="open=false">
                        {{ __('Izsoles auto') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Guests -->
            @guest
            <div class="mt-6 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                <p class="mb-3 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Sveicināts, viesi') }}</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}"
                       class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-800 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                        {{ __('Ieiet') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-[#2B7A78] px-4 py-2 text-center text-sm font-semibold text-white transition hover:bg-[#22615F]">
                        {{ __('Reģistrēties') }}
                    </a>
                </div>
            </div>
            @endguest

            <!-- Auth block -->
            @auth
            <div class="mt-6 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                <div class="space-y-1 text-sm text-slate-700 dark:text-slate-300">
                    <p class="font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate">{{ Auth::user()->email }}</p>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" @click="open=false">
                        {{ __('Profils') }}
                    </x-responsive-nav-link>
                    <button type="button" @click="$store.theme.toggle()"
                            class="flex w-full items-center justify-between rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-[#2B7A78] hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200">
                        <span>{{ __('Tumšais režīms') }}</span>
                        <svg x-show="!$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3.5a.75.75 0 0 1 .75.75V6a.75.75 0 0 1-1.5 0V4.25A.75.75 0 0 1 10 3.5Z"/></svg>
                        <svg x-show="$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.284 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd"/></svg>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Iziet') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </div>
</nav>
