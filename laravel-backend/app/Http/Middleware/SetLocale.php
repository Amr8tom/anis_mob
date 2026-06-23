<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the request locale (validated against config/locales.php) and applies
 * it to the app, Carbon, and the views. Web and API differ slightly in priority.
 */
final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var array<string, mixed> $supported */
        $supported = (array) config('locales.supported', []);
        $codes = array_keys($supported);
        $default = (string) config('locales.default', 'ar');

        $isApi = $request->is('api/*') || $request->expectsJson();

        $locale = $isApi
            ? $this->resolveApi($request, $codes)
            : $this->resolveWeb($request, $codes);

        $locale = in_array($locale, $codes, true) ? $locale : $default;

        App::setLocale($locale);
        Carbon::setLocale($locale);

        $dir = (string) ($supported[$locale]['dir'] ?? 'rtl');
        view()->share('currentLocale', $locale);
        view()->share('currentDirection', $dir);

        // Persist an explicit web choice so it sticks across requests.
        if (! $isApi && $this->isValid($request->query('lang'), $codes)) {
            Cookie::queue('locale', $locale, 60 * 24 * 365);
            $this->putSessionLocale($request, $locale);
        }

        return $next($request);
    }

    /**
     * Web: ?lang -> cookie -> session -> authenticated preference -> default.
     *
     * @param  array<int, string>  $codes
     */
    private function resolveWeb(Request $request, array $codes): ?string
    {
        if ($request->query->has('lang')) {
            $queryLocale = $request->query('lang');

            return $this->isValid($queryLocale, $codes) ? (string) $queryLocale : null;
        }

        return $this->firstValid([
            $request->cookie('locale'),
            $this->sessionLocale($request),
            $this->userLocale($request),
        ], $codes);
    }

    /**
     * API: X-Locale header -> authenticated preference -> Accept-Language -> default.
     *
     * @param  array<int, string>  $codes
     */
    private function resolveApi(Request $request, array $codes): ?string
    {
        return $this->firstValid([
            $request->header('X-Locale'),
            $this->userLocale($request),
            $request->getPreferredLanguage($codes),
        ], $codes);
    }

    private function userLocale(Request $request): ?string
    {
        $owner = $request->user('workspace_owner');
        if ($owner !== null && ! empty($owner->locale)) {
            return (string) $owner->locale;
        }

        $user = $request->user();

        return $user !== null && ! empty($user->locale) ? (string) $user->locale : null;
    }

    private function sessionLocale(Request $request): ?string
    {
        if (! $request->hasSession()) {
            return null;
        }

        $locale = $request->session()->get('locale');

        return is_string($locale) ? $locale : null;
    }

    private function putSessionLocale(Request $request, string $locale): void
    {
        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }
    }

    /**
     * @param  array<int, string|null>  $candidates
     * @param  array<int, string>  $codes
     */
    private function firstValid(array $candidates, array $codes): ?string
    {
        foreach ($candidates as $candidate) {
            if ($this->isValid($candidate, $codes)) {
                return (string) $candidate;
            }
        }

        return null;
    }

    /**
     * @param  array<int, string>  $codes
     */
    private function isValid(?string $value, array $codes): bool
    {
        return $value !== null && $value !== '' && in_array($value, $codes, true);
    }
}
