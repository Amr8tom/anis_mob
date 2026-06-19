<?php

use App\Http\Controllers\Web\AdminAccountingController;
use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AdminPlanCodeController;
use App\Http\Controllers\Web\AdminWorkspaceController;
use App\Http\Controllers\Web\AdminWorkspaceSettlementController;
use App\Http\Controllers\Web\WorkspaceAuthController;
use App\Http\Controllers\Web\WorkspaceClientController;
use App\Http\Controllers\Web\WorkspaceFinancialController;
use App\Http\Controllers\Web\WorkspaceSessionController;
use App\Http\Controllers\Web\WorkspaceSettingsController;
use App\Http\Controllers\Web\WorkspaceVisitController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Serves workspace media straight from the configured disk so images render
// even when the `public/storage` symlink is missing (a common deploy footgun).
// Public, read-only, path-traversal guarded.
Route::get('media/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);

    $disk = Storage::disk((string) config('filesystems.workspace_media_disk'));
    abort_unless($disk->exists($path), 404);

    return $disk->response($path, null, [
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('media.show');

// Anis marketing / onboarding landing page.
Route::get('/landing', function () {
    $plans = Plan::where('is_active', true)->orderBy('price_cents')->get();

    return view('landing', compact('plans'));
})->name('landing');

// Workspace owner portal routes
Route::prefix('workspace')->name('workspace.')->group(function () {
    Route::get('invitations/{token}', [WorkspaceAuthController::class, 'showInvitation'])->name('invitations.show');
    Route::post('invitations/{token}', [WorkspaceAuthController::class, 'acceptInvitation'])->middleware('throttle:auth')->name('invitations.accept');

    // Shown after self-registration while the workspace awaits admin review.
    Route::get('pending', [WorkspaceAuthController::class, 'showPending'])->name('pending');

    // Guest auth routes
    Route::middleware('guest')->group(function () {
        Route::get('register', [WorkspaceAuthController::class, 'showRegister'])->name('register');
        Route::post('register', [WorkspaceAuthController::class, 'register']);
        Route::get('login', [WorkspaceAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [WorkspaceAuthController::class, 'login']);
    });

    // Protected routes for owner
    Route::middleware('workspace.owner')->group(function () {
        Route::post('logout', [WorkspaceAuthController::class, 'logout'])->name('logout');

        // Settings
        Route::get('settings', [WorkspaceSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [WorkspaceSettingsController::class, 'update'])->name('settings.update');

        // Sessions
        Route::resource('sessions', WorkspaceSessionController::class)->except(['show']);

        // Clients
        Route::get('clients', [WorkspaceClientController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}', [WorkspaceClientController::class, 'show'])->name('clients.show');

        // Manual visitor check-in / check-out (owner-registered visitors)
        Route::get('visits', [WorkspaceVisitController::class, 'index'])->name('visits.index');
        Route::post('visits', [WorkspaceVisitController::class, 'store'])->middleware('throttle:auth')->name('visits.store');
        Route::post('visits/clear-recent', [WorkspaceVisitController::class, 'clearRecent'])->name('visits.clear-recent');
        Route::post('visits/{visit}/checkout', [WorkspaceVisitController::class, 'checkOut'])->name('visits.checkout');

        // Financials
        Route::get('financials', [WorkspaceFinancialController::class, 'index'])->name('financials.index');

        // Rooms & reservations (owner-only helper, independent of subscriptions)
        Route::prefix('rooms')->name('rooms.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'storeRoom'])->name('store');
            Route::put('{room}', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'updateRoom'])->name('update');
            Route::post('{room}/deactivate', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'deactivateRoom'])->name('deactivate');
            Route::post('reservations', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'storeReservation'])->name('reservations.store');
            Route::post('reservations/{reservation}/cancel', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'cancelReservation'])->name('reservations.cancel');
            Route::get('clients/{client}', [\App\Http\Controllers\Web\WorkspaceRoomController::class, 'showClient'])->name('clients.show');
        });

        // Special (workspace-scoped) subscriptions
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'index'])->name('index');
            Route::post('plans', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'storePlan'])->name('plans.store');
            Route::put('plans/{plan}', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'updatePlan'])->name('plans.update');
            Route::post('plans/{plan}/deactivate', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'deactivatePlan'])->name('plans.deactivate');
            Route::post('codes', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'generateCodes'])->middleware(['throttle:auth', 'idempotent'])->name('codes.generate');
            Route::post('codes/{code}/revoke', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'revokeCode'])->middleware('throttle:auth')->name('codes.revoke');
            Route::post('assign', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'assignByPhone'])->middleware(['throttle:auth', 'idempotent'])->name('assign');
            Route::post('{subscription}/cancel', [\App\Http\Controllers\Web\WorkspaceSubscriptionController::class, 'cancelSubscription'])->name('cancel');
        });
    });
});

