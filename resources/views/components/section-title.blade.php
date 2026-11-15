@props([
    'number' => '01',
    'label' => 'Section',
    'title' => '',
    'subtitle' => null,
])

<div class="mb-14 md:mb-20 border-b border-light-border dark:border-dark-border pb-8" data-aos="fade-up">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-3 text-xs font-mono uppercase tracking-[0.22em] text-muted mb-4">
                <span class="text-accent font-semibold">{{ $number }}</span>
                <span class="w-8 h-px bg-light-border dark:bg-dark-border"></span>
                <span>{{ $label }}</span>
            </div>
            <h2 class="font-display text-section text-dark dark:text-offwhite tracking-tight">
                {!! $title !!}
            </h2>
        </div>

        @if($subtitle)
            <p class="max-w-md text-sm md:text-base text-muted leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>

