@extends('errors.layout')

@php
    $profile = $profile ?? \App\Models\Profile::query()->first();
    $waNumber = preg_replace('/[^0-9]/', '', $profile?->whatsapp ?? '6281234567890');
@endphp

@section('title', '500 — Terjadi Kesalahan Server')
@section('code', 'HTTP 500 // SERVER_ERROR')

@section('content')
<div class="space-y-8">
    {{-- Status Code & Category Pill --}}
    <div class="flex items-center justify-between pb-6 border-b border-light-border dark:border-dark-border">
        <div class="inline-flex items-center gap-2.5 px-3 py-1 border border-accent-orange/40 bg-accent-orange/10 text-[10px] font-mono uppercase tracking-[0.22em] text-accent-orange">
            <span class="w-1.5 h-1.5 rounded-full bg-accent-orange animate-ping"></span>
            <span>Kendala Sistem</span>
        </div>
        <span class="text-xs font-mono uppercase tracking-widest text-muted">Error 500</span>
    </div>

    {{-- Big Headline --}}
    <div class="space-y-4">
        <div class="font-display italic text-7xl sm:text-8xl md:text-9xl text-accent-orange leading-none select-none">
            500
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl text-dark dark:text-offwhite tracking-tight">
            Terjadi kendala pada <span class="italic text-accent-orange">server aplikasi.</span>
        </h1>
        <p class="text-sm sm:text-base text-muted leading-relaxed max-w-2xl font-sans">
            Sistem kami sedang mengalami gangguan pemrosesan sesaat. Masalah ini telah dicatat ke dalam log pemeliharaan dan sedang ditangani secepatnya.
        </p>
    </div>

    {{-- Interactive Actions Bento --}}
    <div class="pt-6 border-t border-light-border dark:border-dark-border grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button
            type="button"
            onclick="window.location.reload()"
            class="px-6 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold flex items-center justify-between hover:bg-accent-hover transition-colors cursor-pointer"
        >
            <span>↺ Muat Ulang Halaman</span>
            <span>⟳</span>
        </button>

        <a
            href="{{ route('home') }}"
            class="px-6 py-4 border border-light-border dark:border-dark-border text-dark dark:text-offwhite font-mono text-xs uppercase tracking-[0.2em] flex items-center justify-between hover:border-accent hover:text-accent transition-colors"
        >
            <span>← Kembali ke Beranda</span>
            <span>↗</span>
        </a>
    </div>

    {{-- Direct Contact Emergency --}}
    <div class="p-4 bg-light dark:bg-dark border border-light-border dark:border-dark-border flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs font-mono">
        <span class="text-muted">
            Butuh respon darurat terkait proyek yang sedang berjalan?
        </span>
        <a
            href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode('Halo, terjadi kendala error 500 pada website saat membuka halaman: ' . url()->current()) }}"
            target="_blank"
            class="text-accent hover:underline shrink-0"
        >
            Hubungi via WhatsApp ↗
        </a>
    </div>
</div>
@endsection
