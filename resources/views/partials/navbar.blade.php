@php
    $navLinks = [
        ['label' => 'About', 'href' => route('home') . '#about', 'num' => '01'],
        ['label' => 'Services', 'href' => route('home') . '#services', 'num' => '02'],
        ['label' => 'Projects', 'href' => route('home') . '#projects', 'num' => '03'],
        ['label' => 'Clients', 'href' => route('home') . '#clients', 'num' => '04'],
        ['label' => 'Contact', 'href' => route('home') . '#contact', 'num' => '05'],
    ];
@endphp

<header
    x-data="{
        mobileMenuOpen: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 30;
            });
        }
    }"
    :class="scrolled ? 'bg-light/85 dark:bg-dark/85 backdrop-blur-md border-b border-light-border dark:border-dark-border py-4' : 'bg-transparent py-6'"
    class="fixed top-0 inset-x-0 z-40 transition-all duration-500 ease-out-expo"
>
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12 flex items-center justify-between">
        {{-- Left: Initials / Brand Logo & Local Time --}}
        <div class="flex items-center gap-6">
            <a
                href="{{ route('home') }}"
                class="font-display italic text-2xl md:text-3xl tracking-tight text-dark dark:text-offwhite hover:text-accent dark:hover:text-accent transition-colors"
                aria-label="Beranda {{ $profile?->name }}"
            >
                {{ $profile?->initials ?? 'WRW.' }}
            </a>

            <div class="hidden lg:flex items-center gap-2 pl-6 border-l border-light-border dark:border-dark-border text-[11px] font-mono uppercase tracking-[0.2em] text-muted">
                <span>{{ $profile?->location ?? 'Jakarta, ID' }}</span>
                <span class="text-accent">•</span>
                <span class="local-clock text-dark dark:text-offwhite">--:--:-- WIB</span>
            </div>
        </div>

        {{-- Center: Anchor Navigation (Desktop) --}}
        <nav class="hidden md:flex items-center gap-8" aria-label="Navigasi Utama">
            @foreach($navLinks as $link)
                <a
                    href="{{ $link['href'] }}"
                    class="group relative py-1 text-xs font-mono uppercase tracking-[0.18em] text-muted hover:text-dark dark:hover:text-offwhite transition-colors"
                >
                    <span class="text-[10px] text-accent/80 mr-1">{{ $link['num'] }}</span>
                    <span>{{ $link['label'] }}</span>
                    <span class="absolute bottom-0 left-0 w-0 h-px bg-accent transition-all duration-300 group-hover:w-full"></span>
                </a>
            @endforeach
        </nav>

        {{-- Right: Theme Toggle + CTA + Mobile Trigger --}}
        <div class="flex items-center gap-3 md:gap-4">
            {{-- Dark / Light Mode Toggle --}}
            <button
                type="button"
                @click="toggleTheme()"
                class="w-10 h-10 flex items-center justify-center border border-light-border dark:border-dark-border text-dark dark:text-offwhite hover:border-accent dark:hover:border-accent hover:text-accent transition-colors cursor-pointer"
                :aria-label="darkMode ? 'Ganti ke Light Mode' : 'Ganti ke Dark Mode'"
            >
                {{-- Sun Icon (shows when dark) --}}
                <svg x-show="darkMode" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>
                {{-- Moon Icon (shows when light) --}}
                <svg x-show="!darkMode" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>
            </button>

            {{-- Let's Talk CTA (Desktop) --}}
            <a
                href="{{ route('home') }}#contact"
                class="magnetic-btn hidden sm:inline-flex items-center gap-2.5 px-5 py-2.5 bg-accent text-dark text-xs font-mono uppercase tracking-[0.18em] font-semibold hover:bg-accent-hover transition-all duration-300"
            >
                <span>Let's Talk</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                </svg>
            </a>

            {{-- Mobile Hamburger Button --}}
            <button
                type="button"
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 border border-light-border dark:border-dark-border text-dark dark:text-offwhite"
                :aria-expanded="mobileMenuOpen"
                aria-label="Buka Menu Navigasi"
            >
                <span :class="mobileMenuOpen ? 'rotate-45 translate-y-1' : ''" class="w-4 h-0.5 bg-current transition-transform duration-300"></span>
                <span :class="mobileMenuOpen ? '-rotate-45 -translate-y-1' : ''" class="w-4 h-0.5 bg-current transition-transform duration-300"></span>
            </button>
        </div>
    </div>

    {{-- Fullscreen Animated Mobile Menu --}}
    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-awwwards duration-500"
        x-transition:enter-start="opacity-0 -translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-awwwards duration-400"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full"
        class="fixed inset-0 top-[72px] h-[calc(100vh-72px)] bg-light dark:bg-dark z-50 flex flex-col justify-between p-6 md:hidden border-t border-light-border dark:border-dark-border"
    >
        <nav class="flex flex-col divide-y divide-light-border dark:divide-dark-border">
            @foreach($navLinks as $link)
                <a
                    href="{{ $link['href'] }}"
                    @click="mobileMenuOpen = false"
                    class="py-5 flex items-baseline justify-between group"
                >
                    <span class="font-display italic text-4xl text-dark dark:text-offwhite group-hover:text-accent transition-colors">
                        {{ $link['label'] }}
                    </span>
                    <span class="font-mono text-xs text-accent tracking-widest">/ {{ $link['num'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="pt-6 border-t border-light-border dark:border-dark-border flex flex-col gap-4">
            <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-muted">
                <span>{{ $profile?->location }}</span>
                <span class="local-clock text-dark dark:text-offwhite">--:--:-- WIB</span>
            </div>
            <a
                href="{{ route('home') }}#contact"
                @click="mobileMenuOpen = false"
                class="w-full py-4 bg-accent text-dark text-center font-mono text-xs uppercase tracking-[0.2em] font-semibold"
            >
                Let's Talk — Mulai Proyek
            </a>
        </div>
    </div>
</header>

