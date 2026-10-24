@extends('layouts.app')

@section('title', 'Contact Us & Bookings — Kutch Tours & Taxi')
@section('canonical', route('contact'))
@section('meta_description', 'Contact Kutch Tours & Taxi in Bhuj, Gujarat. Inquire about tour packages, taxi fares, airport pickups, and White Rann permits. Instant WhatsApp chat.')
@section('page_name', 'Contact')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
    $phoneDisplay = $settings->contact_number ?? '+91 99042 22293';
    $phoneTel = preg_replace('/[^0-9+]/', '', $phoneDisplay);
@endphp

<!-- ══ PAGE HERO ══ -->
<div class="page-hero bg-[#16100E] py-14 px-[5%] text-center text-white border-b border-white/10">
    <div class="max-w-[800px] mx-auto relative z-20">
        <div class="breadcrumb flex items-center justify-center gap-2 text-xs text-white/50 mb-3 relative z-20">
            <a id="breadcrumb-link-home" href="{{ route('home') }}" class="text-white/85 hover:text-[#DCA134] underline underline-offset-4 cursor-pointer font-medium transition-colors">Home</a>
            <span class="text-white/40">/</span>
            <span class="text-white/90">Contact Us</span>
        </div>
        <span class="bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] inline-block py-1 px-3.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            24/7 Assistance
        </span>
        <h1 class="font-display text-[2.2rem] md:text-[3.2rem] font-extrabold text-white leading-tight mb-3">
            Get in Touch With Our Team
        </h1>
        <p class="text-white/70 text-sm md:text-base max-w-[560px] mx-auto">
            Have questions about tour packages, cab availability, or road routes? Reach out on WhatsApp or phone for immediate assistance.
        </p>
    </div>
</div>

