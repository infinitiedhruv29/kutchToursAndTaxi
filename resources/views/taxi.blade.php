@extends('layouts.app')

@section('title', 'Affordable Taxi Services in Kutch & Bhuj — Cab Booking | Kutch Tours & Taxi')
@section('canonical', route('taxi'))
@section('meta_description', 'Reliable and affordable taxi services in Kutch & Bhuj. Best rates for outstation tours, Bhuj airport transfers & local sightseeing. Private AC cabs. Book now on WhatsApp!')
@section('meta_keywords', 'taxi service in kutch, taxi in bhuj, kutch cab booking, bhuj airport taxi, car rental kutch, outstation cabs bhuj, bhuj to ahmedabad taxi')

@section('page_name', 'Taxi Services')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
@endphp

<!-- ══ PAGE HERO ══ -->
<div class="page-hero bg-[#16100E] py-14 px-[5%] text-center text-white border-b border-white/10">
    <div class="max-w-[800px] mx-auto">
        <div class="breadcrumb flex items-center justify-center gap-2 text-xs text-white/50 mb-3">
            <a href="{{ route('home') }}" class="hover:text-[#DCA134] transition-colors">Home</a>
            <span>/</span>
            <span class="text-white/80">Taxi Services</span>
        </div>
        <span class="bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] inline-block py-1 px-3.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            Reliable & Comfortable
        </span>
        <h1 class="font-display text-[2.2rem] md:text-[3.2rem] font-extrabold text-white leading-tight mb-3">
            Taxi Services in Kutch & Bhuj
        </h1>
        <p class="text-white/70 text-sm md:text-base max-w-[560px] mx-auto">
            City rides, outstation trips, Bhuj airport/railway transfers & desert sightseeing with experienced local drivers and comfortable AC cabs.
        </p>
    </div>
</div>

<!-- ══ PROMISE & INTRO ══ -->
<section class="section py-14 px-[5%] bg-white border-b border-[#e5e7eb]">
    <div class="max-w-[1240px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-[#9E2A2B] text-xs font-bold uppercase tracking-widest block mb-2">Our Promise</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.4rem] font-bold text-[#16100E] leading-snug mb-4">
                Comfortable Rides with Experienced Local Drivers
            </h2>
            <p class="text-[#6B7A8D] text-sm leading-relaxed mb-6">
                All our vehicles are thoroughly sanitized, air-conditioned, and maintained for desert and highway driving. Our local Kutchi drivers know every route, viewpoint, and cultural hotspot to ensure a smooth, safe journey for your family.
            </p>
            <div class="flex gap-4 flex-wrap">
                <a id="link-taxi-intro-whatsapp"
                   href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I need a taxi in Kutch. Please share details.') }}"
                   class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-3 px-6 rounded-[8px] font-bold text-xs inline-flex items-center gap-2 transition-colors"
                   target="_blank">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    <span>Book on WhatsApp</span>
                </a>
                <a id="link-taxi-intro-call"
                   href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number ?? '+919904222293') }}"
                   class="border border-[#16100E] text-[#16100E] hover:bg-[#16100E] hover:text-white py-3 px-6 rounded-[8px] font-bold text-xs transition-colors">
                    <span>Call: {{ $settings->contact_number ?? '+91 99042 22293' }}</span>
                </a>
            </div>
        </div>
        <div>
            <img src="{{ asset('images/kutch_taxi_hero.webp') }}"
                 alt="Comfortable AC taxi cab service in Kutch Bhuj"
                 class="w-full h-[320px] object-cover rounded-[10px] border border-[#e5e7eb] shadow-sm">
        </div>
    </div>
</section>

