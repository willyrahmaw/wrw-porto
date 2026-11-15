@php
    $workflowSteps = [
        [
            'step' => '01',
            'phase' => 'Diskusi & Rencana',
            'title' => 'Diskusi & Analisis Kebutuhan',
            'desc' => 'Membahas fitur apa saja yang Anda butuhkan, alur sistem, serta kesepakatan timeline pengerjaan yang jelas dari awal.',
        ],
        [
            'step' => '02',
            'phase' => 'Pengerjaan',
            'title' => 'Pengembangan & Koding',
            'desc' => 'Website atau fitur langsung dibangun dengan fokus penuh (1 proyek dalam 1 waktu) dengan progres yang transparan.',
        ],
        [
            'step' => '03',
            'phase' => 'Rilis & Support',
            'title' => 'Peluncuran & Pendampingan',
            'desc' => 'Pengujian tuntas, deployment ke hosting/server, serta panduan pemakaian dan bantuan teknis saat Anda bingung.',
        ],
    ];
@endphp

<section id="services" class="py-20 sm:py-24 md:py-36 border-b border-light-border dark:border-dark-border">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        <x-section-title
            number="02"
            label="Layanan Saya"
            title="Apa saja yang bisa <span class='italic font-normal text-accent'>saya bantu?</span>"
            subtitle="Mulai dari pembuatan website baru, penambahan fitur pada sistem yang sudah berjalan, hingga perawatan rutin."
        />

        {{-- Interactive Accordion Services List --}}
        <div
            x-data="{ activeService: 0 }"
            class="border-t border-light-border dark:border-dark-border divide-y divide-light-border dark:divide-dark-border mb-24"
        >
            @foreach($services as $index => $service)
                <div
                    class="transition-colors duration-300"
                    :class="activeService === {{ $index }} ? 'bg-light-surface/60 dark:bg-dark-surface/60' : ''"
                    data-aos="fade-up"
                    data-aos-delay="{{ $index * 100 }}"
                >
                    <button
                        type="button"
                        @click="activeService = (activeService === {{ $index }} ? null : {{ $index }}); setTimeout(() => window.AOS && window.AOS.refresh(), 350)"
                        class="w-full py-6 sm:py-8 md:py-10 px-2 sm:px-4 md:px-8 flex items-center justify-between gap-4 sm:gap-6 text-left group cursor-pointer"
                        :aria-expanded="activeService === {{ $index }}"
                    >
                        <div class="flex items-baseline gap-3 sm:gap-6 md:gap-12">
                            <span class="font-mono text-xs md:text-sm text-accent tracking-widest shrink-0">
                                / {{ $service->number }}
                            </span>
                            <h3 class="font-display text-2xl sm:text-4xl md:text-5xl text-dark dark:text-offwhite group-hover:text-accent transition-colors">
                                {{ $service->title }}
                            </h3>
                        </div>

                        <span
                            class="w-10 h-10 shrink-0 rounded-full border border-light-border dark:border-dark-border flex items-center justify-center font-mono text-lg transition-all duration-300"
                            :class="activeService === {{ $index }} ? 'bg-accent text-dark border-accent rotate-45' : 'text-muted group-hover:border-accent group-hover:text-accent'"
                        >
                            +
                        </span>
                    </button>

                    <div
                        x-show="activeService === {{ $index }}"
                        x-cloak
                        x-transition:enter="transition ease-out-expo duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="px-4 md:px-8 pb-10 pt-2 md:pl-24 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
                    >
                        <p class="lg:col-span-7 text-base md:text-lg text-muted leading-relaxed">
                            {{ $service->description }}
                        </p>

                        <div class="lg:col-span-5">
                            <div class="text-[11px] font-mono uppercase tracking-[0.2em] text-accent mb-3">
                                Cakupan Pengerjaan
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($service->deliverables ?? [] as $deliverable)
                                    <span class="px-3.5 py-1.5 text-xs font-mono border border-light-border dark:border-dark-border bg-light dark:bg-dark text-dark dark:text-offwhite">
                                        {{ $deliverable }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Exclusive 1-Client 1-Focus System Module --}}
        <div class="mb-24 p-8 md:p-12 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card" data-aos="fade-up">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-8 border-b border-light-border dark:border-dark-border mb-8">
                <div>
                    <div class="inline-flex items-center gap-2.5 px-3 py-1 bg-accent/15 border border-accent/40 text-accent text-xs font-mono uppercase tracking-[0.2em] mb-4">
                        <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                        <span>Sistem Kerja Eksklusif</span>
                    </div>
                    <h3 class="font-display italic text-3xl sm:text-4xl md:text-5xl text-dark dark:text-offwhite">
                        1 Klien — 1 Fokus Penuh
                    </h3>
                </div>
                <p class="max-w-md text-sm md:text-base text-muted leading-relaxed">
                    Saya sengaja membatasi kuota proyek. Tidak ada sistem borongan banyak klien secara paralel, sehingga energi dan perhatian saya 100% tercurah untuk menyelesaikan proyek Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 border border-light-border dark:border-dark-border bg-light/50 dark:bg-dark/50">
                    <span class="font-mono text-xs text-accent font-semibold block mb-2">01 // TANPA DISTRAKSI</span>
                    <h4 class="font-display font-bold text-xl text-dark dark:text-offwhite mb-2">Perhatian 100% Utuh</h4>
                    <p class="text-sm text-muted leading-relaxed">
                        Fokus saya tidak terpecah ke deadline proyek orang lain. Kodingan dan arsitektur web Anda dikerjakan dengan ketelitian maksimal.
                    </p>
                </div>

                <div class="p-6 border border-light-border dark:border-dark-border bg-light/50 dark:bg-dark/50">
                    <span class="font-mono text-xs text-accent font-semibold block mb-2">02 // KOMUNIKASI CEPAT</span>
                    <h4 class="font-display font-bold text-xl text-dark dark:text-offwhite mb-2">Respon Langsung &amp; Jelas</h4>
                    <p class="text-sm text-muted leading-relaxed">
                        Anda tidak perlu menunggu balasan berhari-hari. Komunikasi berlangsung cepat via WhatsApp langsung dengan developer yang mengerjakan kodenya.
                    </p>
                </div>

                <div class="p-6 border border-light-border dark:border-dark-border bg-light/50 dark:bg-dark/50">
                    <span class="font-mono text-xs text-accent font-semibold block mb-2">03 // TUNTAS BERGARANSI</span>
                    <h4 class="font-display font-bold text-xl text-dark dark:text-offwhite mb-2">Didampingi Hingga Lancar</h4>
                    <p class="text-sm text-muted leading-relaxed">
                        Setelah website selesai, Anda tidak ditinggal sendiri. Saya tetap mendampingi, memberikan panduan cara pakai, dan siap membantu bila ada kendala.
                    </p>
                </div>
            </div>
        </div>

        {{-- 3-Step How I Work Block --}}
        <div class="pt-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12" data-aos="fade-up">
                <div>
                    <span class="text-xs font-mono uppercase tracking-[0.22em] text-accent block mb-2">
                        Alur Pengerjaan
                    </span>
                    <h3 class="font-display italic text-3xl md:text-5xl text-dark dark:text-offwhite">
                        Cara Kerja Saya — 3 Langkah Jelas
                    </h3>
                </div>
                <p class="text-xs font-mono uppercase tracking-widest text-muted">
                    DISKUSI → BUILD → RILIS &amp; PENDAMPINGAN
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 border border-light-border dark:border-dark-border divide-y md:divide-y-0 md:divide-x divide-light-border dark:divide-dark-border">
                @foreach($workflowSteps as $idx => $item)
                    <div
                        class="p-8 flex flex-col justify-between bg-light-surface/20 dark:bg-dark-card/40 hover:bg-light-surface dark:hover:bg-dark-surface transition-colors duration-300 group"
                        data-aos="fade-up"
                        data-aos-delay="{{ $idx * 100 }}"
                    >
                        <div>
                            <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-muted mb-8">
                                <span class="text-accent font-semibold">STEP // {{ $item['step'] }}</span>
                                <span class="px-2.5 py-0.5 border border-light-border dark:border-dark-border text-[10px]">
                                    {{ $item['phase'] }}
                                </span>
                            </div>

                            <h4 class="font-display text-2xl md:text-3xl text-dark dark:text-offwhite mb-3 group-hover:text-accent transition-colors">
                                {{ $item['title'] }}
                            </h4>

                            <p class="text-sm text-muted leading-relaxed">
                                {{ $item['desc'] }}
                            </p>
                        </div>

                        <div class="mt-8 pt-4 border-t border-light-border/60 dark:border-dark-border/60 flex items-center justify-between text-[11px] font-mono text-muted">
                            <span>Tahap Terukur</span>
                            <span class="text-accent group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Long-term Support & Commitment Banner --}}
            <div class="mt-12 p-8 md:p-10 border border-accent/40 bg-accent/5 flex flex-col md:flex-row md:items-center justify-between gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <span class="text-xs font-mono uppercase tracking-[0.2em] text-accent block mb-2">
                        Komitmen &amp; Dukungan Purna Jual
                    </span>
                    <h4 class="font-display italic text-2xl md:text-3xl text-dark dark:text-offwhite leading-snug">
                        “Bukan sekadar membangun lalu pergi — saya tetap siap mendampingi setiap kali Anda butuh bantuan.”
                    </h4>
                </div>
                <div class="text-xs font-mono text-muted shrink-0 md:text-right">
                    <span class="text-dark dark:text-offwhite font-semibold block mb-1">Dukungan Berkelanjutan</span>
                    <span>Panduan Pengoperasian &amp; Garansi Pemeliharaan</span>
                </div>
            </div>
        </div>

    </div>
</section>
