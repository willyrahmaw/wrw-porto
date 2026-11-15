@props([
    'project',
    'index' => 0,
    'featuredLayout' => true,
])

@php
    $spanClasses = match ($index % 6) {
        0 => 'lg:col-span-7',
        1 => 'lg:col-span-5 lg:mt-16',
        2 => 'lg:col-span-5',
        3 => 'lg:col-span-7 lg:mt-12',
        4 => 'lg:col-span-7',
        default => 'lg:col-span-5 lg:mt-16',
    };

    $aspectClasses = match ($index % 3) {
        0 => 'aspect-[16/10]',
        1 => 'aspect-[4/3]',
        default => 'aspect-[16/11]',
    };
@endphp

<article
    x-show="selectedCategory === 'All' || selectedCategory === '{{ $project->category }}'"
    x-transition:enter="transition ease-out-expo duration-500"
    x-transition:enter-start="opacity-0 translate-y-6"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="{{ $featuredLayout ? $spanClasses : 'lg:col-span-6' }} group relative"
    data-aos="fade-up"
    data-aos-delay="{{ ($index % 2) * 100 }}"
>
    <a
        href="{{ route('projects.show', $project) }}"
        data-cursor="View"
        class="block border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card hover:border-accent dark:hover:border-accent transition-colors duration-500"
    >
        {{-- Top Meta Bar: Kategori & Tahun --}}
        <div class="px-5 py-3.5 border-b border-light-border dark:border-dark-border flex items-center justify-between text-[11px] font-mono uppercase tracking-[0.2em] text-muted">
            <div class="flex items-center gap-3">
                <span class="text-accent font-semibold">0{{ $index + 1 }}</span>
                <span>/</span>
                <span>{{ $project->category }}</span>
            </div>
            <span>{{ $project->year }}</span>
        </div>

        {{-- Thumbnail --}}
        <div class="relative {{ $aspectClasses }} overflow-hidden bg-dark">
            @if($project->thumbnail)
                <img
                    src="{{ $project->thumbnail }}"
                    alt="{{ $project->title }}"
                    loading="lazy"
                    width="900"
                    height="600"
                    class="w-full h-full object-cover grayscale-[35%] contrast-110 group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700 ease-out-expo"
                >
            @else
                <div class="w-full h-full flex items-center justify-center font-display italic text-4xl text-muted">
                    {{ $project->title }}
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent opacity-60 group-hover:opacity-30 transition-opacity duration-500"></div>

            @if($project->link)
                <div class="absolute bottom-4 left-4 px-3 py-1 bg-dark/90 backdrop-blur-md border border-dark-border text-[11px] font-mono text-accent">
                    Live Demo ↗
                </div>
            @endif
        </div>

        {{-- Content Details: Judul & Deskripsi --}}
        <div class="p-6 md:p-8">
            <div class="flex items-start justify-between gap-4 mb-3">
                <h3 class="font-display text-2xl sm:text-3xl text-dark dark:text-offwhite group-hover:text-accent transition-colors">
                    {{ $project->title }}
                </h3>
                <span class="w-9 h-9 shrink-0 rounded-full border border-light-border dark:border-dark-border flex items-center justify-center text-muted group-hover:bg-accent group-hover:text-dark group-hover:border-accent transition-all duration-300">
                    ↗
                </span>
            </div>

            <p class="text-sm md:text-base text-muted leading-relaxed line-clamp-2 mb-4">
                {{ $project->description }}
            </p>

            @if(!empty($project->tech_stack))
                <div class="flex flex-wrap gap-1.5 pt-3 border-t border-light-border dark:border-dark-border/60">
                    @foreach(array_slice($project->tech_stack, 0, 4) as $tech)
                        <span class="px-2 py-0.5 text-[10px] font-mono border border-light-border dark:border-dark-border bg-light-surface/50 dark:bg-dark-surface/50 text-dark/80 dark:text-offwhite/80">
                            {{ $tech }}
                        </span>
                    @endforeach
                    @if(count($project->tech_stack) > 4)
                        <span class="px-1.5 py-0.5 text-[10px] font-mono text-muted self-center">
                            +{{ count($project->tech_stack) - 4 }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </a>
</article>
