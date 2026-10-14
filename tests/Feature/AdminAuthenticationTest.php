<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guests_are_redirected_and_login_is_available(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/profile')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Welcome back');
        $this->patch('/admin/profile', [])->assertRedirect('/admin/login');
        $this->put('/admin/profile/password', [])->assertRedirect('/admin/login');
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->get('/register')->assertNotFound();
    }

    public function test_login_regenerates_the_session_and_remembers_the_user(): void
    {
        $user = User::factory()->create();
        $this->get('/admin/login');
        $sessionId = session()->getId();
        $this->post('/admin/login', ['email' => strtoupper($user->email), 'password' => 'password', 'remember' => '1'])
            ->assertRedirect('/admin')->assertCookie(Auth::guard()->getRecallerName());
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($sessionId, session()->getId());
    }

    public function test_invalid_credentials_are_generic_and_do_not_authenticate(): void
    {
        $user = User::factory()->create();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors(['email' => 'The provided credentials are incorrect.']);
        $this->assertGuest();
        $this->assertNull(session()->getOldInput('password'));
    }

    public function test_malformed_login_fields_are_validation_errors(): void
    {
        $this->post('/admin/login', ['email' => ['unexpected'], 'password' => ['unexpected']])
            ->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_failures_and_recovers_after_a_minute(): void
    {
        $user = User::factory()->create();
        $credentials = ['email' => $user->email, 'password' => 'wrong'];
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/admin/login', $credentials)->assertSessionHasErrors('email');
        }
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->travel(61)->seconds();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_successful_login_clears_failed_attempts(): void
    {
        $user = User::factory()->create();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong']);
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/admin');
        $key = 'admin-login:'.hash('sha256', $user->email.'|127.0.0.1');
        $this->assertSame(0, RateLimiter::attempts($key));
    }

    public function test_login_preserves_only_internal_admin_destinations(): void
    {
        $user = User::factory()->create();
        $this->withSession(['url.intended' => url('/admin/profile')])
            ->post('/admin/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/admin/profile');
        $this->post('/admin/logout');
        $this->withSession(['url.intended' => 'https://example.org/admin/profile'])
            ->post('/admin/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/admin');
        $this->post('/admin/logout');
        $this->withSession(['url.intended' => url('/')])
            ->post('/admin/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/admin');
    }

    public function test_authenticated_users_skip_login_and_logout_invalidates_the_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin/login')->assertRedirect('/admin');
        $this->withSession(['private_marker' => 'secret'])->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
        $this->assertNull(session('private_marker'));
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
