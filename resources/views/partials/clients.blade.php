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
            perView: 3,
            touchStartX: 0,
            touchEndX: 0,
            isPaused: false,
            init() {
                this.updatePerView();
                window.addEventListener('resize', () => this.updatePerView());
                setInterval(() => {
                    if (!this.isPaused && this.canSlide) {
                        this.next();
                    }
                }, 6500);
            },
            updatePerView() {
                if (window.innerWidth < 768) {
                    this.perView = 1;
                } else if (window.innerWidth < 1024) {
                    this.perView = 2;
                } else {
                    this.perView = 3;
                }
                if (this.currentIndex > this.maxIndex) {
                    this.currentIndex = this.maxIndex;
                }
            },
            get maxIndex() {
                return Math.max(0, this.total - this.perView);
            },
            get canSlide() {
                return this.total > this.perView;
            },
            next() {
                if (!this.canSlide) return;
                this.currentIndex = (this.currentIndex >= this.maxIndex) ? 0 : this.currentIndex + 1;
            },
            prev() {
                if (!this.canSlide) return;
                this.currentIndex = (this.currentIndex <= 0) ? this.maxIndex : this.currentIndex - 1;
            },
            goTo(idx) {
                this.currentIndex = Math.min(Math.max(0, idx), this.maxIndex);
            },
            handleTouchStart(e) {
                this.touchStartX = e.touches[0].clientX;
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].clientX;
                const diff = this.touchStartX - this.touchEndX;
                if (Math.abs(diff) > 35) {
                    if (diff > 0) this.next();
                    else this.prev();
                }
            }
        }"
        @keydown.left.window="prev()"
        @keydown.right.window="next()"
        @touchstart.passive="handleTouchStart($event)"
        @touchend.passive="handleTouchEnd($event)"
    >
        @if($testimonials->count() > 0)
            {{-- Slider Top Control Bar --}}
            <div class="flex items-center justify-between pb-4 sm:pb-6" data-aos="fade-up">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    <span class="text-xs font-mono uppercase tracking-[0.2em] text-accent">
                        ULASAN KLIEN //
                        <template x-if="perView > 1">
                            <span>
                                <span x-text="String(currentIndex + 1).padStart(2, '0')">01</span>
                                <span>—</span>
                                <span x-text="String(Math.min(currentIndex + perView, total)).padStart(2, '0')">03</span>
                            </span>
                        </template>
                        <template x-if="perView === 1">
                            <span x-text="String(currentIndex + 1).padStart(2, '0')">01</span>
                        </template>
                        <span class="text-muted">/ {{ sprintf('%02d', $testimonials->count()) }}</span>
                    </span>
                </div>

                {{-- Previous / Next Controls --}}
                <div class="flex items-center gap-2" x-show="canSlide" x-cloak>
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
                class="overflow-hidden border border-light-border dark:border-dark-border"
                @mouseenter="isPaused = true"
                @mouseleave="isPaused = false"
                data-aos="fade-up"
                data-aos-delay="100"
            >
                <div
                    class="flex transition-transform duration-500 ease-out-expo divide-x divide-light-border dark:divide-dark-border"
                    :style="'transform: translateX(-' + (currentIndex * (100 / total)) + '%)'"
                >
                    @foreach($testimonials as $index => $testimonial)
                        <blockquote
                            class="w-full md:w-1/2 lg:w-1/3 shrink-0 p-8 sm:p-9 md:p-10 flex flex-col justify-between bg-light-surface/20 dark:bg-dark-card/50 hover:bg-light-surface/60 dark:hover:bg-dark-surface transition-colors duration-300 select-none"
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
                                        class="w-12 h-12 rounded-full object-cover grayscale contrast-125"
                                    >
                                @else
                                    <div class="w-12 h-12 rounded-full bg-accent text-dark flex items-center justify-center font-mono font-bold text-sm">
                                        {{ substr($testimonial->name, 0, 2) }}
                                    </div>
                                @endif

                                <div>
                                    <div class="font-medium text-sm text-dark dark:text-offwhite">
                                        {{ $testimonial->name }}
                                    </div>
                                    <div class="text-xs font-mono text-muted">
                                        {{ $testimonial->role }}@if($testimonial->company), <span class="text-accent">{{ $testimonial->company }}</span>@endif
                                    </div>
                                </div>
                            </footer>
                        </blockquote>
                    @endforeach
                </div>
            </div>

            {{-- Slider Indicators & Navigation Hint --}}
            <div class="mt-4 flex items-center justify-between" x-show="canSlide" x-cloak>
                <div class="flex items-center gap-2">
                    <template x-for="i in (maxIndex + 1)" :key="i">
                        <button
                            type="button"
                            @click="goTo(i - 1)"
                            class="h-1.5 transition-all duration-300 cursor-pointer"
                            :class="currentIndex === (i - 1) ? 'w-8 bg-accent' : 'w-2 bg-light-border dark:border-dark-border bg-muted/40 hover:bg-muted'"
                            :aria-label="'Pindah ke slide ' + i"
                        ></button>
                    </template>
                </div>

                <span class="text-[11px] font-mono uppercase tracking-wider text-muted hidden sm:inline">
                    Gunakan panah ← → atau geser layar untuk berpindah
                </span>
            </div>
        @else
            <div class="p-8 border border-dashed border-light-border dark:border-dark-border text-center">
                <p class="font-mono text-xs uppercase tracking-widest text-muted">Belum ada testimoni yang dipublikasikan.</p>
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
