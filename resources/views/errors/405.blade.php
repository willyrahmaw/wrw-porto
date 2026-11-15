@extends('errors.layout')

@section('title', '405 — Metode HTTP Tidak Didukung')
@section('code', 'HTTP 405 // METHOD_NOT_ALLOWED')

@section('content')
<div class="space-y-8">
    {{-- Status Code & Category Pill --}}
    <div class="flex items-center justify-between pb-6 border-b border-light-border dark:border-dark-border">
        <div class="inline-flex items-center gap-2.5 px-3 py-1 border border-accent-orange/40 bg-accent-orange/10 text-[10px] font-mono uppercase tracking-[0.22em] text-accent-orange">
            <span class="w-1.5 h-1.5 rounded-full bg-accent-orange"></span>
            <span>Protokol Tidak Sesuai</span>
        </div>
        <span class="text-xs font-mono uppercase tracking-widest text-muted">Error 405</span>
    </div>

    {{-- Big Headline --}}
    <div class="space-y-4">
        <div class="font-display italic text-7xl sm:text-8xl md:text-9xl text-accent-orange leading-none select-none">
            405
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl text-dark dark:text-offwhite tracking-tight">
            Metode permintaan HTTP <span class="italic text-accent-orange">tidak didukung.</span>
        </h1>
        <p class="text-sm sm:text-base text-muted leading-relaxed max-w-2xl font-sans">
            Alur pengiriman data pada tautan ini memerlukan metode HTTP yang berbeda (seperti POST, PUT, atau GET tanpa karakter tambahan). Silakan kembali ke halaman utama atau gunakan navigasi yang tersedia.
        </p>
    </div>

    {{-- Interactive Actions Bento --}}
    <div class="pt-6 border-t border-light-border dark:border-dark-border grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a
            href="{{ route('home') }}"
            class="px-6 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold flex items-center justify-between hover:bg-accent-hover transition-colors"
        >
            <span>← Kembali ke Beranda</span>
            <span>↗</span>
        </a>

        <a
            href="{{ route('admin.dashboard') }}"
            class="px-6 py-4 border border-light-border dark:border-dark-border text-dark dark:text-offwhite font-mono text-xs uppercase tracking-[0.2em] flex items-center justify-between hover:border-accent hover:text-accent transition-colors"
        >
            <span>Buka Dashboard Admin</span>
            <span>→</span>
        </a>
    </div>
</div>
@endsection

