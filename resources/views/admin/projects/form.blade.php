<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth" x-data="{
    theme: localStorage.getItem('theme') || 'dark',
    techStackInput: '{{ old('tech_stack_raw', is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : '') }}',
    get techTags() {
        return this.techStackInput.split(',').map(s => s.trim()).filter(Boolean);
    }
}" :class="theme === 'dark' ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $isEdit ? 'Edit Proyek: ' . $project->title : 'Tambah Proyek Baru' }} — WRW Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark dark:bg-dark dark:text-offwhite font-sans antialiased selection:bg-accent selection:text-dark min-h-screen relative">
    <div class="pointer-events-none fixed inset-0 z-0 bg-grain opacity-20 dark:opacity-60" aria-hidden="true"></div>

    {{-- Top Sticky Header --}}
    <header class="sticky top-0 z-40 bg-light/90 dark:bg-dark/90 backdrop-blur-md border-b border-light-border dark:border-dark-border px-4 sm:px-6 md:px-12 py-3 sm:py-4">
        <div class="max-w-6xl mx-auto flex flex-wrap sm:flex-nowrap items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <a
                    href="{{ route('admin.dashboard', ['tab' => 'projects']) }}"
                    class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-muted hover:text-accent transition-colors"
                >
                    <span>←</span>
                    <span class="hidden sm:inline">Kembali ke Dashboard</span>
                    <span class="sm:hidden">Dashboard</span>
                </a>
                <span class="text-muted/40 hidden sm:inline">|</span>
                <span class="text-xs font-mono uppercase tracking-widest text-accent hidden sm:inline">
                    {{ $isEdit ? 'Edit Proyek' : 'Proyek Baru' }}
                </span>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a
                    href="{{ route('admin.dashboard', ['tab' => 'projects']) }}"
                    class="px-3 sm:px-4 py-2 border border-light-border dark:border-dark-border text-xs font-mono text-muted hover:text-offwhite transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    form="projectForm"
                    class="px-4 sm:px-6 py-2 sm:py-2.5 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold hover:bg-accent-hover transition-colors cursor-pointer"
                >
                    {{ $isEdit ? 'Perbarui →' : 'Simpan →' }}
                </button>
            </div>
        </div>
    </header>

    {{-- Main Content Container --}}
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 md:px-12 py-6 sm:py-8 md:py-12">

        {{-- Page Heading --}}
        <div class="mb-8 border-b border-light-border dark:border-dark-border pb-6">
            <div class="text-[11px] font-mono uppercase tracking-[0.22em] text-accent mb-2">
                Manajemen Portofolio // Studio Admin
            </div>
            <h1 class="font-display text-3xl sm:text-4xl md:text-5xl text-dark dark:text-offwhite">
                {{ $isEdit ? 'Edit Proyek: ' . $project->title : 'Tambah Proyek Baru' }}
            </h1>
            <p class="text-sm font-mono text-muted mt-2">
                Halaman khusus formulir proyek — Anda dapat leluasa mengetik deskripsi dan mengupload foto dari komputer tanpa khawatir modal tertutup.
            </p>
        </div>

        {{-- Error Alerts --}}
        @if ($errors->any())
            <div class="mb-8 p-6 bg-accent-orange/10 border border-accent-orange text-dark dark:text-offwhite">
                <div class="text-xs font-mono uppercase tracking-widest text-accent-orange font-bold mb-2">
                    Terdapat kesalahan pengisian data:
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm font-mono text-muted">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- The Project Form --}}
        <form
            id="projectForm"
            method="POST"
            action="{{ $isEdit ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
            enctype="multipart/form-data"
            class="space-y-8"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Left Column (8 Cols): Content, Tech Stack, Description & Gallery --}}
                <div class="lg:col-span-8 space-y-8">

                    {{-- 01. Info Utama --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h2 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            01 // Informasi Utama Proyek
                        </h2>

                        <div>
                            <label class="block text-xs font-mono uppercase text-muted mb-2">
                                Judul Proyek *
                            </label>
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $project->title) }}"
                                required
                                placeholder="Contoh: Sistem Informasi Manajemen Inventaris & Kasir (POS)"
                                class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-base focus:border-accent focus:outline-none"
                            >
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Kategori Proyek *
                                </label>
                                <input
                                    type="text"
                                    name="category"
                                    value="{{ old('category', $project->category) }}"
                                    required
                                    placeholder="Contoh: Aplikasi Web / Company Profile / Toko Online"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs focus:border-accent focus:outline-none"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Tahun Pengerjaan *
                                </label>
                                <input
                                    type="text"
                                    name="year"
                                    value="{{ old('year', $project->year) }}"
                                    required
                                    placeholder="Contoh: {{ date('Y') }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs focus:border-accent focus:outline-none"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase text-muted mb-2">
                                Tautan Live Demo / Website (Opsional)
                            </label>
                            <input
                                type="url"
                                name="link"
                                value="{{ old('link', $project->link) }}"
                                placeholder="https://website-klien.com"
                                class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs focus:border-accent focus:outline-none"
                            >
                            <p class="text-[11px] font-mono text-muted mt-1">Kosongkan jika proyek bersifat internal atau tidak memiliki URL publik.</p>
                        </div>
                    </div>

                    {{-- 02. Tech Stack --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-4">
                        <div class="flex items-center justify-between border-b border-light-border dark:border-dark-border pb-3">
                            <h2 class="text-xs font-mono uppercase tracking-widest text-accent">
                                02 // Tech Stack & Teknologi yang Dipakai
                            </h2>
                            <span class="text-[11px] font-mono text-muted">Pisahkan dengan koma</span>
                        </div>

                        <div>
                            <input
                                type="text"
                                name="tech_stack_raw"
                                x-model="techStackInput"
                                placeholder="Laravel 11, Tailwind CSS, Alpine.js, MySQL, DomPDF"
                                class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs focus:border-accent focus:outline-none"
                            >
                            <p class="text-[11px] font-mono text-muted mt-1.5">
                                Masukkan teknologi atau tools yang digunakan dalam proyek ini.
                            </p>
                        </div>

                        {{-- Real-time Tags Preview --}}
                        <div class="pt-2">
                            <span class="text-[10px] font-mono uppercase tracking-widest text-muted block mb-2">
                                Preview Tag yang Muncul:
                            </span>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(tag, i) in techTags" :key="i">
                                    <span class="px-3 py-1 text-xs font-mono border border-accent/40 bg-accent/10 text-accent">
                                        <span x-text="tag"></span>
                                    </span>
                                </template>
                                <span x-show="techTags.length === 0" class="text-xs font-mono text-muted italic">
                                    (Belum ada teknologi yang dimasukkan)
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 03. Deskripsi Proyek --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-4">
                        <h2 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            03 // Deskripsi & Rincian Proyek *
                        </h2>

                        <div>
                            <textarea
                                name="description"
                                rows="8"
                                required
                                placeholder="Jelaskan mengenai proyek ini secara jelas dan profesional:&#10;- Latar belakang website atau aplikasi&#10;- Fitur-fitur utama yang dibangun&#10;- Manfaat langsung bagi pengguna atau bisnis klien..."
                                class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm leading-relaxed focus:border-accent focus:outline-none"
                            >{{ old('description', $project->description) }}</textarea>
                            <p class="text-[11px] font-mono text-muted mt-1">
                                Teks ini akan tampil di kartu utama beranda dan menjadi isi utama di halaman studi kasus proyek.
                            </p>
                        </div>
                    </div>

                    {{-- 04. Galeri Foto Proyek (> 1 Foto) --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <div class="flex items-center justify-between border-b border-light-border dark:border-dark-border pb-3">
                            <h2 class="text-xs font-mono uppercase tracking-widest text-accent">
                                04 // Galeri Dokumentasi & Screenshot Proyek
                            </h2>
                            <span class="text-[11px] font-mono text-muted">Lebih dari 1 Foto</span>
                        </div>

                        {{-- Existing Gallery Preview (If Edit) --}}
                        @if($isEdit && !empty($project->gallery) && count($project->gallery) > 0)
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-3">
                                    Foto Galeri yang Sudah Ada (Centang untuk Mempertahankan):
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach($project->gallery as $idx => $gPhoto)
                                        <label class="group relative border border-light-border dark:border-dark-border overflow-hidden bg-dark block cursor-pointer">
                                            <div class="aspect-[16/10] overflow-hidden">
                                                <img src="{{ $gPhoto }}" alt="Galeri #{{ $idx + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            </div>
                                            <div class="p-2.5 bg-light-surface dark:bg-dark-surface/90 border-t border-light-border dark:border-dark-border flex items-center justify-between text-[11px] font-mono">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" name="existing_gallery[]" value="{{ $gPhoto }}" checked class="accent-[#C6F432]">
                                                    <span class="text-dark dark:text-offwhite">Pertahankan</span>
                                                </div>
                                                <span class="text-muted">#{{ $idx + 1 }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-[11px] font-mono text-muted mt-2">
                                    Hilangkan centang pada foto yang ingin Anda hapus dari galeri proyek.
                                </p>
                            </div>
                        @endif

                        {{-- Upload from Computer --}}
                        <div class="p-5 border border-dashed border-accent/40 bg-accent/5">
                            <label class="block text-xs font-mono uppercase text-accent font-semibold mb-2">
                                📁 Upload Foto Baru dari Komputer (Bisa Pilih Banyak Foto Sekaligus)
                            </label>
                            <input
                                type="file"
                                name="gallery_files[]"
                                multiple
                                accept="image/*"
                                class="w-full text-xs font-mono file:mr-4 file:py-2.5 file:px-4 file:border file:border-accent file:bg-accent file:text-dark file:font-mono file:text-xs file:font-semibold file:cursor-pointer hover:file:bg-accent-hover"
                            >
                            <p class="text-[11px] font-mono text-muted mt-2">
                                Tahan tombol <kbd class="px-1.5 py-0.5 border border-light-border dark:border-dark-border text-[10px]">Ctrl</kbd> atau <kbd class="px-1.5 py-0.5 border border-light-border dark:border-dark-border text-[10px]">Shift</kbd> di komputermu untuk memilih beberapa screenshot sekaligus.
                            </p>
                        </div>

                        {{-- Or Via URL --}}
                        <div>
                            <label class="block text-xs font-mono uppercase text-muted mb-2">
                                Atau Tambahkan URL Gambar Tambahan (1 URL per Baris)
                            </label>
                            <textarea
                                name="gallery_lines"
                                rows="3"
                                placeholder="https://domain-anda.com/foto1.jpg&#10;https://domain-anda.com/foto2.jpg"
                                class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs leading-relaxed focus:border-accent focus:outline-none"
                            >{{ old('gallery_lines') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- Right Column (4 Cols): Thumbnail & Publication Settings --}}
                <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-24">

                    {{-- Thumbnail Utama --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-4">
                        <h2 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            Thumbnail Gambar Utama
                        </h2>

                        @if($project->thumbnail)
                            <div>
                                <span class="text-[10px] font-mono uppercase text-muted block mb-2">Gambar Saat Ini:</span>
                                <div class="aspect-[16/10] overflow-hidden border border-light-border dark:border-dark-border bg-dark mb-3">
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-mono uppercase text-accent mb-2">
                                Upload File Thumbnail dari Komputer
                            </label>
                            <input
                                type="file"
                                name="thumbnail_file"
                                accept="image/*"
                                class="w-full text-xs font-mono file:mr-3 file:py-2 file:px-3 file:border file:border-light-border dark:file:border-dark-border file:bg-light dark:file:bg-dark file:text-xs file:font-mono file:cursor-pointer"
                            >
                        </div>

                        <div class="pt-3 border-t border-light-border dark:border-dark-border">
                            <label class="block text-xs font-mono uppercase text-muted mb-2">
                                Atau Gunakan URL Thumbnail Langsung
                            </label>
                            <input
                                type="text"
                                name="thumbnail_url"
                                value="{{ old('thumbnail_url', $project->thumbnail) }}"
                                placeholder="https://..."
                                class="w-full px-3.5 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs focus:border-accent focus:outline-none"
                            >
                        </div>
                    </div>

                    {{-- Pengaturan Tampilan & Publikasi --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h2 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            Pengaturan Publikasi
                        </h2>

                        <div>
                            <label class="inline-flex items-start gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="is_featured"
                                    value="1"
                                    {{ old('is_featured', $project->is_featured ?? true) ? 'checked' : '' }}
                                    class="accent-[#C6F432] mt-0.5"
                                >
                                <div>
                                    <span class="text-sm font-medium block">Tampilkan di Beranda</span>
                                    <span class="text-[11px] font-mono text-muted block mt-0.5">
                                        Proyek ini akan dipajang di seksi pilihan beranda utama.
                                    </span>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase text-muted mb-2">
                                Urutan Tampilan (Sort Order)
                            </label>
                            <input
                                type="number"
                                name="sort_order"
                                value="{{ old('sort_order', $project->sort_order ?? 1) }}"
                                class="w-full px-3.5 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm focus:border-accent focus:outline-none"
                            >
                            <p class="text-[11px] font-mono text-muted mt-1">Angka lebih kecil tampil lebih dulu (misal: 1, 2, 3).</p>
                        </div>

                        <div class="pt-4 border-t border-light-border dark:border-dark-border">
                            <button
                                type="submit"
                                class="w-full py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover transition-colors cursor-pointer text-center"
                            >
                                {{ $isEdit ? 'Simpan Perubahan Proyek →' : 'Simpan Proyek Baru →' }}
                            </button>

                            <a
                                href="{{ route('admin.dashboard', ['tab' => 'projects']) }}"
                                class="w-full block py-3 mt-3 text-center border border-light-border dark:border-dark-border text-xs font-mono text-muted hover:text-offwhite transition-colors"
                            >
                                Kembali tanpa menyimpan
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </main>

</body>
</html>

