<section id="about" class="py-20 sm:py-24 md:py-36 border-b border-light-border dark:border-dark-border" x-data="{ certModalOpen: false }" x-init="$watch('certModalOpen', val => document.documentElement.classList.toggle('overflow-hidden', val))" @keydown.escape.window="certModalOpen = false">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        <x-section-title
            number="01"
            label="Tentang Saya"
            title="Solusi pembuatan & pengembangan web yang <span class='italic font-normal text-accent'>fokus pada kebutuhan Anda.</span>"
            subtitle="Komunikasi jelas, pengerjaan terukur, dan hasil website yang mudah Anda kelola sendiri."
        />

        {{-- Asymmetric Bento Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- Portrait / Editorial Card (5 Columns) --}}
            <div class="lg:col-span-5" data-aos="fade-right">
                <div class="border border-light-border dark:border-dark-border bg-light-surface dark:bg-dark-card p-6 md:p-8">
                    <div class="relative aspect-[4/5] overflow-hidden bg-dark mb-6 group">
                        @if($profile?->photo)
                            <img
                                src="{{ asset($profile->photo) }}"
                                alt="Potret {{ $profile->name }}"
                                loading="lazy"
                                width="600"
                                height="750"
                                class="w-full h-full object-cover grayscale contrast-125 group-hover:grayscale-0 transition-all duration-700"
                            >
                        @else
                            <img
                                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=85"
                                alt="Potret {{ $profile?->name }}"
                                loading="lazy"
                                width="600"
                                height="750"
                                class="w-full h-full object-cover grayscale contrast-125 group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700 ease-out-expo"
                            >
                        @endif
                        <div class="absolute top-4 left-4 px-3 py-1 bg-dark/80 backdrop-blur-sm border border-dark-border text-[10px] font-mono uppercase tracking-[0.2em] text-accent">
                            {{ $profile?->location ?? 'Indonesia' }}
                        </div>
                    </div>

                    <div class="pt-5 border-t border-light-border dark:border-dark-border space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display italic text-2xl text-dark dark:text-offwhite">{{ $profile?->name }}</h3>
                                <p class="text-xs font-mono uppercase tracking-widest text-muted mt-0.5">{{ $profile?->profession }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-mono uppercase tracking-wider bg-light dark:bg-dark border border-light-border dark:border-dark-border text-muted">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                                Verified
                            </span>
                        </div>

                        {{-- Action Buttons: CV & Sertifikasi --}}
                        <div class="grid grid-cols-2 gap-2.5 sm:gap-3 pt-1">
                            @if($profile?->cv_path)
                                <a
                                    href="{{ $profile->cv_path }}"
                                    download
                                    class="magnetic-btn inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 py-2.5 border border-light-border dark:border-dark-border text-[11px] sm:text-xs font-mono uppercase tracking-wider hover:border-accent hover:text-accent transition-colors text-center"
                                >
                                    <span>CV / Resume</span>
                                    <span>↓</span>
                                </a>
                            @else
                                <span class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 border border-light-border/40 dark:border-dark-border/40 text-[11px] sm:text-xs font-mono uppercase tracking-wider text-muted opacity-50 text-center">
                                    <span>CV / Resume</span>
                                </span>
                            @endif

                            <button
                                type="button"
                                @click="certModalOpen = true"
                                class="magnetic-btn inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 py-2.5 bg-accent/10 border border-accent/40 text-accent text-[11px] sm:text-xs font-mono uppercase tracking-wider hover:bg-accent hover:text-dark transition-all duration-300 text-center cursor-pointer group"
                                aria-haspopup="dialog"
                                aria-label="Buka daftar sertifikasi dan kredensial"
                            >
                                <span>Sertifikasi</span>
                                <span class="text-accent group-hover:text-dark transition-colors">★</span>
                                @if(!empty($profile?->certificates) && count($profile->certificates) > 0)
                                    <span class="px-1.5 py-0.5 text-[9px] font-mono rounded bg-accent/20 group-hover:bg-dark/20 text-accent group-hover:text-dark font-bold">
                                        {{ count($profile->certificates) }}
                                    </span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personal Story, Counters & Skills Matrix (7 Columns) --}}
            <div class="lg:col-span-7 flex flex-col gap-12">

                {{-- Personal Narrative --}}
                <div class="space-y-6 text-base md:text-lg text-dark/90 dark:text-offwhite/90 leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                    @foreach(explode("\n\n", $profile?->bio ?? '') as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                {{-- Animated Achievement Numbers (IntersectionObserver) --}}
                @if(!empty($profile?->stats))
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 sm:gap-0 border-y border-light-border dark:border-dark-border py-6 sm:py-0 sm:divide-x divide-light-border dark:divide-dark-border" data-aos="fade-up" data-aos-delay="200">
                        @foreach($profile->stats as $stat)
                            <div class="sm:py-6 sm:px-6 first:sm:pl-0 last:sm:pr-0">
                                <div
                                    class="font-display italic text-4xl sm:text-5xl md:text-6xl text-accent leading-none mb-2"
                                    data-counter="{{ $stat['value'] }}"
                                    data-suffix="{{ $stat['suffix'] ?? '' }}"
                                >
                                    0{{ $stat['suffix'] ?? '' }}
                                </div>
                                <div class="text-[11px] sm:text-xs font-mono uppercase tracking-wider text-muted">
                                    {{ $stat['label'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Technical & Creative Arsenal --}}
                @if(!empty($profile?->skills))
                    <div class="space-y-8" data-aos="fade-up" data-aos-delay="300">
                        <div class="text-xs font-mono uppercase tracking-[0.22em] text-muted">
                            Teknologi & Keahlian Utama
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($profile->skills as $group => $items)
                                <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-surface/40">
                                    <h4 class="text-xs font-mono uppercase tracking-widest text-accent mb-4 pb-3 border-b border-light-border dark:border-dark-border">
                                        {{ $group }}
                                    </h4>
                                    <ul class="space-y-2.5 text-sm text-dark dark:text-offwhite">
                                        @foreach($items as $skill)
                                            <li class="flex items-center gap-2.5">
                                                <span class="w-1 h-1 rounded-full bg-accent"></span>
                                                <span>{{ $skill }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Interactive Certificate Showcase Modal (Option 1) --}}
    <template x-teleport="body">
        <div
            x-show="certModalOpen"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6 md:p-10"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cert-modal-title"
        >
            {{-- Backdrop Overlay --}}
            <div
                x-show="certModalOpen"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-dark/85 backdrop-blur-md"
                @click="certModalOpen = false"
            ></div>

            {{-- Modal Window --}}
            <div
                x-show="certModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-3xl max-h-[90vh] flex flex-col bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border shadow-2xl z-10 overflow-hidden"
                @click.stop
            >
                {{-- Top Control Bar --}}
                <div class="flex items-center justify-between px-5 sm:px-8 py-4 border-b border-light-border dark:border-dark-border bg-light/70 dark:bg-dark/70 shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                        <span class="text-xs font-mono uppercase tracking-[0.2em] text-accent">01 // VERIFIED CREDENTIALS</span>
                    </div>

                    <button
                        type="button"
                        @click="certModalOpen = false"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-light-border dark:border-dark-border hover:border-accent hover:text-accent font-mono text-xs uppercase tracking-wider transition-colors cursor-pointer text-muted"
                        aria-label="Tutup jendela sertifikat"
                    >
                        <span>✕</span>
                        <span class="hidden sm:inline">Tutup (ESC)</span>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="p-5 sm:p-8 md:p-10 overflow-y-auto space-y-6 sm:space-y-8">
                    {{-- Header Information --}}
                    <div>
                        <div class="text-[10px] sm:text-xs font-mono uppercase tracking-widest text-muted mb-2">
                            Pendidikan Berkelanjutan &amp; Standarisasi
                        </div>
                        <h3 id="cert-modal-title" class="font-display italic text-2xl sm:text-3xl md:text-4xl text-dark dark:text-offwhite leading-tight">
                            Sertifikasi &amp; Pengakuan Kompetensi
                        </h3>
                        <p class="text-xs sm:text-sm text-muted mt-2 max-w-2xl leading-relaxed">
                            Bukti kompetensi terverifikasi dalam rekayasa backend, optimasi arsitektur web, dan keamanan data yang diaudit oleh institusi teknologi resmi.
                        </p>
                    </div>

                    {{-- Certificate Cards List --}}
                    @php
                        $certs = $profile?->certificates ?? [];
                    @endphp

                    @if(!empty($certs) && count($certs) > 0)
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($certs as $cert)
                                <div class="p-5 sm:p-6 border border-light-border dark:border-dark-border bg-light/60 dark:bg-dark/40 hover:border-accent hover:bg-light dark:hover:bg-dark transition-all duration-300 group relative">
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2 sm:mb-3">
                                        <div class="flex items-center gap-2 text-xs font-mono">
                                            <span class="uppercase tracking-wider text-muted font-medium">
                                                {{ $cert['issuer'] ?? 'Penerbit Resmi' }}
                                            </span>
                                            <span class="text-muted/60">•</span>
                                            <span class="text-accent font-semibold">
                                                {{ $cert['year'] ?? date('Y') }}
                                            </span>
                                        </div>

                                        @if(!empty($cert['badge']))
                                            <span class="px-2.5 py-0.5 text-[10px] font-mono uppercase tracking-wider bg-accent/10 border border-accent/30 text-accent font-medium">
                                                {{ $cert['badge'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <h4 class="text-base sm:text-lg md:text-xl font-display font-medium text-dark dark:text-offwhite group-hover:text-accent transition-colors mb-2 sm:mb-3">
                                        {{ $cert['title'] ?? 'Sertifikasi Keahlian' }}
                                    </h4>

                                    {{-- Image Preview If Uploaded File Is An Image --}}
                                    @if(!empty($cert['file_path']) && preg_match('/\.(webp|jpg|jpeg|png|gif)$/i', $cert['file_path']))
                                        <div class="my-3 overflow-hidden border border-light-border dark:border-dark-border bg-dark/20 relative group/preview aspect-[16/9] max-h-48 sm:max-h-56">
                                            <img
                                                src="{{ asset($cert['file_path']) }}"
                                                alt="{{ $cert['title'] }}"
                                                loading="lazy"
                                                class="w-full h-full object-cover group-hover/preview:scale-105 transition-transform duration-500"
                                            >
                                            <a
                                                href="{{ asset($cert['file_path']) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="absolute inset-0 bg-dark/70 opacity-0 group-hover/preview:opacity-100 flex items-center justify-center gap-2 text-xs font-mono uppercase tracking-wider text-accent transition-opacity duration-300 backdrop-blur-[2px]"
                                            >
                                                <span>Buka Berkas Resolusi Penuh</span>
                                                <span>↗</span>
                                            </a>
                                        </div>
                                    @endif

                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-light-border/60 dark:border-dark-border/60">
                                        <div class="text-[11px] sm:text-xs font-mono text-muted">
                                            ID Kredensial: <span class="text-dark dark:text-offwhite font-semibold">{{ $cert['credential_id'] ?? '-' }}</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                            @if(!empty($cert['file_path']))
                                                <a
                                                    href="{{ asset($cert['file_path']) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-accent/10 border border-accent/40 text-accent text-xs font-mono uppercase tracking-wider hover:bg-accent hover:text-dark transition-all duration-300"
                                                >
                                                    <span>{{ preg_match('/\.pdf$/i', $cert['file_path']) ? 'Buka Dokumen PDF' : 'Buka Berkas' }}</span>
                                                    <span>↗</span>
                                                </a>
                                            @endif

                                            @if(!empty($cert['url']))
                                                <a
                                                    href="{{ $cert['url'] }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-1.5 text-xs font-mono uppercase tracking-widest text-muted hover:text-accent hover:underline transition-colors py-1"
                                                >
                                                    <span>Verifikasi Online</span>
                                                    <span>↗</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 border border-dashed border-light-border dark:border-dark-border text-center">
                            <p class="font-mono text-xs uppercase tracking-widest text-muted">Belum ada data sertifikasi yang dipublikasikan.</p>
                        </div>
                    @endif

                    {{-- Trust Footer Note --}}
                    <div class="p-4 border border-light-border dark:border-dark-border bg-light/30 dark:bg-dark/30 flex items-start gap-3">
                        <span class="text-accent text-sm mt-0.5">ⓘ</span>
                        <p class="text-[11px] sm:text-xs font-mono text-muted leading-relaxed">
                            Setiap sertifikasi di atas memiliki kredensial resmi yang dapat diverifikasi langsung melalui portal penerbit dengan nomor registrasi terkait.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </template>
</section>
