@props([
    'href' => null,
    'variant' => 'primary', // primary | outline | ghost
    'type' => 'button',
])

@php
    $baseClasses = 'magnetic-btn inline-flex items-center justify-center gap-3 px-7 py-4 text-xs font-mono uppercase tracking-[0.18em] font-medium transition-all duration-300 ease-out-expo focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-dark cursor-pointer';

    $variants = [
        'primary' => 'bg-accent text-dark hover:bg-accent-hover shadow-[0_0_30px_rgba(198,244,50,0.15)] hover:shadow-[0_0_40px_rgba(198,244,50,0.35)]',
        'outline' => 'border border-light-border dark:border-dark-border text-dark dark:text-offwhite hover:border-accent dark:hover:border-accent hover:text-accent dark:hover:text-accent bg-transparent',
        'ghost'   => 'text-muted hover:text-dark dark:hover:text-offwhite',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

