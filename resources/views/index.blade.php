@extends('layouts.app')

@section('title', 'Kutch Taxi Service & Bhuj Cab Booking — White Rann, Dholavira & Outstation')
@section('page_name', 'Home')

@section('schema_json')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "TravelAgency",
      "@@id": "{{ route('home') }}#agency",
      "name": "Kutch Tours & Taxi",
      "url": "{{ route('home') }}",
      "image": "{{ asset('images/index-page/road_to_heaven_2.webp') }}",
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
      },
      "geo": {
        "@@type": "GeoCoordinates",
        "latitude": 23.2420,
        "longitude": 69.6669
      },
      "areaServed": [
        "Bhuj",
        "Rann of Kutch",
        "Dhordo",
        "Dholavira",
        "Mandvi",
        "Kutch",
        "Gujarat"
      ]
    },
    {
      "@@type": "TaxiService",
      "@@id": "{{ route('home') }}#taxiservice",
      "name": "Kutch Tours & Taxi - Cab Service",
      "provider": {
        "@@id": "{{ route('home') }}#agency"
      },
      "areaServed": [
        "Bhuj Railway Station",
        "Bhuj Airport",
        "Rann of Kutch",
        "Dhordo",
        "Dholavira Road to Heaven",
        "Mandvi",
        "Ahmedabad",
        "Rajkot"
      ],
      "serviceType": "Tourist Taxi, Outstation Cabs, Railway Station & Airport Pickup, Day Sightseeing Rental"
    }@if(isset($faqs) && $faqs->isNotEmpty()),
    {
      "@@type": "FAQPage",
      "@@id": "{{ route('home') }}#faq",
      "mainEntity": [
        @foreach($faqs as $faq)
        {
          "@@type": "Question",
          "name": {!! json_encode($faq->question) !!},
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": {!! json_encode(strip_tags($faq->answer)) !!}
          }
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
    @endif
  ]
}
</script>
@endsection

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
@endphp

