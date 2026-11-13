# Project History: Kutch Tours and Taxi

## Project Overview
A Laravel 13 web application on PHP 8.5 designed for a travel & taxi service in the Kutch/Gujarat region (Kutch Tours & Taxi). 
The project includes a robust administrative management portal for taxi routes, vehicle fleets, vehicle types/capacities, destinations, FAQs, settings, branding, and comprehensive schema migrations for tours, packages, custom itineraries, inquiries, invoices, and testimonials.

---

### Initial Setup & Architecture Discovery
- **Date**: 2026-10-23
- **Feature**: Project End-to-End Analysis & Onboarding
- **Files Inspected**:
  - `composer.json`, `package.json`
  - `routes/web.php`
  - `app/Models/*` (`TaxiRoute`, `Vehicle`, `Setting`, `Destination`, `Faq`, `VehicleType`, `VehicleCapacity`, `User`)
  - `app/Http/Controllers/Admin/*`
  - `database/migrations/*`
  - `resources/views/*`
  - `tests/Feature/*`, `tests/Unit/*`
- **Architectural Findings & State**:
  - **Framework & Tech**: Laravel 13, PHP 8.5, Tailwind CSS v4, Vite 8, TipTap editor, DataTables.net, jQuery, SweetAlert2.
  - **Admin Panel**: Fully structured modular CRUD with AJAX DataTables, server-side processing, modal confirmations, image upload handling, and flash alerts.
  - **Database & Schemas**: 23 migrations defining the core business domain: Users, Settings, Page Contents, Taxi Routes, Destinations, FAQs, Vehicle Types/Capacities/Vehicles, Packages & Itineraries, Gallery, Testimonials, Inquiries, and Invoices.
  - **Current Status**: Admin features for Taxi Routes, Vehicles, Vehicle Types/Capacities, Destinations, FAQs, Profile, and Settings are implemented with unit and feature test coverage. Public-facing customer portal and remaining admin sections (Packages, Inquiries, Invoices, Testimonials, Gallery) are mapped in migrations.

---

### Local Environment Setup & Dependency Resolution
- **Date**: 2026-10-23
- **Feature**: Platform PHP Compatibility & Environment Bootstrap
- **Files Modified**:
  - `composer.json`
  - `composer.lock`
  - `.env`
  - `database/database.sqlite`
- **Logic Changes**:
  - Adjusted root PHP requirement from `^8.5` to `^8.4|^8.5` in `composer.json` and `composer.lock` to support installed PHP 8.4 runtime while preserving future PHP 8.5 compatibility.
  - Generated application `.env` and `APP_KEY`.
  - Ran database migrations creating all 23 domain tables in SQLite.
  - Installed NPM dependencies and compiled frontend assets with Vite.
  - Verified test suites: 127/127 PHPUnit tests passed, 25/25 JS unit tests passed.
- **Pending Tasks**:
  - Create admin user account (`php artisan admin:create`).
  - Serve application (`php artisan serve`).

---

### Database Migration to MySQL
- **Date**: 2026-10-23
- **Feature**: MySQL Database Configuration (`tours_db`)
- **Files Modified**:
  - `.env`
- **Logic Changes**:
  - Updated database configuration in `.env` to `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=tours_db`, `DB_USERNAME=root`, `DB_PASSWORD=`.
  - Created MySQL database `tours_db` with `utf8mb4_unicode_ci`.
  - Migrated all 23 domain schemas into `tours_db`.
  - Created administrator user (`admin@example.com`) in MySQL `tours_db`.

---

### Project Conventions & Rulebook Generation
- **Date**: 2026-10-23
- **Feature**: Codebase Convention Analysis & `.ai/rules` Generation (`infer-conventions`)
- **Files Modified**:
  - `.ai/rules/index.md`
  - `.ai/rules/controllers.md`
  - `.ai/rules/models.md`
  - `.ai/rules/migrations.md`
  - `.ai/rules/tests.md`
- **Logic Changes**:
  - Inferred and codified conventions into durable path-scoped rules:
    - `app/Http/**`: Form Requests required, `AdminFeedback::success` pattern, direct Eloquent controllers.
    - `app/Models/**`: `#[Fillable]` PHP 8 attributes, `casts()` array methods, static `suggestedSlug`/`uniqueSlug`, `Attribute::make` accessors.
    - `database/migrations/**`: `foreignId` with explicit cascade/restrict constraints, explicit `down()` drop methods.
    - `tests/**`: `Tests\TestCase` base with `RefreshDatabase` and `withoutVite()`.
- **Pending Tasks**:
  - Select features to integrate from `kutch-holidays`.

---

### Imported Skills from Code - v1.4.9
- **Date**: 2026-10-23
- **Feature**: Extended AI Skills Integration
- **Files Modified**:
  - `.agents/skills/git-commit/*`
  - `.agents/skills/laravel-best-practices/*`
  - `.agents/skills/mysql-best-practices/*`
  - `.agents/skills/newshunt-architecture/*`
  - `.agents/skills/ponytail/*`
- **Logic Changes**:
  - Imported 5 domain skills from `C:\Users\user\Downloads\Code - v1.4.9\.agents\skills` into `.agents/skills/`.
  - Available skills now include git commit standards, Laravel best practices, MySQL optimizations, architecture patterns, and ponytail tooling.

---

### Tailored Skills to Kutch Tours & Taxi
- **Date**: 2026-10-23
- **Feature**: Project-Specific Architecture Skill (`kutch-tours-architecture`)
- **Files Modified**:
  - `.agents/skills/newshunt-architecture` (removed)
  - `.agents/skills/kutch-tours-architecture/SKILL.md` (created)
- **Logic Changes**:
  - Removed irrelevant `newshunt-architecture` skill.
  - Authored a dedicated architecture blueprint and agent skill for **Kutch Tours & Taxi**:
    - Defines domain capabilities: Taxi routes & dynamic fares, fleet management, tour packages, custom package PDF builder, destinations, FAQs, settings, and billing.
    - Documents stack: Laravel 13, PHP 8.4/8.5, MySQL (`tours_db`), Tailwind CSS v4, TipTap editor, DataTables.net, and SweetAlert2.
    - Mandates conventions: Form Requests, `AdminFeedback::success()`, `#[Fillable]` attributes, `casts()` array methods, and static slug generation.

---

### Git Initialization & Remote Configuration
- **Date**: 2026-10-23
- **Feature**: Local Git Repository & Remote GitHub Link
- **Files Modified**:
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Initialized local git repository (`git init`).
  - Added remote origin `https://github.com/infinitiedhruv29/kutchToursAndTaxi`.
  - Fetched and tracked `origin/master`.
  - Aligned local working branch with remote commit `ced37fc` ("Added FAQ and destinations module").
- **Pending Tasks**:
  - Push changes to remote `origin/master`.

---

### Setup & Environment Baseline Commit
- **Date**: 2026-10-23
- **Feature**: Project Setup, AI Conventions & Skills Baseline
- **Files Modified**:
  - `.gitignore`
  - `composer.json`
  - `composer.lock`
  - `package-lock.json`
  - `.ai/rules/*`
  - `.agents/skills/*`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Ignored `kutch-holidays*` reference materials in `.gitignore`.
  - Added PHP 8.4 runtime support alongside PHP 8.5 in `composer.json`.
  - Added repository-specific agent rules (`.ai/rules/`) and developer skills (`.agents/skills/`).
  - Added project activity log (`PROJECT_HISTORY.md`).
  - Successfully pushed commit `8e24e59` to `origin/master`.
- **Pending Tasks**:
  - Feature development (Public portal, customer package booking, etc.).

---

### Feature 1: Admin Gallery Implementation
- **Date**: 2026-10-23
- **Feature**: Gallery Categories & Photos Management (Ported from Kutch Holidays)
- **Branch**: `feature/admin-gallery`
- **Files Modified / Created**:
  - `composer.json`, `composer.lock` (installed `barryvdh/laravel-dompdf` for upcoming PDF builders)
  - `app/Models/GalleryCategory.php`
  - `app/Models/GalleryImage.php`
  - `app/Http/Requests/Admin/GalleryCategoryRequest.php`
  - `app/Http/Requests/Admin/GalleryImageRequest.php`
  - `app/Http/Requests/Admin/GalleryImageTableRequest.php`
  - `app/Http/Controllers/Admin/GalleryCategoryController.php`
  - `app/Http/Controllers/Admin/GalleryImageController.php`
  - `resources/views/admin/gallery/categories/index.blade.php`
  - `resources/views/admin/gallery/images/index.blade.php`
  - `resources/views/admin/gallery/images/cell.blade.php`
  - `resources/views/admin/gallery/images/form.blade.php`
  - `resources/views/admin/partials/navigation.blade.php`
  - `routes/web.php`
  - `tests/Feature/AdminGalleryTest.php`
- **Logic Changes**:
  - Created `GalleryCategory` and `GalleryImage` Eloquent models conforming to `#[Fillable]` and modern `casts()`.
  - Added dedicated Form Requests enforcing file validation (jpg, jpeg, png, webp <= 5MB), unique category names, and integer sort order.
  - Implemented `GalleryCategoryController` supporting create, inline edit, delete with foreign key safeguard against orphan photos, and AJAX reorder.
  - Implemented `GalleryImageController` supporting server-side DataTables pagination/sorting/filtering, photo uploading to `storage/app/public/gallery`, old photo cleanup on edit/delete, and flash feedback via `AdminFeedback::success()`.
  - Added storage symlink (`public/storage`) and compiled Vite frontend assets.
  - Authored comprehensive feature test suite: all 131 tests passing (1,405 assertions).
- **Pending Tasks**:
  - Feature 1 verified and merged to master.
  - Proceed with Feature 2: Package Builder.

---

### Feature 2: Package Builder (Custom Tour Proposals)
- **Date**: 2026-10-23
- **Feature**: Custom Package Builder & DomPDF Proposal Export (Ported from Kutch Holidays)
- **Branch**: `feature/admin-package-builder`
- **Files Modified / Created**:
  - `app/Models/CustomPackage.php`
  - `app/Models/Package.php`
  - `app/Http/Requests/Admin/CustomPackageRequest.php`
  - `app/Http/Requests/Admin/CustomPackageTableRequest.php`
  - `app/Http/Controllers/Admin/PackageBuilderController.php`
  - `resources/views/admin/custom-packages/index.blade.php`
  - `resources/views/admin/custom-packages/cell.blade.php`
  - `resources/views/admin/custom-packages/form.blade.php`
  - `resources/views/admin/custom-packages/pdf.blade.php`
  - `resources/views/admin/partials/navigation.blade.php`
  - `routes/web.php`
  - `tests/Feature/AdminPackageBuilderTest.php`
- **Logic Changes**:
  - Created `CustomPackage` model with `inclusions`, `exclusions`, and `itineraries` JSON casts and decimal pricing casts.
  - Created `Package` model for standard tour package compatibility.
  - Added `CustomPackageRequest` validating client details, phone regex, pricing tiers, inclusions/exclusions, and dynamic day-by-day itineraries.
  - Created `PackageBuilderController` implementing server-side DataTables listing, interactive itinerary builder, and PDF proposal generation via `Barryvdh\DomPDF\Facade\Pdf`.
  - Added DomPDF template `pdf.blade.php` with company header, client metadata, pricing tiers (2P/4P/6P), day-by-day itinerary with spots visited, inclusions/exclusions, and terms.
  - Authored feature test suite in `AdminPackageBuilderTest.php`: all 135 tests passing (1,440 assertions).
- **Pending Tasks**:
  - Feature 2 verified and merged to master.
  - Proceed with Feature 3: Invoice Builder.

---

### Feature 3: Invoice Builder (GST Tax Invoices & Taxi Billing)
- **Date**: 2026-10-23
- **Feature**: Tax Invoice Builder, Taxi Distance Calculator & DomPDF Invoice Export (Ported from Kutch Holidays)
- **Branch**: `feature/admin-invoices`
- **Files Modified / Created**:
  - `app/Models/Invoice.php`
  - `app/Models/InvoiceItem.php`
  - `app/Http/Requests/Admin/InvoiceRequest.php`
  - `app/Http/Requests/Admin/InvoiceTableRequest.php`
  - `app/Http/Controllers/Admin/InvoiceController.php`
  - `resources/views/admin/invoices/index.blade.php`
  - `resources/views/admin/invoices/cell.blade.php`
  - `resources/views/admin/invoices/form.blade.php`
  - `resources/views/admin/invoices/pdf.blade.php`
  - `resources/views/admin/partials/navigation.blade.php`
  - `routes/web.php`
  - `tests/Feature/AdminInvoiceTest.php`
- **Logic Changes**:
  - Implemented `Invoice` and `InvoiceItem` Eloquent models conforming to `#[Fillable]` and modern `casts()` (`business_details` JSON snapshot, decimal money types).
  - Implemented `Invoice::generateInvoiceNumber()` generating daily sequence numbers (`INV-YYYYMMDD-XXXX`).
  - Added `InvoiceRequest` computing taxable subtotal, GST rates (0%, 5%, 12%, 18%), and line items parsing for packages, taxi rentals (vehicle * distance * rate per km), and dynamic custom items.
  - Added `InvoiceTableRequest` for server-side DataTables sorting and filtering.
  - Implemented `InvoiceController` handling CRUD, database transactions for parent invoice & items sync, AJAX export redirection, and PDF rendering via DomPDF.
  - Created `pdf.blade.php` tax invoice template featuring company letterhead with base64 logo, customer GSTIN/billing metadata, itemized billing table, tax breakdown, notes, and payment instructions.
  - Created interactive `form.blade.php` with real-time tax and subtotal computation, taxi vehicle preselection, and dynamic repeater for line items.
  - Added `Invoices & billing` navigation link to admin workspace.
  - Authored comprehensive feature test suite: all 140 tests passing (1,485 assertions).
- **Pending Tasks**:
  - Manual review and verification by user in browser.
  - Local commit on `feature/admin-invoices`, merge into `master`, and push to `origin/master`.

---

