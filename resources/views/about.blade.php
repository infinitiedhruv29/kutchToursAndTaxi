@extends('layouts.app')

@section('title', 'About Us — Kutch Tours & Taxi')
@section('canonical', route('about'))
@section('meta_description', 'Learn about Kutch Tours & Taxi, Bhuj premier local tour agency and cab service. Expert drivers, clean AC fleet, and authentic Kutchi hospitality.')
@section('page_name', 'About Us')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
@endphp

<!-- ══ PAGE HERO ══ -->
<div class="page-hero bg-[#16100E] py-14 px-[5%] text-center text-white border-b border-white/10">
    <div class="max-w-[800px] mx-auto relative z-20">
        <div class="breadcrumb flex items-center justify-center gap-2 text-xs text-white/50 mb-3 relative z-20">
            <a id="breadcrumb-link-home" href="{{ route('home') }}" class="text-white/85 hover:text-[#DCA134] underline underline-offset-4 cursor-pointer font-medium transition-colors">Home</a>
            <span class="text-white/40">/</span>
            <span class="text-white/90">About Us</span>
        </div>
        <span class="bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] inline-block py-1 px-3.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            Local Hospitality
        </span>
        <h1 class="font-display text-[2.2rem] md:text-[3.2rem] font-extrabold text-white leading-tight mb-3">
            Welcome to Kutch Tours & Taxi
        </h1>
        <p class="text-white/70 text-sm md:text-base max-w-[560px] mx-auto">
            Born and based in Bhuj, we are proud locals dedicated to providing unforgettable desert journeys and safe, reliable cab hire throughout Gujarat.
        </p>
    </div>
</div>

<!-- ══ STORY & VALUES ══ -->
<section class="section py-16 px-[5%] bg-white border-b border-[#e5e7eb]">
    <div class="max-w-[1240px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-[#9E2A2B] text-xs font-bold uppercase tracking-widest block mb-2">Our Story</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.4rem] font-bold text-[#16100E] leading-snug mb-5">
                Authentic Kutchi Roots, Modern Travel Standards
            </h2>
            <div class="text-[#6B7A8D] text-sm leading-relaxed space-y-4">
                <p>
                    Kutch is not just a destination to us — it is our home. From the blinding white salt crust of the Great Rann to the silent ancient avenues of Dholavira, our region holds some of the most dramatic landscapes and richest artisan traditions in India.
                </p>
                <p>
                    We founded <strong>Kutch Tours & Taxi</strong> to bridge the gap between traditional Kutchi hospitality and reliable, transparent travel logistics. Whether you are landing at Bhuj airport needing a quick sedan cab or bringing an extended family for a 5-day holiday, our mission is to ensure every moment is comfortable, punctual, and safe.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-8">
                <div class="p-4 bg-[#F6F3EC] rounded-[8px] border border-[#e5e7eb]">
                    <div class="font-display font-bold text-2xl text-[#9E2A2B]">1,200+</div>
                    <div class="text-xs text-[#16100E] font-semibold mt-1">Trips Completed</div>
                </div>
                <div class="p-4 bg-[#F6F3EC] rounded-[8px] border border-[#e5e7eb]">
                    <div class="font-display font-bold text-2xl text-[#9E2A2B]">100%</div>
                    <div class="text-xs text-[#16100E] font-semibold mt-1">Verified Local Drivers</div>
                </div>
            </div>
        </div>

        <div>
            <img src="{{ asset('images/gallery-page/culture/rann-utsav-celebrations.webp') }}"
                 onerror="this.src='{{ asset('images/index-page/white-rann-hero.webp') }}'"
                 alt="Authentic Kutchi folk music and desert traditions"
                 class="w-full h-[380px] object-cover rounded-[10px] border border-[#e5e7eb] shadow-sm">
        </div>
    </div>
</section>

<!-- ══ CORE COMMITMENTS ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-xs font-bold uppercase tracking-widest block mb-1">Why Travel With Us</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.4rem] font-bold text-[#16100E]">What Sets Our Service Apart</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white rounded-[10px] border border-[#e5e7eb] shadow-sm">
                <div class="text-3xl mb-3">🛡️</div>
                <h3 class="font-display font-bold text-base text-[#16100E] mb-2">Safety & Vehicle Hygiene</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">
                    Every sedan, SUV, and traveller undergoes periodic maintenance and safety inspection. Air-conditioning, clean seat covers, and ample roof carriers come standard.
                </p>
            </div>

            <div class="p-6 bg-white rounded-[10px] border border-[#e5e7eb] shadow-sm">
                <div class="text-3xl mb-3">🧭</div>
                <h3 class="font-display font-bold text-base text-[#16100E] mb-2">Native Driver Knowledge</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">
                    Our chauffeurs are friendly native Kutchi residents. They assist with White Rann permits, advise on optimal sunset timings, and steer you away from crowded tourist traps.
                </p>
            </div>

            <div class="p-6 bg-white rounded-[10px] border border-[#e5e7eb] shadow-sm">
                <div class="text-3xl mb-3">💬</div>
                <h3 class="font-display font-bold text-base text-[#16100E] mb-2">WhatsApp Convenience</h3>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">
                    No payment gateway lock-in or complicated checkout loops. Simply ping us on WhatsApp to discuss plans, confirm your driver details, and reserve your ride.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ══ CTA BANNER ══ -->
<section class="cta-banner py-14 px-[5%] text-white text-center">
    <div class="max-w-[700px] mx-auto">
        <h3 class="font-display text-2xl font-bold mb-3">Ready to Plan Your Kutch Journey?</h3>
        <p class="text-white/70 text-xs mb-6">
            Get in touch with our Bhuj team today. We're here to answer questions and prepare your itinerary.
        </p>
        <a id="btn-about-wa"
           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to inquire about your Kutch tours and taxi services.') }}"
           target="_blank"
           class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-2.5 px-6 rounded-[6px] font-bold text-xs inline-flex items-center gap-2 transition-colors">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
            <span>Message on WhatsApp</span>
        </a>
    </div>
</section>

@endsection
