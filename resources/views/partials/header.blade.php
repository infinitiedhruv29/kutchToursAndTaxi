@php
    $settings = $settings ?? \App\Models\Setting::current();
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hi! I am interested in Kutch Tours & Taxi services. Please share details.");
@endphp

<!-- MAIN NAVIGATION -->
<nav class="nav" id="mainNav">
    <a href="{{ route('home') }}" class="brand-link" style="display:flex;align-items:center;text-decoration:none;">
        @if (!empty($settings->logo_path) && file_exists(public_path($settings->logo_path)))
            <img src="{{ asset($settings->logo_path) }}" alt="Kutch Tours & Taxi" class="h-[44px] w-auto">
        @else
            <svg width="200" height="42" viewBox="0 0 200 42" xmlns="http://www.w3.org/2000/svg">
                <rect width="36" height="36" x="0" y="3" rx="6" fill="#9E2A2B" />
                <text x="7" y="27" font-family="Georgia,serif" font-size="20" font-weight="900" fill="white">K</text>
                <circle cx="29" cy="9" r="4" fill="#DCA134" />
                <path d="M4 33 Q11 25 18 33 Q25 25 32 33" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" fill="none" />
                <text x="44" y="19" font-family="'Outfit', sans-serif" font-size="14" font-weight="700" fill="white" letter-spacing="1.2">KUTCH</text>
                <text x="44" y="33" font-family="'Inter', sans-serif" font-size="9.5" font-weight="600" fill="#DCA134" letter-spacing="2">TOURS & TAXI</text>
            </svg>
        @endif
    </a>

    <div class="nav-links">
        <a id="nav-link-home" href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a id="nav-link-packages" href="{{ route('packages') }}" class="{{ request()->routeIs('packages') || request()->routeIs('package.show') ? 'active' : '' }}">Tour Packages</a>
        <a id="nav-link-taxi" href="{{ route('taxi') }}" class="{{ request()->routeIs('taxi') ? 'active' : '' }}">Taxi Services</a>
        <a id="nav-link-gallery" href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
        <a id="nav-link-about" href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
        <a id="nav-link-contact" href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        <a id="nav-link-enquire-whatsapp" href="{{ $waUrl }}" class="nav-cta" target="_blank" style="display:inline-flex;align-items:center;gap:6px;">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
            <span>Book on WhatsApp</span>
        </a>
    </div>

    <div class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></div>
</nav>

<!-- MOBILE SLIDEOUT MENU -->
<div class="nav-mobile" id="navMobile">
    <a id="mob-link-home" href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
    <a id="mob-link-packages" href="{{ route('packages') }}" class="{{ request()->routeIs('packages') || request()->routeIs('package.show') ? 'active' : '' }}">Tour Packages</a>
    <a id="mob-link-taxi" href="{{ route('taxi') }}" class="{{ request()->routeIs('taxi') ? 'active' : '' }}">Taxi Services</a>
    <a id="mob-link-gallery" href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
    <a id="mob-link-about" href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
    <a id="mob-link-contact" href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    <a id="mob-link-enquire-whatsapp" href="{{ $waUrl }}" class="mob-cta" target="_blank" style="display:flex;align-items:center;justify-content:center;gap:8px;">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
        <span>Enquire on WhatsApp</span>
    </a>
</div>
