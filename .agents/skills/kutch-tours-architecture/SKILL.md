---
name: kutch-tours-architecture
description: Comprehensive architecture, tech stack, database models, business rules, conventions, and guidelines for the Kutch Tours & Taxi Laravel project. Use whenever developing, refactoring, or adding features to this application.
---

# Kutch Tours & Taxi Architecture & System Knowledge Base

This skill provides the comprehensive architectural blueprint of the **Kutch Tours & Taxi** application. It details the business domain, technology stack, directory layout, database models, admin management system, customer workflows, and working rules for developers and AI agents.

---

## 1. Business Domain & Capabilities

**Kutch Tours & Taxi** is a travel, tour operator, and taxi booking portal serving visitors to Gujarat, specifically the Kutch, Rann of Kutch, Bhuj, Mandvi, and Dholavira regions.

### Key Capabilities:
1. **Taxi Routes & Dynamic Fares**:
   - Point-to-point routes with calculated distances (km) and travel durations.
   - Separate forward and reverse pricing for one-way and round-trip journeys.
   - SEO metadata, unique slug generation, and sanitized route descriptions.
2. **Fleet Management**:
   - Categorized vehicles by Vehicle Type (Sedan, SUV, Hatchback) and Vehicle Capacity (4-seater, 6-seater, etc.).
   - Per-kilometer pricing rates, minimum daily kilometers, passenger capacity, and vehicle photos.
3. **Tour Packages & Custom Itineraries**:
   - Ready-made travel packages with multi-day itineraries, inclusions, exclusions, and gallery photos.
   - Custom package builder generating downloadable proposals and PDFs.
4. **Content & Destination Management**:
   - Popular tourist spots and historical destinations in Kutch.
   - Frequently Asked Questions (FAQs) with sanitized rich text answers.
5. **Business Settings & Invoicing**:
   - Business branding (logo, dark logo, favicon, dynamic primary color `#9E2A2B`), phone, WhatsApp, email, physical address, and GST registration.
   - Dynamic dark midnight derivation (`$darkMidnight`, `$darkLight`) and theme token overrides.
   - Itemized invoicing system for taxi rentals and tour bookings with PDF exports.
6. **Lead & Inquiry Pipeline**:
   - Booking inquiries for taxis and packages with lifecycle states (`new`, `in_progress`, `confirmed`, `cancelled`).
7. **SEO & Structured Data (JSON-LD)**:
   - Dynamic `/sitemap.xml` listing static pages and all active tour package slugs.
   - Hardened `robots.txt` disallowing `/admin/` and referencing the sitemap.
   - Comprehensive Schema.org JSON-LD graph (`TravelAgency`, `FAQPage`, `TouristTrip`, `TaxiService`, `BreadcrumbList`).
   - Dynamic OpenGraph tags and continuous non-stop marquee ticker ribbon.

---

## 2. Technology Stack

- **Framework**: Laravel 13 running on PHP 8.4+ / 8.5
- **Database**: MySQL 8+ (`tours_db` with `utf8mb4_unicode_ci`), configured via `.env`
- **Frontend / Styling**: Tailwind CSS v4, Vite 8, Blade component architecture
- **Admin UI Libraries**:
  - [TipTap Starter Kit](https://tiptap.dev/): WYSIWYG rich text editor with HTML sanitizer
  - [DataTables.net](https://datatables.net/): Server-side AJAX data tables (`/admin/*/data`)
  - [SweetAlert2](https://sweetalert2.github.io/): Accessible deletion confirmations and modal alerts
  - [jQuery 3.7](https://jquery.com/): Companion for table plugins
- **Testing**:
  - PHPUnit test suite extending `Tests\TestCase` with `RefreshDatabase`
  - JavaScript unit test suite running via Node's native runner (`npm run test:js`)

---

## 3. Directory Layout & Conventions

```
app/
├── Console/Commands/       # CLI tools: admin:create, admin:reset-password
├── Http/
│   ├── AdminFeedback.php   # Centralized redirect & AJAX response formatter
│   ├── Controllers/Admin/  # Back-office CRUD controllers
│   └── Requests/Admin/     # Dedicated Form Requests (extending DataTableRequest or FormRequest)
├── Models/                 # Eloquent models using #[Fillable] attributes & casts() methods
└── Providers/              # Application service providers

database/
├── migrations/             # 23 schema migrations with strict foreign key constraints
└── seeders/                # Database seeders

resources/
├── css/app.css             # Tailwind v4 entry point with theme tokens
├── js/                     # Modular client-side JS (datatable.js, admin-forms.js, etc.)
└── views/
    ├── admin/              # Admin dashboard, routes, vehicles, settings, etc.
    └── components/admin/   # Reusable Blade components (<x-admin.card>, <x-admin.datatable>, etc.)

.ai/rules/                  # Codified project conventions (controllers, models, migrations, tests)
```

---

## 4. Master Architectural Patterns

### 1. Form Requests Required
Every admin write or table query operation must use a dedicated Form Request in `App\Http\Requests\Admin`. Do **not** use `$request->validate()` inline within controllers. Table query parameters must extend `App\Http\Requests\Admin\DataTableRequest`.

### 2. Model Mass Assignment (`#[Fillable]`)
All Eloquent models declare fillable attributes using the class-level PHP 8 attribute:
```php
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug', 'is_published', 'sort_order'])]
class Destination extends Model
{
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
```

### 3. Controller Responses via `AdminFeedback`
Admin controllers do not return raw redirects or ad-hoc JSON. Use `AdminFeedback::success()`:
```php
return AdminFeedback::success($request, 'Record updated successfully.', route('admin.routes.index'));
```

### 4. Slugs on Public Models
Any model with a public slug must implement static `suggestedSlug()` and `uniqueSlug()` helpers to guarantee collision-free URLs.

### 5. Rich Text Sanitization
Always sanitize rich-text or HTML fields (such as FAQ answers and route descriptions) using `Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface` via an Eloquent `Attribute::make(set: ...)`.

---

## 5. Working Rules for AI Agents

1. **Check Project History**:
   - Always read `PROJECT_HISTORY.md` before starting any task.
   - After completing changes, append the date, feature, modified files, and logic changes.
2. **Follow `.ai/rules`**:
   - Check `.ai/rules/index.md` and adhere to path-scoped conventions.
3. **Database Safety**:
   - **Never run destructive commands**: `migrate:fresh`, `migrate:reset`, `migrate:refresh`, or `db:wipe` without explicit user approval.
   - Always define explicit `down(): void` methods on migrations.
4. **Testing Integrity**:
   - Maintain test coverage. Run `php artisan test` and `npm run test:js` to verify changes.
