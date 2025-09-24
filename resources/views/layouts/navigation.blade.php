<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('listings.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:space-x-8 sm:ms-10">
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

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()?->is_admin)
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                            {{ __('Admin panelis') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-4">
                <button type="button" @click="$store.theme.toggle()"
                    class="inline-flex items-center justify-center rounded-full border border-transparent bg-gray-100 px-3 py-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
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
                        <path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.28 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd" />
                    </svg>
                </button>
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 
                                            1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
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
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline me-4">
                        {{ __('Ieiet') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                        {{ __('Reģistrēties') }}
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition">
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
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
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

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()?->is_admin)
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                    {{ __('Admin panelis') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profils') }}
                    </x-responsive-nav-link>

                    <button type="button" @click="$store.theme.toggle()"
                        class="flex w-full items-center gap-2 rounded-md px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
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
                            <path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.28 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd" />
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
                <div class="px-4">
                    <button type="button" @click="$store.theme.toggle()"
                        class="mb-2 flex w-full items-center justify-between rounded-md px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
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
                            <path fill-rule="evenodd" d="M9.598 2.304a.75.75 0 0 1 .95-.95 7 7 0 1 1-7.294 11.465.75.75 0 0 1 .317-1.28 4.8 4.8 0 0 0 3.21-5.082 4.8 4.8 0 0 0 2.817-4.153Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <x-responsive-nav-link :href="route('login')">{{ __('Ieiet') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">{{ __('Reģistrēties') }}</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
