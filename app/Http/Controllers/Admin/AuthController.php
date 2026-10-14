<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('admin.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $intended = $request->session()->pull('url.intended');
        $path = is_string($intended) ? parse_url($intended, PHP_URL_PATH) : null;
        $origin = is_string($intended) ? parse_url($intended) : [];
        $sameOrigin = ! isset($origin['host']) || (
            $origin['host'] === $request->getHost()
            && ($origin['scheme'] ?? $request->getScheme()) === $request->getScheme()
            && ($origin['port'] ?? ($request->isSecure() ? 443 : 80)) === $request->getPort()
        );

        if ($sameOrigin && is_string($path) && ($path === '/admin' || str_starts_with($path, '/admin/'))
            && ! in_array($path, ['/admin/login', '/admin/logout'], true)) {
            return redirect()->to($intended);
        }

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been signed out.');
    }
}
