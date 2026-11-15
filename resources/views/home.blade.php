<x-layouts.app :profile="$profile">
    {{-- Hero Section --}}
    @include('partials.hero', ['profile' => $profile])

    {{-- 01 — About Section --}}
    @include('partials.about', ['profile' => $profile])

    {{-- 02 — Services & 4-Step Process Section --}}
    @include('partials.services', ['services' => $services])

    {{-- 03 — Projects Section (Tahap 4) --}}
    @includeIf('partials.projects', ['projects' => $projects, 'categories' => $categories])

    {{-- 04 — Clients & Testimonials Section (Tahap 4) --}}
    @includeIf('partials.clients', ['clients' => $clients, 'testimonials' => $testimonials, 'profile' => $profile])

    {{-- 05 — Contact Section (Tahap 5) --}}
    @includeIf('partials.contact', ['profile' => $profile])
</x-layouts.app>