### Customer Frontend Implementation (Laravel Blade & WhatsApp Inquiries)
- **Date**: 2026-10-24
- **Feature**: Full Public Website Implementation Inspired by Kutch Holidays
- **Branch**: `master`
- **Files Modified / Created**:
  - `app/Models/Package.php`
  - `app/Models/PackageItinerary.php`
  - `app/Models/PackageFeature.php`
  - `app/Models/PackageGalleryImage.php`
  - `app/Models/Testimonial.php`
  - `database/seeders/DatabaseSeeder.php`
  - `database/seeders/SettingSeeder.php`
  - `database/seeders/VehicleSeeder.php`
  - `database/seeders/TaxiRouteSeeder.php`
  - `database/seeders/PackageSeeder.php`
  - `database/seeders/DestinationSeeder.php`
  - `database/seeders/FaqSeeder.php`
  - `database/seeders/TestimonialSeeder.php`
  - `database/seeders/GallerySeeder.php`
  - `app/Http/Controllers/HomeController.php`
  - `app/Http/Controllers/TaxiController.php`
  - `app/Http/Controllers/PackageController.php`
  - `app/Http/Controllers/GalleryController.php`
  - `routes/web.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/partials/header.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `resources/views/partials/lightbox.blade.php`
  - `resources/views/index.blade.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/packages.blade.php`
  - `resources/views/package-show.blade.php`
  - `resources/views/gallery.blade.php`
  - `resources/views/about.blade.php`
  - `resources/views/contact.blade.php`
  - `public/images/*`, `public/css/*`, `public/js/*`
  - `tests/Feature/FrontendTest.php`
  - `tests/Feature/ExampleTest.php`
- **Logic & UI Design**:
  - **Aesthetic**: Clean, human-crafted local tours & taxi operator design. Avoided AI-typical curvy/blob borders in favor of clean 6px–8px radii, authentic warm desert palette (`#9E2A2B` terracotta maroon, `#DCA134` desert ochre, `#16100E` deep charcoal, `#25D366` WhatsApp emerald), and crisp Outfit + Inter typography.
  - **WhatsApp Booking Redirection**: Since booking does not happen on-site, every vehicle card, route table row, package inquiry button, floating widget, and contact form dynamically formats inquiries and redirects directly to WhatsApp (`https://wa.me/{number}?text={message}`).
  - **Pages Delivered**:
    - **Home (`/`)**: Hero banner, quick ticker stats, featured holiday packages (2N3D, 3N4D, 4N5D), popular taxi routes table with WhatsApp book links, vehicle fleet cards with transparent per-km rates, top destinations in Kutch, verified guest reviews, accordion FAQs, and bottom CTA banner.
    - **Taxi Fleet & Fares (`/taxi`)**: Interactive capacity/type filters (Sedan, SUV, Luxury Innova Crysta, Tempo Traveller), detailed per-km and per-day pricing cards, full outstation route fare matrix, and WhatsApp cab dispatch.
    - **Tour Packages Grid (`/packages`)**: Comprehensive holiday tour packages with highlights, duration badges, tiered pricing (2 Pax / 4 Pax / 6 Pax), inclusion summary, and WhatsApp itinerary request.
    - **Tour Package Detail (`/package-{slug}`)**: Day-by-day spots and activities, package photo gallery with modal lightbox, inclusions & exclusions list, sticky pricing breakdown, and WhatsApp booking action.
    - **Photo Gallery (`/gallery`)**: Categorized photo showcase (White Rann, Dholavira, Mandvi, Heritage & Palaces, Handicrafts & Culture) with interactive category filter tabs and image lightbox viewer.
    - **About Us (`/about`)**: Authentic Kutchi story, fleet cleanliness promise, driver vetting standards, and local tourism ethos.
    - **Contact Us (`/contact`)**: Direct contact cards (WhatsApp, phone, office address) and an interactive quick proposal generator form that formats and redirects directly to WhatsApp.
  - **Seeders & Assets**:
    - Ported real destination imagery, vehicle thumbnails, and sample itineraries from `kutch-holidays` reference folder into `public/images/`.
    - Seeded full dataset (7 vehicles, 5 taxi routes, 3 packages with complete itineraries, 6 destinations, 5 FAQs, 5 gallery categories with photos, and 1 settings singleton) to MySQL `tours_db`.
  - **Testing**:
    - Authored `tests/Feature/FrontendTest.php` covering all public routes, data bindings, and WhatsApp redirection links.
    - Full test suite passed: **147/147 tests (1,515 assertions)**.
- **Pending Tasks**:
  - User review in browser.

---

### Data Entry & Logo Update (Kutch Tours & Taxi Branding & Authentic Content)
- **Date**: 2026-10-24
- **Feature**: Branding Cleanup & Real Tourism Data Seeding
- **Branch**: `master`
- **Files Modified**:
  - `public/images/logo.svg`
  - `public/js/components.js`
  - `public/js/carousel.js`
  - `database/seeders/PackageSeeder.php`
  - `database/seeders/GallerySeeder.php`
  - `database/seeders/TaxiRouteSeeder.php`
- **Logic Changes**:
  - **Brand Logo Overhaul**: Removed all instances of the old "Kutch Holidays" logo. Created a clean, vector SVG logo in `public/images/logo.svg` featuring a terracotta badge (`#9E2A2B`), stylized K monogram, golden sun accent (`#DCA134`), and crisp white & gold typography ("KUTCH TOURS & TAXI").
  - **Shared Components & JS**: Cleaned up WhatsApp inquiry templates and header comments in `components.js` and `carousel.js` to reference "Kutch Tours & Taxi".
  - **25 Authentic Gallery Photos**: Replaced placeholder image paths in `database/seeders/GallerySeeder.php` with 25 authentic, high-resolution photographs across 6 distinct categories (White Rann, Heritage & Palaces, Mandvi Beach, Culture & Crafts, Wildlife & Nature, Bhuj City).
  - **Tour Packages Expansion**: Added the 2N/3D White Rann Special and 6 popular outstation packages (Rajasthan, Kashmir, Kerala, Goa, Golden Triangle, Manali) with real photographs and complete itineraries.
  - **Taxi Routes Expansion**: Added Bhuj to Kalo Dungar / Magnetic Hill and Bhuj to Rajkot outstation route.
  - **Database Migration & Seeding**: Successfully re-seeded MySQL database `tours_db`.
  - **Testing**: Ran full test suite: **147/147 tests passed (1,515 assertions)**.
- **Pending Tasks**:
  - User review in browser.

---

### Gallery Storage Link Resolution & Authentic About Us Photo
- **Date**: 2026-10-24
- **Feature**: Backend Gallery Image Loading & Authentic Photography Fixes
- **Branch**: `master`
- **Files Modified / Created**:
  - `storage/app/public/images` (directory junction linking to `public/images`)
  - `app/Models/GalleryImage.php`
  - `resources/views/gallery.blade.php`
  - `resources/views/admin/gallery/images/cell.blade.php`
  - `resources/views/components/admin/image-upload.blade.php`
  - `database/seeders/GallerySeeder.php`
  - `resources/views/about.blade.php`
  - `public/images/kutch_about_culture.webp`
- **Logic Changes**:
  - **Storage Link Resolution**: Linked `storage/app/public/images` to `public/images` and updated `GalleryImage::imageUrl` accessor to support both direct public asset paths (`images/...`) and admin storage uploads (`gallery/...`). Updated admin table cells and edit form previews so all gallery photos load instantly in both frontend and admin backend.
  - **Pruned Broken Database Records**: Cleaned out 8 legacy database rows referencing non-existent file names (`sunset-at-rann.webp`, `full-moon-rann.webp`, etc.). Exactly 25 verified high-resolution photographs now populate the gallery across 6 categories with sequential sort orders.
  - **Replaced AI-Generated Image on About Us**: Replaced the AI-generated dancer image (`kutch_about_culture.webp`) with an authentic, high-resolution photograph of travelers and Kutchi folk musicians with camels against the sunset on the White Rann (`rann-utsav-celebrations.webp`).
  - **Testing**: Full test suite: **147/147 tests passed (1,515 assertions)**.
- **Pending Tasks**:
  - User review in browser.

---

### Gallery Active Tab Contrast Fix & Redundant Category Cleanup
- **Date**: 2026-10-24
- **Feature**: Gallery Tab Contrast & Category Cleanup
- **Branch**: `master`
- **Files Modified**:
  - `resources/views/gallery.blade.php`
- **Logic Changes**:
  - **Active Tab Text Visibility**: Resolved an issue where hover/focus styling turned the active button text red on a red background (`#9E2A2B` on `#9E2A2B`). Defined explicit `.gallery-filter-btn` and `.gallery-filter-btn.active` rules with `color: #ffffff !important;` on `:hover` and `:focus` so active tab text is always crisp, white, and clearly legible.
  - **Pruned Empty Category**: Removed empty legacy category `Heritage` (which had 0 photos) in favor of the active `Heritage & Palaces` (which contains all 6 heritage photos).
  - **Testing**: Skipped per user instruction for immediate manual review.
- **Pending Tasks**:
  - User manual review in browser.

---

### Dynamic Primary Theme Color Selection in Admin Settings
- **Date**: 2026-10-24
- **Feature**: Dynamic Primary Brand Color Customization
- **Branch**: `master`
- **Files Modified / Created**:
  - `database/migrations/2026_10_24_091416_add_primary_color_to_settings_table.php` (created)
  - `app/Models/Setting.php`
  - `app/Http/Requests/Admin/UpdateSettingsRequest.php`
  - `database/seeders/SettingSeeder.php`
  - `resources/views/admin/settings/edit.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/partials/header.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `tests/Feature/AdminSettingsTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Database Migration**: Added `primary_color` (string 20, nullable, default `#9E2A2B`) to `settings` table with rollback support.
  - **Model & Request**: Added `primary_color` to `Setting` `#[Fillable]` and `CONTENT_FIELDS`. Added hex color code validation (`regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/`) and custom error messaging in `UpdateSettingsRequest`.
  - **Admin UI**: Created "Website theme & appearance" settings card featuring an interactive HTML5 color picker, hex input field, live preview swatch, and 8 quick-preset Kutchi & modern palette swatches (Terracotta Maroon, Desert Ochre, Ajrakh Indigo, Kutch Emerald, Sunset Crimson, Midnight Charcoal, Royal Purple, Ocean Blue).
  - **Frontend Theme Engine**: Injected dynamic primary color overrides into `:root` CSS variables (`--orange`, `--primary`, `--primary-color`), Tailwind runtime config, and dynamic SVG brand logo rects in header and footer.
  - **Testing**: Added unit & feature tests in `AdminSettingsTest` verifying color updates, hex validation, and frontend rendering. All **149/149 tests passed (1,530 assertions)**.
- **Pending Tasks**:
  - User review in browser.

---

### Dynamic Header, Hero & Footer Shades Adaptation
- **Date**: 2026-10-24
- **Feature**: Full-Site Header, Hero, Footer & Button Shade Harmonization with Primary Color
- **Branch**: `master`
- **Files Modified**:
  - `public/css/style.css`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/index.blade.php`
  - `resources/views/gallery.blade.php`
  - `resources/views/taxi.blade.php`
  - `tests/Feature/AdminSettingsTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Dynamic RGB Computation**: Extracted red, green, and blue components from the selected primary hex color in `app.blade.php` (`--primary-rgb: r, g, b`).
  - **Header & Hero Shade Adaptations**: Replaced static red gradients (`rgba(255, 92, 26, ...)`, `rgba(158, 42, 43, ...)`) in `.page-hero::before` and `.hero-blob-1` with dynamic primary color radial glow gradients (`rgba(var(--orange-rgb), 0.24)`). Styled `.nav` and `.nav.scrolled` borders and ambient glow to match the primary shade.
  - **Home Hero Header Section**: Bound `.hero` and `.hero::before` to dynamic radial gradients (`rgba(var(--primary-rgb), 0.28)`), eliminating the static reddish glow on the main home page header.
  - **Crisp Dark Footer Contrast**: Restored solid dark `#140E0D` footer base without foggy washed-out center wash, retaining an elegant primary-colored top accent line (`border-top: 1px solid rgba(var(--primary-rgb), 0.25)`).
  - **Bottom CTA Banner Theme**: Fully themed the home page bottom CTA banner (`.cta-banner`) with a dynamic primary radial gradient aura, primary-accented badge pill, and themed borders.
  - **All-Photos Gallery Button Fix**: Removed hardcoded `#9E2A2B` in `gallery.blade.php` `.gallery-filter-btn.active` and `:hover`, binding them to `var(--orange)` and dynamic primary box-shadow.
  - **Explore Tour Packages CTA Fix**: Replaced inline `style="background:#9E2A2B"` on hero package button in `index.blade.php` with `style="background:var(--orange)"`.
  - **Taxi Capacity Filter Tabs**: Updated `taxi.blade.php` capacity buttons to use dynamic `var(--orange)` active and hover states without hardcoded red.
  - **Testing**: Added RGB shade and element assertions in `AdminSettingsTest.php`. All 20 tests passed.
- **Pending Tasks**:
  - Completed and harmonized.

---

### Footer & CTA Banners Dynamic Dark Midnight Shade Harmonization
- **Date**: 2026-10-24
- **Feature**: Harmonize Footer, Inner Page CTA Banners & Home Hero with Primary Color Dark Midnight Shade
- **Branch**: `master`
- **Files Modified**:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `resources/views/about.blade.php`
  - `resources/views/packages.blade.php`
  - `resources/views/index.blade.php`
  - `tests/Feature/AdminSettingsTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Dynamic Midnight Dark Shade Base**: Replaced all hardcoded red-charcoal bases (`#16100E`, `#140E0D`, `#120D0C`) with mathematically computed `$darkMidnight` and `$darkLight` derived directly from `$primaryColor` in `app.blade.php`, dynamically overriding `--navy` and `--navy-light`.
  - **Footer Shade (Identical to Header)**: Removed hardcoded `bg-[#16100E]` from `partials/footer.blade.php`. Styled `.footer` with `background-color: var(--navy)` and top radial glow `radial-gradient(ellipse at 50% 0%, rgba(var(--primary-rgb), 0.22) 0%, transparent 70%)` plus top primary accent hairline.
  - **Bottom CTA Banners (Identical to Header, No White Wash)**: Standardized all bottom CTA banners across `about.blade.php`, `packages.blade.php`, and `index.blade.php` with `.cta-banner`. Replaced the previous hazy radial gradient with a clean, dark colored ambient glow `radial-gradient(ellipse at 50% 50%, rgba(var(--primary-rgb), 0.22) 0%, transparent 70%)` on the midnight base with no whitish or red cast.
  - **Home Hero Red Removal**: Removed leftover `bg-[#16100E]` and hardcoded `text-[#9E2A2B]` from `index.blade.php` hero section, binding accents to `var(--orange)` and `.hero` to `$darkMidnight`.
  - **Testing**: Added assertions in `AdminSettingsTest` for `--navy`, `.footer::before`, and `.cta-banner::before`. Full suite: **149/149 tests passed (1,538 assertions)**.
- **Pending Tasks**:
  - User review in browser.

---

### Core SEO Infrastructure & Schema Optimization
- **Date**: 2026-10-24
- **Feature**: XML Sitemap, Hardened Robots.txt, and Enhanced JSON-LD Structured Data
- **Files Modified**:
  - `app/Http/Controllers/SitemapController.php` (created)
  - `resources/views/sitemap.blade.php` (created)
  - `routes/web.php`
  - `public/robots.txt`
  - `resources/views/index.blade.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/packages.blade.php`
  - `resources/views/package-show.blade.php`
  - `tests/Feature/FrontendTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Dynamic XML Sitemap (`/sitemap.xml`)**: Created `SitemapController` and `sitemap.blade.php` delivering standards-compliant XML (`application/xml`). Lists all canonical static routes (`/`, `/packages`, `/taxi`, `/gallery`, `/about`, `/contact`) and dynamically queries all published `Package` models with `lastmod`, change frequency, and priority weighting.
  - **Hardened `robots.txt`**: Added disallow directives for `/admin/` and declared the `Sitemap: /sitemap.xml` reference to enable search engine crawlers while protecting the admin portal from indexing.
  - **Dynamic FAQ Schema (`FAQPage`)**: Enhanced `index.blade.php` `@section('schema_json')` with Google rich snippet FAQ schema, iterating published `$faqs` with automated sanitization (`json_encode` + `strip_tags`).
  - **Local Business & Travel Agency Schema**: Enriched `index.blade.php` schema with `@graph`, geo-coordinates for Bhuj, areaServed regions, phone, and URLs.
  - **Taxi Service Schema (`TaxiService`)**: Added schema markup and structured `BreadcrumbList` on `taxi.blade.php`.
  - **Tour Package Schemas (`TouristTrip`, `ItemList`, `BreadcrumbList`)**: Added `ItemList` to `packages.blade.php` and `TouristTrip` + `BreadcrumbList` + dynamic OpenGraph tags (`og:title`, `og:description`, `og:image`) to `package-show.blade.php`.
  - **Testing**: Added unit/feature tests in `FrontendTest.php` verifying XML header, content, and schema rendering. Full test suite: **151/151 tests passed (1,553 assertions)**.
- **Pending Tasks**:
  - Submit `https://yourdomain.com/sitemap.xml` to Google Search Console upon domain launch.
  - Set up Google Business Profile in Bhuj to strengthen local map pack signals.

---

### Seamless Continuous Ticker Ribbon & Clickable Breadcrumbs
- **Date**: 2026-10-24
- **Feature**: Infinite Non-Stop Marquee Ticker & Interactive Clickable Page Breadcrumbs
- **Files Modified**:
  - `public/css/style.css`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/index.blade.php`
  - `resources/views/packages.blade.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/package-show.blade.php`
  - `resources/views/gallery.blade.php`
  - `resources/views/about.blade.php`
  - `resources/views/contact.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Infinite Continuous Rolling Ticker**: Replaced single track marquee with dual identical `.ticker-group` elements inside `.ticker-track` in `index.blade.php`. Updated CSS with `@keyframes tickerContinuous` (`0%` to `-50%` over 28s linear infinite) with `width: max-content` and without pause on hover, eliminating all empty gaps, blank rows, or stops.
  - **Clickable Breadcrumb Navigation**: Fixed an issue where `.page-hero::before` pseudo-element had no `pointer-events: none` and was intercepting clicks. Added `pointer-events: none !important` to `.page-hero::before` in `style.css` and `layouts/app.blade.php`. Elevated `.breadcrumb` to `z-index: 20` across `packages.blade.php`, `taxi.blade.php`, `package-show.blade.php`, `gallery.blade.php`, `about.blade.php`, and `contact.blade.php` with distinct underline, cursor pointer, and gold hover effects, allowing users on any page to click "Home" (or "Tour Packages") directly.
  - **Testing**: Ran full PHPUnit feature & unit test suite: **151/151 tests passed (1,553 assertions)**.
- **Pending Tasks**:
  - User verification in browser.

---

### End-to-End Admin Panel CRUD & Frontend Reflection Verification
- **Date**: 2026-10-24
- **Feature**: Comprehensive End-to-End CRUD Testing across Admin Panel and Public Frontend
- **Branch**: `master`
- **Files Modified / Created**:
  - `tests/Feature/AdminToFrontendEndToEndCrudTest.php` (created)
  - `PROJECT_HISTORY.md`
- **Logic & Coverage**:
  - **Complete CRUD & Reflection Tested**:
    - **Settings & Theme**: Admin update of phone, WhatsApp, email, physical address, primary color hex, social links, terms & privacy -> verified on Home, About, Contact, dynamic CSS variables (`--primary-rgb`, `--orange`, `--navy`), and Admin Dashboard.
    - **Taxi Routes**: Admin Create -> DataTables AJAX -> Frontend Home & Taxi -> Admin Update -> Frontend Reflects -> Admin Unpublish -> Frontend Hides -> Admin Delete -> Database & Frontend Deletion.
    - **Vehicles Fleet, Types & Capacities**: Admin Create of Type, Capacity, and Vehicle -> Frontend Taxi Filter Tabs & Dropdown Reflection -> Frontend Fleet Grid Reflection -> Admin Update -> Frontend Reflects -> Admin Unpublish -> Frontend Hides -> Admin Delete.
    - **Destinations**: Admin Create -> DataTables -> Home Page "Must-Visit Destinations" -> Admin Update -> Frontend Reflects -> Unpublish -> Frontend Hides -> Delete.
    - **General FAQs**: Admin Create -> DataTables -> Home Page Accordion & `@graph` `FAQPage` JSON-LD Schema -> Admin Update -> Frontend Reflects -> Unpublish -> Delete.
    - **Gallery Categories & Photos**: Admin Create Category -> Frontend Filter Tab Reflection -> Photo Upload (File Validation & Storage) -> Gallery Grid Reflection -> Admin Update -> Frontend Reflects -> Unpublish -> Delete (with physical file cleanup).
    - **Package Builder (Custom Packages)**: Admin Proposal Creation (2P/4P/6P tiers, itineraries) -> DataTables -> DomPDF Export -> Proposal Update -> Delete.
    - **Invoices & Billing**: Tax Invoice Creation (sequence number generation, tax rates, vehicle distance calculation) -> DataTables -> DomPDF Tax Invoice Export -> Invoice Update -> Delete.
    - **Profile & Authentication Lifecycle**: Profile Update (with email change password guard) -> Password Change (session invalidation) -> Logout -> Login with new credentials.
  - **Gap Analysis Disclosed**:
    - Identified that Standard Tour Packages (`Package`) and Testimonials (`Testimonial`) currently originate from database seeders, as dedicated admin CRUD controllers exist only for Custom Packages (`CustomPackage`) rather than public standard packages.
    - Identified that public inquiries redirect straight to WhatsApp and the admin does not currently have an internal CRUD inbox for `inquiries`.
  - **Testing**:
    - Ran `AdminToFrontendEndToEndCrudTest.php`: **9/9 tests passed (181 assertions)**.
    - Ran full test suite: **160/160 tests passed (1,734 assertions)** with zero failures.
- **Pending Tasks**:
  - Implemented Tour Packages, Testimonials, and Customer Inquiries dynamic management.

---

### Dynamic Admin Modules: Public Tour Packages, Testimonials & Inquiries Management
- **Date**: 2026-10-24
- **Feature**: Dynamic Admin Management for Public Tour Packages, Customer Testimonials, and Inquiries Inbox
- **Branch**: `master`
- **Files Modified / Created**:
  - `app/Models/Inquiry.php` (created)
  - `app/Models/Testimonial.php` (enhanced with parseYoutubeId helper)
  - `app/Http/Requests/Admin/PackageRequest.php` (created)
  - `app/Http/Requests/Admin/PackageTableRequest.php` (created)
  - `app/Http/Requests/Admin/TestimonialRequest.php` (created)
  - `app/Http/Requests/Admin/TestimonialTableRequest.php` (created)
  - `app/Http/Requests/Admin/InquiryTableRequest.php` (created)
  - `app/Http/Controllers/Admin/PackageController.php` (created)
  - `app/Http/Controllers/Admin/TestimonialController.php` (created)
  - `app/Http/Controllers/Admin/InquiryController.php` (created)
  - `app/Http/Controllers/HomeController.php` (added storeInquiry endpoint with flexible alias support)
  - `resources/views/admin/packages/index.blade.php` (created)
  - `resources/views/admin/packages/cell.blade.php` (created)
  - `resources/views/admin/packages/form.blade.php` (created)
  - `resources/views/admin/testimonials/index.blade.php` (created)
  - `resources/views/admin/testimonials/cell.blade.php` (created)
  - `resources/views/admin/testimonials/form.blade.php` (created)
  - `resources/views/admin/inquiries/index.blade.php` (created)
  - `resources/views/admin/inquiries/cell.blade.php` (created)
  - `resources/views/admin/inquiries/show.blade.php` (created)
  - `resources/views/admin/partials/navigation.blade.php` (added Tour packages, Inquiries, and Testimonials links)
  - `resources/views/layouts/app.blade.php` (added meta csrf-token)
  - `resources/views/contact.blade.php` (asynchronously persists inquiry before WhatsApp redirection)
  - `routes/web.php` (registered public inquiry endpoint and admin resource routes)
  - `tests/Feature/AdminDynamicModulesTest.php` (created)
  - `PROJECT_HISTORY.md`
- **Logic & Implementation**:
  - **Public Tour Packages CRUD (`admin.packages.*`)**:
    - Full admin management of public tour packages (`packages`, `package_itineraries`, `package_features`).
    - Multi-tier pricing fields (2P Couple, 4P Family, 6P Group), package type (`Kutch` / `Outstation`), auto-slug generation, featured badge, and image uploading to `storage/app/public/packages`.
    - Interactive day-by-day itinerary repeater with title, spots visited, description, and meal notes.
    - Plaintext multi-line inclusions and exclusions synced to relational `package_features` table (`type = 'inclusion'` / `'exclusion'`).
    - Immediate reflection on public `/packages`, `/package-{slug}`, and Home page (`/`).
  - **Customer Testimonials CRUD (`admin.testimonials.*`)**:
    - Full admin management of guest reviews (`testimonials`).
    - Supports client name, tour taken, review text, YouTube video link (auto-extracted video ID), display order, and published toggle.
    - Immediate reflection on public Home page guest reviews section.
  - **Customer Inquiries Inbox (`admin.inquiries.*`)**:
    - Added public API endpoint (`POST /inquiry`) capturing contact page submissions into `inquiries` table before opening WhatsApp.
    - Admin data-table listing with search across customer name, phone, email, and service, plus status filtering (`new`, `contacted`, `closed`).
    - Detailed view (`show.blade.php`) displaying customer details, service type, pickup/origin, destination, travel dates, passenger count, customer message, one-click phone call, and direct WhatsApp reply.
    - Admin follow-up status tracking and internal admin notes.
  - **Testing & Verification**:
    - Created `tests/Feature/AdminDynamicModulesTest.php`: **6/6 tests passed (105 assertions)**.
    - Executed full test suite: **166/166 tests passed (1,839 assertions)** with zero failures and zero regressions.
- **Pending Tasks**:
  - Implemented sidebar scrolling fix and navigation naming update.

---

### Admin Sidebar Scrollability & Personalised Package Builder Naming
- **Date**: 2026-10-24
- **Feature**: Scrollable Admin Sidebar & Navigation Renaming
- **Branch**: `master`
- **Files Modified**:
  - `resources/views/components/admin/layout.blade.php`: Added `overflow-y-auto` with clean thin scrollbar styling to desktop `<aside>` and mobile `<dialog>` drawer to allow full scrolling through all navigation items regardless of screen resolution.
  - `resources/views/admin/partials/navigation.blade.php`: Renamed `Package builder` to `Personalised package builder` and optimized spacing.
  - `PROJECT_HISTORY.md`
- **Verification**:
  - Verified with `npm.cmd run build` (Vite assets compiled in 8.78s).
  - Executed full test suite: **166/166 tests passed (1,839 assertions)**.

---

### Middle Desert Emblem Logo Integration & Brand Gold Theming
- **Date**: 2026-10-24
- **Feature**: Figma Middle Logo Variant Integration, High-DPI Vector/Raster Assets, Admin Theming, & Brand Color Alignment
- **Files Modified**:
  - `public/images/logo/logo-gold.svg`, `public/images/logo/logo-black.svg`, `public/images/logo/logo-white.svg`
  - `public/images/logo/logo-gold.png`, `public/images/logo/logo-black.png`, `public/images/logo/logo-white.png`, `public/images/logo/logo-gold-dark-card.png`
  - `public/images/logo.svg` & `public/images/favicon.svg`
  - `storage/app/public/branding/*`
  - `app/Models/Setting.php`
  - `database/seeders/SettingSeeder.php`
  - `resources/views/partials/header.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/admin/settings/edit.blade.php`
  - `resources/views/components/admin/branding-upload.blade.php`
- **Logic & Implementation**:
  - **Vector Extraction**: Extracted and parsed the middle heritage emblem (Kutch camel, desert tent, and sand dunes within an arched dome) directly from the user's Figma vector data.
  - **Dynamic Logo Rendering**: Configured light background surfaces to use the pure black variant (`#0A0A0A`) and dark background surfaces (including header and footer) to use the gold variant (`#E7BE67`).
  - **Exact Color Alignment**: Extracted exact vector fill `#E7BE67` from the logo artwork and aligned the entire site theme, swatch palette, CSS variables, and admin settings defaults to this warm desert gold.
  - **Verification**:
    - `tests/Feature/AdminSettingsTest.php`: 20/20 passed.
    - `tests/Feature/AdminToFrontendEndToEndCrudTest.php`: 9/9 passed.
    - Full test suite: **166/166 passed (1,839 assertions)**.

---

### Brand Logo Visibility & Dimension Scaling
- **Date**: 2026-10-24
- **Feature**: Logo ViewBox Tight Cropping & Navigation Sizing Scaling
- **Files Modified**:
  - `public/images/logo/logo-gold.svg`, `public/images/logo/logo-black.svg`, `public/images/logo/logo-white.svg`, `public/images/logo.svg`
  - `storage/app/public/branding/*`
  - `public/css/style.css`
  - `resources/views/partials/header.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/components/admin/brand.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Tight ViewBox Cropping**: Replaced original `0 0 750 570` viewBoxes (which had ~75px of blank padding on all sides) with tight bounding boxes fitted to the actual vector coordinates (`74 72 602 426`), allowing 100% of the SVG element to display the artwork.
  - **Header Navigation Expansion**: Increased `.nav` height from 68px to 78px (64px scrolled) and expanded logo height from 48px (effective 35px artwork) to 64px (effective 64px artwork, 90px wide), yielding a ~3.3x increase in visual display area.
  - **Footer Logo Scaling**: Expanded footer emblem height from 60px to 88px (124px wide) for clear legibility of the arch, camel, tent, and typography.
  - **Admin Brand Sidebar**: Scaled admin brand logo from `h-11` to `h-12 max-w-40`.
  - **Cache & Assets**: Recompiled Vite bundle (`npm run build`) and cleared Laravel application caches.

---

### Header Logo Upscaling & Expanded Navigation Pill
- **Date**: 2026-10-24
- **Feature**: Logo Scaling to 88px & Navigation Pill Height Expansion to 98px
- **Files Modified**:
  - `public/images/logo/logo-gold.svg`, `public/images/logo/logo-black.svg`, `public/images/logo/logo-white.svg`, `public/images/logo.svg`
  - `storage/app/public/branding/*`
  - `public/css/style.css`
  - `resources/views/partials/header.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Applied exact zero-margin bounding boxes to all SVG vectors (`viewBox="78 75 595 421"`).
  - Scaled header navigation pill height to 98px desktop (72px scrolled, 86px tablet, 76px mobile) with 32px padding.
  - Increased header logo height to 88px (~125px width) desktop, 74px tablet, and 64px mobile.
  - Scaled footer logo to 100px height (~141px width).
  - Rebuilt Vite bundle (`npm run build`) and cleared application caches (`php artisan optimize:clear`).

---

### Home Page Taxi-First Redesign & Dynamic Tour Packages
- **Date**: 2026-10-25
- **Feature**: Road to Heaven Centered Hero, Primary Taxi & Cab Sections, Dribbble Journey Cards & Dynamic Package Toggling
- **Files Modified**:
  - `resources/views/partials/header.blade.php`
  - `resources/views/index.blade.php`
  - `public/css/style.css`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Navbar Reordering & Dynamic Packages**: Re-ordered header links so `Taxi Services` immediately follows `Home`. Added dynamic check `\App\Models\Package::where('is_published', true)->exists()` to conditionally render the `Tour Packages` link in both desktop and mobile slideout navigation. If 0 packages are published, `Tour Packages` is completely hidden.
  - **Centered Road to Heaven Hero**: Replaced the 2-column split with a full-bleed aerial `road_to_heaven_2.webp` background with a cinematic dark gradient scrim. Centered all typography, headlines, and CTAs. Removed the 3 floating image cards. Focused copy on **Kutch Taxi & Cab Services**, Bhuj Airport/Station pickups, and transparent per-km billing.
  - **Dribbble-Inspired Route Cards**: Upgraded popular taxi routes with an origin-to-destination connector banner, distance, estimated travel time, transparent round-trip/one-way pricing, and instant WhatsApp booking.
  - **AC Taxi Fleet Showcase**: Positioned clean vehicle cards right below popular routes with commercial capacity badges, rate per km (`₹13/km`), and WhatsApp cab booking.
  - **Why Choose Us Service Standards**: Added 4-pillar trust section (24/7 Bhuj station pickup, experienced Kutchi chauffeurs, transparent per-km rates with zero surge, and sanitized AC cabs).
  - **Conditional Secondary Tour Packages**: Placed tour packages section below taxi services, wrapped in `@if($packages->isNotEmpty()) ... @endif` so that if no packages are added, the section does not render on the home page.
  - **Enhanced SEO & Rich Snippets**: Added `TaxiService` structured data to JSON-LD `@graph` covering Bhuj station, airport, White Rann, and outstation routes.
- **Verification**:
  - Compiled Vite assets: `npm.cmd run build` (63 modules transformed, 0 errors).
  - Cleared caches: `php artisan view:clear` and `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).
  - Ran `AdminToFrontendEndToEndCrudTest.php`: 9/9 passed (181 assertions).
  - Ran full test suite: **166/166 passed (1,839 assertions)** with zero failures and zero regressions.

---

### UI Polish: Top Bar Elimination, Green Glow Removal & Editorial Image-Backed Cards
- **Date**: 2026-10-25
- **Feature**: Full-Bleed Top Hero Flush Behind Navbar, Green Shadow Removal & Photo Overlay Trust Cards
- **Files Modified**:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/index.blade.php`
  - `public/css/style.css`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Removed Top White Gap**: Reset `body[data-page="Home"] .page-wrapper` padding-top to `0 !important` and set `body[data-page="Home"]` background to dark midnight shade. Styled `.hero-taxi-centered` with `padding-top: 140px` (desktop), `120px` (tablet), and `105px` (mobile), letting the Road to Heaven aerial background seamlessly span from `top: 0` directly behind the fixed floating navbar without any white bar or strip.
  - **Removed Green Neon Highlights**: Removed `box-shadow: 0 10px 24px rgba(37,211,102,0.35)` from both the hero and bottom CTA WhatsApp booking buttons, eliminating the neon glow halo behind the buttons.
  - **Single-Line Section Heading**: Expanded the "Why Choose Us" container to `max-w-[960px]` and styled the heading as `Why Choose Us for Your Kutch Taxi Rentals`, fitting on a single clean line on desktop.
  - **Editorial Photo Overlay Cards**: Replaced generic pastel-icon white boxes with 4 photography-first editorial cards with rich gradient scrims, subtle image zoom on hover (`scale-110`), and gold numbering tags (`01 • Punctual Pickup`, `02 • Local Expertise`, `03 • Zero Hidden Fees`, `04 • Comfort & Safety`). Completely removed emoji icons and AI-typical card templates.
- **Verification**:
  - Rebuilt Vite bundle: `npm.cmd run build` (built in 6.75s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).
  - Ran `AdminToFrontendEndToEndCrudTest.php`: 9/9 passed (181 assertions).

---

### Home Page Redesign: Destinations Carousel, Entrance Animations, Card Highlights & FAQ Dropdowns
- **Date**: 2026-10-25
- **Feature**: Auto-Advancing Destinations Carousel, Hero Clearance & Staggered Animations, Kutch Holidays Card Physics, Full-Card Clickable Tour Packages, Elevated Reviews & Interactive FAQ Dropdowns
- **Files Modified**:
  - `resources/views/index.blade.php`
  - `public/css/style.css`
  - `public/js/carousel.js`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Hero Clearance & Breathing Room**: Increased `.hero-taxi-centered` top padding to `175px !important` on desktop and `135px !important` on mobile, providing ~60px of breathing space between the floating 98px navbar and the eyebrow badge. Added top margin to the badge so it does not crowd the navbar.
  - **Page Load Entrance Animations**: Integrated staggered `fadeUp` keyframe animations matching `kutch-holidays` across the hero badge (`0s`), H1 headline (`0.1s`), subheading (`0.2s`), CTAs (`0.3s`), contacts (`0.35s`), and trust statistics (`0.4s`), giving a fluid initial page entrance.
  - **Auto-Advancing Destinations Carousel**: Converted the 6-destination grid into an auto-playing carousel (`.carousel.destinations-carousel`) with smooth slide transitions, 4-second auto-play intervals (`public/js/carousel.js`), pause-on-hover, prev/next arrows, and dot indicators. Each slide features a high-definition photograph, gradient scrim, the place name, and a crisp one-liner significance description.
  - **Kutch Holidays Card Highlight Physics**: Applied dynamic hover interactions to vehicle fleet cards (`.vehicle-card`), route cards (`.route-card`), and package cards (`.pkg-card`) with smooth 6px elevation, `--orange` (#9E2A2B) border transitions, multi-layer soft drop shadows (`0 20px 40px -10px rgba(22, 16, 14, 0.12)`), and smooth image zooming (`scale-108`).
  - **Full-Card Clickable Tour Packages**: Redesigned `.pkg-card` with editorial badges and made the entire card surface clickable to navigate directly to the package details page (`onclick="window.location='{{ route('package.show', $pkg->slug) }}'"`), while keeping the explicit `Details →` button active and stopping event propagation on the WhatsApp booking button.
  - **Elevated Guest Reviews**: Redesigned testimonial cards with gold 5.0 rating stars, quotation accents, two-letter avatar initials circles, and verified guest checkmark badges.
  - **Interactive FAQ Accordion**: Converted static FAQ cards into an interactive accordion dropdown (`.faq-item`, `.faq-q`, `.faq-icon`, `.faq-a`). Questions feature comfortable font sizes (`1.02rem`), animated `＋`/`－` icon rotation, smooth reveal transitions, and crisp, easily understandable answers.
  - **Bottom CTA Copy**: Updated heading from `"Book Your Clean AC Cab in Minutes"` to `"Book Your AC Cab Now"`.
- **Verification**:
  - Compiled Vite assets: `npm.cmd run build` (built in 4.82s, 0 errors).
  - Cleared Laravel caches: `php artisan optimize:clear`.
  - Ran full test suite: **166/166 passed (1,839 assertions)** with zero failures and zero regressions.

---

### Home Page & Footer Polish: Primary Color Highlights, Infinite Circular Carousel & Conditional Footer Links
- **Date**: 2026-10-25
- **Feature**: Brand Primary Color Card Highlights, Continuous Wheel Carousel, One-Liner Significance Clamping & Footer Quick Links Conditioning
- **Files Modified**:
  - `resources/views/index.blade.php`
  - `public/css/style.css`
  - `public/js/carousel.js`
  - `resources/views/partials/footer.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Brand Primary Color Card Highlights**: Replaced all red (`#9E2A2B`) card hover borders, shadow rings, titles, and badge accents in **Popular Kutch Taxi Routes**, **Our Clean AC Taxi Fleet**, and **Top-Rated Kutch Tour Packages** with the website's primary desert gold brand color (`#DCA134` / marigold). Card hover glow now projects `box-shadow: 0 20px 40px -10px rgba(220, 161, 52, 0.22), 0 0 0 1.5px #DCA134` and `border-color: #DCA134 !important`.
  - **Continuous Infinite Wheel Carousel**: Completely rewrote `public/js/carousel.js` using cloned boundary slides (`firstClone` / `lastClone`) and seamless `transitionend` coordinate resets. The carousel now rotates continuously like a circular wheel without rewinding or sliding backward through all slides upon completion. Auto-advance interval set to a relaxed 5.5s with a smooth, natural decelerating transition curve (`cubic-bezier(0.25, 1, 0.5, 1)`).
  - **Carousel Geometry & Typography**:
    - Removed black borders entirely (`border: none !important;`).
    - Increased carousel height to `560px` (desktop) and `420px` (mobile).
    - Removed the destination counter badge ("Destination 1 of 6" / "01 of 06").
    - Clamped destination significance text to strictly one crisp line using `line-clamp-1 max-w-[820px]`.
  - **Footer Tour Packages Removal & Conditional Inclusion**:
    - Removed the standalone "Tour Packages" footer column from `resources/views/partials/footer.blade.php`.
    - Consolidated footer into 3 balanced columns (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3`).
    - Inside the "Quick Links" column, conditionally rendered the "Tour Packages" link (`@if(\App\Models\Package::where('is_published', true)->exists())`) matching the navbar and homepage logic.
- **Verification**:
  - Rebuilt assets: `npm.cmd run build` (built in 4.72s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.
  - Skipped full test suite per explicit user instruction ("dont run all test all time, this time dont run").

---

### Home Page Refinements: 3-Col Stats Spacing, Primary Cab Enquire Buttons, Clean Carousel Slide Navigation & FAQ Minus Icons
- **Date**: 2026-10-25
- **Feature**: Centered 3-Column Trust Numbers, Primary Themed Cab "Enquire" WhatsApp Buttons, AC Badge Removal, Carousel Image Click Slide Navigation, Primary Tour Package "Book Now" Button, and FAQ Plus/Minus Toggle Icons
- **Files Modified**:
  - `resources/views/index.blade.php`
  - `resources/views/partials/lightbox.blade.php`
  - `public/css/style.css`
  - `public/js/carousel.js`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Centered 3-Column Trust Numbers**: Adjusted `.hero-stats` layout to `grid grid-cols-3 gap-2 sm:gap-8 max-w-[620px] mx-auto` (responsive font sizing `text-[1.5rem] sm:text-[2rem]`). Perfectly centers and balances the 3 statistic columns across mobile, tablet, and desktop without hanging single items on second rows.
  - **Cab Cards "Enquire" Button & AC Badge Removal**:
    - Removed the static top-right "AC" badge from vehicle card image containers.
    - Replaced the green cab button with the primary theme color (`style="background: var(--orange); color: var(--primary-text);"`).
    - Added an embedded WhatsApp SVG icon and updated button text to `"Enquire"`.
  - **Clean Destination Carousel Slide Navigation**:
    - Removed the description paragraph, "Book Cab", and "View in Gallery" buttons from carousel captions, keeping strictly the place name and photography.
    - Added visually hidden screen-reader accessibility (`<span class="sr-only">`) to preserve SEO and accessibility signals.
    - Decoupled `.car-slide` from `lightbox.blade.php` so images never open in an expanded modal.
    - Updated `public/js/carousel.js` and `.car-slide { cursor: pointer; }` so clicking anywhere on the carousel image/slide smoothly advances to the next slide (`next()`), in addition to the prev/next arrow buttons and swipe gestures.
  - **Top-Rated Tour Packages Primary Button**:
    - Replaced the green `bg-[#25D366]` button on tour package cards with the dynamic primary color (`background: var(--orange); color: var(--primary-text);`).
  - **FAQ Plus/Minus Accordion Toggle**:
    - Replaced the rotated text glyph with dedicated SVG plus and minus icons.
    - When open, `.faq-item.open` shows a crisp minus (`—`) icon in `var(--primary-text)` on the primary background, resolving contrast issues on gold/yellow themes and clearly indicating click-to-close behavior.
- **Verification**:
  - Compiled Vite assets: `npm.cmd run build` (built in 8.37s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).
  - Ran `AdminToFrontendEndToEndCrudTest.php`: 9/9 passed (181 assertions).
  - Ran full test suite: **166/166 passed (1,839 assertions)** with zero failures.

---

### UI & Content Polish: Destination Name Fixes, FAQ Icon Removal & Footer Admin Link Cleanup
- **Date**: 2026-10-25
- **Feature**: Destination Renames ("Road to Heaven" & "Mandvi Beach"), FAQ Right Icon Removal, and Footer Admin Portal Link Removal
- **Files Modified**:
  - `database/seeders/DestinationSeeder.php`
  - `resources/views/index.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Destination Renames**:
    - Renamed destination record with `road_to_heaven_2.webp` from `"Kalo Dungar (Black Hill)"` to `"Road to Heaven"`.
    - Simplified `"Mandvi Beach & Vijay Vilas Palace"` to `"Mandvi Beach"`.
    - Updated both the active database records in `tours_db` and `database/seeders/DestinationSeeder.php`.
  - **Removed Icon from FAQs**:
    - Removed the right-hand icon element completely from `.faq-q` in `resources/views/index.blade.php`, leaving a clean, distraction-free accordion headline with primary bullet dot.
  - **Removed Admin Portal from Footer**:
    - Removed the `"Admin Portal"` link from the "Quick Links" column in `resources/views/partials/footer.blade.php`.
- **Verification**:
  - Cleared caches: `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).

---

### Taxi Page Filter Buttons, Card Hover & "Why Choose Us" Redesign
- **Date**: 2026-10-25
- **Feature**: Gallery-Style Capacity Filter Buttons, Dynamic Primary Color Card Hover Lift & Shadows, and Kutch Holidays Styled "Why Choose Us" Service Cards
- **Files Modified**:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/taxi.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Capacity Filter Buttons (Matching Gallery Style)**:
    - Styled all unselected `.filter-cap-btn` buttons with crisp white background, 1px `#e5e7eb` border, rounded corners, and gray text matching the gallery page filter buttons.
    - On hover (unselected): highlights border and text in dynamic primary color (`var(--orange)`), keeping the background clean white without filling the entire button.
    - On selection: fills with solid primary color (`var(--orange)`), white text, and subtle colored glow (`box-shadow: 0 2px 10px rgba(var(--orange-rgb), 0.35)`).
    - Added String casting to JavaScript `filterByCapacity` and `filterByType` with empty results fallback container.
  - **Fleet Cards Hover Effect**:
    - Applied smooth cubic-bezier lift (`transform: translateY(-6px)`), primary color border glow (`border-color: var(--orange)`), ambient primary drop shadow (`rgba(var(--orange-rgb), 0.14)`), and subtle vehicle image zoom (`scale(1.05)`).
    - Styled vehicle card capacity, category, and AC tags with primary accent borders and pill geometry.
  - **"Why Choose Us" Cards Overhaul (Ported from Kutch Holidays with Authentic Content)**:
    - Replaced basic emoji placeholders with crisp, modern stroke SVG icons (Car/Shield for Clean Fleet, Compass for Local Route Masters, Rupee/Transparent badge for Honest Pricing, Zap for Instant WhatsApp Booking) encased in dynamic primary-tinted icon badges (`bg-[rgba(var(--orange-rgb),0.12)]`).
    - Added subtle watermark numbers (`01`, `02`, `03`, `04`) in top-right corner of each card.
    - Retained authentic user content for Clean & Sanitized Fleet, Local Route Masters, Honest & Transparent, and Instant WhatsApp Booking, supplemented with feature pills featuring primary colored dots.
    - Added full-width green WhatsApp booking action buttons (`Book on WhatsApp`) to every card.
- **Verification**:
  - Compiled Vite assets: `npm.cmd run build` (built in 5.50s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).
  - Ran `AdminSettingsTest.php`: 20/20 passed (197 assertions).

---

### Tour Packages Cards Smooth Hover Physics & Primary Color Theming
- **Date**: 2026-10-25
- **Feature**: Smooth Cubic-Bezier Hover Lift, Dynamic Primary Border & Glow Shadow on Tour Package Cards
- **Files Modified**:
  - `resources/views/packages.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Smooth Hover Lift & Shadows**: Styled `.pkg-card` across the tour packages listing page and globally in `app.blade.php` with `transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1)`.
  - **Dynamic Primary Accent**: On hover, the package card border glows with the admin backend primary color (`border-color: var(--orange)`), lifts smoothly by `-6px`, and casts an ambient colored drop shadow (`box-shadow: 0 16px 36px rgba(var(--orange-rgb), 0.14), 0 4px 12px rgba(0, 0, 0, 0.04)`), matching the taxi fleet cards.
  - **Subtle Image Zoom**: Enclosed the thumbnail image in `.relative overflow-hidden` with a smooth 0.4s ease transition (`scale(1.05)` on card hover).
- **Verification**:
  - Cleared caches: `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).

---

### Photo Gallery: 3-Column Layout & Expanded Image Dimensions
- **Date**: 2026-10-25
- **Feature**: 3-Column Grid Layout with Expanded Image Dimensions & Hover Lift
- **Files Modified**:
  - `resources/views/gallery.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **3-Column Grid**: Replaced 4-column layout (`grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4`) with an expanded 3-column layout (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7`).
  - **Larger Image Card Dimensions**: Increased card height from `h-[240px]` to responsive `h-[300px] sm:h-[320px] md:h-[350px]` with refined `14px` border radius, showcasing the photographs at larger scale.
  - **Enhanced Hover Physics & Legibility**: Added smooth cubic-bezier lift (`translateY(-6px)`), primary color border glow, ambient shadow (`rgba(var(--orange-rgb), 0.15)`), and larger caption text (`text-sm sm:text-base font-semibold`).
- **Verification**:
  - Cleared caches: `php artisan optimize:clear`.
  - Ran `FrontendTest.php`: 8/8 passed (42 assertions).

---

### About Us Page Human-Centered UX Overhaul
- **Date**: 2026-10-25
- **Feature**: Human-Centered Copy, Crisp Paragraphs, Bespoke Stroke SVG Service Cards, Real Photography Collage, 3-Step Simple Journey, and Local Office Contact Bar
- **Files Modified**:
  - `resources/views/about.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Human-Like, Crisp Copy (No Long Walls of Text)**:
    - Replaced generic, AI-typical paragraphs with short, bite-sized sentences focused on authentic local facts: Bhuj local drivers, airport/station pickups, direct WhatsApp dispatch, and transparent per-km billing.
    - Added quick-glance badge cards for Bhuj base, private fleet, native chauffeurs, and fixed quotes.
  - **Replaced Vibe-Coded Emoji Cards with High-UX Service Standards**:
    - Replaced emoji cards (`🛡️`, `🧭`, `💬`) with 4 structured, modern cards featuring watermark numbers (`01`, `02`, `03`, `04`) and bespoke stroke SVG icons in primary-tinted badges (`rgba(var(--orange-rgb), 0.12)`).
    - Added feature pill tags with primary bullet dots and dedicated WhatsApp booking action buttons on every card (`Book on WhatsApp`).
    - Added smooth cubic-bezier hover lift (`translateY(-6px)`), dynamic primary border glow, and drop shadows matching the taxi fleet cards.
  - **Real Local & Internet Photography Composition**:
    - Created an overlapping photo collage: authentic Kutchi folk musicians and camel celebrations on the White Rann (`rann-utsav-celebrations.webp` / `kutch_about_culture.webp`), secondary floating card with the Road to Heaven (`kutch_about_road.webp`), and an `8+ Years Local Expertise` pill badge.
    - Curated internet desert travel assets (`kutch_desert_sunset.jpg`, `desert_camel_safari.jpg`, `road_trip_traveler.jpg`) as resilient local fallbacks.
  - **Transparent Experience Numbers (Dark Midnight Base)**:
    - Added 4-item counter row on `var(--navy)` background (`8+ Years Local Experience`, `1,200+ Trips Completed`, `100% Verified Local Drivers`, `4.9 ★ Guest Review Score`).
  - **Simple 3-Step Booking Journey**:
    - Added a frictionless 3-step timeline (`01: Share Your Dates`, `02: Get Clear Written Quote`, `03: Meet Your Driver & Enjoy`) answering travelers' key questions upfront.
  - **Signature Destinations Showcase**:
    - Showcased 4 primary routes driven daily: Great White Rann (Dhordo), Road to Heaven & Dholavira, Bhuj Heritage & Crafts, and Mandvi Beach, with distance badges from Bhuj and one-click WhatsApp inquiries.
  - **Local Bhuj Office & Direct Calling Strip**:
    - Prominently integrated local physical address, direct phone calling link (`tel:...`), and WhatsApp link (`wa.me/...`) directly into the page.
- **Verification**:
  - Ran `php artisan test --filter=FrontendTest`: 8/8 passed (42 assertions).
  - Ran `php artisan test --filter=AdminToFrontendEndToEndCrudTest`: 9/9 passed (181 assertions).
  - Rebuilt Vite assets: `npm.cmd run build` (built in 4.67s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.

---

### About Us Button Guard & Contact Page Layout & Theme Harmonization
- **Date**: 2026-10-25
- **Feature**: Conditional Explore Tour Packages Button, Removal of Destinations/Office Strip from About Us, Contact Page WhatsApp Card Theming & Perfect Height Alignment
- **Files Modified**:
  - `resources/views/about.blade.php`
  - `resources/views/contact.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **About Us Conditional Button**:
    - Wrapped `Explore Tour Packages` button with `@if(\App\Models\Package::where('is_published', true)->exists()) ... @endif` so that if no packages are published, the button is hidden, matching the navigation header logic.
  - **Removed Destinations & Office Strip on About Us**:
    - Removed `Places We Guide & Drive Every Single Day` (4 destination cards) and the bottom 3 contact cards as requested, streamlining the page directly from the 3-Step Journey to the bottom CTA banner.
  - **Contact Us Page Theming & Equal Length Column Layout**:
    - **WhatsApp Card Primary Theming**: Themed the left WhatsApp card container and labels with the admin backend primary color (`rgba(var(--orange-rgb), 0.08)` and `border-[var(--orange)]/35`) while preserving the green WhatsApp circular logo and the green `Chat on WhatsApp` action button.
    - **Submit Button Primary Color**: Converted the form's `Send Booking Inquiry to WhatsApp` submit button from green to the primary theme color (`var(--orange)` with subtle shadow).
    - **Perfect Height Alignment**: Structured both columns using `items-stretch` and flex containers (`h-full flex flex-col justify-between`) so the left column cards and right column proposal form start and end at the exact same vertical lengths.
- **Verification**:
  - Ran `php artisan test --filter=FrontendTest`: 8/8 passed (42 assertions).
  - Ran `php artisan test --filter=AdminToFrontendEndToEndCrudTest`: 9/9 passed (181 assertions).
  - Recompiled Vite bundle: `npm.cmd run build` (built in 4.72s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.

---

### Admin Panel Sidebar Universal Scrollability & Flex Bounding Fix
- **Date**: 2026-10-26
- **Feature**: Universal Admin Sidebar Height Bounding & Cross-Device Scrollability Fix
- **Files Modified**:
  - `resources/views/components/admin/layout.blade.php`
  - `resources/views/admin/partials/navigation.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Fixed Aside Height Bounding**: Converted the desktop `<aside>` element from `lg:flex flex-col` to `lg:block`. Because `<aside>` is `fixed inset-y-0`, setting it to a block element provides an explicit viewport height boundary to its child.
  - **Flexbox Min-Height Resolution (`min-h-0`)**: Added `min-h-0` to the inner scroll wrapper `<div class="flex h-full min-h-0 flex-col overflow-y-auto ...">`. In flexbox, flex children default to `min-height: auto`, which in WebKit/Blink and on laptop/scaled displays causes the container to expand to fit content (~850px) rather than scrolling. `min-h-0` strictly caps container height to the viewport.
  - **Workspace Footer Preservation**: Added `shrink-0` to the bottom workspace information block in `navigation.blade.php` so it never gets compressed when scrolling on smaller viewports.
- **Verification**:
  - Recompiled Vite bundle: `npm.cmd run build` (built in 11.04s, 0 errors).
  - Cleared caches: `php artisan optimize:clear`.

---

### Save & Download PDF Infinite Loading Fix (Personalised Package Builder & Invoices)
- **Date**: 2026-10-26
- **Feature**: Save & Download PDF Asynchronous Download & Destination Redirection Resolution
- **Files Modified**:
  - `app/Http/Controllers/Admin/PackageBuilderController.php`
  - `app/Http/Controllers/Admin/InvoiceController.php`
  - `resources/js/admin-forms.js`
  - `tests/Feature/AdminPackageBuilderTest.php`
  - `tests/Feature/AdminInvoiceTest.php`
  - `PROJECT_HISTORY.md`
- **Root Cause**:
  - In `PackageBuilderController` and `InvoiceController`, the `'export'` action (`Save & Download PDF`) returned `{ redirect_url: route('...pdf', $model) }`.
  - When the client-side form handler in `admin-forms.js` executed `window.location.assign(destination)`, the browser processed the attachment download header (`Content-Disposition: attachment; filename=...`) without navigating or unloading the page.
  - Because the page was not unloaded and `succeeded` was set to `true`, the submit button was never restored and remained disabled in an infinite spinner loading state (`Saving…`), forcing users to manually click "Back" or "Cancel" to escape the page.
- **Logic Changes**:
  - **Controller Export Responses**: Updated both `store()` and `update()` methods in `PackageBuilderController` and `InvoiceController` to return both `download_url` (PDF generation endpoint) and `return_url` (the listing return destination), while preserving `redirect_url` for backward compatibility.
  - **Client-Side Download & Redirect Engine (`admin-forms.js`)**:
    - Detects PDF export actions via `download_url` or `/pdf` endpoints.
    - Asynchronously fetches the PDF as a Blob and triggers an instant client-side download via an object URL (`a.href = blobUrl; a.click()`).
    - After initiating the download, cleanly redirects the user to `destination` (`return_url` / index listing) where their saved record is immediately visible.
    - If no distinct return destination is configured, safely invokes `restore()` to restore the submit button from its busy state and displays the success feedback toast.
- **Verification**:
  - Ran `php artisan test --filter=AdminInvoiceTest`: 6/6 passed (60 assertions).
  - Ran `php artisan test --filter=AdminPackageBuilderTest`: 5/5 passed (51 assertions).
  - Ran `php artisan test --filter=AdminFeedbackTest`: 9/9 passed (126 assertions).
  - Ran `npm.cmd run test:js`: 25/25 passed (0 failures).
  - Recompiled Vite bundle: `npm.cmd run build` (built in 3.92s, 0 errors).
  - Cleared application caches: `php artisan optimize:clear`.
  - Restored `const result = await requestJson(...)` in `resources/js/admin-forms.js` that was accidentally omitted, recompiled Vite bundle (`built in 3.99s`), and verified with full test suites.

---

### Remove Short Description from Destinations (Frontend & Admin)
- **Date**: 2026-10-26
- **Feature**: Destinations Streamlining & Short Description Removal
- **Files Modified**:
  - `resources/views/index.blade.php`
  - `resources/views/admin/destinations/form.blade.php`
  - `resources/views/admin/destinations/cell.blade.php`
  - `resources/views/admin/destinations/index.blade.php`
  - `app/Http/Controllers/Admin/DestinationController.php`
  - `app/Http/Requests/Admin/DestinationRequest.php`
  - `app/Http/Requests/Admin/DestinationTableRequest.php`
  - `tests/Feature/AdminToFrontendEndToEndCrudTest.php`
  - `tests/Feature/AdminContentTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Ordering Retained**: Preserved the priority ordering (`orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc')`) on frontend and admin table.
  - **Frontend Cleaned**: Removed the hidden `sr-only` short description span from the destinations carousel caption in `index.blade.php`.
  - **Admin Form Simplified**: Removed the `short_description` textarea and updated subtitle in `admin/destinations/form.blade.php` to focus on destination name, image, and display order.
  - **Admin Table Optimized**: Removed the `description` column from `DestinationController.php` and `cell.blade.php`. Updated default DataTable order index to column index 2 (`sort_order`). Removed `short_description` from search filter.
  - **Sortable Columns Re-indexed**: Updated `sortableColumns()` in `DestinationTableRequest.php` from `3 => 'sort_order'` to `2 => 'sort_order'` to match the new column positions.
  - **Validation & Request**: Removed `short_description` from `DestinationRequest.php` validation rules and content fields.
  - **Database Intact**: Kept the underlying `destinations` table schema unchanged to maintain backward compatibility and follow database safety rules.
- **Verification**:
  - Ran `php artisan test --filter AdminToFrontendEndToEndCrudTest`: 9/9 passed (179 assertions).
  - Ran `php artisan test --filter AdminContentTest`: 18/18 passed (267 assertions).
  - Ran `php artisan test --filter FrontendTest`: 8/8 passed (42 assertions).
  - Ran `php artisan test --filter AdminDynamicModulesTest`: 6/6 passed (105 assertions).

---

### Tour Package Form Auto-Slug Generation & HTML5 Validation Resolution
- **Date**: 2026-10-26
- **Feature**: Tour Packages Live Auto-Slug Generation & Client Validation Fix
- **Files Modified**:
  - `resources/views/admin/packages/form.blade.php`
  - `PROJECT_HISTORY.md`
- **Root Cause**:
  - The URL Slug `<x-admin.field name="slug">` in `admin/packages/form.blade.php` had the HTML5 `required` attribute directly applied. When creating a package with "Auto-generate slug from title" checked and leaving the slug field blank, browser-level HTML5 validation intercepted form submission with `"Please fill out this field."`, blocking the request before the backend's auto-generation logic (`PackageRequest::prepareForValidation`) could run.
  - Additionally, there was no client-side live slug synchronization script to preview and fill the slug input as the user types.
- **Logic Changes**:
  - **Removed HTML5 Required Attribute**: Removed `required` from the slug field in `admin/packages/form.blade.php` so empty slugs can submit cleanly to backend auto-generation.
  - **Live Slug Sync Engine**: Added real-time client-side slugification listening to `input[name="title"]`, automatically generating lowercase URL-friendly slugs in real-time when `auto_slug` is checked.
  - **Interactive Unchecking**: If a user manually modifies the slug to a custom string, `auto_slug` unchecks automatically; re-checking it immediately re-generates the slug from the current title.
- **Verification**:
  - Ran `php artisan test --filter AdminDynamicModulesTest`: 6/6 passed (105 assertions).
  - Cleared compiled views: `php artisan view:clear`.

---

### CTA Banners Removal & Site-Wide WhatsApp Number Update
- **Date**: 2026-10-27
- **Feature**: Removed Bottom CTA Banners & Updated WhatsApp Number to +91 97732 39587
- **Files Modified**:
  - `resources/views/index.blade.php`: Removed the bottom "Book Your AC Cab Now" CTA banner section and updated WhatsApp number fallback.
  - `resources/views/about.blade.php`: Removed the bottom "Ready to Plan Your Kutch Journey?" CTA banner section and updated WhatsApp number fallback.
  - `resources/views/partials/footer.blade.php`: Updated WhatsApp link and formatted display to `+91 97732 39587 (WhatsApp)`.
  - `resources/views/contact.blade.php`: Updated WhatsApp card button text to `Chat on WhatsApp (+91 97732 39587)` and updated WhatsApp number fallback.
  - `resources/views/partials/header.blade.php`: Updated WhatsApp number fallback to `919773239587`.
  - `resources/views/layouts/app.blade.php`: Updated WhatsApp number fallback to `919773239587`.
  - `resources/views/taxi.blade.php`: Updated WhatsApp number fallback to `919773239587`.
  - `resources/views/packages.blade.php`: Updated WhatsApp number fallback to `919773239587`.
  - `resources/views/package-show.blade.php`: Updated WhatsApp number fallback to `919773239587`.
  - `resources/views/gallery.blade.php`: Updated WhatsApp number fallback to `919773239587`.
  - `public/js/components.js`: Updated `WA_NUMBER` constant to `'919773239587'`.
  - `database/seeders/SettingSeeder.php`: Updated default `whatsapp_number` to `'919773239587'`.
  - Database (`settings` table): Updated active record `whatsapp_number` to `'919773239587'`.
  - `tests/Feature/FrontendTest.php`: Updated test WhatsApp number expectations.
  - `tests/Feature/AdminToFrontendEndToEndCrudTest.php`: Updated test WhatsApp number expectations.
- **Verification**:
  - Executed full test suite: **167/167 tests passed (1,862 assertions)** with zero failures.
  - Cleared caches: `php artisan optimize:clear`.

---

### PDF Action Button Visibility & Brand Consistency Across Proposal and Invoice PDFs
- **Date**: 2026-10-27
- **Feature**: High-Visibility PDF Action Button & Unified Brand Theming in Custom Package and Invoice PDFs
- **Files Modified**:
  - `resources/views/admin/custom-packages/cell.blade.php`: Upgraded the PDF download button from a washed-out, low-contrast dark teal outline into a solid, high-visibility crimson button (`bg-red-600 hover:bg-red-700 text-white font-bold`) that stands out clearly in both light and dark mode.
  - `resources/views/admin/invoices/cell.blade.php`: Applied identical high-visibility solid PDF download styling for seamless admin consistency.
  - `resources/views/admin/custom-packages/pdf.blade.php`: Harmonized typography (`DejaVu Sans`), header layout, metadata container, and accents to dynamically derive from the primary color configured in Admin Settings (`$settings->primary_color`), removing hardcoded red (`#b91c1c`) styles. Computed dynamic contrast text and soft-tint borders/backgrounds for notes and cost tables.
  - `resources/views/admin/invoices/pdf.blade.php`: Replaced hardcoded green (`#047857`) styles with the exact same dynamic primary color palette, matching font family (`DejaVu Sans`), top-accented metadata box, table header styling, grand-total highlight box, and standardized brand footer.
  - `app/Http/Controllers/Admin/PackageBuilderController.php`: Enhanced `getLogoBase64` to prioritize dark/black logo on light PDF paper backgrounds and seamlessly resolve relative public disk paths and high-resolution logo fallbacks.
  - `app/Http/Controllers/Admin/InvoiceController.php`: Enhanced `getLogoBase64` with the same robust logo resolution logic.
- **Verification**:
  - `AdminPackageBuilderTest`: 5/5 tests passed (51 assertions).
  - `AdminInvoiceTest`: 6/6 tests passed (60 assertions).
  - `AdminToFrontendEndToEndCrudTest`: 9/9 tests passed (179 assertions).
---

### PDF Action Button Explicit Light & Dark Mode Compatibility & Production Asset Rebuild
- **Date**: 2026-10-27
- **Feature**: Full Light and Dark Mode Compatibility for Proposal and Invoice PDF Action Buttons
- **Files Modified**:
  - `resources/views/admin/custom-packages/cell.blade.php`: Upgraded PDF button styling to an explicit dual-mode badge (`border border-red-300 bg-red-50 text-red-700 shadow-xs hover:border-red-400 hover:bg-red-100 dark:border-red-700/80 dark:bg-red-950/60 dark:text-red-300 dark:hover:bg-red-900/60 dark:hover:text-red-100`), guaranteeing sharp crimson contrast on light rows and vibrant luminous red on dark rows.
  - `resources/views/admin/invoices/cell.blade.php`: Applied identical light & dark mode badge button styling.
  - `public/build/assets/app-lYWxSXn4.css`: Recompiled production CSS via `npm.cmd run build` to package all light and dark classes.
- **Verification**:
  - `npm.cmd run build`: Built cleanly with all utility classes.
  - `AdminPackageBuilderTest`: 5/5 tests passed (51 assertions).
  - `AdminInvoiceTest`: 6/6 tests passed (60 assertions).
  - Cleared caches: `php artisan optimize:clear`.

---

### Branded Frontend Gold Logo Integration on Proposal and Invoice PDFs
- **Date**: 2026-10-27
- **Feature**: Prioritized Frontend Gold Logo on Proposal and Invoice PDFs
- **Files Modified**:
  - `app/Http/Controllers/Admin/PackageBuilderController.php`: Updated `getLogoBase64` to prioritize the frontend gold logo (`images/logo/logo-gold.png` and `images/logo/logo-gold.svg`), matching the exact branding seen on the public website.
  - `app/Http/Controllers/Admin/InvoiceController.php`: Updated `getLogoBase64` with the same frontend gold logo priority for invoice PDF exports.
- **Verification**:
  - `AdminPackageBuilderTest`: 5/5 passed (51 assertions).
  - `AdminInvoiceTest`: 6/6 passed (60 assertions).
  - Cleared caches: `php artisan optimize:clear`.

---

### Dynamic Primary Color Theming for PDF Brand Logo
- **Date**: 2026-10-27
- **Feature**: Match PDF Logo Color with Active Admin Primary Brand Color
- **Files Modified**:
  - `app/Http/Controllers/Admin/PackageBuilderController.php`: Updated `getLogoBase64` to dynamically inject the active `$settings->primary_color` into the SVG logo vector fills strictly in memory when generating the PDF. This avoids washed-out pastel gold on white paper while preserving all static disk assets on the public website and admin panel untouched.
  - `app/Http/Controllers/Admin/InvoiceController.php`: Applied identical in-memory primary color vector fill mapping for invoice PDFs.
- **Verification**:
  - `AdminPackageBuilderTest`: 5/5 passed (51 assertions).
  - `AdminInvoiceTest`: 6/6 passed (60 assertions).
  - Cleared caches: `php artisan optimize:clear`.

---

### Contact Page Local Office Embedded Google Map with Pin Marker
- **Date**: 2026-10-27
- **Feature**: Embedded Google Map Iframe with Red Location Pin Marker on Local Office Card
- **Files Modified**:
  - `resources/views/contact.blade.php`: Embedded interactive Google Map `<iframe>` with centered red location pin marker (`https://maps.google.com/maps?q=...&output=embed`) inside the Bhuj Headquarters local office card, alongside an "Open in Maps" external action link.
  - `tests/Feature/FrontendTest.php`: Added assertions for `iframe-google-map` and embedded Google Map parameters.
- **Verification**:
  - `FrontendTest`: 8/8 passed (45 assertions).
  - Cleared caches: `php artisan optimize:clear`.

---

### Contact Page Wireframe Realignment & Non-Stretching Inquiry Box
- **Date**: 2026-10-27
- **Feature**: Wireframe Reorganization (Natural Height Inquiry Box + 2 Top Cards + Full-Width Map Below)
- **Files Modified**:
  - `resources/views/contact.blade.php`: 
    - Removed `items-stretch`, `h-full`, and `justify-between` from the inquiry form and container so the booking inquiry card sits at its natural, compact height with no awkward gaps.
    - Realigned the layout to match the user's wireframe sketch: Inquiry Form on the left column (`lg:col-span-6`), and on the right column (`lg:col-span-6`), placed WhatsApp Booking and Direct Call side-by-side (`sm:grid-cols-2`) with the Local Office & wide Google Map embedded card directly below them.
- **Verification**:
  - Full PHPUnit test suite: **167/167 tests passed (1,865 assertions)**.
  - Cleared caches: `php artisan optimize:clear`.

---

### Hostinger Poveda Travel Header & Navbar Redesign
- **Date**: 2026-10-27
- **Feature**: Hostinger Poveda-Inspired Luxury Travel Navigation Bar
- **Files Modified**:
  - `resources/views/partials/header.blade.php`: Retained all existing route links, dynamic package checks (`$hasPackages`), IDs (`#mainNav`, `#nav-link-*`, `#hamburger`, `#navMobile`), and WhatsApp CTA while cleaning up inline styles and standardizing the logo image class to `.nav-logo-img`.
  - `public/css/style.css`: Redesigned navigation bar from floating pill container to full-bleed edge-to-edge luxury header inspired by Hostinger's Poveda travel template:
    - Transparent top bar blending over hero background with subtle top-to-bottom dark gradient and ultra-fine border divider (`border-bottom: 1px solid rgba(255,255,255,0.08)`).
    - Compact frosted glassmorphism on scroll (`.nav.scrolled`) with `backdrop-filter: blur(16px)` and depth shadow.
    - Clean uppercase navigation links with wide letter spacing (`letter-spacing: 0.15em`), text shadow, and animated bottom accent underline on active/hover.
    - Pill-shaped "Book Now" CTA (`.nav-cta`) with smooth hover state and box shadow.
    - Full-width mobile slideout drawer (`.nav-mobile`) aligned to responsive tablet breakpoint (`1024px`).
- **Verification**:
  - Full PHPUnit test suite: **167/167 tests passed (1,865 assertions)**.
  - Cleared caches: `php artisan optimize:clear`.

---

### Navbar Alignment, Transparent Landing & WhatsApp CTA Row Alignment
- **Date**: 2026-10-27
- **Feature**: Centered Container Alignment, Pure Transparent Landing, and Single-Row Book Now CTA
- **Files Modified**:
  - `resources/views/partials/header.blade.php`: Wrapped inner navigation elements in a `.nav-container` wrapper to prevent items from stretching to the extreme edges on wide viewports and align comfortably with the website's central grid.
  - `resources/views/layouts/app.blade.php`: Removed golden border-color overrides and inset shadow injections on `.nav` and `.nav.scrolled`. Configured pure transparency at landing (`background: transparent; border: none; box-shadow: none;`) and a clean transparent dark shade on scroll (`background: rgba(16, 12, 10, 0.88)`).
  - `public/css/style.css`:
    - Defined `.nav-container` with `max-width: 1280px; width: 100%; margin: 0 auto; padding: 0 32px;` to position the logo and navigation links cleanly in the middle area.
    - Updated `.nav-links a::after` and active state to white underline accent, removing any golden shades or borders.
    - Updated `.nav-cta` to `display: inline-flex; flex-direction: row; align-items: center; gap: 8px; white-space: nowrap;` so the WhatsApp icon and "Book Now" label remain strictly on the same horizontal row.
- **Verification**:
  - Full PHPUnit test suite: **167/167 tests passed (1,865 assertions)**.
  - Cleared caches: `php artisan optimize:clear`.

---

### WhatsApp & Social Link Preview Branding & Title Customization
- **Date**: 2026-10-28
- **Feature**: OpenGraph Title Customization & Golden Logo Card for WhatsApp / Social Media Link Sharing
- **Files Modified**:
  - `resources/views/layouts/app.blade.php`:
    - Updated default `<title>`, `<meta property="og:title">`, and `<meta name="twitter:title">` to `"Kutch Tours and Taxi - Rann of Kutch Taxi and Tour Booking"`.
    - Switched OpenGraph default `og:image` and `twitter:image` from `white-rann-hero.webp` to `public/images/logo/logo-gold-dark-card.png`.
    - Added OpenGraph image metadata tags (`og:image:secure_url`, `og:image:type`, `og:image:width="1200"`, `og:image:height="630"`) for optimal crawler compatibility.
  - `resources/views/index.blade.php`:
    - Explicitly set `@section('title')`, `@section('og_title')` to `"Kutch Tours and Taxi - Rann of Kutch Taxi and Tour Booking"`.
    - Explicitly bound `@section('og_image')` to `images/logo/logo-gold-dark-card.png`.
  - `resources/views/package-show.blade.php`:
    - Updated fallback image in `@section('og_image')` to `images/logo/logo-gold-dark-card.png`.
  - `public/images/logo/logo-gold-dark-card.png`:
    - Formatted to 1200x630 (standard 1.91:1 Open Graph ratio, 79 KB) with centered golden brand emblem on midnight dark canvas (`#111111`) so WhatsApp renders without vertical or horizontal cropping.
- **Verification**:
  - View cache cleared: `php artisan view:clear`.
  - Image verified: 1200x630, 79 KB PNG.
- **Pending Tasks**:
  - Deploy changes to Hostinger production server.
  - Test link sharing on WhatsApp (or scrape again via Facebook Debugger to refresh cached preview).

---

### Storage Link & Unlink Web Routes
- **Date**: 2026-10-28
- **Feature**: Artisan Storage Link and Storage Unlink Web Endpoints
- **Files Modified**:
  - `routes/web.php`
- **Logic Changes**:
  - Added `use Illuminate\Support\Facades\Artisan;`.
  - Registered web routes for `/storage-link` and `/storage:link` executing `Artisan::call('storage:link')` (with optional `?force=1` query parameter support).
  - Registered web routes for `/storage-unlink` and `/storage:unlink` executing `Artisan::call('storage:unlink')`.
  - Both routes return preformatted command output for clear browser feedback.
- **Verification**:
  - Tested route registration via `php artisan route:list --path=storage`.
  - Verified route execution and HTTP status 200 responses.

---

### Hero Trust Badge: Former Brand Name Subtext
- **Date**: 2026-10-28
- **Feature**: Shree Ram Tours and Travels Former Brand Subtext on Hero Section
- **Files Modified**:
  - `resources/views/index.blade.php`
- **Logic Changes**:
  - Relocated former brand name notice from bottom void to directly above the stats divider line (`border-t border-white/10`).
  - Restyled into an elegant frosted glass pill badge (`rounded-full bg-white/[0.06] border border-[#E7BE67]/35 backdrop-blur-md shadow-sm`) with a golden star `✦` accent.
  - Highlighted "Shree Ram Tours and Travels" in brand gold (`#E7BE67`) with bold weight (`font-bold`) and balanced spacing (`mt-7` / `mt-6` / `pt-6`).
- **Verification**:
  - View cache cleared (`php artisan view:clear`).
- **Pending Tasks**:
  - Deploy to production.

---

### Storage 403 Forbidden Fix on Public Uploads
- **Date**: 2026-10-31
- **Feature**: Apache Root `.htaccess` Storage Rule Whitelisting
- **Files Modified**:
  - `.htaccess`
- **Logic Changes**:
  - Fixed root `.htaccess` rule blocking all requests to `/storage/*` with `403 Forbidden` (`RewriteRule ^(...|storage|...)/ - [F,L,NC]`).
  - Removed `storage` from the blanket directory blocklist so requests to `/storage/...` can be rewritten to `public/storage/...`.
  - Added targeted protection rule (`RewriteRule ^storage/(app/(?!public/)|framework|logs)/ - [F,L,NC]`) to keep internal logs, framework cache, and private files secure while allowing public uploads (`storage/vehicles/`, `storage/packages/`, `storage/gallery/`) to serve without 403 errors.
- **Pending Tasks**:
  - Upload updated `.htaccess` to Hostinger production server root (`public_html/.htaccess`).
  - Verify `https://kutchtoursandtaxi.com/storage/vehicles/7RhGRRwViQ7xdybtUdjgXCXz4uWj0ng9MiKgS8M0.webp` loads in the browser.

---

### Frontend Vehicle Image 404 Resolution via Storage Accessor
- **Date**: 2026-10-31
- **Feature**: Vehicle Image Storage URL Resolution & Frontend Template Synchronization
- **Files Modified**:
  - `app/Models/Vehicle.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/index.blade.php`
  - `resources/views/admin/vehicles/cell.blade.php`
  - `tests/Feature/FrontendTest.php`
- **Logic Changes**:
  - Identified root cause of frontend 404: backend uploads images to the `public` storage disk (`vehicles/...`), but frontend templates were calling `asset($veh->image_path)`, outputting `/vehicles/...` rather than `/storage/vehicles/...`.
  - Added `imageUrl` accessor attribute to `Vehicle` model via `Attribute::make()` that smoothly handles:
    - Empty/null paths (defaults to `images/taxi-page/dzire.webp`).
    - Full URLs (`http://` / `https://`).
    - Static asset paths in `public/` (e.g. seeded `images/taxi-page/...`).
    - Storage disk uploaded assets (`Storage::disk('public')->url($this->image_path)` -> `/storage/vehicles/...`).
  - Replaced hardcoded `asset($veh->image_path ?? 'images/taxi-page/dzire.webp')` with `$veh->image_url` across `taxi.blade.php` and `index.blade.php`.
  - Updated `admin/vehicles/cell.blade.php` to use `$vehicle->image_url` for unified image path resolution.
  - Updated `FrontendTest.php` to verify vehicle storage URL rendering on `/taxi`.
- **Verification**:
  - Executed `FrontendTest`: 8/8 tests passed.
  - Executed `AdminVehiclesTest`: 15/15 tests passed.
  - Executed `AdminToFrontendEndToEndCrudTest`: 9/9 tests passed.
- **Pending Tasks**:
  - Deploy changes to production server.

---

### Price for 12 Hours Field Integration across Cards & Backend
- **Date**: 2026-10-31
- **Feature**: 12-Hour Rental Package Pricing in Vehicle Cards and Admin Backend
- **Files Modified / Created**:
  - `database/migrations/2026_10_31_120000_add_price_for_12_hrs_to_vehicles_table.php` (created)
  - `app/Models/Vehicle.php`
  - `app/Http/Requests/Admin/VehicleRequest.php`
  - `resources/views/admin/vehicles/form.blade.php`
  - `resources/views/admin/vehicles/cell.blade.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/index.blade.php`
  - `database/seeders/VehicleSeeder.php`
  - `tests/Feature/AdminVehiclesTest.php`
  - `tests/Feature/FrontendTest.php`
  - `tests/Feature/AdminToFrontendEndToEndCrudTest.php`
- **Logic Changes**:
  - **Database Migration**: Created additive migration adding `price_for_12_hrs` (`decimal(12, 2)`, unsigned, nullable) positioned after `min_km_per_day` with clean `down()` rollback support.
  - **Eloquent Model**: Added `'price_for_12_hrs'` to `#[Fillable]` attribute and registered decimal casting `'price_for_12_hrs' => 'decimal:2'` in `Vehicle::casts()`.
  - **Validation & Request**: Added decimal/numeric validation (`decimal:0,2`, `min:0`, `max:9999999999.99`) and mapped `price_for_12_hrs` into `VehicleRequest::vehicleData()`.
  - **Admin Vehicle Management**:
    - `form.blade.php`: Added "Price for 12 hrs (₹)" numeric field under "Pricing and notes" with 3-column responsive layout and helpful tooltip hint.
    - `cell.blade.php`: Displayed `₹{amount} / 12 hrs` under the pricing column in the server-side DataTable.
  - **Public Customer Vehicle Cards**:
    - `taxi.blade.php`: Added dedicated row `Price for 12 hrs -` with formatted price (or `On request` fallback if unpriced) right above the WhatsApp booking button.
    - `index.blade.php`: Added `Price for 12 hrs -` alongside the per-km rate in the fleet showcase card.
  - **Seeders**: Configured realistic 12-hour local tour package rates in `VehicleSeeder.php` for standard sedans, SUVs, and luxury vans.
  - **Testing**: Updated `AdminVehiclesTest`, `FrontendTest`, and `AdminToFrontendEndToEndCrudTest` verifying persistence, validation, admin display, and public card rendering.
- **Pending Tasks**:
  - Run `php artisan migrate` when MySQL service is started.
  - Deploy changes to production server.

---

### Web Migration Endpoints (`/migrate` & `/migrate-status`)
- **Date**: 2026-10-31
- **Feature**: Browser-Triggered Artisan Migration Web Endpoints
- **Files Modified**:
  - `routes/web.php`
  - `tests/Feature/FrontendTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Registered web routes `/migrate` and `/migrate-status`:
    - `/migrate`: Executes `Artisan::call('migrate', ['--force' => true])` (or `migrate:status` when `?status=1` query parameter is present) and prints the console output inside `<pre>`.
    - `/migrate-status`: Executes `Artisan::call('migrate:status')` to inspect migration batch state.
    - Excluded `web` middleware group via `->withoutMiddleware('web')` so the route executes cleanly even if database session store (`sessions` table) is unmigrated or unavailable.
    - Wrapped execution in `try / catch (\Throwable $e)` returning clear error output and appropriate HTTP 500 status on database failure.
  - Added feature test in `FrontendTest.php` verifying HTTP 200 response and `<pre>` output.
- **Pending Tasks**:
  - Deploy to production and visit `https://<domain>/migrate` to run migrations.

---

### Taxi Fleet Filter Redesign: Stacked Capacity & Vehicle Type Filter Bar
- **Date**: 2026-10-31
- **Feature**: Responsive Stacked Filter Bar with Dedicated Capacity and Car Type Tiers
- **Files Modified**:
  - `resources/views/taxi.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Solved Filter Collision & Orphan Wrapping**: Relocated the layout from a cramped single flex row (`justify-between` where 10 capacity buttons collided with the Car Type dropdown and orphaned "56 Seater" on a separate line) to a clean, structured two-tier card layout.
  - **Tier 1 (Capacity Above)**: Placed passenger capacity pills on the top row spanning full container width, fitting all capacity buttons comfortably without premature wrapping on desktop screens and wrapping naturally on mobile.
  - **Tier 2 (Car Type Below)**: Placed car types on their own dedicated row below Capacity with matching pill buttons (`All Types`, `Sedan`, `SUV`, etc.), eliminating the clunky `<select>` dropdown in favor of immediate 1-click filtering.
  - **Alignment & Polish**: Standardized both label widths (`sm:w-24`) so filter pill buttons in both rows start at the exact same horizontal alignment. Added a subtle divider (`border-t border-[#f0f2f5]`) and header bar with a filter icon and an auto-appearing "Reset Filters" action button when any filter is active.
  - **Dynamic Branding**: Added `.filter-type-btn` to dynamic theme stylesheet in `layouts/app.blade.php` for seamless brand color synchronisation.
  - **Interactive Filtering Script**: Enhanced JavaScript `filterByType(typeId, btnEl)` and `filterByCapacity(cap, btnEl)` to update active pill states, support programmatic reset via `resetFilters()`, and toggle the reset button visibility.
- **Verification**:
  - Cleared Blade view cache (`php artisan view:clear`).
- **Pending Tasks**:
  - Deploy updated Blade templates to production server.

---

### Taxi Routes Multi-Vehicle Pricing & Fleet Rate Integration
- **Date**: 2026-11-05
- **Feature**: Multi-Vehicle Pricing per Route, Admin Table Inputs & Frontend Dynamic Dropdown with Fleet Rates
- **Files Modified / Created**:
  - `database/migrations/2026_11_05_140000_create_taxi_route_fares_table.php` (created)
  - `app/Models/TaxiRouteFare.php` (created)
  - `app/Models/TaxiRoute.php` (added `fares()` relationship)
  - `app/Models/Vehicle.php` (added `routeFares()` relationship)
  - `app/Http/Requests/Admin/TaxiRouteRequest.php` (added `vehicle_fares` validation and `vehicleFaresData()` extraction)
  - `app/Http/Controllers/Admin/TaxiRouteController.php` (eager-loaded `fares.vehicle`, passed vehicles to view, and synced vehicle fares on store/update)
  - `resources/views/admin/routes/form.blade.php` (added "Vehicle-specific pricing" table card with One-way & Round-trip fare inputs per fleet vehicle)
  - `resources/views/admin/routes/cell.blade.php` (rendered vehicle fare preview badges in the DataTables fares column)
  - `app/Http/Controllers/TaxiController.php` (eager-loaded `fares.vehicle.type` and `capacity` on routes)
  - `app/Http/Controllers/HomeController.php` (eager-loaded `fares.vehicle.type` and `capacity` on featured routes)
  - `resources/views/taxi.blade.php` (added vehicle selector dropdown in routes table, dynamic One-Way/Round-Trip fare updates, fleet specs line, and dynamic WhatsApp book URL)
  - `resources/views/index.blade.php` (added vehicle selector dropdown on route cards, round trip fare switch, fleet specs line, and dynamic WhatsApp book URL)
  - `database/seeders/TaxiRouteSeeder.php` (seeded 11 live server routes with Dzire, Ertiga, Crysta, and Tempo Traveller fares)
  - `tests/Feature/TaxiRouteVehicleFaresTest.php` (created 4 feature tests covering create, update, and frontend reflection)
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Relational Schema**: Created `taxi_route_fares` table linking `taxi_route_id` and `vehicle_id` with `one_way_fare`, `round_trip_fare`, `is_default`, and `sort_order`, with foreign key cascades and unique constraints.
  - **Graceful Fallback**: If a route has no rows in `taxi_route_fares`, both the admin and frontend cleanly fall back to the route's base `forward_one_way_fare` and `forward_round_trip_fare`, ensuring existing live data remains completely functional.
  - **Admin Vehicle Pricing Card**: When creating or editing a route, the admin sees an organized table of all active fleet vehicles (Dzire, Ertiga, Innova Crysta, etc.) with their capacity and fleet rates, and inputs for One-way and Round-trip fares. Blank rows are automatically excluded.
  - **Frontend Dynamic Dropdowns**: On `/taxi` and the Home page (`/`), each route provides a vehicle `<select>` dropdown. Selecting a vehicle instantly updates the displayed One-way/Round-trip fare, shows the vehicle's fleet rate specs (`₹/km • ₹/12 hrs • 300 km/day avg`), and generates the WhatsApp booking inquiry containing the exact vehicle name.
  - **FTP & Deployment Compatibility**: The migration runs via the existing `GET /migrate` browser endpoint on production without needing shell access.
- **Verification**:
  - Authored `tests/Feature/TaxiRouteVehicleFaresTest.php`: 4/4 tests passed (22 assertions).
  - Executed full test suite: **172/172 tests passed (1,906 assertions)** with zero failures.
- **Pending Tasks**:
  - Upload modified files to production server via FTP.
  - Visit `https://<domain>/migrate` in browser to run the new table migration.

---

### Taxi Routes Browser Seeder Endpoint (`/seed-taxi-routes`)
- **Date**: 2026-11-05
- **Feature**: Browser-Triggered Taxi Routes & Multi-Vehicle Fares Seeder Web Endpoint
- **Files Modified**:
  - `routes/web.php`
  - `tests/Feature/FrontendTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Added public browser endpoint `GET /seed-taxi-routes` (without `web` middleware) that calls `Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TaxiRouteSeeder', '--force' => true])`.
  - Enables servers managed via FTP to populate all 11 routes with their multi-vehicle fares (Dzire, Ertiga, Innova Crysta, Tempo Traveller) simply by loading `https://<domain>/seed-taxi-routes` in the browser once.
- **Verification**:
  - Verified with `tests/Feature/FrontendTest.php`: 9/9 tests passed (54 assertions).

---

### Admin Vehicle Pricing Table Dark Theme & Text Selection Fix
- **Date**: 2026-11-05
- **Feature**: Prevent Accidental White Text Selection & Dark Theme Selection Styling
- **Files Modified**:
  - `resources/views/admin/routes/form.blade.php`
  - `resources/views/components/admin/layout.blade.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Disabled Accidental Text Drag/Selection**: Added `user-select: none` (`select-none`) across table headers, vehicle names, badges, rates, and default radio columns so clicking or dragging around rows never accidentally triggers browser text selection across rows.
  - **Harmonized Dark Selection**: Configured global `.dark ::selection` styling with a subtle amber/gold hue (`rgba(220, 161, 52, 0.3)`) instead of the stark white/light-blue browser default.
  - **Clickable Labels**: Bound vehicle names directly to the default vehicle radio button via `<label for="default_veh_{{ $veh->id }}">` for seamless 1-click default selection without text selection.
- **Verification**:
  - Ran `tests/Feature/TaxiRouteVehicleFaresTest.php`: 4/4 passed.
  - Cleared Blade view cache (`php artisan view:clear`).

---

### Automated Fleet Route Pricing Engine & Dynamic Seeder
- **Date**: 2026-11-05
- **Feature**: Dynamic Multi-Vehicle Pricing Calculation for All Server Fleet Vehicles & FTP Seeder
- **Files Modified**:
  - `database/seeders/TaxiRouteSeeder.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Implemented an intelligent fleet calculation formula in `TaxiRouteSeeder`:
    - Preserves exact server baseline fares for Swift Dzire.
    - Automatically calculates One-Way and Round-Trip fares for ALL vehicles present in the database (Dzire, Amaze, Ertiga, Innova Crysta, Urbania, Tempo Traveller, Mini Bus, Buses) based on distance, rate per km (min 300 km/day avg rule), and 12-hr package thresholds.
    - Clean market rounding (`cleanFare()` to nearest ₹100 or ₹500).
    - Can be executed on production anytime via browser endpoint `/seed-taxi-routes`.
- **Verification**:
  - Verified local database population (77 route fares generated across 11 routes).
  - Feature test suite passed: 4/4 tests passed.

---

### Vehicle Dropdown Seating Capacity Deduplication
- **Date**: 2026-11-05
- **Feature**: Prevent Duplicate Seating Capacity Labels in Frontend Vehicle Dropdowns
- **Files Modified**:
  - `app/Models/Vehicle.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/index.blade.php`
  - `tests/Feature/TaxiRouteVehicleFaresTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Added modern Laravel 13 `displayName(): Attribute` accessor on `Vehicle` model that checks if the vehicle title already contains the capacity label or a `\b\d+\s*seater\b` pattern before appending `({$capLabel})`.
  - Preserves exact database record titles without requiring any renaming in database or admin.
  - Replaced manual bracket appending in `resources/views/taxi.blade.php` and `resources/views/index.blade.php` with `$veh->display_name`.
  - Prevents unsightly duplicates such as `Force Urbania (12 Seater) (12 Seater)` so it renders cleanly as `Force Urbania (12 Seater)`, while cars like `Swift Dzire` render as `Swift Dzire (4 Seater)`.
- **Verification**:
  - Added `test_vehicle_display_name_deduplicates_seater_in_brackets` to `tests/Feature/TaxiRouteVehicleFaresTest.php`.
  - Full test suite passed: 173/173 tests passed (1,914 assertions).
  - View cache cleared (`php artisan view:clear`).













---

### Image Hover GPU Layer Stabilization & Elimination of Red Compositing Flicker
- **Date**: 2026-11-05
- **Feature**: Resolve WebP Image Hover Compositing Glitch and Eliminate Red Fallback Flashing Across Fleet & Gallery Cards
- **Files Modified**:
  - `resources/views/taxi.blade.php`
  - `resources/views/gallery.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `public/css/style.css`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **GPU Layer Pre-Allocation**: Added `-webkit-backface-visibility: hidden`, `backface-visibility: hidden`, `will-change: transform`, and `transform: translateZ(0)` to `.vehicle-item .vehicle-img-wrapper img` and `.gallery-item-card img`. Forces Chromium/Blink to maintain WebP images on dedicated GPU compositing layers continuously, preventing dynamic layer promotion drops and texture buffer rasterization flashes during scale animations.
  - **Container Isolation & Edge Masking**: Added `isolation: isolate`, `-webkit-mask-image: -webkit-radial-gradient(white, black)`, and `transform: translateZ(0)` to `.vehicle-img-wrapper` and `.gallery-item-card` to hardware-accelerate rounded border-radius overflow clipping.
  - **Eliminated `transition: all` Layout Thrashing**: Replaced blanket `transition: all` on `.vehicle-item` and `.gallery-item-card` with discrete, GPU-friendly transitions (`transform`, `box-shadow`, `border-color`), preventing full subtree reflow and paint thrashing when cursor moves quickly between cards.
  - **Fallback Brand Color Alignment**: Updated `--orange` and `--orange-rgb` in `public/css/style.css` from legacy `#9E2A2B` (Madder Red) to brand gold `#DCA134` / `220, 161, 52` to prevent fallback color flashes during cascade initialization.
- **Verification**:
  - Verified view rendering for `taxi.blade.php` and `gallery.blade.php` with 0 syntax or runtime errors.
  - Verified in browser via Chrome DevTools DOM evaluation.

---

### Browser Endpoint for Blade View Cache Clearing (`/view-clear`)
- **Date**: 2026-11-05
- **Feature**: Add Browser Endpoint to Clear Blade View Cache for Remote/FTP Deployments
- **Files Modified**:
  - `routes/web.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - Registered `GET /view-clear` endpoint (named `view.clear`) using `Artisan::call('view:clear')` without web middleware.
  - Allows server administrators on shared hosting / cPanel (without SSH terminal access) to clear compiled Blade views simply by browsing to `https://yourdomain.com/view-clear`.
- **Verification**:
  - Verified route registration via `php artisan route:list --path=view-clear`.


---

### 12-Hour Duty Route Pricing Harmonization & UI Alignment
- **Date**: 2026-11-12
- **Feature**: Taxi Page Rates Table & Home Page Route Cards 12-Hour Duty UI Alignment
- **Files Modified**:
  - `resources/views/taxi.blade.php`
  - `resources/views/index.blade.php`
  - `tests/Feature/TaxiRouteVehicleFaresTest.php`
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Taxi Rates Table (`taxi.blade.php`)**:
    - Consolidated separate "One-Way Fare" and "Round Trip Fare" columns into a single unified column: `Fare (12 hours)`.
    - Bound dynamic vehicle fare update to the `Fare (12 hours)` column when user changes vehicle selection in the dropdown.
    - Removed route short description from the table row destination cell for a cleaner, compact tabular view.
    - Streamlined the rates note beneath the dropdown to remove duplicate 12-hr package price, keeping `₹.../km • Avg 300 km/day`, and added dedicated policy line: `12 Hours Duty , Driver Food Extra`.
  - **Home Page Route Cards (`index.blade.php`)**:
    - Updated card fare label from `Round Trip Fare` to `Trip Fare (12 hours)` with dynamic pricing reflecting the chosen vehicle.
    - Streamlined the vehicle specs under the dropdown to display `₹.../km • Avg 300 km/day` with policy line: `12 Hours Duty , Driver Food Extra`.
  - **Backend & Database Integrity**:
    - Preserved existing database schema, migrations, Eloquent models, and Admin route pricing form intact without breaking changes or regressions.
  - **Automated Verification**:
    - Updated test assertions in `tests/Feature/TaxiRouteVehicleFaresTest.php` to validate `Fare (12 hours)`, `Trip Fare (12 hours)`, `12 Hours Duty`, `Driver Food Extra`, and `Avg 300 km/day`.
    - Executed in-memory view render validation verifying 100% of required headers, labels, and policy lines render properly.
    - Cleared compiled Blade views via `php artisan view:clear`.

---

### Taxi Route Backend Simplification & Fleet Price Alignment
- **Date**: 2026-11-12
- **Feature**: Hide Full Description, Route Image, Fares Card, and 1-Way/2-Way Fare Inputs in Admin View; Connect Frontend Route Pricing to Vehicle Fleet Rates
- **Files Modified**:
  - `resources/views/admin/routes/form.blade.php`
  - `resources/views/admin/routes/cell.blade.php`
  - `app/Http/Requests/Admin/TaxiRouteRequest.php`
  - `resources/views/taxi.blade.php`
  - `resources/views/index.blade.php`
  - `tests/Feature/AdminTaxiRoutesTest.php`
  - `tests/Feature/AdminToFrontendEndToEndCrudTest.php`
  - `tests/Feature/TaxiRouteVehicleFaresTest.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Admin Route Form (`admin/routes/form.blade.php`)**:
    - **Hidden from View**: Removed full description rich text editor, route image card, and base fares card (forward/reverse one-way and round-trip fares) from the form UI.
    - **Hidden One-Way & Two-Way Fare Inputs**: In the "Vehicle fleet rates" table, removed the `One-way Fare (₹)` and `Round-trip Fare (₹)` input columns, displaying only `Vehicle`, `Capacity & Type`, `Fleet Rates` (`₹.../km` and `₹... / 12 hrs`), and the `Default` vehicle radio button.
    - **Data Preservation & Backward Compatibility**: Maintained existing values via hidden form inputs (`description`, `image_alt`, `forward_one_way_fare`, `forward_round_trip_fare`, `reverse_enabled`, `reverse_one_way_fare`, `reverse_round_trip_fare`, and existing vehicle fares) so saving or editing a route retains all underlying data and will never wipe existing fields.
  - **Admin DataTable Fares Cell (`admin/routes/cell.blade.php`)**:
    - Updated vehicle fare previews in the DataTable to prioritize vehicle fleet 12-hr rates with graceful fallback to round-trip / one-way fares.
  - **Frontend Fleet Price Connection (`taxi.blade.php` & `index.blade.php`)**:
    - Verified and confirmed user finding: routes on `/taxi` and `/` were taking the vehicle's 2-way round-trip fare instead of the vehicle's 12-hour fleet rate.
    - Connected both the initial load display and dynamic vehicle dropdown change to take the selected vehicle's fleet rate (`$veh->price_for_12_hrs`, e.g., ₹2,500 for Dzire, ₹3,200 for Ertiga, ₹4,500 for Crysta), with graceful fallback to round-trip / one-way / on-request if unpriced.
    - Preserved dynamic WhatsApp booking URL generation, vehicle specs (`₹.../km • Avg 300 km/day`), and policy line (`12 Hours Duty , Driver Food Extra`).
  - **Automated Verification**:
    - Updated and verified feature test suites:
      - `TaxiRouteVehicleFaresTest`: 6/6 tests passed (36 assertions).
      - `AdminTaxiRoutesTest`: 24/24 tests passed (201 assertions).
      - `AdminToFrontendEndToEndCrudTest`: 9/9 tests passed (179 assertions).
      - `FrontendTest`: 9/9 tests passed (54 assertions).
    - Cleared compiled views and application caches (`php artisan view:clear`, `php artisan optimize:clear`).


---

### Dynamic Social Link Preview Branding & Backend Settings Integration
- **Date**: 2026-11-13
- **Feature**: Link Share Preview (`og:image` / `twitter:image`) Direct Backend Integration & New Emblem Card Asset
- **Files Modified**:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/index.blade.php`
  - `resources/views/package-show.blade.php`
  - `public/images/logo/logo-gold-dark-card.png`
  - `storage/app/public/branding/logo-gold-dark-card.png`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Dynamic Backend Settings Binding (`layouts/app.blade.php`)**:
    - Connected Open Graph (`og:image`, `og:image:secure_url`) and Twitter card (`twitter:image`) default fallbacks to `$settings->dark_logo_url`.
    - Added raster format validation: if `$settings->dark_logo_url` is a raster image (PNG, JPG, WebP uploaded via Admin Branding), it is served directly. If it is an `.svg` (or empty), it safely falls back to `logo-gold-dark-card.png?v=2` so WhatsApp, Facebook, LinkedIn, and Microsoft Teams previews never fail due to unsupported SVG formats.
  - **Inheritance Cleanup (`index.blade.php` & `package-show.blade.php`)**:
    - Removed hardcoded `@section('og_image', asset('images/logo/logo-gold-dark-card.png'))` from `index.blade.php` so the home page dynamically uses the backend setting.
    - Updated `package-show.blade.php` to define `@section('og_image')` only when the package has a custom image path, otherwise inheriting the dynamic brand setting.
  - **New Logo Emblem Card Asset**:
    - Centered the uploaded transparent high-res new emblem (car, camel, airplane with gold typography) onto a 1200x630px `#111111` card canvas for `logo-gold-dark-card.png` with cache-buster `?v=2`.
  - **Cache Clear**:
    - Cleared compiled Blade views via `php artisan view:clear`.
- **Pending Tasks**:
  - User verification on WhatsApp / Microsoft Teams / Facebook debugger.


---

### Dedicated Social Share Image (WhatsApp / Link Preview) in Admin Branding
- **Date**: 2026-11-13
- **Feature**: Dedicated `og_image_path` in Admin Settings Branding with Schema Migration, Validation, Model Accessor & View Integration
- **Files Modified / Created**:
  - `database/migrations/2026_11_13_123000_add_og_image_path_to_settings_table.php` (created)
  - `app/Models/Setting.php`
  - `app/Http/Requests/Admin/UpdateSettingsRequest.php`
  - `resources/views/admin/settings/edit.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `tests/Feature/AdminSettingsTest.php`
  - `PROJECT_HISTORY.md`
- **Logic & UI Changes**:
  - **Schema & Model**:
    - Created migration adding nullable `og_image_path` column to `settings` table.
    - Added `og_image_path` to `Setting` fillable attributes and mapped `'og_image' => 'og_image_path'` in `BRANDING_FILES`.
    - Added accessor `$settings->og_image_url` with fallback to `images/logo/logo-gold-dark-card.png?v=2`.
  - **Admin Settings Form (`admin/settings/edit.blade.php`)**:
    - Expanded branding grid from 3 to 4 responsive columns (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`).
    - Added dedicated `<x-admin.branding-upload>` component for "Social share image (WhatsApp / Link preview)" supporting PNG, JPEG, and WebP uploads and removal checkbox.
  - **Validation (`UpdateSettingsRequest.php`)**:
    - Added validation rules for `og_image` (`nullable|image|mimes:jpg,jpeg,png,webp|max:2048`) and `remove_og_image` (`sometimes|boolean`).
  - **Frontend Layout (`layouts/app.blade.php`)**:
    - Directly bound `og:image`, `og:image:secure_url`, and `twitter:image` to `$settings->og_image_url`.
  - **Automated Verification**:
    - Updated `tests/Feature/AdminSettingsTest.php` to test upload, replacement, removal, and rejection of invalid/oversized `og_image` files.
    - Verified test suite: **20/20 passed (205 assertions)** in `AdminSettingsTest`, and **9/9 passed (54 assertions)** in `FrontendTest`.
    - Cleared compiled Blade views via `php artisan view:clear`.

---

### Working Tree Consolidation & Clean Commit to Main
- **Date**: 2026-11-13
- **Feature**: Commit and Merge All Uncommitted Working Tree Changes onto Main Branch
- **Files Modified / Created**:
  - `.htaccess` (created)
  - `app/Http/Controllers/Admin/TaxiRouteController.php`
  - `app/Http/Controllers/HomeController.php`
  - `app/Http/Controllers/TaxiController.php`
  - `app/Http/Requests/Admin/TaxiRouteRequest.php`
  - `app/Http/Requests/Admin/UpdateSettingsRequest.php`
  - `app/Http/Requests/Admin/VehicleRequest.php`
  - `app/Models/Setting.php`
  - `app/Models/TaxiRoute.php`
  - `app/Models/TaxiRouteFare.php` (created)
  - `app/Models/Vehicle.php`
  - `database/migrations/2026_10_31_120000_add_price_for_12_hrs_to_vehicles_table.php` (created)
  - `database/migrations/2026_11_05_140000_create_taxi_route_fares_table.php` (created)
  - `database/migrations/2026_11_13_123000_add_og_image_path_to_settings_table.php` (created)
  - `database/seeders/TaxiRouteSeeder.php`
  - `database/seeders/VehicleSeeder.php`
  - `public/css/style.css`
  - `public/images/logo/logo-gold-dark-card.png`
  - `resources/views/admin/routes/cell.blade.php`
  - `resources/views/admin/routes/form.blade.php`
  - `resources/views/admin/settings/edit.blade.php`
  - `resources/views/admin/vehicles/cell.blade.php`
  - `resources/views/admin/vehicles/form.blade.php`
  - `resources/views/components/admin/layout.blade.php`
  - `resources/views/gallery.blade.php`
  - `resources/views/index.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/package-show.blade.php`
  - `resources/views/taxi.blade.php`
  - `routes/web.php`
  - `tests/Feature/AdminSettingsTest.php`
  - `tests/Feature/AdminTaxiRoutesTest.php`
  - `tests/Feature/AdminToFrontendEndToEndCrudTest.php`
  - `tests/Feature/AdminVehiclesTest.php`
  - `tests/Feature/FrontendTest.php`
  - `tests/Feature/TaxiRouteVehicleFaresTest.php` (created)
  - `PROJECT_HISTORY.md`
- **Logic Changes**:
  - **Branch Verification**:
    - Checked all existing feature branches (`feature/admin-gallery`, `feature/admin-invoices`, `feature/admin-package-builder`, `feature/website-redesign`, `master`) against `main`. Confirmed 100% of feature branches are already fully merged into `main`.
  - **Working Tree Commit onto `main`**:
    - Consolidated and staged all pending modifications, untracked migrations, models, server configurations, tests, and view assets onto `main`.
    - Included:
      1. Taxi route rates simplification and vehicle fleet 12-hour pricing alignment (`TaxiRouteFare` model, migrations, seeders, views, and controller).
      2. Social share link preview branding with dedicated `og_image_path` in admin settings and dynamic `og:image` / `twitter:image` tags.
      3. UI styling enhancements (Kutch desert gold theme accents, gallery card hover transitions, admin text selection highlights).
      4. Root `.htaccess` for Apache rewrite and security isolation, plus admin maintenance web routes (`/storage-link`, `/migrate`, `/seed-taxi-routes`, `/view-clear`).
- **Pending Tasks**:
  - None. Working tree is clean on `main`.
