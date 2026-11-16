@props([
    'title',
    'description',
    'image',
    'type' => 'website',
    'canonical' => url()->current(),
    'profile' => null,
    'seo' => [],
])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if(!empty($seo['meta_keywords']))
<meta name="keywords" content="{{ $seo['meta_keywords'] }}">
@endif
<link rel="canonical" href="{{ $canonical }}">

{{-- Favicon, Apple Touch & Web App Icons --}}
@php
    $faviconPath = $seo['favicon'] ?? null;
    $faviconUrl = !empty($faviconPath) ? asset($faviconPath) : asset('favicon.svg');
    $faviconVersion = !empty($faviconPath) ? substr(md5($faviconPath), 0, 8) : '1';
    $faviconExt = strtolower(pathinfo(parse_url($faviconUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
    $faviconMime = match($faviconExt) {
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        default => 'image/x-icon',
    };
@endphp
<link rel="icon" type="{{ $faviconMime }}" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
@if(empty($faviconPath))
<link rel="alternate icon" href="{{ asset('favicon.ico') }}?v=1">
@else
<link rel="alternate icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
@endif

{{-- Search Engine Indexing Directives --}}
<meta name="robots" content="{{ ($seo['robots_index'] ?? true) ? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1' : 'noindex, nofollow' }}">

{{-- Webmaster Verification Tags --}}
@if(!empty($seo['google_verification']))
<meta name="google-site-verification" content="{{ $seo['google_verification'] }}">
@endif
@if(!empty($seo['bing_verification']))
<meta name="msvalidate.01" content="{{ $seo['bing_verification'] }}">
@endif

{{-- Open Graph / Facebook / WhatsApp --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="{{ $profile?->name ?? 'Portfolio' }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter / X Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
@if(!empty($seo['twitter_handle']))
<meta name="twitter:site" content="{{ $seo['twitter_handle'] }}">
<meta name="twitter:creator" content="{{ $seo['twitter_handle'] }}">
@endif

{{-- Google Analytics 4 (GA4) Tracking --}}
@if(!empty($seo['google_analytics_id']))
@php
    $gaId = (string) $seo['google_analytics_id'];
@endphp
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', "{{ $gaId }}");
</script>
@endif

{{-- JSON-LD Schema.org Structured Data --}}
@php
    $schemaType = $seo['schema_type'] ?? 'Person';
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => $schemaType,
        'name' => $profile?->name ?? 'Willy Rahmat Wijaya',
        'jobTitle' => $profile?->profession ?? 'Full-Stack Web Developer',
        'url' => url('/'),
        'description' => $description,
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $profile?->location ?? 'Indonesia',
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
