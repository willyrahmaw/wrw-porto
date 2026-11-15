@if(isset($testimonials) && $testimonials->count() > 0)
<section id="clients" class="py-20 sm:py-24 md:py-36 border-b border-light-border dark:border-dark-border overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        <x-section-title
            number="04"
            label="Klien & Testimoni"
            title="Kolaborasi &amp; <span class='italic font-normal text-accent'>Klien Terpercaya.</span>"
            subtitle="Bekerja sama secara dekat dengan pemilik bisnis maupun klien perorangan dengan sistem 1 klien 1 fokus dan dedikasi penuh."
        />

    </div>

    {{-- Infinite CSS Marquee of Client Logos / Wordmarks --}}
    <div class="relative border-y border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-surface/40 py-8 mb-24" data-aos="fade-up">
        <div class="flex whitespace-nowrap overflow-hidden">
            <div class="marquee-track flex items-center gap-16 md:gap-24 animate-marquee pr-16 md:pr-24">
                @for($loopCount = 0; $loopCount < 2; $loopCount++)
                    @foreach($clients as $client)
                        <div class="inline-flex items-center gap-4 group">
                            <span class="w-2 h-2 rounded-full bg-accent/60 group-hover:bg-accent transition-colors"></span>
                            <span class="font-display italic text-3xl md:text-5xl text-muted group-hover:text-dark dark:group-hover:text-offwhite transition-colors tracking-tight">
                                {{ $client->name }}
                            </span>
                            @if($client->industry)
                                <span class="text-[10px] font-mono uppercase tracking-[0.2em] text-muted border border-light-border dark:border-dark-border px-2 py-0.5">
                                    {{ $client->industry }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    </div>

    {{-- Testimonials Carousel / Slider --}}
    <div
        class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12"
        x-data="{
            currentIndex: 0,
            total: {{ $testimonials->count() }},
            isPaused: false,
            autoplayTimer: null,
            init() {
                this.updateScrollState();
                window.addEventListener('resize', () => this.updateScrollState());
                this.startAutoplay();
            },
            startAutoplay() {
                if (this.total <= 1) return;
                this.autoplayTimer = setInterval(() => {
                    if (!this.isPaused) {
                        this.next();
                    }
                }, 6500);
            },
            updateScrollState() {
                const el = this.$refs.slider;
                if (!el) return;
                const card = el.firstElementChild;
                const cardWidth = card ? card.offsetWidth : 1;
                this.currentIndex = Math.min(this.total - 1, Math.max(0, Math.round(el.scrollLeft / cardWidth)));
            },
            next() {
                const el = this.$refs.slider;
                if (!el) return;
                const card = el.firstElementChild;
                if (!card) return;
                const cardWidth = card.offsetWidth;
                const maxScroll = el.scrollWidth - el.clientWidth;
                if (el.scrollLeft >= maxScroll - 15) {
                    el.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    el.scrollBy({ left: cardWidth, behavior: 'smooth' });
                }
            },
            prev() {
                const el = this.$refs.slider;
                if (!el) return;
                const card = el.firstElementChild;
                if (!card) return;
                const cardWidth = card.offsetWidth;
                if (el.scrollLeft <= 15) {
                    const maxScroll = el.scrollWidth - el.clientWidth;
                    el.scrollTo({ left: maxScroll, behavior: 'smooth' });
                } else {
                    el.scrollBy({ left: -cardWidth, behavior: 'smooth' });
                }
            },
            scrollToIndex(idx) {
                const el = this.$refs.slider;
                if (!el) return;
                const card = el.firstElementChild;
                if (!card) return;
                el.scrollTo({ left: idx * card.offsetWidth, behavior: 'smooth' });
            }
        }"
        @keydown.left.window="prev()"
        @keydown.right.window="next()"
    >
        @if($testimonials->count() > 0)
            {{-- Slider Top Control Bar --}}
            <div class="flex items-center justify-between pb-4 sm:pb-6" data-aos="fade-up">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    <span class="text-xs font-mono uppercase tracking-[0.2em] text-accent">
                        ULASAN KLIEN //
                        <span x-text="String(currentIndex + 1).padStart(2, '0')">01</span>
                        <span class="text-muted">/ {{ sprintf('%02d', $testimonials->count()) }}</span>
                    </span>
                </div>

                {{-- Previous / Next Controls --}}
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="prev()"
                        class="magnetic-btn w-9 h-9 sm:w-10 sm:h-10 border border-light-border dark:border-dark-border flex items-center justify-center text-dark dark:text-offwhite hover:border-accent hover:text-accent transition-colors cursor-pointer"
                        aria-label="Testimoni sebelumnya"
                    >
                        <span class="text-sm font-mono font-bold">←</span>
                    </button>
                    <button
                        type="button"
                        @click="next()"
                        class="magnetic-btn w-9 h-9 sm:w-10 sm:h-10 border border-light-border dark:border-dark-border flex items-center justify-center text-dark dark:text-offwhite hover:border-accent hover:text-accent transition-colors cursor-pointer"
                        aria-label="Testimoni selanjutnya"
                    >
                        <span class="text-sm font-mono font-bold">→</span>
                    </button>
                </div>
            </div>

            {{-- Slider Viewport / Track --}}
            <div
                class="border border-light-border dark:border-dark-border bg-light-surface/10 dark:bg-dark-card/20"
                @mouseenter="isPaused = true"
                @mouseleave="isPaused = false"
                data-aos="fade-up"
                data-aos-delay="100"
            >
                <div
                    x-ref="slider"
                    @scroll.debounce.40ms="updateScrollState()"
                    class="flex overflow-x-auto no-scrollbar scroll-smooth snap-x snap-mandatory divide-x divide-light-border dark:divide-dark-border"
                >
                    @foreach($testimonials as $index => $testimonial)
                        <blockquote
                            class="w-full md:w-1/2 lg:w-1/3 shrink-0 snap-start p-7 sm:p-9 md:p-10 flex flex-col justify-between bg-light-surface/20 dark:bg-dark-card/50 hover:bg-light-surface/60 dark:hover:bg-dark-surface transition-colors duration-300 select-none"
                        >
                            <div>
                                <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-accent mb-6 sm:mb-8">
                                    <span>ULASAN // {{ sprintf('%02d', $index + 1) }}</span>
                                    <span class="tracking-widest text-accent">
                                        {{ str_repeat('★', $testimonial->rating ?? 5) }}{{ str_repeat('☆', 5 - ($testimonial->rating ?? 5)) }}
                                    </span>
                                </div>

                                <p class="font-display italic text-xl sm:text-2xl md:text-[26px] leading-snug text-dark dark:text-offwhite mb-8 sm:mb-10">
                                    “{{ $testimonial->quote }}”
                                </p>
                            </div>

                            <footer class="flex items-center gap-4 pt-6 border-t border-light-border dark:border-dark-border">
                                @if($testimonial->avatar)
                                    <img
                                        src="{{ asset($testimonial->avatar) }}"
                                        alt="{{ $testimonial->name }}"
                                        loading="lazy"
                                        width="48"
                                        height="48"
                                        class="w-12 h-12 rounded-full object-cover grayscale contrast-125 shrink-0"
                                    >
                                @else
                                    <div class="w-12 h-12 rounded-full bg-accent text-dark flex items-center justify-center font-mono font-bold text-sm shrink-0">
                                        {{ substr($testimonial->name, 0, 2) }}
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <div class="font-medium text-sm text-dark dark:text-offwhite truncate">
                                        {{ $testimonial->name }}
                                    </div>
                                    <div class="text-xs font-mono text-muted truncate">
                                        {{ $testimonial->role }}@if($testimonial->company), <span class="text-accent">{{ $testimonial->company }}</span>@endif
                                    </div>
                                </div>
                            </footer>
                        </blockquote>
                    @endforeach
                </div>
            </div>

            {{-- Slider Indicators & Navigation Hint --}}
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @foreach($testimonials as $index => $testimonial)
                        <button
                            type="button"
                            @click="scrollToIndex({{ $index }})"
                            class="h-1.5 transition-all duration-300 cursor-pointer"
                            :class="currentIndex === {{ $index }} ? 'w-8 bg-accent' : 'w-2 bg-light-border dark:border-dark-border bg-muted/40 hover:bg-muted'"
                            aria-label="Pindah ke ulasan {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>

                <span class="text-[11px] font-mono uppercase tracking-wider text-muted hidden sm:inline">
                    Gunakan panah ← → atau geser layar untuk berpindah
                </span>
            </div>
        @endif

        {{-- Public Testimonial Invite Callout --}}
        <div class="mt-8 pt-6 border-t border-light-border dark:border-dark-border flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-mono" data-aos="fade-up">
            <span class="text-muted">
                Pernah berkolaborasi atau memesan pembuatan website bersama saya?
            </span>
            <a
                href="{{ route('testimonials.create') }}"
                class="inline-flex items-center gap-2 text-accent hover:underline uppercase tracking-wider font-semibold"
            >
                <span>Bagikan Ulasan / Testimoni Anda</span>
                <span>↗</span>
            </a>
        </div>
    </div>
</section>
@endif
