<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    public function test_profile_update_only_changes_the_authenticated_account(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($user)->patch('/admin/profile', [
            'name' => 'Updated Admin', 'email' => $user->email, 'id' => $other->id,
            'password' => 'InjectedPassword123', 'avatar_path' => 'arbitrary.jpg',
        ])->assertRedirect('/admin/profile')->assertSessionHas('status');
        $this->assertSame('Updated Admin', $user->fresh()->name);
        $this->assertSame($other->name, $other->fresh()->name);
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertNull($user->fresh()->avatar_path);
    }

    public function test_email_changes_require_current_password_and_unique_email(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($user)->patch('/admin/profile', ['name' => $user->name, 'email' => 'new@example.com'])
            ->assertSessionHasErrors('current_password');
        $this->patch('/admin/profile', ['name' => $user->name, 'email' => 'new@example.com', 'current_password' => 'wrong'])
            ->assertSessionHasErrors('current_password');
        $this->patch('/admin/profile', ['name' => $user->name, 'email' => $other->email, 'current_password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->patch('/admin/profile', ['name' => $user->name, 'email' => 'NEW@example.com', 'current_password' => 'password'])
            ->assertRedirect('/admin/profile')->assertSessionHasNoErrors();
        $this->assertSame('new@example.com', $user->fresh()->email);
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_malformed_profile_email_is_rejected(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch('/admin/profile', ['name' => $user->name, 'email' => ['unexpected']])
            ->assertSessionHasErrors('email');
        $this->assertSame($user->email, $user->fresh()->email);
    }

    public function test_avatar_can_be_uploaded_replaced_and_removed(): void
    {
        $user = User::factory()->create();
        $data = ['name' => $user->name, 'email' => $user->email];
        $this->actingAs($user)->patch('/admin/profile', $data + ['avatar' => UploadedFile::fake()->image('photo.jpg')])
            ->assertSessionHasNoErrors();
        $first = $user->fresh()->avatar_path;
        Storage::disk('public')->assertExists($first);
        $this->patch('/admin/profile', $data + ['avatar' => UploadedFile::fake()->image('new.png')])
            ->assertSessionHasNoErrors();
        $second = $user->fresh()->avatar_path;
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);
        $this->patch('/admin/profile', $data + ['remove_avatar' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($user->fresh()->avatar_path);
        Storage::disk('public')->assertMissing($second);
    }

    public function test_invalid_or_oversized_avatars_are_rejected(): void
    {
        $user = User::factory()->create();
        $data = ['name' => $user->name, 'email' => $user->email];
        $this->actingAs($user)->patch('/admin/profile', $data + ['avatar' => UploadedFile::fake()->create('file.svg', 10, 'image/svg+xml')])
            ->assertSessionHasErrors('avatar');
        $this->patch('/admin/profile', $data + ['avatar' => UploadedFile::fake()->image('large.jpg')->size(2049)])
            ->assertSessionHasErrors('avatar');
        $this->assertNull($user->fresh()->avatar_path);
    }

    public function test_failed_profile_save_cleans_new_avatar_and_preserves_existing_file(): void
    {
        $oldAvatar = UploadedFile::fake()->image('old.jpg')->store('avatars', 'public');
        $user = User::factory()->create(['avatar_path' => $oldAvatar]);
        User::saving(function (): void {
            throw new RuntimeException('Simulated save failure');
        });
        $this->withoutExceptionHandling();
        try {
            $this->actingAs($user)->patch('/admin/profile', [
                'name' => $user->name, 'email' => $user->email,
                'avatar' => UploadedFile::fake()->image('new.jpg'),
            ]);
            $this->fail('Expected the save failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated save failure', $exception->getMessage());
        }
        $this->assertSame([$oldAvatar], Storage::disk('public')->allFiles('avatars'));
        $this->assertSame($oldAvatar, $user->fresh()->avatar_path);
    }

    public function test_password_change_requires_current_password_and_policy_and_confirmation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->put('/admin/profile/password', [
            'current_password' => 'wrong', 'password' => 'NewPassword1234', 'password_confirmation' => 'NewPassword1234',
        ])->assertSessionHasErrors('current_password', null, 'password');
        $this->put('/admin/profile/password', [
            'current_password' => 'password', 'password' => 'short', 'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password', null, 'password');
        $this->put('/admin/profile/password', [
            'current_password' => 'password', 'password' => 'NewPassword1234', 'password_confirmation' => 'Mismatch1234',
        ])->assertSessionHasErrors('password', null, 'password');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_password_change_hashes_password_rotates_remember_token_and_keeps_current_session(): void
    {
        $user = User::factory()->create(['remember_token' => 'old-token']);
        $this->actingAs($user)->get('/admin/profile')->assertOk();
        $oldHash = $user->password;
        $this->put('/admin/profile/password', [
            'current_password' => 'password', 'password' => 'NewPassword1234', 'password_confirmation' => 'NewPassword1234',
        ])->assertRedirect('/admin/profile')->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewPassword1234', $user->fresh()->password));
        $this->assertNotSame('old-token', $user->fresh()->remember_token);
        $this->get('/admin/profile')->assertOk();
        $this->assertAuthenticatedAs($user);
        $this->withSession(['password_hash_web' => $oldHash])->get('/admin')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_stale_remember_cookie_cannot_authenticate_after_password_change(): void
    {
        $user = User::factory()->create(['remember_token' => 'old-token']);
        $cookieName = Auth::guard()->getRecallerName();
        $cookieValue = $user->id.'|old-token|'.Auth::guard()->hashPasswordForCookie($user->password);
        $user->forceFill(['password' => 'NewPassword1234', 'remember_token' => 'new-token'])->save();
        Auth::forgetGuards();
        $this->withCookie($cookieName, $cookieValue)->get('/admin')->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
