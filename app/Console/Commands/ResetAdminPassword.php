<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password';

    protected $description = 'Reset an administrator password and invalidate existing logins';

    public function handle(): int
    {
        $email = Str::lower(trim((string) $this->ask('Email address')));
        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error('No administrator exists with that email address.');

            return self::FAILURE;
        }
        $data = [
            'password' => $this->secret('New password (at least 12 characters, with letters and numbers)'),
            'password_confirmation' => $this->secret('Confirm password'),
        ];
        $validator = Validator::make($data, ['password' => ['required', Password::defaults(), 'confirmed']]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }
        $user->forceFill(['password' => $data['password'], 'remember_token' => Str::random(60)])->save();
        $this->info('Password reset successfully. Existing logins will be invalidated.');

        return self::SUCCESS;
    }
}
