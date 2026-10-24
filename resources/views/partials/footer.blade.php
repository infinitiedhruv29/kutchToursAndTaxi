@php
    $settings = $settings ?? \App\Models\Setting::current();
    $waNumber = preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '919904222293');
    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hi! I want to inquire about Kutch tour packages and taxi services.");
    $phoneDisplay = $settings->contact_number ?? '+91 99042 22293';
    $phoneTel = preg_replace('/[^0-9+]/', '', $phoneDisplay);
@endphp

<footer class="footer text-white/80 pt-16 pb-8 border-t border-white/10">
    <div class="footer-grid max-w-[1240px] mx-auto px-[5%] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
        <!-- BRAND COLUMN -->
        <div class="footer-brand lg:col-span-1">
            <div style="margin-bottom:16px;">
                <img src="{{ $settings->dark_logo_url }}" alt="Kutch Tours & Taxi" class="h-24 sm:h-28 w-auto max-w-[320px] shrink-0 object-contain" style="height:100px;width:auto;max-width:320px;object-fit:contain;">
            </div>
            <p class="text-white/65 text-[0.88rem] leading-[1.7] mb-5">
                Discover the magic of Kutch with Gujarat's most trusted local cab and tour service. Authentic desert experiences, seasoned drivers, transparent fares, and 24/7 WhatsApp assistance.
            </p>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                <a id="footer-link-whatsapp-brand" href="{{ $waUrl }}" target="_blank"
                    style="background:#25D366;color:#fff;padding:8px 16px;border-radius:6px;font-size:0.78rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg> WhatsApp Us
                </a>
                @if(!empty($settings->instagram_link))
                <a id="footer-link-instagram" href="{{ $settings->instagram_link }}" target="_blank"
                    style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;padding:8px 16px;border-radius:6px;font-size:0.78rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg> Instagram
                </a>
                @endif
            </div>
        </div>

        <!-- PACKAGES COLUMN -->
        <div class="footer-col">
            <h4 class="font-display text-white font-bold text-base mb-4 tracking-wide border-b border-white/10 pb-2">Tour Packages</h4>
            <ul class="space-y-2.5 text-[0.85rem] text-white/60">
                <li><a id="footer-link-pkg-classic" href="{{ route('package.show', 'kutch-classic') }}" class="hover:text-white transition-colors">Kutch Classic Tour (2N/3D)</a></li>
                <li><a id="footer-link-pkg-rann-dholavira" href="{{ route('package.show', 'whiterann-dholavira') }}" class="hover:text-white transition-colors">White Rann & Dholavira (3N/4D)</a></li>
                <li><a id="footer-link-pkg-grand" href="{{ route('package.show', 'kutch-grand') }}" class="hover:text-white transition-colors">Kutch Grand Explorer (4N/5D)</a></li>
                <li><a id="footer-link-pkg-all" href="{{ route('packages') }}" class="hover:text-[#DCA134] transition-colors">→ View All Tour Packages</a></li>
            </ul>
        </div>

        <!-- QUICK LINKS -->
        <div class="footer-col">
            <h4 class="font-display text-white font-bold text-base mb-4 tracking-wide border-b border-white/10 pb-2">Quick Links</h4>
            <ul class="space-y-2.5 text-[0.85rem] text-white/60">
                <li><a id="footer-link-home" href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                <li><a id="footer-link-taxi" href="{{ route('taxi') }}" class="hover:text-white transition-colors">Taxi Fleet & Fares</a></li>
                <li><a id="footer-link-gallery" href="{{ route('gallery') }}" class="hover:text-white transition-colors">Photo Gallery</a></li>
                <li><a id="footer-link-about" href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                <li><a id="footer-link-contact" href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact & Enquiries</a></li>
                <li><a id="footer-link-admin" href="{{ route('admin.login') }}" class="hover:text-white/40 transition-colors text-xs">Admin Portal</a></li>
            </ul>
        </div>

        <!-- CONTACT DETAILS -->
        <div class="footer-col">
            <h4 class="font-display text-white font-bold text-base mb-4 tracking-wide border-b border-white/10 pb-2">Contact Us</h4>
            <ul class="space-y-3 text-[0.85rem] text-white/70">
                <li class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#DCA134] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $settings->address ?? 'Station Road, Near Bus Station, Bhuj, Kutch, Gujarat — 370001' }}</span>
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#DCA134] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <a id="footer-link-phone" href="tel:{{ $phoneTel }}" class="hover:text-white transition-colors">{{ $phoneDisplay }}</a>
                </li>
                @if(!empty($settings->contact_number_2))
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#DCA134] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <a id="footer-link-phone-2" href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->contact_number_2) }}" class="hover:text-white transition-colors">{{ $settings->contact_number_2 }}</a>
                </li>
                @endif
                <li class="flex items-center gap-2.5 text-[#25D366]">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    <a id="footer-link-whatsapp" href="{{ $waUrl }}" target="_blank" class="hover:underline font-semibold">+91 {{ $waNumber }} (WhatsApp)</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- COPYRIGHT SUBFOOTER -->
    <div class="mt-12 pt-6 border-t border-white/10 text-center text-xs text-white/45 px-[5%]">
        <p>© {{ date('Y') }} Kutch Tours & Taxi. All rights reserved. Crafted with authentic Kutchi hospitality.</p>
    </div>
</footer>
