# Implementation Plan: Package Builder (Custom Tour Proposals)

## Background
Port the Custom Package Builder feature from `kutch-holidays` reference into `kutchToursAndTaxi`. Enables admins to craft personalized, client-specific travel itineraries, multi-tier passenger pricing (2-person sedan, 4-person Ertiga, 6-person Innova), inclusions/exclusions, and export branded PDF quotes directly to clients.

## Proposed Changes

### Models
- `CustomPackage`: Fields for `client_name`, `client_phone`, `title`, `duration`, `highlight`, `price_2p`, `price_4p`, `price_6p`, `inclusions` (JSON), `exclusions` (JSON), `itineraries` (JSON array of day plans).
- `Package`: Standard tour package catalog model with slug generator and pricing tiers.

### Form Requests
- `CustomPackageRequest`: Validates client info, contact regex, title, duration, multi-tier pricing, inclusions/exclusions text parsed to arrays, and dynamic day itineraries (`day_title`, `day_spots`, `day_description`, `day_note`).
- `CustomPackageTableRequest`: DataTable pagination, sorting, and client/proposal searching.

### Controller & PDF Export
- `PackageBuilderController`:
  - `index()` & `data()`: Server-side DataTables listing.
  - `create()` & `edit()`: Form rendering with interactive day itinerary builder.
  - `store()` & `update()`: Save proposals and handle dual action (`save` or `export` which returns redirect URL for AJAX fetch).
  - `downloadPdf()`: Branded DomPDF export using base64 company logo, itinerary details, spots visited, inclusions/exclusions, and terms.

### Views
- `resources/views/admin/custom-packages/index.blade.php`: Listing table with PDF export buttons.
- `resources/views/admin/custom-packages/form.blade.php`: Client details, multi-tier pricing, dynamic JavaScript itinerary repeater.
- `resources/views/admin/custom-packages/pdf.blade.php`: Clean, elegant DomPDF template for client quote downloads.

## Verification Plan
- `tests/Feature/AdminPackageBuilderTest.php`:
  - Authentication checks.
  - CRUD operations with nested JSON itineraries.
  - DomPDF quote generation and MIME-type validation.
  - AJAX export response structure.
