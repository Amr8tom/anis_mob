<?php

use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Map framework exceptions into the standard { success, message, errors, data } envelope.
        // (Domain ApiException subclasses render themselves via their own render() method.)
        $exceptions->render(fn (ValidationException $e) => ApiResponse::error('The given data was invalid.', 422, $e->errors()));
        $exceptions->render(fn (AuthenticationException $e) => ApiResponse::error('Unauthenticated.', 401));
        $exceptions->render(fn (AuthorizationException $e) => ApiResponse::error($e->getMessage() ?: 'This action is unauthorized.', 403));
        $exceptions->render(fn (ModelNotFoundException $e) => ApiResponse::error('Resource not found.', 404));
        $exceptions->render(fn (NotFoundHttpException $e) => ApiResponse::error('Resource not found.', 404));
        $exceptions->render(fn (ThrottleRequestsException $e) => ApiResponse::error('Too many requests. Please slow down.', 429));
    })->create();
