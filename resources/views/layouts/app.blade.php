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

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#16100E',
                        'navy-light': '#231B19',
                        orange: '#9E2A2B',
                        'orange-dark': '#7F1D1D',
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
