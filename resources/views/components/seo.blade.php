@props([
    'title',
    'description',
    'image',
    'type' => 'website',
    'canonical' => url()->current(),
    'profile' => null,
])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

{{-- JSON-LD Person Structured Data --}}
@php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $profile?->name ?? 'Willy Putra',
        'jobTitle' => $profile?->profession ?? 'Senior Full-Stack Developer & Creative Engineer',
        'url' => url('/'),
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $profile?->location ?? 'Jakarta, Indonesia',
        ],
        'sameAs' => array_values(array_filter([
            $profile?->github,
            $profile?->linkedin,
            $profile?->instagram,
            $profile?->dribbble,
        ])),
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

