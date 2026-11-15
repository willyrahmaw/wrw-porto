<!DOCTYPE html>
<html lang="id" class="dark" x-data="themeHandler">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Studio Command Center — {{ $profile?->name ?? 'Admin CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    x-data="{
        activeTab: '{{ request('tab', 'overview') }}',
        mobileMenuOpen: false
    }"
    class="bg-light text-dark dark:bg-dark dark:text-offwhite font-sans antialiased selection:bg-accent selection:text-dark min-h-screen flex flex-col lg:flex-row relative"
>
    <div class="pointer-events-none fixed inset-0 z-0 bg-grain opacity-20 dark:opacity-60" aria-hidden="true"></div>

    {{-- ===================================================================== --}}
    {{-- LEFT SIDEBAR NAVIGATION (Editorial Command Rail)                     --}}
    {{-- ===================================================================== --}}
    <aside class="relative z-30 w-full lg:w-72 shrink-0 border-b lg:border-b-0 lg:border-r border-light-border dark:border-dark-border bg-light-surface/90 dark:bg-dark-surface/95 backdrop-blur-md flex flex-col justify-between">
        <div>
            {{-- Brand Header (Sticky responsive bar in mobile with Hamburger toggle) --}}
            <div class="p-4 sm:p-6 border-b border-light-border dark:border-dark-border flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="font-display italic text-2xl text-dark dark:text-offwhite">
                        {{ $profile?->initials ?? 'WRW.' }} <span class="font-mono not-italic text-[10px] uppercase tracking-[0.22em] text-accent ml-1">CMS</span>
                    </a>
                    <span class="hidden sm:inline-block px-2 py-0.5 bg-accent/15 border border-accent/40 text-accent text-[10px] font-mono uppercase tracking-widest">
                        ONLINE
                    </span>
                </div>

                {{-- Mobile Navigation Trigger --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <span class="px-2.5 py-1 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-[10px] font-mono text-accent uppercase">
                        <span x-text="activeTab"></span>
                    </span>

                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="px-3 py-1.5 border border-light-border dark:border-dark-border text-xs font-mono uppercase tracking-wider flex items-center gap-1.5 bg-light dark:bg-dark hover:border-accent cursor-pointer"
                        :aria-expanded="mobileMenuOpen"
                    >
                        <span x-text="mobileMenuOpen ? '✕ Tutup' : '☰ Menu'"></span>
                    </button>
                </div>
            </div>

            {{-- Navigation Menu (Collapsible in mobile, persistent rail in desktop) --}}
            <div :class="mobileMenuOpen ? 'block' : 'hidden lg:block'" class="transition-all duration-300">
                <nav class="p-4 space-y-1.5">
                    @php
                        $tabs = [
                            ['id' => 'overview', 'num' => '00', 'label' => 'Command Overview', 'badge' => null],
                            ['id' => 'inbox', 'num' => '01', 'label' => 'Inquiries Inbox', 'badge' => $unreadCount > 0 ? $unreadCount : null],
                            ['id' => 'projects', 'num' => '02', 'label' => 'Projects & Case Studies', 'badge' => $projects->count()],
                            ['id' => 'services', 'num' => '03', 'label' => 'Services & Capabilities', 'badge' => $services->count()],
                            ['id' => 'clients', 'num' => '04', 'label' => 'Clients & Testimonials', 'badge' => $clients->count()],
                            ['id' => 'profile', 'num' => '05', 'label' => 'Profile, Bio & CV', 'badge' => null],
                            ['id' => 'seo', 'num' => '06', 'label' => 'SEO & Meta Config', 'badge' => null],
                            ['id' => 'security', 'num' => '07', 'label' => 'Admin Security', 'badge' => null],
                            ['id' => 'firewall', 'num' => '08', 'label' => 'Firewall & IP Blacklist', 'badge' => $bannedIps->filter->isActive()->count() ?: null],
                        ];
                    @endphp

                    @foreach($tabs as $tab)
                        <button
                            type="button"
                            @click="activeTab = '{{ $tab['id'] }}'; mobileMenuOpen = false"
                            :class="activeTab === '{{ $tab['id'] }}'
                                ? 'bg-accent text-dark font-semibold border-accent'
                                : 'text-muted hover:text-dark dark:hover:text-offwhite border-transparent hover:bg-light dark:hover:bg-dark-card'"
                            class="w-full px-4 py-3.5 border text-left flex items-center justify-between text-xs font-mono uppercase tracking-[0.15em] transition-all cursor-pointer"
                        >
                            <div class="flex items-center gap-3">
                                <span :class="activeTab === '{{ $tab['id'] }}' ? 'text-dark' : 'text-accent'" class="text-[10px]">
                                    {{ $tab['num'] }}
                                </span>
                                <span>{{ $tab['label'] }}</span>
                            </div>

                            @if($tab['badge'] !== null)
                                <span
                                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-dark text-accent' : 'bg-accent/20 text-accent'"
                                    class="px-2 py-0.5 text-[10px] font-mono font-bold"
                                >
                                    {{ $tab['badge'] }}
                                </span>
                            @endif
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Bottom Actions: Theme, View Live Site & Logout --}}
        <div :class="mobileMenuOpen ? 'block' : 'hidden lg:block'" class="p-4 border-t border-light-border dark:border-dark-border space-y-3">
            <div class="flex items-center justify-between px-2 text-[11px] font-mono text-muted">
                <span>LOCAL TIME</span>
                <span class="local-clock text-dark dark:text-offwhite">--:--:-- WIB</span>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="py-2.5 px-3 border border-light-border dark:border-dark-border text-center text-[11px] font-mono uppercase tracking-widest hover:border-accent hover:text-accent transition-colors"
                >
                    Live Site ↗
                </a>

                <button
                    type="button"
                    @click="toggleTheme()"
                    class="py-2.5 px-3 border border-light-border dark:border-dark-border text-center text-[11px] font-mono uppercase tracking-widest hover:border-accent hover:text-accent transition-colors cursor-pointer"
                >
                    Theme ◐
                </button>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full py-2.5 px-3 bg-dark-card border border-dark-border text-accent-orange hover:bg-accent-orange hover:text-dark text-[11px] font-mono uppercase tracking-widest transition-colors cursor-pointer"
                >
                    Terminate Session (Logout)
                </button>
            </form>
        </div>
    </aside>

    {{-- ===================================================================== --}}
    {{-- MAIN WORKSPACE AREA                                                   --}}
    {{-- ===================================================================== --}}
    <div class="relative z-10 flex-grow flex flex-col min-w-0">

        {{-- Top Command Header --}}
        <header class="border-b border-light-border dark:border-dark-border px-4 sm:px-6 md:px-10 py-4 sm:py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-light/60 dark:bg-dark/60 backdrop-blur-sm">
            <div>
                <div class="text-[10px] font-mono uppercase tracking-[0.22em] text-accent">
                    STUDIO COMMAND CENTER // ACTIVE: {{ auth()->user()->email }}
                </div>
                <h1 class="font-display italic text-2xl sm:text-3xl md:text-4xl text-dark dark:text-offwhite mt-0.5">
                    {{ $profile?->name ?? 'Portfolio Engine' }}
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.projects.create') }}"
                    class="w-full sm:w-auto text-center px-4 sm:px-5 py-2.5 sm:py-3 bg-accent text-dark font-mono text-xs uppercase tracking-[0.18em] font-semibold hover:bg-accent-hover transition-colors cursor-pointer"
                >
                    + Proyek Baru
                </a>
            </div>
        </header>

        {{-- Flash Notifications --}}
        <div class="px-4 sm:px-6 md:px-10 pt-4 sm:pt-6">
            @if(session('success'))
                <div class="p-4 border border-accent bg-accent/10 flex items-center justify-between text-xs font-mono text-dark dark:text-offwhite">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 border border-accent-orange bg-accent-orange/10 text-xs font-mono text-accent-orange space-y-1">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Dynamic Tab Content Container --}}
        <main class="p-4 sm:p-6 md:p-10 flex-grow">

            {{-- ============================================================= --}}
            {{-- TAB 00: COMMAND OVERVIEW                                      --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'overview'" class="space-y-10">
                {{-- Bento KPI Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card flex flex-col justify-between">
                        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-muted mb-6">
                            <span>01 / Published Works</span>
                            <span class="text-accent">ACTIVE</span>
                        </div>
                        <div class="font-display italic text-6xl text-accent leading-none mb-2">
                            {{ $projects->count() }}
                        </div>
                        <div class="text-xs font-mono text-muted">
                            {{ $projects->where('is_featured', true)->count() }} ditampilkan di Halaman Utama
                        </div>
                    </div>

                    <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card flex flex-col justify-between">
                        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-muted mb-6">
                            <span>02 / Client Inquiries</span>
                            <span class="{{ $unreadCount > 0 ? 'text-accent-orange' : 'text-accent' }}">
                                {{ $unreadCount }} UNREAD
                            </span>
                        </div>
                        <div class="font-display italic text-6xl text-dark dark:text-offwhite leading-none mb-2">
                            {{ $messages->count() }}
                        </div>
                        <div class="text-xs font-mono text-muted">
                            Pesan masuk dari formulir kontak
                        </div>
                    </div>

                    <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card flex flex-col justify-between">
                        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-muted mb-6">
                            <span>03 / Services & Process</span>
                            <span class="text-accent">DYNAMIC</span>
                        </div>
                        <div class="font-display italic text-6xl text-dark dark:text-offwhite leading-none mb-2">
                            {{ $services->count() }}
                        </div>
                        <div class="text-xs font-mono text-muted">
                            Layanan interaktif aktif
                        </div>
                    </div>

                    <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card flex flex-col justify-between">
                        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-widest text-muted mb-6">
                            <span>04 / Social Proof</span>
                            <span class="text-accent">VERIFIED</span>
                        </div>
                        <div class="font-display italic text-6xl text-dark dark:text-offwhite leading-none mb-2">
                            {{ $clients->count() }} <span class="text-2xl text-muted">/ {{ $testimonials->count() }}</span>
                        </div>
                        <div class="text-xs font-mono text-muted">
                            Logo Klien Marquee & Testimoni
                        </div>
                    </div>
                </div>

                {{-- Recent Messages & Quick Profile Status --}}
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                    <div class="xl:col-span-7 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card p-6 md:p-8">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-light-border dark:border-dark-border">
                            <h2 class="font-display italic text-2xl">Inkuiri Proyek Terbaru</h2>
                            <button type="button" @click="activeTab = 'inbox'" class="text-xs font-mono uppercase tracking-widest text-accent hover:underline cursor-pointer">
                                Buka Inbox ({{ $messages->count() }}) →
                            </button>
                        </div>

                        @if($messages->isEmpty())
                            <p class="py-12 text-center text-xs font-mono uppercase tracking-widest text-muted">
                                Belum ada pesan masuk dari pengunjung.
                            </p>
                        @else
                            <div class="divide-y divide-light-border dark:divide-dark-border">
                                @foreach($messages->take(4) as $msg)
                                    <div class="py-4 flex items-start justify-between gap-4">
                                        <div>
                                            <div class="flex items-center gap-2.5">
                                                @if(!$msg->is_read)
                                                    <span class="w-2 h-2 rounded-full bg-accent"></span>
                                                @endif
                                                <span class="font-medium text-sm">{{ $msg->name }}</span>
                                                <span class="text-xs font-mono text-muted">({{ $msg->email }})</span>
                                            </div>
                                            <div class="text-xs font-mono text-accent mt-1">
                                                {{ $msg->project_type }}{{ $msg->budget_range ? ' • ' . $msg->budget_range : '' }}
                                            </div>
                                            <p class="text-sm text-muted mt-1 line-clamp-1">{{ $msg->message }}</p>
                                        </div>
                                        <span class="text-[11px] font-mono text-muted shrink-0">
                                            {{ $msg->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="xl:col-span-5 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card p-6 md:p-8 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-mono uppercase tracking-widest text-muted">04 / Ketersediaan Slot Proyek</span>
                                @if($profile?->is_available)
                                    <span class="px-2.5 py-1 bg-accent/20 border border-accent text-accent text-[10px] font-mono uppercase font-bold flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-ping"></span>
                                        <span>SLOT TERBUKA</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-accent-orange/20 border border-accent-orange text-accent-orange text-[10px] font-mono uppercase font-bold flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-accent-orange"></span>
                                        <span>SLOT PENUH / TUTUP</span>
                                    </span>
                                @endif
                            </div>

                            <h2 class="font-display text-2xl sm:text-3xl mb-3">
                                {{ $profile?->is_available ? ($profile?->availability_status ?? 'Slot Terbuka • Sistem 1 Klien, 1 Fokus Penuh') : ($profile?->unavailable_status ?? 'Slot Sedang Penuh • Fokus Pengerjaan Proyek Berjalan') }}
                            </h2>

                            <p class="text-xs font-mono text-muted leading-relaxed mb-6">
                                {{ $profile?->is_available
                                    ? 'Pengunjung melihat indikator hijau aktif bahwa Anda siap menerima 1 proyek baru dengan dedikasi penuh.'
                                    : 'Pengunjung melihat indikator peringatan bahwa slot saat ini sedang penuh dan Anda sedang fokus mengerjakan proyek aktif.' }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-light-border dark:border-dark-border flex flex-wrap items-center gap-3">
                            <form method="POST" action="{{ route('admin.profile.toggle-availability') }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="px-5 py-3 border font-mono text-xs uppercase tracking-widest font-semibold transition-colors cursor-pointer {{ $profile?->is_available ? 'border-accent-orange bg-accent-orange/15 text-accent-orange hover:bg-accent-orange hover:text-dark' : 'border-accent bg-accent text-dark hover:bg-accent-hover' }}"
                                >
                                    {{ $profile?->is_available ? '⊘ Tutup Slot (Set Penuh)' : '✓ Buka Slot Sekarang' }}
                                </button>
                            </form>

                            <button type="button" @click="activeTab = 'profile'" class="px-5 py-3 border border-light-border dark:border-dark-border font-mono text-xs uppercase tracking-widest text-muted hover:text-dark dark:hover:text-offwhite hover:border-accent cursor-pointer">
                                Edit Teks Status →
                            </button>
                        </div>

                        {{-- Quick SEO Status Banner --}}
                        <div class="mt-6 pt-6 border-t border-light-border dark:border-dark-border flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full {{ ($profile?->seo['robots_index'] ?? true) ? 'bg-accent animate-pulse' : 'bg-accent-orange' }}"></span>
                                <div>
                                    <div class="text-xs font-mono font-medium text-dark dark:text-offwhite">
                                        SEO Google: {{ ($profile?->seo['robots_index'] ?? true) ? 'Terbuka (index, follow)' : 'Terkunci (noindex)' }}
                                    </div>
                                    <div class="text-[11px] font-mono text-muted">
                                        Title: {{ Str::limit($profile?->seo['meta_title'] ?? ($profile?->name . ' | ' . $profile?->profession), 38) }}
                                    </div>
                                </div>
                            </div>
                            <button type="button" @click="activeTab = 'seo'" class="text-xs font-mono uppercase tracking-widest text-accent hover:underline cursor-pointer">
                                Kelola SEO →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 01: INQUIRIES INBOX                                       --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'inbox'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-light-border dark:border-dark-border">
                    <div>
                        <span class="text-xs font-mono uppercase tracking-widest text-accent">01 // CLIENT INQUIRIES</span>
                        <h2 class="font-display italic text-3xl mt-1">Kotak Masuk Brief Proyek ({{ $messages->count() }})</h2>
                    </div>
                </div>

                @if($messages->isEmpty())
                    <div class="p-16 border border-light-border dark:border-dark-border text-center text-sm font-mono uppercase tracking-widest text-muted">
                        Kotak masuk masih kosong. Coba kirim pesan tes dari halaman utama!
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($messages as $msg)
                            <div class="p-6 md:p-8 border {{ $msg->is_read ? 'border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card/50' : 'border-accent bg-accent/5' }}">
                                <div class="flex flex-wrap items-start justify-between gap-4 pb-4 mb-4 border-b border-light-border dark:border-dark-border">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            @if(!$msg->is_read)
                                                <span class="px-2 py-0.5 bg-accent text-dark text-[10px] font-mono uppercase font-bold">BARU</span>
                                            @endif
                                            <h3 class="font-display text-2xl">{{ $msg->name }}</h3>
                                            <a href="mailto:{{ $msg->email }}" class="text-xs font-mono text-accent hover:underline">{{ $msg->email }}</a>
                                        </div>
                                        <div class="text-xs font-mono text-muted mt-1">
                                            Dikirim pada {{ $msg->created_at->format('d M Y, H:i') }} WIB • IP: {{ $msg->ip_address }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a
                                            href="mailto:{{ $msg->email }}?subject={{ rawurlencode('Re: Diskusi Proyek ' . $msg->project_type) }}"
                                            class="px-4 py-2 bg-accent text-dark text-xs font-mono uppercase tracking-widest font-semibold"
                                        >
                                            Balas Email ↗
                                        </a>

                                        <form method="POST" action="{{ route('admin.messages.toggle', $msg) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-2 border border-light-border dark:border-dark-border text-xs font-mono uppercase hover:border-accent cursor-pointer">
                                                {{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Selesai' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Hapus pesan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 border border-light-border dark:border-dark-border text-xs font-mono text-accent-orange hover:border-accent-orange cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 {{ $msg->budget_range ? 'sm:grid-cols-2' : '' }} gap-4 mb-4 text-xs font-mono">
                                    <div class="p-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border">
                                        <span class="text-muted block mb-1">JENIS LAYANAN / PROYEK:</span>
                                        <span class="text-accent font-semibold">{{ $msg->project_type }}</span>
                                    </div>
                                    @if($msg->budget_range)
                                        <div class="p-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border">
                                            <span class="text-muted block mb-1">ESTIMASI ANGGARAN:</span>
                                            <span class="text-dark dark:text-offwhite font-semibold">{{ $msg->budget_range }}</span>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-sm md:text-base leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 02: PROJECTS & CASE STUDIES CRUD                          --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'projects'" x-cloak class="space-y-8">
                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-light-border dark:border-dark-border">
                    <div>
                        <span class="text-xs font-mono uppercase tracking-widest text-accent">02 // CASE STUDIES REPOSITORY</span>
                        <h2 class="font-display italic text-3xl mt-1">Daftar Proyek & Studi Kasus ({{ $projects->count() }})</h2>
                    </div>
                    <a
                        href="{{ route('admin.projects.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold hover:bg-accent-hover transition-colors"
                    >
                        <span>+</span>
                        <span>Tambah Proyek Baru</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($projects as $proj)
                        <div class="border border-light-border dark:border-dark-border bg-light-surface/30 dark:bg-dark-card flex flex-col justify-between">
                            <div>
                                <div class="p-4 border-b border-light-border dark:border-dark-border flex items-center justify-between text-xs font-mono">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-accent text-dark font-bold">#{{ $proj->sort_order }}</span>
                                        <span class="uppercase text-muted">{{ $proj->category }} • {{ $proj->year }}</span>
                                    </div>
                                    @if($proj->is_featured)
                                        <span class="text-accent uppercase tracking-widest text-[10px]">★ Featured</span>
                                    @endif
                                </div>

                                <div class="aspect-[16/9] bg-dark overflow-hidden">
                                    @if($proj->thumbnail)
                                        <img src="{{ $proj->thumbnail }}" alt="{{ $proj->title }}" class="w-full h-full object-cover">
                                    @endif
                                </div>

                                <div class="p-6">
                                    <h3 class="font-display text-2xl mb-2">{{ $proj->title }}</h3>
                                    <p class="text-xs text-muted line-clamp-2 mb-3">{{ $proj->description }}</p>
                                    @if(!empty($proj->tech_stack))
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach($proj->tech_stack as $t)
                                                <span class="px-2 py-0.5 text-[10px] font-mono border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-surface/40">
                                                    {{ $t }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($proj->link)
                                        <a href="{{ $proj->link }}" target="_blank" class="inline-flex items-center gap-1.5 text-[11px] font-mono text-accent hover:underline">
                                            <span>Demo URL</span> ↗
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 border-t border-light-border dark:border-dark-border flex items-center justify-between gap-2">
                                <a href="{{ route('projects.show', $proj) }}" target="_blank" class="text-xs font-mono text-muted hover:text-accent">
                                    Lihat Halaman ↗
                                </a>

                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.projects.edit', $proj) }}"
                                        class="px-4 py-2 bg-accent text-dark text-xs font-mono uppercase tracking-widest font-semibold hover:bg-accent-hover transition-colors"
                                    >
                                        Edit Proyek
                                    </a>

                                    <form method="POST" action="{{ route('admin.projects.destroy', $proj) }}" onsubmit="return confirm('Hapus proyek ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 border border-light-border dark:border-dark-border text-xs font-mono text-accent-orange hover:border-accent-orange cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 03: SERVICES CRUD                                         --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'services'" x-cloak class="space-y-10">
                <div class="pb-4 border-b border-light-border dark:border-dark-border">
                    <span class="text-xs font-mono uppercase tracking-widest text-accent">03 // CAPABILITIES</span>
                    <h2 class="font-display italic text-3xl mt-1">Kelola Layanan & Deliverables</h2>
                </div>

                {{-- Existing Services Editable Cards --}}
                <div class="space-y-6">
                    @foreach($services as $srv)
                        <form method="POST" action="{{ route('admin.services.update', $srv) }}" class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-muted mb-2">Nomor</label>
                                    <input type="text" name="number" value="{{ $srv->number }}" required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-muted mb-2">Judul Layanan</label>
                                    <input type="text" name="title" value="{{ $srv->title }}" required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-muted mb-2">Urutan</label>
                                    <input type="number" name="sort_order" value="{{ $srv->sort_order }}" class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-muted mb-2">Deskripsi Layanan</label>
                                <textarea name="description" rows="2" required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">{{ $srv->description }}</textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                <div class="flex-grow">
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-muted mb-2">Deliverables (Pisahkan dengan koma)</label>
                                    <input type="text" name="deliverables_csv" value="{{ implode(', ', $srv->deliverables ?? []) }}" required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="submit" class="px-5 py-2.5 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold cursor-pointer">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>

                {{-- Add New Service Form --}}
                <form method="POST" action="{{ route('admin.services.store') }}" class="p-6 md:p-8 border border-dashed border-accent/60 bg-accent/5 space-y-6">
                    @csrf
                    <div class="text-xs font-mono uppercase tracking-widest text-accent">+ Tambah Layanan Baru</div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" name="number" placeholder="06" required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                        </div>
                        <div class="md:col-span-10">
                            <input type="text" name="title" placeholder="Judul Layanan Baru..." required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                        </div>
                    </div>

                    <textarea name="description" rows="2" placeholder="Deskripsi nilai jual layanan..." required class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm"></textarea>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="text" name="deliverables_csv" placeholder="Deliverable 1, Deliverable 2, Deliverable 3" required class="flex-grow px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                        <button type="submit" class="px-6 py-2.5 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold cursor-pointer">
                            + Tambahkan Layanan
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 04: CLIENTS & TESTIMONIALS CRUD                           --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'clients'" x-cloak class="space-y-12">
                {{-- Clients Marquee Manager --}}
                <div>
                    <div class="pb-4 mb-6 border-b border-light-border dark:border-dark-border">
                        <span class="text-xs font-mono uppercase tracking-widest text-accent">04A // MARQUEE CLIENTS</span>
                        <h2 class="font-display italic text-3xl mt-1">Daftar Klien Marquee ({{ $clients->count() }})</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach($clients as $cl)
                            <div class="p-4 border border-light-border dark:border-dark-border bg-light-surface/30 dark:bg-dark-card flex items-center justify-between">
                                <div>
                                    <div class="font-display italic text-xl">{{ $cl->name }}</div>
                                    <div class="text-[10px] font-mono uppercase text-accent">{{ $cl->industry }}</div>
                                </div>
                                <form method="POST" action="{{ route('admin.clients.destroy', $cl) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-mono text-accent-orange hover:underline cursor-pointer">✕</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('admin.clients.store') }}" class="flex flex-wrap gap-3 p-4 border border-dashed border-accent/50">
                        @csrf
                        <input type="text" name="name" placeholder="NAMA PERUSAHAAN / BRAND" required class="flex-grow px-4 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                        <input type="text" name="industry" placeholder="Industri (mis. Fintech)" class="px-4 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                        <button type="submit" class="px-6 py-2.5 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold cursor-pointer">
                            + Tambah Klien
                        </button>
                    </form>
                </div>

                {{-- Testimonials Manager --}}
                <div>
                    <div class="pb-4 mb-6 border-b border-light-border dark:border-dark-border flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-mono uppercase tracking-widest text-accent">04B // EXECUTIVE ENDORSEMENTS</span>
                            <h2 class="font-display italic text-3xl mt-1">Testimoni Klien ({{ $testimonials->count() }})</h2>
                        </div>
                    </div>

                    {{-- Public Form Shareable Banner --}}
                    <div class="mb-8 p-5 border border-accent/60 bg-accent/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-mono uppercase tracking-widest text-accent font-semibold block mb-1">
                                🔗 Formulir Testimoni Publik untuk Klien Anda
                            </span>
                            <div class="text-xs font-mono text-dark dark:text-offwhite font-bold break-all">
                                {{ url('/testimoni') }}
                            </div>
                            <p class="text-[11px] font-mono text-muted mt-1">
                                Bagikan link ini ke klien setelah proyek selesai. Klien dapat mengisi nama, rating, ulasan, dan mengunggah foto profil dari HP/komputer mereka.
                            </p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <a
                                href="{{ url('/testimoni') }}"
                                target="_blank"
                                class="px-4 py-2 bg-accent text-dark text-xs font-mono uppercase tracking-wider font-semibold hover:bg-accent-hover transition-colors"
                            >
                                Buka Formulir ↗
                            </a>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($testimonials as $testi)
                            <div class="p-6 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-4">
                                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-light-border/60 dark:border-dark-border/60">
                                    <div class="flex items-center gap-3">
                                        @if($testi->is_approved)
                                            <span class="px-2.5 py-1 bg-accent/20 border border-accent text-accent text-[10px] font-mono uppercase font-semibold">
                                                ✓ Tayang di Portofolio
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-accent-orange/20 border border-accent-orange text-accent-orange text-[10px] font-mono uppercase font-semibold">
                                                ⏳ Menunggu Persetujuan
                                            </span>
                                        @endif

                                        <span class="text-xs font-mono text-accent">
                                            {{ str_repeat('★', $testi->rating ?? 5) }} ({{ $testi->rating ?? 5 }}/5)
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.testimonials.toggle-approval', $testi) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 border {{ $testi->is_approved ? 'border-light-border dark:border-dark-border text-muted hover:text-offwhite' : 'border-accent bg-accent text-dark font-semibold' }} text-xs font-mono cursor-pointer"
                                            >
                                                {{ $testi->is_approved ? 'Sembunyikan' : '✓ Setujui & Tayangkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testi) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 border border-light-border dark:border-dark-border text-xs font-mono text-accent-orange hover:border-accent-orange cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.testimonials.update', $testi) }}" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-mono uppercase text-muted mb-1">Nama Klien</label>
                                            <input type="text" name="name" value="{{ $testi->name }}" required class="w-full px-3 py-2 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-mono uppercase text-muted mb-1">Jabatan / Role</label>
                                            <input type="text" name="role" value="{{ $testi->role }}" required class="w-full px-3 py-2 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-mono uppercase text-muted mb-1">Perusahaan / Brand</label>
                                            <input type="text" name="company" value="{{ $testi->company }}" required class="w-full px-3 py-2 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-mono uppercase text-muted mb-1">Isi Testimoni</label>
                                        <textarea name="quote" rows="3" required class="w-full px-3 py-2 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm leading-relaxed">{{ $testi->quote }}</textarea>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
                                        <div class="flex items-center gap-3 flex-grow">
                                            @if($testi->avatar)
                                                <img src="{{ asset($testi->avatar) }}" alt="{{ $testi->name }}" class="w-9 h-9 rounded-full object-cover border border-light-border dark:border-dark-border">
                                            @endif
                                            <input type="text" name="avatar" value="{{ $testi->avatar }}" placeholder="URL Avatar (opsional)" class="flex-grow px-3 py-2 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                                        </div>

                                        <button type="submit" class="px-5 py-2 bg-accent text-dark font-mono text-xs uppercase font-semibold cursor-pointer">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 05: PROFILE, BIO, STATS & CV                              --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'profile'" x-cloak>
                <div class="pb-4 mb-8 border-b border-light-border dark:border-dark-border">
                    <span class="text-xs font-mono uppercase tracking-widest text-accent">05 // PERSONAL IDENTITY & SETTINGS</span>
                    <h2 class="font-display italic text-3xl mt-1">Pengaturan Data Diri, Bio, Kontak & CV</h2>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-8 max-w-5xl">
                    @csrf
                    @method('PUT')

                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h3 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            Identitas Utama & Ketersediaan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $profile?->name }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Inisial Logo</label>
                                <input type="text" name="initials" value="{{ $profile?->initials }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Lokasi</label>
                                <input type="text" name="location" value="{{ $profile?->location }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Profesi / Role</label>
                                <input type="text" name="profession" value="{{ $profile?->profession }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Tagline Utama (1 Kalimat Kuat)</label>
                                <input type="text" name="tagline" value="{{ $profile?->tagline }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">
                            </div>
                        </div>

                        {{-- Pengaturan Status Ketersediaan Proyek (Open / Closed) --}}
                        <div class="p-5 border border-accent/40 bg-accent/5 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-light-border dark:border-dark-border">
                                <div>
                                    <div class="font-mono text-xs uppercase tracking-widest text-accent font-semibold flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $profile?->is_available ? 'bg-accent animate-ping' : 'bg-accent-orange' }}"></span>
                                        <span>Status Penerimaan Proyek (Buka / Tutup Slot)</span>
                                    </div>
                                    <p class="text-xs text-muted mt-1">
                                        Centang untuk membuka slot pengerjaan baru. Hapus centang bila slot sedang penuh (fokus pengerjaan 1 klien).
                                    </p>
                                </div>

                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="is_available" value="1" {{ $profile?->is_available ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-14 h-7 bg-light dark:bg-dark border border-light-border dark:border-dark-border peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-muted peer-checked:after:bg-dark after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-accent peer-checked:border-accent"></div>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                                <div>
                                    <label class="block text-[11px] font-mono uppercase text-muted mb-1.5">
                                        Teks Saat Slot Terbuka (Aktif) *
                                    </label>
                                    <input
                                        type="text"
                                        name="availability_status"
                                        value="{{ $profile?->availability_status }}"
                                        required
                                        placeholder="Slot Terbuka • Sistem 1 Klien, 1 Fokus Penuh"
                                        class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-xs font-mono"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-mono uppercase text-muted mb-1.5">
                                        Teks Saat Slot Ditutup / Penuh *
                                    </label>
                                    <input
                                        type="text"
                                        name="unavailable_status"
                                        value="{{ $profile?->unavailable_status ?? 'Slot Sedang Penuh • Fokus Pengerjaan Proyek Berjalan' }}"
                                        required
                                        placeholder="Slot Sedang Penuh • Fokus Pengerjaan Proyek Berjalan"
                                        class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-xs font-mono"
                                    >
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase text-muted mb-2">Subheadline Hero</label>
                            <textarea name="subheadline" rows="2" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm">{{ $profile?->subheadline }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase text-muted mb-2">Cerita Personal / Bio (Pisahkan paragraf dengan baris kosong)</label>
                            <textarea name="bio" rows="5" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm leading-relaxed">{{ $profile?->bio }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-light-border dark:border-dark-border">
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Foto Profil Potret (Upload dari Komputer)</label>
                                <div class="flex items-center gap-4">
                                    @if($profile?->photo)
                                        <div class="w-16 h-16 border border-light-border dark:border-dark-border shrink-0 overflow-hidden bg-dark">
                                            <img src="{{ asset($profile->photo) }}" alt="Preview Foto" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="flex-grow">
                                        <input type="file" name="photo_file" accept="image/*" class="w-full text-xs font-mono">
                                        <p class="text-[11px] font-mono text-muted mt-1">Pilih file gambar baru dari komputer untuk mengganti foto profil.</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Upload File CV / Resume (PDF)</label>
                                <div class="flex items-center gap-4">
                                    @if($profile?->cv_path)
                                        <a href="{{ $profile->cv_path }}" target="_blank" class="px-3 py-2 text-xs font-mono border border-light-border dark:border-dark-border text-accent shrink-0">Lihat CV</a>
                                    @endif
                                    <div class="flex-grow">
                                        <input type="file" name="cv_file" accept=".pdf" class="w-full text-xs font-mono">
                                        <p class="text-[11px] font-mono text-muted mt-1">Format file .pdf maksimal 8MB.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kontak & Sosial Media --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h3 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            Kontak & Tautan Jejaring Sosial
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Email Utama</label>
                                <input type="email" name="email" value="{{ $profile?->email }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Nomor WhatsApp (Format 628...)</label>
                                <input type="text" name="whatsapp" value="{{ $profile?->whatsapp }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">URL GitHub</label>
                                <input type="url" name="github" value="{{ $profile?->github }}" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">URL LinkedIn</label>
                                <input type="url" name="linkedin" value="{{ $profile?->linkedin }}" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">URL Instagram</label>
                                <input type="url" name="instagram" value="{{ $profile?->instagram }}" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">URL Dribbble / Behance</label>
                                <input type="url" name="dribbble" value="{{ $profile?->dribbble }}" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Jumlah Klien Dipercaya (Seksi 04)</label>
                                <input type="number" name="trusted_clients_count" value="{{ $profile?->trusted_clients_count ?? 38 }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">Jumlah Industri (Seksi 04)</label>
                                <input type="number" name="trusted_industries_count" value="{{ $profile?->trusted_industries_count ?? 11 }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Angka Pencapaian & Statistik (Seksi About) --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <div class="border-b border-light-border dark:border-dark-border pb-3">
                            <h3 class="text-xs font-mono uppercase tracking-widest text-accent">
                                Angka Pencapaian &amp; Metrik (Seksi 01 — About)
                            </h3>
                            <p class="text-xs text-muted mt-1">
                                4 angka counter interaktif yang beranimasi saat pengunjung scroll ke seksi Tentang Saya.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @php
                                $defaultStats = [
                                    ['value' => 4, 'suffix' => '+', 'label' => 'Tahun Pengalaman'],
                                    ['value' => 25, 'suffix' => '+', 'label' => 'Proyek Selesai'],
                                    ['value' => 98, 'suffix' => '%', 'label' => 'Skor Kecepatan Web'],
                                    ['value' => 24, 'suffix' => ' Jam', 'label' => 'Respon Support'],
                                ];
                                $currentStats = !empty($profile?->stats) ? $profile->stats : $defaultStats;
                            @endphp

                            @foreach($currentStats as $idx => $st)
                                <div class="p-4 border border-light-border dark:border-dark-border bg-light dark:bg-dark space-y-3">
                                    <div class="text-[10px] font-mono uppercase tracking-widest text-accent font-semibold">
                                        Metrik #0{{ $idx + 1 }}
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-mono uppercase text-muted mb-1">Angka (Value)</label>
                                        <input
                                            type="number"
                                            name="stats[{{ $idx }}][value]"
                                            value="{{ $st['value'] ?? 0 }}"
                                            required
                                            class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border font-mono text-sm"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-mono uppercase text-muted mb-1">Simbol / Akhiran (Suffix)</label>
                                        <input
                                            type="text"
                                            name="stats[{{ $idx }}][suffix]"
                                            value="{{ $st['suffix'] ?? '' }}"
                                            placeholder="misal: +, %, Jam"
                                            class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border font-mono text-xs"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-mono uppercase text-muted mb-1">Label Keterangan</label>
                                        <input
                                            type="text"
                                            name="stats[{{ $idx }}][label]"
                                            value="{{ $st['label'] ?? '' }}"
                                            required
                                            placeholder="misal: Tahun Pengalaman"
                                            class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Keahlian & Tech Stack --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h3 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            Keahlian Teknis &amp; Tech Stack (Seksi About)
                        </h3>
                        <p class="text-xs text-muted">
                            Pisahkan setiap item skill dengan tanda koma (,). Data ini tersimpan di database dan langsung tampil di seksi 01 — About.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">01 / Backend &amp; Database</label>
                                <input
                                    type="text"
                                    name="skills_raw[Backend & Database]"
                                    value="{{ implode(', ', $profile?->skills['Backend & Database'] ?? ['Laravel', 'PHP 8+', 'MySQL / MariaDB', 'SQLite', 'REST API', 'Integrasi Payment / WA Gateway']) }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm font-mono"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">02 / Frontend &amp; UI</label>
                                <input
                                    type="text"
                                    name="skills_raw[Frontend & UI]"
                                    value="{{ implode(', ', $profile?->skills['Frontend & UI'] ?? ['Tailwind CSS', 'Bootstrap', 'Alpine.js', 'JavaScript', 'Blade Templating', 'Responsive Web Design']) }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm font-mono"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">03 / Tools &amp; Deployment</label>
                                <input
                                    type="text"
                                    name="skills_raw[Tools & Deployment]"
                                    value="{{ implode(', ', $profile?->skills['Tools & Deployment'] ?? ['Git & GitHub', 'cPanel / Shared Hosting', 'VPS Linux', 'Optimasi SEO Dasar', 'Troubleshooting & Bug Fixing']) }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-sm font-mono"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Sertifikasi & Kredensial (Modal Seksi About) --}}
                    @php
                        $adminCerts = $profile?->certificates ?? [
                            ['title' => '', 'issuer' => '', 'year' => '', 'credential_id' => '', 'file_path' => '', 'url' => '', 'badge' => '']
                        ];
                        if (empty($adminCerts)) {
                            $adminCerts = [['title' => '', 'issuer' => '', 'year' => '', 'credential_id' => '', 'file_path' => '', 'url' => '', 'badge' => '']];
                        }
                    @endphp
                    <div
                        x-data="{
                            certs: {{ Js::from($adminCerts) }},
                            addCert() {
                                this.certs.push({ title: '', issuer: '', year: '{{ date('Y') }}', credential_id: '', file_path: '', url: '', badge: '' });
                            },
                            removeCert(index) {
                                if (this.certs.length > 1) {
                                    this.certs.splice(index, 1);
                                } else {
                                    this.certs = [{ title: '', issuer: '', year: '', credential_id: '', file_path: '', url: '', badge: '' }];
                                }
                            }
                        }"
                        class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-light-border dark:border-dark-border pb-3">
                            <div>
                                <h3 class="text-xs font-mono uppercase tracking-widest text-accent">
                                    Sertifikasi &amp; Kredensial Profesional (Modal About)
                                </h3>
                                <p class="text-xs text-muted mt-1">
                                    Unggah berkas sertifikat (PDF atau Gambar) yang dapat dilihat/diunduh langsung oleh pengunjung saat tombol "Sertifikasi ★" diklik.
                                </p>
                            </div>
                            <button
                                type="button"
                                @click="addCert()"
                                class="self-start sm:self-auto px-3.5 py-1.5 border border-accent/40 bg-accent/10 hover:bg-accent hover:text-dark text-accent text-xs font-mono uppercase tracking-wider transition-colors cursor-pointer"
                            >
                                + Tambah Sertifikat
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(cert, index) in certs" :key="index">
                                <div class="p-4 sm:p-5 border border-light-border dark:border-dark-border bg-light dark:bg-dark space-y-4 relative">
                                    {{-- Hidden input to preserve existing uploaded file path --}}
                                    <input type="hidden" :name="'certificates[' + index + '][file_path]'" :value="cert.file_path || ''">

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-mono text-muted uppercase tracking-wider" x-text="'Sertifikat #' + (index + 1)"></span>
                                        <button
                                            type="button"
                                            @click="removeCert(index)"
                                            class="text-xs font-mono text-danger hover:underline cursor-pointer"
                                        >
                                            Hapus ×
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono uppercase text-muted mb-1">Nama Sertifikasi / Lisensi *</label>
                                            <input
                                                type="text"
                                                :name="'certificates[' + index + '][title]'"
                                                x-model="cert.title"
                                                placeholder="mis. Laravel Certified Developer"
                                                class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs font-mono"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-mono uppercase text-muted mb-1">Penerbit / Institusi *</label>
                                            <input
                                                type="text"
                                                :name="'certificates[' + index + '][issuer]'"
                                                x-model="cert.issuer"
                                                placeholder="mis. Laravel LLC / Dicoding"
                                                class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs font-mono"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-mono uppercase text-muted mb-1">Tahun Perolehan</label>
                                            <input
                                                type="text"
                                                :name="'certificates[' + index + '][year]'"
                                                x-model="cert.year"
                                                placeholder="2024"
                                                class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs font-mono"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-mono uppercase text-muted mb-1">ID Kredensial / No. Registrasi</label>
                                            <input
                                                type="text"
                                                :name="'certificates[' + index + '][credential_id]'"
                                                x-model="cert.credential_id"
                                                placeholder="LCD-89214-ID"
                                                class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs font-mono"
                                            >
                                        </div>

                                        {{-- File Upload for Certificate --}}
                                        <div class="sm:col-span-2 p-3 border border-dashed border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-surface/40">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                                <label class="block text-[11px] font-mono uppercase text-accent font-semibold">
                                                    Upload File Sertifikat (PDF / JPG / PNG / WebP)
                                                </label>
                                                <template x-if="cert.file_path">
                                                    <span class="text-[11px] font-mono text-muted flex items-center gap-1.5">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                                                        <span>Berkas saat ini:</span>
                                                        <a :href="cert.file_path" target="_blank" class="text-accent underline font-semibold hover:text-offwhite">
                                                            Buka Berkas ↗
                                                        </a>
                                                    </span>
                                                </template>
                                            </div>

                                            <input
                                                type="file"
                                                :name="'certificate_files[' + index + ']'"
                                                accept=".pdf,.jpg,.jpeg,.png,.webp"
                                                class="block w-full text-xs font-mono text-muted file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-[11px] file:font-mono file:uppercase file:tracking-wider file:bg-dark dark:file:bg-offwhite file:text-offwhite dark:file:text-dark hover:file:bg-accent hover:file:text-dark cursor-pointer transition-colors"
                                            >
                                            <p class="text-[10px] font-mono text-muted mt-1.5">
                                                Pilih berkas PDF atau gambar (PNG, JPG, WebP) maks 10MB. Gambar otomatis dikompresi dan dikonversi ke WebP. Kosongkan jika ingin mempertahankan berkas sebelumnya.
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-mono uppercase text-muted mb-1">Link URL Verifikasi Online (Opsional)</label>
                                            <input
                                                type="url"
                                                :name="'certificates[' + index + '][url]'"
                                                x-model="cert.url"
                                                placeholder="https://certification.laravel.com/..."
                                                class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs font-mono"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-mono uppercase text-muted mb-1">Badge / Tingkat (Opsional)</label>
                                            <input
                                                type="text"
                                                :name="'certificates[' + index + '][badge]'"
                                                x-model="cert.badge"
                                                placeholder="Official / Expert / Specialization"
                                                class="w-full px-3 py-2 bg-light-surface dark:bg-dark-card border border-light-border dark:border-dark-border text-xs font-mono"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <button type="submit" class="px-8 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover cursor-pointer">
                        Simpan Seluruh Data Profil →
                    </button>
                </form>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 06: SEO & METADATA CONFIGURATION                          --}}
            {{-- ============================================================= --}}
            @php
                $seoData = $profile?->seo ?? [];
                $currentTitle = $seoData['meta_title'] ?? ($profile?->name . ' | ' . $profile?->profession);
                $currentDesc = $seoData['meta_description'] ?? ($profile?->tagline ?? '');
                $currentKeywords = $seoData['meta_keywords'] ?? 'Full-Stack Developer, Laravel, Tailwind CSS';
                $currentOgImage = $seoData['og_image'] ?? null;
                $currentTwitter = $seoData['twitter_handle'] ?? '@wrwstudio';
                $currentRobots = $seoData['robots_index'] ?? true;
                $currentGoogleVerif = $seoData['google_verification'] ?? '';
                $currentBingVerif = $seoData['bing_verification'] ?? '';
                $currentGA = $seoData['google_analytics_id'] ?? '';
                $currentSchema = $seoData['schema_type'] ?? 'Person';
            @endphp
            <div
                x-show="activeTab === 'seo'"
                x-cloak
                class="max-w-5xl space-y-8"
                x-data="{
                    title: {{ Js::from($currentTitle) }},
                    desc: {{ Js::from($currentDesc) }},
                    robotsIndex: {{ $currentRobots ? 'true' : 'false' }},
                    schemaType: {{ Js::from($currentSchema) }}
                }"
            >
                <div class="pb-4 border-b border-light-border dark:border-dark-border flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-mono uppercase tracking-widest text-accent">06 // SEARCH ENGINE OPTIMIZATION</span>
                        <h2 class="font-display italic text-3xl mt-1">SEO, Open Graph &amp; Webmaster</h2>
                        <p class="text-xs text-muted mt-1">
                            Kelola judul meta, pratinjau mesin pencari Google, kartu sosial media WhatsApp/Twitter, sitemap, dan verifikasi webmaster.
                        </p>
                    </div>

                    {{-- Quick Diagnostics Links --}}
                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('sitemap') }}"
                            target="_blank"
                            class="px-3 py-1.5 border border-light-border dark:border-dark-border text-xs font-mono uppercase tracking-wider hover:border-accent hover:text-accent transition-colors flex items-center gap-1.5"
                        >
                            <span>sitemap.xml</span>
                            <span>↗</span>
                        </a>
                        <a
                            href="{{ url('/robots.txt') }}"
                            target="_blank"
                            class="px-3 py-1.5 border border-light-border dark:border-dark-border text-xs font-mono uppercase tracking-wider hover:border-accent hover:text-accent transition-colors flex items-center gap-1.5"
                        >
                            <span>robots.txt</span>
                            <span>↗</span>
                        </a>
                    </div>
                </div>

                {{-- Interactive Live SERP Preview --}}
                <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card space-y-4">
                    <div class="flex items-center justify-between border-b border-light-border dark:border-dark-border pb-3">
                        <span class="text-xs font-mono uppercase tracking-widest text-accent flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                            Live Pratinjau Google Search (SERP)
                        </span>
                        <span class="text-[10px] font-mono text-muted uppercase">Desktop &amp; Mobile Preview</span>
                    </div>

                    {{-- Google Card Simulation --}}
                    <div class="p-4 sm:p-5 border border-light-border dark:border-dark-border bg-light dark:bg-dark/80 max-w-2xl font-sans space-y-1.5 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-accent/20 border border-accent/40 flex items-center justify-center text-[10px] font-mono font-bold text-accent">
                                {{ substr($profile?->initials ?? 'W', 0, 1) }}
                            </div>
                            <div class="text-xs font-sans text-dark/70 dark:text-offwhite/70 truncate">
                                {{ url('/') }}
                                <span class="text-muted text-[11px]"> › beranda</span>
                            </div>
                        </div>

                        <h4
                            class="text-base sm:text-lg text-[#1a0dab] dark:text-[#8ab4f8] hover:underline cursor-pointer font-medium leading-snug line-clamp-1"
                            x-text="title || 'Judul Meta Website Anda'"
                        ></h4>

                        <p
                            class="text-xs sm:text-sm text-dark/80 dark:text-offwhite/80 leading-relaxed line-clamp-2"
                            x-text="desc || 'Deskripsi singkat website Anda yang akan dibaca oleh calon klien di mesin pencari Google...'"
                        ></p>
                    </div>
                </div>

                {{-- Main SEO Settings Form --}}
                <form
                    method="POST"
                    action="{{ route('admin.seo.update') }}"
                    enctype="multipart/form-data"
                    class="space-y-8"
                >
                    @csrf
                    @method('PUT')

                    {{-- Group 1: Meta Tags --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h3 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            01 // Meta Tags Utama (Title, Description, Keywords)
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-mono uppercase text-muted">
                                        Judul Meta Halaman (Meta Title) *
                                    </label>
                                    <span class="text-[11px] font-mono" :class="title.length > 60 ? 'text-accent-orange' : 'text-muted'">
                                        <span x-text="title.length"></span> / 60 karakter disarankan
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    name="meta_title"
                                    x-model="title"
                                    required
                                    maxlength="150"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm"
                                    placeholder="Nama Lengkap | Profesi / Tagline Utama"
                                >
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-mono uppercase text-muted">
                                        Deskripsi Meta (Meta Description) *
                                    </label>
                                    <span class="text-[11px] font-mono" :class="desc.length > 160 ? 'text-accent-orange' : 'text-muted'">
                                        <span x-text="desc.length"></span> / 160 karakter disarankan
                                    </span>
                                </div>
                                <textarea
                                    name="meta_description"
                                    x-model="desc"
                                    required
                                    rows="3"
                                    maxlength="300"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm leading-relaxed"
                                    placeholder="Rangkuman 1-2 kalimat mengenai value dan jasa yang Anda tawarkan..."
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Kata Kunci Pencarian (Meta Keywords - Pisahkan dengan koma)
                                </label>
                                <input
                                    type="text"
                                    name="meta_keywords"
                                    value="{{ $currentKeywords }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm"
                                    placeholder="mis. Web Developer, Laravel Indonesia, Jasa Website, Frontend Engineer"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Group 2: Social Media & Open Graph --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h3 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            02 // Open Graph &amp; Social Card (WhatsApp, Telegram, Facebook, Twitter)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <div class="space-y-4">
                                <label class="block text-xs font-mono uppercase text-muted">
                                    Unggah Gambar Banner Share (OG Image 1200x630px)
                                </label>

                                <input
                                    type="file"
                                    name="og_image_file"
                                    accept="image/*"
                                    class="block w-full text-xs font-mono text-muted file:mr-3 file:py-2 file:px-4 file:border-0 file:text-xs file:font-mono file:uppercase file:tracking-wider file:bg-dark dark:file:bg-offwhite file:text-offwhite dark:file:text-dark hover:file:bg-accent hover:file:text-dark cursor-pointer transition-colors"
                                >
                                <p class="text-[11px] font-mono text-muted">
                                    Gambar ini muncul otomatis saat link website Anda dibagikan ke WhatsApp, Telegram, X/Twitter, dan LinkedIn. Disarankan rasio 1.91:1 (mis. 1200x630px). Otomatis dikonversi ke WebP.
                                </p>

                                @if(!empty($currentOgImage))
                                    <div class="pt-2 flex items-center gap-3">
                                        <label class="flex items-center gap-2 text-xs font-mono text-accent-orange cursor-pointer">
                                            <input type="checkbox" name="remove_og_image" value="1" class="rounded border-light-border dark:border-dark-border text-accent-orange focus:ring-0">
                                            <span>Hapus banner kustom (kembali ke bawaan)</span>
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <span class="block text-[11px] font-mono uppercase text-muted">Pratinjau Banner Share Aktif:</span>
                                @if(!empty($currentOgImage))
                                    <div class="border border-light-border dark:border-dark-border aspect-[1.91/1] overflow-hidden bg-dark/20 relative group">
                                        <img src="{{ asset($currentOgImage) }}" alt="OG Banner" class="w-full h-full object-cover">
                                        <a href="{{ asset($currentOgImage) }}" target="_blank" class="absolute inset-0 bg-dark/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-mono uppercase text-accent transition-opacity">
                                            Buka Ukuran Asli ↗
                                        </a>
                                    </div>
                                @else
                                    <div class="border border-dashed border-light-border dark:border-dark-border aspect-[1.91/1] flex flex-col items-center justify-center text-muted p-6 text-center">
                                        <span class="text-2xl mb-1">🖼</span>
                                        <span class="text-xs font-mono uppercase">Menggunakan Banner Bawaan (/images/og-default.jpg)</span>
                                    </div>
                                @endif
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Twitter / X Creator Handle
                                </label>
                                <input
                                    type="text"
                                    name="twitter_handle"
                                    value="{{ $currentTwitter }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm"
                                    placeholder="@username"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Group 3: Indexing, Webmaster & Analytics --}}
                    <div class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                        <h3 class="text-xs font-mono uppercase tracking-widest text-accent border-b border-light-border dark:border-dark-border pb-3">
                            03 // Kontrol Mesin Pencari, Verifikasi Webmaster &amp; Analitik
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Robots Index Toggle --}}
                            <div class="md:col-span-2 p-4 border border-light-border dark:border-dark-border bg-light dark:bg-dark flex items-start gap-4">
                                <input
                                    type="checkbox"
                                    id="robots_index_toggle"
                                    name="robots_index"
                                    value="1"
                                    x-model="robotsIndex"
                                    class="mt-1 w-4 h-4 rounded border-light-border dark:border-dark-border text-accent focus:ring-accent"
                                >
                                <label for="robots_index_toggle" class="cursor-pointer select-none">
                                    <span class="font-medium text-sm text-dark dark:text-offwhite block">
                                        Izinkan Mesin Pencari Mengindeks Website Ini (index, follow)
                                    </span>
                                    <span class="text-xs font-mono text-muted block mt-0.5">
                                        Hilangkan centang ini hanya jika website sedang dalam tahap perbaikan / uji coba rahasia (noindex, nofollow).
                                    </span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Google Search Console Verification Tag
                                </label>
                                <input
                                    type="text"
                                    name="google_verification"
                                    value="{{ $currentGoogleVerif }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs"
                                    placeholder="Contoh: google-site-verification=abc123xyz"
                                >
                                <span class="text-[10px] font-mono text-muted mt-1 block">
                                    Ambil kode verifikasi meta tag dari Google Search Console.
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Bing Webmaster Tools Verification Tag
                                </label>
                                <input
                                    type="text"
                                    name="bing_verification"
                                    value="{{ $currentBingVerif }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs"
                                    placeholder="Kode msvalidate.01"
                                >
                                <span class="text-[10px] font-mono text-muted mt-1 block">
                                    Kode verifikasi meta dari Bing Webmaster Portal.
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Google Analytics 4 (GA4 Measurement ID)
                                </label>
                                <input
                                    type="text"
                                    name="google_analytics_id"
                                    value="{{ $currentGA }}"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs"
                                    placeholder="G-XXXXXXXXXX"
                                >
                                <span class="text-[10px] font-mono text-muted mt-1 block">
                                    Script tracking gtag.js otomatis terpasang jika ID diisi.
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase text-muted mb-2">
                                    Tipe Schema.org Structured Data
                                </label>
                                <select
                                    name="schema_type"
                                    x-model="schemaType"
                                    class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-xs"
                                >
                                    <option value="Person">Person (Profil Pribadi / Developer / Kreatif)</option>
                                    <option value="ProfessionalService">ProfessionalService (Studio Jasa / Agensi)</option>
                                </select>
                                <span class="text-[10px] font-mono text-muted mt-1 block">
                                    Membantu Google memetakan Rich Snippet secara akurat.
                                </span>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="px-8 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover transition-colors cursor-pointer"
                    >
                        Simpan Konfigurasi SEO &amp; Metadata →
                    </button>
                </form>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 07: ADMIN SECURITY                                        --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'security'" x-cloak class="max-w-xl">
                <div class="pb-4 mb-8 border-b border-light-border dark:border-dark-border">
                    <span class="text-xs font-mono uppercase tracking-widest text-accent">07 // ACCESS CONTROL</span>
                    <h2 class="font-display italic text-3xl mt-1">Keamanan Akun Administrator</h2>
                </div>

                <form method="POST" action="{{ route('admin.security.update') }}" class="p-6 md:p-8 border border-light-border dark:border-dark-border bg-light-surface/20 dark:bg-dark-card space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-mono uppercase text-muted mb-2">Email Login Admin</label>
                        <input type="email" name="admin_email" value="{{ auth()->user()->email }}" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-muted mb-2">Kata Sandi Saat Ini *</label>
                        <input type="password" name="current_password" required class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-muted mb-2">Kata Sandi Baru (Kosongkan jika hanya ganti email)</label>
                        <input type="password" name="new_password" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-muted mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border font-mono text-sm">
                    </div>

                    <button type="submit" class="w-full py-4 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold cursor-pointer">
                        Perbarui Kredensial Keamanan
                    </button>
                </form>
            </div>

            {{-- ============================================================= --}}
            {{-- TAB 08: FIREWALL & IP BLACKLIST MANAGEMENT                    --}}
            {{-- ============================================================= --}}
            <div x-show="activeTab === 'firewall'" x-cloak class="space-y-10">
                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-light-border dark:border-dark-border">
                    <div>
                        <span class="text-xs font-mono uppercase tracking-widest text-accent-orange">08 // FIREWALL APPARATUS</span>
                        <h2 class="font-display italic text-3xl mt-1">Daftar Hitam IP &amp; Keamanan Jaringan</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-accent-orange/15 border border-accent-orange/40 text-accent-orange text-xs font-mono font-bold">
                            {{ $bannedIps->filter->isActive()->count() }} IP AKTIF DIBLOKIR
                        </span>
                    </div>
                </div>

                {{-- Policy Information Banner --}}
                <div class="p-6 border border-accent-orange/40 bg-accent-orange/5 space-y-3 text-xs font-mono">
                    <div class="flex items-center gap-2 text-accent-orange font-bold uppercase tracking-wider">
                        <span>🛡 PROTOKOL OTOMATISASI PEMBLOKIRAN IP (FIREWALL POLICY):</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div class="p-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border">
                            <span class="text-accent font-bold block mb-1">STRIKE 1 (1 JAM)</span>
                            <span class="text-muted">Gagal login admin 2x berturut-turut atau spamming form.</span>
                        </div>
                        <div class="p-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border">
                            <span class="text-accent-orange font-bold block mb-1">STRIKE 2 (3 JAM)</span>
                            <span class="text-muted">Pelanggaran berulang dalam jendela pemantauan aktif.</span>
                        </div>
                        <div class="p-3 bg-light dark:bg-dark border border-light-border dark:border-dark-border">
                            <span class="text-red-500 font-bold block mb-1">STRIKE 3 (PERMANEN)</span>
                            <span class="text-muted">Scripting injection (XSS/SQLi) atau pelanggaran 3x.</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-muted pt-2 border-t border-light-border dark:border-dark-border">
                        Anda dapat menghapus atau membuka kembali akses IP kapan saja dengan menekan tombol <strong>[Hapus Blokir]</strong> di bawah.
                    </p>
                </div>

                {{-- Banned IPs Table --}}
                <div class="border border-light-border dark:border-dark-border bg-light-surface/30 dark:bg-dark-card overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-light-border dark:border-dark-border flex items-center justify-between">
                        <h3 class="font-display italic text-2xl">Daftar IP yang Pernah / Sedang Terblokir ({{ $bannedIps->count() }})</h3>
                    </div>

                    @if($bannedIps->isEmpty())
                        <div class="p-16 text-center text-xs font-mono uppercase tracking-widest text-muted">
                            Belum ada alamat IP yang tercatat dalam daftar blokir firewall.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs font-mono">
                                <thead>
                                    <tr class="border-b border-light-border dark:border-dark-border bg-light dark:bg-dark text-muted uppercase tracking-widest text-[10px]">
                                        <th class="p-4">Alamat IP</th>
                                        <th class="p-4">Alasan Pemblokiran</th>
                                        <th class="p-4">Tingkat Strike</th>
                                        <th class="p-4">Status &amp; Sisa Waktu</th>
                                        <th class="p-4">Waktu Kejadian</th>
                                        <th class="p-4 text-right">Aksi Kelola</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-light-border dark:divide-dark-border">
                                    @foreach($bannedIps as $ban)
                                        <tr class="hover:bg-light/50 dark:hover:bg-dark-surface/50 transition-colors {{ $ban->isActive() ? 'bg-accent-orange/5' : '' }}">
                                            <td class="p-4 font-bold text-dark dark:text-offwhite whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full {{ $ban->isActive() ? ($ban->is_permanent ? 'bg-red-500 animate-ping' : 'bg-accent-orange') : 'bg-muted' }}"></span>
                                                    <span>{{ $ban->ip_address }}</span>
                                                </div>
                                                <span class="text-[10px] text-muted block mt-0.5">{{ $ban->incident_hash }}</span>
                                            </td>

                                            <td class="p-4 text-muted max-w-xs break-words">
                                                {{ $ban->reason }}
                                            </td>

                                            <td class="p-4 whitespace-nowrap">
                                                <span class="px-2 py-0.5 border {{ $ban->is_permanent ? 'border-red-500 text-red-500 bg-red-500/10' : 'border-accent-orange/40 text-accent-orange bg-accent-orange/10' }} text-[10px] font-bold">
                                                    {{ $ban->tier_label }}
                                                </span>
                                            </td>

                                            <td class="p-4 whitespace-nowrap font-medium">
                                                @if($ban->is_permanent)
                                                    <span class="text-red-500 font-bold">PERMANEN</span>
                                                @elseif($ban->isActive())
                                                    <span class="text-accent-orange font-bold">
                                                        AKTIF ({{ $ban->banned_until->diffForHumans(now(), true) }} lagi)
                                                    </span>
                                                @else
                                                    <span class="text-muted">Kadaluarsa (Selesai)</span>
                                                @endif
                                            </td>

                                            <td class="p-4 text-muted whitespace-nowrap text-[11px]">
                                                {{ $ban->last_violation_at ? $ban->last_violation_at->format('d M Y, H:i') : $ban->created_at->format('d M Y, H:i') }} WIB
                                            </td>

                                            <td class="p-4 text-right whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.banned-ips.destroy', $ban) }}" onsubmit="return confirm('Buka kembali akses untuk IP {{ $ban->ip_address }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="px-3.5 py-1.5 bg-accent text-dark font-mono text-xs uppercase tracking-wider font-semibold hover:bg-accent-hover transition-colors cursor-pointer"
                                                        title="Hapus IP ini dari daftar blokir"
                                                    >
                                                        ✓ Hapus Blokir (Unban)
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Manual IP Ban Form --}}
                <div class="border border-dashed border-accent-orange/60 bg-accent-orange/5 p-6 md:p-8 space-y-4">
                    <div>
                        <span class="text-xs font-mono uppercase tracking-widest text-accent-orange font-bold">+ Blokir Alamat IP Secara Manual</span>
                        <p class="text-xs text-muted mt-1">Masukkan alamat IP yang ingin Anda blokir secara langsung dari studio.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.banned-ips.store') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        @csrf
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-mono uppercase text-muted mb-1.5">Alamat IP *</label>
                            <input
                                type="text"
                                name="ip_address"
                                placeholder="misal: 182.253.112.4"
                                required
                                class="w-full px-4 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-xs font-mono"
                            >
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-mono uppercase text-muted mb-1.5">Durasi Blokir *</label>
                            <select
                                name="duration"
                                required
                                class="w-full px-3 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-xs font-mono"
                            >
                                <option value="1_hour">Tier 1 — Blokir 1 Jam</option>
                                <option value="3_hours">Tier 2 — Blokir 3 Jam</option>
                                <option value="permanent">Tier 3 — Blokir Permanen</option>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-mono uppercase text-muted mb-1.5">Alasan Pemblokiran *</label>
                            <input
                                type="text"
                                name="reason"
                                placeholder="misal: Scraping / Spam berulang"
                                required
                                class="w-full px-4 py-2.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-xs font-mono"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <button
                                type="submit"
                                class="w-full py-2.5 px-4 bg-accent-orange text-dark font-mono text-xs uppercase tracking-widest font-bold hover:bg-accent-orange/90 transition-colors cursor-pointer"
                            >
                                + Blokir IP
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

</body>
</html>

