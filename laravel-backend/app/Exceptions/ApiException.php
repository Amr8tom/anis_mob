<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Support\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Base class for domain exceptions. Each subclass sets an HTTP status and a
 * human-readable message, and renders into the standard API envelope.
 */
abstract class ApiException extends Exception
{
    protected int $status = 400;

    public function render(Request $request): JsonResponse
    {
        return ApiResponse::error($this->getMessage(), $this->status);
    }
}
