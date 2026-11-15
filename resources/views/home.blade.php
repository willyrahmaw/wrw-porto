<x-layouts.app :profile="$profile" :has-clients="$clients->count() > 0 || $testimonials->count() > 0">
    {{-- Hero Section --}}
    @include('partials.hero', ['profile' => $profile])

    {{-- 01 — About Section --}}
    @include('partials.about', ['profile' => $profile])

    {{-- 02 — Services & 4-Step Process Section --}}
    @include('partials.services', ['services' => $services])

    {{-- 03 — Projects Section (Tahap 4) --}}
    @includeIf('partials.projects', ['projects' => $projects, 'categories' => $categories])

    {{-- 04 — Clients & Testimonials Section (Tampil jika ada klien atau testimoni) --}}
    @if($clients->count() > 0 || $testimonials->count() > 0)
        @includeIf('partials.clients', ['clients' => $clients, 'testimonials' => $testimonials, 'profile' => $profile])
    @endif

    {{-- 05 — Contact Section (Tahap 5) --}}
    @includeIf('partials.contact', ['profile' => $profile, 'hasClients' => $clients->count() > 0 || $testimonials->count() > 0])
</x-layouts.app>

