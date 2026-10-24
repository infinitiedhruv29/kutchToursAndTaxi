# Task: Backend Primary Brand Color Theming & Midnight Tone Harmonization

- [x] Investigate Settings model, database schema, and admin settings interface <!-- id: 0 -->
- [x] Create migration `add_primary_color_to_settings_table` <!-- id: 1 -->
- [x] Update `Setting` Eloquent model with `$fillable` and default value `#9E2A2B` <!-- id: 2 -->
- [x] Update `UpdateSettingsRequest` FormRequest with hex validation rule (`^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$`) <!-- id: 3 -->
- [x] Add interactive HTML5/hex color picker in `resources/views/admin/settings/edit.blade.php` <!-- id: 4 -->
- [x] Derive mathematical midnight dark shades (`$darkMidnight`, `$darkLight`) from primary color in `resources/views/layouts/app.blade.php` <!-- id: 5 -->
- [x] Standardize footer background, borders, and ambient glow across public site <!-- id: 6 -->
- [x] Standardize `.cta-banner` across `about.blade.php`, `packages.blade.php`, and `index.blade.php` with dynamic primary ambient aura <!-- id: 7 -->
- [x] Remove hardcoded red shades from hero headers and taxi capacity filter buttons <!-- id: 8 -->
- [x] Author comprehensive feature tests in `tests/Feature/AdminSettingsTest.php` <!-- id: 9 -->
- [x] Verify tests (20/20 passed in settings suite; 149/149 total passed) <!-- id: 10 -->
