<x-layouts.app
    :title="$project->title"
    :description="Str::limit($project->description, 160)"
    :image="$project->thumbnail"
    type="article"
    :profile="$profile"
>
    <article class="pt-28 pb-20 sm:pt-32 sm:pb-24 md:pt-40 md:pb-36">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

            {{-- Top Navigation & Category Bar --}}
            <div class="flex flex-wrap items-center justify-between gap-4 pb-8 border-b border-light-border dark:border-dark-border mb-12" data-aos="fade-up">
                <a
                    href="{{ route('projects.index') }}"
                    class="inline-flex items-center gap-2.5 text-xs font-mono uppercase tracking-[0.2em] text-muted hover:text-accent transition-colors"
                >
                    <span>← Kembali ke Semua Proyek</span>
                </a>

                <div class="flex items-center gap-3 text-xs font-mono uppercase tracking-[0.2em]">
                    <span class="px-3 py-1 bg-accent text-dark font-semibold">{{ $project->category }}</span>
                    <span class="text-muted">{{ $project->year }}</span>
                </div>
            </div>

            {{-- Project Title & Demo Action Bar --}}
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 pb-12 border-b border-light-border dark:border-dark-border mb-16" data-aos="fade-up" data-aos-delay="100">
                <div class="max-w-4xl">
                    <span class="text-xs font-mono uppercase tracking-[0.22em] text-accent block mb-3">
                        {{ $project->category }} // {{ $project->year }}
                    </span>
                    <h1 class="font-display text-4xl sm:text-6xl md:text-7xl leading-[1.02] tracking-tight text-dark dark:text-offwhite mb-6">
                        {{ $project->title }}
                    </h1>

                    @if(!empty($project->tech_stack))
                        <div class="flex flex-wrap items-center gap-2 pt-2">
                            <span class="text-[11px] font-mono uppercase tracking-widest text-muted mr-1">Tech Stack:</span>
                            @foreach($project->tech_stack as $tech)
                                <span class="px-3 py-1 text-xs font-mono border border-light-border dark:border-dark-border bg-light-surface/60 dark:bg-dark-card text-dark dark:text-offwhite">
                                    {{ $tech }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($project->link)
                    <div class="shrink-0">
                        <a
                            href="{{ $project->link }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="magnetic-btn inline-flex items-center gap-3 px-8 py-4 bg-accent text-dark font-mono text-xs uppercase tracking-[0.2em] font-semibold hover:bg-accent-hover transition-colors"
                        >
                            <span>Buka Live Demo</span>
                            <span>↗</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Main Showcase Image / Thumbnail --}}
            @if($project->thumbnail)
                <div class="relative aspect-[16/9] overflow-hidden border border-light-border dark:border-dark-border bg-dark mb-16" data-aos="zoom-in">
                    <img
                        src="{{ $project->thumbnail }}"
                        alt="Tampilan utama {{ $project->title }}"
                        width="1400"
                        height="788"
                        class="w-full h-full object-cover"
                    >
                </div>
            @endif

            {{-- Project Description --}}
            <div class="max-w-3xl mb-24" data-aos="fade-up">
                <div class="text-xs font-mono uppercase tracking-[0.22em] text-accent mb-4">
                    Deskripsi Proyek
                </div>
                <div class="space-y-4 text-base sm:text-lg text-dark/90 dark:text-offwhite/90 leading-relaxed whitespace-pre-line">
                    {{ $project->description }}
                </div>
            </div>

            {{-- Project Visual Gallery (Lebih dari 1 Foto) --}}
            @if(!empty($project->gallery) && count($project->gallery) > 0)
                <div class="space-y-8 mb-28">
                    <div class="flex items-center justify-between pb-4 border-b border-light-border dark:border-dark-border text-xs font-mono uppercase tracking-[0.22em] text-muted" data-aos="fade-up">
                        <span>Galeri Dokumentasi &amp; Tangkapan Layar ({{ count($project->gallery) }} Foto)</span>
                        <span class="text-accent">Eksplorasi UI</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($project->gallery as $idx => $galleryUrl)
                            <div
                                class="border border-light-border dark:border-dark-border overflow-hidden bg-dark {{ $loop->first && count($project->gallery) % 2 !== 0 ? 'md:col-span-2 aspect-[16/9]' : 'aspect-[16/10]' }}"
                                data-aos="fade-up"
                                data-aos-delay="{{ ($idx % 2) * 100 }}"
                            >
                                <img
                                    src="{{ $galleryUrl }}"
                                    alt="Foto Galeri {{ $project->title }} #{{ $idx + 1 }}"
                                    loading="lazy"
                                    width="1200"
                                    height="800"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 ease-out-expo"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Next Project Banner --}}
            @if($nextProject)
                <div class="border-t border-light-border dark:border-dark-border pt-16" data-aos="fade-up">
                    <a
                        href="{{ route('projects.show', $nextProject) }}"
                        data-cursor="Next"
                        class="group block p-5 sm:p-8 md:p-14 border border-light-border dark:border-dark-border bg-light-surface/40 dark:bg-dark-card hover:border-accent transition-all duration-500"
                    >
                        <div class="flex items-center justify-between text-xs font-mono uppercase tracking-[0.22em] text-muted mb-4">
                            <span>Proyek Selanjutnya</span>
                            <span class="text-accent">{{ $nextProject->category }} — {{ $nextProject->year }}</span>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                            <h3 class="font-display italic text-3xl sm:text-5xl md:text-6xl text-dark dark:text-offwhite group-hover:text-accent transition-colors">
                                {{ $nextProject->title }}
                            </h3>
                            <span class="inline-flex items-center gap-3 text-xs font-mono uppercase tracking-[0.2em] text-accent">
                                <span>Lihat Proyek</span>
                                <span class="text-xl group-hover:translate-x-2 transition-transform">→</span>
                            </span>
                        </div>
                    </a>
                </div>
            @endif

        </div>
    </article>
</x-layouts.app>
