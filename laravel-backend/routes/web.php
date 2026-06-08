<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Models\Plan;

// Anis marketing / onboarding landing page.
Route::get('/landing', function () {
    $plans = Plan::where('is_active', true)->orderBy('price_cents')->get();

    return view('landing', compact('plans'));
})->name('landing');

// Workspace owner portal routes
use App\Http\Controllers\Web\WorkspaceAuthController;
use App\Http\Controllers\Web\WorkspaceSettingsController;

Route::prefix('workspace')->name('workspace.')->group(function () {
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
        Route::resource('sessions', \App\Http\Controllers\Web\WorkspaceSessionController::class);

        // Clients
        Route::get('clients', [\App\Http\Controllers\Web\WorkspaceClientController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}', [\App\Http\Controllers\Web\WorkspaceClientController::class, 'show'])->name('clients.show');
    });
});
