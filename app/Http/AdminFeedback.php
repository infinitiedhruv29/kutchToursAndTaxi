<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFeedback
{
    public static function success(Request $request, string $message, string $destination): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'redirect_url' => $destination]);
        }

        return redirect()->to($destination)->with('status', $message);
    }

    /** @param array<string, mixed> $queryRules */
    public static function returnDestination(Request $request, string $listingRoute, array $queryRules): string
    {
        $fallback = route($listingRoute);
        $value = $request->input('return_to');
        if (! is_string($value) || strlen($value) > 4096 || preg_match('/[\x00-\x20\x7f\\\\]/', $value)
            || preg_match('/%(?![a-fA-F0-9]{2})/', $value) || str_starts_with($value, '//')) {
            return $fallback;
        }
        $parts = parse_url($value);
        if ($parts === false || isset($parts['user']) || isset($parts['pass']) || isset($parts['fragment'])
            || ($parts['path'] ?? '') !== parse_url($fallback, PHP_URL_PATH)) {
            return $fallback;
        }
        if (isset($parts['host']) && ($parts['host'] !== $request->getHost()
            || ($parts['scheme'] ?? '') !== $request->getScheme()
            || ($parts['port'] ?? ($request->isSecure() ? 443 : 80)) !== $request->getPort())) {
            return $fallback;
        }
        if (isset($parts['scheme']) && ! isset($parts['host'])) {
            return $fallback;
        }
        parse_str($parts['query'] ?? '', $query);
        if (array_diff(array_keys($query), array_keys($queryRules)) !== []) {
            return $fallback;
        }
        $validator = Validator::make($query, $queryRules);
        if ($validator->fails()) {
            return $fallback;
        }
        $queryString = http_build_query($validator->validated(), '', '&', PHP_QUERY_RFC3986);

        return $fallback.($queryString === '' ? '' : '?'.$queryString);
    }
}
