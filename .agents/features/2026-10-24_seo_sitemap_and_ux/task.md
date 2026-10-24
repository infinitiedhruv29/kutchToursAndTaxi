# Task: SEO Sitemap, Schema Optimization, Infinite Ticker & Clickable Breadcrumbs

- [x] Perform full SEO audit and competitive gap analysis against `kutchholidays.in` <!-- id: 0 -->
- [x] Create `SitemapController` and `resources/views/sitemap.blade.php` delivering standards-compliant `/sitemap.xml` <!-- id: 1 -->
- [x] Register `/sitemap.xml` route in `routes/web.php` <!-- id: 2 -->
- [x] Harden `public/robots.txt` with disallow rules for `/admin/` and link to `Sitemap: /sitemap.xml` <!-- id: 3 -->
- [x] Implement enhanced Google JSON-LD schema on homepage (`TravelAgency` with Bhuj GPS coordinates, areaServed + `FAQPage` rich snippets) <!-- id: 4 -->
- [x] Implement `TaxiService` and `BreadcrumbList` schema in `resources/views/taxi.blade.php` <!-- id: 5 -->
- [x] Implement `ItemList` and `BreadcrumbList` schema in `resources/views/packages.blade.php` <!-- id: 6 -->
- [x] Implement `TouristTrip`, `Offer` (INR pricing), `BreadcrumbList`, and dynamic OpenGraph tags in `resources/views/package-show.blade.php` <!-- id: 7 -->
- [x] Verify schemas with Google Rich Results Test & Schema.org Validator (0 errors, 0 warnings) <!-- id: 8 -->
- [x] Implement seamless infinite continuous rolling ticker in `resources/views/index.blade.php` (no blank rows, no stopping) <!-- id: 9 -->
- [x] Fix unclickable breadcrumbs by adding `pointer-events: none` to `.page-hero::before` and elevating `.breadcrumb` across all pages <!-- id: 10 -->
- [x] Add automated feature tests in `tests/Feature/FrontendTest.php` <!-- id: 11 -->
- [x] Full test suite passing (151/151 tests passed, 1,553 assertions) <!-- id: 12 -->
