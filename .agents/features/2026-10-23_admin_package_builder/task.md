# Task: Admin Package Builder (Custom Tour Proposals & PDF Export)

- [x] Investigate package proposal requirements and DomPDF templating from reference project <!-- id: 0 -->
- [x] Create feature branch `feature/admin-package-builder` <!-- id: 1 -->
- [x] Create `CustomPackage` model with JSON casts (`itineraries`, `inclusions`, `exclusions`) and decimal pricing tiers <!-- id: 2 -->
- [x] Create `Package` model for standard tour package compatibility <!-- id: 3 -->
- [x] Build `CustomPackageRequest` and `CustomPackageTableRequest` FormRequests <!-- id: 4 -->
- [x] Build `PackageBuilderController` with DataTables feed, itinerary repeater, and DomPDF proposal generator <!-- id: 5 -->
- [x] Design Blade views (`index.blade.php`, `cell.blade.php`, `form.blade.php`, `pdf.blade.php`) <!-- id: 6 -->
- [x] Register routes in `routes/web.php` and sidebar navigation in `navigation.blade.php` <!-- id: 7 -->
- [x] Resolve DOM element shadowing in `resources/js/admin-forms.js` (`form.getAttribute('action')`, `submit_action`) <!-- id: 8 -->
- [x] Write comprehensive feature test suite `tests/Feature/AdminPackageBuilderTest.php` <!-- id: 9 -->
- [x] Verify tests (135/135 passed) and compile Vite assets <!-- id: 10 -->
- [x] User manual verification in browser <!-- id: 11 -->
- [x] Merge `feature/admin-package-builder` into `master` and push to remote `origin/master` <!-- id: 12 -->
