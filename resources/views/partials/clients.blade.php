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

    {{-- 3 Testimonials Grid --}}
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 border border-light-border dark:border-dark-border divide-y lg:divide-y-0 lg:divide-x divide-light-border dark:divide-dark-border">
            @foreach($testimonials as $index => $testimonial)
                <blockquote
                    class="p-8 md:p-10 flex flex-col justify-between bg-light-surface/20 dark:bg-dark-card/50 hover:bg-light-surface/60 dark:hover:bg-dark-surface transition-colors duration-300"
                    data-aos="fade-up"
                    data-aos-delay="{{ $index * 100 }}"
                >
                    <div>
                        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-accent mb-8">
                            <span>TESTIMONI KLIEN // 0{{ $index + 1 }}</span>
                            <span class="tracking-widest">
                                {{ str_repeat('★', $testimonial->rating ?? 5) }}{{ str_repeat('☆', 5 - ($testimonial->rating ?? 5)) }}
                            </span>
                        </div>

                        <p class="font-display text-2xl md:text-[28px] leading-snug text-dark dark:text-offwhite mb-10">
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
                                {{ $testimonial->role }}, <span class="text-accent">{{ $testimonial->company }}</span>
                            </div>
                        </div>
                    </footer>
                </blockquote>
            @endforeach
        </div>

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
