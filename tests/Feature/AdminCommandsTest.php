<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_be_created_with_normalized_email_and_hashed_password(): void
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Administrator name', 'Site Admin')
            ->expectsQuestion('Email address', 'ADMIN@example.com')
            ->expectsQuestion('Password (at least 12 characters, with letters and numbers)', 'SecurePassword123')
            ->expectsQuestion('Confirm password', 'SecurePassword123')
            ->expectsOutput('Administrator created successfully.')->assertSuccessful();
        $user = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('SecurePassword123', $user->password));
    }

    public function test_duplicate_email_and_weak_password_are_rejected(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);
        $this->artisan('admin:create')
            ->expectsQuestion('Administrator name', 'Site Admin')
            ->expectsQuestion('Email address', 'admin@example.com')
            ->expectsQuestion('Password (at least 12 characters, with letters and numbers)', 'short')
            ->expectsQuestion('Confirm password', 'different')->assertFailed();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_password_reset_rotates_token_and_hashes_password(): void
    {
        $user = User::factory()->create(['remember_token' => 'old-token']);
        $this->artisan('admin:reset-password')
            ->expectsQuestion('Email address', $user->email)
            ->expectsQuestion('New password (at least 12 characters, with letters and numbers)', 'ResetPassword123')
            ->expectsQuestion('Confirm password', 'ResetPassword123')->assertSuccessful();
        $this->assertTrue(Hash::check('ResetPassword123', $user->fresh()->password));
        $this->assertNotSame('old-token', $user->fresh()->remember_token);
    }

    public function test_reset_rejects_missing_accounts_and_invalid_passwords(): void
    {
        $this->artisan('admin:reset-password')->expectsQuestion('Email address', 'missing@example.com')->assertFailed();
        $user = User::factory()->create();
        $this->artisan('admin:reset-password')
            ->expectsQuestion('Email address', $user->email)
            ->expectsQuestion('New password (at least 12 characters, with letters and numbers)', 'onlyletterslong')
            ->expectsQuestion('Confirm password', 'onlyletterslong')->assertFailed();
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_default_seeder_does_not_create_credentials(): void
    {
        $this->seed();
        $this->assertDatabaseCount('users', 0);
    }
}
