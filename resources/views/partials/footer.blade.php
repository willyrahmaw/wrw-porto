<footer class="relative bg-light-surface/80 dark:bg-[#060606] border-t border-light-border dark:border-dark-border pt-16 sm:pt-20 md:pt-24 pb-12 overflow-hidden transition-colors duration-500">
    {{-- Subtle Noise Texture Overlay --}}
    <div class="pointer-events-none absolute inset-0 z-0 bg-grain opacity-20 dark:opacity-40" aria-hidden="true"></div>

    {{-- Subtle Radial Glow in Dark Mode --}}
    <div class="pointer-events-none absolute -bottom-32 left-1/2 -translate-x-1/2 w-[800px] h-[350px] bg-accent/5 dark:bg-accent/[0.03] blur-[120px] rounded-full z-0" aria-hidden="true"></div>

    <div class="relative z-10 max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        {{-- ================================================================= --}}
        {{-- TOP ROW: BRAND MARK & AVAILABILITY STATUS                         --}}
        {{-- ================================================================= --}}
        <div class="pb-12 border-b border-light-border dark:border-dark-border/80 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] font-mono uppercase tracking-widest text-muted">
                    <span class="w-2 h-2 rounded-full {{ $profile?->is_available ? 'bg-accent animate-pulse' : 'bg-accent-orange' }}"></span>
                    <span>{{ $profile?->is_available ? 'Slot Pengerjaan Terbuka // 2026' : 'Slot Penuh • Reservasi Antrean' }}</span>
                </div>
                <h3 class="font-display italic text-4xl sm:text-5xl text-dark dark:text-offwhite tracking-tight">
                    {{ $profile?->name ?? 'Willy Rahmat Wijaya' }}
                </h3>
            </div>

            <div class="flex flex-wrap items-center gap-4 text-xs font-mono">
                @if($profile?->email)
                    <a
                        href="mailto:{{ $profile->email }}"
                        class="px-4 py-2.5 border border-light-border dark:border-dark-border bg-light dark:bg-dark-card hover:border-accent hover:text-accent transition-colors flex items-center gap-2 group"
                    >
                        <span class="text-muted group-hover:text-accent">✉</span>
                        <span>{{ $profile->email }}</span>
                    </a>
                @endif
                @if($profile?->whatsapp)
                    <a
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->whatsapp) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="px-4 py-2.5 border border-accent/40 bg-accent/10 hover:bg-accent hover:text-dark text-accent transition-colors flex items-center gap-2"
                    >
                        <span>WhatsApp Quick Chat</span>
                        <span>↗</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- EDITORIAL SITEMAP & CAPABILITIES GRID                             --}}
        {{-- ================================================================= --}}
        <div class="py-14 sm:py-16 border-b border-light-border dark:border-dark-border/80 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 text-xs font-mono">

            {{-- Col 1: Studio Identity & Work Ethic (4 Cols) --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    IDENTITY &bull; PRINCIPLES
                </div>
                <p class="text-muted leading-relaxed font-sans text-sm pr-4">
                    {{ $profile?->tagline ?? 'Membantu bisnis membangun website yang rapi, cepat, dan mudah dikelola dengan ekosistem Laravel & Tailwind CSS.' }}
                </p>
                <p class="text-xs text-muted leading-relaxed font-sans pr-4">
                    Prinsip kerja jelas: memastikan sistem berjalan lancar, kode mudah dikembangkan, dan siap mendampingi kebutuhan bisnis Anda jangka panjang.
                </p>

                <div class="pt-2 text-[11px] text-muted space-y-1">
                    <div>DOMISILI: <span class="text-dark dark:text-offwhite font-semibold">{{ $profile?->location ?? 'Indonesia' }}</span></div>
                    <div>STATUS: <span class="text-accent font-semibold">{{ $profile?->is_available ? 'Available for Contract / Freelance' : 'Fully Booked' }}</span></div>
                </div>
            </div>

            {{-- Col 2: Navigation Index (3 Cols) --}}
            <div class="lg:col-span-3 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    01 // SITEMAP
                </div>
                <ul class="space-y-3 uppercase tracking-wider text-muted">
                    <li>
                        <a href="{{ route('home') }}#about" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1.5 inline-flex items-center gap-2 transition-transform group">
                            <span class="text-accent opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                            <span>Tentang Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#services" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1.5 inline-flex items-center gap-2 transition-transform group">
                            <span class="text-accent opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                            <span>Layanan &amp; Solusi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('projects.index') }}" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1.5 inline-flex items-center gap-2 transition-transform group">
                            <span class="text-accent opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                            <span>Portofolio Proyek</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#clients" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1.5 inline-flex items-center gap-2 transition-transform group">
                            <span class="text-accent opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                            <span>Klien &amp; Testimoni</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#contact" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1.5 inline-flex items-center gap-2 transition-transform group">
                            <span class="text-accent opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                            <span>Hubungi Saya</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Col 3: Layanan Spesialisasi & Stack (3 Cols) --}}
            <div class="lg:col-span-3 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    02 // KEAHLIAN &bull; CORE STACK
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">Laravel 11</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">PHP 8.4</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">Tailwind CSS</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">Alpine.js</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">REST API</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">MySQL / SQLite</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">SEO &amp; Performance</span>
                    <span class="px-2.5 py-1 bg-light dark:bg-dark-card border border-light-border dark:border-dark-border text-[11px] text-dark dark:text-offwhite">Security Hardening</span>
                </div>
            </div>

            {{-- Col 4: Tautan Eksternal & Testimoni (2 Cols) --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    03 // JEJARING
                </div>
                <ul class="space-y-2.5 uppercase tracking-wider text-muted">
                    @if($profile?->github)
                        <li>
                            <a href="{{ $profile->github }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center justify-between group">
                                <span>GitHub</span>
                                <span class="group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">↗</span>
                            </a>
                        </li>
                    @endif
                    @if($profile?->linkedin)
                        <li>
                            <a href="{{ $profile->linkedin }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center justify-between group">
                                <span>LinkedIn</span>
                                <span class="group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">↗</span>
                            </a>
                        </li>
                    @endif
                    @if($profile?->instagram)
                        <li>
                            <a href="{{ $profile->instagram }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center justify-between group">
                                <span>Instagram</span>
                                <span class="group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">↗</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="pt-2">
                    <a
                        href="{{ route('testimonials.create') }}"
                        class="p-3 border border-dashed border-accent/40 bg-accent/5 hover:bg-accent/15 hover:border-accent text-accent block transition-colors group"
                    >
                        <div class="flex items-center justify-between font-semibold text-[11px]">
                            <span>Beri Ulasan Klien</span>
                            <span>★</span>
                        </div>
                        <p class="text-[10px] text-muted normal-case mt-1 font-sans">
                            Bagikan pengalaman bekerja sama
                        </p>
                    </a>
                </div>
            </div>

        </div>

        {{-- ================================================================= --}}
        {{-- GRAND DISPLAY SIGNATURE (Awwwards Editorial Watermark)            --}}
        {{-- ================================================================= --}}
        <div class="py-12 sm:py-16 md:py-20 border-b border-light-border dark:border-dark-border/80 overflow-hidden select-none">
            <div class="font-display italic text-[13.5vw] leading-[0.8] tracking-tighter text-transparent [-webkit-text-stroke:1px_rgba(10,10,10,0.12)] dark:[-webkit-text-stroke:1px_rgba(237,237,232,0.08)] hover:[-webkit-text-stroke:1px_rgba(198,244,50,0.4)] dark:hover:[-webkit-text-stroke:1px_rgba(198,244,50,0.4)] hover:text-accent/5 text-center uppercase whitespace-nowrap transition-all duration-700">
                {{ $profile?->name ?? 'WILLY RAHMAT WIJAYA' }}
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- BOTTOM UTILITY RAIL: Clock, Copyright & Back to Top               --}}
        {{-- ================================================================= --}}
        <div class="pt-8 sm:pt-10 flex flex-col md:flex-row items-center justify-between gap-6 text-xs font-mono uppercase tracking-[0.18em] text-muted">

            {{-- Real-Time Local Time & Timezone --}}
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-accent"></span>
                <span>WAKTU LOKAL:</span>
                <span class="local-clock text-dark dark:text-offwhite font-bold">--:--:-- WIB</span>
            </div>

            {{-- Copyright Text --}}
            <div class="text-center text-[11px] sm:text-xs">
                &copy; {{ date('Y') }} {{ $profile?->name ?? 'Willy Rahmat Wijaya' }} &bull; All Rights Reserved.
            </div>

            {{-- Magnetic Back To Top Button --}}
            <div>
                <button
                    type="button"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="magnetic-btn inline-flex items-center gap-2.5 px-4 py-2 border border-light-border dark:border-dark-border text-dark dark:text-offwhite hover:border-accent hover:text-accent transition-colors cursor-pointer group"
                    aria-label="Kembali ke atas halaman"
                >
                    <span>Ke Atas</span>
                    <span class="group-hover:-translate-y-1 transition-transform">&uarr;</span>
                </button>
            </div>

        </div>

    </div>
</footer>
