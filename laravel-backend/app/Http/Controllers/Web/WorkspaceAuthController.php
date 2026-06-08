<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\WorkspacePortal\Actions\RegisterWorkspaceAction;
use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspacePortal\WorkspaceLoginRequest;
use App\Http\Requests\WorkspacePortal\WorkspaceRegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class WorkspaceAuthController extends Controller
{
    public function showRegister(): View
    {
        return view('workspace.auth.register');
    }

    public function register(
        WorkspaceRegisterRequest $request,
        RegisterWorkspaceAction $action
    ): RedirectResponse {
        $workspace = $action->handle(WorkspaceRegistrationData::fromRequest($request));

        // Authenticate the newly created owner
        Auth::login($workspace->owner);

        return redirect()
            ->route('workspace.settings.edit')
            ->with('success', 'تم إنشاء الحساب ومساحة العمل بنجاح!');
    }

    public function showLogin(): View
    {
        return view('workspace.auth.login');
    }

    public function login(WorkspaceLoginRequest $request): RedirectResponse
    {
        $credentials = [
            'phone_number' => $request->string('phone_number')->trim()->value(),
            'password' => $request->string('password')->value(),
        ];

        // 1. Fetch user first to check role
        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if (! $user || $user->role !== UserRole::WORKSPACE_OWNER) {
            return back()
                ->withInput($request->only('phone_number'))
                ->withErrors(['phone_number' => 'هذا الحساب غير مسجل كشريك لدينا أو البيانات المدخلة غير صحيحة.']);
        }

        // 2. Perform credential check
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            return redirect()->intended(route('workspace.settings.edit'));
        }

        return back()
            ->withInput($request->only('phone_number'))
            ->withErrors(['password' => 'كلمة المرور غير صحيحة.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('workspace.login');
    }
}
