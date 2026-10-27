@extends('layouts.app')

@section('title', 'About Us — Kutch Tours & Taxi | Local Bhuj Travel & Cab Experts')
@section('canonical', route('about'))
@section('meta_description', 'Learn about Kutch Tours & Taxi, Bhuj premier local tour agency and cab service. Expert Kutchi drivers, clean AC fleet, transparent fares, and authentic desert hospitality.')
@section('meta_keywords', 'about kutch tours and taxi, tour agency in bhuj, taxi service bhuj, kutch travel experts, local tour guide bhuj, rann of kutch cabs')

@section('og_title', 'About Us — Kutch Tours & Taxi')
@section('og_description', 'Born and based in Bhuj. Reliable AC cabs, expert local drivers, transparent per-km billing, and authentic desert travel guidance.')
@section('og_image', asset('images/kutch_about_culture.webp'))
@section('og_url', route('about'))

@section('schema_json')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "AboutPage",
  "name": "About Us — Kutch Tours & Taxi",
  "description": "Learn about Kutch Tours & Taxi, the premier local tour operator and taxi service in Bhuj, Kutch.",
  "mainEntity": {
    "@@type": "TravelAgency",
    "name": "Kutch Tours & Taxi",
    "telephone": "{{ $settings->phone_number ?? '+91 99042 22293' }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo/logo-gold.svg') }}",
    "priceRange": "₹₹",
    "image": "{{ asset('images/kutch_about_culture.webp') }}",
    "address": {
      "@@type": "PostalAddress",
      "streetAddress": "{{ $settings->address ?? 'Station Road / Airport Area' }}",
      "addressLocality": "Bhuj",
      "addressRegion": "Gujarat",
      "postalCode": "370001",
      "addressCountry": "IN"
    }
  }
}
</script>
@endsection

@section('page_name', 'About Us')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919773239587');
    $phone = $settings->phone_number ?? '+91 99042 22293';
    $email = $settings->email ?? 'info@kutchtoursandtaxi.com';
    $address = $settings->address ?? 'Bhuj, Kutch, Gujarat — 370001';
@endphp

<!-- ══ PAGE HERO ══ -->
<div class="page-hero bg-[#16100E] py-14 px-[5%] text-center text-white border-b border-white/10">
    <div class="max-w-[800px] mx-auto relative z-20">
        <div class="breadcrumb flex items-center justify-center gap-2 text-xs text-white/50 mb-3 relative z-20">
            <a id="breadcrumb-link-home" href="{{ route('home') }}" class="text-white/85 hover:text-[#DCA134] underline underline-offset-4 cursor-pointer font-medium transition-colors">Home</a>
            <span class="text-white/40">/</span>
            <span class="text-white/90">About Us</span>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3"
              style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.16); color: var(--orange); border: 1px solid rgba(var(--orange-rgb, 220, 161, 52), 0.35);">
            ✦ Local Hospitality & Cab Hire
        </span>
        <h1 class="font-display text-[2.2rem] md:text-[3.2rem] font-extrabold text-white leading-tight mb-3">
            Welcome to Kutch Tours & Taxi
        </h1>
        <p class="text-white/70 text-sm md:text-base max-w-[620px] mx-auto leading-relaxed">
            Born and based in Bhuj. We are local drivers and tour curators delivering spotless AC cabs, transparent per-km billing, and memorable desert journeys across Gujarat.
        </p>
    </div>
</div>

<style>
    /* ── High-UX Service & Story Cards ── */
    .about-service-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .about-service-card:hover {
        border-color: var(--orange) !important;
        box-shadow: 0 16px 36px rgba(var(--orange-rgb, 220, 161, 52), 0.14), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transform: translateY(-6px);
    }
    .destination-spot-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .destination-spot-card:hover {
        border-color: var(--orange) !important;
        box-shadow: 0 14px 32px rgba(var(--orange-rgb, 220, 161, 52), 0.12) !important;
        transform: translateY(-5px);
    }
    .destination-spot-card:hover img {
        transform: scale(1.05);
    }
