<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap');

    .nav-root { font-family: 'DM Sans', sans-serif; }
    .nav-wordmark { font-family: 'Syne', sans-serif; }

    /* ── pill track ── */
    .pill-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 500;
        letter-spacing: 0.01em;
        color: var(--nav-text-muted);
        transition: color 0.18s, background 0.18s;
        white-space: nowrap;
    }
    .pill-link:hover { color: var(--nav-text); background: var(--nav-pill-hover); }
    .pill-link.active {
        color: var(--nav-accent);
        background: var(--nav-accent-subtle);
        font-weight: 600;
    }
    .pill-link .dot {
        width: 4px; height: 4px;
        border-radius: 50%;
        background: var(--nav-accent);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .pill-link.active .dot { opacity: 1; }

    /* ── CTA button ── */
    .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 18px;
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        background: var(--nav-accent);
        color: #fff;
        box-shadow: 0 2px 12px rgba(43,122,120,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
        transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
    }
    .cta-btn:hover {
        background: #22615F;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(43,122,120,0.45), inset 0 1px 0 rgba(255,255,255,0.15);
    }
    .cta-btn:active { transform: scale(0.97); }

    /* ── icon btn ── */
    .icon-btn {
        width: 36px; height: 36px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px;
        color: var(--nav-text-muted);
        transition: color 0.15s, background 0.15s;
    }
    .icon-btn:hover { color: var(--nav-accent); background: var(--nav-pill-hover); }

    /* ── user chip ── */
    .user-chip {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 4px 12px 4px 4px;
        border-radius: 100px;
        border: 1px solid var(--nav-border);
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--nav-text);
        transition: border-color 0.15s, background 0.15s;
        cursor: pointer;
        background: transparent;
    }
    .user-chip:hover { border-color: var(--nav-accent); background: var(--nav-accent-subtle); }

    .avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2B7A78, #14b8a6);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; font-weight: 700; color: #fff;
        font-family: 'Syne', sans-serif;
        flex-shrink: 0;
    }

    /* ── admin pill ── */
    .admin-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px 5px 8px;
        border-radius: 100px;
        border: 1px solid rgba(245,158,11,0.3);
        background: rgba(245,158,11,0.07);
        font-size: 0.775rem; font-weight: 600;
        color: #b45309;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
    }
    .admin-pill:hover { border-color: rgba(245,158,11,0.55); background: rgba(245,158,11,0.12); }
    .dark .admin-pill { color: #fbbf24; }

    /* ── dropdown ── */
    .nav-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        min-width: 220px;
        border-radius: 16px;
        border: 1px solid var(--nav-border);
        background: var(--nav-dropdown-bg);
        box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.06);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        overflow: hidden;
        transform-origin: top center;
    }
    .nav-dropdown-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px;
        font-size: 0.8125rem; font-weight: 500;
        color: var(--nav-text-muted);
        transition: color 0.15s, background 0.15s;
        cursor: pointer; text-decoration: none;
    }
    .nav-dropdown-item:hover { color: var(--nav-text); background: var(--nav-pill-hover); }
    .nav-dropdown-item svg { flex-shrink: 0; color: var(--nav-accent); opacity: 0.75; }

    /* ── mobile ── */
    .mobile-link {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 16px;
        border-radius: 12px;
        font-size: 0.875rem; font-weight: 500;
        color: var(--nav-text-muted);
        transition: color 0.15s, background 0.15s;
        text-decoration: none;
    }
    .mobile-link:hover { color: var(--nav-text); background: var(--nav-pill-hover); }
    .mobile-link.active { color: var(--nav-accent); background: var(--nav-accent-subtle); font-weight: 600; }

    /* ── divider label ── */
    .section-label {
        padding: 6px 16px 4px;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--nav-text-muted);
        opacity: 0.5;
        font-family: 'Syne', sans-serif;
    }

    /* ── scroll shimmer line ── */
    .progress-line {
        position: absolute;
        bottom: 0; left: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--nav-accent), transparent);
        opacity: 0;
        transition: opacity 0.3s;
        width: 100%;
    }
    [data-scrolled="true"] .progress-line { opacity: 1; }

    /* ── CSS variables ── */
    :root {
        --nav-accent: #2B7A78;
        --nav-accent-subtle: rgba(43,122,120,0.08);
        --nav-text: #0f172a;
        --nav-text-muted: #64748b;
        --nav-border: rgba(15,23,42,0.1);
        --nav-bg: rgba(255,255,255,0.88);
        --nav-bg-scrolled: rgba(255,255,255,0.94);
        --nav-pill-hover: rgba(15,23,42,0.05);
        --nav-dropdown-bg: rgba(255,255,255,0.97);
    }
    .dark {
        --nav-accent: #2dd4bf;
        --nav-accent-subtle: rgba(45,212,191,0.1);
        --nav-text: #f1f5f9;
        --nav-text-muted: #94a3b8;
        --nav-border: rgba(255,255,255,0.1);
        --nav-bg: rgba(10,15,30,0.88);
        --nav-bg-scrolled: rgba(10,15,30,0.96);
        --nav-pill-hover: rgba(255,255,255,0.06);
        --nav-dropdown-bg: rgba(12,18,36,0.97);
    }

    /* transition utility */
    [x-cloak] { display: none !important; }
</style>

<nav
    class="nav-root sticky top-0 z-40"
    x-data="{
        open: false,
        adminOpen: false,
        userOpen: false,
        adminCount: Number({{ $adminNotificationCount ?? 0 }} || 0),
        scrolled: false,
        init() {
            const update = () => {
                this.scrolled = window.scrollY > 8;
                this.$el.dataset.scrolled = this.scrolled;
            };
            update();
            window.addEventListener('scroll', update, { passive: true });
        }
    }"
    x-init="init()"
    x-effect="document.documentElement.style.overflowY = open ? 'hidden' : ''"
    @keydown.escape.window="open = false; adminOpen = false; userOpen = false"
    @click.outside="adminOpen = false; userOpen = false"
    style="font-family: 'DM Sans', sans-serif;"
>
    {{-- ── background layer ──────────────────────────────────────── --}}
    <div class="absolute inset-0 -z-10 transition-all duration-500"
         :style="scrolled
             ? 'background: var(--nav-bg-scrolled); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);'
             : 'background: var(--nav-bg); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);'">
        {{-- Mesh gradient accents --}}
        <div class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(ellipse 40% 80% at -5% 50%, rgba(43,122,120,0.08) 0%, transparent 70%), radial-gradient(ellipse 30% 60% at 105% 50%, rgba(20,184,166,0.06) 0%, transparent 70%);">
        </div>
    </div>
    {{-- scroll shimmer --}}
    <div class="progress-line pointer-events-none"></div>

    {{-- ── TOP BAR ────────────────────────────────────────────────── --}}
    <div class="mx-auto flex h-16 w-full max-w-7xl items-center px-4 sm:px-6 lg:px-10 gap-4">

        {{-- ── Logo ── --}}
        <a href="{{ route('landing.index') }}"
           class="group flex items-center gap-3 mr-2 flex-shrink-0">
            <div class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#2B7A78] to-teal-500 shadow-lg shadow-teal-500/25 transition-all duration-300 group-hover:shadow-teal-500/40 group-hover:scale-105">
                <x-application-logo class="block h-5 w-auto fill-current text-white" />
                <div class="absolute inset-0 rounded-xl ring-2 ring-white/20"></div>
            </div>
            <span class="nav-wordmark hidden text-[15px] font-700 tracking-tight sm:inline"
                  style="font-weight: 700; color: var(--nav-text); letter-spacing: -0.02em;">
                Car<span style="color: var(--nav-accent);">Market</span>
            </span>
        </a>

        {{-- ── Desktop nav ─────────────────────────────────────────── --}}
        <div class="hidden lg:flex items-center gap-0.5 flex-1">

            <a href="{{ route('landing.index') }}"
               class="pill-link {{ request()->routeIs('landing.index') ? 'active' : '' }}">
                <span class="dot"></span>
                {{ __('Sākumlapa') }}
            </a>

            <a href="{{ route('listings.index') }}"
               class="pill-link {{ request()->routeIs('listings.index') ? 'active' : '' }}">
                <span class="dot"></span>
                {{ __('Sludinājumi') }}
            </a>

            @auth
                <a href="{{ route('listings.mine') }}"
                   class="pill-link {{ request()->routeIs('listings.mine') ? 'active' : '' }}">
                    <span class="dot"></span>
                    {{ __('Mani sludinājumi') }}
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="pill-link {{ request()->routeIs('favorites.index') ? 'active' : '' }}">
                    <span class="dot"></span>
                    {{ __('Favorīti') }}
                </a>
            @endauth
        </div>

        {{-- ── Right actions ────────────────────────────────────────── --}}
        <div class="hidden lg:flex items-center gap-2 ml-auto">

            {{-- Admin dropdown --}}
            @if(auth()->user()?->is_admin)
                <div class="relative" @click.outside="adminOpen = false">
                    <button @click="adminOpen = !adminOpen" class="admin-pill">
                        <span style="font-size:10px;">★</span>
                        <span>{{ __('Admin') }}</span>
                        <span x-cloak x-show="adminCount > 0"
                              class="inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white leading-none"
                              x-text="adminCount"></span>
                        <svg class="h-3 w-3 transition-transform duration-200" :class="adminOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div x-cloak x-show="adminOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="nav-dropdown" style="left: 0;">
                        <div style="padding: 8px 14px 6px; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; opacity: 0.4; font-family: 'Syne', sans-serif; color: var(--nav-text-muted);">
                            {{ __('Admin iespējas') }}
                        </div>
                        <div style="height: 1px; background: var(--nav-border); margin: 0 10px;"></div>
                        <div style="padding: 6px;">
                            <a href="{{ route('admin.index') }}" class="nav-dropdown-item" style="border-radius: 10px;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                {{ __('Admin panelis') }}
                            </a>
                            <a href="{{ route('admin.bidding.index') }}" class="nav-dropdown-item" style="border-radius: 10px;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 006.54 17H17M7 13l1-4h9"/><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg>
                                {{ __('Izsoļu pārvaldība') }}
                            </a>
                            <a href="{{ route('admin.bidding.create') }}" class="nav-dropdown-item" style="border-radius: 10px;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                {{ __('Pievienot izsoles auto') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Add listing CTA --}}
            @auth
                <a href="{{ route('listings.create') }}" class="cta-btn {{ request()->routeIs('listings.create') ? 'ring-2 ring-offset-2 ring-[#2B7A78]' : '' }}">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    {{ __('Pievienot') }}
                </a>
            @endauth

            {{-- Dark mode --}}
            <button type="button"
                    @click="$store.theme.toggle()"
                    class="icon-btn"
                    aria-label="Tumšais režīms">
                <svg x-show="!$store.theme.isDark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                </svg>
                <svg x-show="$store.theme.isDark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </button>

            {{-- Auth --}}
            @auth
                {{-- User dropdown --}}
                <div class="relative" @click.outside="userOpen = false">
                    <button @click="userOpen = !userOpen" class="user-chip">
                        <span class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span style="max-width: 7rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</span>
                        <svg class="h-3 w-3 transition-transform duration-200 flex-shrink-0" :class="userOpen ? 'rotate-180' : ''" style="color: var(--nav-text-muted);" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div x-cloak x-show="userOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="nav-dropdown" style="right: 0; min-width: 200px;">
                        {{-- User header --}}
                        <div style="padding: 14px 16px 12px; border-bottom: 1px solid var(--nav-border);">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span class="avatar" style="width: 34px; height: 34px; font-size: 0.75rem; flex-shrink: 0;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <div style="min-width: 0;">
                                    <p style="font-size: 0.8125rem; font-weight: 600; color: var(--nav-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->name }}</p>
                                    <p style="font-size: 0.7rem; color: var(--nav-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 6px;">
                            <a href="{{ route('profile.edit') }}" class="nav-dropdown-item" style="border-radius: 10px;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                {{ __('Profils') }}
                            </a>
                            <div style="height: 1px; background: var(--nav-border); margin: 4px 6px;"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-dropdown-item w-full" style="border-radius: 10px; border: none; background: none; cursor: pointer; text-align: left; width: 100%; color: #ef4444;">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #ef4444; opacity: 0.75;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                    {{ __('Iziet') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="pill-link">
                    {{ __('Ieiet') }}
                </a>
                <a href="{{ route('register') }}" class="cta-btn">
                    {{ __('Reģistrēties') }}
                </a>
            @endauth
        </div>

        {{-- ── Mobile hamburger ────────────────────────────────────── --}}
        <div class="flex items-center gap-2 ml-auto lg:hidden">
            @auth
                <a href="{{ route('listings.create') }}" class="cta-btn" style="padding: 7px 14px; font-size: 0.75rem;">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    {{ __('Pievienot') }}
                </a>
            @endauth

            <button type="button"
                    @click="open = !open"
                    class="icon-btn border"
                    style="border: 1px solid var(--nav-border);"
                    :aria-expanded="open.toString()"
                    aria-label="Izvēlne">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <g x-show="!open">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                    </g>
                    <g x-show="open">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                    </g>
                </svg>
            </button>
        </div>
    </div>

    {{-- ─── Mobile Panel ──────────────────────────────────────────────── --}}
    <div x-cloak x-show="open" class="lg:hidden">
        {{-- Backdrop --}}
        <div class="fixed inset-0 z-30"
             style="background: rgba(0,0,0,0.45); backdrop-filter: blur(4px);"
             @click="open = false"
             aria-hidden="true">
        </div>

        {{-- Panel --}}
        <div class="fixed inset-x-0 top-16 z-40 overflow-y-auto"
             style="height: calc(100dvh - 4rem); background: var(--nav-dropdown-bg);"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4">

            <div style="padding: 12px 12px 24px;">

                {{-- Nav section --}}
                <div class="section-label">{{ __('Navigācija') }}</div>
                <div style="display: flex; flex-direction: column; gap: 2px; margin-bottom: 8px;">
                    <a href="{{ route('landing.index') }}" @click="open=false"
                       class="mobile-link {{ request()->routeIs('landing.index') ? 'active' : '' }}">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5L12 4l9 7.5M6.5 10.5V20h11V10.5"/></svg>
                        {{ __('Sākumlapa') }}
                    </a>
                    <a href="{{ route('listings.index') }}" @click="open=false"
                       class="mobile-link {{ request()->routeIs('listings.index') ? 'active' : '' }}">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" d="M7 10h10M7 14h6"/></svg>
                        {{ __('Sludinājumi') }}
                    </a>

                    @auth
                        <a href="{{ route('listings.mine') }}" @click="open=false"
                           class="mobile-link {{ request()->routeIs('listings.mine') ? 'active' : '' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M6 7V5h12v2m-1 0v12H7V7m3 4h4"/></svg>
                            {{ __('Mani sludinājumi') }}
                        </a>
                        <a href="{{ route('favorites.index') }}" @click="open=false"
                           class="mobile-link {{ request()->routeIs('favorites.index') ? 'active' : '' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m12 20-7-6.3A4.4 4.4 0 0111.2 7L12 7.8l.8-.8a4.4 4.4 0 016.2 6.2L12 20Z"/></svg>
                            {{ __('Favorīti') }}
                        </a>
                    @endauth
                </div>

                {{-- Admin section --}}
                @if(auth()->user()?->is_admin)
                    <div style="height: 1px; background: var(--nav-border); margin: 8px 4px;"></div>
                    <div class="section-label" style="color: rgba(245,158,11,0.8);">{{ __('Admin zona') }}</div>
                    <div style="display: flex; flex-direction: column; gap: 2px; margin-bottom: 8px;">
                        <a href="{{ route('admin.index') }}" @click="open=false"
                           class="mobile-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                            <span style="display: flex; align-items: center; gap: 8px;">
                                {{ __('Admin panelis') }}
                                <span x-cloak x-show="adminCount > 0"
                                      class="inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white leading-none"
                                      x-text="adminCount"></span>
                            </span>
                        </a>
                        <a href="{{ route('admin.bidding.index') }}" @click="open=false"
                           class="mobile-link {{ request()->routeIs('admin.bidding.*') ? 'active' : '' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 006.54 17H17M7 13l1-4h9"/><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg>
                            {{ __('Izsoles auto') }}
                        </a>
                    </div>
                @endif

                {{-- Account section --}}
                <div style="height: 1px; background: var(--nav-border); margin: 8px 4px;"></div>

                @guest
                    <div style="padding: 16px 4px 8px;">
                        <p style="font-size: 0.8125rem; color: var(--nav-text-muted); margin-bottom: 12px; padding: 0 8px;">
                            {{ __('Piesakies vai reģistrējies, lai piekļūtu visām funkcijām.') }}
                        </p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 0 4px;">
                            <a href="{{ route('login') }}"
                               style="display: flex; align-items: center; justify-content: center; padding: 10px; border-radius: 12px; border: 1px solid var(--nav-border); font-size: 0.875rem; font-weight: 600; color: var(--nav-text); text-decoration: none; transition: border-color 0.15s;">
                                {{ __('Ieiet') }}
                            </a>
                            <a href="{{ route('register') }}" class="cta-btn" style="justify-content: center;">
                                {{ __('Reģistrēties') }}
                            </a>
                        </div>
                    </div>
                @endguest

                @auth
                    {{-- User info card --}}
                    <div style="display: flex; align-items: center; gap: 12px; padding: 14px 8px 12px;">
                        <span class="avatar" style="width: 42px; height: 42px; font-size: 0.875rem; flex-shrink: 0;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <div style="min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 600; color: var(--nav-text);">{{ Auth::user()->name }}</p>
                            <p style="font-size: 0.75rem; color: var(--nav-text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <a href="{{ route('profile.edit') }}" @click="open=false" class="mobile-link">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            {{ __('Profils') }}
                        </a>

                        {{-- Dark mode toggle --}}
                        <button type="button"
                                @click="$store.theme.toggle()"
                                class="mobile-link" style="border: none; cursor: pointer; background: none; width: 100%; text-align: left; justify-content: space-between;">
                            <span style="display: flex; align-items: center; gap: 10px;">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                                {{ __('Tumšais režīms') }}
                            </span>
                            <span style="display: flex; align-items: center; width: 40px; height: 22px; border-radius: 100px; transition: background 0.2s; flex-shrink: 0;"
                                  :style="$store.theme.isDark ? 'background: var(--nav-accent);' : 'background: #cbd5e1;'">
                                <span style="width: 18px; height: 18px; border-radius: 50%; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.15); margin-left: 2px; transition: transform 0.2s;"
                                      :style="$store.theme.isDark ? 'transform: translateX(18px);' : 'transform: translateX(0);'"></span>
                            </span>
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="mobile-link" style="border: none; cursor: pointer; background: none; width: 100%; text-align: left; color: #ef4444;">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #ef4444;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                {{ __('Iziet') }}
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>