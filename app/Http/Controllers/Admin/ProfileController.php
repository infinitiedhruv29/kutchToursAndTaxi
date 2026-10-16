<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\AdminFeedback;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile', ['user' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $oldAvatar = $user->avatar_path;
        $newAvatar = null;
        $data = $request->safe()->only(['name', 'email']);

        try {
            if ($request->hasFile('avatar')) {
                $newAvatar = $request->file('avatar')->store('avatars', 'public');
                if ($newAvatar === false) {
                    throw new RuntimeException('Unable to store the avatar.');
                }
                $data['avatar_path'] = $newAvatar;
            } elseif ($request->boolean('remove_avatar')) {
                $data['avatar_path'] = null;
            }
            if ($data['email'] !== $user->email) {
                $user->email_verified_at = null;
            }
            DB::transaction(fn () => $user->fill($data)->save());
        } catch (Throwable $exception) {
            if (is_string($newAvatar)) {
                Storage::disk('public')->delete($newAvatar);
            }
            throw $exception;
        }

        if ($oldAvatar && $oldAvatar !== $user->avatar_path) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return AdminFeedback::success($request, 'Your profile has been updated.', route('admin.profile.edit'));
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $user->forceFill([
            'password' => $request->validated('password'),
            'remember_token' => Str::random(60),
        ])->save();
        Auth::logoutOtherDevices($request->validated('password'));
        $request->session()->regenerate();

        return AdminFeedback::success($request, 'Your password has been changed. Other sessions have been signed out.', route('admin.profile.edit'));
    }
}
