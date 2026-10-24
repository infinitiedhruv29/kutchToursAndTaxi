# Implementation Plan: Invoice Builder (GST Tax Invoices & Taxi Billing)

## Background
Port the Invoice Builder feature from `kutch-holidays` reference into `kutchToursAndTaxi`. Enables admins to create itemized GST tax invoices for clients with automated taxi distance pricing (`distance_km * price_per_km`), tour packages, additional trip expenses (tolls, parking, entry permits), selectable GST tax rates (0%, 5%, 12%, 18%), and export branded PDF tax invoices.

## Proposed Changes

### Models
- `Invoice`: Fields for `invoice_number`, `invoice_date`, customer info (`name`, `contact`, `email`, `address`, `gst_number`), `business_details` (JSON snapshot of company settings at issue time), `subtotal`, `tax_rate`, `tax_amount`, `total_amount`, and `notes`. Relationship `items(): HasMany`.
- `InvoiceItem`: Fields for `invoice_id`, `item_type` (`package`, `taxi`, `other`), `description`, `quantity`, `unit_price`, `amount`, `vehicle_id`, vehicle details snapshot (`vehicle_name`, `vehicle_type`, `vehicle_capacity`, `price_per_km`, `total_distance_km`), `sort_order`.

### Form Requests
- `InvoiceRequest`: Validates client contact info, parses package selection, taxi rental calculations, and dynamic line items repeater. Computes taxable subtotal and GST breakdown.
- `InvoiceTableRequest`: DataTables query helper for invoice search, sorting, and pagination.

### Controller & DomPDF Export
- `InvoiceController`:
  - `index()` & `data()`: DataTables listing with search on invoice number, client, and contact.
  - `create()` & `edit()`: Form populated with vehicles, custom proposals from Package Builder, and standard tour packages.
  - `store()` & `update()`: Database transactions synchronizing invoice records and items.
  - `downloadPdf()`: Branded DomPDF tax invoice template with base64 logo and itemized tax breakdown.

### Views
- `resources/views/admin/invoices/index.blade.php`: Invoices listing table with quick PDF download actions.
- `resources/views/admin/invoices/form.blade.php`: Real-time calculator for taxi distances, package pre-filling, dynamic additional items repeater, and GST rate selector.
- `resources/views/admin/invoices/pdf.blade.php`: GST-compliant tax invoice template with issuer details, customer GSTIN, and payment terms.

## Verification Plan
- `tests/Feature/AdminInvoiceTest.php`:
  - Authentication checks on all invoice endpoints.
  - Invoice creation with package, taxi distance, and additional item rows.
  - Subtotal, GST calculation, and grand total accuracy in DB.
  - Edit view and invoice update with cascade line items sync.
  - DataTables JSON structure validation.
  - PDF generation and MIME-type validation.
  - AJAX export response with redirect URL.
  - Package dropdown display of Custom Proposals.
