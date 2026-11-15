<footer class="relative bg-light-surface/70 dark:bg-[#070707] border-t border-light-border dark:border-dark-border pt-16 sm:pt-20 md:pt-24 pb-12 overflow-hidden">
    {{-- Subtle Noise Texture Overlay --}}
    <div class="pointer-events-none absolute inset-0 z-0 bg-grain opacity-20 dark:opacity-50" aria-hidden="true"></div>

    <div class="relative z-10 max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        {{-- ================================================================= --}}
        {{-- EDITORIAL SITEMAP & CAPABILITIES GRID                             --}}
        {{-- ================================================================= --}}
        <div class="pb-16 md:pb-20 border-b border-light-border dark:border-dark-border grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 text-xs font-mono">

            {{-- Col 1: Studio Identity (4 Cols) --}}
            <div class="lg:col-span-4 space-y-5">
                <a
                    href="{{ route('home') }}"
                    class="font-display italic text-3xl sm:text-4xl text-dark dark:text-offwhite hover:text-accent transition-colors block"
                >
                    {{ $profile?->initials ?? 'WRW.' }}
                </a>

                <p class="text-muted leading-relaxed font-sans text-sm max-w-sm">
                    {{ $profile?->tagline ?? 'Membantu bisnis membangun website yang rapi, cepat, dan mudah dikelola dengan ekosistem Laravel & Tailwind CSS.' }}
                </p>

                <div class="pt-2 text-[11px] text-muted space-y-1.5">
                    <div>LOKASI: <span class="text-dark dark:text-offwhite font-semibold">{{ $profile?->location ?? 'Indonesia' }}</span></div>
                    <div class="flex items-center gap-2">
                        <span>STATUS:</span>
                        <span class="inline-flex items-center gap-1.5 text-accent font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full {{ $profile?->is_available ? 'bg-accent animate-ping' : 'bg-accent-orange' }}"></span>
                            {{ $profile?->is_available ? 'Slot Pengerjaan Terbuka' : 'Slot Penuh • Reservasi Antrean' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Col 2: Navigation Index (3 Cols) --}}
            <div class="lg:col-span-3 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    01 // NAVIGASI UTAMA
                </div>
                <ul class="space-y-2.5 uppercase tracking-wider text-muted">
                    <li>
                        <a href="{{ route('home') }}#about" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1 inline-block transition-transform">
                            → Tentang Saya
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#services" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1 inline-block transition-transform">
                            → Layanan &amp; Solusi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('projects.index') }}" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1 inline-block transition-transform">
                            → Portofolio Proyek
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#clients" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1 inline-block transition-transform">
                            → Klien &amp; Testimoni
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#contact" class="hover:text-dark dark:hover:text-offwhite hover:translate-x-1 inline-block transition-transform">
                            → Hubungi Saya
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Col 3: Layanan Spesialisasi (3 Cols) --}}
            <div class="lg:col-span-3 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    02 // SPESIALISASI
                </div>
                <ul class="space-y-2.5 text-muted">
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-accent"></span>
                        <span>Pembuatan Web Baru (Laravel)</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-accent"></span>
                        <span>Penambahan Fitur &amp; Modul</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-accent"></span>
                        <span>Integrasi REST API &amp; Payment</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-accent"></span>
                        <span>Maintenance &amp; Perbaikan Error</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-accent"></span>
                        <span>Optimasi Kecepatan &amp; SEO</span>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Tautan Eksternal & Jejaring (2 Cols) --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="text-[10px] uppercase tracking-[0.22em] text-accent font-bold pb-2 border-b border-light-border/60 dark:border-dark-border/60">
                    03 // JEJARING
                </div>
                <ul class="space-y-2.5 uppercase tracking-wider text-muted">
                    @if($profile?->github)
                        <li>
                            <a href="{{ $profile->github }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center justify-between">
                                <span>GitHub</span>
                                <span>↗</span>
                            </a>
                        </li>
                    @endif
                    @if($profile?->linkedin)
                        <li>
                            <a href="{{ $profile->linkedin }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center justify-between">
                                <span>LinkedIn</span>
                                <span>↗</span>
                            </a>
                        </li>
                    @endif
                    @if($profile?->instagram)
                        <li>
                            <a href="{{ $profile->instagram }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center justify-between">
                                <span>Instagram</span>
                                <span>↗</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('testimonials.create') }}" class="text-accent hover:underline flex items-center justify-between">
                            <span>Beri Ulasan</span>
                            <span>★</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.login') }}" class="hover:text-muted transition-colors">
                            <span>CMS Admin</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- ================================================================= --}}
        {{-- GRAND DISPLAY SIGNATURE (Awwwards Giant Typography Watermark)     --}}
        {{-- ================================================================= --}}
        <div class="py-12 sm:py-16 md:py-20 border-b border-light-border dark:border-dark-border overflow-hidden select-none">
            <div class="font-display italic text-[14vw] leading-[0.82] tracking-tighter text-dark/15 dark:text-offwhite/10 text-center uppercase whitespace-nowrap hover:text-accent/20 dark:hover:text-accent/20 transition-colors duration-700">
                {{ $profile?->name ?? 'WILLY RAHMAT WIJAYA' }}
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- BOTTOM UTILITY RAIL: Clock, Copyright & Back to Top               --}}
        {{-- ================================================================= --}}
        <div class="pt-8 sm:pt-10 flex flex-col md:flex-row items-center justify-between gap-6 text-xs font-mono uppercase tracking-[0.2em] text-muted">

            {{-- Real-Time Local Time & Timezone --}}
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-accent"></span>
                <span>WAKTU LOKAL:</span>
                <span class="local-clock text-dark dark:text-offwhite font-bold">--:--:-- WIB</span>
            </div>

            {{-- Copyright Text --}}
            <div class="text-center text-[11px] sm:text-xs">
                &copy; {{ date('Y') }} {{ $profile?->name ?? 'Willy Rahmat Wijaya' }}. Hak Cipta Dilindungi.
            </div>

            {{-- Magnetic Back To Top Button --}}
            <div>
                <button
                    type="button"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="magnetic-btn inline-flex items-center gap-2.5 px-4 py-2.5 border border-light-border dark:border-dark-border text-dark dark:text-offwhite hover:border-accent hover:text-accent transition-colors cursor-pointer"
                    aria-label="Kembali ke atas halaman"
                >
                    <span>Ke Atas</span>
                    <span>↑</span>
                </button>
            </div>

        </div>

    </div>
</footer>