// Admin portal routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:auth');
        Route::get('mfa', [AdminAuthController::class, 'showMfa'])->name('mfa');
        Route::post('mfa', [AdminAuthController::class, 'verifyMfa'])->name('mfa.verify')->middleware('throttle:auth');
    });

    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', AdminDashboardController::class)->middleware('admin.permission:reports.view')->name('dashboard');

        // Workspaces
        Route::get('workspaces', [AdminWorkspaceController::class, 'index'])->middleware('admin.permission:workspaces.view')->name('workspaces.index');
        Route::get('workspaces/create', [AdminWorkspaceController::class, 'create'])->middleware('admin.permission:workspaces.manage')->name('workspaces.create');
        Route::post('workspaces', [AdminWorkspaceController::class, 'store'])->middleware('admin.permission:workspaces.manage')->name('workspaces.store');
        Route::get('workspaces/{workspace}', [AdminWorkspaceController::class, 'show'])->middleware('admin.permission:workspaces.view')->name('workspaces.show');
        Route::post('workspaces/{workspace}/qr/regenerate', [AdminWorkspaceController::class, 'regenerateQr'])->middleware('admin.permission:workspaces.manage')->name('workspaces.qr.regenerate');
        Route::post('workspaces/{workspace}/owner', [AdminWorkspaceController::class, 'changeOwner'])->middleware('admin.permission:workspaces.manage')->name('workspaces.owner.change');
        Route::patch('workspaces/{workspace}/billing', [AdminWorkspaceController::class, 'updateBilling'])->middleware('admin.permission:workspaces.manage')->name('workspaces.billing.update');

        // Lifecycle: registration review (approve / reject) + freeze (suspend) + status.
        Route::post('workspaces/{workspace}/approve', [AdminWorkspaceController::class, 'approve'])->middleware('admin.permission:workspaces.manage')->name('workspaces.approve');
        Route::post('workspaces/{workspace}/reject', [AdminWorkspaceController::class, 'reject'])->middleware('admin.permission:workspaces.manage')->name('workspaces.reject');
        Route::post('workspaces/{workspace}/suspend', [AdminWorkspaceController::class, 'suspend'])->middleware('admin.permission:workspaces.manage')->name('workspaces.suspend');
        Route::post('workspaces/{workspace}/unsuspend', [AdminWorkspaceController::class, 'unsuspend'])->middleware('admin.permission:workspaces.manage')->name('workspaces.unsuspend');
        Route::patch('workspaces/{workspace}/status', [AdminWorkspaceController::class, 'updateStatus'])->middleware('admin.permission:workspaces.manage')->name('workspaces.status.update');
        Route::delete('workspaces/{workspace}', [AdminWorkspaceController::class, 'destroy'])->middleware(['admin.permission:workspaces.delete', 'idempotent'])->name('workspaces.destroy');
        Route::post('workspaces/{workspace}/restore', [AdminWorkspaceController::class, 'restore'])->middleware('admin.permission:workspaces.delete')->name('workspaces.restore')->withTrashed();
        Route::post('workspaces/{workspace}/settlements', [AdminWorkspaceSettlementController::class, 'store'])->middleware(['admin.permission:settlements.create', 'idempotent'])->name('workspaces.settlements.store');
        Route::post('visits/{visit}/corrections', [AdminAccountingController::class, 'correctVisit'])->middleware(['admin.permission:accounting.correct', 'idempotent'])->name('visits.corrections.store');
        Route::post('settlements/{settlement}/reverse', [AdminAccountingController::class, 'reverseSettlement'])->middleware(['admin.permission:settlements.reverse', 'idempotent'])->name('settlements.reverse');
        Route::post('subscriptions/{subscription}/refunds', [AdminAccountingController::class, 'refundSubscription'])->middleware(['admin.permission:accounting.refund', 'idempotent'])->name('subscriptions.refunds.store');

        // Plan Codes
        Route::get('plancodes', [AdminPlanCodeController::class, 'index'])->middleware('admin.permission:codes.view')->name('plancodes.index');
        Route::post('plancodes', [AdminPlanCodeController::class, 'store'])->middleware('admin.permission:codes.manage')->name('plancodes.store');
        Route::post('plancodes/{planCode}/revoke', [AdminPlanCodeController::class, 'revoke'])->middleware('admin.permission:codes.manage')->name('plancodes.revoke');
    });
});
