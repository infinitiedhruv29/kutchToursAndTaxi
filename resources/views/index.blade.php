@extends('layouts.app')

@section('title', 'Kutch Tours & Taxi — Rann of Kutch Tour Packages & Cab Booking')
@section('page_name', 'Home')

@section('schema_json')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "TravelAgency",
  "name": "Kutch Tours & Taxi",
  "image": "{{ asset('images/index-page/white-rann-hero.webp') }}",
  "logo": "{{ asset('images/logo.svg') }}",
  "telephone": "{{ $settings->contact_number ?? '+919904222293' }}",
  "priceRange": "$$",
  "address": {
    "@@type": "PostalAddress",
    "streetAddress": "Station Road",
    "addressLocality": "Bhuj",
    "addressRegion": "Gujarat",
    "postalCode": "370001",
    "addressCountry": "IN"
  }
}
</script>
@endsection

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
@endphp

<!-- ══ HERO SECTION ══ -->
<section class="hero section-dark grid grid-cols-1 lg:grid-cols-2 items-center px-[5%] gap-12 min-h-[85vh] pt-12 pb-16 lg:py-16 bg-[#16100E]">
    <div class="hero-left relative z-10 pt-4">
        <div class="hero-badge inline-flex items-center gap-2 bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] py-1.5 px-4 rounded-full text-[0.74rem] font-bold tracking-wider mb-6 uppercase">
            <span class="text-[#9E2A2B] text-[10px]">✦</span> Gujarat's Trusted Travel & Taxi Partner
        </div>
        <h1 class="font-display text-[2.6rem] sm:text-[3.5rem] lg:text-[4.2rem] font-extrabold leading-[1.08] text-white">
            Discover the <span class="accent text-[#9E2A2B] block sm:inline">Magic of Kutch</span>
        </h1>
        <p class="hero-sub text-white/70 text-base leading-[1.75] max-w-[490px] mt-5">
            From the infinite White Salt Desert to UNESCO Harappan ruins at Dholavira and scenic Mandvi beaches — we provide well-maintained AC cabs and memorable holiday tours with trusted local drivers.
        </p>

        <!-- CTAs -->
        <div class="hero-actions flex gap-4 mt-8 flex-wrap">
            <a id="link-hero-explore-packages" href="{{ route('packages') }}" class="btn-primary" style="background:#9E2A2B;color:#fff;padding:12px 24px;border-radius:8px;font-weight:700;font-size:0.92rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                Explore Tour Packages
            </a>
            <a id="link-hero-explore-taxis" href="{{ route('taxi') }}" class="btn-outline-white" style="border:1.5px solid rgba(255,255,255,0.3);color:#fff;padding:12px 24px;border-radius:8px;font-weight:700;font-size:0.92rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                Taxi Rates & Fleet
            </a>
        </div>

        <!-- Quick Contacts -->
        <div class="flex items-center gap-5 mt-6 text-[0.82rem] text-white/60 flex-wrap">
            <a id="link-hero-whatsapp"
                href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to plan a Kutch trip. Please share details.') }}"
                target="_blank" class="flex items-center gap-1.5 text-[#25D366] hover:underline font-semibold">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                <span>Instant WhatsApp Chat</span>
            </a>
            <span class="w-[1px] h-[10px] bg-white/20 hidden sm:inline"></span>
            <span class="text-white/60">Call: {{ $settings->contact_number ?? '+91 99042 22293' }}</span>
        </div>

        <!-- Key Numbers -->
        <div class="hero-stats flex gap-6 sm:gap-10 mt-10">
            <div class="hstat border-l-2 border-[#9E2A2B] pl-3.5">
                <div class="hstat-num font-display text-[1.8rem] font-bold text-white leading-none">1,200+</div>
                <div class="hstat-lbl text-[0.72rem] text-white/60 mt-1 uppercase tracking-wider">Happy Travellers</div>
            </div>
            <div class="hstat border-l-2 border-[#9E2A2B] pl-3.5">
                <div class="hstat-num font-display text-[1.8rem] font-bold text-white leading-none">10+</div>
                <div class="hstat-lbl text-[0.72rem] text-white/60 mt-1 uppercase tracking-wider">AC Cab Fleet</div>
            </div>
            <div class="hstat border-l-2 border-[#9E2A2B] pl-3.5">
                <div class="hstat-num font-display text-[1.8rem] font-bold text-white leading-none">8+ Years</div>
                <div class="hstat-lbl text-[0.72rem] text-white/60 mt-1 uppercase tracking-wider">Local Experience</div>
            </div>
        </div>
    </div>

    <!-- HERO PHOTO GRID -->
    <div class="hero-right relative z-10 w-full grid grid-cols-2 gap-4">
        <div class="hero-gallery-item relative h-[420px] rounded-[12px] overflow-hidden border border-white/10 shadow-lg group cursor-pointer" onclick="openLightbox(this)">
            <img src="{{ asset('images/index-page/white-rann-hero.webp') }}" alt="White Rann of Kutch salt desert" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <span class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-sm text-white text-[0.72rem] font-semibold py-1 px-3 rounded-[6px] border border-white/20">
                🏜️ White Rann of Kutch
            </span>
        </div>
        <div class="flex flex-col gap-4">
            <div class="hero-gallery-item relative h-[202px] rounded-[12px] overflow-hidden border border-white/10 shadow-lg group cursor-pointer" onclick="openLightbox(this)">
                <img src="{{ asset('images/index-page/prag-mahal-new.webp') }}" alt="Prag Mahal Clock Tower Bhuj" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <span class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white text-[0.72rem] font-semibold py-1 px-3 rounded-[6px] border border-white/20">
                    🏛️ Prag Mahal, Bhuj
                </span>
            </div>
            <div class="hero-gallery-item relative h-[202px] rounded-[12px] overflow-hidden border border-white/10 shadow-lg group cursor-pointer" onclick="openLightbox(this)">
                <img src="{{ asset('images/index-page/mandvi-beach-2.webp') }}" alt="Mandvi Beach Coastline" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <span class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white text-[0.72rem] font-semibold py-1 px-3 rounded-[6px] border border-white/20">
                    🌊 Mandvi Beach
                </span>
            </div>
        </div>
    </div>
