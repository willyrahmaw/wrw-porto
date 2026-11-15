@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'profile' => null,
    'hasTestimonials' => false,
])

@php
    $seo = $profile?->seo ?? [];
    $siteName = $profile?->name ?? config('app.name', 'Willy — Creative Engineer');
    $defaultTitle = !empty($seo['meta_title']) ? $seo['meta_title'] : "{$siteName} | {$profile?->profession}";
    $metaTitle = $title ? "{$title} — {$siteName}" : $defaultTitle;
    $metaDesc = $description ?? ($seo['meta_description'] ?? $profile?->tagline ?? 'Building digital products and high-impact web experiences with precision, performance, and editorial craft.');
    $defaultOgImage = !empty($seo['og_image']) ? asset($seo['og_image']) : asset('images/og-default.jpg');
    $metaImage = $image ? asset($image) : $defaultOgImage;
    $canonical = url()->current();
@endphp

<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth" x-data="themeHandler">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Prevent Flash of Wrong Theme --}}
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    {{-- SEO Component --}}
    <x-seo
        :title="$metaTitle"
        :description="$metaDesc"
        :image="$metaImage"
        :type="$type"
        :canonical="$canonical"
        :profile="$profile"
        :seo="$seo"
    />

    {{-- Vite Assets + Bunny Fonts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark dark:bg-dark dark:text-offwhite font-sans antialiased selection:bg-accent selection:text-dark relative min-h-screen flex flex-col">

    {{-- Subtle Editorial Noise Overlay --}}
    <div class="pointer-events-none fixed inset-0 z-40 bg-grain opacity-20 dark:opacity-70" aria-hidden="true"></div>

    {{-- Brief Editorial Preloader (0 - 100) --}}
    <div
        id="preloader"
        class="fixed inset-0 z-50 bg-dark text-offwhite flex flex-col justify-between p-6 md:p-12 transition-transform duration-1000 ease-awwwards"
        aria-hidden="true"
    >
        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-[0.25em] text-muted">
            <span>{{ $siteName }}</span>
            <span>Portfolio Edition '26</span>
        </div>

        <div class="flex items-baseline justify-between">
            <span class="text-xs font-mono uppercase tracking-[0.2em] text-accent flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                Loading Experience
            </span>
            <div class="font-display italic text-7xl sm:text-9xl md:text-[11rem] leading-none tracking-tight text-offwhite">
                <span id="preloader-counter">00</span><span class="text-accent text-4xl sm:text-6xl">%</span>
            </div>
        </div>
    </div>

    {{-- Custom Cursor (Awwwards Magnetic Cursor without glitchy mix-blend) --}}
    <div
        id="custom-cursor"
        class="pointer-events-none fixed top-0 left-0 z-[1000] hidden md:flex items-center justify-center w-3 h-3 rounded-full bg-accent opacity-0 transition-[width,height,background-color,border-color,opacity] duration-200 ease-out-expo shadow-xl shadow-black/30"
        aria-hidden="true"
    >
        <span
            id="custom-cursor-text"
            class="text-[11px] font-mono uppercase tracking-[0.2em] font-bold text-dark opacity-0 scale-50 transition-all duration-150 select-none text-center leading-none"
        >
        </span>
    </div>

    {{-- Skip to Content for Keyboard Accessibility --}}
    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-5 focus:py-3 focus:bg-accent focus:text-dark focus:font-mono focus:text-xs focus:uppercase focus:tracking-widest"
    >
        Lewati ke konten utama
    </a>

    {{-- Navbar --}}
    @includeIf('partials.navbar', ['profile' => $profile, 'hasTestimonials' => $hasTestimonials])

    {{-- Main Page Content --}}
    <main id="main-content" class="flex-grow relative z-10">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @includeIf('partials.footer', ['profile' => $profile, 'hasTestimonials' => $hasTestimonials])

</body>
</html>