<!-- ══ HERO SECTION (ROAD TO HEAVEN CENTERED) ══ -->
<section class="hero-taxi-centered relative min-h-[88vh] flex items-center justify-center px-[5%] text-center overflow-hidden"
         style="background: linear-gradient(180deg, rgba(14, 10, 9, 0.78) 0%, rgba(14, 10, 9, 0.60) 45%, rgba(14, 10, 9, 0.94) 100%), radial-gradient(ellipse at center, rgba(14, 10, 9, 0.35) 0%, rgba(14, 10, 9, 0.88) 100%), url('{{ asset('images/index-page/road_to_heaven_2.webp') }}') center 55% / cover no-repeat;">
    <div class="relative z-10 max-w-[880px] mx-auto flex flex-col items-center">
        <!-- Eyebrow Badge (With ample breathing room below floating navbar) -->
        <div class="hero-badge inline-flex items-center gap-2 bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] py-1.5 px-4 rounded-full text-[0.74rem] font-bold tracking-wider mt-4 sm:mt-6 mb-6 uppercase"
             style="animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;">
            <span class="text-[10px]" style="color: var(--orange);">✦</span> Gujarat's Trusted Travel &amp; Taxi Partner
        </div>

        <!-- H1 Headline (SEO Focused with Kutch Holidays fadeUp animation) -->
        <h1 class="font-display text-[2.4rem] sm:text-[3.4rem] lg:text-[4.2rem] font-extrabold leading-[1.1] text-white"
            style="animation: fadeUp 0.7s 0.1s cubic-bezier(0.16, 1, 0.3, 1) both;">
            Reliable Taxi &amp; Tour Services in <span class="accent" style="color: var(--orange);">Kutch</span>
        </h1>

        <!-- Subheading -->
        <p class="hero-sub text-white/80 text-base sm:text-lg leading-[1.7] max-w-[680px] mt-5"
           style="animation: fadeUp 0.7s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;">
            From Bhuj Railway Station &amp; Airport pick-ups to the infinite White Salt Desert at Dhordo and scenic Road to Heaven at Dholavira — well-maintained AC cabs, verified local drivers, and transparent per-km billing with zero hidden charges.
        </p>

        <!-- Centered CTAs -->
        <div class="hero-actions flex justify-center items-center gap-4 mt-8 flex-wrap"
             style="animation: fadeUp 0.7s 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;">
            <a id="link-hero-whatsapp"
               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book a taxi in Kutch. Please share cab options and fares.') }}"
               target="_blank"
               class="btn-primary"
               style="background:#25D366;color:#fff;padding:13px 28px;border-radius:8px;font-weight:700;font-size:0.95rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                <span>Instant WhatsApp Booking</span>
            </a>
            <a id="link-hero-explore-taxis" href="#taxi-routes" class="btn-outline-white" style="border:1.5px solid rgba(255,255,255,0.3);background:rgba(255,255,255,0.06);color:#fff;padding:13px 24px;border-radius:8px;font-weight:700;font-size:0.95rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;backdrop-filter:blur(6px);">
                View Taxi Routes &amp; Fares
            </a>
            @if($packages->isNotEmpty())
            <a id="link-hero-explore-packages" href="{{ route('packages') }}" class="btn-outline-white" style="display:none;" aria-hidden="true">
                Explore Tour Packages
            </a>
            @endif
        </div>

        <!-- Quick Contacts -->
        <div class="flex items-center justify-center gap-3 sm:gap-5 mt-6 text-[0.84rem] text-white/70 flex-wrap"
             style="animation: fadeUp 0.7s 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;">
            <span class="inline-flex items-center gap-1.5">
                <svg width="15" height="15" fill="currentColor" class="text-[#25D366]" viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                Call: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number ?? '+919904222293') }}" class="text-white font-bold hover:underline">{{ $settings->contact_number ?? '+91 99042 22293' }}</a>
            </span>
            <span class="w-[1px] h-[12px] bg-white/20 hidden sm:inline"></span>
            <span class="text-white/60">24/7 Bhuj Railway Station &amp; Airport Pickup</span>
        </div>

        <!-- Centered Trust Numbers -->
        <div class="hero-stats grid grid-cols-3 gap-2 sm:gap-8 max-w-[620px] mx-auto mt-12 w-full pt-8 border-t border-white/10"
             style="animation: fadeUp 0.7s 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;">
            <div class="hstat text-center">
                <div class="hstat-num font-display text-[1.5rem] sm:text-[2rem] font-extrabold text-white leading-none">10+</div>
                <div class="hstat-lbl text-[0.66rem] sm:text-[0.72rem] text-white/60 mt-1.5 uppercase tracking-wider font-semibold">AC Cab Fleet</div>
            </div>
            <div class="hstat text-center">
                <div class="hstat-num font-display text-[1.5rem] sm:text-[2rem] font-extrabold text-white leading-none">1,200+</div>
                <div class="hstat-lbl text-[0.66rem] sm:text-[0.72rem] text-white/60 mt-1.5 uppercase tracking-wider font-semibold">Happy Travellers</div>
            </div>
            <div class="hstat text-center">
                <div class="hstat-num font-display text-[1.5rem] sm:text-[2rem] font-extrabold text-white leading-none">8+ Years</div>
                <div class="hstat-lbl text-[0.66rem] sm:text-[0.72rem] text-white/60 mt-1.5 uppercase tracking-wider font-semibold">Local Drivers</div>
            </div>
        </div>
    </div>
</section>

<!-- TICKER RIBBON (INFINITE SEAMLESS CONTINUOUS MARQUEE) -->
<div class="ticker-wrap py-2.5 overflow-hidden border-y border-black/20 select-none" style="background:var(--orange);">
    <div class="ticker-track">
        <div class="ticker-group">
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">White Rann of Kutch Taxi <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Road to Heaven Dholavira Cab <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Bhuj Railway Station &amp; Airport Pickup <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Mandvi Beach &amp; Vijay Vilas Palace <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Kalo Dungar (Black Hill) <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Outstation Cabs to Ahmedabad &amp; Rajkot <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Transparent Per-Km Rates <span class="text-white/40">✦</span></span>
        </div>
        <div class="ticker-group" aria-hidden="true">
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">White Rann of Kutch Taxi <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Road to Heaven Dholavira Cab <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Bhuj Railway Station &amp; Airport Pickup <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Mandvi Beach &amp; Vijay Vilas Palace <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Kalo Dungar (Black Hill) <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Outstation Cabs to Ahmedabad &amp; Rajkot <span class="text-white/40">✦</span></span>
            <span class="text-white/95 text-[0.78rem] font-semibold tracking-wider px-6 inline-flex items-center gap-2">Transparent Per-Km Rates <span class="text-white/40">✦</span></span>
        </div>
    </div>
