<nav
    x-data="mainNavigation({{ $adminNotificationCount ?? 0 }})"
    class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur-sm transition dark:border-slate-800/80 dark:bg-slate-950/70"
>
    <!-- Primary Navigation Menu -->
    <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex flex-1 items-center gap-6">
            <!-- Logo -->
            <a
                href="{{ route('listings.index') }}"
                class="flex items-center gap-2 rounded-xl border border-transparent px-2 py-1 text-slate-700 transition hover:border-[#2B7A78]/40 hover:bg-white/90 dark:text-slate-200 dark:hover:border-[#2B7A78]/40 dark:hover:bg-slate-900/70"
            >
                <x-application-logo class="block h-8 w-auto fill-current text-[#2B7A78]" />
                <span class="hidden text-sm font-semibold tracking-tight sm:inline">
                    {{ config('app.name', 'CarMarket') }}
                </span>
            </a>

            <!-- Navigation Links -->
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
                        {{ __('Pievienot') }}
                    </x-nav-link>
                @endauth

                {{-- Dashboard redz tikai admini --}}
                @if(auth()->user()?->is_admin)
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                @endif

                {{-- Admin panelis --}}
                @if(auth()->user()?->is_admin)
                    <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                        <span>{{ __('Admin panelis') }}</span>
                        <span
                            x-cloak
                            x-show="adminCount > 0"
                            x-text="adminCount"
                            class="inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[0.65rem] font-semibold leading-none text-white"
                        >{{ $adminNotificationCount ?? 0 }}</span>
                    </x-nav-link>
                @endif
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="hidden items-center gap-3 md:flex">
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/80 text-[#2B7A78] transition hover:border-[#2B7A78]/50 hover:text-[#22615F] dark:border-slate-700 dark:text-[#2B7A78] dark:hover:border-[#2B7A78]/60"
            >
                <span class="sr-only">Tumšais režīms</span>
                <svg x-show="!$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 3.5a.75.75 0 0 1 .75.75V6a.75.75 0 0 1-1.5 0V4.25A.75.75 0 0 1 10 3.5Z" />
                    <path d="M6.404 5.404a.75.75 0 0 1 1.06 0l.884.884a.75.75 0 1 1-1.06 1.06l-.884-.883a.75.75 0 0 1 0-1.061Z" />
                    <path d="M3.5 10a.75.75 0 0 1 .75-.75H6a.75.75 0 0 1 0 1.5H4.25A.75.75 0 0 1 3.5 10Z" />
                    <path d="M6.404 14.596a.75.75 0 0 1 0-1.061l.884-.884a.75.75 0 0 1 1.06 1.061l-.884.884a.75.75 0 0 1-1.06 0Z" />
                    <path d="M10 13.5a.75.75 0 0 1 .75.75V16a.75.75 0 0 1-1.5 0v-1.75A.75.75 0 0 1 10 13.5Z" />
                    <path d="M13.596 14.596a.75.75 0 0 1 0-1.061l.884-.884a.75.75 0 0 1 1.06 1.061l-.884.884a.75.75 0 0 1-1.06 0Z" />
                    <path d="M14 10a.75.75 0 0 1 .75-.75H16a.75.75 0 0 1 0 1.5h-1.25A.75.75 0 0 1 14 10Z" />
                    <path d="M13.596 5.404a.75.75 0 0 1 1.06 0c.293.293.293.768 0 1.061l-.884.883a.75.75 0 0 1-1.06-1.06l.884-.884Z" />
                    <path d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4Z" />
                </svg>
                <svg x-show="$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.284 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd" />
                </svg>
            </button>
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200/80 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#2B7A78]/40 hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200 dark:hover:border-[#2B7A78]/50"
                        >
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-[#2B7A78]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profils') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Iziet') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 transition hover:text-[#2B7A78] dark:text-slate-300 dark:hover:text-[#2B7A78]">
                    {{ __('Ieiet') }}
                </a>
                <a href="{{ route('register') }}" class="rounded-xl bg-[#2B7A78] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#22615F]">
                    {{ __('Reģistrēties') }}
                </a>
            @endauth
        </div>

        <!-- Mobile Hamburger -->
        <div class="flex items-center md:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200/80 p-2 text-slate-600 transition hover:border-[#2B7A78]/40 hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200 dark:hover:border-[#2B7A78]/50"
            >
                <span class="sr-only">Izvēlne</span>
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200/70 bg-white/90 backdrop-blur-sm md:hidden dark:border-slate-800/80 dark:bg-slate-950/70">
        <div class="space-y-1 px-4 py-4">
            <x-responsive-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.index')">
                {{ __('Sludinājumi') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('listings.mine')" :active="request()->routeIs('listings.mine')">
                    {{ __('Mani sludinājumi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
                    {{ __('Favorīti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('listings.create')" :active="request()->routeIs('listings.create')">
                    {{ __('Pievienot') }}
                </x-responsive-nav-link>
            @endauth

            {{-- Dashboard redz tikai admini --}}
            @if(auth()->user()?->is_admin)
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif

            {{-- Admin sadaļa --}}
            @if(auth()->user()?->is_admin)
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                    <span>{{ __('Admin panelis') }}</span>
                    <span
                        x-cloak
                        x-show="adminCount > 0"
                        x-text="adminCount"
                        class="ml-2 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[0.65rem] font-semibold leading-none text-white"
                    >{{ $adminNotificationCount ?? 0 }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.bidding.index')" :active="request()->routeIs('admin.bidding.*')">
                    {{ __('Izsoles auto') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Mobile User Info -->
        <div class="border-t border-slate-200/70 px-4 py-4 dark:border-slate-800/70">
            @auth
                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-300">
                    <p class="font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p>{{ Auth::user()->email }}</p>
                </div>

                <div class="mt-4 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profils') }}
                    </x-responsive-nav-link>

                    <button type="button" @click="$store.theme.toggle()"
                        class="flex w-full items-center justify-between rounded-lg border border-slate-200/70 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#2B7A78]/40 hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-200 dark:hover:border-[#2B7A78]/50"
                    >
                        <span>{{ __('Tumšais režīms') }}</span>
                        <svg x-show="!$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3.5a.75.75 0 0 1 .75.75V6a.75.75 0 0 1-1.5 0V4.25A.75.75 0 0 1 10 3.5Z" />
                            <path d="M6.404 5.404a.75.75 0 0 1 1.06 0l.884.884a.75.75 0 1 1-1.06 1.06l-.884-.883a.75.75 0 0 1 0-1.061Z" />
                            <path d="M3.5 10a.75.75 0 0 1 .75-.75H6a.75.75 0 0 1 0 1.5H4.25A.75.75 0 0 1 3.5 10Z" />
                            <path d="M6.404 14.596a.75.75 0 0 1 0-1.061l.884-.884a.75.75 0 0 1 1.06 1.061l-.884.884a.75.75 0 0 1-1.06 0Z" />
                            <path d="M10 13.5a.75.75 0 0 1 .75.75V16a.75.75 0 0 1-1.5 0v-1.75A.75.75 0 0 1 10 13.5Z" />
                            <path d="M13.596 14.596a.75.75 0 0 1 0-1.061l.884-.884a.75.75 0 0 1 1.06 1.061l-.884.884a.75.75 0 0 1-1.06 0Z" />
                            <path d="M14 10a.75.75 0 0 1 .75-.75H16a.75.75 0 0 1 0 1.5h-1.25A.75.75 0 0 1 14 10Z" />
                            <path d="M13.596 5.404a.75.75 0 0 1 1.06 0c.293.293.293.768 0 1.061l-.884.883a.75.75 0 0 1-1.06-1.06l.884-.884Z" />
                            <path d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4Z" />
                        </svg>
                        <svg x-show="$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.284 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Iziet') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="flex flex-col gap-2 text-sm">
                    <a href="{{ route('login') }}" class="rounded-lg border border-slate-200/70 px-4 py-2 font-semibold text-slate-600 transition hover:border-[#2B7A78]/40 hover:text-[#2B7A78] dark:border-slate-700 dark:text-slate-300 dark:hover:border-[#2B7A78]/50">
                        {{ __('Ieiet') }}
                    </a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-[#2B7A78] px-4 py-2 text-center font-semibold text-white transition hover:bg-[#22615F]">
                        {{ __('Reģistrēties') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
