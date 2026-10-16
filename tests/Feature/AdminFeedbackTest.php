<?php

namespace Tests\Feature;

use App\Models\TaxiRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    private function routeData(array $overrides = []): array
    {
        return array_replace(['origin_name' => 'Bhuj', 'destination_name' => 'Mandvi', 'title' => 'Bhuj taxi', 'slug' => 'bhuj-taxi'], $overrides);
    }

    public function test_json_login_uses_intended_admin_destination_without_flashing_success(): void
    {
        $user = User::factory()->create();
        $this->get('/admin/login');
        $sessionId = session()->getId();
        $this->withSession(['url.intended' => url('/admin/routes?status=draft&page=2')])
            ->postJson('/admin/login', ['email' => $user->email, 'password' => 'password', 'remember' => true])
            ->assertOk()->assertJsonPath('redirect_url', url('/admin/routes?status=draft&page=2'))
            ->assertJsonPath('message', 'You have been signed in.')->assertSessionMissing('status');
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($sessionId, session()->getId());
        $this->get('/admin')->assertOk()->assertDontSee('data-flash-message', false);
    }

    public function test_json_login_errors_remain_generic_and_throttled(): void
    {
        $user = User::factory()->create();
        for ($index = 0; $index < 5; $index++) {
            $this->postJson('/admin/login', ['email' => $user->email, 'password' => 'wrong'])
                ->assertUnprocessable()->assertJsonValidationErrors('email');
        }
        $response = $this->postJson('/admin/login', ['email' => $user->email, 'password' => 'password']);
        $response->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->assertStringContainsString('Too many login attempts', $response->json('errors.email.0'));
        $this->assertGuest();
    }

    public function test_json_logout_invalidates_the_session_and_does_not_repeat_a_message(): void
    {
        $this->actingAs(User::factory()->create());
        $this->withSession(['private' => 'remove'])->postJson('/admin/logout')->assertOk()
            ->assertJsonPath('redirect_url', route('admin.login'))->assertSessionMissing('private')->assertSessionMissing('status');
        $this->assertGuest();
        $this->get('/admin/login')->assertOk()->assertDontSee('data-flash-message', false);
    }

    public function test_json_profile_and_password_updates_return_to_profile_and_keep_current_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patchJson('/admin/profile', ['name' => 'Updated admin', 'email' => $user->email])
            ->assertOk()->assertJsonPath('redirect_url', route('admin.profile.edit'))->assertSessionMissing('status');
        $this->assertSame('Updated admin', $user->fresh()->name);
        $this->putJson('/admin/profile/password', ['current_password' => 'password', 'password' => 'UpdatedPassword123', 'password_confirmation' => 'UpdatedPassword123'])
            ->assertOk()->assertJsonPath('redirect_url', route('admin.profile.edit'))->assertSessionMissing('status');
        $this->assertTrue(Hash::check('UpdatedPassword123', $user->fresh()->password));
        $this->get('/admin/profile')->assertOk();
    }

    public function test_json_validation_is_standard_and_cannot_modify_another_account(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($user)->patchJson('/admin/profile', ['name' => '', 'email' => $other->email, 'id' => $other->id])
            ->assertUnprocessable()->assertJsonStructure(['message', 'errors'])->assertJsonValidationErrors(['name', 'email', 'current_password']);
        $this->putJson('/admin/profile/password', ['current_password' => 'wrong', 'password' => 'short'])
            ->assertUnprocessable()->assertJsonValidationErrors(['current_password', 'password']);
        $this->assertNotSame('', $other->fresh()->name);
    }

    public function test_multipart_ajax_settings_and_profile_uploads_keep_existing_storage_behavior(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/profile', ['_method' => 'PATCH', 'name' => $user->name, 'email' => $user->email, 'avatar' => UploadedFile::fake()->image('avatar.webp')], ['Accept' => 'application/json'])
            ->assertOk()->assertJsonPath('redirect_url', route('admin.profile.edit'));
        Storage::disk('public')->assertExists($user->fresh()->avatar_path);
        $this->post('/admin/settings', ['_method' => 'PUT', 'business_name' => 'Taxi business', 'logo' => UploadedFile::fake()->image('logo.png')], ['Accept' => 'application/json'])
            ->assertOk()->assertJsonPath('redirect_url', route('admin.settings.edit'))->assertSessionMissing('status');
        $this->assertDatabaseHas('settings', ['business_name' => 'Taxi business']);
    }

    public function test_route_create_edit_cancel_and_save_preserve_a_validated_listing_destination(): void
    {
        $this->actingAs(User::factory()->create());
        $destination = url('/admin/routes?q=Bhuj&status=draft&sort=title&direction=desc&page=2&per_page=25');
        $this->get('/admin/routes/create?'.http_build_query(['return_to' => $destination]))
            ->assertOk()->assertViewHas('returnTo', $destination)->assertSee('name="return_to"', false);
        $this->postJson('/admin/routes', $this->routeData(['return_to' => $destination]))->assertOk()
            ->assertJsonPath('redirect_url', $destination)->assertSessionMissing('status');
        $route = TaxiRoute::firstOrFail();
        $this->get('/admin/routes/'.$route->id.'/edit?'.http_build_query(['return_to' => $destination]))
            ->assertOk()->assertViewHas('returnTo', $destination)->assertSee('href="'.e($destination).'"', false);
        $this->putJson('/admin/routes/'.$route->id, $this->routeData(['return_to' => $destination]))
            ->assertOk()->assertJsonPath('redirect_url', $destination);
        $this->put('/admin/routes/'.$route->id, $this->routeData(['return_to' => $destination]))->assertRedirect($destination)->assertSessionHas('status');
    }

    public function test_direct_entry_and_hostile_return_destinations_fall_back_to_route_listing(): void
    {
        $this->actingAs(User::factory()->create());
        $route = TaxiRoute::create($this->routeData());
        foreach ([
            null, 'https://evil.test/admin/routes', '//localhost/admin/routes', '/admin/profile', '/admin/routes/data',
            '/admin/routes?sort=password', '/admin/routes?per_page=-1', '/admin/routes?page=0', '/admin/routes?q[]=bad',
            '/admin/routes?unknown=value', '/admin/routes#fragment', '/admin/routes%0d%0aLocation:evil',
            'javascript:/admin/routes', 'http://localhost:81/admin/routes', 'http://admin@localhost/admin/routes',
            '/admin/routes?q=%ZZ', '/admin/routes\\evil', ['bad'],
        ] as $destination) {
            $this->putJson('/admin/routes/'.$route->id, $this->routeData(['return_to' => $destination]))
                ->assertOk()->assertJsonPath('redirect_url', route('admin.routes.index'));
        }
        $this->get('/admin/routes/'.$route->id.'/edit')->assertViewHas('returnTo', route('admin.routes.index'));
        $this->putJson('/admin/routes/'.$route->id, $this->routeData(['return_to' => '/admin/routes?q=0&page=2']))
            ->assertOk()->assertJsonPath('redirect_url', route('admin.routes.index').'?q=0&page=2');
    }

    public function test_native_flash_feedback_and_explicit_managed_forms_are_present(): void
    {
        $user = User::factory()->create();
        $this->get('/admin/login')->assertSee('data-admin-form', false);
        $this->actingAs($user)->get('/admin/profile')->assertSee('data-loading-text="Updating password…"', false);
        $this->put('/admin/settings', ['business_name' => 'Saved'])->assertRedirect('/admin/settings')->assertSessionHas('status');
        $this->get('/admin/settings')->assertOk()->assertSee('data-flash-message', false)->assertSee('Business settings have been saved.');
    }
}
