<section
    id="projects"
    x-data="{
        selectedCategory: 'All',
        setCategory(cat) {
            this.selectedCategory = cat;
            setTimeout(() => {
                if (window.AOS) window.AOS.refresh();
            }, 350);
        }
    }"
    class="relative py-20 sm:py-24 md:py-36 border-b border-light-border dark:border-dark-border"
>
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

        <x-section-title
            number="03"
            label="Portofolio Proyek"
            title="Beberapa proyek yang <span class='italic font-normal text-accent'>pernah dikerjakan.</span>"
            subtitle="Mulai dari pembuatan aplikasi web, company profile, penambahan fitur baru, hingga optimasi dan maintenance."
        />

        {{-- Category Filter Bar + Archive Link --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-14" data-aos="fade-up">
            <div class="flex flex-wrap items-center gap-2" role="tablist" aria-label="Filter kategori proyek">
                <button
                    type="button"
                    @click="setCategory('All')"
                    :class="selectedCategory === 'All'
                        ? 'bg-accent text-dark border-accent font-semibold'
                        : 'border-light-border dark:border-dark-border text-muted hover:text-dark dark:hover:text-offwhite hover:border-accent'"
                    class="px-5 py-2.5 text-xs font-mono uppercase tracking-[0.18em] border transition-all duration-300 cursor-pointer"
                >
                    Semua ({{ $projects->count() }})
                </button>

                @foreach($categories as $category)
                    <button
                        type="button"
                        @click="setCategory('{{ $category }}')"
                        :class="selectedCategory === '{{ $category }}'
                            ? 'bg-accent text-dark border-accent font-semibold'
                            : 'border-light-border dark:border-dark-border text-muted hover:text-dark dark:hover:text-offwhite hover:border-accent'"
                        class="px-5 py-2.5 text-xs font-mono uppercase tracking-[0.18em] border transition-all duration-300 cursor-pointer"
                    >
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <a
                href="{{ route('projects.index') }}"
                class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-[0.2em] text-muted hover:text-accent transition-colors"
            >
                <span>Lihat Semua Proyek</span>
                <span>→</span>
            </a>
        </div>

        {{-- Asymmetric Bento Project Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            @foreach($projects as $index => $project)
                <x-project-card
                    :project="$project"
                    :index="$index"
                    :featuredLayout="true"
                />
            @endforeach
        </div>

        {{-- Bottom CTA: View All Projects --}}
        <div class="mt-20 pt-10 border-t border-light-border dark:border-dark-border flex flex-col sm:flex-row items-center justify-between gap-6" data-aos="fade-up">
            <p class="text-sm font-mono uppercase tracking-widest text-muted">
                Ingin melihat daftar lengkap proyek lainnya?
            </p>
            <x-button :href="route('projects.index')" variant="outline">
                <span>Lihat Semua Proyek</span>
                <span>→</span>
            </x-button>
        </div>

    </div>
</section>
