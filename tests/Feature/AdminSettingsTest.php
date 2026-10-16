<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    public function test_guests_cannot_read_or_update_settings(): void
    {
        $this->get('/admin/settings')->assertRedirect('/admin/login');
        $this->put('/admin/settings', ['business_name' => 'Changed'])->assertRedirect('/admin/login');
        $this->assertDatabaseCount('settings', 0);
    }

    public function test_viewing_empty_settings_does_not_create_a_record(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/settings')
            ->assertOk()->assertSee('Business settings')->assertSee('Save business settings')
            ->assertSee('Tours &amp; Taxi', false)
            ->assertViewHas('settings', fn (Setting $settings): bool => ! $settings->exists);
        $this->assertDatabaseCount('settings', 0);
    }

    public function test_first_save_creates_singleton_with_all_content_fields_and_normalized_lists(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'business_name' => 'Desert Taxi',
            'contact_number' => '+91 98765 43210',
            'contact_number_2' => '02832 123456',
            'whatsapp_number' => '+91 (98765) 43210',
            'contact_email' => 'hello@example.com',
            'address' => "Office 1\nBhuj",
            'google_map_link' => 'https://maps.app.goo.gl/example',
            'instagram_link' => 'https://www.instagram.com/example',
            'facebook_link' => 'https://www.facebook.com/example',
            'youtube_link' => 'https://www.youtube.com/@example',
            'gst_number' => 'BUSINESS-GST',
            'general_inclusions' => " Driver allowance \r\n\r\n Fuel \n Parking ",
            'general_exclusions' => " Meals\rPersonal expenses ",
        ])->assertRedirect('/admin/settings')->assertSessionHasNoErrors()->assertSessionHas('status');

        $this->assertDatabaseCount('settings', 1);
        $settings = Setting::findOrFail(1);
        $this->assertSame('Desert Taxi', $settings->business_name);
        $this->assertSame('+91 98765 43210', $settings->contact_number);
        $this->assertSame('02832 123456', $settings->contact_number_2);
        $this->assertSame('919876543210', $settings->whatsapp_number);
        $this->assertSame('hello@example.com', $settings->contact_email);
        $this->assertSame("Office 1\nBhuj", $settings->address);
        $this->assertSame('https://maps.app.goo.gl/example', $settings->google_map_link);
        $this->assertSame('https://www.instagram.com/example', $settings->instagram_link);
        $this->assertSame('https://www.facebook.com/example', $settings->facebook_link);
        $this->assertSame('https://www.youtube.com/@example', $settings->youtube_link);
        $this->assertSame('BUSINESS-GST', $settings->gst_number);
        $this->assertSame(['Driver allowance', 'Fuel', 'Parking'], $settings->general_inclusions);
        $this->assertSame(['Meals', 'Personal expenses'], $settings->general_exclusions);
        $this->get('/admin/settings')->assertOk()->assertSee("Driver allowance\nFuel\nParking");
    }

    public function test_repeated_saves_update_record_one_and_ignore_untrusted_fields(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', ['business_name' => 'First']);
        $this->put('/admin/settings', [
            'business_name' => 'Updated', 'id' => 99, 'logo_path' => 'injected.png',
            'dark_logo_path' => 'injected-dark.png', 'favicon_path' => 'injected-icon.png',
            'theme_color' => 'red', 'created_at' => '2000-01-01',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', ['id' => 1, 'business_name' => 'Updated', 'logo_path' => null]);
        $this->assertNull(Setting::current()->dark_logo_path);
        $this->assertNull(Setting::current()->favicon_path);
    }

    public function test_optional_content_can_be_cleared_without_removing_saved_images(): void
    {
        $path = UploadedFile::fake()->image('logo.png')->store('branding', 'public');
        Setting::current()->fill([
            'business_name' => 'Existing', 'logo_path' => $path, 'contact_email' => 'old@example.com',
            'general_inclusions' => ['Fuel'], 'general_exclusions' => ['Meals'],
        ])->save();
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'business_name' => '', 'contact_email' => '', 'whatsapp_number' => '   ',
            'general_inclusions' => "\n \r\n", 'general_exclusions' => '',
        ])->assertSessionHasNoErrors();
        $settings = Setting::current();
        foreach (Setting::CONTENT_FIELDS as $field) {
            $this->assertNull($settings->getAttribute($field), $field);
        }
        $this->assertSame($path, $settings->logo_path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_invalid_contact_links_and_malformed_fields_are_rejected(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'business_name' => ['unexpected'],
            'contact_number' => str_repeat('1', 31),
            'contact_email' => 'invalid-email',
            'whatsapp_number' => ['unexpected'],
            'instagram_link' => 'javascript:alert(1)',
            'facebook_link' => 'ftp://example.com',
            'youtube_link' => 'not-a-link',
            'google_map_link' => '<iframe src="https://example.com"></iframe>',
            'remove_logo' => 'invalid',
        ])->assertSessionHasErrors([
            'business_name', 'contact_number', 'contact_email', 'whatsapp_number',
            'instagram_link', 'facebook_link', 'youtube_link', 'google_map_link', 'remove_logo',
        ]);
        $this->assertDatabaseCount('settings', 0);
    }

    public function test_empty_arrays_are_stored_as_null_and_list_values_are_trimmed(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'general_inclusions' => [],
            'general_exclusions' => [' Meals ', '', '  ', ' Personal expenses '],
        ])->assertSessionHasNoErrors();
        $this->assertNull(Setting::current()->general_inclusions);
        $this->assertSame(['Meals', 'Personal expenses'], Setting::current()->general_exclusions);
    }

    public function test_whatsapp_requires_an_international_number_without_a_leading_zero(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['01234567890', '1234567', '1234567890123456', 'https://wa.me/919876543210', '+91letters'] as $number) {
            $this->put('/admin/settings', ['whatsapp_number' => $number])->assertSessionHasErrors('whatsapp_number');
        }
        $this->assertDatabaseCount('settings', 0);
    }

    public function test_list_limits_and_invalid_items_are_validated_and_errors_render(): void
    {
        $this->actingAs(User::factory()->create())->from('/admin/settings')->put('/admin/settings', [
            'general_inclusions' => implode("\n", array_fill(0, 101, 'Item')),
            'general_exclusions' => str_repeat('x', 501),
        ])->assertSessionHasErrors(['general_inclusions', 'general_exclusions.0']);
        $this->withCookie(config('session.cookie'), session()->getId())->get('/admin/settings')->assertOk()
            ->assertViewHas('errors', fn ($errors): bool => $errors->has('general_inclusions'))
            ->assertSee('Enter no more than 100 inclusions.')
            ->assertSee('Each exclusion must be no longer than 500 characters.');
        $this->put('/admin/settings', ['general_inclusions' => [['unexpected']]])
            ->assertSessionHasErrors('general_inclusions.0');
        $this->put('/admin/settings', ['general_exclusions' => ['unexpected' => 'Item']])
            ->assertSessionHasErrors('general_exclusions');
        $this->assertDatabaseCount('settings', 0);
    }

    public function test_images_can_be_uploaded_replaced_and_removed(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'logo' => UploadedFile::fake()->image('logo.jpg'),
            'dark_logo' => UploadedFile::fake()->image('dark.webp'),
            'favicon' => UploadedFile::fake()->image('icon.png', 32, 32),
        ])->assertSessionHasNoErrors();
        $initial = Setting::current()->only(array_values(Setting::BRANDING_FILES));
        foreach ($initial as $path) {
            $this->assertStringStartsWith('branding/', $path);
            Storage::disk('public')->assertExists($path);
        }
        $this->get('/admin/settings')->assertOk()->assertSee('Current logo for light backgrounds');

        $this->put('/admin/settings', [
            'logo' => UploadedFile::fake()->image('new-logo.png'),
            'dark_logo' => UploadedFile::fake()->image('new-dark.jpg'),
            'favicon' => UploadedFile::fake()->image('new-icon.png', 64, 64),
            'remove_logo' => '1',
        ])->assertSessionHasNoErrors();
        $replacement = Setting::current()->only(array_values(Setting::BRANDING_FILES));
        foreach ($initial as $column => $path) {
            Storage::disk('public')->assertMissing($path);
            Storage::disk('public')->assertExists($replacement[$column]);
            $this->assertNotSame($path, $replacement[$column]);
        }

        $this->put('/admin/settings', ['remove_logo' => '1', 'remove_dark_logo' => '1', 'remove_favicon' => '1'])
            ->assertSessionHasNoErrors();
        foreach (Setting::BRANDING_FILES as $column) {
            $this->assertNull(Setting::current()->getAttribute($column));
        }
        $this->assertSame([], Storage::disk('public')->allFiles('branding'));
    }

    public function test_removal_of_one_image_preserves_the_other_images(): void
    {
        $logo = UploadedFile::fake()->image('logo.png')->store('branding', 'public');
        $darkLogo = UploadedFile::fake()->image('dark.png')->store('branding', 'public');
        Setting::current()->fill(['logo_path' => $logo, 'dark_logo_path' => $darkLogo])->save();
        $this->actingAs(User::factory()->create())->put('/admin/settings', ['remove_logo' => '1'])->assertSessionHasNoErrors();
        $this->assertNull(Setting::current()->logo_path);
        $this->assertSame($darkLogo, Setting::current()->dark_logo_path);
        Storage::disk('public')->assertMissing($logo);
        Storage::disk('public')->assertExists($darkLogo);
    }

    public function test_unsupported_or_oversized_branding_files_are_rejected(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'logo' => UploadedFile::fake()->create('logo.svg', 10, 'image/svg+xml'),
            'dark_logo' => UploadedFile::fake()->image('large.png')->size(2049),
            'favicon' => UploadedFile::fake()->image('not-png.jpg'),
        ])->assertSessionHasErrors(['logo', 'dark_logo', 'favicon']);
        $this->put('/admin/settings', [
            'logo' => UploadedFile::fake()->image('logo.gif'),
            'favicon' => UploadedFile::fake()->image('large-icon.png')->size(513),
        ])->assertSessionHasErrors(['logo', 'favicon']);
        $this->assertDatabaseCount('settings', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('branding'));
    }

    public function test_failed_database_save_cleans_new_files_and_keeps_old_files_and_values(): void
    {
        $oldPaths = [];
        foreach (Setting::BRANDING_FILES as $column) {
            $oldPaths[$column] = UploadedFile::fake()->image($column.'.png')->store('branding', 'public');
        }
        Setting::current()->fill($oldPaths + ['business_name' => 'Existing'])->save();
        Setting::saving(function (): void {
            throw new RuntimeException('Simulated save failure');
        });
        $this->withoutExceptionHandling();
        try {
            $this->actingAs(User::factory()->create())->put('/admin/settings', [
                'business_name' => 'Must not persist',
                'logo' => UploadedFile::fake()->image('new-logo.jpg'),
                'dark_logo' => UploadedFile::fake()->image('new-dark.png'),
                'favicon' => UploadedFile::fake()->image('new-icon.png'),
            ]);
            $this->fail('Expected a database save failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated save failure', $exception->getMessage());
        }
        $this->assertSame('Existing', Setting::current()->business_name);
        $this->assertSame($oldPaths, Setting::current()->only(array_values(Setting::BRANDING_FILES)));
        $this->assertEqualsCanonicalizing(array_values($oldPaths), Storage::disk('public')->allFiles('branding'));
    }

    public function test_failed_first_save_does_not_leave_a_record_or_new_files(): void
    {
        Setting::saving(fn (): bool => false);
        $this->withoutExceptionHandling();
        try {
            $this->actingAs(User::factory()->create())->put('/admin/settings', [
                'business_name' => 'Must not persist', 'logo' => UploadedFile::fake()->image('logo.png'),
            ]);
            $this->fail('Expected a cancelled save to fail.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Unable to save the business settings.', $exception->getMessage());
        }
        $this->assertDatabaseCount('settings', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('branding'));
    }

    public function test_storage_failure_preserves_existing_settings(): void
    {
        $oldPath = UploadedFile::fake()->image('old.png')->store('branding', 'public');
        Setting::current()->fill(['logo_path' => $oldPath, 'business_name' => 'Existing'])->save();
        $disk = Storage::disk('public');
        $failingDisk = Mockery::mock($disk);
        $failingDisk->shouldReceive('putFileAs')->once()->andReturn(false);
        Storage::set('public', $failingDisk);
        $this->withoutExceptionHandling();
        try {
            $this->actingAs(User::factory()->create())->put('/admin/settings', [
                'business_name' => 'Must not persist', 'logo' => UploadedFile::fake()->image('new.png'),
            ]);
            $this->fail('Expected an upload failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Unable to store the branding image.', $exception->getMessage());
        }
        $this->assertSame('Existing', Setting::current()->business_name);
        $this->assertSame($oldPath, Setting::current()->logo_path);
        $disk->assertExists($oldPath);
    }

    public function test_branding_is_shared_between_login_dashboard_and_profile_with_no_stale_cache(): void
    {
        Setting::current()->fill([
            'business_name' => 'Desert Taxi',
            'logo_path' => 'branding/light.png', 'dark_logo_path' => 'branding/dark.png',
            'favicon_path' => 'branding/icon.png',
        ])->save();
        $this->get('/admin/login')->assertOk()
            ->assertSee('Sign in · Desert Taxi · Admin')
            ->assertSee(Storage::disk('public')->url('branding/light.png'))
            ->assertSee(Storage::disk('public')->url('branding/dark.png'))
            ->assertSee(Storage::disk('public')->url('branding/icon.png'));
        $this->actingAs(User::factory()->create(['name' => 'Account Owner']))->get('/admin')
            ->assertOk()->assertSee('Dashboard · Desert Taxi · Admin')
            ->assertSee('Account Owner')->assertSee(Storage::disk('public')->url('branding/dark.png'));
        $this->get('/admin/profile')->assertOk()->assertSee('My profile · Desert Taxi · Admin');
        $this->put('/admin/settings', ['business_name' => 'Renamed Business'])->assertSessionHasNoErrors();
        $this->get('/admin')->assertSee('Dashboard · Renamed Business · Admin');
        $this->put('/admin/settings', [
            'business_name' => '', 'remove_logo' => '1', 'remove_dark_logo' => '1', 'remove_favicon' => '1',
        ])->assertSessionHasNoErrors();
        $this->get('/admin')->assertSee('Tours &amp; Taxi', false)->assertDontSee('rel="icon"', false);
        $this->post('/admin/logout');
        $this->get('/admin/login')->assertOk()->assertSee('Tours &amp; Taxi', false)
            ->assertDontSee('branding/light.png')->assertDontSee('branding/dark.png')->assertDontSee('rel="icon"', false);
    }

    public function test_business_content_is_escaped_and_public_home_is_unchanged(): void
    {
        Setting::current()->fill(['business_name' => '<script>alert(1)</script>'])->save();
        $this->get('/admin/login')->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
        $this->get('/')->assertOk()->assertDontSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_branding_uses_same_origin_storage_urls_when_app_url_differs_from_herd(): void
    {
        config(['app.url' => 'http://localhost:8000']);
        Setting::current()->fill([
            'business_name' => 'Desert Taxi',
            'logo_path' => 'branding/light.png',
            'dark_logo_path' => 'branding/dark.png',
            'favicon_path' => 'branding/icon.png',
        ])->save();

        $this->get('http://desert-taxi.test/admin/login')->assertOk()
            ->assertSee('src="/storage/branding/light.png"', false)
            ->assertSee('src="/storage/branding/dark.png"', false)
            ->assertSee('href="/storage/branding/icon.png"', false)
            ->assertDontSee('http://localhost:8000/storage', false);

        $this->actingAs(User::factory()->create())
            ->get('http://desert-taxi.test/admin/settings')->assertOk()
            ->assertSee('src="/storage/branding/light.png"', false)
            ->assertSee('src="/storage/branding/dark.png"', false)
            ->assertDontSee('http://localhost:8000/storage', false);
    }
}
