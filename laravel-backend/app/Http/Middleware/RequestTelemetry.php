<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class RequestTelemetry
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = hrtime(true);
        $requestId = $request->header('X-Request-Id') ?: (string) Str::uuid7();
        $request->headers->set('X-Request-Id', $requestId);

        try {
            return $response = $next($request);
        } finally {
            $durationMs = round((hrtime(true) - $startedAt) / 1_000_000, 2);
            Log::info('http.request', [
                'request_id' => $requestId,
                'method' => $request->method(),
                'route' => $request->route()?->getName() ?? $request->path(),
                'status' => isset($response) ? $response->getStatusCode() : 500,
                'duration_ms' => $durationMs,
                'user_id' => $request->user()?->id ?? $request->user('admin')?->id,
            ]);
            if (isset($response)) {
                $response->headers->set('X-Request-Id', $requestId);
            }
        }
    }
}
