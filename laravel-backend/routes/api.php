<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceTokenController;
use App\Http\Controllers\Api\V1\BuddySessionController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\WorkspaceController;
use App\Http\Controllers\Api\V1\WorkspacePrivateSessionCheckInController;
use App\Http\Controllers\Api\V1\WorkspaceVisitController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('health/ready', [HealthController::class, 'ready'])->name('health.ready');

Route::prefix('v1')->group(function (): void {
    // ============================ PUBLIC (tokenless) ============================
    // Auth (throttled)
    Route::post('auth/register', [AuthController::class, 'register'])->middleware('throttle:auth');
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:auth');

    // Public catalog / discovery — redacted, read-only, paginated.
    Route::middleware('throttle:public')->group(function (): void {
        Route::get('workspaces', [WorkspaceController::class, 'index']);
        Route::get('workspaces/{workspace}', [WorkspaceController::class, 'show']);
        Route::get('buddy-sessions', [BuddySessionController::class, 'index']);
        Route::get('buddy-sessions/{session}', [BuddySessionController::class, 'show']);
        Route::get('plans', [PlanController::class, 'index']);
    });

    // ============================ AUTHENTICATED ============================
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
        // Auth
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Push notification device tokens (FCM)
        Route::post('device-tokens', [DeviceTokenController::class, 'store']);
        Route::delete('device-tokens', [DeviceTokenController::class, 'destroy']);

        // Home
        Route::get('home/profile', [HomeController::class, 'profile']);
        Route::get('home/today-sessions', [HomeController::class, 'todaySessions']);

        // QR workspace attendance
        Route::post('workspace-visits/check-in', [WorkspaceVisitController::class, 'checkIn'])->middleware('idempotent');
        Route::post('workspace-private-sessions/check-in', [WorkspacePrivateSessionCheckInController::class, 'store'])->middleware('idempotent');
        Route::post('workspace-visits/{visit}/check-out', [WorkspaceVisitController::class, 'checkOut'])->middleware('idempotent');
        Route::post('workspace-visits/{visit}/request-checkout', [WorkspaceVisitController::class, 'requestCheckout']);
        Route::delete('workspace-visits/{visit}/request-checkout', [WorkspaceVisitController::class, 'cancelCheckoutRequest']);
        Route::get('workspace-visits/active', [WorkspaceVisitController::class, 'active']);

        // Buddy sessions (write)
        Route::post('buddy-sessions', [BuddySessionController::class, 'store']);
        Route::post('buddy-sessions/{session}/join', [BuddySessionController::class, 'join']);

        // Profile
        Route::get('profile', [ProfileController::class, 'show']);
        Route::patch('profile', [ProfileController::class, 'update']);

        // Subscriptions
        Route::get('subscriptions/current', [SubscriptionController::class, 'current']);
        Route::post('subscriptions/activate', [SubscriptionController::class, 'activate'])->middleware(['throttle:activation', 'idempotent']);
    });
});