<!-- ══ CONTACT INFO CARDS & INQUIRY FORM ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- LEFT 1 COLUMN: CONTACT DETAILS -->
        <div class="space-y-6">
            <!-- WHATSAPP FAST CARD -->
            <div class="bg-[#25D366]/10 border border-[#25D366]/30 p-6 rounded-[10px]">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-[#1b7e3e] uppercase tracking-wide block">Fastest Response</span>
                        <h4 class="font-display font-bold text-base text-[#16100E]">WhatsApp Booking</h4>
                    </div>
                </div>
                <p class="text-xs text-[#16100E]/80 mb-4">
                    Send us your travel dates, preferred vehicle, and pickup time. We usually respond within 5–10 minutes.
                </p>
                <a id="btn-contact-wa-direct"
                   href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to inquire about Kutch tour packages and cabs.') }}"
                   target="_blank"
                   class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white py-2.5 px-4 rounded-[6px] font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                    <span>Chat on WhatsApp (+91 {{ $waNumber }})</span>
                </a>
            </div>

            <!-- PHONE CALL CARD -->
            <div class="bg-white border border-[#e5e7eb] p-6 rounded-[10px] shadow-sm">
                <span class="text-xs font-bold text-[#9E2A2B] uppercase tracking-wide block mb-1">Direct Call</span>
                <h4 class="font-display font-bold text-base text-[#16100E] mb-2">Speak to Our Travel Desk</h4>
                <div class="space-y-2 text-xs">
                    <p class="text-[#6B7A8D]">Primary Phone:</p>
                    <a id="link-contact-call-1" href="tel:{{ $phoneTel }}" class="font-bold text-sm text-[#16100E] block hover:text-[#9E2A2B]">{{ $phoneDisplay }}</a>
                    @if(!empty($settings->contact_number_2))
                        <p class="text-[#6B7A8D] mt-2">Secondary Phone:</p>
                        <a id="link-contact-call-2" href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number_2) }}" class="font-bold text-sm text-[#16100E] block hover:text-[#9E2A2B]">{{ $settings->contact_number_2 }}</a>
                    @endif
                </div>
            </div>

            <!-- OFFICE ADDRESS CARD -->
            <div class="bg-white border border-[#e5e7eb] p-6 rounded-[10px] shadow-sm">
                <span class="text-xs font-bold text-[#9E2A2B] uppercase tracking-wide block mb-1">Local Office</span>
                <h4 class="font-display font-bold text-base text-[#16100E] mb-2">Bhuj Headquarters</h4>
                <p class="text-xs text-[#6B7A8D] leading-relaxed mb-3">
                    {{ $settings->address ?? 'Station Road, Near Bus Station, Bhuj, Kutch, Gujarat — 370001' }}
                </p>
                @if(!empty($settings->contact_email))
                    <div class="pt-2 border-t border-[#f3f4f6]">
                        <span class="text-[0.68rem] text-[#6B7A8D] block uppercase">Email Address</span>
                        <a href="mailto:{{ $settings->contact_email }}" class="text-xs font-semibold text-[#16100E] hover:underline">{{ $settings->contact_email }}</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT 2 COLUMNS: INTERACTIVE WHATSAPP INQUIRY GENERATOR -->
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-[10px] border border-[#e5e7eb] shadow-sm">
                <span class="text-xs font-bold text-[#9E2A2B] uppercase tracking-wide block mb-1">Quick Proposal</span>
                <h3 class="font-display font-bold text-xl md:text-2xl text-[#16100E] mb-2">Send an Instant Booking Inquiry</h3>
                <p class="text-[#6B7A8D] text-xs leading-relaxed mb-6">
                    Fill out the quick details below, and click "Send to WhatsApp". It will instantly format your request and open WhatsApp directly. No sign up required!
                </p>

                <form id="contact-form" onsubmit="handleInquirySubmit(event)" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#16100E] mb-1">Your Full Name *</label>
                            <input type="text" id="inq-name" required placeholder="e.g. Ramesh Patel"
                                   class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#16100E] mb-1">Mobile / WhatsApp Number *</label>
                            <input type="tel" id="inq-phone" required placeholder="e.g. +91 98765 43210"
                                   class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#16100E] mb-1">Service Needed *</label>
                            <select id="inq-service" class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]">
                                <option value="Tour Package (2N/3D Kutch Classic)">Tour Package — 2N3D Kutch Classic</option>
                                <option value="Tour Package (3N/4D White Rann & Dholavira)" selected>Tour Package — 3N4D White Rann & Dholavira</option>
                                <option value="Tour Package (4N/5D Kutch Grand Explorer)">Tour Package — 4N5D Kutch Grand Explorer</option>
                                <option value="AC Cab Hire (Swift Dzire 4 Seater)">AC Cab — Swift Dzire (4 Seater)</option>
                                <option value="AC Cab Hire (Maruti Ertiga 6 Seater)">AC Cab — Maruti Ertiga (6 Seater)</option>
                                <option value="AC Cab Hire (Toyota Innova 7 Seater)">AC Cab — Toyota Innova (7 Seater)</option>
                                <option value="Tempo Traveller (12 to 17 Seater)">Group — Force Tempo Traveller / Urbania</option>
                                <option value="Custom Itinerary / Outstation Route">Custom Itinerary / Outstation Cab</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#16100E] mb-1">Approximate Travel Date</label>
                            <input type="text" id="inq-dates" placeholder="e.g. 15th to 18th Dec 2026"
                                   class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#16100E] mb-1">Number of Passengers</label>
                            <input type="number" id="inq-passengers" min="1" max="50" placeholder="e.g. 4 adults, 1 child"
                                   class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#16100E] mb-1">Pickup Location</label>
                            <input type="text" id="inq-pickup" placeholder="e.g. Bhuj Railway Station / Airport"
                                   class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#16100E] mb-1">Special Notes / Requirements</label>
                        <textarea id="inq-notes" rows="3" placeholder="Tell us about train/flight timings, hotel budget preferences, or specific places you wish to visit..."
                                  class="w-full border border-[#e5e7eb] rounded-[6px] p-2.5 text-xs text-[#16100E] outline-none focus:border-[#9E2A2B]"></textarea>
                    </div>

                    <button type="submit"
                            id="btn-submit-inquiry-wa"
                            class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white py-3 px-6 rounded-[6px] font-bold text-sm flex items-center justify-center gap-2 transition-colors cursor-pointer">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                        <span>Send Booking Inquiry to WhatsApp</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>

@endsection

@section('extra_js')
<script>
    function handleInquirySubmit(e) {
        e.preventDefault();
        const name = document.getElementById('inq-name').value;
        const phone = document.getElementById('inq-phone').value;
        const service = document.getElementById('inq-service').value;
        const dates = document.getElementById('inq-dates').value;
        const passengers = document.getElementById('inq-passengers').value;
        const pickup = document.getElementById('inq-pickup').value;
        const notes = document.getElementById('inq-notes').value;

        let message = `*New Booking Inquiry - Kutch Tours & Taxi*\n\n`;
        message += `• *Name:* ${name}\n`;
        message += `• *Contact:* ${phone}\n`;
        message += `• *Service:* ${service}\n`;
        if (dates) message += `• *Dates:* ${dates}\n`;
        if (passengers) message += `• *Passengers:* ${passengers}\n`;
        if (pickup) message += `• *Pickup Location:* ${pickup}\n`;
        if (notes) message += `• *Notes:* ${notes}\n`;

        const waUrl = `https://wa.me/{{ $waNumber }}?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }
</script>
@endsection