</section>

<!-- TICKER RIBBON -->
<div class="ticker-wrap bg-[#9E2A2B] py-2.5 overflow-hidden border-y border-[#7F1D1D]">
    <div class="ticker flex whitespace-nowrap [animation:tickerScroll_30s_linear_infinite]">
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">White Rann of Kutch <span class="text-white/40">✦</span></span>
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">UNESCO Dholavira <span class="text-white/40">✦</span></span>
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Mandvi Beach & Palaces <span class="text-white/40">✦</span></span>
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Kalo Dungar (Black Hill) <span class="text-white/40">✦</span></span>
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Road to Heaven Drive <span class="text-white/40">✦</span></span>
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Bhujodi Handicrafts <span class="text-white/40">✦</span></span>
        <span class="text-white/90 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Outstation Cabs to Ahmedabad & Rajkot <span class="text-white/40">✦</span></span>
    </div>
</div>

<!-- ══ TOUR PACKAGES SECTION ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]" id="packages">
    <div class="max-w-[1240px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Authentic Experiences</span>
                <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Top-Rated Kutch Tour Packages</h2>
                <p class="text-[#6B7A8D] text-sm mt-2 max-w-[540px]">All-inclusive holiday packages with private AC transport, comfortable stays, meals, and experienced local guidance.</p>
            </div>
            <a href="{{ route('packages') }}" class="text-[#9E2A2B] font-bold text-sm hover:underline mt-4 md:mt-0 inline-flex items-center gap-1">
                View All Packages →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $pkg)
                <div class="pkg-card bg-white rounded-[10px] border border-[#e5e7eb] shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                    <div class="relative h-[210px] overflow-hidden">
                        <img src="{{ asset($pkg->image_path ?? 'images/index-page/white-rann-hero.webp') }}"
                             alt="{{ $pkg->image_alt ?? $pkg->title }}"
                             class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#16100E]/80 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-[6px]">
                            ⏱️ {{ $pkg->duration ?? '2N / 3D' }}
                        </span>
                        @if($pkg->is_featured)
                        <span class="absolute top-3 right-3 bg-[#DCA134] text-[#16100E] text-[0.7rem] font-bold px-2 py-0.5 rounded-[4px] uppercase tracking-wider">
                            Popular Choice
                        </span>
                        @endif
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-display font-bold text-lg text-[#16100E] mb-2 leading-snug">{{ $pkg->title }}</h3>
                            <p class="text-[#6B7A8D] text-xs leading-relaxed mb-4 line-clamp-2">{{ $pkg->description }}</p>
                            @if(!empty($pkg->highlight))
                                <div class="bg-[#F6F3EC] text-[#9E2A2B] text-xs font-medium px-2.5 py-1.5 rounded-[6px] mb-4">
                                    ★ {{ $pkg->highlight }}
                                </div>
                            @endif
                        </div>
                        <div class="pt-4 border-t border-[#f3f4f6]">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-[0.7rem] text-[#6B7A8D] uppercase block">Starting from</span>
                                    <span class="text-base font-bold text-[#16100E]">₹{{ number_format($pkg->price_2p ?? 24000) }}</span>
                                    <span class="text-[0.7rem] text-[#6B7A8D]">/ 2 Persons</span>
                                </div>
                                <a id="pkg-view-{{ $pkg->slug }}" href="{{ route('package.show', $pkg->slug) }}" class="text-xs font-bold text-[#9E2A2B] hover:underline">
                                    View Itinerary →
                                </a>
                            </div>
                            <a id="pkg-wa-{{ $pkg->slug }}"
                               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book the ' . $pkg->title . '. Please share availability and pricing.') }}"
                               target="_blank"
                               class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white py-2.5 px-4 rounded-[6px] font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                                <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                                <span>Book on WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ POPULAR TAXI ROUTES ══ -->
<section class="section py-16 px-[5%] bg-[#F6F3EC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Reliable Cab Service</span>
                <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Popular Kutch Taxi Routes</h2>
                <p class="text-[#6B7A8D] text-sm mt-1 max-w-[520px]">Fixed and transparent taxi fares for point-to-point transfers and full-day sightseeing with local drivers.</p>
            </div>
            <a href="{{ route('taxi') }}" class="text-[#9E2A2B] font-bold text-sm hover:underline mt-4 md:mt-0 inline-flex items-center gap-1">
                View All Taxi Services →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($routes as $route)
                <div class="route-card bg-white p-5 rounded-[10px] border border-[#e5e7eb] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs text-[#6B7A8D] mb-2">
                            <span>📍 {{ $route->origin_name }} → {{ $route->destination_name }}</span>
                            <span class="font-semibold text-[#16100E]">{{ $route->distance_km ? round($route->distance_km) . ' km' : '' }}</span>
                        </div>
                        <h3 class="font-display font-bold text-base text-[#16100E] mb-2">{{ $route->title }}</h3>
                        <p class="text-[#6B7A8D] text-xs line-clamp-2 leading-relaxed mb-4">{{ $route->short_description }}</p>
                    </div>
                    <div class="pt-3 border-t border-[#f3f4f6] flex items-center justify-between">
                        <div>
                            <span class="text-[0.68rem] text-[#6B7A8D] uppercase block">Round Trip Fare</span>
                            <span class="text-base font-bold text-[#16100E]">₹{{ number_format($route->forward_round_trip_fare ?? $route->forward_one_way_fare) }}</span>
                        </div>
                        <a id="route-wa-{{ $route->slug }}"
                           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book a taxi for ' . $route->title . '. Please share availability.') }}"
                           target="_blank"
                           class="bg-[#16100E] hover:bg-[#9E2A2B] text-white text-xs font-bold py-2 px-3.5 rounded-[6px] transition-colors inline-flex items-center gap-1.5">
                            <span>Book Taxi</span> →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ OUR FLEET SHOWCASE ══ -->
<section class="section py-16 px-[5%] bg-white">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Comfort & Safety</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Our Clean AC Taxi Fleet</h2>
            <p class="text-[#6B7A8D] text-sm mt-2">Well-maintained air-conditioned sedans, SUVs, and luxury Tempo Travellers ready for your journey.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($vehicles as $veh)
                <div class="vehicle-card bg-[#FDFDFC] rounded-[10px] border border-[#e5e7eb] p-4 flex flex-col justify-between hover:border-[#9E2A2B]/40 transition-colors">
                    <div>
                        <div class="h-[150px] rounded-[8px] overflow-hidden bg-[#F6F3EC] mb-3 flex items-center justify-center">
                            <img src="{{ asset($veh->image_path ?? 'images/taxi-page/dzire.webp') }}"
                                 alt="{{ $veh->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex items-center justify-between text-xs text-[#6B7A8D] mb-1">
                            <span class="font-medium text-[#DCA134]">{{ $veh->type->name ?? 'Cab' }}</span>
                            <span>👥 {{ $veh->capacity->label ?? '4 Seater' }}</span>
                        </div>
                        <h4 class="font-display font-bold text-base text-[#16100E] mb-1">{{ $veh->name }}</h4>
                        <p class="text-[#6B7A8D] text-xs mb-3">{{ $veh->notes ?? 'Clean AC cab with carrier' }}</p>
                    </div>
                    <div class="pt-3 border-t border-[#f3f4f6] flex items-center justify-between">
                        <div>
                            <span class="text-[0.68rem] text-[#6B7A8D] block uppercase">Rate / Km</span>
                            <span class="text-sm font-bold text-[#16100E]">₹{{ round($veh->price_per_km ?? 13) }}/km</span>
                        </div>
                        <a id="veh-wa-{{ $veh->slug }}"
                           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to hire ' . $veh->name . ' for a Kutch trip.') }}"
                           target="_blank"
                           class="bg-[#25D366] hover:bg-[#20ba5a] text-white text-xs font-bold py-1.5 px-3 rounded-[6px] transition-colors inline-flex items-center gap-1">
                            Book
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ TOP DESTINATIONS ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Wonders of Kutch</span>
                <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Must-Visit Destinations</h2>
                <p class="text-[#6B7A8D] text-sm mt-1 max-w-[500px]">Explore iconic sights across the vast Kutch landscape from salt pans to royal palaces.</p>
            </div>
            <a href="{{ route('gallery') }}" class="text-[#9E2A2B] font-bold text-sm hover:underline mt-4 md:mt-0 inline-flex items-center gap-1">
                Explore Photo Gallery →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($destinations as $dest)
                <div class="dest-card relative h-[250px] rounded-[10px] overflow-hidden border border-[#e5e7eb] group cursor-pointer dest-gallery-item" onclick="openLightbox(this)">
                    <img src="{{ asset($dest->image_path ?? 'images/index-page/white-rann-hero.webp') }}"
                         alt="{{ $dest->image_alt ?? $dest->name }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <h4 class="font-display font-bold text-base text-white mb-1 dest-name">{{ $dest->name }}</h4>
                        <p class="text-white/70 text-xs line-clamp-2 leading-relaxed">{{ $dest->short_description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ TESTIMONIALS SECTION ══ -->
<section class="section py-16 px-[5%] bg-[#F6F3EC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Traveller Experiences</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">What Our Guests Say</h2>
            <p class="text-[#6B7A8D] text-sm mt-2">Hear directly from families, groups, and solo travellers who explored Kutch with us.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials as $test)
                <div class="bg-white p-6 rounded-[10px] border border-[#e5e7eb] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="text-[#DCA134] text-sm mb-3">★★★★★</div>
                        <p class="text-[#16100E]/80 text-xs leading-relaxed italic mb-4">"{{ $test->review }}"</p>
                    </div>
                    <div class="pt-3 border-t border-[#f3f4f6] flex items-center justify-between">
                        <div>
                            <span class="font-bold text-xs text-[#16100E] block">{{ $test->client_name }}</span>
                            <span class="text-[0.7rem] text-[#6B7A8D]">{{ $test->service_name ?? 'Kutch Tour' }}</span>
                        </div>
                        <span class="text-[0.65rem] bg-[#25D366]/15 text-[#1b7e3e] px-2 py-0.5 rounded-[4px] font-semibold">Verified Guest</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ FAQ SECTION ══ -->
<section class="section py-16 px-[5%] bg-white">
    <div class="max-w-[840px] mx-auto">
        <div class="text-center mb-10">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Clear & Transparent</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.4rem] font-bold text-[#16100E]">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="faq-card border border-[#e5e7eb] rounded-[8px] p-5 bg-[#FDFDFC]">
                    <h4 class="font-display font-bold text-sm text-[#16100E] mb-2 flex items-center justify-between">
                        <span>{{ $faq->question }}</span>
                        <span class="text-[#9E2A2B] text-lg font-light">✦</span>
                    </h4>
                    <div class="text-[#6B7A8D] text-xs leading-relaxed space-y-2">
                        {!! $faq->answer !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ BOTTOM CTA BANNER ══ -->
<section class="cta-banner py-14 px-[5%] bg-[#16100E] text-white border-t border-white/10">
    <div class="max-w-[1000px] mx-auto text-center">
        <span class="text-[#DCA134] text-xs font-bold uppercase tracking-widest block mb-2">Planning a Trip to Kutch?</span>
        <h2 class="font-display text-[2rem] md:text-[3rem] font-extrabold mb-4">Let's Plan Your Perfect Desert Journey</h2>
        <p class="text-white/70 text-sm max-w-[560px] mx-auto mb-8">
            Get instant itinerary quotes, cab availability, and permit guidance directly on WhatsApp in minutes. No booking fees or hidden charges.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a id="btn-bottom-cta-whatsapp"
               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to plan a Kutch trip. Please share details.') }}"
               target="_blank"
               class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-3 px-6 rounded-[8px] font-bold text-sm inline-flex items-center gap-2 transition-colors">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                <span>Chat on WhatsApp</span>
            </a>
            <a id="btn-bottom-cta-call"
               href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number ?? '+919904222293') }}"
               class="bg-white/10 hover:bg-white/20 text-white border border-white/20 py-3 px-6 rounded-[8px] font-bold text-sm inline-flex items-center gap-2 transition-colors">
                <span>Call Us Now</span>
            </a>
        </div>
    </div>
</section>

@endsection
