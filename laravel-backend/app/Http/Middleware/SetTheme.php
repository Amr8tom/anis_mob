<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

final class SetTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        $default = 'dark';
        $validThemes = ['dark', 'light'];

        $theme = $this->resolveTheme($request, $validThemes) ?? $default;

        view()->share('currentTheme', $theme);

        // Persist explicit web choice to cookie.
        if ($this->isValid((string) $request->query('theme'), $validThemes)) {
            Cookie::queue('theme', $theme, 60 * 24 * 365);
        }

        return $next($request);
    }

    /**
     * @param array<int, string> $validThemes
     */
    private function resolveTheme(Request $request, array $validThemes): ?string
    {
        if ($request->query->has('theme')) {
            $queryTheme = (string) $request->query('theme');
            if ($this->isValid($queryTheme, $validThemes)) {
                return $queryTheme;
            }
        }

        $cookieTheme = $request->cookie('theme');
        if ($this->isValid((string) $cookieTheme, $validThemes)) {
            return (string) $cookieTheme;
        }

        $owner = $request->user('workspace_owner');
        if ($owner !== null && ! empty($owner->theme)) {
            $userTheme = (string) $owner->theme;
            if ($this->isValid($userTheme, $validThemes)) {
                return $userTheme;
            }
        }

        return null;
    }

    /**
     * @param array<int, string> $validThemes
     */
    private function isValid(string $value, array $validThemes): bool
    {
        return in_array($value, $validThemes, true);
    }
}