<!-- ══ FLEET WITH INTERACTIVE FILTERS ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-10">
            <span class="text-[#9E2A2B] text-xs font-bold uppercase tracking-widest block mb-1">Our Fleet</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Choose Your Rental Cab</h2>
            <p class="text-[#6B7A8D] text-sm mt-2">Filter by passenger capacity or vehicle category to find the ideal car for your group.</p>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white p-4 rounded-[8px] border border-[#e5e7eb] mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Capacity Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-[#16100E] uppercase mr-1">Capacity:</span>
                <button class="filter-cap-btn py-1 px-3.5 rounded-[6px] text-xs font-semibold border border-[#9E2A2B] bg-[#9E2A2B] text-white cursor-pointer transition-colors active" onclick="filterByCapacity('all', this)">All</button>
                @foreach($capacities as $cap)
                    <button class="filter-cap-btn py-1 px-3.5 rounded-[6px] text-xs font-semibold border border-[#e5e7eb] bg-white text-[#6B7A8D] hover:border-[#9E2A2B] hover:text-[#9E2A2B] cursor-pointer transition-colors" onclick="filterByCapacity('{{ $cap->passenger_capacity }}', this)">{{ $cap->label }}</button>
                @endforeach
            </div>

            <!-- Type Dropdown -->
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-[#16100E] uppercase">Car Type:</span>
                <select id="vehicle-type-filter" class="border border-[#e5e7eb] rounded-[6px] py-1.5 px-3 text-xs font-medium text-[#16100E] outline-none" onchange="filterByType(this.value)">
                    <option value="all">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Vehicle Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="vehicles-container">
            @foreach($vehicles as $veh)
                <div class="vehicle-item bg-white rounded-[10px] border border-[#e5e7eb] p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow"
                     data-capacity="{{ $veh->capacity->passenger_capacity ?? 4 }}"
                     data-type="{{ $veh->vehicle_type_id }}">
                    <div>
                        <div class="h-[160px] rounded-[6px] overflow-hidden bg-[#F6F3EC] mb-3 flex items-center justify-center">
                            <img src="{{ asset($veh->image_path ?? 'images/taxi-page/dzire.webp') }}"
                                 alt="{{ $veh->image_alt ?? $veh->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex items-center justify-between text-xs text-[#6B7A8D] mb-1">
                            <span class="font-bold text-[#DCA134] uppercase tracking-wider text-[0.7rem]">{{ $veh->type->name ?? 'Cab' }}</span>
                            <span>👥 {{ $veh->capacity->label ?? '4 Seater' }}</span>
                        </div>
                        <h3 class="font-display font-bold text-base text-[#16100E] mb-1">{{ $veh->name }}</h3>
                        <p class="text-[#6B7A8D] text-xs leading-relaxed mb-4">{{ $veh->notes ?? 'Full AC, carrier luggage space' }}</p>
                    </div>

                    <div class="pt-3 border-t border-[#f3f4f6]">
                        <div class="flex items-center justify-between mb-3 text-xs">
                            <div>
                                <span class="text-[0.68rem] text-[#6B7A8D] block uppercase">Rate per Km</span>
                                <span class="text-base font-bold text-[#16100E]">₹{{ round($veh->price_per_km ?? 13) }}/km</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[0.68rem] text-[#6B7A8D] block uppercase">Min Daily</span>
                                <span class="text-xs font-semibold text-[#16100E]">{{ $veh->min_km_per_day ?? 300 }} km/day</span>
                            </div>
                        </div>

                        <a id="btn-book-veh-{{ $veh->slug }}"
                           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book ' . $veh->name . ' for a Kutch trip. Please share availability.') }}"
                           target="_blank"
                           class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white py-2 px-3 rounded-[6px] font-bold text-xs flex items-center justify-center gap-1.5 transition-colors">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                            <span>Book on WhatsApp</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ POPULAR ROUTE FARES TABLE ══ -->
<section class="section py-16 px-[5%] bg-[#F6F3EC]">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[640px] mx-auto mb-10">
            <span class="text-[#9E2A2B] text-xs font-bold uppercase tracking-widest block mb-1">Fixed Fares</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.6rem] font-bold text-[#16100E]">Popular Kutch Taxi Route Rates</h2>
            <p class="text-[#6B7A8D] text-sm mt-2">Transparent pricing with no unexpected extras. Fares are for standard Sedan / SUV cabs with driver.</p>
        </div>

        <div class="bg-white rounded-[10px] border border-[#e5e7eb] shadow-sm overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#16100E] text-white uppercase tracking-wider text-[0.72rem]">
                        <th class="py-3.5 px-5 font-bold">Route Destination</th>
                        <th class="py-3.5 px-5 font-bold">One-Way Distance</th>
                        <th class="py-3.5 px-5 font-bold">Estimated Time</th>
                        <th class="py-3.5 px-5 font-bold">One-Way Fare</th>
                        <th class="py-3.5 px-5 font-bold">Round Trip Fare</th>
                        <th class="py-3.5 px-5 font-bold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f3f4f6]">
                    @foreach($routes as $route)
                    <tr class="hover:bg-[#FDFDFC] transition-colors">
                        <td class="py-4 px-5">
                            <span class="font-bold text-[#16100E] text-sm block">{{ $route->title }}</span>
                            <span class="text-[#6B7A8D] text-[0.7rem]">{{ $route->short_description }}</span>
                        </td>
                        <td class="py-4 px-5 font-semibold text-[#16100E]">{{ round($route->distance_km ?? 0) }} km</td>
                        <td class="py-4 px-5 text-[#6B7A8D]">{{ round(($route->estimated_duration_minutes ?? 60) / 60, 1) }} hrs</td>
                        <td class="py-4 px-5 font-bold text-[#16100E]">₹{{ number_format($route->forward_one_way_fare ?? 0) }}</td>
                        <td class="py-4 px-5 font-bold text-[#9E2A2B]">₹{{ number_format($route->forward_round_trip_fare ?? 0) }}</td>
                        <td class="py-4 px-5 text-center">
                            <a id="tbl-book-route-{{ $route->slug }}"
                               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I want to book a taxi for ' . $route->title . '. Please share details.') }}"
                               target="_blank"
                               class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-1.5 px-3 rounded-[6px] font-bold text-xs inline-flex items-center gap-1 transition-colors">
                                Book
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ══ WHY BOOK WITH US ══ -->
<section class="section py-16 px-[5%] bg-white">
    <div class="max-w-[1240px] mx-auto">
        <div class="text-center max-w-[600px] mx-auto mb-12">
            <span class="text-[#9E2A2B] text-xs font-bold uppercase tracking-widest block mb-1">Our Standards</span>
            <h2 class="font-display text-[1.8rem] md:text-[2.4rem] font-bold text-[#16100E]">Why Choose Our Cab Services?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-5 rounded-[8px] border border-[#e5e7eb] bg-[#FDFDFC]">
                <div class="text-2xl mb-2">🚗</div>
                <h4 class="font-display font-bold text-sm text-[#16100E] mb-1">Clean & Sanitized Fleet</h4>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">Spotless interior, chilled air-conditioning, and luggage carriers on all outstation cabs.</p>
            </div>
            <div class="p-5 rounded-[8px] border border-[#e5e7eb] bg-[#FDFDFC]">
                <div class="text-2xl mb-2">🧭</div>
                <h4 class="font-display font-bold text-sm text-[#16100E] mb-1">Local Route Masters</h4>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">Experienced native drivers with full knowledge of Rann routes, border checkpoints & timing.</p>
            </div>
            <div class="p-5 rounded-[8px] border border-[#e5e7eb] bg-[#FDFDFC]">
                <div class="text-2xl mb-2">💰</div>
                <h4 class="font-display font-bold text-sm text-[#16100E] mb-1">Honest & Transparent</h4>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">Clear per-km rates with zero hidden surge fees. What we quote on WhatsApp is what you pay.</p>
            </div>
            <div class="p-5 rounded-[8px] border border-[#e5e7eb] bg-[#FDFDFC]">
                <div class="text-2xl mb-2">⚡</div>
                <h4 class="font-display font-bold text-sm text-[#16100E] mb-1">Instant WhatsApp Booking</h4>
                <p class="text-xs text-[#6B7A8D] leading-relaxed">No complicated app downloads or signups. Message us your travel dates and book in 2 minutes.</p>
            </div>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
    let activeCapacity = 'all';
    let activeType = 'all';

    function filterByCapacity(cap, btnEl) {
        activeCapacity = cap;
        document.querySelectorAll('.filter-cap-btn').forEach(b => {
            b.classList.remove('bg-[#9E2A2B]', 'text-white', 'border-[#9E2A2B]');
            b.classList.add('bg-white', 'text-[#6B7A8D]', 'border-[#e5e7eb]');
        });
        btnEl.classList.remove('bg-white', 'text-[#6B7A8D]', 'border-[#e5e7eb]');
        btnEl.classList.add('bg-[#9E2A2B]', 'text-white', 'border-[#9E2A2B]');
        applyFilters();
    }

    function filterByType(typeId) {
        activeType = typeId;
        applyFilters();
    }

    function applyFilters() {
        const items = document.querySelectorAll('.vehicle-item');
        items.forEach(item => {
            const cap = item.getAttribute('data-capacity');
            const type = item.getAttribute('data-type');
            
            const matchCap = (activeCapacity === 'all' || activeCapacity === cap);
            const matchType = (activeType === 'all' || activeType === type);

            if (matchCap && matchType) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endsection
