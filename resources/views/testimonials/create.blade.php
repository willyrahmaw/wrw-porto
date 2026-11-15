<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth" x-data="{
    theme: localStorage.getItem('theme') || 'dark',
    rating: 5,
    hoverRating: 0
}" :class="theme === 'dark' ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kirim Testimoni &amp; Ulasan Klien — {{ $profile?->name ?? 'WRW Studio' }}</title>
    <meta name="description" content="Formulir resmi ulasan dan testimoni pelanggan untuk kerja sama pembuatan website dan sistem informasi bersama {{ $profile?->name }}.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark dark:bg-dark dark:text-offwhite font-sans antialiased selection:bg-accent selection:text-dark min-h-screen relative flex flex-col justify-between">
    <div class="pointer-events-none fixed inset-0 z-0 bg-grain opacity-20 dark:opacity-60" aria-hidden="true"></div>

    {{-- Top Sticky Navigation --}}
    <header class="sticky top-0 z-40 bg-light/90 dark:bg-dark/90 backdrop-blur-md border-b border-light-border dark:border-dark-border px-4 sm:px-6 md:px-12 py-3.5 sm:py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-muted hover:text-accent transition-colors"
            >
                <span>←</span>
                <span class="hidden sm:inline">Kembali ke Portofolio</span>
                <span class="sm:hidden">Beranda</span>
            </a>

            <div class="flex items-center gap-3">
                <span class="text-[11px] sm:text-xs font-mono uppercase tracking-widest text-accent font-semibold">
                    Formulir Ulasan Klien
                </span>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="relative z-10 max-w-4xl mx-auto w-full px-4 sm:px-6 md:px-12 py-8 sm:py-12 md:py-20 flex-grow">

        {{-- Hero Heading --}}
        <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-up">
            <span class="text-xs font-mono uppercase tracking-[0.25em] text-accent block mb-3">
                Feedback &amp; Testimoni Klien
            </span>
            <h1 class="font-display italic text-4xl sm:text-5xl md:text-6xl text-dark dark:text-offwhite leading-tight mb-4">
                Bagikan Pengalaman Kerja Sama Anda
            </h1>
            <p class="text-sm md:text-base text-muted leading-relaxed">
                Ulasan jujur Anda sangat berharga untuk perbaikan kualitas layanan saya dan membantu calon klien lain melihat gambaran hasil kerja sama.
            </p>
        </div>

        {{-- Success Flash Alert --}}
        @if (session('success'))
            <div class="mb-10 p-6 md:p-8 border border-accent bg-accent/10 text-dark dark:text-offwhite" data-aos="zoom-in">
                <div class="flex items-start gap-4">
                    <span class="text-2xl text-accent">✓</span>
                    <div>
                        <h3 class="font-display italic text-2xl text-accent mb-2">Testimoni Berhasil Dikirim!</h3>
                        <p class="text-sm font-mono text-dark/90 dark:text-offwhite/90 leading-relaxed">
                            {{ session('success') }}
                        </p>
                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 mt-4 text-xs font-mono uppercase tracking-widest text-accent hover:underline"
                        >
                            <span>Kembali ke Beranda Utama</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-10 p-6 bg-accent-orange/10 border border-accent-orange text-dark dark:text-offwhite">
                <div class="text-xs font-mono uppercase tracking-widest text-accent-orange font-bold mb-2">
                    Mohon periksa isian formulir Anda:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs font-mono text-muted">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Submission Form --}}
        <div class="border border-light-border dark:border-dark-border bg-light-surface/30 dark:bg-dark-card p-5 sm:p-8 md:p-12 shadow-2xl" data-aos="fade-up" data-aos-delay="100">
            <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data" class="space-y-8" novalidate>
                @csrf

                {{-- Anti-Spam Honeypot --}}
                <div class="hidden" aria-hidden="true">
                    <label for="website_url">Jangan isi kolom ini</label>
                    <input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off">
                </div>

                {{-- Star Rating Selector --}}
                <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark/60 text-center space-y-3">
                    <label class="block text-xs font-mono uppercase tracking-[0.2em] text-muted">
                        Penilaian Pengalaman Kerja Sama (Rating) *
                    </label>

                    <input type="hidden" name="rating" :value="rating">

                    <div class="flex items-center justify-center gap-2 text-3xl sm:text-4xl">
                        <template x-for="star in 5" :key="star">
                            <button
                                type="button"
                                @click="rating = star"
                                @mouseenter="hoverRating = star"
                                @mouseleave="hoverRating = 0"
                                class="cursor-pointer transition-transform duration-200 hover:scale-125 focus:outline-none"
                                :class="(hoverRating ? star <= hoverRating : star <= rating) ? 'text-accent' : 'text-muted/30'"
                            >
                                ★
                            </button>
                        </template>
                    </div>

                    <div class="text-xs font-mono text-accent">
                        <span x-show="rating === 5">Sangat Puas &amp; Sangat Direkomendasikan (5 / 5)</span>
                        <span x-show="rating === 4">Puas dengan Hasilnya (4 / 5)</span>
                        <span x-show="rating === 3">Cukup Memuaskan (3 / 5)</span>
                        <span x-show="rating === 2">Kurang Sesuai Harapan (2 / 5)</span>
                        <span x-show="rating === 1">Perlu Peningkatan Signifikan (1 / 5)</span>
                    </div>
                </div>

                {{-- Personal Identity Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-mono uppercase tracking-[0.18em] text-muted mb-2">
                            Nama Lengkap Anda *
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Budi Pratama"
                            class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm text-dark dark:text-offwhite focus:border-accent focus:outline-none transition-colors"
                        >
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-mono uppercase tracking-[0.18em] text-muted mb-2">
                            Jabatan / Peran Anda *
                        </label>
                        <input
                            type="text"
                            id="role"
                            name="role"
                            value="{{ old('role') }}"
                            required
                            placeholder="Contoh: Founder, Owner, Manajer Operasional, dsb."
                            class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm text-dark dark:text-offwhite focus:border-accent focus:outline-none transition-colors"
                        >
                    </div>
                </div>

                {{-- Company / Brand --}}
                <div>
                    <label for="company" class="block text-xs font-mono uppercase tracking-[0.18em] text-muted mb-2">
                        Nama Usaha / Perusahaan / Nama Proyek *
                    </label>
                    <input
                        type="text"
                        id="company"
                        name="company"
                        value="{{ old('company') }}"
                        required
                        placeholder="Contoh: Kopi Nusantara / CV Karya Maju / Personal Project"
                        class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm text-dark dark:text-offwhite focus:border-accent focus:outline-none transition-colors"
                    >
                </div>

                {{-- Testimonial Quote Textarea --}}
                <div>
                    <label for="quote" class="block text-xs font-mono uppercase tracking-[0.18em] text-accent mb-2">
                        Isi Ulasan &amp; Pengalaman Kerja Sama *
                    </label>
                    <textarea
                        id="quote"
                        name="quote"
                        rows="6"
                        required
                        placeholder="Ceritakan bagaimana proses kerja sama berlangsung: bagaimana komunikasi, ketepatan waktu, kualitas website yang dihasilkan, atau kemudahan saat Anda mengoperasikannya..."
                        class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm leading-relaxed text-dark dark:text-offwhite focus:border-accent focus:outline-none transition-colors"
                    >{{ old('quote') }}</textarea>
                    <p class="text-[11px] font-mono text-muted mt-1.5">
                        Minimal 20 karakter. Tulis secara jujur sesuai yang Anda rasakan selama proyek berlangsung.
                    </p>
                </div>

                {{-- Avatar Photo Upload (From Computer or URL) --}}
                <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark/40 space-y-4">
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-[0.18em] text-muted mb-2">
                            Foto Profil / Avatar Anda (Opsional tapi Sangat Disarankan)
                        </label>
                        <input
                            type="file"
                            name="avatar_file"
                            accept="image/*"
                            class="w-full text-xs font-mono file:mr-3 file:py-2.5 file:px-4 file:border file:border-accent file:bg-accent file:text-dark file:font-mono file:text-xs file:font-semibold file:cursor-pointer hover:file:bg-accent-hover"
                        >
                        <p class="text-[11px] font-mono text-muted mt-1.5">
                            Upload foto wajah atau logo instansi Anda (format JPG/PNG/WebP, maks 4MB) agar testimoni terlihat lebih kredibel.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-light-border dark:border-dark-border">
                        <label for="avatar_url" class="block text-[11px] font-mono uppercase text-muted mb-1.5">
                            Atau Masukkan Tautan URL Foto (Jika Ada)
                        </label>
                        <input
                            type="url"
                            id="avatar_url"
                            name="avatar_url"
                            value="{{ old('avatar_url') }}"
                            placeholder="https://..."
                            class="w-full px-3.5 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs focus:border-accent focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4 border-t border-light-border dark:border-dark-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <span class="text-[11px] font-mono text-muted">
                        Testimoni akan ditinjau terlebih dahulu sebelum dipublikasikan di portofolio.
                    </span>

                    <button
                        type="submit"
                        class="magnetic-btn px-8 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover transition-colors cursor-pointer text-center"
                    >
                        Kirim Ulasan Saya →
                    </button>
                </div>
            </form>
        </div>

    </main>

    {{-- Footer --}}
    <footer class="relative z-10 py-8 border-t border-light-border dark:border-dark-border text-center text-xs font-mono text-muted">
        <div>&copy; {{ date('Y') }} {{ $profile?->name ?? 'Willy Rahmat Wijaya' }}. All rights reserved.</div>
    </footer>

</body>
</html>

