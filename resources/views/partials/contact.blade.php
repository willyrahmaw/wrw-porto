@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', $profile?->whatsapp ?? '6281234567890');
    $waText = rawurlencode("Halo {$profile?->name}, saya tertarik mendiskusikan pembuatan/pengembangan website setelah melihat portofolio Anda.");
    $waUrl = "https://wa.me/{$whatsappNumber}?text={$waText}";

    $mailSubject = rawurlencode("Diskusi Proyek Website — {$profile?->name}");
    $mailBody = rawurlencode("Halo {$profile?->name},\n\nSaya ingin berdiskusi mengenai kebutuhan website:\n- Jenis Layanan: \n- Catatan / Kebutuhan: \n\nTerima kasih.");
    $mailUrl = "mailto:" . ($profile?->email ?? 'hello@wrw.studio') . "?subject={$mailSubject}&body={$mailBody}";

    $projectTypes = [
        'Pembuatan Website Baru',
        'Penambahan Fitur Baru',
        'Maintenance & Perbaikan Error',
        'Redesign Tampilan Website',
        'Konsultasi Lainnya',
    ];
@endphp

<section id="contact" class="py-20 sm:py-24 md:py-36 border-b border-light-border dark:border-dark-border">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        <x-section-title
            number="05"
            label="Hubungi Saya"
            title="Punya kebutuhan website? <span class='italic font-normal text-accent'>Mari diskusikan.</span>"
            subtitle="Ceritakan kebutuhan website baru, penambahan fitur, atau perbaikan sistem Anda. Biasanya membalas dalam 24 jam."
        />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            {{-- Left Column: Direct Channels (5 Cols) --}}
            <div class="lg:col-span-5 flex flex-col justify-between gap-10" data-aos="fade-right">

                <div class="p-5 sm:p-8 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card space-y-8">
                    <div class="flex items-center gap-3 text-xs font-mono uppercase tracking-[0.2em] {{ $profile?->is_available ? 'text-accent' : 'text-accent-orange' }}">
                        <span class="w-2 h-2 rounded-full {{ $profile?->is_available ? 'bg-accent animate-ping' : 'bg-accent-orange' }}"></span>
                        <span>{{ $profile?->is_available ? 'Slot pengerjaan terbuka (1 Klien Fokus)' : 'Slot pengerjaan penuh (Antrean dibuka)' }}</span>
                    </div>

                    <div>
                        <h3 class="font-display italic text-3xl md:text-4xl text-dark dark:text-offwhite mb-3">
                            Kontak Langsung
                        </h3>
                        <p class="text-sm text-muted leading-relaxed">
                            Ingin respon lebih cepat? Anda bisa langsung menghubungi saya melalui WhatsApp atau email dengan format pesan yang sudah disiapkan.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <a
                            href="{{ $waUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="magnetic-btn w-full py-4 px-6 bg-accent text-dark font-mono text-xs uppercase tracking-[0.18em] font-semibold flex items-center justify-between hover:bg-accent-hover transition-colors"
                        >
                            <span>Chat via WhatsApp</span>
                            <span>↗</span>
                        </a>

                        <a
                            href="{{ $mailUrl }}"
                            class="magnetic-btn w-full py-4 px-6 border border-light-border dark:border-dark-border text-dark dark:text-offwhite font-mono text-xs uppercase tracking-[0.18em] flex items-center justify-between hover:border-accent hover:text-accent transition-colors"
                        >
                            <span>{{ $profile?->email ?? 'hello@wrw.studio' }}</span>
                            <span>✉</span>
                        </a>
                    </div>

                    {{-- Social Links Matrix --}}
                    <div class="pt-6 border-t border-light-border dark:border-dark-border">
                        <div class="text-[10px] font-mono uppercase tracking-[0.22em] text-muted mb-4">
                            Sosial Media & Jejaring
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-xs font-mono uppercase tracking-widest">
                            @if($profile?->github)
                                <a href="{{ $profile->github }}" target="_blank" rel="noopener noreferrer" class="py-2 text-muted hover:text-accent transition-colors flex items-center justify-between border-b border-light-border/60 dark:border-dark-border/60">
                                    <span>GitHub</span><span>↗</span>
                                </a>
                            @endif
                            @if($profile?->linkedin)
                                <a href="{{ $profile->linkedin }}" target="_blank" rel="noopener noreferrer" class="py-2 text-muted hover:text-accent transition-colors flex items-center justify-between border-b border-light-border/60 dark:border-dark-border/60">
                                    <span>LinkedIn</span><span>↗</span>
                                </a>
                            @endif
                            @if($profile?->instagram)
                                <a href="{{ $profile->instagram }}" target="_blank" rel="noopener noreferrer" class="py-2 text-muted hover:text-accent transition-colors flex items-center justify-between border-b border-light-border/60 dark:border-dark-border/60">
                                    <span>Instagram</span><span>↗</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Interactive AJAX Contact Form --}}
            <div
                class="lg:col-span-7"
                data-aos="fade-up"
                data-aos-delay="150"
                x-data="{
                    form: {
                        name: '',
                        email: '',
                        project_type: '{{ $projectTypes[0] }}',
                        message: '',
                        website_url: ''
                    },
                    loading: false,
                    status: null,
                    feedbackMessage: '',
                    errors: {},
                    async submitForm() {
                        this.loading = true;
                        this.status = null;
                        this.errors = {};

                        try {
                            const response = await fetch('{{ route('contact.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify(this.form)
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.status = 'success';
                                this.feedbackMessage = data.message;
                                this.form.name = '';
                                this.form.email = '';
                                this.form.message = '';
                            } else if (response.status === 422) {
                                this.status = 'error';
                                this.errors = data.errors || {};
                                this.feedbackMessage = data.message || 'Mohon periksa kembali isian formulir Anda.';
                            } else if (response.status === 429) {
                                this.status = 'error';
                                this.feedbackMessage = 'Terlalu banyak pengiriman pesan. Silakan tunggu sebentar.';
                            } else {
                                this.status = 'error';
                                this.feedbackMessage = 'Terjadi kendala teknis. Silakan hubungi langsung melalui WhatsApp.';
                            }
                        } catch (e) {
                            this.status = 'error';
                            this.feedbackMessage = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
                        } finally {
                            this.loading = false;
                        }
                    }
                }"
            >
                <form @submit.prevent="submitForm" class="p-5 sm:p-8 md:p-12 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-8" novalidate>

                    {{-- Honeypot --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website_url">Jangan isi kolom ini</label>
                        <input type="text" id="website_url" name="website_url" x-model="form.website_url" tabindex="-1" autocomplete="off">
                    </div>

                    {{-- Name & Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="contact-name" class="block text-xs font-mono uppercase tracking-[0.2em] text-muted mb-3">
                                01 / Nama Lengkap *
                            </label>
                            <input
                                id="contact-name"
                                type="text"
                                x-model="form.name"
                                required
                                placeholder="Nama Anda atau Instansi"
                                class="w-full px-4 py-3.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-dark dark:text-offwhite text-sm focus:outline-none focus:border-accent transition-colors"
                            >
                            <template x-if="errors.name">
                                <p class="mt-2 text-xs font-mono text-accent-orange" x-text="errors.name[0]"></p>
                            </template>
                        </div>

                        <div>
                            <label for="contact-email" class="block text-xs font-mono uppercase tracking-[0.2em] text-muted mb-3">
                                02 / Alamat Email *
                            </label>
                            <input
                                id="contact-email"
                                type="email"
                                x-model="form.email"
                                required
                                placeholder="nama@email.com"
                                class="w-full px-4 py-3.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-dark dark:text-offwhite text-sm focus:outline-none focus:border-accent transition-colors"
                            >
                            <template x-if="errors.email">
                                <p class="mt-2 text-xs font-mono text-accent-orange" x-text="errors.email[0]"></p>
                            </template>
                        </div>
                    </div>

                    {{-- Project Type --}}
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-[0.2em] text-muted mb-3">
                            03 / Layanan yang Dibutuhkan *
                        </label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($projectTypes as $type)
                                <button
                                    type="button"
                                    @click="form.project_type = '{{ $type }}'"
                                    :class="form.project_type === '{{ $type }}'
                                        ? 'bg-accent text-dark border-accent font-semibold'
                                        : 'border-light-border dark:border-dark-border text-muted hover:border-accent hover:text-dark dark:hover:text-offwhite'"
                                    class="px-4 py-2.5 text-xs font-mono border transition-all duration-200 cursor-pointer"
                                >
                                    {{ $type }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="contact-message" class="block text-xs font-mono uppercase tracking-[0.2em] text-muted mb-3">
                            04 / Detail Kebutuhan & Pesan *
                        </label>
                        <textarea
                            id="contact-message"
                            rows="5"
                            x-model="form.message"
                            required
                            placeholder="Ceritakan gambaran singkat kebutuhan website yang ingin dibuat, fitur baru yang ingin ditambahkan, atau kendala error yang ingin diperbaiki..."
                            class="w-full px-4 py-3.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-dark dark:text-offwhite text-sm focus:outline-none focus:border-accent transition-colors"
                        ></textarea>
                        <template x-if="errors.message">
                            <p class="mt-2 text-xs font-mono text-accent-orange" x-text="errors.message[0]"></p>
                        </template>
                    </div>

                    {{-- Alert Box --}}
                    <div
                        x-show="status !== null"
                        x-cloak
                        :class="status === 'success' ? 'border-accent bg-accent/10 text-dark dark:text-offwhite' : 'border-accent-orange bg-accent-orange/10 text-accent-orange'"
                        class="p-4 border text-xs font-mono leading-relaxed"
                        role="alert"
                    >
                        <span x-text="feedbackMessage"></span>
                    </div>

                    {{-- Submit --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                        <span class="text-[11px] font-mono text-muted">
                            Data pesan terkirim langsung ke admin & email.
                        </span>

                        <button
                            type="submit"
                            :disabled="loading"
                            class="magnetic-btn inline-flex items-center justify-center gap-3 px-8 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover disabled:opacity-50 cursor-pointer transition-all"
                        >
                            <span x-show="!loading">Kirim Pesan</span>
                            <span x-show="loading" x-cloak>Mengirim Pesan...</span>
                            <span>→</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
