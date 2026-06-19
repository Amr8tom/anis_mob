<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\IdempotencyKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IdempotentRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = trim((string) $request->header('Idempotency-Key'));
        if ($key === '') {
            return $next($request);
        }

        abort_if(strlen($key) > 100, 422, 'Idempotency-Key must not exceed 100 characters.');

        $actorKey = (string) ($request->user()?->id ?? $request->user('admin')?->id ?? $request->ip());
        $operation = $request->method().' '.($request->route()?->getName() ?? $request->route()?->uri());
        $requestHash = hash('sha256', $request->getContent());
        $record = IdempotencyKey::firstOrCreate(
            ['actor_key' => $actorKey, 'operation' => $operation, 'idempotency_key' => $key],
            ['request_hash' => $requestHash, 'status' => 'PROCESSING'],
        );

        if (! $record->wasRecentlyCreated) {
            abort_if(! hash_equals($record->request_hash, $requestHash), 409, 'Idempotency key was already used with a different request.');
            abort_if($record->status !== 'COMPLETED', 409, 'An identical request is still processing.');

            return response($record->response_body, $record->response_status ?? 200, ['Content-Type' => 'application/json']);
        }

        try {
            $response = $next($request);
            $record->update([
                'status' => 'COMPLETED',
                'response_status' => $response->getStatusCode(),
                'response_body' => $response->getContent(),
            ]);

            return $response;
        } catch (\Throwable $exception) {
            $record->delete();
            throw $exception;
        }
    }
}
