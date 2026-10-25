@extends('layouts.app')

@section('title', 'Kutch Tour Packages | 2N3D, 3N4D, 4N5D Holiday Packages — Kutch Tours & Taxi')
@section('canonical', route('packages'))
@section('meta_description', 'Book best-selling Kutch holiday tour packages starting ₹24,000. All-inclusive 2N3D, 3N4D, and 4N5D holiday packages to White Rann, Dholavira & Mandvi.')
@section('meta_keywords', 'Kutch Tour Packages, Kutch tour package, Kutch holiday packages, 2 Nights 3 Days Kutch Tour, 3 Nights 4 Days Kutch, 4 Nights 5 Days Kutch')

@section('schema_json')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ route('home') }}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "Tour Packages",
          "item": "{{ route('packages') }}"
        }
      ]
    },
    {
      "@@type": "ItemList",
      "name": "Kutch Tour Packages",
      "itemListElement": [
        @foreach($packages as $index => $pkg)
        {
          "@@type": "ListItem",
          "position": {{ $index + 1 }},
          "name": {!! json_encode($pkg->title) !!},
          "url": "{{ route('package.show', $pkg->slug) }}"
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
  ]
}
</script>
@endsection

@section('page_name', 'Tour Packages')

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
            <span class="text-white/90">Tour Packages</span>
        </div>
        <span class="bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] inline-block py-1 px-3.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            Handcrafted Itineraries
        </span>
        <h1 class="font-display text-[2.2rem] md:text-[3.2rem] font-extrabold text-white leading-tight mb-3">
            Kutch Holiday Tour Packages
        </h1>
        <p class="text-white/70 text-sm md:text-base max-w-[560px] mx-auto">
            Explore the White Salt Desert, UNESCO Dholavira, holy western shrines, and serene beaches with our all-inclusive private car packages.
        </p>
    </div>
</div>

<!-- ══ PACKAGES LISTING ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto">
        <style>
            .pkg-card {
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .pkg-card:hover {
                border-color: var(--orange) !important;
                box-shadow: 0 16px 36px rgba(var(--orange-rgb, 220, 161, 52), 0.14), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
                transform: translateY(-6px);
            }
            .pkg-card .pkg-img-wrapper img,
            .pkg-card .relative img {
                transition: transform 0.4s ease;
            }
            .pkg-card:hover .pkg-img-wrapper img,
            .pkg-card:hover .relative img {
                transform: scale(1.05);
            }
        </style>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $pkg)
                <div class="pkg-card bg-white rounded-[14px] border border-[#e5e7eb] shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="relative h-[220px] overflow-hidden bg-[#F6F3EC]">
                            <img src="{{ asset($pkg->image_path ?? 'images/index-page/white-rann-hero.webp') }}"
                                 alt="{{ $pkg->image_alt ?? $pkg->title }}"
                                 class="w-full h-full object-cover">
                            <span class="absolute top-3 left-3 bg-[#16100E]/85 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-[6px]">
                                ⏱️ {{ $pkg->duration ?? '2N / 3D' }}
                            </span>
                            @if($pkg->is_featured)
                            <span class="absolute top-3 right-3 bg-[#DCA134] text-[#16100E] text-[0.7rem] font-bold px-2 py-0.5 rounded-[4px] uppercase tracking-wider">
                                Recommended
                            </span>
                            @endif
                        </div>

                        <div class="p-6">
                            <h2 class="font-display font-bold text-lg text-[#16100E] mb-2">{{ $pkg->title }}</h2>
                            <p class="text-[#6B7A8D] text-xs leading-relaxed mb-4 line-clamp-3">{{ $pkg->description }}</p>

                            @if(!empty($pkg->highlight))
                                <div class="bg-[#F6F3EC] text-[#9E2A2B] text-xs font-semibold px-3 py-1.5 rounded-[6px] mb-4">
                                    ✦ {{ $pkg->highlight }}
                                </div>
                            @endif

                            <!-- Pricing Tiers Breakdown -->
                            <div class="bg-[#fafafa] p-3 rounded-[6px] border border-[#f3f4f6] text-xs text-[#16100E] space-y-1 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-[#6B7A8D]">2 Persons (Couple):</span>
                                    <span class="font-bold">₹{{ number_format($pkg->price_2p ?? 24000) }}</span>
                                </div>
                                @if($pkg->price_4p)
                                <div class="flex justify-between">
                                    <span class="text-[#6B7A8D]">4 Persons (Family):</span>
                                    <span class="font-bold">₹{{ number_format($pkg->price_4p) }}</span>
                                </div>
                                @endif
                                @if($pkg->price_6p)
                                <div class="flex justify-between">
                                    <span class="text-[#6B7A8D]">6 Persons (Group):</span>
                                    <span class="font-bold">₹{{ number_format($pkg->price_6p) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-[#f3f4f6] space-y-2 mt-auto">
                        <a id="btn-pkg-itinerary-{{ $pkg->slug }}"
                           href="{{ route('package.show', $pkg->slug) }}"
                           class="w-full border border-[#16100E] text-[#16100E] hover:bg-[#16100E] hover:text-white py-2.5 px-4 rounded-[6px] font-bold text-xs flex items-center justify-center transition-colors">
                            View Day-by-Day Itinerary & Spots →
                        </a>
                        <a id="btn-pkg-whatsapp-{{ $pkg->slug }}"
                           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I am interested in booking ' . $pkg->title . '. Please share details and availability.') }}"
                           target="_blank"
                           class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white py-2.5 px-4 rounded-[6px] font-bold text-xs flex items-center justify-center gap-1.5 transition-colors">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                            <span>Book on WhatsApp</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ WHAT ALL PACKAGES INCLUDE ══ -->
<section class="section py-14 px-[5%] bg-[#F6F3EC] border-t border-[#e5e7eb]">
    <div class="max-w-[1240px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="p-6 bg-white rounded-[10px] border border-[#e5e7eb]">
            <h3 class="font-display font-bold text-base text-[#16100E] mb-4 flex items-center gap-2 text-green-700">
                <span>✓</span> Inclusions in All Packages
            </h3>
            <ul class="space-y-2.5 text-xs text-[#16100E]/80">
                <li class="flex items-start gap-2"><span>•</span> Pickup and drop from Bhuj Railway Station / Airport / Bus Station.</li>
                <li class="flex items-start gap-2"><span>•</span> Private air-conditioned car (Dzire / Ertiga / Innova) throughout the tour.</li>
                <li class="flex items-start gap-2"><span>•</span> Hotel / Resort accommodation with daily breakfast & dinner.</li>
                <li class="flex items-start gap-2"><span>•</span> Driver allowance, fuel, parking charges, and toll taxes.</li>
                <li class="flex items-start gap-2"><span>•</span> White Rann border permit verification assistance.</li>
            </ul>
        </div>
        <div class="p-6 bg-white rounded-[10px] border border-[#e5e7eb]">
            <h3 class="font-display font-bold text-base text-[#16100E] mb-4 flex items-center gap-2 text-red-700">
                <span>✕</span> Exclusions
            </h3>
            <ul class="space-y-2.5 text-xs text-[#16100E]/80">
                <li class="flex items-start gap-2"><span>•</span> Lunch and personal refreshments outside the hotel meal plan.</li>
                <li class="flex items-start gap-2"><span>•</span> Monument entry fees, museum tickets, and camera charges.</li>
                <li class="flex items-start gap-2"><span>•</span> Adventure sports, camel cart rides, paramotoring at Rann.</li>
                <li class="flex items-start gap-2"><span>•</span> Pickup or drop-off outside Bhuj city limits (available at actuals).</li>
                <li class="flex items-start gap-2"><span>•</span> Personal expenses, shopping, laundry, and tips.</li>
            </ul>
        </div>
    </div>
</section>

<!-- ══ CUSTOM TOUR PROPOSAL BANNER ══ -->
<section class="cta-banner py-14 px-[5%] text-white">
    <div class="max-w-[900px] mx-auto text-center">
        <h3 class="font-display text-2xl md:text-3xl font-bold mb-3">Want a Custom Tour Itinerary?</h3>
        <p class="text-white/70 text-sm max-w-[560px] mx-auto mb-6">
            Tell us your travel dates, train/flight arrival times, and group size. We'll generate a personalized day-by-day plan and PDF proposal in minutes.
        </p>
        <a id="btn-custom-pkg-wa"
           href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I need a custom Kutch tour package. My dates and details are:') }}"
           target="_blank"
           class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-3 px-6 rounded-[8px] font-bold text-sm inline-flex items-center gap-2 transition-colors">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
            <span>Request Custom Tour on WhatsApp</span>
        </a>
    </div>
</section>

@endsection
