<footer class="border-t border-light-border dark:border-dark-border bg-light dark:bg-dark py-12 sm:py-16 transition-colors duration-300">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        {{-- Main Row: Identity & Navigation --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 pb-8 sm:pb-10 border-b border-light-border dark:border-dark-border">

            {{-- Brand Identity --}}
            <div class="space-y-1.5">
                <a
                    href="{{ route('home') }}"
                    class="font-display italic text-3xl sm:text-4xl text-dark dark:text-offwhite hover:text-accent transition-colors block leading-none"
                >
                    {{ $profile?->initials ?? 'WRW.' }}
                </a>
                <p class="text-xs font-mono text-muted">
                    {{ $profile?->profession ?? 'Full-Stack Web Developer' }} &bull; {{ $profile?->location ?? 'Indonesia' }}
                </p>
            </div>

            {{-- Nav Links & Socials --}}
            <div class="flex flex-wrap items-center gap-5 sm:gap-7 text-xs font-mono uppercase tracking-wider text-muted">
                <a href="{{ route('home') }}#about" class="hover:text-accent transition-colors">Tentang</a>
                <a href="{{ route('home') }}#services" class="hover:text-accent transition-colors">Layanan</a>
                <a href="{{ route('projects.index') }}" class="hover:text-accent transition-colors">Proyek</a>
                @if($hasClients ?? false)
                    <a href="{{ route('home') }}#clients" class="hover:text-accent transition-colors">Klien</a>
                @endif
                <a href="{{ route('home') }}#contact" class="hover:text-accent transition-colors">Kontak</a>

                <span class="text-light-border dark:text-dark-border hidden sm:inline">|</span>

                @if($profile?->github)
                    <a href="{{ $profile->github }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center gap-1 group">
                        <span>GitHub</span>
                        <span class="text-[10px] group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform">↗</span>
                    </a>
                @endif
                @if($profile?->linkedin)
                    <a href="{{ $profile->linkedin }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center gap-1 group">
                        <span>LinkedIn</span>
                        <span class="text-[10px] group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform">↗</span>
                    </a>
                @endif
                @if($profile?->instagram)
                    <a href="{{ $profile->instagram }}" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors flex items-center gap-1 group">
                        <span>Instagram</span>
                        <span class="text-[10px] group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform">↗</span>
                    </a>
                @endif
                <a href="{{ route('testimonials.create') }}" class="text-accent hover:underline flex items-center gap-1">
                    <span>Ulasan</span>
                    <span class="text-[11px]">★</span>
                </a>
            </div>

        </div>

        {{-- Bottom Utility Bar: Copyright, Real-time Clock, Back To Top --}}
        <div class="pt-6 sm:pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-mono text-muted">

            {{-- Copyright --}}
            <div class="text-[11px] sm:text-xs text-center sm:text-left">
                &copy; {{ date('Y') }} {{ $profile?->name ?? 'Willy Rahmat Wijaya' }}. Hak cipta dilindungi.
            </div>

            {{-- Real-Time Clock --}}
            <div class="flex items-center gap-2 text-[11px]">
                <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                <span class="uppercase tracking-wider">Waktu Lokal:</span>
                <span class="local-clock font-bold text-dark dark:text-offwhite">--:--:-- WIB</span>
            </div>

            {{-- Back to Top Button --}}
            <button
                type="button"
                onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="magnetic-btn inline-flex items-center gap-2 hover:text-accent text-[11px] uppercase tracking-widest transition-colors cursor-pointer group"
                aria-label="Kembali ke atas"
            >
                <span>Ke Atas</span>
                <span class="group-hover:-translate-y-1 transition-transform">&uarr;</span>
            </button>

        </div>

    </div>
</footer>
