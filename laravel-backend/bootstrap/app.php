<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AdminPermission;
use App\Http\Middleware\EnsureWorkspaceOwner;
use App\Http\Middleware\IdempotentRequest;
use App\Http\Middleware\RequestTelemetry;
use App\Http\Middleware\SetLocale;
use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
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
        $middleware->append(RequestTelemetry::class);
        $middleware->web(append: [
            SetLocale::class,
        ]);
        $middleware->api(append: [
            SetLocale::class,
        ]);
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin/*')
            ? route('admin.login')
            : route('workspace.login'));
        $middleware->redirectUsersTo(fn (Request $request) => $request->is('admin/*')
            ? route('admin.dashboard')
            : route('workspace.settings.edit'));
        $middleware->alias([
            'workspace.owner' => EnsureWorkspaceOwner::class,
            'admin' => AdminMiddleware::class,
            'admin.permission' => AdminPermission::class,
            'idempotent' => IdempotentRequest::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('visits:auto-checkout')->everyFiveMinutes()->onOneServer()->withoutOverlapping();
        $schedule->command('workspace-subscriptions:expire')->everyFifteenMinutes()->onOneServer()->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Map framework exceptions into the standard { success, message, errors, data } envelope for API requests.
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error('The given data was invalid.', 422, $e->errors());
            }

            return null;
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error('Unauthenticated.', 401);
            }

            return null;
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error($e->getMessage() ?: 'This action is unauthorized.', 403);
            }

            return null;
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error('Resource not found.', 404);
            }

            return null;
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error('Resource not found.', 404);
            }

            return null;
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error('Too many requests. Please slow down.', 429);
            }

            return null;
        });

        // Handle oversized uploads gracefully (POST exceeds PHP post_max_size)
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error('الملف كبير جداً. الحجم الأقصى المسموح به هو 50MB.', 413);
            }

            return back()
                ->with('error', 'الصور المرفوعة كبيرة جداً حتى بعد الضغط. يُرجى تقليل عدد الصور أو اختيار صور بحجم أصغر (الحد الأقصى 50MB إجمالاً).')
                ->with('open_tab', 'gallery-tab');
        });
    })->create();