</div>

<!-- ══ POPULAR TAXI ROUTES (PRIMARY) ══ -->
<section class="section py-16 px-[5%] bg-[#F8F7F4]" id="taxi-routes">
    <div class="max-w-[1240px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Fixed &amp; Transparent Fares</span>
                <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Popular Kutch Taxi Routes</h2>
                <p class="text-[#6B7A8D] text-sm mt-1 max-w-[560px]">Fixed and transparent taxi fares for point-to-point transfers and full-day sightseeing with local drivers. Toll and parking at actuals.</p>
            </div>
            <a href="{{ route('taxi') }}" class="text-[#9E2A2B] font-bold text-sm hover:underline mt-4 md:mt-0 inline-flex items-center gap-1">
                View All Taxi Services &amp; Routes →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($routes as $route)
                <div class="route-card bg-white p-6 rounded-[14px] border border-[#e5e7eb] shadow-sm flex flex-col justify-between group">
                    <div>
                        <!-- Route Journey Visual Connector (Dribbble Pattern) -->
                        <div class="bg-[#F6F4EE] rounded-[8px] p-3 mb-4 border border-[#e8e4d9]">
                            <div class="flex items-center justify-between text-xs font-bold text-[#16100E]">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-[#9E2A2B]"></span>
                                    {{ $route->origin_name }}
                                </span>
                                <div class="flex-1 mx-3 flex items-center justify-center relative">
                                    <div class="w-full h-[1.5px] bg-[#DCA134]/40 border-t border-dashed border-[#DCA134]"></div>
                                    <span class="absolute bg-[#F6F4EE] px-2 text-[0.68rem] font-semibold text-[#6B7A8D]">
                                        {{ $route->distance_km ? round($route->distance_km) . ' km' : 'Direct' }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-[#25D366]"></span>
                                    {{ $route->destination_name }}
                                </span>
                            </div>
                        </div>

                        <h3 class="font-display font-bold text-base text-[#16100E] mb-2 leading-snug group-hover:text-[#DCA134] transition-colors">{{ $route->title }}</h3>
                        <p class="text-[#6B7A8D] text-xs line-clamp-2 leading-relaxed mb-4">{{ $route->short_description }}</p>
                    </div>

                    <div class="pt-4 border-t border-[#f3f4f6] flex items-center justify-between">
                        <div>
                            <span class="text-[0.68rem] text-[#6B7A8D] uppercase block font-semibold">Round Trip Fare</span>
                            <span class="text-lg font-extrabold text-[#16100E]">₹{{ number_format($route->forward_round_trip_fare ?? $route->forward_one_way_fare) }}</span>
                            <span class="text-[0.65rem] text-[#6B7A8D] block">AC Cab with Driver</span>
                        </div>
                        <a id="route-wa-{{ $route->slug }}"
                           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book a taxi for ' . $route->title . '. Please share availability and car options.') }}"
                           target="_blank"
                           class="bg-[#16100E] group-hover:bg-[#DCA134] group-hover:text-[#16100E] text-white text-xs font-bold py-2.5 px-4 rounded-[6px] transition-colors inline-flex items-center gap-1.5 shadow-sm">
                            <span>Book Taxi</span> →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ OUR FLEET SHOWCASE (PRIMARY) ══ -->
<section class="section py-16 px-[5%] bg-white" id="fleet">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[620px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Comfort &amp; Safety</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Our Clean AC Taxi Fleet</h2>
            <p class="text-[#6B7A8D] text-sm mt-2">Well-maintained air-conditioned sedans, SUVs, and luxury Tempo Travellers ready for local sightseeing and outstation road trips.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($vehicles as $veh)
                <div class="vehicle-card bg-[#FDFDFC] rounded-[14px] border border-[#e5e7eb] p-5 flex flex-col justify-between group">
                    <div>
                        <!-- Vehicle Image Container with Zoom effect -->
                        <div class="h-[150px] rounded-[10px] overflow-hidden bg-[#F6F4EE] mb-4 flex items-center justify-center p-2 relative">
                            <img src="{{ asset($veh->image_path ?? 'images/taxi-page/dzire.webp') }}"
                                 alt="{{ $veh->name }} AC cab for hire in Kutch"
                                 class="w-full h-full object-contain vehicle-thumb-img transition-transform duration-500 group-hover:scale-108">
                        </div>
                        
                        <!-- Badges -->
                        <div class="flex items-center justify-between text-xs text-[#6B7A8D] mb-1.5">
                            <span class="font-bold text-[#b47a16] uppercase tracking-wide text-[0.7rem] bg-[#DCA134]/15 px-2 py-0.5 rounded-full">{{ $veh->type->name ?? 'Cab' }}</span>
                            <span class="bg-[#F6F4EE] text-[#334155] px-2 py-0.5 rounded text-[0.7rem] font-semibold border border-black/5">👥 {{ $veh->capacity->label ?? '4 Seater' }}</span>
                        </div>
                        
                        <h4 class="font-display font-bold text-base text-[#16100E] mb-1 group-hover:text-[#DCA134] transition-colors">{{ $veh->name }}</h4>
                        <p class="text-[#6B7A8D] text-xs mb-3 leading-relaxed">{{ $veh->notes ?? 'Clean AC cab with luggage carrier and verified driver' }}</p>
                    </div>

                    <div class="pt-3.5 border-t border-[#f3f4f6] flex items-center justify-between">
                        <div>
                            <span class="text-[0.66rem] text-[#6B7A8D] block uppercase font-medium">Rate / Km</span>
                            <span class="text-base font-extrabold text-[#16100E]">₹{{ round($veh->price_per_km ?? 13) }}/km</span>
                        </div>
                        <a id="veh-wa-{{ $veh->slug }}"
                           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to hire ' . $veh->name . ' for a Kutch trip. Please share availability.') }}"
                           target="_blank"
                           class="text-xs font-bold py-2 px-3.5 rounded-[6px] transition-all inline-flex items-center gap-1.5 shadow-sm hover:opacity-90 hover:shadow-md"
                           style="background: var(--orange); color: var(--primary-text);">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>Enquire</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ WHY CHOOSE US (TAXI SERVICE STANDARDS) ══ -->
<section class="section py-16 px-[5%] bg-[#F8F7F4]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[960px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Our Service Standards</span>
            <h2 class="font-display text-[1.8rem] sm:text-[2.2rem] lg:text-[2.6rem] font-bold text-[#16100E] leading-tight">
                Why Choose Us for Your Kutch Taxi Rentals
            </h2>
            <p class="text-[#6B7A8D] text-sm mt-2 max-w-[580px] mx-auto">
                We focus 100% on passenger safety, clean air-conditioned vehicles, punctual drivers, and straightforward transparent billing.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: 24/7 Pickup -->
            <div class="relative min-h-[320px] rounded-[12px] overflow-hidden border border-black/10 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-end p-6">
                <img src="{{ asset('images/gallery-page/bhuj-city/swaminarayan-mandir-bhuj.webp') }}"
                     alt="Bhuj railway station and airport pickup taxi service"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/30 group-hover:via-black/50 transition-colors"></div>
                <div class="relative z-10 text-white">
                    <span class="inline-block text-[0.68rem] font-bold tracking-widest text-[#DCA134] uppercase mb-1.5 font-display">
                        01 • Punctual Pickup
                    </span>
                    <h3 class="font-display font-bold text-lg text-white mb-2 leading-snug">24/7 Bhuj Station Pickup</h3>
                    <p class="text-white/80 text-xs leading-relaxed line-clamp-3">Flight or train arriving late night or early morning? We track schedules and provide on-time greeting right at the exit gate.</p>
                </div>
            </div>

            <!-- Card 2: Local Drivers -->
            <div class="relative min-h-[320px] rounded-[12px] overflow-hidden border border-black/10 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-end p-6">
                <img src="{{ asset('images/index-page/kalo-dungar-new.webp') }}"
                     alt="Experienced local Kutchi driver for White Rann and Dholavira"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/30 group-hover:via-black/50 transition-colors"></div>
                <div class="relative z-10 text-white">
                    <span class="inline-block text-[0.68rem] font-bold tracking-widest text-[#DCA134] uppercase mb-1.5 font-display">
                        02 • Local Expertise
                    </span>
                    <h3 class="font-display font-bold text-lg text-white mb-2 leading-snug">Local Kutchi Drivers</h3>
                    <p class="text-white/80 text-xs leading-relaxed line-clamp-3">Experienced kutchi drivers who know every desert route, best sunset points and authentic Kutchi dining spot.</p>
                </div>
            </div>

            <!-- Card 3: Transparent Fares -->
            <div class="relative min-h-[320px] rounded-[12px] overflow-hidden border border-black/10 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-end p-6">
                <img src="{{ asset('images/kutch_about_road.webp') }}"
                     alt="Transparent per-km taxi rates on Road to Heaven"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/30 group-hover:via-black/50 transition-colors"></div>
                <div class="relative z-10 text-white">
                    <span class="inline-block text-[0.68rem] font-bold tracking-widest text-[#DCA134] uppercase mb-1.5 font-display">
                        03 • Zero Hidden Fees
                    </span>
                    <h3 class="font-display font-bold text-lg text-white mb-2 leading-snug">100% Transparent Fares</h3>
                    <p class="text-white/80 text-xs leading-relaxed line-clamp-3">Fixed point-to-point fares and clear per-km outstation billing, with zero surge during peak festival dates.</p>
                </div>
            </div>

            <!-- Card 4: Sanitized AC Fleet -->
            <div class="relative min-h-[320px] rounded-[12px] overflow-hidden border border-black/10 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-end p-6">
                <img src="{{ asset('images/kutch_taxi_hero.webp') }}"
                     alt="Clean sanitized AC taxi cabs in Kutch"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/30 group-hover:via-black/50 transition-colors"></div>
                <div class="relative z-10 text-white">
                    <span class="inline-block text-[0.68rem] font-bold tracking-widest text-[#DCA134] uppercase mb-1.5 font-display">
                        04 • Comfort &amp; Safety
                    </span>
                    <h3 class="font-display font-bold text-lg text-white mb-2 leading-snug">Sanitized &amp; AC Cabs</h3>
                    <p class="text-white/80 text-xs leading-relaxed line-clamp-3">Mechanically inspected vehicles with working high-cooling AC, luggage roof carriers, and clean comfortable seating.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ TOP DESTINATIONS (AUTO-CHANGING CAROUSEL WITH CRISP SIGNIFICANCE) ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]" id="destinations">
    <div class="max-w-[1240px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
            <div>
                <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Wonders of Kutch</span>
                <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Must-Visit Destinations</h2>
                <p class="text-[#6B7A8D] text-sm mt-1 max-w-[540px]">Explore places from endless salt desert to 5,000-year Harappan citadels.</p>
            </div>
            <a href="{{ route('gallery') }}" class="text-[#9E2A2B] font-bold text-sm hover:underline mt-4 md:mt-0 inline-flex items-center gap-1">
                Explore Photo Gallery →
            </a>
        </div>

        @if($destinations->isNotEmpty())
        <!-- Bulletproof Auto-Advancing Destinations Carousel -->
        <div class="carousel destinations-carousel relative" data-autoplay="4000">
            <div class="car-track">
                @foreach($destinations as $dest)
                    <div class="car-slide relative group">
                        <img src="{{ asset($dest->image_path ?? 'images/index-page/white-rann-hero.webp') }}"
                             alt="{{ $dest->image_alt ?? $dest->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        
                        <!-- Rich Cinematic Gradient Vignette -->
                        <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(22, 16, 14, 0.15) 0%, rgba(22, 16, 14, 0.45) 45%, rgba(22, 16, 14, 0.95) 100%);"></div>
                        
                        <!-- Badge -->
                        <div class="absolute top-5 left-5 z-10">
                            <span class="inline-flex items-center gap-1.5 py-1 px-3.5 rounded-full text-xs font-bold uppercase tracking-wider text-white bg-[#16100E]/75 backdrop-blur-md border border-white/15 shadow-sm">
                                <span style="color: var(--yellow);">✦</span> Must-Visit Landmark
                            </span>
                        </div>

                        <!-- Slide Content: Place Name -->
                        <div class="car-slide-caption absolute bottom-0 left-0 right-0 p-6 sm:p-10 z-10 text-white pointer-events-none">
                            <h3 class="font-display text-2xl sm:text-3xl lg:text-[2.2rem] font-extrabold text-white leading-tight">
                                {{ $dest->name }}
                            </h3>
                            <span class="sr-only">{{ $dest->short_description }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            <button class="car-prev" aria-label="Previous destination">&#10094;</button>
            <button class="car-next" aria-label="Next destination">&#10095;</button>
            <div class="car-dots">
                @foreach($destinations as $dest)
                    <span class="car-dot {{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $loop->iteration }}"></span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- ══ TOUR PACKAGES SECTION (CONDITIONALLY SHOWN ONLY IF PACKAGES EXIST) ══ -->
@if($packages->isNotEmpty())
<section class="section py-16 px-[5%] bg-white border-t border-[#f3f4f6]" id="packages">
    <div class="max-w-[1240px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Holiday Packages</span>
                <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Top-Rated Kutch Tour Packages</h2>
                <p class="text-[#6B7A8D] text-sm mt-2 max-w-[540px]">All-inclusive holiday packages with private AC transport, comfortable stays, meals, and experienced local guidance.</p>
            </div>
            <a href="{{ route('packages') }}" class="text-[#9E2A2B] font-bold text-sm hover:underline mt-4 md:mt-0 inline-flex items-center gap-1">
                View All Packages →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $pkg)
                <!-- Full Card Clickable with Kutch Holidays Hover Physics -->
                <div class="pkg-card bg-white rounded-[16px] border border-[#e5e7eb] shadow-sm flex flex-col group cursor-pointer"
                     onclick="window.location='{{ route('package.show', $pkg->slug) }}'">
                    <div class="relative h-[220px] overflow-hidden bg-[#F6F4EE]">
                        <img src="{{ asset($pkg->image_path ?? 'images/index-page/white-rann-hero.webp') }}"
                             alt="{{ $pkg->image_alt ?? $pkg->title }}"
                             class="pkg-thumb-img w-full h-full object-cover">
                        <span class="absolute top-3.5 left-3.5 bg-[#16100E]/85 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full border border-white/10 shadow-xs">
                            ⏱️ {{ $pkg->duration ?? '2N / 3D' }}
                        </span>
                        @if($pkg->is_featured)
                        <span class="absolute top-3.5 right-3.5 bg-[#DCA134] text-[#16100E] text-[0.72rem] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-xs">
                            ✦ Most Popular
                        </span>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            @if(!empty($pkg->highlight))
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @foreach(explode(',', $pkg->highlight) as $hl)
                                        <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold tracking-wide uppercase"
                                              style="background: rgba(220, 161, 52, 0.12); color: #B37D1D; border: 1px solid rgba(220, 161, 52, 0.28);">
                                            {{ trim($hl) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <h3 class="font-display font-bold text-lg sm:text-xl text-[#16100E] mb-2 leading-snug group-hover:text-[#DCA134] transition-colors">
                                <a href="{{ route('package.show', $pkg->slug) }}">{{ $pkg->title }}</a>
                            </h3>
                            <p class="text-[#6B7A8D] text-xs sm:text-sm leading-relaxed mb-4 line-clamp-2">{{ $pkg->description }}</p>
                        </div>

                        <div class="pt-4 border-t border-[#f3f4f6]">
                            <div class="flex items-center justify-between mb-3.5">
                                <div>
                                    <span class="text-[0.7rem] text-[#6B7A8D] uppercase font-semibold block">Starting from</span>
                                    <span class="text-lg font-extrabold text-[#16100E]">₹{{ number_format($pkg->price_2p ?? 24000) }}</span>
                                    <span class="text-[0.7rem] text-[#6B7A8D]">/ 2 Persons</span>
                                </div>
                                <a id="pkg-view-{{ $pkg->slug }}"
                                   href="{{ route('package.show', $pkg->slug) }}"
                                   class="btn-primary py-2 px-4 text-xs rounded-full font-bold inline-flex items-center gap-1"
                                   style="background:#DCA134;color:#16100E;">
                                    <span>Details</span> →
                                </a>
                            </div>
                            <a id="pkg-wa-{{ $pkg->slug }}"
                               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book the ' . $pkg->title . '. Please share availability and pricing.') }}"
                               target="_blank"
                               onclick="event.stopPropagation();"
                               class="w-full py-2.5 px-4 rounded-[8px] font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-sm hover:opacity-90"
                               style="background: var(--orange); color: var(--primary-text);">
                                <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                                <span>Book Now</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ══ TESTIMONIALS SECTION (ELEVATED GUEST REVIEWS) ══ -->
<section class="section py-16 px-[5%] bg-[#F6F4EE]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Traveller Experiences</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">What Our Guests Say</h2>
            <p class="text-[#6B7A8D] text-sm mt-2">Hear directly from families, groups, and solo travellers who hired our cabs and explored Kutch with us.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials as $test)
                <div class="testimonial-card bg-white p-6 rounded-[14px] border border-[#e5e7eb] shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Rating & Quote Accent -->
                        <div class="flex items-center justify-between mb-3.5">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[#DCA134] text-sm tracking-widest">★★★★★</span>
                                <span class="text-xs font-bold text-[#16100E]">5.0</span>
                            </div>
                            <span class="text-2xl font-serif text-[#DCA134]/30 leading-none select-none">“</span>
                        </div>
                        <p class="text-[#16100E]/85 text-xs sm:text-[0.88rem] leading-relaxed italic mb-5">
                            "{{ $test->review }}"
                        </p>
                    </div>
                    <div class="pt-4 border-t border-[#f3f4f6] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#16100E] text-[#DCA134] font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($test->client_name, 0, 2)) }}
                            </div>
                            <div>
                                <span class="font-display font-bold text-xs sm:text-sm text-[#16100E] block leading-tight">{{ $test->client_name }}</span>
                                <span class="text-[0.68rem] text-[#6B7A8D] font-medium">{{ $test->service_name ?? 'Kutch Taxi Service' }}</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[0.66rem] font-semibold text-[#15803d] bg-[#dcfce7] px-2.5 py-1 rounded-full shrink-0">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Verified Guest
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ FAQ SECTION (INTERACTIVE ACCORDION DROPDOWN) ══ -->
<section class="section py-16 px-[5%] bg-white" id="faq">
    <div class="max-w-[880px] mx-auto">
        <div class="text-center mb-10">
            <span class="text-[#9E2A2B] text-[0.74rem] font-bold uppercase tracking-widest block mb-1">Clear &amp; Transparent</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Frequently Asked Questions</h2>
            <p class="text-[#6B7A8D] text-sm mt-1.5">Common questions about taxi booking, airport pickup, Rann permits, and customized routes.</p>
        </div>

        <div class="faq-list flex flex-col gap-3">
            @foreach($faqs as $faq)
                <div class="faq-item bg-white border border-[#e5e7eb] rounded-[12px] overflow-hidden {{ $loop->first ? 'open' : '' }}">
                    <div id="faq-q-{{ $faq->id }}"
                         class="faq-q px-6 py-5 font-display font-bold text-base sm:text-[1.02rem] text-[#16100E] cursor-pointer flex justify-between items-center select-none"
                         onclick="this.parentElement.classList.toggle('open')">
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background: var(--orange);"></span>
                            {{ $faq->question }}
                        </span>
                    </div>
                    <div class="faq-a px-6 pb-5 pt-1 text-[#4b5563] text-sm sm:text-[0.92rem] leading-relaxed border-t border-[#f3f4f6]">
                        {!! $faq->answer !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ BOTTOM CTA BANNER (UPDATED COPY) ══ -->
<section class="cta-banner py-16 px-[5%] text-white text-center">
    <div class="max-w-[1000px] mx-auto text-center relative z-10">
        <span class="inline-flex items-center gap-1.5 py-1 px-4 rounded-full text-xs font-bold uppercase tracking-widest mb-3 border shadow-sm"
              style="background-color: rgba(var(--primary-rgb, 158, 42, 43), 0.18); border-color: rgba(var(--primary-rgb, 158, 42, 43), 0.4); color: #fff;">
            ✦ Need a Cab in Kutch?
        </span>
        <h2 class="font-display text-[2rem] md:text-[3rem] font-extrabold mb-4">Book Your AC Cab Now</h2>
        <p class="text-white/75 text-sm max-w-[560px] mx-auto mb-8">
            Get instant fare quotes, cab availability, and permit guidance directly on WhatsApp. No booking fees or hidden charges.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a id="btn-bottom-cta-whatsapp"
               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I need a taxi in Kutch. Please share cab options and rates.') }}"
               target="_blank"
               class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-3 px-6 rounded-[8px] font-bold text-sm inline-flex items-center gap-2 transition-colors">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                <span>Chat on WhatsApp</span>
            </a>
            <a id="btn-bottom-cta-call"
               href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number ?? '+919904222293') }}"
               class="text-white border py-3 px-6 rounded-[8px] font-bold text-sm inline-flex items-center gap-2 transition-all hover:bg-white/15"
               style="background-color: rgba(255,255,255,0.08); border-color: rgba(var(--primary-rgb, 158, 42, 43), 0.4);">
                <span>Call Us Now</span>
            </a>
        </div>
    </div>
</section>

@endsection
