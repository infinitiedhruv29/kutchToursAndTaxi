# Task: Admin Invoice Builder (GST Invoices & Taxi Billing)

- [x] Investigate invoice and line items database schema (`invoices`, `invoice_items`) <!-- id: 0 -->
- [x] Create feature branch `feature/admin-invoices` <!-- id: 1 -->
- [x] Implement `Invoice` and `InvoiceItem` Eloquent models with `generateInvoiceNumber()` sequential numbering helper <!-- id: 2 -->
- [x] Create `InvoiceRequest` with subtotal/tax computation and line items extraction (packages, taxi rental distance calculator, custom extras) <!-- id: 3 -->
- [x] Create `InvoiceTableRequest` for DataTables listing <!-- id: 4 -->
- [x] Build `InvoiceController` handling parent-child item synchronization, transactions, DataTables feed, and PDF generation <!-- id: 5 -->
- [x] Design Blade views (`index.blade.php`, `cell.blade.php`, `form.blade.php`, `pdf.blade.php`) <!-- id: 6 -->
- [x] Implement live client-side tax, taxi distance fare, and grand total calculator in `form.blade.php` <!-- id: 7 -->
- [x] Support both Custom Proposals (`CustomPackage`) and Standard Packages (`Package`) in the invoice form dropdown <!-- id: 8 -->
- [x] Add sidebar navigation links and register routes in `routes/web.php` <!-- id: 9 -->
- [x] Write feature test suite `tests/Feature/AdminInvoiceTest.php` <!-- id: 10 -->
- [x] Verify tests (141/141 passed) and compile Vite assets <!-- id: 11 -->
- [x] Manual review & verification by user in browser <!-- id: 12 -->
