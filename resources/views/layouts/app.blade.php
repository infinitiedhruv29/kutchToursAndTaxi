@php
    $settings = $settings ?? \App\Models\Setting::current();
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hi! I am interested in Kutch Tours & Taxi services. Please share details.");
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kutch Tours & Taxi — Rann of Kutch Tour Packages & Cab Booking')</title>
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="description" content="@yield('meta_description', 'Book reliable taxi service and all-inclusive tour packages in Kutch, Gujarat. Explore White Rann, Dholavira, Mandvi Beach and Bhuj with local expert drivers.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Kutch tour packages, taxi service in bhuj, kutch cab booking, rann of kutch tour, dholavira cab, road to heaven kutch')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'Kutch Tours & Taxi — Rann of Kutch Tour Packages & Cab Booking')">
    <meta property="og:description" content="@yield('og_description', 'Book reliable taxi service and all-inclusive tour packages in Kutch, Gujarat.')">
    <meta property="og:image" content="@yield('og_image', asset('images/index-page/white-rann-hero.webp'))">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Kutch Tours & Taxi')">
    <meta name="twitter:description" content="@yield('og_description', 'Explore White Rann, Dholavira and Mandvi with expert local cab drivers.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/index-page/white-rann-hero.webp'))">

    @yield('schema_json')

    <link rel="icon" href="{{ $settings->favicon_url }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @php
        $primaryColor = $settings->primary_color ?? '#E7BE67';
        $hex = ltrim($primaryColor, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $primaryRgb = "{$r}, {$g}, {$b}";

        // Compute high-contrast text color for primary background (dark on bright yellow/gold, white on dark)
        $primaryLuminance = ($r * 0.299 + $g * 0.587 + $b * 0.114);
        $primaryText = $primaryLuminance > 165 ? '#111111' : '#FFFFFF';

        // Compute midnight dark base from primary color (eliminates hardcoded reddish base)
        $darkR = max(8, min(24, (int) round($r * 0.08)));
        $darkG = max(8, min(24, (int) round($g * 0.08)));
        $darkB = max(10, min(32, (int) round($b * 0.10)));
        $darkMidnight = sprintf('#%02x%02x%02x', $darkR, $darkG, $darkB);

        // Slightly lighter midnight shade for cards/borders
        $lightR = max(16, min(38, (int) round($r * 0.14)));
        $lightG = max(16, min(38, (int) round($g * 0.14)));
        $lightB = max(20, min(48, (int) round($b * 0.16)));
        $darkLight = sprintf('#%02x%02x%02x', $lightR, $lightG, $lightB);
    @endphp
    <style>
        :root {
            --orange: {{ $primaryColor }};
            --orange-rgb: {{ $primaryRgb }};
            --orange-dark: {{ $primaryColor }};
            --primary: {{ $primaryColor }};
            --primary-rgb: {{ $primaryRgb }};
            --primary-color: {{ $primaryColor }};
            --primary-text: {{ $primaryText }};
            --navy: {{ $darkMidnight }};
            --navy-light: {{ $darkLight }};
        }

        /* ── BUTTONS ON PRIMARY BACKGROUND ── */
        .btn-primary, [style*="background:var(--orange)"] {
            color: {{ $primaryText }} !important;
        }

        /* ── HEADER NAVIGATION SHADE & BORDER ── */
        .nav {
            height: 98px !important;
            padding: 0 32px !important;
            border-color: rgba({{ $primaryRgb }}, 0.18) !important;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba({{ $primaryRgb }}, 0.25) !important;
        }
        .nav.scrolled {
            height: 72px !important;
            border-color: rgba({{ $primaryRgb }}, 0.25) !important;
            box-shadow: 0 20px 48px -10px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba({{ $primaryRgb }}, 0.3) !important;
        }
        .nav .brand-link img {
            height: 88px !important;
            width: auto !important;
            max-width: 320px !important;
            object-fit: contain !important;
            transition: height 0.3s ease !important;
        }
        .nav.scrolled .brand-link img {
            height: 62px !important;
        }
        @media(max-width:900px) {
            .nav {
                height: 86px !important;
                padding: 0 24px !important;
            }
            .nav .brand-link img {
                height: 74px !important;
            }
            .nav.scrolled .brand-link img {
                height: 58px !important;
            }
        }
        @media(max-width:600px) {
            .nav {
                height: 76px !important;
                padding: 0 16px !important;
            }
            .nav .brand-link img {
                height: 64px !important;
            }
            .nav.scrolled .brand-link img {
                height: 52px !important;
            }
        }

        /* ── HOME HERO & INNER PAGE HERO (DARK SHADE OF PRIMARY COLOR) ── */
        body[data-page="Home"] {
            background-color: {{ $darkMidnight }} !important;
        }
        body[data-page="Home"] .page-wrapper {
            padding-top: 0 !important;
        }
        .hero {
            position: relative;
            overflow: hidden;
            background-color: {{ $darkMidnight }} !important;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 70% 30%, rgba({{ $primaryRgb }}, 0.24) 0%, transparent 68%) !important;
            pointer-events: none;
            z-index: 1;
        }
        .page-hero {
            position: relative;
            overflow: hidden;
            background-color: {{ $darkMidnight }} !important;
        }
        .page-hero::before {
            background: radial-gradient(ellipse at 60% 40%, rgba({{ $primaryRgb }}, 0.22) 0%, transparent 65%) !important;
            pointer-events: none !important;
        }
        .hero-blob-1 {
            background: radial-gradient(circle, rgba({{ $primaryRgb }}, 0.20) 0%, transparent 70%) !important;
            pointer-events: none !important;
        }

        /* ── FOOTER (DARK SHADE OF PRIMARY COLOR, MATCHING HEADER SHADE) ── */
        .footer {
            position: relative;
            overflow: hidden;
            background-color: {{ $darkMidnight }} !important;
            border-top: 1px solid rgba({{ $primaryRgb }}, 0.25) !important;
        }
        .footer::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 0%, rgba({{ $primaryRgb }}, 0.22) 0%, transparent 70%) !important;
            pointer-events: none;
            z-index: 0;
        }
        .footer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, {{ $primaryColor }}, transparent);
            opacity: 0.9;
            pointer-events: none;
            z-index: 2;
        }
        .footer > * {
            position: relative;
            z-index: 1;
        }

        /* ── BOTTOM CTA BANNER (DARK SHADE OF PRIMARY COLOR, MATCHING HEADER SHADE) ── */
        .cta-banner {
            position: relative;
            overflow: hidden;
            background-color: {{ $darkMidnight }} !important;
            border-top: 1px solid rgba({{ $primaryRgb }}, 0.25) !important;
            border-bottom: 1px solid rgba({{ $primaryRgb }}, 0.20) !important;
        }
        .cta-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 50%, rgba({{ $primaryRgb }}, 0.22) 0%, transparent 70%) !important;
            pointer-events: none;
            z-index: 0;
        }
        .cta-banner::after {
            content: '';
            position: absolute;
            top: 0;
            left: 15%;
            right: 15%;
            height: 1px;
            background: linear-gradient(90deg, transparent, {{ $primaryColor }}, transparent);
            opacity: 0.9;
            pointer-events: none;
            z-index: 2;
        }
        .cta-banner > * {
            position: relative;
            z-index: 1;
        }

        /* ── BUTTONS, TABS & WIDGETS ── */
        #link-hero-explore-packages, .btn-primary {
            background-color: {{ $primaryColor }} !important;
            background: {{ $primaryColor }} !important;
        }
        .btn-primary:hover {
            box-shadow: 0 8px 28px rgba({{ $primaryRgb }}, 0.4) !important;
        }
        .gallery-filter-btn.active,
        .gallery-filter-btn.active:hover,
        .gallery-filter-btn.active:focus {
            background-color: {{ $primaryColor }} !important;
            border-color: {{ $primaryColor }} !important;
            color: #ffffff !important;
            box-shadow: 0 2px 10px rgba({{ $primaryRgb }}, 0.35) !important;
        }
        .gallery-filter-btn:hover {
            border-color: {{ $primaryColor }} !important;
            color: {{ $primaryColor }} !important;
        }
        .filter-cap-btn {
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
        }
        .filter-cap-btn:hover {
            border-color: {{ $primaryColor }} !important;
            color: {{ $primaryColor }} !important;
            background: #ffffff !important;
        }
        .filter-cap-btn.active,
        .filter-cap-btn.active:hover,
        .filter-cap-btn.active:focus {
            background-color: {{ $primaryColor }} !important;
            border-color: {{ $primaryColor }} !important;
            color: #ffffff !important;
            box-shadow: 0 2px 10px rgba({{ $primaryRgb }}, 0.35) !important;
        }
        .vehicle-item, .pkg-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .vehicle-item:hover, .pkg-card:hover {
            border-color: {{ $primaryColor }} !important;
            box-shadow: 0 16px 36px rgba({{ $primaryRgb }}, 0.14), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
            transform: translateY(-6px) !important;
        }
        .vehicle-item .vehicle-img-wrapper img,
        .pkg-card .relative img,
        .pkg-card img.pkg-thumb-img {
            transition: transform 0.4s ease !important;
        }
        .vehicle-item:hover .vehicle-img-wrapper img,
        .pkg-card:hover .relative img,
        .pkg-card:hover img.pkg-thumb-img {
            transform: scale(1.05) !important;
        }
        .ticker-wrap {
            background-color: {{ $primaryColor }} !important;
            border-color: rgba(0, 0, 0, 0.25) !important;
        }

        /* ── TAILWIND CLASS OVERRIDES FOR BRAND COLOR ── */
        .text-\[\#9E2A2B\], .text-\[\#9e2a2b\] { color: {{ $primaryColor }} !important; }
        .bg-\[\#9E2A2B\], .bg-\[\#9e2a2b\] { background-color: {{ $primaryColor }} !important; }
        .border-\[\#9E2A2B\], .border-\[\#9e2a2b\] { border-color: {{ $primaryColor }} !important; }
        .hover\:border-\[\#9E2A2B\]:hover, .hover\:border-\[\#9e2a2b\]:hover { border-color: {{ $primaryColor }} !important; }
        .hover\:bg-\[\#9E2A2B\]:hover, .hover\:bg-\[\#9e2a2b\]:hover { background-color: {{ $primaryColor }} !important; }
        .hover\:text-\[\#9E2A2B\]:hover, .hover\:text-\[\#9e2a2b\]:hover { color: {{ $primaryColor }} !important; }
        .focus\:border-\[\#9E2A2B\]:focus, .focus\:border-\[\#9e2a2b\]:focus { border-color: {{ $primaryColor }} !important; }
        .border-\[\#9E2A2B\]\/40 { border-color: rgba({{ $primaryRgb }}, 0.4) !important; }
        .hover\:border-\[\#9E2A2B\]\/40:hover { border-color: rgba({{ $primaryRgb }}, 0.4) !important; }
    </style>

    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#16100E',
                        'navy-light': '#231B19',
                        orange: '{{ $primaryColor }}',
                        'orange-dark': '{{ $primaryColor }}',
                        primary: '{{ $primaryColor }}',
                        indigo: '#1A355E',
                        teal: '#1A355E',
                        light: '#F6F3EC',
                        yellow: '#DCA134',
                        success: '#25D366',
                        muted: '#6B7A8D',
                        border: 'rgba(22, 16, 14, 0.08)'
                    },
                    fontFamily: {
                        display: ['Outfit', 'sans-serif'],
                        body: ['Inter', 'sans-serif']
                    },
                    borderRadius: {
                        'custom': '10px',
                        'custom-sm': '6px'
                    }
                }
            }
        }
    </script>
    @yield('extra_head')
</head>

<body data-page="@yield('page_name', 'Home')">

    @include('partials.header')

    <div class="page-wrapper">
        @yield('content')
    </div>

    @include('partials.lightbox')
    @include('partials.footer')

    <!-- Floating WhatsApp & Instagram Buttons -->
    <a id="link-float-whatsapp"
        href="{{ $waUrl }}"
        class="wa-float" target="_blank" title="Chat on WhatsApp">
        <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>

    @if(!empty($settings->instagram_link))
    <a id="link-float-instagram" href="{{ $settings->instagram_link }}" target="_blank"
        title="Follow on Instagram" class="ig-float">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
        </svg>
    </a>
    @endif

    <script src="{{ asset('js/carousel.js') }}"></script>
    <script src="{{ asset('js/components.js') }}"></script>
    @yield('extra_js')
</body>

</html>
