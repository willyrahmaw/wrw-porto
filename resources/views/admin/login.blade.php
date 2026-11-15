<!DOCTYPE html>
<html lang="id" class="dark" x-data="themeHandler">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Studio Command Center — Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark dark:bg-dark dark:text-offwhite font-sans antialiased selection:bg-accent selection:text-dark min-h-screen flex flex-col justify-between relative overflow-hidden">

    <div class="pointer-events-none fixed inset-0 z-0 bg-grain opacity-20 dark:opacity-70" aria-hidden="true"></div>

    {{-- Top Minimal Bar --}}
    <header class="relative z-10 border-b border-light-border dark:border-dark-border px-4 sm:px-6 md:px-12 py-4 sm:py-6 flex items-center justify-between">
        <a href="{{ route('home') }}" class="font-display italic text-2xl md:text-3xl text-dark dark:text-offwhite hover:text-accent transition-colors">
            WRW. <span class="font-mono not-italic text-[11px] uppercase tracking-[0.2em] text-muted ml-1 sm:ml-2">// Studio CMS</span>
        </a>

        <div class="flex items-center gap-4">
            <span class="hidden sm:inline-block local-clock text-xs font-mono uppercase tracking-widest text-muted">--:--:-- WIB</span>
            <a href="{{ route('home') }}" class="text-xs font-mono uppercase tracking-[0.18em] text-muted hover:text-accent transition-colors">
                ← <span class="hidden sm:inline">Live Website</span><span class="sm:hidden">Web</span>
            </a>
        </div>
    </header>

    {{-- Center Login Split Grid --}}
    <main class="relative z-10 flex-grow flex items-center justify-center px-4 sm:px-6 py-6 sm:py-12">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card shadow-2xl">

            {{-- Left Editorial Branding Panel (6 Cols) --}}
            <div class="lg:col-span-6 p-6 sm:p-8 md:p-12 border-b lg:border-b-0 lg:border-r border-light-border dark:border-dark-border flex flex-col justify-between bg-dark text-offwhite">
                <div>
                    <div class="inline-flex items-center gap-2.5 px-3 py-1 border border-dark-border text-[10px] font-mono uppercase tracking-[0.22em] text-accent mb-6 sm:mb-8">
                        <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                        <span>Restricted Access</span>
                    </div>

                    <h1 class="font-display text-4xl sm:text-5xl md:text-6xl leading-[0.95] tracking-tight mb-4 sm:mb-6">
                        Studio <span class="italic text-accent">Command</span> Center.
                    </h1>

                    <p class="text-xs sm:text-sm text-muted leading-relaxed">
                        Panel kendali untuk mengelola studi kasus portofolio, layanan, klien, testimoni, serta membalas brief proyek yang masuk secara real-time.
                    </p>
                </div>

                <div class="pt-8 sm:pt-12 mt-8 sm:mt-12 border-t border-dark-border flex items-center justify-between text-[11px] font-mono uppercase tracking-widest text-muted">
                    <span>Encrypted Session</span>
                    <span class="text-accent">Laravel Engine</span>
                </div>
            </div>

            {{-- Right Authentication Form (6 Cols) --}}
            <div class="lg:col-span-6 p-6 sm:p-8 md:p-12 flex flex-col justify-center">
                <div class="text-xs font-mono uppercase tracking-[0.22em] text-accent mb-6">
                    Authentication Required
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 border border-accent bg-accent/10 text-xs font-mono text-dark dark:text-offwhite">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 border border-accent-orange bg-accent-orange/10 text-xs font-mono text-accent-orange">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-mono uppercase tracking-[0.2em] text-muted mb-2.5">
                            01 / Admin Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', 'admin@wrw.studio') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-dark dark:text-offwhite text-sm font-mono focus:outline-none focus:border-accent transition-colors"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-mono uppercase tracking-[0.2em] text-muted mb-2.5">
                            02 / Security Key (Password)
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••••••"
                            class="w-full px-4 py-3.5 bg-light dark:bg-dark border border-light-border dark:border-dark-border text-dark dark:text-offwhite text-sm font-mono focus:outline-none focus:border-accent transition-colors"
                        >
                    </div>

                    <div class="flex items-center justify-between text-xs font-mono text-muted">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="accent-[#C6F432]">
                            <span>Ingat perangkat ini</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-4 px-6 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover transition-colors flex items-center justify-between cursor-pointer"
                    >
                        <span>Masuk ke Command Center</span>
                        <span>→</span>
                    </button>
                </form>
            </div>

        </div>
    </main>

    {{-- Bottom Status Footer --}}
    <footer class="relative z-10 border-t border-light-border dark:border-dark-border px-6 md:px-12 py-4 flex items-center justify-between text-[11px] font-mono uppercase tracking-widest text-muted">
        <span>STATUS: SYSTEM ONLINE</span>
        <span>DEFAULT DEV LOGIN: admin@wrw.studio / password123</span>
    </footer>

</body>
</html>