</style>

<!-- ══ WHO WE ARE (STORY & IMAGERY COLLAGE) ══ -->
<section class="section py-16 px-[5%] bg-white border-b border-[#e5e7eb]">
    <div class="max-w-[1240px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Story Copy: Short, Crisp, Human -->
        <div class="lg:col-span-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[0.72rem] font-bold uppercase tracking-wider mb-3 before:content-['✦'] before:text-[0.6rem]"
                  style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                Who We Are
            </span>
            <h2 class="font-display text-[1.85rem] md:text-[2.4rem] font-bold text-[#16100E] leading-snug mb-4">
                Kutch Isn't Just Our Route — It's Our Home
            </h2>
            
            <div class="text-[#4b5563] text-sm leading-relaxed space-y-3.5 mb-6">
                <p>
                    We started <strong>Kutch Tours & Taxi</strong> in Bhuj with a clear principle: travelers visiting Kutch deserve spotless cars, honest per-km taxi rates, and native drivers who treat them like family.
                </p>
                <p>
                    No call-center intermediaries or third-party aggregators. From airport pickups at Bhuj and White Rann permits to the breathtaking 30-km Road to Heaven, we handle every road detail so you can travel with complete ease.
                </p>
            </div>

            <!-- Quick-Glance Local Facts -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                <div class="flex items-start gap-3 p-3.5 bg-[#F6F3EC] rounded-[10px] border border-[#e5e7eb]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.18); color: var(--orange);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#16100E]">Based in Bhuj</div>
                        <div class="text-[0.75rem] text-[#6B7A8D]">Instant airport & station pickups</div>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3.5 bg-[#F6F3EC] rounded-[10px] border border-[#e5e7eb]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.18); color: var(--orange);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"/>
                            <circle cx="7" cy="17" r="2"/>
                            <circle cx="17" cy="17" r="2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#16100E]">Private Fleet</div>
                        <div class="text-[0.75rem] text-[#6B7A8D]">Dzire, Ertiga, Crysta & Travellers</div>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3.5 bg-[#F6F3EC] rounded-[10px] border border-[#e5e7eb]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.18); color: var(--orange);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#16100E]">Native Chauffeurs</div>
                        <div class="text-[0.75rem] text-[#6B7A8D]">100% verified Kutchi drivers</div>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3.5 bg-[#F6F3EC] rounded-[10px] border border-[#e5e7eb]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.18); color: var(--orange);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#16100E]">Fixed Quotation</div>
                        <div class="text-[0.75rem] text-[#6B7A8D]">Zero hidden tolls or driver charges</div>
                    </div>
                </div>
            </div>

            <!-- Action CTAs -->
            <div class="flex items-center gap-3.5 flex-wrap">
                <a id="link-about-taxi" href="{{ route('taxi') }}" 
                   class="btn-primary inline-flex items-center gap-2 text-white font-bold text-xs py-3 px-6 rounded-[8px] transition-all">
                    <span>View Cab Rates</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                @if(\App\Models\Package::where('is_published', true)->exists())
                <a id="link-about-packages" href="{{ route('packages') }}" 
                   class="border border-[#e5e7eb] hover:border-[var(--orange)] text-[#16100E] hover:text-[var(--orange)] bg-white font-bold text-xs py-3 px-6 rounded-[8px] transition-colors">
                    Explore Tour Packages
                </a>
                @endif
            </div>
        </div>

        <!-- Visual Image Composition -->
        <div class="lg:col-span-6 relative">
            <div class="relative max-w-[500px] mx-auto">
                <!-- Primary Main Image: Authentic Kutchi Folk Musicians & Desert Culture -->
                <div class="rounded-[16px] overflow-hidden border border-[#e5e7eb] shadow-md bg-white">
                    <img src="{{ asset('images/gallery-page/culture/rann-utsav-celebrations.webp') }}"
                         onerror="this.src='{{ asset('images/kutch_about_culture.webp') }}'"
                         alt="Authentic Kutchi desert folk musicians and travelers on the White Rann"
                         class="w-full h-[320px] sm:h-[370px] object-cover">
                </div>

                <!-- Secondary Floating Accent Card: Road to Heaven -->
                <div class="hidden sm:block absolute -bottom-6 -left-6 w-[230px] rounded-[14px] overflow-hidden border-4 border-white shadow-xl bg-white z-20">
                    <img src="{{ asset('images/kutch_about_road.webp') }}"
                         onerror="this.src='{{ asset('images/index-page/road_to_heaven_2.webp') }}'"
                         alt="Road to Heaven highway to Dholavira Kutch"
                         class="w-full h-[140px] object-cover">
                    <div class="p-2.5 bg-white text-left">
                        <span class="text-[0.7rem] font-bold text-[#16100E] block leading-tight">Road to Heaven</span>
                        <span class="text-[0.65rem] text-[#6B7A8D]">Daily private cab trips</span>
                    </div>
                </div>

                <!-- Experience Pill Badge -->
                <div class="absolute -top-4 -right-3 sm:-right-4 py-3.5 px-5 rounded-[12px] text-white shadow-lg text-center z-20"
                     style="background: var(--orange);">
                    <div class="font-display font-black text-2xl leading-none">8+</div>
                    <div class="text-[0.68rem] font-semibold text-white/90 uppercase tracking-wider mt-0.5">Years Local<br>Expertise</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ STATS COUNTERS (DARK MIDNIGHT BASE) ══ -->
<section class="py-12 px-[5%]" style="background-color: var(--navy);">
    <div class="max-w-[1240px] mx-auto grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-[12px] text-center border border-white/10" style="background: rgba(255, 255, 255, 0.03);">
            <div class="font-display text-[2.2rem] md:text-[2.6rem] font-extrabold" style="color: var(--orange);">8+</div>
            <div class="text-white font-bold text-xs mt-1">Years in Kutch Travel</div>
            <div class="text-white/60 text-[0.75rem] mt-0.5">Continuous local operations</div>
        </div>

        <div class="p-6 rounded-[12px] text-center border border-white/10" style="background: rgba(255, 255, 255, 0.03);">
            <div class="font-display text-[2.2rem] md:text-[2.6rem] font-extrabold" style="color: var(--orange);">1,200+</div>
            <div class="text-white font-bold text-xs mt-1">Trips & Cabs Dispatched</div>
            <div class="text-white/60 text-[0.75rem] mt-0.5">White Rann, Dholavira & Mandvi</div>
        </div>

        <div class="p-6 rounded-[12px] text-center border border-white/10" style="background: rgba(255, 255, 255, 0.03);">
            <div class="font-display text-[2.2rem] md:text-[2.6rem] font-extrabold" style="color: var(--orange);">100%</div>
            <div class="text-white font-bold text-xs mt-1">Verified Local Drivers</div>
            <div class="text-white/60 text-[0.75rem] mt-0.5">Experienced Bhuj residents</div>
        </div>

        <div class="p-6 rounded-[12px] text-center border border-white/10" style="background: rgba(255, 255, 255, 0.03);">
            <div class="font-display text-[2.2rem] md:text-[2.6rem] font-extrabold" style="color: var(--orange);">4.9 ★</div>
            <div class="text-white font-bold text-xs mt-1">Guest Review Score</div>
            <div class="text-white/60 text-[0.75rem] mt-0.5">Reliable & punctual service</div>
        </div>
    </div>
</section>

<!-- ══ CORE STANDARDS (HIGH-UX CARDS WITH BESPOKE SVG ICONS & WATERMARKS) ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[620px] mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[0.72rem] font-bold uppercase tracking-wider mb-2.5 before:content-['✦'] before:text-[0.6rem]"
                  style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                Our Standards
            </span>
            <h2 class="font-display text-[1.9rem] md:text-[2.5rem] font-bold text-[#16100E] leading-tight">
                Built for Comfort, Trust & Peace of Mind
            </h2>
            <p class="text-[#6B7A8D] text-xs sm:text-sm mt-2">
                We take care of vehicle hygiene, safety, route navigation, and permits so you travel without surprises.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 01: Spotless Cabs -->
            <div class="about-service-card relative bg-white border border-[#e5e7eb] rounded-[16px] p-6 sm:p-7 flex flex-col group">
                <span class="absolute top-5 right-6 font-display font-extrabold text-[3.8rem] text-[#16100E]/[0.06] select-none leading-none group-hover:text-[var(--orange)]/15 transition-colors">
                    01
                </span>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 relative z-10 transition-transform group-hover:scale-105"
                     style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"/>
                        <circle cx="7" cy="17" r="2"/>
                        <circle cx="17" cy="17" r="2"/>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-[1.15rem] text-[#16100E] mb-2">Spotless & Sanitized Cabs</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed mb-5">
                    Clean cabins, strong air-conditioning, and daily maintenance on every Dzire, Ertiga, and Innova Crysta.
                </p>

                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Sanitized seat covers
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Chilled AC
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Luggage roof carrier
                    </span>
                </div>

                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to check taxi availability for my Kutch trip.') }}"
                   class="mt-auto border border-emerald-500/30 hover:border-emerald-500 text-emerald-600 bg-emerald-500/[0.04] hover:bg-[#25D366] hover:text-white py-2 px-3.5 rounded-full font-bold text-[0.78rem] transition-all duration-300 w-full justify-center flex items-center gap-1.5"
                   target="_blank">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Book on WhatsApp
                </a>
            </div>

            <!-- Card 02: Native Drivers -->
            <div class="about-service-card relative bg-white border border-[#e5e7eb] rounded-[16px] p-6 sm:p-7 flex flex-col group">
                <span class="absolute top-5 right-6 font-display font-extrabold text-[3.8rem] text-[#16100E]/[0.06] select-none leading-none group-hover:text-[var(--orange)]/15 transition-colors">
                    02
                </span>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 relative z-10 transition-transform group-hover:scale-105"
                     style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-[1.15rem] text-[#16100E] mb-2">Native Route Masters</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed mb-5">
                    Friendly Bhuj residents who know White Rann permit rules, optimum sunset timings, and local food spots.
                </p>

                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Rann permit guidance
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Sunset spot expertise
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Zero commercial traps
                    </span>
                </div>

                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to hire an experienced local driver for Kutch.') }}"
                   class="mt-auto border border-emerald-500/30 hover:border-emerald-500 text-emerald-600 bg-emerald-500/[0.04] hover:bg-[#25D366] hover:text-white py-2 px-3.5 rounded-full font-bold text-[0.78rem] transition-all duration-300 w-full justify-center flex items-center gap-1.5"
                   target="_blank">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Book on WhatsApp
                </a>
            </div>

            <!-- Card 03: Honest Pricing -->
            <div class="about-service-card relative bg-white border border-[#e5e7eb] rounded-[16px] p-6 sm:p-7 flex flex-col group">
                <span class="absolute top-5 right-6 font-display font-extrabold text-[3.8rem] text-[#16100E]/[0.06] select-none leading-none group-hover:text-[var(--orange)]/15 transition-colors">
                    03
                </span>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 relative z-10 transition-transform group-hover:scale-105"
                     style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <path d="M8 10h8"/>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-[1.15rem] text-[#16100E] mb-2">Zero Hidden Charges</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed mb-5">
                    Transparent per-km fares with toll, parking, and driver allowance quoted upfront in writing.
                </p>

                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Fixed per-km billing
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Upfront toll estimates
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Zero surge pricing
                    </span>
                </div>

                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! Please share transparent cab rates for my Kutch travel.') }}"
                   class="mt-auto border border-emerald-500/30 hover:border-emerald-500 text-emerald-600 bg-emerald-500/[0.04] hover:bg-[#25D366] hover:text-white py-2 px-3.5 rounded-full font-bold text-[0.78rem] transition-all duration-300 w-full justify-center flex items-center gap-1.5"
                   target="_blank">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Book on WhatsApp
                </a>
            </div>

            <!-- Card 04: Direct WhatsApp Support -->
            <div class="about-service-card relative bg-white border border-[#e5e7eb] rounded-[16px] p-6 sm:p-7 flex flex-col group">
                <span class="absolute top-5 right-6 font-display font-extrabold text-[3.8rem] text-[#16100E]/[0.06] select-none leading-none group-hover:text-[var(--orange)]/15 transition-colors">
                    04
                </span>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 relative z-10 transition-transform group-hover:scale-105"
                     style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-[1.15rem] text-[#16100E] mb-2">Direct WhatsApp Dispatch</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed mb-5">
                    Real human assistance before, during, and after your trip. Driver contact details shared right away.
                </p>

                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Quick response
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Driver number shared
                    </span>
                    <span class="text-[0.7rem] text-[#4b5563] bg-[#F6F3EC] px-2.5 py-1 rounded-md font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--orange);"></span>
                        Live journey support
                    </span>
                </div>

                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to inquire about Kutch tour and cab bookings.') }}"
                   class="mt-auto border border-emerald-500/30 hover:border-emerald-500 text-emerald-600 bg-emerald-500/[0.04] hover:bg-[#25D366] hover:text-white py-2 px-3.5 rounded-full font-bold text-[0.78rem] transition-all duration-300 w-full justify-center flex items-center gap-1.5"
                   target="_blank">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Book on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ══ HOW IT WORKS (SIMPLE 3-STEP JOURNEY) ══ -->
<section class="section py-16 px-[5%] bg-white border-t border-b border-[#e5e7eb]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[0.72rem] font-bold uppercase tracking-wider mb-2 before:content-['✦'] before:text-[0.6rem]"
                  style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.12); color: var(--orange);">
                Easy Booking
            </span>
            <h2 class="font-display text-[1.85rem] md:text-[2.3rem] font-bold text-[#16100E]">
                How Booking Works in 3 Quick Steps
            </h2>
            <p class="text-[#6B7A8D] text-xs sm:text-sm mt-1.5">No lengthy account creation or checkout forms.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="p-6 bg-[#FDFDFC] border border-[#e5e7eb] rounded-[14px] relative">
                <span class="inline-block px-3 py-1 rounded-md text-xs font-bold mb-4"
                      style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.15); color: var(--orange);">
                    Step 01
                </span>
                <h3 class="font-display font-bold text-base text-[#16100E] mb-1.5">Share Your Travel Dates</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">
                    Message us on WhatsApp with your Bhuj arrival details, passenger count, and destinations you want to visit.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="p-6 bg-[#FDFDFC] border border-[#e5e7eb] rounded-[14px] relative">
                <span class="inline-block px-3 py-1 rounded-md text-xs font-bold mb-4"
                      style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.15); color: var(--orange);">
                    Step 02
                </span>
                <h3 class="font-display font-bold text-base text-[#16100E] mb-1.5">Get a Clear Written Quote</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">
                    We suggest suitable vehicles (Sedan, Ertiga, or Crysta) with transparent per-km or fixed package pricing.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="p-6 bg-[#FDFDFC] border border-[#e5e7eb] rounded-[14px] relative">
                <span class="inline-block px-3 py-1 rounded-md text-xs font-bold mb-4"
                      style="background: rgba(var(--orange-rgb, 220, 161, 52), 0.15); color: var(--orange);">
                    Step 03
                </span>
                <h3 class="font-display font-bold text-base text-[#16100E] mb-1.5">Meet Your Chauffeur & Enjoy</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">
                    Your driver welcomes you on time at Bhuj railway station or airport. Relax and immerse yourself in Kutch.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection

