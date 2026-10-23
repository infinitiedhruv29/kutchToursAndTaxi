# Testing Rules

**Applies to**: `tests/**`

- **Test Structure**: Feature tests must extend `Tests\TestCase` and use the `Illuminate\Foundation\Testing\RefreshDatabase` trait.
- **Vite Bundling**: Call `$this->withoutVite()` in `setUp()` when rendering views to bypass Vite asset bundling during test execution.
- **Storage**: Fake public disk uploads with `Storage::fake('public')`.
- **JavaScript Testing**: Frontend unit tests run independently using Node's native test runner via `npm run test:js`.
