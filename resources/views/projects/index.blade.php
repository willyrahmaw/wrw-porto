<x-layouts.app
    title="Daftar Proyek & Portofolio"
    description="Indeks proyek pembuatan website, aplikasi web, penambahan fitur, dan maintenance."
    :profile="$profile"
>
    <section
        x-data="{
            selectedCategory: 'All',
            setCategory(cat) {
                this.selectedCategory = cat;
                setTimeout(() => {
                    if (window.AOS) window.AOS.refresh();
                }, 350);
            }
        }"
        class="pt-36 pb-24 md:pt-44 md:pb-36 border-b border-light-border dark:border-dark-border"
    >
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12">

            {{-- Breadcrumb & Header --}}
            <div class="mb-12" data-aos="fade-up">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-[0.2em] text-muted hover:text-accent transition-colors mb-6"
                >
                    <span>← Kembali ke Beranda</span>
                </a>

                <h1 class="font-display text-hero uppercase text-dark dark:text-offwhite">
                    Selected <span class="italic font-normal text-accent lowercase">projects.</span>
                </h1>
            </div>

            {{-- Category Filter --}}
            <div class="flex flex-wrap items-center justify-between gap-4 py-8 border-y border-light-border dark:border-dark-border mb-16" data-aos="fade-up" data-aos-delay="100">
                <div class="flex flex-wrap items-center gap-2">
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

                <span class="text-xs font-mono uppercase tracking-widest text-muted">
                    Menampilkan Proyek Pilihan
                </span>
            </div>

            {{-- Projects Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                @foreach($projects as $index => $project)
                    <x-project-card
                        :project="$project"
                        :index="$index"
                        :featuredLayout="false"
                    />
                @endforeach
            </div>

        </div>
    </section>
</x-layouts.app>
