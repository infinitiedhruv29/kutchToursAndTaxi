@extends('layouts.app')

@section('title', $package->title . ' — Kutch Tours & Taxi')
@section('canonical', route('package.show', $package->slug))
@section('meta_description', Str::limit(strip_tags($package->description), 160))
@section('page_name', 'Tour Package Details')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
    $bookWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hi! I would like to book the {$package->title}. Please confirm availability for my dates.");
@endphp

<!-- ══ BREADCRUMB & HEADER ══ -->
<div class="bg-[#16100E] text-white py-12 px-[5%] border-b border-white/10">
    <div class="max-w-[1240px] mx-auto">
        <div class="breadcrumb flex items-center gap-2 text-xs text-white/50 mb-3">
            <a href="{{ route('home') }}" class="hover:text-[#DCA134] transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('packages') }}" class="hover:text-[#DCA134] transition-colors">Tour Packages</a>
            <span>/</span>
            <span class="text-white/80">{{ $package->title }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-block bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                    ⏱️ {{ $package->duration ?? '2N / 3D' }}
                </span>
                <h1 class="font-display text-2xl md:text-4xl font-extrabold text-white">{{ $package->title }}</h1>
                @if(!empty($package->highlight))
                    <p class="text-white/70 text-sm mt-1">✦ {{ $package->highlight }}</p>
                @endif
            </div>

            <div class="bg-white/5 border border-white/15 p-4 rounded-[8px] flex items-center gap-6">
                <div>
                    <span class="text-[0.7rem] text-white/60 block uppercase">Price for 2 Persons</span>
                    <span class="text-xl font-bold text-[#DCA134]">₹{{ number_format($package->price_2p ?? 24000) }}</span>
                </div>
                <a id="btn-pkg-show-wa-top"
                   href="{{ $bookWaUrl }}"
                   target="_blank"
                   class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-2.5 px-5 rounded-[6px] font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    <span>Book on WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ══ MAIN CONTENT WRAPPER ══ -->
<div class="py-14 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- LEFT 2 COLUMNS: ITINERARY & DETAILS -->
        <div class="lg:col-span-2 space-y-10">
            <!-- PHOTO GALLERY CAROUSEL / GRID -->
            @if($package->galleryImages->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($package->galleryImages as $img)
                        <div class="relative h-[140px] rounded-[8px] overflow-hidden border border-[#e5e7eb] group cursor-pointer pkg-gallery-item" onclick="openLightbox(this)">
                            <img src="{{ asset($img->image_path) }}" alt="{{ $img->caption ?? $package->title }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                            @if(!empty($img->caption))
                                <span class="dest-name hidden">{{ $img->caption }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-[280px] rounded-[10px] overflow-hidden border border-[#e5e7eb]">
                    <img src="{{ asset($package->image_path ?? 'images/index-page/white-rann-hero.webp') }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- OVERVIEW -->
            <div class="bg-white p-6 rounded-[10px] border border-[#e5e7eb] shadow-sm">
                <h2 class="font-display text-lg font-bold text-[#16100E] mb-3">Tour Overview</h2>
                <div class="text-[#6B7A8D] text-sm leading-relaxed space-y-3">
                    <p>{{ $package->description }}</p>
                </div>
            </div>

            <!-- DAY BY DAY ITINERARY -->
            <div class="bg-white p-6 rounded-[10px] border border-[#e5e7eb] shadow-sm">
                <h2 class="font-display text-xl font-bold text-[#16100E] mb-6">Day-by-Day Detailed Itinerary</h2>

                @if($package->itineraries->count() > 0)
                    <div class="space-y-6">
                        @foreach($package->itineraries as $day)
                            <div class="border-l-2 border-[#9E2A2B] pl-5 relative pb-2">
                                <span class="bg-[#9E2A2B] text-white text-[0.7rem] font-bold px-2.5 py-0.5 rounded-[4px] uppercase tracking-wide inline-block mb-1.5">
                                    Day {{ $day->day_number }}
                                </span>
                                <h3 class="font-display font-bold text-base text-[#16100E] mb-2">{{ $day->title }}</h3>
                                
                                @if(!empty($day->description))
                                    <p class="text-[#6B7A8D] text-xs leading-relaxed mb-3">{{ $day->description }}</p>
                                @endif

                                @if(!empty($day->spots) && is_array($day->spots))
                                    <div class="bg-[#F6F3EC] p-3 rounded-[6px] mb-3">
                                        <span class="text-[0.7rem] font-bold text-[#16100E] uppercase block mb-1.5">Sightseeing Spots Covered:</span>
                                        <ul class="space-y-1 text-xs text-[#16100E]/80">
                                            @foreach($day->spots as $spot)
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-[#9E2A2B] font-bold">✓</span>
                                                    <span>{{ $spot }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!empty($day->note))
                                    <p class="text-[0.75rem] text-[#DCA134] font-medium bg-[#16100E] p-2 rounded-[4px] inline-block">
                                        {{ $day->note }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-[#6B7A8D]">Itinerary details can be customized upon request on WhatsApp.</p>
                @endif
            </div>

            <!-- INCLUSIONS & EXCLUSIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-[10px] border border-[#e5e7eb] shadow-sm">
                    <h3 class="font-display font-bold text-base text-[#16100E] mb-4 flex items-center gap-2 text-green-700">
                        <span>✓</span> Package Inclusions
                    </h3>
                    <ul class="space-y-2 text-xs text-[#16100E]/80">
                        @forelse($package->inclusions as $inc)
                            <li class="flex items-start gap-2"><span>•</span> {{ $inc->content }}</li>
                        @empty
                            <li class="flex items-start gap-2"><span>•</span> Dedicated private AC vehicle with experienced driver</li>
                            <li class="flex items-start gap-2"><span>•</span> Hotel/resort stay with daily breakfast and dinner</li>
                            <li class="flex items-start gap-2"><span>•</span> Bhuj airport/station pickup & drop</li>
                            <li class="flex items-start gap-2"><span>•</span> Toll, parking, and driver allowance</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-[10px] border border-[#e5e7eb] shadow-sm">
                    <h3 class="font-display font-bold text-base text-[#16100E] mb-4 flex items-center gap-2 text-red-700">
                        <span>✕</span> Package Exclusions
                    </h3>
                    <ul class="space-y-2 text-xs text-[#16100E]/80">
                        @forelse($package->exclusions as $exc)
                            <li class="flex items-start gap-2"><span>•</span> {{ $exc->content }}</li>
                        @empty
                            <li class="flex items-start gap-2"><span>•</span> Lunch and personal snacks</li>
                            <li class="flex items-start gap-2"><span>•</span> Monument, museum & entry pass fees</li>
                            <li class="flex items-start gap-2"><span>•</span> Camel rides & adventure activities</li>
                            <li class="flex items-start gap-2"><span>•</span> Personal expenses & handicraft purchases</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR: PRICING & INSTANT BOOKING -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[10px] border border-[#e5e7eb] shadow-sm sticky top-24">
                <span class="text-xs font-bold text-[#9E2A2B] uppercase tracking-wider block mb-1">Pricing Tiers</span>
                <h3 class="font-display text-xl font-bold text-[#16100E] mb-4">Tour Cost Breakdown</h3>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between p-3 bg-[#F6F3EC] rounded-[6px]">
                        <div>
                            <span class="font-bold text-xs text-[#16100E] block">2 Persons (Couple)</span>
                            <span class="text-[0.68rem] text-[#6B7A8D]">Sedan AC Cab + 1 Room</span>
                        </div>
                        <span class="font-bold text-base text-[#16100E]">₹{{ number_format($package->price_2p ?? 24000) }}</span>
                    </div>

                    @if($package->price_4p)
                    <div class="flex items-center justify-between p-3 bg-[#F6F3EC] rounded-[6px]">
                        <div>
                            <span class="font-bold text-xs text-[#16100E] block">4 Persons (Family)</span>
                            <span class="text-[0.68rem] text-[#6B7A8D]">SUV (Ertiga) + 2 Rooms</span>
                        </div>
                        <span class="font-bold text-base text-[#16100E]">₹{{ number_format($package->price_4p) }}</span>
                    </div>
                    @endif

                    @if($package->price_6p)
                    <div class="flex items-center justify-between p-3 bg-[#F6F3EC] rounded-[6px]">
                        <div>
                            <span class="font-bold text-xs text-[#16100E] block">6 Persons (Group)</span>
                            <span class="text-[0.68rem] text-[#6B7A8D]">Toyota Innova + 3 Rooms</span>
                        </div>
                        <span class="font-bold text-base text-[#16100E]">₹{{ number_format($package->price_6p) }}</span>
                    </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <a id="btn-pkg-show-book-wa"
                       href="{{ $bookWaUrl }}"
                       target="_blank"
                       class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white py-3 px-4 rounded-[6px] font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                        <span>Book on WhatsApp</span>
                    </a>

                    <a id="btn-pkg-show-call"
                       href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number ?? '+919904222293') }}"
                       class="w-full border border-[#16100E] text-[#16100E] hover:bg-[#16100E] hover:text-white py-2.5 px-4 rounded-[6px] font-bold text-xs flex items-center justify-center transition-colors">
                        <span>Direct Call: {{ $settings->contact_number ?? '+91 99042 22293' }}</span>
                    </a>
                </div>

                <div class="mt-4 pt-4 border-t border-[#f3f4f6] text-center">
                    <p class="text-[0.72rem] text-[#6B7A8D]">
                        🔒 No booking fees • WhatsApp confirmation • Pay advance securely
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
