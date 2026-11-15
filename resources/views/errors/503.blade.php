@extends('errors.layout')

@php
    $profile = $profile ?? \App\Models\Profile::query()->first();
    $waNumber = preg_replace('/[^0-9]/', '', $profile?->whatsapp ?? '6281234567890');
    $ownerName = $profile?->name ?? 'Willy Rahmat Wijaya';
@endphp

@section('title', '503 — Sedang Dalam Pemeliharaan')
@section('code', 'HTTP 503 // MAINTENANCE')

@section('content')
<div class="space-y-8">
    {{-- Status Code & Category Pill --}}
    <div class="flex items-center justify-between pb-6 border-b border-light-border dark:border-dark-border">
        <div class="inline-flex items-center gap-2.5 px-3 py-1 border border-accent/40 bg-accent/10 text-[10px] font-mono uppercase tracking-[0.22em] text-accent">
            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-ping"></span>
            <span>Pembaruan Sistem Terjadwal</span>
        </div>
        <span class="text-xs font-mono uppercase tracking-widest text-muted">Status 503</span>
    </div>

    {{-- Big Headline --}}
    <div class="space-y-4">
        <div class="font-display italic text-7xl sm:text-8xl md:text-9xl text-accent leading-none select-none">
            503
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl text-dark dark:text-offwhite tracking-tight">
            Website sedang dalam proses <span class="italic text-accent">pemeliharaan &amp; rilis fitur.</span>
        </h1>
        <p class="text-sm sm:text-base text-muted leading-relaxed max-w-2xl font-sans">
            Saya sedang melakukan optimasi performa atau pembaruan berkas rutin agar pengalaman berselancar Anda semakin cepat dan aman. Website akan kembali online dalam beberapa menit.
        </p>
    </div>

    {{-- Interactive Actions Bento --}}
    <div class="pt-6 border-t border-light-border dark:border-dark-border grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button
            type="button"
            onclick="window.location.reload()"
            class="px-6 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold flex items-center justify-between hover:bg-accent-hover transition-colors cursor-pointer"
        >
            <span>↺ Periksa Ulang Status</span>
            <span>⟳</span>
        </button>

        <a
            href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode('Halo ' . $ownerName . ', saya sedang mencoba mengakses website Anda.') }}"
            target="_blank"
            class="px-6 py-4 border border-light-border dark:border-dark-border text-dark dark:text-offwhite font-mono text-xs uppercase tracking-[0.2em] flex items-center justify-between hover:border-accent hover:text-accent transition-colors"
        >
            <span>Hubungi via WhatsApp</span>
            <span>↗</span>
        </a>
    </div>
</div>
@endsection
