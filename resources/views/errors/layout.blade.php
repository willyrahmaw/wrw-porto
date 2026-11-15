@php
    $profile = $profile ?? \App\Models\Profile::query()->first();
    $siteName = $profile?->name ?? 'Willy Rahmat Wijaya';
    $whatsappNumber = preg_replace('/[^0-9]/', '', $profile?->whatsapp ?? '6281234567890');
@endphp
<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth" x-data="themeHandler">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') — {{ $siteName }}</title>

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark dark:bg-dark dark:text-offwhite font-sans antialiased selection:bg-accent selection:text-dark min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    {{-- Subtle Noise Grain Texture --}}
    <div class="pointer-events-none fixed inset-0 z-0 bg-grain opacity-20 dark:opacity-60" aria-hidden="true"></div>

    {{-- Top Minimal Navigation --}}
    <header class="relative z-10 border-b border-light-border dark:border-dark-border px-4 sm:px-6 md:px-12 py-5 flex items-center justify-between">
        <div class="flex items-center gap-4 sm:gap-6">
            <a
                href="{{ route('home') }}"
                class="font-display italic text-2xl md:text-3xl text-dark dark:text-offwhite hover:text-accent transition-colors"
            >
                {{ $profile?->initials ?? 'WRW.' }}
            </a>
            <span class="text-xs font-mono uppercase tracking-[0.2em] text-muted hidden sm:inline-block">
                // @yield('code', 'ERROR')
            </span>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
            <span class="hidden md:inline-block local-clock text-xs font-mono uppercase tracking-widest text-muted">--:--:-- WIB</span>

            <button
                type="button"
                @click="toggleTheme()"
                class="w-9 h-9 flex items-center justify-center border border-light-border dark:border-dark-border text-dark dark:text-offwhite hover:border-accent hover:text-accent transition-colors cursor-pointer"
                aria-label="Toggle Theme"
            >
                ◐
            </button>

            <a
                href="{{ route('home') }}"
                class="px-4 py-2 bg-accent text-dark font-mono text-xs uppercase tracking-widest font-semibold hover:bg-accent-hover transition-colors"
            >
                Beranda Utama →
            </a>
        </div>
    </header>

    {{-- Center Editorial Content Container --}}
    <main class="relative z-10 flex-grow flex items-center justify-center px-4 sm:px-6 md:px-12 py-12 md:py-20">
        <div class="max-w-4xl w-full border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card shadow-2xl p-6 sm:p-10 md:p-16">
            @yield('content')
        </div>
    </main>

    {{-- Minimal Footer --}}
    <footer class="relative z-10 border-t border-light-border dark:border-dark-border px-4 sm:px-6 md:px-12 py-6 text-xs font-mono uppercase tracking-widest text-muted flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            &copy; {{ date('Y') }} {{ $siteName }}. All Rights Reserved.
        </div>
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}#contact" class="hover:text-accent transition-colors">Kontak</a>
            <a href="{{ route('projects.index') }}" class="hover:text-accent transition-colors">Portofolio</a>
            <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Beranda</a>
        </div>
    </footer>

</body>
</html>
