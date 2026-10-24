# Implementation Plan: Admin Gallery

## Background
Port the Gallery management module from `kutch-holidays` reference into `kutchToursAndTaxi`. The module allows administrators to organize photography into categories (e.g. White Rann, Heritage & Palaces, Wildlife, Culture & Handicrafts) and upload/manage individual photos with metadata, tags, and category associations.

## Proposed Changes

### Models & Schema
- `GalleryCategory`: `name`, `slug`, `sort_order`, with relation `images(): HasMany`.
- `GalleryImage`: `gallery_category_id`, `title`, `image_path`, `caption`, `is_published`, `sort_order`, with relation `category(): BelongsTo`.

### Validation & Requests
- `GalleryCategoryRequest`: Validates `name` (required, unique), `sort_order` (integer).
- `GalleryImageRequest`: Validates `gallery_category_id` (exists in categories), `title`, `image` (mimes: jpg, jpeg, png, webp, max: 5120 KB), `caption`, `is_published`.
- `GalleryImageTableRequest`: Handles DataTables sorting, pagination, and search filtering.

### Controllers
- `GalleryCategoryController`: CRUD operations, reordering route (`gallery-categories.reorder`), prevent deletion if category contains photos.
- `GalleryImageController`: Server-side DataTables JSON response (`data()`), image upload to `storage/app/public/gallery/`, storage cleanup on update/delete, flash feedback via `AdminFeedback::success()`.

### Views
- `resources/views/admin/gallery/categories/index.blade.php`: Inline category management & reorder list.
- `resources/views/admin/gallery/images/index.blade.php`: DataTables photo gallery with thumbnails and filter.
- `resources/views/admin/gallery/images/form.blade.php`: Image upload dropzone, category selector, published toggle.
- `resources/views/admin/gallery/images/cell.blade.php`: DataTables custom cells for thumbnail, title, category, and action buttons.

### Routes & Navigation
- Routes registered under `admin` prefix in `routes/web.php`.
- Navigation group added to `resources/views/admin/partials/navigation.blade.php`.

## Verification Plan
- Unit & Feature tests via `tests/Feature/AdminGalleryTest.php`:
  - Authentication protection.
  - Category create, inline update, delete with orphan protection, AJAX reorder.
  - Image upload, update with file replacement, delete with file cleanup.
  - DataTables JSON structure validation.
