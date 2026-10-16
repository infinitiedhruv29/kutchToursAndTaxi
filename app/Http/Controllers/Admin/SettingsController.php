<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => Setting::current()]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $newPaths = [];
        $oldPaths = [];

        try {
            DB::transaction(function () use ($request, &$newPaths, &$oldPaths): void {
                $settings = Setting::query()->lockForUpdate()->find(Setting::SINGLETON_ID)
                    ?? (new Setting)->forceFill(['id' => Setting::SINGLETON_ID]);
                $data = array_replace(
                    array_fill_keys(Setting::CONTENT_FIELDS, null),
                    $request->safe()->only(Setting::CONTENT_FIELDS),
                );

                foreach (Setting::BRANDING_FILES as $input => $column) {
                    $oldPath = $settings->getAttribute($column);

                    if ($request->hasFile($input)) {
                        $path = $request->file($input)->store('branding', 'public');

                        if ($path === false) {
                            throw new RuntimeException('Unable to store the branding image.');
                        }

                        $newPaths[] = $path;
                        $data[$column] = $path;
                    } elseif ($request->boolean('remove_'.$input)) {
                        $data[$column] = null;
                    }

                    if ($oldPath && array_key_exists($column, $data) && $oldPath !== $data[$column]) {
                        $oldPaths[] = $oldPath;
                    }
                }

                if (! $settings->fill($data)->save()) {
                    throw new RuntimeException('Unable to save the business settings.');
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newPaths);
            throw $exception;
        }

        Storage::disk('public')->delete(array_unique($oldPaths));

        return redirect()->route('admin.settings.edit')->with('status', 'Business settings have been saved.');
    }
}
