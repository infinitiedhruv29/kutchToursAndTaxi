@extends('layouts.app')

@section('title', 'Photo Gallery — Kutch Tours & Taxi')
@section('canonical', route('gallery'))
@section('meta_description', 'Explore stunning photos of the White Rann of Kutch, Dholavira, Mandvi Beach, Vijay Vilas Palace, Prag Mahal, and authentic Kutchi handicrafts.')
@section('page_name', 'Gallery')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919773239587');
@endphp

<!-- ══ PAGE HERO ══ -->
<div class="page-hero bg-[#16100E] py-14 px-[5%] text-center text-white border-b border-white/10">
    <div class="max-w-[800px] mx-auto relative z-20">
        <div class="breadcrumb flex items-center justify-center gap-2 text-xs text-white/50 mb-3 relative z-20">
            <a id="breadcrumb-link-home" href="{{ route('home') }}" class="text-white/85 hover:text-[#DCA134] underline underline-offset-4 cursor-pointer font-medium transition-colors">Home</a>
            <span class="text-white/40">/</span>
            <span class="text-white/90">Photo Gallery</span>
        </div>
        <span class="bg-[#DCA134]/15 border border-[#DCA134]/30 text-[#DCA134] inline-block py-1 px-3.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            Visual Journey
        </span>
        <h1 class="font-display text-[2.2rem] md:text-[3.2rem] font-extrabold text-white leading-tight mb-3">
            Glimpses of Extraordinary Kutch
        </h1>
        <p class="text-white/70 text-sm md:text-base max-w-[560px] mx-auto">
            From the infinite salt flats of White Rann to 5,000-year-old Harappan ruins and golden coastal sunsets.
        </p>
    </div>
</div>

<!-- ══ GALLERY GRID WITH CATEGORY FILTER ══ -->
<section class="section py-16 px-[5%] bg-[#FDFDFC]">
    <div class="max-w-[1240px] mx-auto">
        
<style>
    .gallery-filter-btn {
        padding: 7px 18px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
    }
    .gallery-filter-btn:hover {
        border-color: var(--orange);
        color: var(--orange);
    }
    .gallery-filter-btn.active,
    .gallery-filter-btn.active:hover,
    .gallery-filter-btn.active:focus {
        background-color: var(--orange) !important;
        border-color: var(--orange) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(var(--orange-rgb, 158, 42, 43), 0.35);
    }
    .gallery-item-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .gallery-item-card:hover {
        border-color: var(--orange) !important;
        box-shadow: 0 16px 36px rgba(var(--orange-rgb, 220, 161, 52), 0.15), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transform: translateY(-6px);
    }
</style>

        <!-- Category Filter Tabs -->
        <div class="flex items-center justify-center gap-2.5 flex-wrap mb-10">
            <button class="gallery-filter-btn active" onclick="filterGallery('all', this)">
                All Photos
            </button>
            @foreach($categories as $cat)
                <button class="gallery-filter-btn" onclick="filterGallery('{{ $cat->id }}', this)">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Images Grid: 3 Columns with Expanded Image Dimensions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="gallery-container">
            @forelse($images as $img)
                <div class="gallery-item-card relative h-[300px] sm:h-[320px] md:h-[350px] rounded-[14px] overflow-hidden border border-[#e5e7eb] shadow-sm group cursor-pointer gallery-item-clickable"
                     data-cat="{{ $img->gallery_category_id }}"
                     onclick="openLightbox(this)">
                    <img src="{{ $img->image_url }}"
                         alt="{{ $img->image_alt ?? $img->caption }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                        <span class="text-white text-sm sm:text-base font-semibold dest-name tracking-wide">{{ $img->caption ?? 'Kutch Sight' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-[#6B7A8D] text-sm">
                    No images available at this moment.
                </div>
            @endforelse
        </div>

        <!-- BOTTOM CALL TO ACTION -->
        <div class="mt-16 text-center bg-[#F6F3EC] p-8 rounded-[10px] border border-[#e5e7eb]">
            <h3 class="font-display font-bold text-xl text-[#16100E] mb-2">Want to experience these wonders in person?</h3>
            <p class="text-[#6B7A8D] text-xs max-w-[500px] mx-auto mb-5">
                Book a personalized tour package or rent a clean AC cab with local driver in minutes.
            </p>
            <a id="btn-gallery-wa-cta"
               href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hi! I saw your gallery and want to plan a tour to Kutch.') }}"
               target="_blank"
               class="bg-[#25D366] hover:bg-[#20ba5a] text-white py-2.5 px-5 rounded-[6px] font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                <span>Plan on WhatsApp</span>
            </a>
        </div>

    </div>
</section>

@endsection

@section('extra_js')
<script>
    function filterGallery(catId, btnEl) {
        document.querySelectorAll('.gallery-filter-btn').forEach(b => {
            b.classList.remove('active');
        });
        btnEl.classList.add('active');

        const items = document.querySelectorAll('.gallery-item-card');
        items.forEach(item => {
            const itemCat = item.getAttribute('data-cat');
            if (catId === 'all' || catId === itemCat) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endsection
