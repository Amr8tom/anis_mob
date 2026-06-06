<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

final class ApiResponse
{
    public static function ok(mixed $data, string $message = 'OK'): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data]);
    }

    public static function created(mixed $data, string $message = 'Created'): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data], 201);
    }

    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * @param  array<string, array<int, string>>|null  $errors
     */
    public static function error(string $message, int $code = 400, ?array $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => null,
        ], $code);
    }

    /**
     * Standard envelope for paginated list endpoints: data = rows, meta = paging info.
     */
    public static function paginated(ResourceCollection $collection, string $message = 'OK'): JsonResponse
    {
        /** @var LengthAwarePaginator<int, mixed> $paginator */
        $paginator = $collection->resource;

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $collection->collection,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}
