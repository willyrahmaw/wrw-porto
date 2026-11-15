<section class="relative min-h-screen pt-28 pb-14 sm:pt-32 sm:pb-16 md:pt-40 md:pb-24 flex flex-col justify-between border-b border-light-border dark:border-dark-border overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12 w-full flex-grow flex flex-col justify-between">

        {{-- Top Status Row: Availability Badge & Role Tag --}}
        <div class="flex flex-wrap items-center justify-between gap-4 pb-8 border-b border-light-border dark:border-dark-border">
            <div class="inline-flex items-center gap-3 px-4 py-2 border {{ $profile?->is_available ? 'border-light-border dark:border-dark-border bg-light-surface/60 dark:bg-dark-surface/60' : 'border-accent-orange/40 bg-accent-orange/10' }}">
                <span class="relative flex h-2.5 w-2.5">
                    @if($profile?->is_available)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-accent"></span>
                    @else
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-accent-orange"></span>
                    @endif
                </span>
                <span class="text-[11px] font-mono uppercase tracking-[0.18em] {{ $profile?->is_available ? 'text-dark dark:text-offwhite' : 'text-accent-orange font-semibold' }}">
                    {{ $profile?->is_available
                        ? ($profile?->availability_status ?? 'Slot Terbuka • Sistem 1 Klien, 1 Fokus Penuh')
                        : ($profile?->unavailable_status ?? 'Slot Sedang Penuh • Fokus Pengerjaan Proyek Berjalan') }}
                </span>
            </div>

            <div class="text-xs font-mono uppercase tracking-[0.2em] text-muted">
                <span>{{ $profile?->profession ?? 'Full-Stack Web Developer' }}</span>
            </div>
        </div>

        {{-- Center Display Headline (Line-by-Line Reveal Triggered After Preloader) --}}
        <div class="my-12 md:my-16">
            <h1 class="font-display text-hero uppercase text-dark dark:text-offwhite select-none">
                <span class="reveal-line">
                    <span class="reveal-line-inner" style="transition-delay: 80ms;">
                        Full-Stack
                    </span>
                </span>
                <span class="reveal-line">
                    <span class="reveal-line-inner flex flex-wrap items-baseline gap-x-3 sm:gap-x-6" style="transition-delay: 200ms;">
                        <span class="italic font-normal text-accent lowercase">web</span>
                        <span>developer</span>
                    </span>
                </span>
                <span class="reveal-line">
                    <span class="reveal-line-inner" style="transition-delay: 320ms;">
                        &amp; builder.
                    </span>
                </span>
            </h1>
        </div>

        {{-- Bottom Asymmetric Grid: Editorial Visual Card + Subheadline + CTAs + Scroll Indicator --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-end pt-8 border-t border-light-border dark:border-dark-border">

            {{-- Left Visual Composition (5 Cols) --}}
            <div class="lg:col-span-5" data-aos="fade-right" data-aos-delay="200">
                <div class="relative overflow-hidden border border-light-border dark:border-dark-border bg-light-surface dark:bg-dark-card p-6 group">
                    <div class="flex items-center justify-between text-[11px] font-mono uppercase tracking-widest text-muted mb-4">
                        <span>Index // 00</span>
                        <span class="text-accent">{{ $profile?->name ?? 'Willy Rahmat Wijaya' }}</span>
                    </div>

                    <div class="relative aspect-[16/9] overflow-hidden bg-dark">
                        <img
                            src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=900&q=80"
                            alt="Workspace — {{ $profile?->name }}"
                            width="900"
                            height="506"
                            class="w-full h-full object-cover grayscale contrast-125 group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700 ease-out-expo"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-[11px] font-mono text-offwhite">
                            <span>PEMBUATAN WEB / FITUR BARU / MAINTENANCE</span>
                            <span class="px-2 py-0.5 bg-accent text-dark font-semibold">LARAVEL</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Value Proposition + Primary & Secondary CTA (7 Cols) --}}
            <div class="lg:col-span-7 flex flex-col justify-between gap-8" data-aos="fade-up" data-aos-delay="300">
                <div class="max-w-2xl">
                    <p class="text-xl md:text-2xl font-medium text-dark dark:text-offwhite leading-snug mb-4">
                        {{ $profile?->tagline }}
                    </p>
                    <p class="text-sm md:text-base text-muted leading-relaxed">
                        {{ $profile?->subheadline }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <x-button href="#projects" variant="primary">
                            <span>Lihat Portofolio</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>
                        </x-button>

                        <x-button href="#contact" variant="outline">
                            <span>Diskusikan Proyek</span>
                        </x-button>
                    </div>

                    {{-- Scroll Indicator --}}
                    <a
                        href="#about"
                        class="hidden sm:inline-flex items-center gap-3 text-xs font-mono uppercase tracking-[0.2em] text-muted hover:text-accent transition-colors"
                    >
                        <span>Scroll</span>
                        <span class="w-8 h-8 rounded-full border border-light-border dark:border-dark-border flex items-center justify-center animate-bounce">
                            ↓
                        </span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
